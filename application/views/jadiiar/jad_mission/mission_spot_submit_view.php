<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>任务预览页面</title>
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
        <h2>请确认任务详情</h2>
								<?php foreach($m_type_items as $sti) {
									      if($sti[1]==$m_type) echo $sti[0];
									    } 
								?>        
				<?php echo form_open(current_url());?>  	
				<input type="hidden" name="mission_type" value="<?php echo $m_type; ?>" />	
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
             <li>
								<label for="mission_merch_id">商品长编码:</label>
								<label for="mission_merch_id"><?php echo $merchId;?></label>
	              <input type="hidden" id="mission_merch_id" name="submit_mission_merch_id" value ="<?php echo $merchId; ?>" />
             </li>
             <hr/>
             </ul>
          </fieldset>   
					<fieldset>
					<legend>物流信息</legend>
					<ul>
						<?php $i=1;
						      foreach($mailLine as $mli){
			                  $senduser = $this->flexi_auth->get_user_by_id($mli['0'])->row_array();
			                  $sendusername = $senduser['upro_first_name'].$senduser['upro_last_name'];
			                  if ($mli['1'] != 888){
			                      $receiveuser = $this->flexi_auth->get_user_by_id($mli['1'])->row_array();
			                      
			                      $receiveusername = $receiveuser['upro_address'].'   '.$receiveuser['upro_first_name'].$receiveuser['upro_last_name'];
			                  }else{
			                  	$receiveusername = $missionClient;
			                	}
						?>
						<li><?php echo 'No.'.$i.'       发送人：'.$sendusername.'   接收人：'.$receiveusername;$i++; ?> </li>
						<?php } ?>
					</ul>
					</fieldset>
					<?php if ( $m_type == 3) { ?>
					<fieldset>
					<legend>订购客户信息</legend>
					<ul>
						<li><?php echo $missionClient; ?>
	              <input type="hidden" id="mission_client" name="set_mission_client" value ="<?php echo $missionClient; ?>" />
						</li>
						<li>订购价格：<?php echo $orderPrice; ?>
	              <input type="hidden" id="order_price" name="set_order_price" value ="<?php echo $orderPrice; ?>" />
						    <?php echo $merchUnit; ?>
	              <input type="hidden" id="merch_unit" name="set_merch_unit" value ="<?php echo $merchUnit; ?>" />
						</li>
					</ul>
					</fieldset>
					<?php } ?>
					<hr/>
							<li>
								<label for="submit">提交:</label>
								<input type="submit" name="mission_spot_submit" id="submit" value="Submit" class="link_button large"/>
								<input type="button" name="cancel" id="cancel" value="取消" class="link_button large" onclick="javascript:window.history.back(-1);" />
							</li>
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