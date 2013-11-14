<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>编辑用户组信息</title>
	<?php $this->load->view('includes/head'); ?>
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?>  
</head>

<body id="update_user_group">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">用户组信息一览</a>

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				<?php echo form_open(current_url());	?>  	
					<fieldset>
						<legend>用户组信息</legend>
						<ul>
							<li class="info_req">
								<label for="group">用户组名称:</label>
								<input type="text" id="group" name="update_group_name" value="<?php echo set_value('update_group_name', $group[$this->flexi_auth->db_column('user_group', 'name')]);?>" class="tooltip_trigger"
									title="The name of the user group."/>
							</li>
							<li>
								<label for="description">用户组描述:</label>
								<textarea id="description" name="update_group_description" class="width_400 tooltip_trigger"
									title="A short description of the purpose of the user group."><?php echo set_value('update_group_description', $group[$this->flexi_auth->db_column('user_group', 'description')]);?></textarea>
							</li>
							<li>
								<?php $ugrp_admin = ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ;?>
								<label for="admin">Is Admin Group:</label>
								<input type="checkbox" id="admin" name="update_group_admin" value="1" <?php echo set_checkbox('update_group_admin', 1, $ugrp_admin);?> class="tooltip_trigger"
									title="If checked, the user group is set as an 'Admin' group."/>
							</li>
							<li>
								<label>用户组权限:</label>
								<a href="<?php echo $base_url;?>index.php/jad_auth_admin/update_group_privileges/<?php echo $group['ugrp_id']; ?>">Manage Privileges for this User Group</a>
							</li>
						</ul>
					</fieldset>
									
					<fieldset>
						<legend>操作</legend>
						<ul>
							<li>
								<label>编辑</label>
								<input type="submit" name="update_user_group" id="submit" value="Submit" class="link_button large"/>
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