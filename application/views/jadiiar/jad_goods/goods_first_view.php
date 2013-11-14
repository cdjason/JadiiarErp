<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>商品一级信息维护</title>
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
				<h2>商品一级信息维护</h2>
			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php echo form_open(current_url());	?>
					<fieldset>
						<legend>搜  索</legend>
						
						<label for="search">关键字:</label>
						<input type="text" id="search" name="search_query" value="<?php echo set_value('search_goods',$search_query);?>" 
							title="支持品牌、类别、货号或年份的模糊查询"
						/>
						<input type="submit" name="search_goods" value="Search" class="link_button"/>
						<a href="<?php echo $base_url; ?>index.php/jad_goods/manage_goods_first" class="link_button grey">重设</a>
					</fieldset>
				<?php echo form_close();?>
				
							<?php echo form_open(current_url());	?>
					<table>
						<thead>
							<tr>
								<th>品牌</th>
								<th>类别</th>
								<th>货号</th>
								<th>短条码</th>				
								<th>描述</th>
								<th>编辑</th>
								<th>过期</th>
								<th>删除</th>
							</tr>
						</thead>
						<?php if (!empty($goods_first)) { ?>
						<tbody>
							<?php foreach ($goods_first as $good) { ?>
							<tr>
								<td>
									<?php echo $good['goods_brand'];?>
								</td>
								<td>
									<?php echo $good['categ_name'];?>
								</td>
								<td>
									<a href="<?php echo $base_url.'index.php/jad_goods/manage_goods_second/'.$good['goods_seriesnum'];?>">
									<?php echo $good['goods_seriesnum'];?>
								  </a>
								</td>
								<td>
									<?php echo $good['goods_shortcode'];?>
								</td>	
								<td>
									<?php echo $good['goods_desc'];?>
								</td>				
								<td>
									<a href="<?php echo $base_url.'index.php/jad_goods/update_goods_first/'.$good['goods_seriesnum'];?>">
										编辑
									</a>
								</td>
								<td>
									
									<input type="hidden" name="current_status[<?php echo $good['goods_seriesnum'];?>]" value="<?php echo $good['goods_expired'];?>"/>
									<!-- A hidden 'suspend_status[]' input is included to detect unchecked checkboxes on submit -->
									<input type="hidden" name="expire_goods[<?php echo $good['goods_seriesnum'];?>]" value="0"/>
								
									<input type="checkbox" name="expire_goods[<?php echo $good['goods_seriesnum'];?>]" value="1" <?php echo ($good['goods_expired'] == 1) ? 'checked="checked"' : "";?> />
								</td>
								<td>
									<input type="checkbox" name="delete_goods[<?php echo $good['goods_seriesnum'];?>]" value="1"/>
								</td>
							</tr>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="8">
									<input type="submit" id="submit" name="update_goodes_first" value="更新/删除 商品基础信息" class="link_button large" />
								</td>
							</tr>
						</tfoot>
					<?php } else { ?>
						<tbody>
							<tr>
								<td colspan="8" class="highlight_red">
									无商品信息
								</td>
							</tr>
						</tbody>
					<?php } ?>
					</table>	
					
				<?php if (! empty($pagination['links'])) { ?>
					<div id="pagination" class="w100 frame">
						<p>分页: 共 <?php echo $pagination['total_goods_first'];?>  条搜索结果</p>
						<p>链接: <?php echo $pagination['links'];?></p>
					</div>
				<?php } ?>					
				
				<?php echo form_close();?>	
       <a href="<?php echo $base_url; ?>index.php/jad_goods/add_goods_first" >增加商品基础信息</a>
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
