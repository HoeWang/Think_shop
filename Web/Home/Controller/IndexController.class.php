<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
     * 数据处理
     */
    public function index()
    {
        
        if(IS_AJAX){
            $code = I('get.code');
            $goods = D('picture');
            
                // $info = $goods->getData($code);
            
            $this->ajaxReturn($code);
        }else{
            $type = D('type');
            $arr = $type->getData();
            
            $this->assign('types',$arr);
            $this->display("Home/index");
        }
        
    }

    /**
     * 每日签到
     * @return [type] [description]
     */
    public function doSign(){

        $sign = M('Sign');
        $user = M('User');
        if(IS_AJAX){
            $map['username'] = ['eq',session('homeInfo.username')]; 
            $infos = $user->where($map)->find();
            
            $where['sname'] = ['eq',session('homeInfo.username')];
            $arr = $sign->where($where)->find();
            // echo '<pre>';
            // print_r($arr);
            if(!$arr){
                $info =[
                    'sname' => session('homeInfo.username'),
                    'signnum' => 1,
                    'signtime' => time(),
                ];
                if($sign->add($info) !==false){
                    $infos['sign'] += 2;
                    $infos['signnum'] +=1;
                    $user->save($infos);
                    $res['status'] = 1;
                    $res['msg'] = "签到成功";
                    
                }else{
                    $res['status'] = 2;
                    $res['msg'] = "签到失败";
                }
            }else{
                
                if(date("Y-m-d",$arr['signtime'])==date("Y-m-d",time())){
                    $res['status'] = 2;
                    $res['msg'] = "今日已签到";
                }else{
                    $arr['signtime'] = time();
                    $arr['signnum'] += 1;
                    if($sign->save($arr)){
                        $infos['sign'] += 2;
                        $infos['signnum'] +=1;
                        $user->save($infos);
                        $res['status'] = 1;
                        $res['msg'] = "签到成功";
                        
                    }else{
                        $res['status'] = 2;
                        $res['msg'] = "签到失败";
                    }
                }
            }
            // dump($res);
                $this->ajaxReturn($res);
        }       
    }





    
}
    