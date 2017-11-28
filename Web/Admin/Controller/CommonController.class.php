<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        if (empty($_SESSION['adminInfo'])) {
        	$this->redirect('Login/index');
        }
    }

    public function _empty()
    {
    	echo '404';
    }
}