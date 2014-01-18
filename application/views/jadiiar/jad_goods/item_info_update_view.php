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
            <p class="stat" id="p_cid"><span class="number">CID:</span><?php  ?></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php  ?></p>
            <p class="stat" ><span class="number">产品名称:</span><?php  ?></p>
        </div>

        <h1 class="page-title">宝贝信息编辑</h1>
    </div>
        
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_product_items/<?php echo $productId;  ?> ">商品列表</a> <span class="divider">/</span></li>
        <li class="active">宝贝信息编辑</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
        <?php $this->load->view('includes/jad_message'); ?>  
        <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
        <div class="row-fluid">
            <div class="span2">
                <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  确定</button>
                <input type="hidden" name="update_item_info" value="1" />
            </div>
            <div class="span1" id = "ajaxPic">
            </div>
        </div></br>

        <div class="well">
            <div class="row-fluid">
                <div class="span2" ><b>宝贝图片</b></div>
                <div class="span10" ><img class="img-rounded" src="<?php echo $itemInfo['pic_url'];?>"  ></div>
            </div>
            <div class="row-fluid">
                <div class="span2" ><b>宝贝标题</b></div>
                <div class="span10" >
                     <input type="text" name="product_title" id = "product_title" value = "<?php echo $itemInfo['title'];?>"/>
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>一口价</b></div>
                <div class="span10" >
                     <input type="text" name="price" id = "product_price" placeholder="注意价格区间位于sku属性之间" value = "<?php echo $itemInfo['price'];?>"/>
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>宝贝数量</b></div>
                <div class="span10" >
                     <input type="text" name="product_num" id = "product_num" placeholder="若销售属性有值，则为sku之和" value = "<?php echo $itemInfo['price'];?>"/>
                </div>
            </div>              
            <div class="row-fluid">
                <div class="span2" ><b>商品描述</b></div>
                <div class="span10" ><textarea id="item_desc" name="item_desc"><?php echo $itemInfo['desc'];?></textarea></div>
            </div>              
        </div>

	    <?php echo form_close();?>
        <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>

//对宝贝的描述内容进行HTML编码，以防止CI的XSS跨站攻击策略对代码进行修改;后台利用html_entity_decode进行解码
function HTMLEncode( input ){
    var converter = document.createElement("DIV");
    converter.innerText = input;
    var output = converter.innerHTML;
    converter = null;
    return output;
}

function checkNum(obj)
{
     var re = /^[1-9]\d*$/;
     //alert(re.test(obj));
     return re.test(obj);
} 

