<?php

/** 
 * 验证码检查 
 */  
function check_verify($code, $id = ""){  
    $verify = new \Think\Verify();  
    return $verify->check($code, $id);  
}  



/**
  * 邮件发送函数
  */
function sendMail($to, $subject, $content) {
	//导入vender\PHPMailer\classphpmailer.php
	//注意：用vender函数导入的是.php的文件！！！！
	Vendor('PHPMailer.classphpmailer');
	$mail = new \PHPMailer(); /*实例化*/
	$mail->IsSMTP(); /*启用SMTP*/
	$mail->Host 		=	C('MAIL_HOST'); /*smtp服务器的名称*/

	$mail->SMTPDebug 	=	C('MAIL_DEBUG'); /*开启调试模式，显示信息*/
	$mail->Port 		=	C('MAIL_PORT'); /*smtp服务器的端口号*/
	$mail->SMTPSecure 	=	C('MAIL_SECURE'); /*注意：要开启PHP中的openssl扩展,smtp服务器的验证方式*/

	$mail->SMTPAuth 	= 	C('MAIL_SMTPAUTH'); /*启用smtp认证*/
	$mail->Username 	= 	C('MAIL_USERNAME'); /*你的邮箱名*/
	$mail->Password 	= 	C('MAIL_PASSWORD') ; /*邮箱密码*/
	$mail->From 		= 	C('MAIL_FROM'); /*发件人地址（也就是你的邮箱地址）*/
	$mail->FromName 	= 	C('MAIL_FROMNAME'); /*发件人姓名*/
	$mail->AddAddress($to,"name");
	$mail->WordWrap 	= 	50; /*设置每行字符长度*/
	$mail->IsHTML(C('MAIL_ISHTML')); /* 是否HTML格式邮件*/
	$mail->CharSet 		=	C('MAIL_CHARSET'); /*设置邮件编码*/
	$mail->Subject 		=	$subject; /*邮件主题*/
	$mail->Body 		= 	$content; /*邮件内容*/
	$mail->AltBody 		= 	"This is the body in plain text for non-HTML mail clients"; /*邮件正文不支持HTML的备用显示*/
	if($mail->Send()) {
		return "ok";
	} else {
		return "邮件发送失败: " . $mail->ErrorInfo;
	}
}


function sendSms($phone,$code){
   Vendor('Alisms.Core.Config');
   //use Aliyun\Core\Profile\DefaultProfile;
   Vendor('Alisms.Core.Profile.DefaultProfile');
   //use Aliyun\Core\DefaultAcsClient;
   Vendor('Alisms.Core.DefaultAcsClient');
   //use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
   Vendor('Alisms.Api.Sms.Request.V20170525.SendSmsRequest');
   //use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
   Vendor('Alisms.Api.Sms.Request.V20170525.QuerySendDetailsRequest');
   // 加载区域结点配置
   \Aliyun\Core\Config::load();
   // 初始化用户Profile实例
   $profile = \Aliyun\Core\Profile\DefaultProfile::getProfile(C('ALI_SMS.REGION'), C('ALI_SMS.KEY_ID'), C('ALI_SMS.KEY_SECRET'));
   // 增加服务结点
   \Aliyun\Core\Profile\DefaultProfile::addEndpoint(C('ALI_SMS.END_POINT_NAME'), C('ALI_SMS.REGION'), C('ALI_SMS.PRODUCT'), C('ALI_SMS.DOMAIN'));
   // 初始化AcsClient用于发起请求
   $acsClient = new \Aliyun\Core\DefaultAcsClient($profile);
   // 初始化SendSmsRequest实例用于设置发送短信的参数
   $request = new \Aliyun\Api\Sms\Request\V20170525\SendSmsRequest();
   // 必填，设置雉短信接收号码
   $request->setPhoneNumbers($phone);
   // 必填，设置签名名称
   $request->setSignName('短信验证');
   // 必填，设置模板CODE
   $request->setTemplateCode('SMS_113095098');
   $params = array(
     'code' => $code
   );
   // 可选，设置模板参数
   $request->setTemplateParam(json_encode($params));
   // 可选，设置流水号
   //if($outId) {
   //    $request->setOutId($outId);
   //}
   // 发起访问请求
   $acsResponse = $acsClient->getAcsResponse($request);
   // 打印请求结果
   // var_dump($acsResponse);
   return $acsResponse;
}