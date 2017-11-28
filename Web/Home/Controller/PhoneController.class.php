<?php
namespace Home\Controller;
use Think\Controller;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

class PhoneController extends Controller {
	
	/**
	 * 验证手机号格式
	 */
	public function addPhone(){
		if(IS_AJAX){
			$user = D('User');
			$arr  = I('get.');
			$data = $user->create($arr);
			if($data){
				$info['status'] = 1;
				$info['msg'] = '该手机可以注册';
			}else{
				$info['status'] = 2;
				$info['msg'] = $user->getError();
			}
			$this->ajaxReturn($info);

		}
			
	}

	public function doAddPhone(){
    	
    	if(IS_AJAX){
			$phone = I('get.phone');
			$code = I('get.code');
        	$res = $this->send_phone($phone,$code);
			$this->ajaxReturn($res);
		}else{
			$user = D('User');
			$data = $user->create();
			
			if($data){
				$data['username'] = $data['phone'];
				unset($data['code']);
				$info = $user->addUser($data);
				if($info){
					
					
					$this->success('注册成功',U('Index/index'));
				}else{
					$this->error('失败');
				}
			}else{
				$this->error($user->getError());
			}
			 
		}
	
        // phpinfo();
    }

    

    /**
     * 生成短信验证码
     * @param  integer $length [验证码长度]
     */
    public function createSMSCode($length = 4){
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }


    /**
     * 发送验证码
     * @param  [integer] $phone [手机号]
     */
    public function send_phone($phone,$codes){
        // $code=$this->createSMSCode($length = 4);
    	$code = $codes;
        require_once  './Api/dysms/vendor/autoload.php';    //此处为你放置API的路径
        Config::load();             //加载区域结点配置

        $accessKeyId = 'LTAIdtlGbIUdwQvO';
        $accessKeySecret = '3fgMsEdRMSIiWHy0CA85ubY1aZ2MqM';
        $templateCode = '758ca9c896ae4504b34ca9b25e563ee5';   //短信模板ID

        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";

        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";

        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);

        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称
        $request->setSignName("lamp的商城注册");

        // 必填，设置模板CODE
        $request->setTemplateCode("SMS_113095098");

        $smsData = array('code'=>$code);    //所使用的模板若有变量 在这里填入变量的值  我的变量名为username此处也为username

        //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParam(json_encode($smsData));

        //发起访问请求
        $acsResponse = $acsClient -> getAcsResponse($request);
        //返回请求结果
        $result = json_decode(json_encode($acsResponse), true);
        $resp = $result['Code'];
        $info = $this->sendMsgResult($resp,$phone,$code);
        return $info;
    }


    /**
     * 验证手机号是否发送成功  前端用ajax，发送成功则提示倒计时，如50秒后可以重新发送
     * @param  [json] $resp  [发送结果]
     * @param  [type] $phone [手机号]
     * @param  [type] $code  [验证码]
     * @return [type]        [description]
     */
    private function sendMsgResult($resp,$phone,$code){
        if ($resp == "OK") {
            $data['phone']=$phone;
            $data['code']=$code;
            $data['send_time']=time();
            $result= 1;
            if($result){
                $data['msg']="发送成功";
            }else{
                $data['msg']="发送失败";
            }
        } else{
            $data['msg']="发送失败";
        }
        return $data;
    }


     /**
     * 验证验证码是否在可用时间
    *  @param  [json] $nowTimeStr  当前时间
     * @param  [type] $smsCodeTimeStr 发送出去的时间
     */
    public function checkTime ($nowTimeStr,$smsCodeTimeStr) {
        $time = $nowTimeStr - $smsCodeTimeStr;
        if ($time>60) {
            return false;
        }else{
            return true;
        }
    }






}





























