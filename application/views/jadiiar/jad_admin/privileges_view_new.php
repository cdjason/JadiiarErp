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
        <h1 class="page-title">用户组权限一览</h1>
    </div>
            <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">权限列表</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="btn-toolbar">
<a href="<?php echo $base_url;?>index.php/jad_auth_admin/insert_privilege" class="btn btn-primary" ><i class="icon-plus"></i> 添加权限</a>
<div class="btn-group">
</div>
</div>
<div class="well">
    <?php $attributes = array('id' => 'privileges_list_form');echo form_open(current_url(), $attributes);?>  	
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>权限</th>
                        <th>描述</th>
                        <th>拥有权限</th>
                    </tr>
                </thead>
                <?php if (!empty($privileges)) { ?>
                <tbody>
						<?php foreach ($privileges as $privilege) { ?>
                    <tr>
                        <td>
							<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][id]" value="<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>"/>
							<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'name')];?>
                        </td>
                        <td><?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'description')];?>
                        </td>
                        <td>
						<input type="checkbox" name="delete_privilege[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>]" value="1"/>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <TFOOT>
      <TR>
             <TD colSpan=2>
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
                        <td colspan="3">
                            No privileges are available.
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
		'title' : '删除权限',
		'message' : '您确定要删除该权限吗,请谨慎操作？!',        
		'action' : function() {
			$('#privileges_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>
