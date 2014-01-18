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
        <div class="stats">
            <p class="stat" id="p_cid"><span class="number">CID:</span><?php echo $productInfo['cid']; ?></p>
            <p class="stat"><span class="number">Product_ID:</span><?php echo $productInfo['product_id']; ?></p>
            <p class="stat"><span class="number">产品标题:</span><?php echo $productInfo['product_title']; ?></p>
        </div>
        <h1 class="page-title">编辑宝贝信息</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/<?php echo $productInfo['product_id']; ?>">商品列表</a> <span class="divider">/</span></li>
        <li class="active">编辑宝贝信息</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  

        <div class="row-fluid">
            <div class="" id = "ajaxPic">
            </div>
        </div></br>

        <div class="row-fluid">
            <div class="span2">
                <img class="img-rounded" src="<?php echo $this->jad_global_model->get_url_sub_image_by_formal($productInfo['product_img_url'],'sq');?>"  >
            </div>
            <div class="span10">
                <table id="sku_items_table" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <td>商品编号</td>
                            <td>色卡</td>
                            <td>SKU描述</td>
                            <td>价格</td>
                            <td>数量</td>
                            <td>发布状态</td>
                            <td>选择</td>
                        </tr>
                    </thead>
                    <?php if (!empty($productItemsInfo)) { 
                    ?>
                    <tbody>
                        <?php foreach ($productItemsInfo as $pItemInfo) { 
                            $isNormal = false;
                            //获取相应的价格与数量
                            if ( $pItemInfo['sku_id'] != '' && $pItemInfo['item_expired'] != '2' ){
                                $isNormal = true;
                                
                                //$skuInfo = $this->jad_goods_model->get_sku_info($pItemInfo['sku_id'],$pItemInfo['num_iid']);
                                //$this->topsdk->autoload('ItemSkuGetRequest');
                                $this->topsdk->req->setFields("sku_id,iid,properties,quantity,price,outer_id,created,modified,status");
                                $this->topsdk->req->setSkuId($pItemInfo['sku_id']);
                                $this->topsdk->req->setNumIid($pItemInfo['num_iid']);
                                $skuInfo = $this->topsdk->get_data();
                            }
                        ?>
                        <tr>
                            <td id = "<?php echo $pItemInfo['iprops'];?>" ><?php echo $pItemInfo['item_id'];?></td>
                            <td><?php echo $pItemInfo['item_colour'];?></td>
                            <td id = "<?php echo $pItemInfo['property_alias'];?>">
                                <?php if (!empty($pItemInfo['property_alias'])){
                                            $skuDescStr = ''; 
                                            $skuDesc = explode(";",$pItemInfo['property_alias']);
                                            for($i = 0 ; $i < count($skuDesc) ; $i++){
                                                $skuItemDesc = explode(":",$skuDesc[$i]);
                                                $skuDescStr = $skuDescStr.' & '.$skuItemDesc[2]; 
                                            }
                                            $skuDescStr = substr($skuDescStr,3);
                                            echo $skuDescStr;
                                      }
                                ?>
                            </td>
                            <td><input type = "text" class='span6' name = "sku_item_price" value ="<?php echo ( $isNormal ) ? $skuInfo['sku']['price'] : "" ;?>" /></td>
                            <td><input type = "text" class='span6' name = "sku_item_num" value ="<?php echo ( $isNormal ) ? $skuInfo['sku']['quantity'] : "" ;?>" /></td>
                            <td id = "<?php echo $pItemInfo['sku_id']; ?>" ><?php echo ( $isNormal ) ? "已发布" : "" ;?></td>
                            <td><input type="checkbox" name="publish_item_checkbox" value="1" <?php echo ( $isNormal ) ? "checked" : "";?> /></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>


        <?php $attributes = array('id' => 'test_form','class' => 'form-horizontal','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
<div class="control-group">
    <label class="control-label" for="product_price">一口价</label>
    <div class="controls">
    <input type="text" name="product_price" value="<?php echo $itemInfo['price'];?>" id="product_price" placeholder="价格位于商品SKU价格范围之内">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="product_num">数量</label>
    <div class="controls">
        <input type="text" name="product_num" value="<?php echo $itemInfo['num'];?>" id="product_num" placeholder="数量为商品SKU数量之和">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="product_title">宝贝标题</label>
    <div class="controls">
        <input type="text" name="product_title" value="<?php echo $itemInfo['title'];?>" id="product_title" class="span12" >
    </div>
</div>
<div class="control-group" id="location_bought_div">
    <label class="control-label" for="location_bought">采购地</label>
    <div class="controls">
        <label class="radio inline" style="margin-left:1cm">
        <input type="radio" name="location_bought" checked value="1" onclick="createGlobalStock(this.value)" />国内
        </label>
        <label class="radio inline" style="margin-left:2cm">
        <input type="radio" name="location_bought" value="2" onclick="createGlobalStock(this.value)" />国外及港澳台
        </label>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="publish_shop">上架店铺</label>
    <div class="controls">
        <select id="publish_shop" name="publish_shop" disabled>
        <option value="">--请选择--</option>
        <option value="肥肥9089">CJ测试用，勿选</option>
        <option value="jadiiar">JADIIAR 总店</option>
        <option value="siiena">SIIENA 姐妹店</option>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="seller_cats">店铺类别</label>
    <div class="controls well" style="max-height: 200px;overflow-y: scroll;" id="seller_cats">
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="product_title">宝贝描述</label>
    <div class="controls">
        <textarea name="product_desc" id="product_desc"><?php echo $itemInfo['desc'];?></textarea>
    </div>
</div>
        <div class="row-fluid">
            <div class="span2 pull-right">
                <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  确定</button>
                <input type="hidden" name="publish_product_items" value="1" />
            </div>
        </div>

        <input type="hidden" name = "product_id"  id="product_id" value = '<?php echo $productId; ?>' />
        <input type="hidden" name = "num_iid" id="num_iid" value = '<?php echo $productInfo['num_iid']; ?>' />

        <input type="hidden" name = "cid" id= "cid" value = '<?php echo $productInfo['cid']; ?>' />
        <input type="hidden" name = "product_props" value = '<?php echo $productInfo['props']; ?>' />
        <input type="hidden" name = "product_item_props" id = "product_item_props" />
        <input type="hidden" name = "productTitle" id = "productTitle" value = '<?php echo $productInfo['product_title']; ?>' />
        <input type="hidden" name = "sku_properties" id= "sku_properties" />
        <input type="hidden" name = "sku_quantities" id= "sku_quantities" />
        <input type="hidden" name = "sku_prices" id= "sku_prices" />
        <input type="hidden" name = "sku_outer_ids" id= "sku_outer_ids" />
        <input type="hidden" name = "props_property_alias" id = "props_property_alias" />
        <input type="hidden" name = "sku_properties_for_del" id = "sku_properties_for_del" />
        <input type="hidden" name = "sku_properies_for_edit" id = "sku_properies_for_edit" />
        <input type="hidden" name = "product_items_num" id= "product_items_num" />
        <input type="hidden" name = "format_item_desc" id = "format_item_desc" />
        <input type="hidden" name = "seller_cats_str" id = "seller_cats_str" />
	    <?php echo form_close();?>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
Array.prototype.max = function(){ 
    return Math.max.apply({},this) 
} 
Array.prototype.min = function(){ 
    return Math.min.apply({},this) 
} 

//ajax方法，更新sku信息
function update_sku_items(){
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);
    //遍历sku_item_table，获取sku相关参数
    var sku_properties_for_del = "";
    var sku_properties = "";
    var sku_property_alias = "";
    var item_props = "";
    var sku_quantities = "";   
    var sku_prices = "";
    var sku_outer_ids = "";
    var product_items_num = 0;
    var skuPropValues = $("#sku_items_table")[0];
    var propsArray = new Array();

    var sku_properies_for_edit_array = new Array();
    var sku_properies_for_del_array = new Array();
    //防止对没有销售属性的产品进行sku表的遍历 
    if( skuPropValues.tBodies.length > 0 ){
        //对sku属性列表进行遍历，获取需要更新的sku的详细值，获取需要删除的sku的id值
        for(var i = 0 ; i < skuPropValues.tBodies[0].rows.length ; i++ ){
            //获取需要更新的sku的详细值，必须要checked以后的才可以发布
            if (skuPropValues.tBodies[0].rows[i].cells[skuPropValues.tBodies[0].rows[i].cells.length - 1].childNodes[0].checked) {
                //对checked的销售属性的输入信息进行验证，目前只设置了判空验证
                if( !isfloat( skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value )  || !checkNum( skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value )){
                    alert("宝贝SKU的价格信息必须是最多两位小数的正实数，数量信息必须是正整数!");
                    return false;
                } 
                //获取outer_id
                sku_outer_ids = sku_outer_ids + "," + skuPropValues.tBodies[0].rows[i].cells[0].innerText;
                //获取sku价格
                sku_prices = sku_prices + "," + skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value;
                //pricesArray.push(skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value); 
                //获取sku数量
                sku_quantities = sku_quantities + "," + skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value;
                //设置宝贝总的数量
                product_items_num += parseInt(skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value);
                //props属性值需要重新提取，不能用单条item中的iprop
                var propsArr = skuPropValues.tBodies[0].rows[i].cells[2].id.split(";");
                var sku_property = '';
                for (var j = 0; j < propsArr.length; j++){
                    //取第二个冒号之前的数据
                    var propsAlias = propsArr[j].split(":");
                    sku_property = sku_property + ';' + propsAlias[0]+':'+propsAlias[1];
                    if(propsArray.indexOf(propsAlias[0]+':'+propsAlias[1]) <0 ){
                        propsArray.push(propsAlias[0]+':'+propsAlias[1]);
                        item_props = item_props + ';' + propsAlias[0]+':'+propsAlias[1];
                        sku_property_alias = sku_property_alias + ';' + propsArr[j];
                    }
                }
                sku_property = sku_property.substr(1);
                //sku_properties = sku_properties.substr(1);
                sku_properties = sku_properties + ',' + sku_property; 
                //将完整的sku参数放入该数组,包括sku_id
                var sku_properies_for_edit_item_array = new Array();
                sku_properies_for_edit_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[0].innerText,skuPropValues.tBodies[0].rows[i].cells[5].id);

                sku_properies_for_edit_array.push(sku_properies_for_edit_item_array);
            }else if( skuPropValues.tBodies[0].rows[i].cells[5].id != '') {
                //获取需要删除的sku的sku_properies值
                var sku_properies_for_del_item_array = new Array();
                sku_properies_for_del_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[0].innerText); 
                sku_properies_for_del_array.push(sku_properies_for_del_item_array);
            }

        }
        item_props = item_props.substr(1); 
        sku_property_alias = sku_property_alias.substr(1); 
        sku_outer_ids = sku_outer_ids.substr(1);
        sku_properties = sku_properties.substr(1);
        sku_prices = sku_prices.substr(1);
        sku_quantities = sku_quantities.substr(1);
        $("#sku_properies_for_edit")[0].value = sku_properies_for_edit_array.join('#');
        $("#sku_properties_for_del")[0].value = sku_properies_for_del_array.join('#');
    }

    //编辑宝贝sku的时候，必须至少选择一个销售属性进行发布
    if(  sku_outer_ids == '' ){
        alert("请选择要发布的宝贝SKU，至少选择一项!");
        return false;
    }
    $("#product_item_props")[0].value = item_props;
    $("#sku_outer_ids")[0].value = sku_outer_ids;
    $("#sku_properties")[0].value = sku_properties;
    $("#sku_prices")[0].value = sku_prices;
    $("#sku_quantities")[0].value = sku_quantities;
    $("#props_property_alias")[0].value = sku_property_alias;
    $.ajax({
        type:"post",
        data: "skuPropEdit=" + $("#sku_properies_for_edit")[0].value + "&skuPropDel=" + $("#sku_properties_for_del")[0].value + "&numIid=" + $("#num_iid")[0].value,
        url: "<?php echo base_url();?>index.php/jad_goods/update_sku_items",
        success: function(prop_data){
            alert(prop_data);

            $("#sku_set")[0].innerHTML = '';
            createSkuItemsList($("#product_id")[0].value);
            $("#ajaxPic")[0].innerHTML = '';
        },          
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    });
}

