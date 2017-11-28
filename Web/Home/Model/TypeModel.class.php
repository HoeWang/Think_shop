<?php
namespace Home\Model;
class TypeModel extends \Think\Model
{
	public function getData(){
		$arr = $this->select();
		return $arr;
	}
}