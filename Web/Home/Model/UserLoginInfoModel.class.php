<?php
namespace Home\Model;
use Think\Model;
class UserLoginInfoModel extends Model
{
	
	/**
	 * 检查用户最近30分钟密码错误次数
	 */
	public function checkPass($uid){
		$min = 30;
		$wTime = 5;
		$time = time();
		$prevTime = time() - $min*60;
		$ip = get_client_ip();
		$where['uid'] = ['eq',$uid];
		$where['pass_wrong_time_status'] = ['eq',2];
		$where['logintime'] = ['between',[$prevTime,$time]];
		$where['ipaddr'] = ['eq',$ip];
		

		$res = $this->where($where)->select();
		$wrongTime = count($res);
		if( $wrongTime > $wTime - 1){
			return false;
		}

		return $wrongTime;
	}

	/**
	 * 记录密码错误次数
	 */
	public function recordPassWrongTime($uid){
		$ip = get_client_ip();
		$arr['ipaddr'] = $ip;
		$arr['logintime'] = time();
		$arr['uid'] = $uid;
		$arr['pass_wrong_time_status'] = 2;


		$res = $this->add($arr);
		return $res;
	}

	/**
	 * 添加邮箱
	 */
	public function addEmailData(){
		
	}




}