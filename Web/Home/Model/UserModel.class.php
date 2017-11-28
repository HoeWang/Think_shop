<?php
namespace Home\Model;

class UserModel extends \Think\Model
{
	/**
	 * 开启自动验证
	 * @var [type]
	 */
	protected $_validate = [
	['username','','用户名已存在',0,'unique'],
	['username', 'require', '请输入用户名',0],
	['username',"/^[\w\x{4e00}-\x{9fa5}]{3,18}$/u",'请输入正确格式的用户名',2],
	['pwd', 'require', '请输入密码'],
	['pwd2', 'require', '请输入确认密码'],
	['pwd','/^(?![0-9]+$)(?![a-zA-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-zA-Z]|[0-9]){6,18}$/','请输入由数字字母符号两种混合6位以上的密码',2],
	['pwd2','pwd','两次密码不一致',2,'confirm'],

	['email', 'require', '请输入邮箱'],
	['email',"/^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/",'请输入正确格式的邮箱',2],	
	['email','','该邮箱已被注册',0,'unique'],

	['phone','','手机已存在',0,'unique'],
	['phone', 'require', '请输入手机号',0],
	['phone',"/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/",'请输入正确的手机',2],

	['code', 'require', '请输入验证码',0],
	
	];

	/**
	 * 自动生成
	 * @var [type]
	 */
	protected $_auto = [
		['pwd','password_hash', 1 ,'function',[PASSWORD_DEFAULT]],
		['addtime', 'time', 1 , 'function'],
	];


	/**
	 * 添加用户处理数据
	 */
	public function addUser($data){
		// dump($data);
		if($arr = $this->filter('strip_tags')->add($data)){
			$arr = $this->find($arr);
			return $arr;
		}else{
			return false;
		}
	}

	/**
	 * 登录用户信息处理
	 */
	public function getData(){
		$arr = $this->find();
		$sex = ['', '男', '女', '保密'];
		




		
		if($arr){

			$arr['sex'] = $sex[$arr['sex']];

			return $arr;
		}else{
			return false;
		}

		
	}

	/**
	 * 添加邮箱处理
	 */
	public function addEmailData($info){
		// dump($info);
		// dump('123');
		$res = $this->save($info);
		return $res;
	}
	
}