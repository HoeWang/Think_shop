<?php
namespace Admin\Model;

class UserModel extends \Think\Model
{
	public function getData()
	{
		$arr = $this->select();

		$sex = ['', '男', '女', '未知'];
		$status = ['','正常','禁用'];
		//在返回之前处理数据
		foreach ($arr as $k=>$v) {
			$arr[$k]['sex'] = $sex[ $v['sex'] ];
			$arr[$k]['status'] = $status[$v['status']];
			$arr[$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
		}
		return $arr;
	}

	public function addData($list)
	{
		$res = $this->add($list);
		return $res;
	}

	// public function findData($uname='')
	// {

	// 	$res = $this->where("username='{$uname}'")->select();
	// 	if(empty($res)){
	// 	return 0;
	// 	}else{
	// 		return $res;
	// 	}
	// }

	// public function delData($id)
	// {
	// 	$res = $this->delete($id);
	// 	return $res;
	// }	


	// public function upFind($id)
	// {
	// 	$res = $this->find($id);
	// 	return $res;
	// }
	/**
	 * 用来改变状态
	 * @param  [type] $list [description]
	 * @return [type]       [description]
	 */
	public function changeData($list)
	{
		if($list['status'] == "正常"){
			$list['status'] = 2;
			
		}else{
			$list['status'] = 1;
			
		}
		$arr = $this->where('id='.$list['id'])->field('status')->save($list);
		return $arr;
	}
}