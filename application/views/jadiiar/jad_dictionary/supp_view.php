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
        <h1 class="page-title">供应商信息一览</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">供应商列表</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<?php echo form_open(current_url());	?>
<div class="input-append">
<input type="text" class="span2 search-query" id="search" name="search_query" value="<?php echo set_value('search_suppliers',$search_query);?>">
<input type="submit" name="search_suppliers" class="btn" value="search" />
<a class="btn" href="<?php echo $base_url; ?>index.php/jad_dictionary/manage_suppliers">重设</a>
</div>
<?php echo form_close();?>	
<div class="well">
    <?php $attributes = array('id' => 'suppliers_list_form');echo form_open(current_url(), $attributes);?>  	
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>品牌</th>
                        <th>别名</th>
                        <th>位置</th>
                        <th>电话</th>
                        <th>合作店员</th>
                        <th>联系方式</th>
                        <th>邮寄</th>
                        <th>退税</th>
                        <th>备注</th>
                        <?php if(!$IsBuyer){ ?>
                        <th>相应买手</th>
                        <?php } ?>								
                        <th>编辑</th>							
                        <th>删除</th>
                </tr>
                </thead>
                <?php if (!empty($suppliers)) { ?>
                <tbody>
                    <?php foreach ($suppliers as $supplier) { ?>
                    <tr>
                                <td>
									<?php echo $supplier['suppl_brand'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_alias'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_location'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_phone'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_collaborate'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_collaphone'];?>
								</td>
								<td>
									<?php echo $supplier['suppl_hkmail'] ? "Y":"N";?>
								</td>
								<td>
									<?php echo $supplier['suppl_taxrebate'] ? "Y":"N";?>
								</td>

								<td>
									<?php echo $supplier['suppl_note'];?>
								</td>							
                			    <td style="display:<?php echo ($IsBuyer) ? "none" : "";  ?> ">
									<?php echo $supplier['upro_full_name'];?>
								</td>	               
                		
								<td>
									<a href="<?php echo $base_url.'index.php/jad_dictionary/update_supplier/'.$supplier['suppl_id'];?>">
										编辑
									</a>
								</td>
								<td>
									<input type="checkbox" name="delete_supplier[<?php echo $supplier['suppl_id'];?>]" value="1"/>
								</td>
                    </tr>
                <?php } ?>
                </tbody>	<TFOOT>
      <TR>
         <TD colSpan=8><?php if (! empty($pagination['links'])) { ?>
               总数: 共 <?php echo $pagination['total_suppliers'];?> 条查询结果
               链接: <?php echo $pagination['links'];?>
               <?php } ?>
         </TD> 
         <td colSpan=4>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 更改/删除供应商信息 
  </button>
  <input type="hidden" value="1" name="update_suppliers"/>
         </td>
      </TR>
    </TFOOT>
            <?php } else { ?>
                <tbody>
                    <tr>
                        <td colspan="12">
                            No users are available.
                        </td>
                    </tr>
                </tbody>
            <?php } ?>
            </table>
            <?php echo form_close();?>
                
</div>
<div class="btn-toolbar">
<a href="<?php echo $base_url; ?>index.php/jad_dictionary/add_supplier" class="btn btn-primary" style="display:<?php echo ($IsBuyer) ? "" : "none";  ?> ">
<i class="icon-plus"></i> 增加供应商基本信息</a>
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
		'title' : '更改/删除供应商',
		'message' : '您确定要更改/删除该供应商信息吗？',        
		'action' : function() {
			$('#suppliers_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>
