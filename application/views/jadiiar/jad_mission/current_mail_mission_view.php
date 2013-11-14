<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>当前邮寄任务一览页面</title>
	<?php $this->load->view('includes/head'); ?>  
</head>
<script>
function checkAllBox(obj) {	 
	if(obj.checked==true)
	  checkAll();
	else
		uncheckAll();
} 
function checkAll() { 
  var code_Values = document.getElementsByTagName("input"); 
  for(i = 0;i < code_Values.length;i++){ 
    if(code_Values[i].type == "checkbox") { 
      code_Values[i].checked = true; 
    } 
  }
} 
function uncheckAll() { 
  var code_Values = document.getElementsByTagName("input"); 
  for(i = 0;i < code_Values.length;i++){ 
    if(code_Values[i].type == "checkbox") { 
      code_Values[i].checked = false; 
    } 
  } 
}
function submitTest(){  
  var code_Values = document.getElementsByTagName("input");
  var checkedNum = 0;
  var supplAddress = '';
  for(i = 0;i < code_Values.length;i++){ 
    if(code_Values[i].type == "checkbox" && code_Values[i].name != "select_all" && code_Values[i].checked == true) { 
      checkedNum++;
      //判断是否同一个邮寄对象，否则提出错误信息
      if(checkedNum > 1 && supplAddress != code_Values[i].parentNode.parentNode.getElementsByTagName("td")[2].innerHTML){
      	alert("邮寄对象不是相同路线，请重新选择！");
      	return false;
      }
      supplAddress = code_Values[i].parentNode.parentNode.getElementsByTagName("td")[2].innerHTML;
    } 
  }
  //判断是否选择，没有选择就提出错误信息
  if (checkedNum < 2){
  	alert("请选择多条待集体邮寄的商品！");
  	return false;	
  }
}	 
</script>
<body id="manage_privileges">

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
				
				<!--<?php echo form_open(current_url());	?>
					<fieldset>
						<legend>搜  索</legend>
						
						<label for="search">关键字:</label>
						<input type="text" id="search" name="search_query" value="<?php echo set_value('search_goods',$search_query);?>" 
							title="支持品牌、类别、货号或年份的模糊查询"
						/>
						<input type="submit" name="search_goods" value="Search" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_goods/manage_goods" class="link_button grey">重设</a>
					</fieldset>
				<?php echo form_close();?>-->
				<?php echo form_open($base_url.'index.php/jad_mission/finish_mail/selected/', array('onsubmit' => 'return submitTest()'));?>	
						
        <h2>当前邮寄任务一览</h2>
					<table>
						<thead>
							<tr>
								<th><input type="checkbox" id="select_all" name="select_all" onClick="checkAllBox(this);" /></th>
								<th>商品标识码</th>
								<th>接收人详细地址</th>
								<th>发送时间</th>
								<th>接收时间</th>
								<th>邮寄任务状态</th>
								<th>主任务状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<?php if (!empty($cm_mission)) { ?>
						<tbody>
							<?php foreach ($cm_mission as $mission) { ?>
							<tr>
								<td><input type="checkbox" name="select_trans[<?php echo $mission['trans_id'];?>]" value="1"/>
								</td>
								<td>
								  <a href="<?php echo $base_url.'index.php/jad_inventory/inventory_merch_details/'.$mission['merch_pre_id'];?>"><?php echo $mission['merch_pre_id'];?></a>
								</td>
								<td><?php 
									if($mission['recuser_id'] > 10000 && $mission['recuser_id'] < 20000){
										$clientInfo = $this->jad_global_model->get_clientinfo_by_clientid($mission['recuser_id']);
										echo $clientInfo['client_message'];
								  }
								  if($mission['recuser_id'] < 10000){
								  	$userInfo = $this->flexi_auth->get_user_by_id($mission['recuser_id'])->row_array();
								  	echo $userInfo['upro_first_name'].$userInfo['upro_last_name'].' '.$userInfo['upro_phone'].' '.$userInfo['upro_post_code'].'<br />'.$userInfo['upro_country'].' '.$userInfo['upro_county'].' '.$userInfo['upro_city'].' '.$userInfo['upro_address'];
								  }
									if($mission['recuser_id'] > 20000){
										$clientInfo = $this->jad_global_model->get_supplier_info_by_sId($mission['recuser_id']);
										echo $clientInfo['suppl_location'].' '.$clientInfo['suppl_phone'];
								  }									   
								?>
								</td>
								<td>
									<?php echo $mission['trans_sendtime'];?>
								</td>
								<td>
									<?php echo $mission['trans_rectime'];?>
								</td>
								<td>
								<?php foreach($mm_status as $mm) { 
									      if ( $mm[1] == $mission['trans_status'] ) echo $mm[0];
								}?>
								</td>
							  <td><?php $isNormal=true;
							  	        if($mission['mission_status']!=4){
							  	          echo '任务正常';
							  	        }else{
							  	        	$isNormal = false;
							  	        	echo '任务被终止';
							  	        }
							  ?>
							  </td>
								<td>
								<?php if($mission['senduser_id'] > 20000&&$mission['trans_status']==2&&$mission['recuser_id'] != $this->flexi_auth->get_user_id()){ ?>
								      <a href="<?php echo $base_url.'index.php/jad_mission/finish_mail/'.$mission['trans_id'];?>">供应商直邮寄出</a>
								<?php } ?>
								
								<?php if($mission['senduser_id'] > 20000&&$mission['trans_status']==2&&$mission['recuser_id'] == $this->flexi_auth->get_user_id()){ ?>
								      <a href="<?php echo $base_url.'index.php/jad_mission/finish_mail/'.$mission['trans_id'];?>">供应商已寄出</a>
								<?php } ?>
								
								<?php if($mission['senduser_id'] == $this->flexi_auth->get_user_id()&&$mission['trans_status']==2&&$mission['recuser_id'] > 20000){ ?>
								      <a href="<?php echo $base_url.'index.php/jad_mission/finish_mail/'.$mission['trans_id'];?>">邮寄退货</a></br>
								      <?php if ($this->jad_global_model->is_in_group($mission['senduser_id'],4)){ ?>
								        <a href="<?php echo $base_url.'index.php/jad_mission/finish_return/'.$mission['trans_id'].'/'.urlencode($mission['merch_pre_id']).'/'.$mission['mission_id'];?>">直接退货</a></br>
								        <a href="<?php echo $base_url.'index.php/jad_mission/end_return_mission/'.$mission['trans_id'];?>">留存入库</a>	
								      <?php } ?>	
								      
								    
								<?php } ?>							

								<?php if(($mission['senduser_id'] == $this->flexi_auth->get_user_id()&&$mission['trans_status']==2&&$mission['recuser_id'] < 20000)||($mission['senduser_id'] > 10000&&$mission['senduser_id'] < 20000&&$mission['trans_status']==2)){ ?>
								    <a href="<?php echo $base_url.'index.php/jad_mission/finish_mail/'.$mission['trans_id'];?>">寄出</a></br>
								    <?php if (!$isNormal){?>
								    <a href="<?php echo $base_url.'index.php/jad_mission/end_terminated_mission/'.$mission['trans_id'];?>">留存入库</a> 
								    <?php } ?>		
								
								<?php } ?>
								
								<?php if($mission['recuser_id'] == $this->flexi_auth->get_user_id()&&$mission['trans_status']==3){ ?>
								      <a href="<?php echo $base_url.'index.php/jad_mission/received_mail/'.$mission['trans_id'];?>">接收</a>
								<?php } ?>
								
								<?php if($mission['senduser_id'] == $this->flexi_auth->get_user_id()&&$mission['trans_status']==3&&$mission['recuser_id'] > 10000){ 
									         if($mission['recuser_id'] > 20000){?>
								           <a href="<?php echo $base_url.'index.php/jad_mission/finish_return/'.$mission['trans_id'].'/'.urlencode($mission['merch_pre_id']).'/'.$mission['mission_id'];?>">供应商已签收</a> &nbsp
								           <?php }else{ ?>   
								           <a href="<?php echo $base_url.'index.php/jad_mission/received_mail/'.$mission['trans_id'];?>">客户已签收</a> &nbsp
								           <?php } ?>	
								<?php } ?>	    
						
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="8">
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="8" class="highlight_red">
									没有任何任务信息
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>	
				  <input type="submit" id="multiple_mail_submit" name="multiple_mail" value="寄出选中项" class="link_button large" />
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