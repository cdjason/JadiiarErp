<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>商品二级信息维护</title>
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
				<h2>商品二级信息</h2>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>

					<fieldset>
						<legend>一级信息</legend>
						<ul>
							<li>
								<label>商品货号:</label><label><?php echo $goodsFirst['goods_seriesnum']; ?></label>
							</li>
							<li>
								<label>短条码:</label><label><?php echo $goodsFirst['goods_shortcode']; ?></label>
							</li>
							<li>
								<label>一级描述:</label><label><?php echo $goodsFirst['goods_desc']; ?></label>
							</li>
						</ul>
					</fieldset>			
			
			<!--<?php echo form_open(current_url());	?>
			
				<fieldset>
						<legend>搜  索</legend>
						<label for="search">关键字:</label>
						<input type="text" id="search" name="search_query" value="<?php echo set_value('search_merchandises',$search_query);?>" 
							title="支持货号的模糊查询"
						/>
						<input type="submit" name="search_merchandises" value="Search" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_goods/manage_merchandises" class="link_button grey">重设</a>
				</fieldset>
			<?php echo form_close();?>-->
			
				
							<?php echo form_open(current_url());	?>
							
							
					<table  <?php echo $isExpired ? 'disabled = "disabled"':'';?> >
						
						<thead>
							<tr><th></th>
                <th>商品长编码</th>
                <th>条形码</th>
								<th>品牌</th>
								<th>类别</th>
								<th>颜色</th>
								<th>尺寸</th>
								<th>编辑</th>
								<th>操作</th>
							</tr>
						</thead>
						<?php if (!empty($goods_second)) { ?>
						<tbody>
							<?php foreach ($goods_second as $good) { ?>
							<tr>
								<td><a id="example6" href = "<?php echo $good['goods_image_url'];?>">
									<img title="<?php echo $good['goods_s_desc'];?>" 
									     src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($good['goods_image_url']);?>" height="50px" width="50px" >
								</a></td>
								<td>
									<?php echo $good['goods_code'];?>
								</td>
								<td>
									<?php echo $good['goods_barcode'];?>
								</td>
								<td>
									<?php echo $good['goods_brand'];?>
								</td>
								<td>
									<?php echo $good['categ_name'];?>
								</td>
								<td><?php foreach($colour as $coo){ 
									          if ($coo[1] == substr($good['goods_code'],10,3)) echo $coo[0];
									        }
									  ?>
									
								</td>				
								<td><?php foreach($size as $soo){ 
									          if ($soo[1] == substr($good['goods_code'],13,3)) echo $soo[0];
									        }
									  ?>
								</td>	
								<td>
									<a href="<?php echo $base_url.'index.php/jad_goods/update_goods_second/'.urlencode($good['goods_code']);?>">	
										编辑
									</a>
								</td>	
							  <td>
									<a href="<?php echo $isExpired ? '#':$base_url.'index.php/jad_mission/new_mission/'.urlencode($good['goods_code']);?>">	
										下任务
									</a>
								</td>	
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="6" class="highlight_red">
									该货号下没有任何的商品二级信息.
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>	
					
				<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>分页: 共 <?php echo $pagination['total_goods_second'];?>  条搜索结果</p>
						<p>链接: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>					
				
				<?php echo form_close();?>	
			<?php if(!$isExpired ){ ?>
       <a href="<?php echo $base_url; ?>index.php/jad_goods/add_goods_second/<?php echo $g_seriesnum; ?>" >增加商品二级信息</a>
      <?php }else{ ?> 该商品已经过期，不能进行任务操作。
      <?php } ?>
       <a href="<?php echo $base_url; ?>index.php/jad_goods/manage_goods_first">返回商品一级目录</a>
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