Array.prototype.max = function(){ 
    return Math.max.apply({},this) 
} 
Array.prototype.min = function(){ 
    return Math.min.apply({},this) 
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

//获取非关键属性中必选属性链pid:vid
function checkAll(form){

    //宝贝标题不能为空
    if( $("#product_title")[0].value == '' || $("#product_title")[0].value.length >30 ){
        alert("宝贝标题必须填写且小于30个字符!");
        return false;
    }
    //发布的店铺不能不选
    if( $("#publish_shop")[0].value == '' ){
        alert("必须选择需要发布的店铺!");
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

    //对表单输入框进行验证

    //遍历sku_item_tabli，获取sku相关参数
    var sku_properties = "";
    var sku_property_alias = "";
    var item_props = "";
    var sku_quantities = "";   
    var sku_prices = "";
    var sku_outer_ids = "";
    var product_items_num = 0;
    var skuPropValues = $("#sku_items_table")[0];
    var propsArray = new Array()
    var pricesArray = new Array();

    //防止对没有销售属性的产品进行sku表的遍历 
    if( skuPropValues.tBodies.length > 0 ){
        for(var i = 0 ; i < skuPropValues.tBodies[0].rows.length ; i++ ){
            //必须要checked以后的才可以发布
            if (skuPropValues.tBodies[0].rows[i].cells[skuPropValues.tBodies[0].rows[i].cells.length - 1].childNodes[0].checked) {
                //对checked的销售属性的输入信息进行验证
                if( (!isfloat( skuPropValues.tBodies[0].rows[i].cells[3].childNodes[0].value ))  || (!checkNum( skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value ))){
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
                product_items_num += parseInt(skuPropValues.tBodies[0].rows[i].cells[4].childNodes[0].value);
                //props属性值需要重新提取，不能用单条item中的iprop
                //sku_properties与sku_property_alias的值也需要提取
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
            }
        }
        item_props = item_props.substr(1); 
        sku_property_alias = sku_property_alias.substr(1); 
        sku_outer_ids = sku_outer_ids.substr(1);
        sku_properties = sku_properties.substr(1);
        sku_prices = sku_prices.substr(1);
        sku_quantities = sku_quantities.substr(1);
    }
    //alert(product_items_num );
    //alert(sku_properties);

    //说明不存在商品信息(sku串)，或者没有选择存在的任何一个sku串，这种情况下，宝贝的数量就需要从输入框获取了
    //若存在销售属性，则必须选择商品发布；因为有些类目的销售属性是必须属性
    if( skuPropValues.tBodies.length == 0 ){
        product_items_num = $("#product_num")[0].value;
        //不存在，则没有props_property_alias
    }
    if( sku_outer_ids == '' ){
        alert("请选择要发布的宝贝SKU，至少选择一项!");
        return false;
        //不存在，则没有props_property_alias
    }
    //1、宝贝描述信息不能为空
    if( $("#item_desc")[0].value == '' ){
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


    $("#seller_cats_str")[0].value = seller_cats_str;
    $("#product_item_props")[0].value = item_props;
    $("#sku_outer_ids")[0].value = sku_outer_ids;
    $("#sku_properties")[0].value = sku_properties;
    $("#sku_prices")[0].value = sku_prices;
    $("#sku_quantities")[0].value = sku_quantities;
    $("#product_items_num")[0].value = product_items_num;
    $("#props_property_alias")[0].value = sku_property_alias;
    $("#format_item_desc")[0].value = HTMLEncode($("#item_desc")[0].value);

    //alert($("#format_item_desc")[0].value);
    //return false;
    //遍历非关键属性中必选属性，必须对每一个进行选择
    var mustProps = $("#enum-must-props")[0];
    var mustPropsStr = '';
    for ( var i=0;i<mustProps.childNodes.length;i++){
        if ( mustProps.childNodes[i].childNodes[1].childNodes[0].value == ''){
            alert('必须选择每一个必选属性!');
            return false;
        }
        mustPropsStr = mustPropsStr + mustProps.childNodes[i].childNodes[1].childNodes[0].value + ';';
    }
    mustPropsStr = mustPropsStr.substr(0,mustPropsStr.length-1);
    $("#must_props")[0].value = mustPropsStr;
}

String.prototype.Trim = function() { 
   return this.replace(/(^\s*)|(\s*$)/g, ""); 
}
//去除字符串左侧空格
String.prototype.LTrim = function() { 
   return this.replace(/(^\s*)/g, ""); 
}
//去除字符串右侧空格
String.prototype.RTrim = function() { 
   return this.replace(/(\s*$)/g, ""); 
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

function creatSellerCatsCheckFrom(o){
    //清除已经选择的数据
    $("#seller_cats")[0].innerHTML = '';
    //如果选择的未选择
    if (o.value == ''){
        return false;
    }
    //根据不同的店铺名称，获取不同的店铺分类
    //alert(o.value);

    //对提交按钮做出处理
    $("#ajaxPic")[0].innerHTML = '';//防止页面初始化的时候，出现重复的gif图
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);

     //调用api读取相应的数据
     $.ajax({
         type:"post",
         data: "nickName=" + o.value,
         url: "<?php echo base_url();?>index.php/jad_goods/get_seller_cats_by_nickname",
         success: function(data){
             var jsObject = top_error_check(data);
             jsObject = jsObject.seller_cats.seller_cat;
             //alert(sellerCats.length);

/*
             var sel = document.createElement('SELECT');
             sel.setAttribute('name', 'cat_' + c_id);
             sel.setAttribute('id', 'cat_' + c_id);
             sel.setAttribute('class', 'input-xlarge');
             var op = document.createElement('OPTION');
             op.setAttribute('value', '');
             op.innerHTML = '--请选择--';
             sel.appendChild(op);
 
 */
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
                         //checkNameDiv.setAttribute('class', 'span1');
                         //checkElementDiv.setAttribute('class', 'span11');
                     }else{
                         checkLabel.setAttribute('style', 'padding-left:2cm;');
                         //checkNameDiv.setAttribute('class', 'span3 offset1');
                         //checkElementDiv.setAttribute('class', 'span8');
                     }
                     var checkBox = document.createElement('input');
                     checkBox.setAttribute('type', 'checkbox');
                     checkBox.id = jsObject[i].cid;
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
             checkLabel.appendChild(checkBox);

             $("#seller_cats")[0].appendChild(checkLabel);

             $("#form_btn")[0].disabled = '';
             $("#ajaxPic")[0].innerHTML = '';
         
        },
        error: function(){
        alert("获取数据失败，请与网站管理员联系！");
        }
     }); 
}

$(document).ready(function(){
    //为输入框绑定插件
    $("#item_desc").cleditor(); 
});
</script>  
</body>

