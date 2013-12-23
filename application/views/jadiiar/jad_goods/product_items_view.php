<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>商品信息维护</title>
<?php $this->load->view('includes/jad_head'); ?>  
<link rel="stylesheet" href="<?php echo $includes_dir;?>css/jquery.fancybox-1.3.4.css">
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
        <h1 class="page-title">商品信息维护</h1>
    </div>
            <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li class="active">商品信息列表</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
<div class="btn-toolbar">
    <a href="<?php echo $base_url;?>index.php/jad_goods/add_product_item/<?php echo $productId;?>" class="btn btn-primary" ><i class="icon-plus"></i> 添加新商品</a>
<div class="btn-group">
</div>
</div>
<?php echo form_open(current_url());?>
<div class="input-append">
<input type="text" class="span4 search-query" id="search" placeholder="色卡or商品描述or商品编号" name="search_query" value="<?php echo $search_query;?>">
<input type="submit" name="search_item" class="btn" value="Search" />
<a class="btn" href="<?php echo $base_url; ?>index.php/jad_goods/manage_product_items/<?php echo $productId;?>">重设</a>
</div>
<?php echo form_close();?>	

<div class="well">
    <?php $attributes = array('id' => 'product_items_list_form');echo form_open(current_url(), $attributes);?>  	
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>图片</th>
                        <th>商品编号</th>
                        <th>产品编号</th>
                        <th>色卡</th>
                        <th>描述</th>
                        <th>操作</th>
                        <th>删除</th>
                    </tr>
                </thead>
                <?php if (!empty($product_items)) { ?>
                <tbody>
						<?php foreach ($product_items as $pItem) { ?>
                    <tr>
                        <td><a id="fancy_box" href = "<?php echo $pItem['item_img_link'];?>">
                            <img class="img-rounded" title="<?php echo $pItem['item_img_link'];?>" 
                                 src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($pItem['item_img_link']);?>"  >
                        </a></td>
                        <td>
                        <?php echo $pItem['item_id'];?>
                        </td>
                        <td>
                        <?php echo $pItem['product_id'];?>
                        </td>
                        <td>
                        <?php echo $pItem['item_colour'];?>
                        </td>
                        <td>
                        <?php echo $pItem['item_desc'];?>
                        </td>
                        <td><a href = "<?php echo $base_url.'index.php/jad_goods/publish_product_item/'.$pItem['item_id'];?>">发布</a>
                        </td>
                        <td><input type="checkbox" name="delete_item[<?php echo $pItem['item_id'];?>]" value="1"/>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <TFOOT>
      <TR>
             <TD colSpan=5>
<?php if (! empty($pagination['links'])) { ?>
               总数: 共 <?php echo $pagination['total_product_items'];?> 条查询结果
               链接: <?php echo $pagination['links'];?>
               <?php } ?>
             </TD> 
         <td colSpan=2>
  <button type="submit" class="btn" id="del_btn" /><i class="icon-remove"></i> 删除选中的商品信息 
  </button>
  <input type="hidden" value="1" />
         </td>
      </TR>
    </TFOOT>
            <?php } else { ?>
                <tbody>
                    <tr>
                        <td colspan="7">
                            No privileges are available.
                        </td>
                    </tr>
                </tbody>
            <?php } ?>
            </table>
<input type ='hidden' name='productId' value='<?php echo $productId;?>' />
            <?php echo form_close();?>
</div>
<?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script src="<?php echo $includes_dir;?>js/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.fancybox-1.3.4.pack.js"></script> 
<script>
$(document).ready(function(){
    $("a#fancy_box").fancybox({
        'titlePosition'		: 'outside',
        'overlayColor'		: '#000',
        'overlayOpacity'	: 0.9
    });
    $('#del_btn').confirm({
		'title' : '删除商品',
		'message' : '您确定要删除商品信息吗,请谨慎操作？!',        
		'action' : function() {
			$('#product_items_list_form').submit();
		}
	});
});
</script>  
</body>
</html>

