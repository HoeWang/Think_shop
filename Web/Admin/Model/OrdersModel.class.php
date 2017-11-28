<?php
namespace Admin\Model;
use Think\Model;

class OrdersModel extends Model
{   

	public function getData(){
		$arr = $this->select();
		$info = ['','待付款','代发货','待收货','已完成','无效订单'];
		foreach($arr as $k=>$v){
			$arr[$k]['status'] = $info[$v['status']];
		}
		return $arr;
	}
}