<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>增加分店信息</title>
<?php $this->load->view('includes/head'); ?>  
</head>

<body id="insert_branch">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>分店信息</legend>
						<ul>
							<li>
								<label for="branch_name">分店名称:</label>
								<input type="text" id="branch_name" name="register_branch_name" value="<?php echo set_value('register_branch_name');?>"/>
							</li>
							<li>
								<label for="branch_address">分店地址:</label>
								<input type="text" id="branch_address" name="register_branch_address" value="<?php echo set_value('register_branch_address');?>"/>
							</li>
							<li>
								<label for="branch_phone">分店电话:</label>
								<input type="text" id="branch_phone" name="register_branch_phone" value="<?php echo set_value('register_branch_phone');?>"/>
							</li>
							<li>
								<label for="branch_zipcode">邮编:</label>
								<input type="text" id="branch_zipcode" name="register_branch_zipcode" value="<?php echo set_value('register_branch_zipcode');?>"/>
							</li>
							
							<li>
								<label for="upro_id">负责人:</label>
								<select id="upro_id" name="register_upro_id">
								<?php foreach($managers as $manager) { ?>
									<option value="<?php echo $manager['upro_id'];?>">
										<?php echo $manager['upro_first_name'].' '.$manager['upro_last_name'];?>
									</option>
								<?php } ?>
								</select>
							</li>
						
						</ul>
					</fieldset>
					
					<fieldset>
						<legend>操作</legend>
						<ul>
							<li>
								<hr/>
								<label>增加:</label>
								<input type="submit" name="add_branch" id="submit" value="Submit" class="link_button large"/>
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
	<!-- Scripts -->  
  <?php $this->load->view('includes/scripts'); ?>


</body>
</html>