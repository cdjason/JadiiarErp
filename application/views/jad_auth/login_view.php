<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Login Jadiiar ERP</title>
<?php $this->load->view('includes/head'); ?>
<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?> 
</head>
<body id="login">
<div id="body_wrap">
 <!-- Header  -->
 <?php $this->load->view('includes/header'); ?>  
 <!-- Demo Navigation 
 <?php $this->load->view('includes/jad_header'); ?>-->
 <!-- Main Content -->
 <div class="content_wrap main_content_bg">
	<div class="content clearfix">
	 <div class="col100">
		<?php if (! empty($message)) { ?><div id="message"><?php echo $message; ?></div><?php } ?>
		<?php echo form_open(current_url(), 'class="parallel"');?>  	
		<fieldset class="w50 parallel_target"><legend>用户登录</legend>
		 <ul>
			<li>
			  <label for="identity">用户名:</label>
			  <input type="text" id="identity" name="login_identity" value="<?php echo set_value('login_identity');?>" />
			</li>
			<li>
			  <label for="password">密码:</label>
				<input type="password" id="password" name="login_password" value="<?php echo set_value('login_password');?>"/>
			</li>
			<li>
				<label for="remember_me">记住账号:</label>
				<input type="checkbox" id="remember_me" name="remember_me" value="1" <?php echo set_checkbox('remember_me', 1); ?>/>
			</li>
			<li>
				<label for="submit"></label>
				<input type="submit" name="login_user" id="submit" value="登陆" class="link_button large"/>
			</li>
			<li>
				<small>注意密码输入的正确性，超过3次无效的密码输入会导致延迟允许登陆的时间</small> 
			</li>
		 </ul>
	  </fieldset>
		<?php echo form_close();?>			
	 </div>
	</div>
 </div>	 
 <!-- Footer -->
 <?php $this->load->view('includes/footer'); ?>     
</div>
</body>
</html>
