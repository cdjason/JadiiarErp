<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>编辑供应商信息</title>
<?php $this->load->view('includes/head'); ?> 
</head>

<body id="update_supplier">

<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_suppliers">供应商信息一览</a>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>基本信息更新</legend>
						<ul>
							<li class="info_req">
								<label for="suppl_brand">品牌:</label>
								<input type="text" id="suppl_brand" name="update_suppl_brand" value="<?php echo set_value('update_suppl_brand',$supplier['suppl_brand']);?>"/>
							</li>
							<li class="info_req">
								<label for="suppl_alias">别名:</label>
								<input type="text" id="suppl_alias" name="update_suppl_alias" value="<?php echo set_value('update_suppl_alias',$supplier['suppl_alias']);?>"/>
							</li>
							<li class="info_req">
								<label for="suppl_location">位置:</label>
								<textarea id="suppl_location" name="update_suppl_location" class="width_400 tooltip_trigger"
								><?php echo set_value('update_suppl_location',$supplier['suppl_location']);?></textarea>							
							</li>
							<li class="info_req">
								<label for="suppl_phone">电话:</label>
								<input type="text" id="suppl_phone" name="update_suppl_phone" value="<?php echo set_value('update_suppl_phone',$supplier['suppl_phone']);?>"/>
							</li>
							<li>
								<label for="suppl_collaborate">合作店员:</label>
								<input type="text" id="suppl_collaborate" name="update_suppl_collaborate" value="<?php echo set_value('update_suppl_collaborate',$supplier['suppl_collaborate']);?>"/>
							</li>
							<li>
								<label for="suppl_collaphone">联系方式:</label>
								<input type="text" id="suppl_collaphone" name="update_suppl_collaphone" value="<?php echo set_value('update_suppl_collaphone',$supplier['suppl_collaphone']);?>"/>
							</li>
							<li>
							<label for="suppl_taxrebate">能够退税:</label>
							<input type="checkbox" id="suppl_taxrebate" name="rebate_status" value="1" <?php echo ($supplier['suppl_taxrebate'] == 1) ? 'checked="checked"' : "";?>/>
							</li>
  						<li>
							<label for="suppl_hkmail">能否邮寄香港:</label>
							<input type="checkbox" id="suppl_hkmail" name="mailhk_status" value="1" <?php echo ($supplier['suppl_hkmail'] == 1) ? 'checked="checked"' : "";?>/>
							</li>
							<li>
								<label for="suppl_note">备注:</label>
								<textarea id="suppl_note" name="update_suppl_note" class="width_400 tooltip_trigger"
								><?php echo set_value('update_suppl_note',$supplier['suppl_note']);?></textarea>
							</li>													

							<li>
								<label for="submit">更新信息:</label>
								<input type="submit" name="update_supplier" id="submit" value="Submit" class="link_button large"/>
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