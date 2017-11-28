<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
	/**
	 * 后台首页展示
	 * @return [type] [description]
	 */
    public function index(){
    	
        $this->display('Index/index');
    }
}