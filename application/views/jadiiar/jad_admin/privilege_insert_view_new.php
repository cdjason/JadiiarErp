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
            <h1 class="page-title">新增权限</h1>
        </div>
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_privileges">权限列表</a> <span class="divider">/</span></li>
            <li class="active">新增权限</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'new_privilege_form');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn"/><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="insert_privilege" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
                    <label>权限名称</label>
                    <input type="text" id="privilege" name="insert_privilege_name" value="<?php echo set_value('insert_privilege_name');?>" class="span4"/>
                    <label>权限描述</label>
                    <textarea id="description" name="insert_privilege_description" rows="3" class="input-xlarge"><?php echo set_value('insert_privilege_description');?></textarea>

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
		'title' : '新增权限',
		'message' : '您确定要新增该权限吗？',        
		'action' : function() {
			$('#new_privilege_form').submit();
		}
	});
});
</script>  
  
  </body>
</html>
