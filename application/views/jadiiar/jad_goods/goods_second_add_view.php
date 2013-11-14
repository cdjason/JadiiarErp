<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>新增商品二级信息</title>
<?php $this->load->view('includes/head'); ?> 
</head>

<body style="text-align:left;">

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
        <h2>增加商品二级信息</h2>
        
				<?php echo form_open(current_url(), array('onsubmit' => 'return submitTest()'));?> 
					<fieldset>
						<legend>商品一级信息</legend>
						<ul>
							<li>
								<label for="goods_seriesnum" >货号:</label>
								<label for="goods_seriesnum" ><?php echo $goods_first['goods_seriesnum']; ?></label>
								<input type = "hidden" id = "goods_seriesnum" name = "input_goods_seriesnum" value = "<?php echo $goods_first['goods_seriesnum']; ?>" />
								<label for="goods_shortcode" >短条码:</label>
								<label for="goods_shortcode" ><?php echo $goods_first['goods_shortcode']; ?></label>
								<input type = "hidden" id = "goods_shortcode" name = "input_goods_shortcode" value = "<?php echo $goods_first['goods_shortcode']; ?>" />
						  </li>
						  <li>
								<label>类别:</label>
								<label><?php echo $goods_first['categ_name']; ?></label>
								<label>品牌:</label>
								<label><?php echo $goods_first['goods_brand']; ?></label>
							</li>
							<li>
								<label>描述:</label>
								<label><?php echo $goods_first['goods_desc']; ?></label>
							</li>
						</ul>
					</fieldset>	
					<fieldset>
						<legend>商品二级信息</legend>
						<ul>
						  <li>
								<div class="ui-widget" >
                <label for="merch_colour" >商品颜色: </label>
                    <input id="merch_colour" name="select_merch_colour"/>
                </div>
              </li>
              <li>
								<div class="ui-widget">
                <label for="merch_size" >商品尺码: </label>
                    <input id="merch_size" name="select_merch_size"/>
                </div>
              </li>
							<li>
								<label for="merch_desc">细节描述:</label>
								<textarea id="merch_desc" name="add_merch_desc" class="width_400 tooltip_trigger"
									title="商品的基本属性描述"><?php echo set_value('add_merch_desc');?></textarea>
							</li>
							<li>
								<label for="merch_image_url">图片链接:</label>
								<textarea id="merch_image_url" name="add_merch_image_url" class="width_400 tooltip_trigger"
									title="商品的图片链接"><?php echo set_value('add_merch_image_url');?></textarea>
							</li>
							<li>
								<hr/>
								<label for="submit">增加:</label>
								<input type="submit" name="add_goods_second" id="add_goods_second_submit" value="生成商品二级信息" class="link_button large"/>
							</li>
						</ul>
					</fieldset>
				<?php echo form_close();?>
				<a href="<?php echo $base_url; ?>index.php/jad_goods/manage_goods_second/<?php echo $goods_first['goods_seriesnum']; ?>" >返回商品二级信息</a>
			</div>
		</div>
	</div>	
	
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
</div>

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
  <script>  	
  	
  function submitTest(){
  	var arrcolour = new Array();
  	var arrsize = new Array();
  	<?php foreach($colour as $coo){ ?>
  	arrcolour.push(<?php echo '"'.$coo[1].'"'; ?>);
    <?php } ?>	
  	<?php foreach($size as $soo){ ?>
  	arrsize.push(<?php echo '"'.$soo[1].'"'; ?>);
    <?php } ?>	  		
  	if (jQuery.inArray(document.getElementById("merch_colour").value, arrcolour) == -1 || jQuery.inArray(document.getElementById("merch_size").value, arrsize) == -1){
  		alert("请重新选择正确的颜色和尺码信息！");
  	  return false;
  	}else 
  		return true;
  }	
  
  $(function() {
    var availableColours = [
		<?php foreach($colour as $key => $col) { 
			    if ($key == (count($colour)-1) ) 
			       echo '{ label: "'.$col[0].'", value: "'.$col[1].'" }'; 
		      else 
		         echo '{ label: "'.$col[0].'", value: "'.$col[1].'" },'; 	    
			    
		} ?>    
    ];
    var availableSizes = [
		<?php foreach($size as $key => $si) { 
          if ($key == (count($size)-1) ) 
              echo '{ label: "'.$si[0].'", value: "'.$si[1].'" }'; 							
		      else
		          echo '{ label: "'.$si[0].'", value: "'.$si[1].'" },'; 	
		} ?>    
    ];
    $( "#merch_colour" ).autocomplete({
      source: availableColours
    });
    $( "#merch_size" ).autocomplete({
      source: availableSizes
    });
  });
  </script>
</body>
</html>