<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model
{

	public function getData($i){
		$arr = $this->select();
		return $arr;
	}	
}