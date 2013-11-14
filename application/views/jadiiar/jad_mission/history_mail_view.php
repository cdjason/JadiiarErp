<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>历史邮寄任务一览</title>
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
				<h2>历史任务一览</h2>
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
				
							<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th>商品标识码</th>
								<th>快递公司</th>
								<th>快递单号</th>
								<th>快递费用</th>
								<th>商品邮寄时间</th>
								<th>商品接收时间</th>
								<th>任务类型</th>
								<th>任务状态</th>
							</tr>
						</thead>
						<?php if (!empty($h_mail)) { ?>
						<tbody>
							<?php foreach ($h_mail as $mail) { ?>
							<tr>
								<td>
									<a href="<?php echo $base_url.'index.php/jad_inventory/inventory_merch_details/'.$mail['merch_id'];?>"><?php echo $mail['merch_id'];?></a>
								</td>
								<td>
								<?php foreach($couriers_info as $ci) { 
									      if ( $ci[1] == $mail['mail_courier'] ) echo $ci[0];
								}?>
								</td>
								<td>
									<?php echo $mail['mail_tracking'];?>
								</td>
								<td>
									<?php echo $mail['mail_fee'].' '.$mail['monetary_unit'];?>
								</td>
								<td>
									<?php echo $mail['trans_sendtime'];?>
								</td>
								<td>
									<?php echo $mail['trans_rectime'];?>
								</td>
								
								<td>
									<?php if ($mail['senduser_id']== $this->flexi_auth->get_user_id()){
										        //获取用户名称
										        //$query = $this->jad_global_model->get_userinfo_by_userid($mail['senduser_id']);
										        echo '已发送'; 
										    } 
									?>
									<?php if ($mail['recuser_id']== $this->flexi_auth->get_user_id()){
										        //$query = $this->jad_global_model->get_userinfo_by_userid($mail['recuser_id']);
										        echo '已接收';
										    }  
									?>
									<?php if ( $mail['senduser_id'] > 10000 && $this->flexi_auth->in_group('Master Admin') ){
										        //$query = $this->jad_global_model->get_userinfo_by_userid($mail['recuser_id']);
										        echo '已发送';
										    }  
									?>
								</td>
								
								<td>
								<?php foreach($mm_status as $mm) { 
									      if ( $mm[1] == $mail['trans_status'] ) echo $mm[0];
								}?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							
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
					
				<!--<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>分页: 共 <?php echo $pagination['total_suppliers'];?>  条搜索结果</p>
						<p>链接: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>		-->			
				
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