//ajax方法，编辑宝贝信息
function update_item_info(){
    //宝贝标题不能为空
    if( $("#product_title")[0].value == '' || $("#product_title")[0].value.length >30 ){
        alert("宝贝标题必须填写且小于30个字符!");
        return false;
    }
    //遍历店铺类目，获取选择的类目cid串
    var seller_cats_str= "";
    var checkDIV = $("#seller_cats")[0];
    for (var i=0;i<checkDIV.childNodes.length;i++){
        if ( checkDIV.childNodes[i].childNodes.length > 1 && checkDIV.childNodes[i].childNodes[1].checked){
            seller_cats_str = seller_cats_str + "," + checkDIV.childNodes[i].childNodes[1].value;
        }
    }
    seller_cats_str = seller_cats_str.substr(1);
    //至少选择一种类别
    if (seller_cats_str == '' ){
        alert("至少选择一项店铺类目!");
    }
    //1、宝贝描述信息不能为空
    if( $("#product_desc")[0].value == '' ){
        alert('宝贝描述信息不能为空!');
        return false;
    }    
    //2、宝贝价格信息不能为空,且必须为正整数！
    if (!checkNum($("#product_price")[0].value)){
        alert('宝贝价格信息不能为空，且必须为正整数!');
        return false;
    }else if($("#product_price")[0].value < pricesArray.min() || $("#product_price")[0].value > pricesArray.max()){
        //3、宝贝价格必须处于sku价格之间
        alert('宝贝的价格必须处于sku价格之间!');
        return false;
    }

    //3、宝贝数量信息不能为空,且必须为正整数！
    if (!checkNum($("#product_num")[0].value)){
        alert('宝贝数量信息不能为空，且必须为正整数!');
        return false;
    }
    //采购地的地区必须选择
    var location_bought='';
    robj=document.getElementsByName("location_bought");
    for(i=0;i<robj.length;i++){
        if(robj[i].checked){
            location_bought = robj[i].value;
        }
    }

    var global_type='';
    var global_stock='';
    if (location_bought == '2'){
        //必须选择采购地的地区
        if ($("#sel_global_stock")[0].value == ''){
            alert("采购地的地区必须选择!");
            return false;
        }
        global_stock=$("#sel_global_stock")[0].value;
        gt=document.getElementsByName("global_type");
        for(i=0;i<gt.length;i++){
            if(gt[i].checked){
                global_type = gt[i].value;
            }
        }
    }

    $.ajax({
        type:"post",
        data: "price=" + $("#product_price")[0].value + "&num=" + $("#product_num")[0].value + "&title=" + $("#product_title")[0].value + "&sellerCats=" + seller_cats_str + "&desc=" + encodeURIComponent(HTMLEncode($("#product_desc")[0].value)) + "&locationBought=" + location_bought + "&globalType=" + global_type + "&globalStock=" + global_stock,
        url: "<?php echo base_url();?>index.php/jad_goods/update_item_info",
        success: function(prop_data){
            alert(prop_data);
        },          
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    });
}
function checkNum(obj)
{
     var re = /^[1-9]\d*$/;
     //alert(re.test(obj));
     return re.test(obj);
} 

