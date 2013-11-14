<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>更新账户信息</title>
	<?php $this->load->view('includes/head'); ?>
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>
<body id="update_account">
<div id="body_wrap">
<!-- Header -->  
<?php $this->load->view('includes/header'); ?> 
<!-- Demo Navigation -->
<?php $this->load->view('includes/jad_header'); ?> 
<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>编辑帐户信息</h2>
				<a href="<?php echo $base_url;?>index.php/jad_auth_public/change_password">更改密码</a>
			     <?php if (! empty($message)) { ?>
				    <div id="message">
					     <?php echo $message; ?>
				    </div>
			     <?php } ?>
				<?php echo form_open(current_url());	?>  	
					<fieldset>
						<legend>姓名</legend>
						<ul>
							<li class="info_req">
								<label for="first_name">First Name:</label>
								<input type="text" id="first_name" name="update_first_name" value="<?php echo set_value('update_first_name',$user['upro_first_name']);?>"/>
							</li>
							<li class="info_req">
								<label for="last_name">Last Name:</label>
								<input type="text" id="last_name" name="update_last_name" value="<?php echo set_value('update_last_name',$user['upro_last_name']);?>"/>
							</li>
						</ul>
					</fieldset>
					
					<fieldset>
						<legend>联系方式</legend>
						<ul>
							<li>
								<label for="country">国家:</label>
								<input type="text" id="country" name="update_country" value="<?php echo set_value('update_country',$user['upro_country']);?>"/>
							</li>									
							<li>
								<label for="county">省/州:</label>
								<input type="text" id="county" name="update_county" value="<?php echo set_value('update_county',$user['upro_county']);?>"/>
							</li>							
							<li>
								<label for="city">城市:</label>
								<input type="text" id="city" name="update_city" value="<?php echo set_value('update_city',$user['upro_city']);?>"/>
							</li>	
							<li class="info_req">
								<label for="phone_number">电话:</label>
								<input type="text" id="phone_number" name="update_phone_number" value="<?php echo set_value('update_phone_number',$user['upro_phone']);?>"/>
							</li>
              <li>
								<label for="post_code">邮编:</label>
								<input type="text" id="post_code" name="update_post_code" value="<?php echo set_value('update_post_code',$user['upro_post_code']);?>"/>
							</li>							
							<li class="info_req">
								<label for="address_01">地址:</label>
								<textarea id="address_01" name="update_address_01" class="width_400 tooltip_trigger"><?php echo set_value('update_address_01',$user['upro_address']);?></textarea>
							</li>
						</ul>
					</fieldset>
					<fieldset>
						<legend>登录信息</legend>
						<ul>
							<li class="info_req">
								<label>Email:</label>
								<input type="text" id="email" name="update_email" value="<?php echo set_value('update_email',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" class="tooltip_trigger"
									title="Set an email address that can be used to login with."
								/>

							</li>
							<li class="info_req">
								<hr/>
								<label for="username">用户名:</label>
								<input type="text" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="tooltip_trigger"
									title="Set a username that can be used to login with."
								/>
							</li>
							<li>
								<label>密码:</label>
								<a href="<?php echo $base_url;?>index.php/jad_auth_public/change_password">更改密码</a>
							</li>
						</ul>
					</fieldset>
					
					<fieldset>
						<legend>更新帐户信息</legend>
						<ul>

							<li>
								<hr/>
								<label>更新帐户:</label>
								<input type="submit" name="update_account" id="submit" value="提交" class="link_button large"/>
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
