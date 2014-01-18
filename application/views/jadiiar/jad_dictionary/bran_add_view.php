<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<?php $this->load->view('includes/jad_head'); ?>  
<style>

		label.valid {
		  width: 24px;
		  height: 24px;
		  background: url('<?php echo $includes_dir;?>/images/valid.png') center center no-repeat;
		  display: inline-block;
		  text-indent: -9999px;
		}
		label.error {
			font-weight: bold;
			color: red;
			padding: 2px 8px;
			margin-top: 2px;
		}
</style>
</head>
  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
<?php $this->load->view('includes/jad_navbar'); ?>  
<?php $this->load->view('includes/jad_sidebar'); ?>  
    <div class="content">
        <div class="header">
            <h1 class="page-title">新增分店信息</h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_branches">分店信息列表</a> <span class="divider">/</span></li>
            <li class="active">新增分店信息</li>
        </ul>
        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'new_branch_form');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn"/><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="add_branch" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
								<label>分店名称:</label>
								<input type="text" id="branch_name" class="span4" name="register_branch_name" value="<?php echo set_value('register_branch_name');?>"/>
								<label>分店地址:</label>
								<input type="text" id="branch_address" class="span4" name="register_branch_address" value="<?php echo set_value('register_branch_address');?>"/>
								<label>分店电话:</label>
								<input type="text" id="branch_phone" class="span4" name="register_branch_phone" value="<?php echo set_value('register_branch_phone');?>"/>
								<label>邮编:</label>
								<input type="text" id="branch_zipcode" class="span4" name="register_branch_zipcode" value="<?php echo set_value('register_branch_zipcode');?>"/>
								<label>负责人:</label>
								<select id="upro_id" name="register_upro_id" class="span4">
								<?php foreach($managers as $manager) { ?>
									<option value="<?php echo $manager['upro_id'];?>">
										<?php echo $manager['upro_first_name'].' '.$manager['upro_last_name'];?>
									</option>
								<?php } ?>
								</select>
</div>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
$(document).ready(function(){

	// Validate
	// http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	// http://docs.jquery.com/Plugins/Validation/
	// http://docs.jquery.com/Plugins/Validation/validate#toptions

		$('#new_branch_form').validate({
	    rules: {
	      register_branch_name: {
	        minlength: 8,
	        required: true
	      },
	      email: {
	        required: true,
	        email: true
	      },
	      subject: {
	      	minlength: 2,
	        required: true
	      },
	      message: {
	        minlength: 2,
	        required: true
	      }
	    },
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });


    $('#form_btn').confirm({
		'title' : '新增分店信息',
		'message' : '您确定要新增该分店信息吗？',        
		'action' : function() {
			$('#new_branch_form').submit();
		}
	});
});
</script>  
  </body>
</html>
