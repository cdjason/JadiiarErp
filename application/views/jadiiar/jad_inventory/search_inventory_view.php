<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>库存搜索</title>
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
				<h2>库存搜索</h2>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php 
				$attributes = array('id' => 'form1');
				echo form_open(current_url(),$attributes);	?>
					<fieldset>
						<legend>搜  索</legend>
						编码关键字:
						<input type="text" id="search" name="code_search_query" value="<?php echo $search_code;?>" 
							title="支持商品条形码、货号、唯一标识或长编码的模糊查询"/>
						库存位置:
						<select id="location" name="select_location">
									<option value="noselected">请选择</option>
								<?php foreach($info_branch as $ibra) { 
								   if ($search_b_id == $ibra['branch_id']) $checked = true;
								   else $checked = false;
								?>
									<option value="<?php echo $ibra['branch_id'];?>" <?php echo ($checked)?"selected":""; ?> >
							       <?php echo $ibra['branch_name'];?>
									</option>
								<?php } ?>
								
						</select>
						<input type="submit" name="search_missions" value="Search" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_inventory/inventory_search" class="link_button grey">重设</a>							
					</fieldset>
				<?php echo form_close();?>
				
					<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th></th>
								<th>商品标识码</th>
								<th>商品长编码</th>
								<th>商品状态</th>
								<th>商品位置</th>								
								<th>操作</th>
							</tr>
						</thead>
						<?php if (!empty($details_inven)) { ?>
						<tbody>
							<?php foreach ($details_inven as $inven) { ?>
							<tr>
								<td><a id="example6" href = "<?php echo $inven['goods_image_url'];?>">
									<img title="<?php echo $inven['goods_desc'];?>" 
									     src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($inven['goods_image_url']);?>" height="40px" width="60px" >
								</a></td>		
								<td><a href = "<?php echo $base_url; ?>index.php/jad_inventory/inventory_merch_details/<?php echo $inven['merch_id'];?>">
									<?php echo $inven['merch_id'];?></a>
								</td>
								<td>
									<?php echo $inven['goods_code'];?>
								</td>
                <td><?php $merStatus = $this->jad_global_model->get_merch_exist_status_info_by_id($inven['merch_id']);
     
                	        /*只能找到非
                	        if( $merStatus == 2){
                	        	//找出任务的类型
                	        	$mtype = $this->jad_global_model->get_mission_info_by_id($inven['merch_id']);
                	          foreach ($m_type as $mt) {
                	            if ( $mt[1] == $mtype['mission_type']){
                	              echo $mt[0].'&nbsp';
                	            } 
                	          }
                	        }*/
                	        foreach ($m_existed_type as $met) {
                	          if ( $met[1] == $merStatus){
                	            echo $met[0];
                	          } 
                	        }
                	  ?>
								</td>
                <td>
								<?php switch ($merStatus)
								      {
                            case 1:
                               echo $inven['branch_name'];
                               break;
                            case 2:
                               echo "任务中";
                               break;
                            case 3:
                               echo "客户";
                               break;
                            case 4:
                               echo "供应商";
                               break;
                            case 5:
									      	     $buyerId = $this->jad_global_model->get_merch_location_by_id($inven['merch_id']);
									      	     $buyerInfo = $this->flexi_auth->get_user_by_id($buyerId)->row_array();
									      	     echo $buyerInfo['upro_first_name'].' '.$buyerInfo['upro_last_name'];
                               break;
                      }
								?>
								</td>
								<td>
									<?php if ($merStatus==1 ){
										        $branchInfo = $this->jad_global_model->get_branch_info_by_id($inven['branch_id']);
									?>
									<a href="<?php echo $base_url.'index.php/jad_mission/new_mission_spot/'.$inven['merch_id'].'/'.$branchInfo['upro_id'];?>">下任务</a></br>
								  <?php }?>
									<?php if ($merStatus==5 ){?>
									<a href="<?php echo $base_url.'index.php/jad_mission/new_mission_spot/'.$inven['merch_id'].'/'.$buyerId;?>">下任务</a></br>
                  <a href="<?php echo $base_url.'index.php/jad_mission/new_mission_return/'.$inven['merch_id'].'/'.$inven['suppl_id'].'/'.$merStatus;?>">退货</a>
								  <?php }?>	
								  <?php if ($merStatus==1||$merStatus==3){?>
                  <a href="<?php echo $base_url.'index.php/jad_mission/new_mission_return/'.$inven['merch_id'].'/'.$inven['suppl_id'].'/'.$merStatus;?>">退货</a>
								  <?php }?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="11" class="highlight_red">
									没有任何库存信息
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
</script>
</body>
</html>
