<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>增加新的权限</title>
	<?php $this->load->view('includes/head'); ?> 
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?> 
</head>
<body id="insert_privilege">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 
	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_privileges">权限一览</a>

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				<?php echo form_open(current_url());	?>  	
					<fieldset>
						<legend>权限信息</legend>
						<ul>
							<li class="info_req">
								<label for="privilege">权限名称:</label>
								<input type="text" id="privilege" name="insert_privilege_name" value="<?php echo set_value('insert_privilege_name');?>" class="tooltip_trigger"
									title="The name of the privilege."/>
							</li>
							<li>
								<label for="description">权限描述:</label>
								<textarea id="description" name="insert_privilege_description" class="width_400 tooltip_trigger"
									title="A short description of the purpose of the privilege."><?php echo set_value('insert_privilege_description');?></textarea>
							</li>
						</ul>
					</fieldset>

					<fieldset>
						<legend>操作</legend>
						<ul>
							<li>
								<label>添加:</label>
								<input type="submit" name="insert_privilege" id="submit" value="Submit" class="link_button large"/>
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