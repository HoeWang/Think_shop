<?php
namespace Admin\Controller;
use Think\Controller;
class OrderController extends CommonController 
{
	/**
	 * 订单列表
	 */
	public function OrderForm(){
		$orders = D('Orders');
		$orders = D('Orders');
    	$map = null;
		
		if(I('get.uid') != null){
			$map['uid'] = ['eq',I('get.uid')];
		}
		if(I('get.status') != null){
			$map['status'] = ['eq',I('get.status')];
		}
		
			$count = $orders->where($map)->count();
		

		$page = new \Think\Page($count,5);
		
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$btn = $page->show();
		// var_dump($btn);
		$arr = $orders->where($map)->limit($page->firstRow,$page->listRows)->order('id desc')->getData();

		if(IS_AJAX){
			$this->assign('list',$arr);
			$this->assign('btn',$btn);
			$html = $this->fetch('Order/ajaxPage');
			$this->ajaxReturn($html);
		}
		$this->assign('count',$count);
		$this->assign('list',$arr);
		$this->assign('btn',$btn);

        
		$this->display('Order/Orderform');
	}
}



