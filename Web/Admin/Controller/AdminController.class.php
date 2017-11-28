<?php
namespace Admin\Controller;
use Think\Controller;
class adminController extends CommonController {
	/**
	 * 权限管理
	 */
    public function index(){
        $this->display('Admin/index');
    }

    /**
     * 管理员列表
     */
    public function showList(){
    	$this->display('Admin/showList');
    }

    /**
     * 个人信息
     */
    public function info(){
    	$this->display('Admin/info');
    } 
}