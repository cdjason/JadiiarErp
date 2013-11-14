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
        <h1 class="page-title">分店信息一览</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">分店信息列表</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="btn-toolbar">
<a class="btn btn-primary" href="<?php echo $base_url;?>index.php/jad_dictionary/add_branch"><i class="icon-plus"></i> 新分店</a>
<div class="btn-group">
</div>
</div>
<div class="well">
    <?php $attributes = array('id' => 'branch_list_form');echo form_open(current_url(), $attributes);?>  	
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
								<th>分店名</th>
								<th>地址</th>
								<th>电话</th>
								<th>邮编</th>
								<th>负责人</th>
								<th>编辑</th>
								<th>删除</th>
                        </tr>
                    </thead>
                    <?php if (!empty($branches)) { ?>
                    <tbody>
                    <?php foreach ($branches as $branch) { ?>
                        <tr>
                                <td>
									<?php echo $branch['branch_name'];?>
								</td>
								<td>
									<?php echo $branch['branch_address'];?>
								</td>
								<td>
									<?php echo $branch['branch_phone'];?>
								</td>
								<td>
									<?php echo $branch['branch_zipcode'];?>
								</td>
								<td>
									<?php echo $branch['upro_first_name'].' '.$branch['upro_last_name'];?>
								</td>					
								<td>
									<a href="<?php echo $base_url.'index.php/jad_dictionary/update_branch/'.$branch['branch_id'];?>">
										Edit
									</a>
								</td>
								<td>
									<input type="checkbox" name="delete_branch[<?php echo $branch['branch_id'];?>]" value="1"/>
								</td>
                        </tr>
                    <?php } ?>
                    </tbody>	<TFOOT>
          <TR>
             <TD colSpan=4>
             </TD> 
             <td colSpan=3>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 删除选中的分店信息 
  </button>
  <input type="hidden" value="1" name="update_branches"/>
             </td>
          </TR>
        </TFOOT>
                <?php } else { ?>
                    <tbody>
                        <tr>
                            <td colspan="7">
                               没有更好的分店信息 
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
		'title' : '删除分店信息',
		'message' : '您确定要删除该分店信息吗,请谨慎操作？!',        
		'action' : function() {
			$('#branch_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>



