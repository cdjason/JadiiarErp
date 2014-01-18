<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JaddiarERP</title>
<?php $this->load->view('includes/jad_head'); ?>  
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir;?>CLEditor1_4_3/jquery.cleditor.css">
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
        <div class="stats">
            <p class="stat" id="product_id"><span class="number">状态:</span><?php echo ($productInfo['num_iid'] == '')?"未发布":"已发布"; ?></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $productInfo['product_id']; ?></p>
        </div>
        <h1 class="page-title">更新产品信息</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_products/order_by/num_iid/order_parameter/desc">产品列表</a> <span class="divider">/</span></li>
        <li class="active">更新产品信息</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php $this->load->view('includes/jad_message'); ?>  
            <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
            <div class="row-fluid">
                <div class="span2">
                    <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  更新</button>
                    <input type="hidden" name="update_product" value="1" />
                </div>
                <div class="span1" id = "ajaxPic">
                </div>
            </div></br>

            <div class="well">

                <div class="row-fluid">
                    <div class="span2" ><b>产品名称</b></div>
                    <div class="span10" >
                        <input = "text" id = "product_title" name = "product_title" class="span6" placeholder="不要超过30个字符" value = "<?php echo $productInfo['product_title']; ?>" />
                    </div>
                </div><br>              
                <div class="row-fluid">
                    <div class="span2" ><b>产品品牌</b></div>
                    <div class="span10" >
                        <select name = "product_brand" id = "product_brand">
                        <option value = "">--请选择--</option>
                        <?php for ( $i =0;$i<count($brandList);$i++ ){ 
                        ?>
                        <option value = "<?php echo $brandList[$i]; ?>"><?php echo $brandList[$i]; ?></option>
                        <?php } ?> 

                        </select>
                    </div>
                </div><br>              
                <div class="row-fluid">
                    <div class="span2" ><b>产品图片URL</b></div>
                    <div class="span10" ><input = "text" id = "product_img_url" name = "product_img_url" class="span12" value = "<?php echo $productInfo['product_img_url']; ?>"/></div>
                </div><br>         
                <div class="row-fluid">
                    <div class="span2" ><b>产品描述</b></div>
                    <div class="span10" ><input = "text" id = "product_desc" name = "product_desc" class="span12" placeholder="不要出现标点符号" value = "<?php echo $productInfo['product_desc']; ?>"/></div>
                </div>              
            </div>
            <input type="hidden" name="cid" id="cid"/>
            <input type="hidden" name="inputs_pids" value = "<?php echo $productInfo['inputs_pids']; ?>"/>
            <input type="hidden" name="inputs_str" value = "<?php echo $productInfo['inputs_str']; ?>"/>
            <?php echo form_close();?>
            <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
function checkAll(form){
    if( $("#product_brand")[0].value == ''){
        alert('尚未选择产品品牌，请选择!');
        return false;
    }

}
/*
$.getJSON("http://127.0.0.1/JadiiarErp/top_cats/11", function(json){
  alert(json.childCategoryList.categoryPropList);
});
     */
/*
    $('#form_btn').confirm({
		'title' : '新增产品信息',
		'message' : '您确定要新增该产品信息吗？',        
        'action' : function() {
			$('#test_form').submit();
		}
});
 */

$(document).ready(function(){
    $('#product_brand')[0].value = "<?php echo $productInfo['product_brand']; ?>";
    if ($('#product_brand')[0].options.length == 1){
        alert('获取淘宝店铺品牌信息失败，请检查网络连接!');
    }
});
</script>  
</body>
</html>



