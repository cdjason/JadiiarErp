<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>产品信息维护</title>
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
        <h1 class="page-title">产品信息维护</h1>
    </div>
            <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">产品信息列表</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="btn-toolbar">
<a href="<?php echo $base_url;?>index.php/jad_goods/add_product" class="btn btn-primary" ><i class="icon-plus"></i> 添加新产品</a>
<div class="btn-group">
</div>
</div>
<?php echo form_open(current_url());?>
<div class="input-append">
<input type="text" class="span2 search-query" id="search" placeholder="货号or产品标题" name="search_query" value="<?php echo $search_query;?>">
<input type="submit" name="search_product" class="btn" value="Search" />
<a class="btn" href="<?php echo $base_url; ?>index.php/jad_goods/manage_product">重设</a>
</div>
<?php echo form_close();?>	

<div class="well">
    <?php $attributes = array('id' => 'product_list_form');echo form_open(current_url(), $attributes);?>  	
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>货号</th>
                        <th>产品标题</th>
                        <th>删除</th>
                    </tr>
                </thead>
                <?php if (!empty($product)) { ?>
                <tbody>
						<?php foreach ($product as $product_item) { ?>
                    <tr>
                        <td><?php echo $product_item['product_id'];?>
                        </td>
                        <td><?php echo $product_item['product_title'];?>
                        </td>
                        <td><input type="checkbox" name="delete_product[<?php echo $product_item['product_id'];?>]" value="1"/>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <TFOOT>
      <TR>
             <TD colSpan=2>
               <?php if (! empty($pagination['links'])) { ?>
               总数: 共 <?php echo $pagination['total_product'];?> 条查询结果
               链接: <?php echo $pagination['links'];?>
               <?php } ?>
             </TD> 
         <td colSpan=1>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 删除选中的产品信息 
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
		'message' : '您确定要删除产品信息吗,请谨慎操作？!',        
		'action' : function() {
			$('#product_list_form').submit();
		}
	});
});
</script>  
  
</body>
</html>

