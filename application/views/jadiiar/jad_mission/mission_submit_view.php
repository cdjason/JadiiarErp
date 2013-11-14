<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>任务预览页面</title>
	<?php $this->load->view('includes/head'); ?> 
<script>
$(document).ready(function() {
    $('#submit').click(function() {
        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        } });
        setTimeout($.unblockUI, 20000);
    });
});
</script>
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
        <h2>请确认任务详情    
								<?php foreach($m_type_items as $sti) {
									      if($sti[1]==$m_type) echo $sti[0];
									    } 
								?>
        </h2>
        
				<?php echo form_open(current_url());?>  	
				<input type="hidden" name="mission_type" value="<?php echo $m_type; ?>" />	
					<fieldset>
						<legend>商品详细信息</legend>
						<ul>
             <li>
								<label>商品品牌:</label>
								<label><?php echo $goods_first['goods_brand'];?></label>
								<label>商品类别:</label>
								<label><?php echo $goods_first['categ_name'];?></label>
             </li>
             <li>
								<label>基本描述:</label>
								<label><?php echo $goods_first['goods_desc'];?></label>
             </li>
             <hr/>
             <li>
								<label for="mission_merch_id">商品长编码:</label>
								<label for="mission_merch_id"><?php echo $goods_code;?></label>
	              <input type="hidden" id="mission_merch_id" name="submit_mission_merch_id" value ="<?php echo $goods_code; ?>" />
             </li>
             <li>
								<label>商品颜色:</label>
								<label><?php echo $m_colour;?></label>   
								<label>商品尺码:</label>
								<label><?php echo $m_size;?></label>
             </li>
             <li>
								<label>详细描述:</label>
								<label><?php echo $goods_s_desc;?></label>
	              <input type="hidden" id="mission_s_desc" name="submit_mission_s_desc" value ="<?php echo $goods_s_desc; ?>" />
	           </li>
             <hr/>
             <li>
								<label for="mission_desc">购买任务要求:</label>
								<label for="mission_desc"><?php echo $m_desc;?></label>
	              <input type="hidden" id="mission_desc" name="submit_mission_desc" value ="<?php echo $m_desc; ?>" />
             </li>
             </ul>
          </fieldset>   
          <fieldset>
						<legend>买手信息</legend>
	              <input type="hidden" id="mission_buyer_id" name="submit_mission_buyer_id" value = " <?php echo $m_buyer['upro_id'] ?> "  />
             <ul>
             <li>
								<label>买手姓名:</label>
								<label><?php echo $m_buyer['upro_first_name'].' '.$m_buyer['upro_last_name']; ?></label>
             </li>
             <li>
								<label>电话:</label>
								<label><?php echo $m_buyer['upro_phone'];?></label>
             
								<label>地址:</label>
								<label><?php echo $m_buyer['upro_address'];?></label>
             </li>

						</ul>
					</fieldset>
					<fieldset>
					<legend>物流信息</legend>
					<ul>
						<?php $i=1;
						      foreach($mailLine as $mal){
			                  $senduser = $this->flexi_auth->get_user_by_id($mal['0'])->row_array();
			                  $sendusername = $senduser['upro_first_name'].$senduser['upro_last_name'];
			                  if ($mal['1'] != 888){
			                      $receiveuser = $this->flexi_auth->get_user_by_id($mal['1'])->row_array();
			                      
			                      $receiveusername = $receiveuser['upro_address'].'   '.$receiveuser['upro_first_name'].$receiveuser['upro_last_name'];
			                  }else{
			                  	$receiveusername = $missionClient;
			                	}
						?>
						<li><?php echo 'No.'.$i.'       发送人：'.$sendusername.'   接收人：'.$receiveusername;$i++; ?> </li>
						<?php } ?>
					</ul>
					</fieldset>
					<?php if ( $m_type != 2) { ?>
					<fieldset>
					<legend>订购客户信息</legend>
					<ul>
						<li>客户信息：<?php echo $missionClient; ?>
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
								<input type="submit" name="mission_submit" id="submit" value="Submit" class="link_button large"/>
								<input type="button" name="cancel" id="cancel" value="取消" class="link_button large" onclick="javascript:window.history.back(-1);" />
							</li>
					<input type="hidden" name="is_from_mission_page" value="<?php echo $isFromMPage ?>" />
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