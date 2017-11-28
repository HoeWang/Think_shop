<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
    	if (IS_POST) {
    		if (I('username') == 'lzl') {
    			//登录成功
    			$_SESSION['adminInfo'] = ['name'=>'lzl', 'id'=>1];
    			$this->success('登录成功', U('Index/index'));
    		} else {
    			$this->error('用户名或密码错误');
    		}
    	} else {
	        $this->display();
    	}
    }

	//退出
    public function logout()
    {
    	// unset($_SESSION['adminInfo']);
    	session('adminInfo', null);
        $this->redirect('Login/index');
    }
}