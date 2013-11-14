<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>当前购买任务</title>
	<?php $this->load->view('includes/head'); ?>  
</head>
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
				
        <h2>当前购买任务一览</h2>
							<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th>商品标识码</th>
								<th>任务下达时间</th>
								<th>任务备注</th>
								<th>购买任务状态</th>
								<th>主任务状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<?php if (!empty($c_mission)) { ?>
						<tbody>
							<?php foreach ($c_mission as $mission) { ?>
							<tr>
								<td>
									<a href="<?php echo $base_url.'index.php/jad_goods/merch_details/'.$mission['merch_pre_id'];?>"><?php echo $mission['merch_pre_id'];?></a>
								
								</td>
								<td>
									<?php echo $mission['mission_time'];?>
								</td>
								<td>
									<?php echo $mission['purchase_desc'];?>
								</td>
								<td>
								<?php foreach($pm_status as $pm) { 
									      if ( $pm[1] == $mission['purchase_status'] ) echo $pm[0];
								}?>
								</td>
								<td><?php $isNormal = true;
									        if($mission['mission_status']!=4){
							  	          echo '任务正常';
							  	        }else{
							  	        	$isNormal = false;
							  	        	echo '任务被终止';
							  	        }
								?>
								</td>
								<td>
									<?php if (!$isNormal){?>
									<a href="<?php echo $base_url.'index.php/jad_mission/end_purchase_mission/'.$mission['mission_id'];?>" style="display:<?php echo ($mission['purchase_status']==2)?"":"none";?>;">未购买</a> 
								  <?php } ?>	
									<a href="<?php echo $base_url.'index.php/jad_mission/execute_mission/'.$mission['purchase_id'].'/2';?>" style="display:<?php echo ($mission['purchase_status']==1)?"":"none";?>;">接受</a>
									<a href="<?php echo $base_url.'index.php/jad_mission/execute_mission/'.$mission['purchase_id'].'/4';?>" style="display:<?php echo ($mission['purchase_status']==1)?"":"none";?>;">退回</a>
									<a href="<?php echo $base_url.'index.php/jad_mission/finish_purchase/'.$mission['purchase_id'].'/'.urlencode($mission['merch_pre_id']).'/'.$mission['mission_id'];?>" style="display:<?php echo ($mission['purchase_status']==2)?"":"none";?>;">完成</a>
									  
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="6" class="highlight_red">
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