function isfloat(oNum){
    if(!oNum) return false;
    var strP=/^[0-9]+(.[0-9]{2})?$/;
    if(!strP.test(oNum)) return false;
    try{
        if(parseFloat(oNum)!=oNum) return false;
    }catch(ex){
        return false;
    }
    return true;
}

function HTMLEncode( input ){
    var converter = document.createElement("DIV");
    converter.innerText = input;
    var output = converter.innerHTML;
    converter = null;
    return output;
}
//获取非关键属性中必选属性链pid:vid
function checkAll(form){
    //遍历sku_item_table，获取sku相关参数
    var sku_properties_for_del = "";
    var sku_properties = "";
    var sku_property_alias = "";
    var item_props = "";
    var sku_quantities = "";   
    var sku_prices = "";
    var sku_outer_ids = "";
    var product_items_num = 0;
    var skuPropValues = $("#sku_items_table")[0];
    var propsArray = new Array();
    var pricesArray = new Array();
    var pricesArray = new Array();

    var sku_properies_for_edit_array = new Array();
    var sku_properies_for_del_array = new Array();
    //防止对没有销售属性的产品进行sku表的遍历 
    if( skuPropValues.tBodies.length > 0 ){
        //对sku属性列表进行遍历，获取需要更新的sku的详细值，获取需要删除的sku的id值
        for(var i = 0 ; i < skuPropValues.tBodies[0].rows.length ; i++ ){
            //获取需要更新的sku的详细值，必须要checked以后的才可以发布
            if (skuPropValues.tBodies[0].rows[i].cells[skuPropValues.tBodies[0].rows[i].cells.length - 1].childNodes[0].checked) {
                //对checked的销售属性的输入信息进行验证，目前只设置了判空验证
                if( !isfloat( skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value )  || !checkNum( skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value )){
                    alert("宝贝SKU的价格信息必须是最多两位小数的正实数，数量信息必须是正整数!");
                    return false;
                } 
                //获取outer_id
                sku_outer_ids = sku_outer_ids + "," + skuPropValues.tBodies[0].rows[i].cells[0].innerText;
                //获取sku价格
                sku_prices = sku_prices + "," + skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value;
                pricesArray.push(skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value); 
                //获取sku数量
                sku_quantities = sku_quantities + "," + skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value;
                //设置宝贝总的数量
                product_items_num += parseInt(skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value);
                //props属性值需要重新提取，不能用单条item中的iprop
                var propsArr = skuPropValues.tBodies[0].rows[i].cells[2].id.split(";");
                var sku_property = '';
                for (var j = 0; j < propsArr.length; j++){
                    //取第二个冒号之前的数据
                    var propsAlias = propsArr[j].split(":");
                    sku_property = sku_property + ';' + propsAlias[0]+':'+propsAlias[1];
                    if(propsArray.indexOf(propsAlias[0]+':'+propsAlias[1]) <0 ){
                        propsArray.push(propsAlias[0]+':'+propsAlias[1]);
                        item_props = item_props + ';' + propsAlias[0]+':'+propsAlias[1];
                        sku_property_alias = sku_property_alias + ';' + propsArr[j];
                    }
                }
                sku_property = sku_property.substr(1);
                //sku_properties = sku_properties.substr(1);
                sku_properties = sku_properties + ',' + sku_property; 
                //将完整的sku参数放入该数组,包括sku_id
                var sku_properies_for_edit_item_array = new Array();
                sku_properies_for_edit_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value,skuPropValues.tBodies[0].rows[i].cells[0].innerText,skuPropValues.tBodies[0].rows[i].cells[5].id);

                sku_properies_for_edit_array.push(sku_properies_for_edit_item_array);
            }else if( skuPropValues.tBodies[0].rows[i].cells[5].id != '') {
                //获取需要删除的sku的sku_properies值
                var sku_properies_for_del_item_array = new Array();
                sku_properies_for_del_item_array.push(skuPropValues.tBodies[0].rows[i].cells[0].id,skuPropValues.tBodies[0].rows[i].cells[0].innerText); 
                sku_properies_for_del_array.push(sku_properies_for_del_item_array);
            }

        }
        item_props = item_props.substr(1); 
        sku_property_alias = sku_property_alias.substr(1); 
        sku_outer_ids = sku_outer_ids.substr(1);
        sku_properties = sku_properties.substr(1);
        sku_prices = sku_prices.substr(1);
        sku_quantities = sku_quantities.substr(1);
    }
    //宝贝标题不能为空
    if( $("#product_title")[0].value == '' || $("#product_title")[0].value.length >30 ){
        alert("宝贝标题必须填写且小于30个字符!");
        return false;
    }
    //遍历店铺类目，获取选择的类目cid串
    var seller_cats_str= "";
    var checkDIV = $("#seller_cats")[0];
    for (var i=0;i<checkDIV.childNodes.length;i++){
        if ( checkDIV.childNodes[i].childNodes.length > 1 && checkDIV.childNodes[i].childNodes[1].checked){
            seller_cats_str = seller_cats_str + "," + checkDIV.childNodes[i].childNodes[1].value;
        }
    }
    seller_cats_str = seller_cats_str.substr(1);
    //至少选择一种类别
    if (seller_cats_str == '' ){
        alert("至少选择一项店铺类目!");
        return false;
    }

    //修改宝贝sku的时候，必须至少选择一个销售属性进行发布
    if(  sku_outer_ids == '' ){
        alert("请选择要发布的宝贝SKU，至少选择一项!");
        return false;
    }

    //1、宝贝描述信息不能为空
    if( $("#product_desc")[0].value == '' ){
        alert('宝贝描述信息不能为空!');
        return false;
    }
    //2、宝贝价格信息不能为空,且必须为正整数！
    if ( !isfloat($("#product_price")[0].value) ){
        alert('一口价信息必须是最多两位小数的正实数!');
        return false;
    }else if($("#product_price")[0].value < pricesArray.min() || $("#product_price")[0].value > pricesArray.max()){
        //3、宝贝价格必须处于sku价格之间
        alert('宝贝的价格必须处于sku价格之间!');
        return false;
    }

    //3、宝贝数量信息不能为空,且必须为正整数！
    if (!checkNum($("#product_num")[0].value)){
        alert('宝贝数量信息不能为空，且必须为正整数!');
        return false;
    }
    //采购地的地区必须选择
    var location_bought='';
    robj=document.getElementsByName("location_bought");
    for(i=0;i<robj.length;i++){
        if(robj[i].checked){
            location_bought = robj[i].value;
        }
    }
    var global_type='';
    var global_stock='';
    if (location_bought == '2'){
        //必须选择采购地的地区
        if ($("#sel_global_stock")[0].value == ''){
            alert("采购地的地区必须选择!");
            return false;
        }
        global_stock=$("#sel_global_stock")[0].value;
        gt=document.getElementsByName("global_type");
        for(i=0;i<gt.length;i++){
            if(gt[i].checked){
                global_type = gt[i].value;
            }
        }
    }

    $("#seller_cats_str")[0].value = seller_cats_str;
    $("#product_item_props")[0].value = item_props;
    $("#sku_outer_ids")[0].value = sku_outer_ids;
    $("#sku_properties")[0].value = sku_properties;
    $("#sku_prices")[0].value = sku_prices;
    $("#sku_quantities")[0].value = sku_quantities;
    $("#props_property_alias")[0].value = sku_property_alias;
    $("#sku_properies_for_edit")[0].value = sku_properies_for_edit_array.join('#');
    $("#sku_properties_for_del")[0].value = sku_properies_for_del_array.join('#');
    $("#product_items_num")[0].value = product_items_num;
    $("#format_item_desc")[0].value = HTMLEncode($("#product_desc")[0].value);
 

}
function insertAfter(newElement,targetElement) {
    var parent = targetElement.parentNode;
    if (parent.lastChild == targetElement) {
        // 如果最后的节点是目标元素，则直接添加。因为默认是最后
        parent.appendChild(newElement);
    } else {
        //如果不是，则插入在目标元素的下一个兄弟节点 的前面。也就是目标元素的后面。
        parent.insertBefore(newElement,targetElement.nextSibling);
    }
}
function setCheckedValue(radioName, newValue) {  
    if(!radioName) return;  
       var radios = document.getElementsByName(radioName);     
       for(var i=0; i<radios.length; i++) {  
           radios[i].checked = false;  
          if(radios[i].value == newValue.toString()) {  
           radios[i].checked = true;  
        }  
         }  
}  

