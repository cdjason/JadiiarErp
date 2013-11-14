<!doctype html> 
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Password Update Jadiiar</title>
	<?php $this->load->view('includes/head'); ?>
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>
<body id="change_password">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>更改密码</h2>
				<a href="<?php echo $base_url;?>index.php/jad_auth_public/update_account">编辑帐户信息</a>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				<?php echo form_open(current_url());	?>  	
					<div class="w100 frame">
						<ul>
							<li>
								<small>
									<strong>密码长度至少为<?php echo $this->flexi_auth->min_password_length(); ?>个字符</strong><br/>
									只允许半角字符的数字字母以及下划线横杆逗号句号
								</small>
							</li>
							<li class="info_req">
								<label for="current_password">旧密码:</label>
								<input type="password" id="current_password" name="current_password" value="<?php echo set_value('current_password');?>"/>
							</li>
							<li class="info_req">
								<label for="new_password">新密码:</label>
								<input type="password" id="new_password" name="new_password" value="<?php echo set_value('new_password');?>"/>
							</li>
							<li class="info_req">
								<label for="confirm_new_password">确认新密码:</label>
								<input type="password" id="confirm_new_password" name="confirm_new_password" value="<?php echo set_value('confirm_new_password');?>"/>
							</li>
							<li>
								<label for="submit">更改密码:</label>
								<input type="submit" name="change_password" id="submit" value="提交" class="link_button large"/>
							</li>
						</ul>
					</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>	
	
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
</div>



</body>
</html>
