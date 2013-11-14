<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>商品购买信息</title>
<?php $this->load->view('includes/head'); ?>
</head>

<body>

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
        <h2>提交商品购买信息</h2>
        
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
							
						<input type="hidden" id="mission_id" name="get_mission_id" value="<?php echo $missionId;?>"/>
							<li>
								<label for="merch_id">商品序列号:</label>
								<label for="merch_id"><?php echo $merch_id;?></label>
								<input type="hidden" id="merch_id" name="add_merch_id" value="<?php echo $merch_id;?>" />
								<input type="hidden" id="purchase_id" name="add_purchase_id" value="<?php echo set_value('add_purchase_id',$pur_id);?>" />
							</li>
							<li>
								<label for="merch_price">价格:</label>
								<input type="text" id="merch_price" name="add_merch_price" value="<?php echo set_value('add_merch_price');?>"/>
								<label for="merch_unit">货币单位:
								<select id="merch_unit" name="select_merch_unit" >
								<?php foreach($monetary_unit as $mu) { ?>
									<option value="<?php echo $mu[1];?>">
										<?php echo $mu[0];?>
									</option>
								<?php } ?>
								</select>
							  </label>
							</li>
							<li>
								<label for="suppl_id">供应商:</label>
								<select id="suppl_id" name="select_suppl_id">
								<?php foreach($suppliers as $supplier) { ?>
									<option value="<?php echo $supplier['suppl_id'];?>">
										<?php echo $supplier['suppl_alias'];?>
									</option>
								<?php } ?>
								</select>
							</li>
							<li >
								<label for="get_type">取货方式:</label>
								<select id="get_type" name="select_get_type" onchange="selectChange(this);">
									<option value="1" checked>直接取货</option>
								  <option value="2">邮寄取货</option>
								</select>
							</li>
							<li id="li_mail_type" style="display:none">
								<label for="mail_type">邮寄对象:</label>
								<select id="mail_type" name="select_mail_type">
									<option value="1" checked>当前买手</option>
									<?php if(!$this->jad_global_model->is_single_line($missionId)){ ?>
								  <option value="2">分店接收</option>
								  <?php } ?>
								</select>
							</li>
							<li>
								<hr/>
								<label for="submit">提交:</label>
								<input type="submit" name="finish_purchase" id="submit" value="Submit" class="link_button large"/>
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
<script>
  function selectChange(obj){
  	var type_select = document.getElementById("get_type"); 
  	var mail_type = document.getElementById("li_mail_type"); 
  	
  	if(obj.options[obj.selectedIndex].value == 1){
  		mail_type.style.display='none';
  	}
  	if(obj.options[obj.selectedIndex].value == 2){
  		mail_type.style.display='';
  	}

  } 		
</script> 
</body>
</html>