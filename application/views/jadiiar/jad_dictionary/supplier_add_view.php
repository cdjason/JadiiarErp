<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>增加供应商</title>
<?php $this->load->view('includes/head'); ?> 
</head>

<body id="insert_supplier">

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
        <h2>增加供应商基本信息</h2>	
				<?php echo form_open(current_url());?>
					<fieldset>
						<legend>基本信息</legend>
						<ul>
							<li>
								<label for="suppl_brand">品牌:</label>
								<input type="text" id="suppl_brand" name="register_suppl_brand" value="<?php echo set_value('register_suppl_brand');?>"/>
							</li>
							<li>
								<label for="suppl_alias">别名:</label>
								<input type="text" id="suppl_alias" name="register_suppl_alias" value="<?php echo set_value('register_suppl_alias');?>"/>
							</li>
							<li>
								<label for="suppl_location">位置:</label>
								<textarea id="suppl_location" name="register_suppl_location" class="width_400 tooltip_trigger"
								><?php echo set_value('register_suppl_location');?></textarea>	
							</li>
							<li>
								<label for="suppl_phone">电话:</label>
								<input type="text" id="suppl_phone" name="register_suppl_phone" value="<?php echo set_value('register_suppl_phone');?>"/>
							</li>
							<li>
								<label for="suppl_collaborate">合作店员:</label>
								<input type="text" id="suppl_collaborate" name="register_suppl_collaborate" value="<?php echo set_value('register_suppl_collaborate');?>"/>
							</li>
							<li>
								<label for="suppl_collaphone">联系方式:</label>
								<input type="text" id="suppl_collaphone" name="register_suppl_collaphone" value="<?php echo set_value('register_suppl_collaphone');?>"/>
							</li>
							<li>
							<label for="suppl_taxrebate">能够退税:</label>
							<input type="checkbox" id="suppl_taxrebate" name="rebate_status" value="1" />
							</li>
  						<li>
							<label for="suppl_hkmail">能够邮寄香港:</label>
							<input type="checkbox" id="suppl_hkmail" name="mailhk_status" value="1"  />
							</li>
							<li>
								<label for="suppl_note">备注:</label>
								<textarea id="suppl_note" name="register_suppl_note" class="width_400 tooltip_trigger"
								><?php echo set_value('register_suppl_note');?></textarea>
							</li>
							</li>							

							<li>
								<hr/>
								<label for="submit">提交:</label>
								<input type="submit" name="add_supplier" id="submit" value="Submit" class="link_button large"/>
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