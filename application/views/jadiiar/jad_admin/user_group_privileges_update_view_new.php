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
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">用户组列表</a> <span class="divider">/</span></li>
        <li class="active">用户组权限</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="well">
            <?php echo form_open(current_url());	?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>权限</th>
                        <th>描述</th>
                        <th>删除</th>
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
                        <td><?php 
										// Define form input values.
										$current_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 1 : 0; 
										$new_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 'checked="checked"' : NULL;
									?>
									<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][current_status]" value="<?php echo $current_status ?>"/>
									<input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="0"/>
									<input type="checkbox" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>][new_status]" value="1" <?php echo $new_status ?>/>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <TFOOT>
                <TR><td colSpan=2></td>
         <td colSpan=1><input type="submit" name="update_group_privilege" value="编辑用户组权限" class="btn" /></td>
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
</body>
</html>



