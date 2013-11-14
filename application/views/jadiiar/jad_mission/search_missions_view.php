<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>任务搜索</title>
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
				<h2>任务搜索</h2>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php 
				$attributes = array('id' => 'form1');
				echo form_open(current_url(),$attributes);	?>
			
					<table>
						<thead>
							<tr>
								<th></th>
								<th>商品标识码</th>
								<th>发货</th>
								<th>收货</th>
								<th>任务类型</th>
								<th>下达时间</th>
								<th>完成时间</th>
								<th>
									<select id="status" name="select_status" onchange="change(this);">
									    <option value="5">所有任务</option>
								      <?php foreach($m_status as $sta) { 
								             if ($search_query == $sta[1]) $checked = true;
								             else $checked = false;
								      ?>
									    <option value="<?php echo $sta[1];?>" <?php echo ($checked)?"selected":""; ?> ><?php echo $sta[0];?>
									    </option>
								<?php } ?>
								
								</select>	
								</th>
								<th>操作</th>
							</tr>
						</thead>
						<?php if (!empty($s_mission)) { ?>
						<tbody>
							<?php foreach ($s_mission as $mission) { 
								    //通过商品标识码中的货号信息去查找该商品的二级信息
								    $goodSecondInfo = $this->jad_global_model->get_goods_s_info_by_barcode(substr($mission['merch_pre_id'],0,8));
							?>
							<tr>
								<td><a id="example6" href = "<?php echo $goodSecondInfo['goods_image_url'];?>">
									<img title="<?php echo $goodSecondInfo['goods_desc'];?>" 
									     src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($goodSecondInfo['goods_image_url']);?>" height="40px" width="60px" >
								</a></td>
								<td>
									<?php echo $mission['merch_pre_id'];?>
								</td>
								<td><?php echo $this->jad_global_model->get_person_name_by_id($this->jad_global_model->get_mail_end_user_id_by_missionId($mission['mission_id'],1))  ; ?></td>
								<td><?php echo $this->jad_global_model->get_person_name_by_id($this->jad_global_model->get_mail_end_user_id_by_missionId($mission['mission_id'],2))  ; ?></td>
								<td>
								<?php foreach($m_type as $mt) { 
									      if ( $mt[1] == $mission['mission_type'] ) echo $mt[0];
								}?>
								</td>
								<td>
								<?php echo substr($mission['mission_time'],0,10).'</br>'.substr($mission['mission_time'],10,18);?>
								</td>
                <td>
                	<?php echo substr($mission['mission_end_time'],0,10).'</br>'.substr($mission['mission_end_time'],10,18);?>
                </td>
                <td><?php foreach ($m_status as $st){ if ($st[1]==$mission['mission_status']){ echo $st[0];break;} }?>
                </td>
								<td>
									<a href="<?php echo $base_url.'index.php/jad_mission/mission_details/'.$mission['mission_id'];?>">
									详细信息</a>
									<?php if ( ($mission['mission_type']==1||$mission['mission_type']==2||$mission['mission_type']==3) && $mission['mission_status']!=3 && $mission['mission_status']!=4) {?>
									  </br><a href="<?php echo $base_url.'index.php/jad_mission/terminate_mission/'.$mission['mission_id'];?>">
									  终止任务</a>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
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
					
				<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>分页: 共 <?php echo $pagination['total_missions'];?>  条搜索结果</p>
						<p>链接: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>				
				
				<?php echo form_close();?>	
			</div>
		</div>
	</div>	
	
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
</div>

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>
<script src="<?php echo $includes_dir;?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.fancybox-1.3.4.pack.js"></script>  
<script type="text/javascript">
		$(document).ready(function() {
			$("a#example6").fancybox({
				'titlePosition'		: 'outside',
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.9
			});
		});
//响应select事件，并立即刷新页面，返回查询结果
function change(obj){
   if (obj.options[obj.selectedIndex].value==5){
      <?php echo 'window.location.href="'.$base_url.'/index.php/jad_mission/missions_search"';?>;
   }else{
      var f=document.getElementById("form1");
      f.submit();
   }
}
</script>
</body>
</html>
