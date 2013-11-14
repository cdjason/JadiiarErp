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
            <h1 class="page-title">更新用户组</h1>
        </div>
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">用户组列表</a> <span class="divider">/</span></li>
            <li class="active">更新用户组</li>
        </ul>
        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php echo form_open(current_url());?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" /><i class="icon-plus"></i> 保存 
  </button>
  <input type="hidden" name="update_user_group" value="1" />
  <div class="btn-group">
  </div>
</div>
<div class="well">
                <form>
                    <label>用户组名称</label>
                    <input type="text" id="group" name="update_group_name" value="<?php echo set_value('update_group_name', $group[$this->flexi_auth->db_column('user_group', 'name')]);?>" class="span4"/>
                    <label>用户组描述</label>
                    <textarea id="description" name="update_group_description" class="input-xlarge" rows="3"><?php echo set_value('update_group_description', $group[$this->flexi_auth->db_column('user_group', 'description')]);?></textarea>
                    <?php $ugrp_admin = ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ;?>
                    <label class="checkbox">
                    <input type="checkbox" id="admin" name="update_group_admin" value="1" <?php echo set_checkbox('update_group_admin', 1, $ugrp_admin);?> />
                    Is Admin Group
                    </label>
                </form>
</div>
	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>  
  </body>
</html>



