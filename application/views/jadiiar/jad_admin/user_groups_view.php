<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>用户组信息维护</title>
	<?php $this->load->view('includes/head'); ?>
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>

<body id="manage_user_groups">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<a href="<?php echo $base_url;?>index.php/jad_auth_admin/insert_user_group">添加新用户组</a>

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>

				<?php echo form_open(current_url());	?>  	
					<table>
						<thead>
							<tr>
								<th class="spacer_150 tooltip_trigger" 
									title="The user group name.">
									用户组
								</th>
								<th class="tooltip_trigger" 
									title="A short description of the purpose of the user group.">
									描述
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger" 
									title="Indicates whether the group is considered an 'Admin' group.<br/> Note: Privileges can still be set seperately.">
									是否管理员组
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger"
									title="Manage the access privileges of user groups.">
									权限
								</th>
								<th class="spacer_100 align_ctr tooltip_trigger" 
									title="If checked, the row will be deleted upon the form being updated.">
									删除
								</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($user_groups as $group) { ?>
							<tr>
								<td>
									<a href="<?php echo $base_url;?>index.php/jad_auth_admin/update_user_group/<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>">
										<?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
									</a>
								</td>
								<td><?php echo $group[$this->flexi_auth->db_column('user_group', 'description')];?></td>
								<td class="align_ctr"><?php echo ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ? "Yes" : "No";?></td>
								<td class="align_ctr">
									<a href="<?php echo $base_url.'index.php/jad_auth_admin/update_group_privileges/'.$group[$this->flexi_auth->db_column('user_group', 'id')];?>">编辑</a>
								</td>
								<td class="align_ctr">
								<?php if ($this->flexi_auth->is_privileged('Delete User Groups')) { ?>
									<input type="checkbox" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="1"/>
								<?php } else { ?>
									<input type="checkbox" disabled="disabled"/>
									<small>Not Privileged</small>
									<input type="hidden" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="0"/>
								<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<td colspan="5">
								<?php $disable = (! $this->flexi_auth->is_privileged('Update User Groups') && ! $this->flexi_auth->is_privileged('Delete User Groups')) ? 'disabled="disabled"' : NULL;?>
								<input type="submit" name="submit" value="删除选中的用户组" class="link_button large" <?php echo $disable; ?>/>
							</td>
						</tfoot>
					</table>
					
				<?php echo form_close();?>
			</div>
		</div>
	</div>	
	
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
</div>



</body>
</html>
