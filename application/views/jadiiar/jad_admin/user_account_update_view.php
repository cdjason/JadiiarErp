<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>更新用户账户</title>
	<?php $this->load->view('includes/head'); ?>
  <!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>

<body id="update_user_account">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
	      <h2>Update Account of <?php echo $user['upro_first_name'].' '.$user['upro_last_name']; ?></h2>
				<a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">管理用户账户</a> </br>
				<a href="<?php echo $base_url;?>index.php/jad_auth_admin/change_password/<?php echo $user['upro_id']; ?>" >更改该用户密码</a>

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>

				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>Personal Details</legend>
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
						<legend>Contact Details</legend>
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
						<legend>Login Details</legend>
						<ul>
							<li class="info_req">
								<label for="email_address">Email Address:</label>
								<input type="text" id="email_address" name="update_email_address" value="<?php echo set_value('update_email_address',$user[$this->flexi_auth->db_column('user_acc', 'email')]);?>" class="tooltip_trigger"
									title="Set the users email address that they can use to login with."
								/>
							</li>
							<li class="info_req">
								<label for="username">Username:</label>
								<input type="text" id="username" name="update_username" value="<?php echo set_value('update_username',$user[$this->flexi_auth->db_column('user_acc', 'username')]);?>" class="tooltip_trigger"
									title="Set the users username that they can use to login with."
								/>
							</li>
							<li>
								<label for="group">Group:</label>
								<select id="group" name="update_group" class="tooltip_trigger"
									title="Set the users group, that can define them as an admin, public, moderator etc."
								>
								<?php foreach($groups as $group) { ?>
									<?php $user_group = ($group[$this->flexi_auth->db_column('user_group', 'id')] == $user[$this->flexi_auth->db_column('user_acc', 'group_id')]) ? TRUE : FALSE;?>
									<option value="<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>" <?php echo set_select('update_group', $group[$this->flexi_auth->db_column('user_group', 'id')], $user_group);?>>
										<?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
									</option>
								<?php } ?>
								</select>
							</li>
							<li>
								<label>Privileges:</label>
								<a href="<?php echo $base_url.'index.php/jad_auth_admin/update_user_privileges/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>" class="tooltip_trigger"
									title="Manage a users access privileges.">Manage User Privileges</a>
							</li>
						</ul>
					</fieldset>
					
					<fieldset>
						<legend>Update Account</legend>
						<ul>
							<li>
								<label for="submit">Update Account:</label>
								<input type="submit" name="update_users_account" id="submit" value="Submit" class="link_button large"/>
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