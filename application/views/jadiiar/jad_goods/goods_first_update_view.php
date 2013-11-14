<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
<title>更新商品一级信息条目</title>
<?php $this->load->view('includes/head'); ?> 
</head>
<body id="update_branch">
<div id="body_wrap">
	<!-- Header -->  
	<?php $this->load->view('includes/header'); ?> 

	<!-- Demo Navigation -->
	<?php $this->load->view('includes/jad_header'); ?> 
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>更新商品一级信息</h2>
				

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品一级信息</legend>
						<ul>
							<li>
								<label for="goods_brand">品牌:</label>
								<label for="goods_brand"><?php echo $goods_first['goods_brand'];?></label>
							  <input type="hidden" id="goods_brand" name="update_goods_brand" value="<?php echo $goods_first['goods_brand'];?>" />
								
							</li>
							<li>
								<label for="goods_category">类别:</label>
								<label for="goods_category"><?php echo $goods_first['categ_id'];?></label>
								<input type="hidden" id="goods_category" name="update_goods_category" value="<?php echo $goods_first['categ_id'];?>"  />
							</li>
							<li>
								<label for="goods_year">生产年份:</label>
								<label for="goods_year"><?php echo substr($goods_first['goods_seriesnum'],4,2);?></label>
								<input type="hidden" id="goods_year" name="update_goods_year" value="<?php echo substr($goods_first['goods_seriesnum'],4,2);?>"  />
							</li>
							<li>
								<label for="goods_desc">细节描述:</label>
								<textarea id="goods_desc" name="update_goods_desc" class="width_400 tooltip_trigger" title="描述字数小于50"
									title="商品的基本属性描述"><?php echo set_value('update_goods_desc',$goods_first['goods_desc']);?></textarea>
							</li>
																
             <HR/>
							<li>
								<label for="submit">更新基础信息:</label>
								<input type="submit" name="update_goods_first" id="submit" value="提  交" class="link_button large"/>
							</li>
						</ul>
					</fieldset>
				<?php echo form_close();?>
				<input type="button" name="cancel" id="cancel" value="返回" class="link_button large" onclick="javascript:window.history.back(-1);" />
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