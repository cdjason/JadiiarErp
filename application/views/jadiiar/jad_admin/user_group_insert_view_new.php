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
            <h1 class="page-title">新增用户组</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">用户组列表</a> <span class="divider">/</span></li>
            <li class="active">新增用户组</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'new_group_form');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn"/><i class="icon-plus"></i> 增加
  </button>
  <input type="hidden" name="insert_user_group" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
                    <label>用户组名称</label>
                    <input type="text" id="group" name="insert_group_name" value="<?php echo set_value('insert_group_name');?>" class="span4"
                        title="The name of the user group."/>
                    <label>用户组描述</label>
                    <textarea id="description" name="insert_group_description" rows="3" class="input-xlarge"><?php echo set_value('insert_group_description');?></textarea>
                    <label class="checkbox">
                    <input type="checkbox" id="admin" name="insert_group_admin" value="1" <?php echo set_checkbox('insert_group_admin',1);?> />
                    Is Admin Group
                    </label>

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
		'title' : '新增用户组',
		'message' : '您确定要新增该用户组吗？',        
		'action' : function() {
			$('#new_group_form').submit();
		}
	});
});
</script>  
  </body>
</html>



