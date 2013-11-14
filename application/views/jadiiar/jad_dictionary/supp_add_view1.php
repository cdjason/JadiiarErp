<!DOCTYPE html>
<html lang="en"> 
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<?php $this->load->view('includes/jad_head'); ?>  
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
            <h1 class="page-title">新增供应商</h1>
        </div>
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_privileges">供应商列表</a> <span class="divider">/</span></li>
            <li class="active">新增供应商</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'new_supplier_form');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn"/><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="add_supplier" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
        <label>品牌:</label>
        <input type="text" id="suppl_brand" class="span4" name="register_suppl_brand" value="<?php echo set_value('register_suppl_brand');?>"/>
        <label>别名:</label>
        <input type="text" id="suppl_alias" class="span4" name="register_suppl_alias" value="<?php echo set_value('register_suppl_alias');?>"/>
		<label>位置:</label>
		<textarea id="suppl_location" class="span4" rows="3" name="register_suppl_location"><?php echo set_value('register_suppl_location');?></textarea>	
		<label>电话:</label>
		<input type="text" id="suppl_phone" class="span4" name="register_suppl_phone" value="<?php echo set_value('register_suppl_phone');?>"/>
		<label>合作店员:</label>
		<input type="text" id="suppl_collaborate" class="span4" name="register_suppl_collaborate" value="<?php echo set_value('register_suppl_collaborate');?>"/>
        <label>联系方式:</label>
        <input type="text" id="suppl_collaphone" class="span4" name="register_suppl_collaphone" value="<?php echo set_value('register_suppl_collaphone');?>"/>
        <label>能够退税:</label>
        <input type="checkbox" id="suppl_taxrebate" name="rebate_status" value="1" />
        <label>能够邮寄香港:</label>
        <input type="checkbox" id="suppl_hkmail" name="mailhk_status" value="1"  />
        <label>备注:</label>
        <textarea id="suppl_note" name="register_suppl_note" rows="3" class="span4"><?php echo set_value('register_suppl_note');?></textarea>
</div>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
$(document).ready(function(){
    $('#form_btn').confirm({
		'title' : '新增供应商',
		'message' : '您确定要新增该供应商吗？',        
		'action' : function() {
			$('#new_supplier_form').submit();
		}
	});
});
</script>  
  </body>
</html>
