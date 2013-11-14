<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>编辑分店信息</title>
	<?php $this->load->view('includes/head'); ?> 

</head>

<body id="update_branch">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
	
				<h2>Update branch of No.<?php echo $branch['branch_name']; ?></h2>
				<a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_branches">分店信息一览</a>

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
								<input type="text" id="branch_name" name="update_branch_name" value="<?php echo set_value('update_branch_name',$branch['branch_name']);?>"/>
							</li>
							<li>
								<label for="branch_address">分店地址:</label>
								<textarea id="branch_address" name="update_branch_address" class="width_400 tooltip_trigger"><?php echo set_value('update_branch_address',$branch['branch_address']);?></textarea>
							</li>
							<li>
								<label for="branch_phone">分店电话:</label>
								<input type="text" id="branch_phone" name="update_branch_phone" value="<?php echo set_value('update_branch_phone',$branch['branch_phone']);?>"/>
							</li>
							<li>
								<label for="branch_zipcode">分店邮编:</label>
								<input type="text" id="branch_zipcode" name="update_branch_zipcode" value="<?php echo set_value('update_branch_zipcode',$branch['branch_zipcode']);?>"/>
							</li>
							<li>
								<label for="upro_id">负责人:</label>
								<select id="upro_id" name="update_upro_id" class="tooltip_trigger">
								<?php foreach($managers as $manager) { ?>
									<?php $branch_manager = ($manager['upro_id'] == $branch['upro_id']) ? TRUE : FALSE;?>
									<option value="<?php echo $manager['upro_id'];?>" <?php echo set_select('update_manager', $manager['upro_id'], $branch_manager);?>>
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
								<label>编辑:</label>
								<input type="submit" name="update_branch" id="submit" value="Submit" class="link_button large"/>
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