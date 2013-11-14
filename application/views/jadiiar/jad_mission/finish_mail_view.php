<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>商品邮寄信息</title>
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
        <h2>
        <?php if($mailType==1) echo '单独邮寄！';else echo '集体邮寄！';
         ?> 
        </h2>
        
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品邮寄详细信息</legend>
						<ul>
							
						<input type="hidden" id="trans_id" name="get_trans_id" value="<?php echo $transId;?>"/>

							<li>
								<label for="courier">物流公司:</label>
								
								<select id="courier" name="select_courier">
								<?php foreach($couriersInfo as $courier) { ?>
									<option value="<?php echo $courier[1];?>">
										<?php echo $courier[0];?>
									</option>
								<?php } ?>
								</select>
							</li>
							<li>
								<label for="mail_tracking">快递单号:</label>
								<input type="text" id="mail_tracking" name="add_mail_tracking" title="数字、字母、下划线或者破折号" value="<?php echo set_value('add_mail_tracking');?>"/>
							</li>
							<li>
								<label for="mail_price">邮寄费用:</label>
								<input type="text" id="mail_price" name="add_mail_price" value="<?php echo set_value('add_mail_price');?>"/>
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
								<hr/>
								<label for="submit">提交:</label>
								<input type="submit" name="finish_mail" id="submit" value="Submit" class="link_button large"/>
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