//对于必填属性中的子属性进行展示
function parentPropList(o){
    //一部分属性存在子属性，而另一部分属性不存在子属性
	if ('' == o.value) {
        pid = o.name.substr(4);
        //需要把之前显示该属性的子属性的HTML元素给去掉
        if (document.getElementById('pid_' + pid + '_subDiv') != null){
            document.getElementById('pid_' + pid + '_subDiv').outerHTML = '';
        }
		//document.getElementById(o.id + '_span').innerHTML = '';
    } else {
        var pidVidArr = o.value.split(':');
		if (1 === pidVidArr.length && pidVidArr[0]) {
			pidVidArr = pidVidArr[0].split('_');
			var pid = pidVidArr[0];
			var childName = '';

			var txt = document.createElement('input');
			txt.type = 'text';
			txt.name = 'input_' + pid;
			txt.id = 'input_' + pid;
            txt.setAttribute('class', 'span6');
			txt.value = '';
            //添加作为附加子属性的元素
            var propDiv = document.createElement('div');
            propDiv.id =  o.id + '_subDiv';
            propDiv.setAttribute('class', 'row-fluid');

            var propDivName = document.createElement('div');
            propDivName.id =  o.id + '_subName';
            propDivName.setAttribute('class', 'span2 offset1');

            var propDivValue = document.createElement('div');
            propDivValue.id =  o.id + '_subValue';
            propDivValue.setAttribute('class', 'span9');

            propDivValue.appendChild(txt);
            propDivName.appendChild(document.createTextNode("自定义："));

            propDiv.appendChild(propDivName);
            propDiv.appendChild(propDivValue);
            insertAfter(propDiv,o.parentNode.parentNode);
        }else {
			var pid = pidVidArr[0];
			var vid = pidVidArr[1];
            //需要把之前显示该属性的子属性的HTML元素给去掉
            if (document.getElementById('pid_' + pid + '_subDiv') != null){
                document.getElementById('pid_' + pid + '_subDiv').outerHTML = '';
            }
            //获取在当前叶子cid和pid的下面，以pid为父亲属性的子属性数据，在AJAX获取的过程中首先就需要把提交的按钮给disabled了
            $("#form_btn")[0].disabled = 'disabled';
            var ajaxPic = document.createElement('img');
            ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
            $("#ajaxPic")[0].appendChild(ajaxPic);

            $.ajax({
                type:"post",
                data: "cId=" + $("#cid").val() + "&parentPId=" + pid,
                url: "<?php echo base_url();?>index.php/jad_goods/get_child_itemprops",
                success: function(prop_data){
                    var prop_data = eval('('+prop_data+')');
                    if(prop_data==''){
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                        return false;
                    }else if ( prop_data.code === 0){
                        //当网络链接失败的时候，也会在这个地方返回数据；一定要注意对格式进行处理
                        alert('网络访问失败，请检查网络设置!');
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                        return false;
                    }
                    prop_data = prop_data.item_props.item_prop;
                    var i;
                    for (i in prop_data) {
                        if (pid == prop_data[i].parent_pid && vid == prop_data[i].parent_vid) {
                            var sel = document.createElement('SELECT');
                            sel.setAttribute('name', 'pid_' + prop_data[i]['pid']);
                            sel.setAttribute('id', 'pid_' + prop_data[i]['pid']);
                            sel.setAttribute('class', 'span6');
                            var op = document.createElement('OPTION');
                            op.setAttribute('value', '');
                            op.innerHTML = '--请选择--';
                            sel.appendChild(op);
                            //alert(prop_data[i].prop_values.prop_value[0]['name']);
                            var propvalues = prop_data[i].prop_values.prop_value;
                            if(propvalues!=''){
                               //alert(propvalues[0].name_alias); 
                               var j;
                               for(j in propvalues){
                               op = document.createElement('OPTION');
                               op.setAttribute('value', prop_data[i]['pid'] + ':' + propvalues[j]['vid']);
                               op.innerHTML = propvalues[j]['name'];
                               sel.appendChild(op);
                               }
                            }
                            if (prop_data[i]['is_input_prop']) {
                                op = document.createElement('OPTION');
                                op.setAttribute('value', prop_data[i].pid);
                                op.innerHTML = '自定义';
                                sel.appendChild(op);
                            }
                            //添加作为附加子属性的元素
                            var propDiv = document.createElement('div');
                            propDiv.id = 'pid_' + pid + '_subDiv';
                            propDiv.setAttribute('class', 'row-fluid');

                            var propDivName = document.createElement('div');
                            propDivName.id =  pid + '_subName';
                            propDivName.setAttribute('class', 'span2 offset1');

                            var propDivValue = document.createElement('div');
                            propDivValue.id =  pid + '_subValue';
                            propDivValue.setAttribute('class', 'span9');

                            propDivValue.appendChild(sel);
                            propDivName.appendChild(document.createTextNode(prop_data[i]['name']));

                            propDiv.appendChild(propDivName);
                            propDiv.appendChild(propDivValue);

                            insertAfter(propDiv,o.parentNode.parentNode);
                          }
                        }
                        $("#form_btn")[0].disabled = '';
                        $("#ajaxPic")[0].innerHTML = '';
                },          
                error: function(){
                    alert("获取数据失败，请与网站管理员联系！");
                }
            });
		} 
    }
} 
//创建非关键属性中必选属性的选择表单
function createPropsForm(cid) {
    //对提交按钮做出处理
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);
    //获取该cid下的销售属性
    $.ajax({
        type:"post",
        data: "parentId=" + cid,
        url: "<?php echo base_url();?>index.php/jad_goods/get_itemprops_by_cid",
        success: function(data){
            var props = eval('('+data+')');
            if(props==''){
                $("#form_btn")[0].disabled = '';
                $("#ajaxPic")[0].innerHTML = '';
                return false;
            }
            props = props.item_props.item_prop;
            var i;
            for (i in props) {
                //对数据进行处理,获取每一项销售属性
                if (props[i].is_sale_prop == false && props[i].is_key_prop == false && props[i].must == true) {
                    if (props[i].is_enum_prop) {
                        var sel = document.createElement('SELECT');
                        sel.setAttribute('name', 'pid_' + props[i].pid);
                        sel.setAttribute('id', 'pid_' + props[i].pid);
                        sel.setAttribute('class', 'span6');

                        var op = document.createElement('OPTION');
                        op.setAttribute('value', '');
                        op.innerHTML = '--请选择--';
                        sel.appendChild(op);

                        $.ajax({
                            type:"post",
                            data: "cId="+cid+"&pId=" + props[i].pid,
                            url: "<?php echo base_url();?>index.php/jad_goods/get_propvalues",
                            async: false,
                            success: function(prop_data){
                               var propvalues = eval('(' + prop_data + ')');
                               if(propvalues!=''){
                                   propvalues = propvalues.prop_values.prop_value;
                                   var j;
                                   for(j in propvalues){
                                       op = document.createElement('OPTION');
                                       op.setAttribute('value', propvalues[j].pid + ':' + propvalues[j].vid);
                                       op.innerHTML = propvalues[j].name_alias;
                                       sel.appendChild(op);
                                   }
                               } 
                            },          
                            error: function(){
                                alert("获取数据失败，请与网站管理员联系！");
                            }
                        }); 
                        //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                        var propDiv = document.createElement('div');
                        propDiv.id = 'pid_' + props[i]['pid'] + '_div';
                        propDiv.setAttribute("class", "row-fluid"); 

                        var propDivName = document.createElement('div');
                        propDivName.id = 'pid_' + props[i]['pid'] + '_divName';
                        propDivName.setAttribute('class', 'span2');

                        var propDivValue = document.createElement('div');
                        propDivValue.id = 'pid_' + props[i]['pid'] + '_divValue';
                        propDivValue.setAttribute('class', 'span10');

                        propDivName.appendChild(document.createTextNode(props[i].name+"："));
                        propDivValue.appendChild(sel);

                        propDiv.appendChild(propDivName);
                        propDiv.appendChild(propDivValue);
                        $("#enum-must-props")[0].appendChild(propDiv);
                        document.getElementById('pid_' + props[i].pid).onchange = function(){parentPropList(this)};
                    }
                }
            }
            $("#form_btn")[0].disabled = '';
            $("#ajaxPic")[0].innerHTML = '';
        },
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    }); 
}
//AJAX创建商品信息列表
function createSkuItemsList(pId){

    //$("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);

    //获取商品信息列表
    $.ajax({
        type:"post",
        data: "pId=" + pId,
        url: "<?php echo base_url();?>index.php/jad_goods/get_product_items_by_pId",
        success: function(items_data){
            var itemsList = eval('(' + items_data + ')');
            //对商品列表进行遍历，并生成TABLE中的元素

            var skuTable = document.createElement('table');//生成table
            skuTable.id = 'sku_items_table';
            skuTable.setAttribute('class', 'table table-bordered table-condensed');

            var skuThead = document.createElement('thead');//生成thead
            var skuTheadTr = document.createElement('tr');//生成tr

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("商品编号")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("色卡")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("sku描述")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("价格")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("数量")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("发布状态")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            var skuTheadTrTd = document.createElement('td');//生成td
            skuTheadTrTd.appendChild(document.createTextNode("选择")); 
            skuTheadTr.appendChild(skuTheadTrTd); 

            skuThead.appendChild(skuTheadTr); 


            var skuTbody = document.createElement('tbody');//生成tbody
            for(var j=0;j<itemsList.length;j++){ //循环添加每一行
                //AJAX获取每种已发布的sku的相关信息
                var isPublished = false;//
                var skuData = '';
                if (itemsList[j].sku_id != '' && itemsList[j].item_expired != '2'){//当商品信息的sku_id不为空且未过期时，说明该商品当前正发布
                    isPublished = true;
                $.ajax({
                    type:"post",
                    data: "skuId=" + itemsList[j].sku_id + "&numIid=" + itemsList[j].num_iid,
                    async: false,
                    url: "<?php echo base_url();?>index.php/jad_goods/get_sku_info_by_sId",
                    success: function(sku_data){
                        skuData = eval('(' + sku_data + ')');
                    },
                    error: function(){
                        alert("获取数据失败，请与网站管理员联系！");
                    }
                }); 
                                    
                }
                var skuTbodyTr = document.createElement('tr');
                var skuTbodyTrTd = document.createElement('td');//生成td
                skuTbodyTrTd.id = itemsList[j].iprops;
                skuTbodyTrTd.appendChild(document.createTextNode(itemsList[j].item_id)); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                skuTbodyTrTd.appendChild(document.createTextNode(itemsList[j].item_colour)); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                skuTbodyTrTd.id = itemsList[j].property_alias;
                if ( itemsList[j].property_alias != ''){
                    var skuDescStr = '';
                    skuDesc = itemsList[j].property_alias.split(';');
                    for(var i=0;i<skuDesc.length;i++){
                        skuItemDesc = skuDesc[i].split(':');
                        skuDescStr = skuDescStr + ' & ' + skuItemDesc[2];
                    }
                    skuDescStr = skuDescStr.substr(3);
                    skuTbodyTrTd.appendChild(document.createTextNode(skuDescStr)); 
                }
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                var skuPriceTd = document.createElement('input');
                skuPriceTd.type = 'text';
                skuPriceTd.setAttribute('class', 'span6');
                skuPriceTd.name = 'sku_item_price';
                if (isPublished) {
                    skuPriceTd.value = skuData.price;
                    pricesArray.push(skuData.price); 
                }
                skuTbodyTrTd.appendChild(skuPriceTd); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                var skuNumTd = document.createElement('input');
                skuNumTd.type = 'text';
                skuNumTd.setAttribute('class', 'span6');
                skuNumTd.name = 'sku_item_num';
                if (isPublished) skuNumTd.value = skuData.quantity;
                skuTbodyTrTd.appendChild(skuNumTd); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                skuTbodyTrTd.id = itemsList[j].sku_id;
                if (isPublished) 
                skuTbodyTrTd.appendChild(document.createTextNode('已发布')); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                var skuTbodyTrTd = document.createElement('td');//生成td
                var skuCheckTd = document.createElement('input');
                skuCheckTd.type = 'checkbox';
                //skuCheckTd.setAttribute('class', 'span6');
                skuCheckTd.name = 'publish_item_checkbox';
                skuCheckTd.value = "1";
                if (isPublished) skuCheckTd.checked = "checked";
                skuTbodyTrTd.appendChild(skuCheckTd); 
                skuTbodyTr.appendChild(skuTbodyTrTd); 
                skuTbody.appendChild(skuTbodyTr);
                //alert(itemsList[j].item_id);
            }
            skuTable.appendChild(skuThead);
            skuTable.appendChild(skuTbody);
            sku_set.appendChild(skuTable);

            $("#ajaxPic")[0].innerHTML = '';
        },          
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    }); 
}

