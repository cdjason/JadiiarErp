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
        <h1 class="page-title">用户组列表</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">用户组列表</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="btn-toolbar">
<a class="btn btn-primary" href="<?php echo $base_url;?>index.php/jad_auth_admin/insert_user_group"><i class="icon-plus"></i> 新用户组</a>
<div class="btn-group">
</div>
</div>
<div class="well">
    <?php $attributes = array('id' => 'group_list_form');echo form_open(current_url(), $attributes);?>  	
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>用户组</th>
                            <th>描述</th>
                            <th>是否管理员组</th>
                            <th>权限</th>
                            <th>删除</th>
                        </tr>
                    </thead>
                    <?php if (!empty($user_groups)) { ?>
                    <tbody>
                    <?php foreach ($user_groups as $group) { ?>
                        <tr>
                            <td>
                                <a href="<?php echo $base_url;?>index.php/jad_auth_admin/update_user_group/<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>">
                                    <?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
                                </a>
                            </td>
                            <td><?php echo $group[$this->flexi_auth->db_column('user_group', 'description')];?></td>
                            <td class="align_ctr"><?php echo ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ? "Yes" : "No";?></td>
                            <td class="align_ctr">
                                <a href="<?php echo $base_url.'index.php/jad_auth_admin/update_group_privileges/'.$group[$this->flexi_auth->db_column('user_group', 'id')];?>"><i class="icon-wrench"></i></a>
                            </td>
                            <td class="align_ctr">
                            <?php if ($this->flexi_auth->is_privileged('Delete User Groups')) { ?>
                                <input type="checkbox" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="1"/>
                            <?php } else { ?>
                                <input type="checkbox" disabled="disabled"/>
                                <small>Not Privileged</small>
                                <input type="hidden" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="0"/>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>	<TFOOT>
          <TR>
             <TD colSpan=4>
             </TD> 
             <td colSpan=1>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 删除选中的用户组 
  </button>
  <input type="hidden" value="1" />
             </td>
          </TR>
        </TFOOT>
                <?php } else { ?>
                    <tbody>
                        <tr>
                            <td colspan="5">
                                No user groups are available.
                            </td>
                        </tr>
                    </tbody>
                <?php } ?>
                </table>
            <?php echo form_close();?>
                
</div>
<?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
$(document).ready(function(){
    $('#del_btn').confirm({
		'title' : '删除用户组',
		'message' : '您确定要删除该用户组吗,请谨慎操作？!',        
		'action' : function() {
			$('#group_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>



