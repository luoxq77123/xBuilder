<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>xBuilder用户登录</title>
<link href="<?php echo $this->webroot;?>themes/css/new_login.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/x-icon" href="<?php echo $this->webroot;?>themes/default/images/favicon.ico" />
<script type="text/javascript">
var notice = '<?php echo $this->Session->flash('flash',array('element'=>''));?>';
if(notice){
	alert(notice);
}
</script>
</head>

<body>
<div class="loginBox">
	<div class="login">
		<ul>
        	<?php
			echo $this->Form->create('User');
			?>
			<li><span>用户名:</span><?php echo $this->Form->input('email', array('label'=>false,'class'=>'userName','div'=>false));?></li>
			<li><span>密　码:</span><?php echo $this->Form->input('password', array('label'=>false,'class'=>'password','div'=>false));?></li>
			<li><span>验证码:</span><?php echo $this->Form->input('authCode', array('label'=>false,'class'=>'yzm','size'=>5,'div'=>false));?><img src="<?php echo $this->Html->url(array('action' => 'captcha'));?>" width="66" height="29" class="yzmImg" /></li>
			<li><span>&nbsp;</span><?php echo $this->Form->input('',array('class'=>'sub','type'=>'submit','label'=>false));?></li>
            <?php
			echo $this->Form->end();
			?>
		</ul>
	</div>
</div>
<div class="bottomInfo">
	索贝数码科技有限公司CopyRight ©2012,All Rights Reserved. 川ICP备12006843号-1 川公网安备11010802008796号
</div>
</body>
</html>