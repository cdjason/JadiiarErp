<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>新增商品一级信息条目</title>
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
        <h2>新增商品一级信息</h2>
        
				<?php echo form_open(current_url());?>  	
					<fieldset>
						<legend>商品一级信息</legend>
						<ul>
							<li>
								<label for="goods_brand">品牌:</label>
								<select id="goods_brand" name="add_goods_brand">
								<?php foreach($brands as $brand) { ?>
									<option value="<?php echo $brand[1].$brand[0];?>">
										<?php echo $brand[0];?>
									</option>
								<?php } ?>
								</select>
								
							</li>
							<li>
								<label for="goods_category">类别:</label>
								<select id="categ_id" name="add_categ_id">
								<?php foreach($categorys as $categ) { ?>
									<option value="<?php echo $categ[1];?>">
										<?php echo $categ[0];?>
									</option>
								<?php } ?>
								</select>
							</li>
							<li>
								<label for="goods_year">生产年份:</label>
								<select id="goods_year" name="add_goods_year">
								<?php foreach($years as $year) { ?>
									<option value="<?php echo $year[1];?>">
										<?php echo $year[0];?>
									</option>
								<?php } ?>
								</select>
							</li>
							<li>
								<label for="goods_desc">细节描述:</label>
								<textarea id="goods_desc" name="add_goods_desc" class="width_400 tooltip_trigger" title="描述字数小于50"
									title="商品的基本属性描述"><?php echo set_value('add_goods_desc');?></textarea>
							</li>
							<li>
								<hr/>
								<label for="submit">增加:</label>
								<input type="submit" name="add_goods_first" id="submit" value="提  交" class="link_button large"/>
							</li>
						</ul>
					</fieldset>
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