<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	/**
	 * 登录页面
	 * @return [type] [description]
	 */
    public function index(){

    	
    	if(IS_POST){
       	$post = I('post.');
       	// $post['pwd'] = md5($post['pwd']);
       	// dump($post);
       	$user = D('User');
       	$info = D('UserLoginInfo');
       	  
       	//可以看到在Login控制器中,利用了D函数实例化了user这张表，所以课件不需要控制器和model名称以及表名一致，而是只需要model名称和表名一致就可以对这张表增删改查
       	$where['username'] = ['eq',$post['username']];
       	$res = $user->where($where)->getData();
        // dump($post);exit;
       	$result = $info->checkPass($res['id']);
        //当输入次数超过3次的时候才开启验证码验证。
       	if($result >= 3 || $result === false){
				// 检查验证码  
			
			$verify = I('param.verify','');  
			if(!check_verify($verify)){  
			    $this->error("亲，验证码输错了哦！",$this->site_url,3);  
			}  
			
       	}
       	if($res){
       		
       		
       		if(password_verify($post['pwd'],$res['pwd'])){
       			if($res['status'] == 1){
       			
       				if($result === false){
       					$this->error('该用户在半小时内输错密码次数超过5次被稍后再试');
       				}
              if(empty($res['email']) && empty($res['phone'])){
                $this->error("请添加邮箱激活账号",U('Login/addEmail?id='.$res['id']));
              }
       				session('homeInfo',$res);
       				$this->success('登陆成功',U('Index/index'));
       			}else{
       				$this->error('该用户被禁用！');
       			}
       		}else{

       			$ls = $info->recordPassWrongTime($res['id']);
       			

       			$this->error('密码错误');
       		}

       	}else{
       		$this->error('该用户不存在');
       	}
       	

       		/*if($post['pwd'] == $res['pwd']){
       			echo'denglu chenggon ';
       			$_SESSION['adminInfo'] = $res;
       			$this->redirect('Index/index');
       		}else{
       			$this->error('账户或者密码错误!');
       		}*/

       }elseif(IS_AJAX){
       		$post = I('get.');
	       	// $post['pwd'] = md5($post['pwd']);
	       	// dump($post);
	       	$user = D('User');
	       	$info = D('UserLoginInfo');

	       	$where['username'] = ['eq',$post['username']];
	       	$res = $user->where($where)->getData();

	       	$result = $info->checkPass($res['id']);
	       	if($result > 3 || $result === false){
	       		$this->ajaxReturn(1);
	       	}else{
	       		$this->ajaxReturn(2);
	       	}
       }else{
       	
       	$this->display('login');
       }
    }

    /**
     * 注册页面,以及
     */
    public function register(){
    	if(IS_POST){

    		if(I('post.mode') == 'email'){
    
    			$post = I('post.');
    			// $post['username'] = $post['email'];
    			// dump($post);
    			$user = D('User');

    			//默认会传$_POST
            if(empty(I('post.pwd')) && !empty(I('post.pwd2'))){
                $this->error('请输入密码');
            }elseif(!empty(I('post.pwd')) && empty(I('post.pwd2'))){
                $this->error('请输入确认密码');
            }
            	// dump($post);
            //自动验证生成数据
            $data = $user->create($post);

            //dump($data);
            //验证通过后发送激活邮件
            if($data){
           	$data['username'] = $post['email'];
            C('MEMCACHE_HOST','39.106.15.82');
            S(array('type'=>'memcache','expire'=>5000));
            $res = S($data['email'],$data);
            vendor('PHPMailer.class','','.pop3.php');
        	   vendor('PHPMailer.classphpmailer');
        	$str = "<h1>请点击下面的链接进行激活邮箱账号</h1><span style='text-decoration:none;' color='lightblue'><a href='http://39.106.15.82?key={$data['email']}'>完成验证，立即体验购物之旅</a></span>";
        	sendMail($data['email'],'欢迎您使用邮箱注册我站账号',$str);
        	
        	}else{
        		$this->error($user->getError());
        	}
            $this->success("请立即前往{$data['email']}进行激活",U('Login/login'),1000);


    		}else{
    			$user = D('User');
    			$post = I('post.');
    			if(empty(I('post.pwd')) && !empty(I('post.pwd2'))){
               	 	$this->error('请输入密码');
            	}elseif(!empty(I('post.pwd')) && empty(I('post.pwd2'))){
                	$this->error('请输入确认密码');
            	}
            	$data = $user->create($post);

    			// dump($data);
    			if($data){
    				$arr = $user->addUser($data);
    				if($arr){

    					$this->success('请验证邮箱进行激活',U('Login/addEmail?id='.$arr['id']));
    				}

    			}else{
    				$this->error($user->getError());
    			}
    		}

    	}elseif(IS_AJAX){
    		$user = D('User');
    		$data = $user->create($_GET);


    		if($data){
    			$info['status'] = 0;
    			$info['msg'] = "可以注册";	
    		}else{
    			$info['status'] = 1;
    			$info['msg'] = $user->getError();
    		}
    		$this->ajaxReturn($info);
    	}else{
    		$this->display('register');
    	}
    }

    /**
     * 添加邮箱布局与验证
     */
    public function addEmail(){
    	if(IS_AJAX){
        $user = D('User');
        $data = $user->create($_POST);
    	  
        if($data){
          $info['status'] = 1;
          $info['content'] = '此邮箱可以使用';
          
        }else{
          $info['status'] = 2;
          $info['content'] = $user->getError();
          
        }
    	$this->ajaxReturn($info);
    	}elseif(IS_GET){

        $this->assign('id',$_GET['id']);
    	  $this->display('User/email');
    		 
    	}
    }
    //处理添加邮箱
    public function doAddEmail(){
      if(IS_AJAX){
        $user = D('User');
        $data = $user->create($_POST);
        if($data){
        $yan = $this->generate_code();

        vendor('PHPMailer.class','','.pop3.php');
        vendor('PHPMailer.classphpmailer');
        $str = "<h1>请立即前往网页填写以下验证码</h1><br>
        <font size='40'>".$yan."</font>";
        sendMail($data['email'],'欢迎您使用邮箱验证',$str);
        C('MEMCACHE_HOST','39.106.15.82');
        S(array('type'=>'memcache','expire'=>6000));
        $res = S("add".$data['email'],$yan);
        $this->ajaxReturn(1);
        }else{
          $this->ajaxReturn(0);
        }
      }elseif(IS_POST){
        C('MEMCACHE_HOST','39.106.15.82');
        $res = S("add".I('post.email'));
        if($res || $res == I('post.codes')){
          $user = D('User');
          $res = $user->addEmailData(I('post.'));
          if($res){
            
            $this->success('添加邮箱成功',U('Login/index'));
          }
        }else{
          $this->error('验证码错误或者过期了');
        }
      }
    }
    /**
     * 生成四位随机数
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public function generate_code($length = 4) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
        }


    /** 
	 *  
	 * 验证码生成 
	 */  
	public function verify_c(){  
	    $Verify = new \Think\Verify();  
	    $Verify->fontSize = 18;  
	    $Verify->length   = 4;  
	    $Verify->useNoise = false;  
	    $Verify->codeSet = '0123456789';  
	    // $Verify->imageW = 130;  
	    // $Verify->imageH = 50;  
	    // $Verify->expire = 600;  
	    $Verify->entry();  
	}  

  /**
     * 退出登录
     */
    public function logout()
    {
      session('homeInfo', null);
      $this->redirect('Index/index');
    }

}