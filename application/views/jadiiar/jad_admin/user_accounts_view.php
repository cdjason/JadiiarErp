<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>用户信息维护</title>
	<?php $this->load->view('includes/head'); ?> 
  <!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>

<body id="manage_users">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 
	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>用户信息一览</h2>

			     <?php if (! empty($message)) { ?>
				     <div id="message">
					     <?php echo $message; ?>
				     </div>
			     <?php } ?>
				
				<?php echo form_open(current_url());	?>
					<fieldset>
						<legend>搜索</legend>
						
						<label for="search">搜索用户：</label>
						<input type="text" id="search" name="search_query" value="<?php echo set_value('search_users',$search_query);?>" 
							title="This searches for users by email, first name and last name."
						/>
						<input type="submit" name="search_users" value="搜索" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_auth_admin/manage_user_accounts" class="link_button grey">重置</a>
					</fieldset>
				<?php echo form_close();?>	
	
				<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th class="spacer_200">Email</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th class="spacer_100 align_ctr tooltip_trigger"
									title="Indicates the user group the user belongs to.">
									用户组
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger"
									title="Manage the access privileges of users.">
									用户权限
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger"
									title="If checked, the users account will be locked and they will not be able to login.">
									吊销帐户
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger" 
									title="If checked, the row will be deleted upon the form being updated.">
									删除帐户
								</th>
							</tr>
						</thead>
						<?php if (!empty($users)) { ?>
						<tbody>
							<?php foreach ($users as $user) { ?>
							<tr>
								<td>
									<a href="<?php echo $base_url.'index.php/jad_auth_admin/update_user_account/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>">
										<?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')];?>
									</a>
								</td>
								<td>
									<?php echo $user['upro_first_name'];?>
								</td>
								<td>
									<?php echo $user['upro_last_name'];?>
								</td>
								<td class="align_ctr">
									<?php echo $user[$this->flexi_auth->db_column('user_group', 'name')];?>
								</td>
								<td class="align_ctr">
									<a href="<?php echo $base_url.'index.php/jad_auth_admin/update_user_privileges/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>">更改</a>
								</td>
								<td class="align_ctr">
									<input type="hidden" name="current_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="<?php echo $user[$this->flexi_auth->db_column('user_acc', 'suspend')];?>"/>
									<!-- A hidden 'suspend_status[]' input is included to detect unchecked checkboxes on submit -->
									<input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
								
								<?php if ($this->flexi_auth->is_privileged('Update Users')) { ?>
									<input type="checkbox" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1" <?php echo ($user[$this->flexi_auth->db_column('user_acc', 'suspend')] == 1) ? 'checked="checked"' : "";?>/>
								<?php } else { ?>
									<input type="checkbox" disabled="disabled"/>
									<small>Not Privileged</small>
									<input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
								<?php } ?>
								</td>
								<td class="align_ctr">
								<?php if ($this->flexi_auth->is_privileged('Delete Users')) { ?>
									<input type="checkbox" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1"/>
								<?php } else { ?>
									<input type="checkbox" disabled="disabled"/>
									<small>Not Privileged</small>
									<input type="hidden" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
								<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7">
									<?php $disable = (! $this->flexi_auth->is_privileged('Update Users') && ! $this->flexi_auth->is_privileged('Delete Users')) ? 'disabled="disabled"' : NULL;?>
									<input type="submit" name="update_users" value="更改/删除用户" class="link_button large" <?php echo $disable; ?>/>
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="7" class="highlight_red">
									No users are available.
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>
					
				<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>Pagination: <?php echo $pagination['total_users'];?> users match your search</p>
						<p>Links: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>
					
				<?php echo form_close();?>
        <a href="<?php echo $base_url; ?>index.php/jad_auth_admin/add_user_account" >添加用户</a>
			</div>
		</div>
	</div>	
	
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
</div>




</body>
</html>
