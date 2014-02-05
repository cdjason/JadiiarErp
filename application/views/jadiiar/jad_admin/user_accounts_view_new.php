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
        
        <h1 class="page-title">用户列表</h1>
    </div>
    
            <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">用户列表</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<?php echo form_open(current_url());	?>
<div class="input-append">
<input type="text" class="span2 search-query" id="search" name="search_query" value="<?php echo set_value('search_users',$search_query);?>">
<input type="submit" name="search_users" class="btn" value="search" />
<a class="btn" href="<?php echo $base_url; ?>index.php/jad_auth_admin/manage_user_accounts">重设</a>
</div>
<?php echo form_close();?>	
<div class="well">
    <?php $attributes = array('id' => 'user_list_form');echo form_open(current_url(), $attributes);?>  	
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>姓名</th>
                        <th>用户组</th>
                        <th>用户权限</th>
                        <th>吊销帐户</th>
                        <th>删除帐户</th>
                    </tr>
                </thead>
                <?php if (!empty($users)) { ?>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                    <tr>
                        <td>
                            <a href="<?php echo $base_url.'index.php/jad_auth_admin/update_user_account/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>">
                                <?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')];?>
                            </a>
                        </td>
                        <td>
                            <?php echo $user['upro_full_name'];?>
                        </td>
                        <td>
                            <?php echo $user[$this->flexi_auth->db_column('user_group', 'name')];?>
                        </td>
                        <td>
                            <a href="<?php echo $base_url.'index.php/jad_auth_admin/update_user_privileges/'.$user[$this->flexi_auth->db_column('user_acc', 'id')];?>"><i class="icon-wrench"></i></a>
                        </td>
                        <td>
                            <input type="hidden" name="current_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="<?php echo $user[$this->flexi_auth->db_column('user_acc', 'suspend')];?>"/>
                            <!-- A hidden 'suspend_status[]' input is included to detect unchecked checkboxes on submit -->
                            <input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
                        
                        <?php if ($this->flexi_auth->is_privileged('Update Users')) { ?>
                            <input type="checkbox" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1" <?php echo ($user[$this->flexi_auth->db_column('user_acc', 'suspend')] == 1) ? 'checked="checked"' : "";?>/>
                        <?php } else { ?>
                            <input type="checkbox" disabled="disabled"/>
                            <small>Not Privileged</small>
                            <input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
                        <?php } ?>
                        </td>
                        <td>
                        <?php if ($this->flexi_auth->is_privileged('Delete Users')) { ?>
                            <input type="checkbox" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="1"/>
                        <?php } else { ?>
                            <input type="checkbox" disabled="disabled"/>
                            <small>Not Privileged</small>
                            <input type="hidden" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>]" value="0"/>
                        <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>	<TFOOT>
      <TR>
         <TD colSpan=6><?php if (! empty($pagination['links'])) { ?>
               总数: 共 <?php echo $pagination['total_users'];?> 条查询结果
               链接: <?php echo $pagination['links'];?>
               <?php } ?>
         </TD> 
         <td colSpan=1>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 更改/删除用户 
  </button>
  <input type="hidden" value="1" name="update_users"/>
         </td>
      </TR>
    </TFOOT>
            <?php } else { ?>
                <tbody>
                    <tr>
                        <td colspan="7">
                            No users are available.
                        </td>
                    </tr>
                </tbody>
            <?php } ?>
            </table>
            <?php echo form_close();?>
                
</div>
<div class="btn-toolbar">
<a href="<?php echo $base_url;?>index.php/jad_auth_admin/add_user_account" class="btn btn-primary" ><i class="icon-plus"></i> 新用户</a>
<div class="btn-group">
</div>
</div>
<?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
$(document).ready(function(){
    $('#del_btn').confirm({
		'title' : '更改/删除用户',
		'message' : '您确定要更改/删除该用户吗,请谨慎操作？!',        
		'action' : function() {
			$('#user_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>