//AJAX创建宝贝信息编辑页面
function createItemInfoEdit(numIid){

    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('一口价'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');
    var txt = document.createElement('input');//生成div
    txt.type = "text";
    txt.name = "product_price";
    txt.id = "product_price";
    txt.setAttribute('placeholder', '价格位于商品SKU价格范围之内');
    controlsDiv.appendChild(txt);
    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);

    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('数量'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');
    var txt = document.createElement('input');//生成div
    txt.type = "text";
    txt.name = "product_num";
    txt.id = "product_num";
    txt.setAttribute('placeholder', '数量为商品SKU数量之和');
    controlsDiv.appendChild(txt);
    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);

    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('宝贝标题'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');
    var txt = document.createElement('input');//生成div
    txt.type = "text";
    txt.name = "product_title";
    txt.id = "product_title";
    txt.setAttribute('class', 'span12');
    controlsDiv.appendChild(txt);
    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);


    //生成选择宝贝采购地的radio选择框***********************************************************************************/
    //***********************************************************************************************************/
    var controlDiv = document.createElement('div');//生成div
    controlDiv.id = "location_bought_div";
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label inline');
    controlLabel.appendChild(document.createTextNode('采购地'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');

    var radioLabel = document.createElement('label');
    radioLabel.setAttribute('class','radio inline');
    radioLabel.setAttribute('style', 'margin-left:1cm');
    var radio = document.createElement('input');//生成textarea
    radio.type = "radio";
    radio.name = "location_bought";
    radio.id = "location_bought";
    radio.value = "1";
    radio.checked = "checked";
    radioLabel.appendChild(radio);
    radioLabel.appendChild(document.createTextNode('国内'));
    controlsDiv.appendChild(radioLabel);

    var radioLabel = document.createElement('label');
    radioLabel.setAttribute('class','radio inline');
    radioLabel.setAttribute('style', 'margin-left:2cm');
    var radio = document.createElement('input');//生成textarea
    radio.type = "radio";
    radio.name = "location_bought";
    radio.id = "location_bought";
    radio.value = "2";
    radioLabel.appendChild(radio);
    radioLabel.appendChild(document.createTextNode('国外及港澳台'));
    controlsDiv.appendChild(radioLabel);

    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);

    //生成选择宝贝所属店铺类别的选择框***********************************************************************************/
    //***********************************************************************************************************/
    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('上架店铺'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');

    var sel = document.createElement('SELECT');
    sel.setAttribute('name', 'publish_shop');
    sel.setAttribute('id', 'publish_shop');
    sel.setAttribute('class', 'span4');
    sel.setAttribute('disabled', 'disabled');

    var op = document.createElement('OPTION');
    op.setAttribute('value', '');
    op.innerHTML = '--请选择--';
    sel.appendChild(op);
    var op = document.createElement('OPTION');
    op.setAttribute('value', '肥肥9089');
    op.innerHTML = 'CJ测试用，勿选';
    sel.appendChild(op);
    var op = document.createElement('OPTION');
    op.setAttribute('value', 'jadiiar');
    op.innerHTML = 'JADIIAR 总店';
    sel.appendChild(op);
    var op = document.createElement('OPTION');
    op.setAttribute('value', 'siiena');
    op.innerHTML = 'SIIENA 姐妹店';
    sel.appendChild(op);
    //sel.onchange = function(){creatSellerCatsCheckFrom(this)};
    controlsDiv.appendChild(sel);
    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);

    //生成编辑宝贝店铺类别的选择框***********************************************************************************/
    //***********************************************************************************************************/
    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('店铺类别'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls well ');
    controlsDiv.setAttribute('style', 'max-height: 200px;overflow-y: scroll;');
    controlsDiv.id = "seller_cats";

    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);
    
    //生成宝贝描述的textarea框***********************************************************************************/
    //***********************************************************************************************************/
    var controlDiv = document.createElement('div');//生成div
    controlDiv.setAttribute('class', 'control-group');
    var controlLabel = document.createElement('label');//生成label
    controlLabel.setAttribute('class', 'control-label');
    controlLabel.appendChild(document.createTextNode('宝贝描述'));
    var controlsDiv = document.createElement('div');//生成div
    controlsDiv.setAttribute('class', 'controls');
    var textarea = document.createElement('textarea');//生成textarea
    textarea.name = "product_desc";
    textarea.id = "product_desc";
    controlsDiv.appendChild(textarea);
    controlDiv.appendChild(controlLabel);
    controlDiv.appendChild(controlsDiv);
    $("#item_edit_form")[0].appendChild(controlDiv);

   //var ajaxPic = document.createElement('img');
    //ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    //$("#ajaxPic")[0].appendChild(ajaxPic);

    /*/获取商品信息列表
    $.ajax({
        type:"post",
        data: "pId=" + pId,
        url: "<?php echo base_url();?>index.php/jad_goods/get_product_items_by_pId",
        success: function(items_data){
            $("#ajaxPic")[0].innerHTML = '';
        },          
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
});
*/

}

function creatSellerCatsCheckFrom(nickName){
    $("#ajaxPic")[0].innerHTML = '';//防止页面初始化的时候，出现重复的gif图
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);

    //将类目信息放入数组中
    var seller_cats_array = new Array();
    var seller_cats_str = "<?php echo $itemInfo['seller_cids'];?>";
    seller_cats_array = seller_cats_str.split(',');

     //调用api读取相应的数据
     $.ajax({
         type:"post",
         data: "nickName=" + nickName,
         url: "<?php echo base_url();?>index.php/jad_goods/get_seller_cats_by_nickname",
         success: function(data){
             var jsObject = top_error_check(data);
             jsObject = jsObject.seller_cats.seller_cat;
             //alert(sellerCats.length);

             //对于每一个类目要进行判断，如果为叶子类目，则存在checkbox，若为父亲类目，则不存在checkbox
             //从返回的类别数据可以发现，子类目和父类目是紧靠在一起的，且只有二级分类
             for (var i=0; i < jsObject.length - 1; i++) {
                 var checkLabel = document.createElement('label');
                 checkLabel.setAttribute('class', 'checkbox');
                 checkLabel.appendChild(document.createTextNode(jsObject[i].name));
                 if (jsObject[i].parent_cid == 0 && jsObject[i+1].parent_cid !=0 ){//说明该节点为拥有子节点的父节点，没有checkbox
                     //加入未offset的checkbox
                 }else{//说明该节点存在checkbox
                     if (jsObject[i].parent_cid == 0){
                         checkLabel.setAttribute('style', 'padding-left:1cm;');
                     }else{
                         checkLabel.setAttribute('style', 'padding-left:2cm;');
                     }
                     var checkBox = document.createElement('input');
                     checkBox.setAttribute('type', 'checkbox');
                     checkBox.id = jsObject[i].cid;
                     if (seller_cats_array.indexOf(checkBox.id)>=0){
                         checkBox.checked = "checked";
                     }
                     checkBox.value = jsObject[i].cid;
                     checkLabel.appendChild(checkBox);
                 }
                 $("#seller_cats")[0].appendChild(checkLabel);
             }
             //对最末一个节点分类进行处理
             var checkLabel = document.createElement('label');
             checkLabel.setAttribute('class', 'checkbox');
             checkLabel.appendChild(document.createTextNode(jsObject[i].name));
             if (jsObject[jsObject.length-1].parent_cid == 0){
                 checkLabel.setAttribute('style', 'padding-left:1cm;');
             }else{
                 checkLabel.setAttribute('style', 'padding-left:2cm;');
             }
             var checkBox = document.createElement('input');
             checkBox.setAttribute('type', 'checkbox');
             checkBox.id = jsObject[i].cid;
             checkBox.value = jsObject[i].cid;
             if (seller_cats_array.indexOf(checkBox.id)>=0){
                 checkBox.checked = "checked";
             }
             checkLabel.appendChild(checkBox);
             $("#seller_cats")[0].appendChild(checkLabel);
             $("#ajaxPic")[0].innerHTML = '';
        },
        error: function(){
        alert("获取数据失败，请与网站管理员联系！");
        }
     }); 
}
//对ajax访问TOP平台的返回错误代码进行检查，及时反馈错误信息
/*访问成功的返回数据一般为一个长度为1的二维数组,json表示如下：
* 例：{"sku":{"iid":234556,"num_iid":123456}}
*访问错误的返回数据一般为一个长度为3或者4的一维数组，code、msg、sub_code、sub_msg
*有些有sub_msg，有些没有；具体原因目前不详
*/
function top_error_check(msg){
     var jsObject = eval('('+msg+')');
     //存在msg即为错误返回
     if ( jsObject.code != undefined ){
         if ( jsObject.sub_msg != undefined ){
             //若存在子错误码，就显示子错误码，否则显示错误码
             alert(jsObject.sub_msg);
         }else{
             alert(jsObject.msg);
         }
         return false;
     }
     //否则就正常执行程序
     return jsObject;
}

function createGlobalStock(o){
            var o = $("input[type='radio'][name='location_bought']:checked").val();
            if (o == 1) {
                //清除选择框
                $("#global_stock_div")[0].outerHTML = '';
                $("#global_type_div")[0].outerHTML = '';
            }
            else {
                //放置选择购买地的元素
                var subControlDiv = document.createElement('div');//生成div
                subControlDiv.setAttribute('class', 'control-group');
                subControlDiv.id = "global_stock_div";
                var subControlLabel = document.createElement('label');//生成label
                subControlLabel.setAttribute('class', 'control-label');
                var subControlsDiv = document.createElement('div');//生成div
                subControlsDiv.setAttribute('class', 'controls');

                //设置select中的值
                var sel = document.createElement('SELECT');
                sel.setAttribute('name', 'sel_global_stock');
                sel.setAttribute('id', 'sel_global_stock');
                sel.setAttribute('class', 'span4');

                var op = document.createElement('OPTION');
                op.setAttribute('value', '');
                op.innerHTML = '--请选择国家或地区--';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '美国');
                op.innerHTML = '美国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '香港');
                op.innerHTML = '香港';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '日本');
                op.innerHTML = '日本';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '英国');
                op.innerHTML = '英国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '新西兰');
                op.innerHTML = '新西兰';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '德国');
                op.innerHTML = '德国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '韩国');
                op.innerHTML = '韩国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '荷兰');
                op.innerHTML = '荷兰';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '澳洲');
                op.innerHTML = '澳洲';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '法国');
                op.innerHTML = '法国';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '意大利');
                op.innerHTML = '意大利';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '台湾');
                op.innerHTML = '台湾';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '澳门');
                op.innerHTML = '澳门';
                sel.appendChild(op);
                var op = document.createElement('OPTION');
                op.setAttribute('value', '其他');
                op.innerHTML = '其他';
                sel.appendChild(op);

                var subConGroDiv =  document.createElement('div');
                subConGroDiv.setAttribute('class', 'control-group');
                var stateLabel = document.createElement('label');
                stateLabel.setAttribute('class', 'control-label');
                stateLabel.appendChild(document.createTextNode('国家/地区'));
                var subConDiv =  document.createElement('div');
                subConDiv.setAttribute('class', 'controls');
                subConDiv.appendChild(sel);

                subConGroDiv.appendChild(stateLabel);
                subConGroDiv.appendChild(subConDiv);
                subControlsDiv.appendChild(subConGroDiv);

                subControlDiv.appendChild(subControlLabel);
                subControlDiv.appendChild(subControlsDiv);
                insertAfter(subControlDiv,$("#location_bought_div")[0]);

                var subControlDiv = document.createElement('div');//生成div
                subControlDiv.setAttribute('class', 'control-group');
                subControlDiv.id = "global_type_div";
                var subControlLabel = document.createElement('label');//生成label
                subControlLabel.setAttribute('class', 'control-label');
                var subControlsDiv = document.createElement('div');//生成div
                subControlsDiv.setAttribute('class', 'controls');

                var subConGroDiv =  document.createElement('div');
                subConGroDiv.setAttribute('class', 'control-group');
                var stockLabel = document.createElement('label');
                stockLabel.setAttribute('class', 'control-label');
                stockLabel.appendChild(document.createTextNode('库存类型'));
                var subConDiv =  document.createElement('div');
                subConDiv.setAttribute('class', 'controls');

                var radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'global_type';
                radio.id = 'global_type';
                radio.value = '1';

                var typeLabel = document.createElement('label');
                typeLabel.setAttribute('class', 'radio inline');
                typeLabel.setAttribute('style', 'margin-left:1cm');
                typeLabel.appendChild(radio);
                typeLabel.appendChild(document.createTextNode('现货'));

                subConDiv.appendChild(typeLabel);

                var radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'global_type';
                radio.id = 'global_type';
                radio.value = '2';
                radio.checked = 'checked';

                var typeLabel = document.createElement('label');
                typeLabel.setAttribute('class', 'radio inline');
                typeLabel.setAttribute('style', 'margin-left:2cm');
                typeLabel.appendChild(radio);
                typeLabel.appendChild(document.createTextNode('代购'));

                subConDiv.appendChild(typeLabel);

                subConGroDiv.appendChild(stockLabel);
                subConGroDiv.appendChild(subConDiv);
                subControlsDiv.appendChild(subConGroDiv);

                subControlDiv.appendChild(subControlLabel);
                subControlDiv.appendChild(subControlsDiv);
                insertAfter(subControlDiv,$("#global_stock_div")[0]);
               
            }
}

$(document).ready(function(){
    /*
    $('#update_item_info_href').confirm({
		'title' : '新增产品信息',
		'message' : '您确定要新增该产品信息吗？',        
        'action' : function() {
        update_item_info();
         $("#update_item_info_href").fadeOut(500);
		}
    });
    */

    $("#publish_shop")[0].value = "<?php echo $productInfo['shop_published_on'];?>"; 
    creatSellerCatsCheckFrom("<?php echo $productInfo['shop_published_on'];?>");
    $("#product_desc").cleditor(); 
    //设置购买地点
    <?php if (count($itemInfo) == 8 ){ ?>
        setCheckedValue("location_bought","2");
        setCheckedValue("global_type","<?php echo $itemInfo['global_stock_type'];?>");
        createGlobalStock("<?php echo $itemInfo['global_stock_type'];?>");
        $("#sel_global_stock")[0].value = "<?php echo $itemInfo['global_stock_country'];?>"; 
    <?php } ?>

    //$("input[type='radio'][name='location_bought']").change();
    //createPropsForm(<?php echo $productInfo['cid']; ?>);
});
</script>  
</body>

