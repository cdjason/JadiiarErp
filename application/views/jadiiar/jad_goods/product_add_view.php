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
            <p class="stat" id="p_cid"><span class="number">CID:</span></p>
            <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $productId; ?></p>
        </div>
        <h1 class="page-title">新增产品信息</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.html">Home</a> <span class="divider">/</span></li>
        <li><a href="<?php echo $base_url;?>index.php/jad_goods/manage_products/order_by/num_iid/order_parameter/desc">产品列表</a> <span class="divider">/</span></li>
        <li class="active">新增产品信息</li>
    </ul>

    <div class="container-fluid">
        <div class="row-fluid">
            <?php $this->load->view('includes/jad_message'); ?>  
            <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
            <div class="row-fluid">
                <div class="span2">
                    <button type="submit" class="btn btn-primary span12" id="form_btn" /><i class="icon-plus"></i>  增加</button>
                    <input type="hidden" name="add_goods_first" value="1" />
                </div>
                <div class="span1" id = "ajaxPic">
                </div>
            </div></br>

            <div class="well">
                <div class="row-fluid">
                    <div class="span2" id="parentDiv"><b>产品类目</b></div>
                    <div class="span10" id="parentCidDiv"></div>
                </div><br><br><br>
                <div class="row-fluid">
                    <div class="span2"><b>关键属性</b></div>
                    <div class="span10" id="enum-one-props"></div>
                </div>
            </div>

            <div class="well">
                <div class="row-fluid">
                    <div class="span2" ><b>产品名称</b></div>
                    <div class="span10" ><input = "text" id = "product_title" name = "product_title" class="span6" placeholder="不要超过30个字符" /></div>
                </div><br>              
                <div class="row-fluid">
                    <div class="span2" ><b>品牌</b></div>
                    <div class="span10" id = 'sub_branch_brand' >
                    <select name = "product_brand" id = "product_brand">
                    <option value = "">--请选择--</option>
                    <?php for ( $i =0;$i<count($brandList);$i++ ){ 
                    ?>
                    <option value = "<?php echo $brandList[$i]; ?>"><?php echo $brandList[$i]; ?></option>
                    <?php } ?> 

                    </select>
                    </div>
                </div>  <br>            
                <div class="row-fluid">
                    <div class="span2" ><b>产品图片URL</b></div>
                    <div class="span10" ><input = "text" id = "product_img_url" name = "product_img_url" class="span12" /></div>
                </div>     <br>         
                <div class="row-fluid">
                    <div class="span2" ><b>产品描述</b></div>
                    <div class="span10" ><input = "text" id = "product_desc" name = "product_desc" class="span12" placeholder="不要出现标点符号" /></div>
                </div>              
            </div>
            <input type="hidden" name="cid" id="cid"/>
            <input type="hidden" name="cid_name_chain" id="cid_name_chain"/>
            <input type="hidden" name="props" id="props" />
            <input type="hidden" name="input_str" id="input_str"/>
            <input type="hidden" name="input_pids" id="input_pids"/>
            <input type="hidden" name="product_hidd_id" id="product_hidd_id" value="<?php echo $productId; ?>"/>
            <?php echo form_close();?>
            <?php $this->load->view('includes/jad_footer'); ?>  
        </div>
    </div>
</div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//提交获取属性链pid:vid
function checkAll(form){

    if( $("#cid")[0].value == ''){
        alert('尚未选择商品叶子类目，请选择!');
        return false;
    }
    if( $("#product_brand")[0].value == ''){
        alert('尚未选择产品品牌，请选择!');
        return false;
    }
    var formElements = form.elements;
    var propStr = '';
    var inputStr = ''; 
    var inputPidsStr = ''; 

    //获取所有的类目信息名称
    var oJu = $("#parentCidDiv")[0].childNodes[0];
    oJu = $(oJu);
    var cidName = '';
    while(oJu[0]!=undefined){
        var temp = oJu[0];
        cidName = cidName + ',' + oJu[0].options[oJu[0].selectedIndex].text; 
        oJu = oJu.next();
    }
    $("#cid_name_chain")[0].value = cidName.substr(1); 

    //遍历关键属性DIV下的各个属性div，除了取值以外，还要判断是否存在那种未选择、未填写的情况
    var prop_divs = $("#enum-one-props")[0];
	for (var j=0;j<prop_divs.childNodes.length;j++) {
        //若存在input,那么就把pid与vid放入input pid与str中
        var prop_div = prop_divs.childNodes[j];
        if(prop_div.childNodes[1].childNodes[0].nodeName == 'INPUT'){
            //当关键属性为输入框时
            //若该关键属性为品牌时，直接去品牌的选项处获取值；否则，则为普通情况处理
            if ( prop_div.childNodes[1].childNodes[0].id.substr(6) == '20000' ){
                prop_div.childNodes[1].childNodes[0].value = $("#product_brand")[0].value;
            }else{
                if(prop_div.childNodes[1].childNodes[0].value == ''){
                    alert('关键属性不能有未输入的项');
                    return false;
                }
            }
            inputStr += ',' + prop_div.childNodes[1].childNodes[0].value;
            inputPidsStr += ',' + prop_div.childNodes[1].childNodes[0].id.substr(6);
        }
        if(prop_div.childNodes[1].childNodes[0].nodeName == 'SELECT'){
            //当关键属性为下拉列表时
            if(prop_div.childNodes[1].childNodes[0].value == ''){
                alert('关键属性不能有未选择的项');
                return false;
            }
            if(prop_div.childNodes[1].childNodes[0].value.split(':').length === 1){
            }else{
                propStr +=  prop_div.childNodes[1].childNodes[0].value + ';';
            }
        }
	}

    inputStr = inputStr.substr(1);
    inputPidsStr = inputPidsStr.substr(1);
    $("#props")[0].value = propStr; 
    $("#input_str")[0].value = inputStr; 
    $("#input_pids")[0].value = inputPidsStr; 
}
//对于关键属性中的子属性进行展示
function parentPropList(o){
    //一部分属性存在子属性，而另一部分属性不存在子属性
	if ('' == o.value) {
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

            //当为品牌属性的时候，自定义直接readonly
            //苹果手机壳品牌属性中的自定义的value比较特殊，为20000_型号
            if ( o.value == '20000' || o.value == '20000_型号' ){
                $(txt).attr("readonly","readonly")
            }

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
            
            //对提交按钮做出处理
            $("#form_btn")[0].disabled = 'disabled';
            var ajaxPic = document.createElement('img');
            ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
            $("#ajaxPic")[0].appendChild(ajaxPic);
            
            //获取在当前叶子cid和pid的下面，以pid为父亲属性的子属性数据
            $.ajax({
                type:"post",
                data: "cId=" + $("#cid").val() + "&parentPId=" + pid,
                url: "<?php echo base_url();?>index.php/jad_goods/get_child_itemprops",
                success: function(prop_data){
                    var prop_data = top_error_check(prop_data);
                    if(prop_data==''){ 
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
        parent.insertBefore(newElement,targetElement.nextSibling);
        //如果不是，则插入在目标元素的下一个兄弟节点 的前面。也就是目标元素的后面。
    }
}

//创建关键属性的选择表单
function createPropsForm(cid) {
    //对提交按钮做出处理
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);

    //获取该cid下的关键属性
    $.ajax({
        type:"post",
        data: "parentId=" + cid,
        url: "<?php echo base_url();?>index.php/jad_goods/get_itemprops_by_cid",
        success: function(data){
            var props = top_error_check(data);
            if(props==''){ 
                $("#form_btn")[0].disabled = '';
                $("#ajaxPic")[0].innerHTML = '';
                return false;
            }
            props = props.item_props.item_prop;
            var i;
            for (i in props) {
                //对数据进行处理,获取关键属性
                if (props[i].is_key_prop == 'true' || props[i].is_key_prop === true) {
                    //获取枚举型属性或者父亲属性id为0的属性
                    if (props[i].is_enum_prop && '0' == props[i].parent_pid) {
                        //构建关键属性的HTML元素
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
                               var propvalues = top_error_check(prop_data);
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

                        if (props[i].is_input_prop) {
                            op = document.createElement('OPTION');
                            if (props[i].child_template) {
                                op.setAttribute('value', props[i].pid + '_' + props[i].child_template);
                            } else {
                                op.setAttribute('value', props[i].pid);
                            }
                            op.innerHTML = '自定义';
                            sel.appendChild(op);
                        }
                        //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                        var propDiv = document.createElement('div');
                        propDiv.id = 'pid_' + props[i]['pid'] + '_div';
                        propDiv.setAttribute('class', 'row-fluid');

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
                        $("#enum-one-props")[0].appendChild(propDiv);
                        document.getElementById('pid_' + props[i].pid).onchange = function(){parentPropList(this)};

                        //当为品牌属性的时候，默认为自定义
                        if ( props[i].pid == '20000' ){
                            //苹果手机壳品牌属性中的自定义的value比较特殊，为20000_型号
                            if ( props[i].name == '保护壳/套品牌'){
                                sel.value = '20000_型号';
                            }else{
                                sel.value = props[i].pid;
                            }
                            $(sel).attr("disabled","disabled")
                            parentPropList(sel);
                        }

                    }else if ('0' == props[i].parent_pid){
                        //获取非枚举型(即input=text)的关键属性
                        var txt = document.createElement('input');
                        txt.type = 'text';
                        txt.name = 'input_' + props[i].pid;
                        txt.id = 'input_' + props[i].pid;
                        txt.setAttribute('class', 'span6');
                        txt.value = '';
                        //当为货号或者款式input的时候，需要复制页面上的product_hidd_id,且不能编辑
                        if ( props[i].pid == '1632501' ){
                            txt.value = $("#product_hidd_id")[0].value;
                            $(txt).attr("readonly","readonly")
                        }
                        //针对每一项关键属性，都用了一个div来便于多重属性的选择或者设置
                        var propDiv = document.createElement('div');
                        propDiv.id = 'pid_' + props[i].pid + '_div';
                        propDiv.setAttribute('class', 'row-fluid');

                        var propDivName = document.createElement('div');
                        propDivName.id = 'pid_' + props[i]['pid'] + '_divName';
                        propDivName.setAttribute('class', 'span2');

                        var propDivValue = document.createElement('div');
                        propDivValue.id = 'pid_' + props[i]['pid'] + '_divValue';
                        propDivValue.setAttribute('class', 'span10');

                        propDivName.appendChild(document.createTextNode(props[i].name+"："));
                        propDivValue.appendChild(txt);

                        propDiv.appendChild(propDivName);
                        propDiv.appendChild(propDivValue);
                        $("#enum-one-props")[0].appendChild(propDiv);
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

function childSellerCidList(o, c_id){
    //当选择之后，当前元素节点之后的所有兄弟节点需要删除
    var oJu = $(o).next();
    while(oJu[0]!=undefined){
        var temp = oJu[0];
        oJu = oJu.next();
        temp.outerHTML='';
    }
    var cid = o.value;

    //当选择"请选择"的时候，考虑一下怎么进行处理一下；
    if (cid==''){
        return false;
    }

    createSellerCidSelect(cid)
    //var is_parent=o.options[o.selectedIndex].is_parent;
    //if(is_parent === true || is_parent == 'true')
    //{
        //非叶子类目，则继续以该cid为父亲id添加子类目下拉菜单
        //createSellerCidSelect(c_id)
    //}
    //else
    //{
        //叶子类目，则显示该cid
        //createPropsForm(cid);
    //}
}

//响应类目选择的动作
function childCidList(o, p_id)
{
    //当选择非叶子类目或是叶子类目之后，关键属性中的内容都需要消除掉;当前元素节点之后的兄弟节点也需要删除
    $("#enum-one-props")[0].innerHTML='';
    var oJu = $(o).next();
    while(oJu[0]!=undefined){
        var temp = oJu[0];
        oJu = oJu.next();
        temp.outerHTML='';
    }
    var cid = o.value;
    //当选择"请选择"的时候，考虑一下怎么进行处理一下；
    if (cid==''){
        return false;
    }
    var is_parent=o.options[o.selectedIndex ].is_parent;
    if(is_parent === true || is_parent == 'true')
    {
        //非叶子类目，则继续以该cid为父亲id添加子类目下拉菜单
        createCidSelect(cid)
    }
    else
    {
        //叶子类目，则显示该cid
        $("#cid")[0].value=cid;
        var i=1;
        while($("#p_cid")[0].childNodes[i]!=undefined){
            $("#p_cid")[0].childNodes[i].nodeValue='';
            i++;
        }
        $("#p_cid")[0].appendChild(document.createTextNode(cid));
        createPropsForm(cid);
    }
}

//创建店铺类目选择的下拉菜单，参数为parent_id
function createSellerCidSelect(c_id) 
{
    //对提交按钮做出处理
    $("#ajaxPic")[0].innerHTML = '';//防止页面初始化的时候，出现重复的gif图
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);

     //调用api读取相应的数据
     $.ajax({
         type:"post",
         url: "<?php echo base_url();?>index.php/jad_goods/get_seller_cats_by_nickname",
         success: function(data){
             var jsObject = top_error_check(data);
             jsObject = jsObject.seller_cats.seller_cat;
             //alert(sellerCats.length);

             var sel = document.createElement('SELECT');
             sel.setAttribute('name', 'cat_' + c_id);
             sel.setAttribute('id', 'cat_' + c_id);
             sel.setAttribute('class', 'input-xlarge');
             var op = document.createElement('OPTION');
             op.setAttribute('value', '');
             op.innerHTML = '--请选择--';
             sel.appendChild(op);

             var i;
             var j=0;
             for (i in jsObject) {
                if (jsObject[i].parent_cid == c_id ) {
                    j++;
                    var option = document.createElement('OPTION');
                    option.innerHTML = jsObject[i].name;
                    option.setAttribute('value', jsObject[i].cid);
                    option.parent_id=jsObject[i].parent_cid;//判断是否是子类目
                    sel.appendChild(option);
                }
             }
             sel.onchange = function(){childSellerCidList(this, c_id);};
             if (j != 0){
                 $("#sub_branch_brand")[0].appendChild(sel);
             }
            $("#form_btn")[0].disabled = '';
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

//创建类目选择的下拉菜单，参数为parent_id
//其实只需要品牌类目就可以了
function createCidSelect(parent_id) {

    //对提交按钮做出处理
    $("#form_btn")[0].disabled = 'disabled';
    var ajaxPic = document.createElement('img');
    ajaxPic.setAttribute('src', '<?php echo $includes_dir;?>/images/67.gif');
    $("#ajaxPic")[0].appendChild(ajaxPic);
    
  //调用api读取相应的数据
  $.ajax({
     type:"post",
     data: "parentId=" + parent_id,
     url: "<?php echo base_url();?>index.php/jad_goods/get_itemcats_by_parent_id",
     success: function(data){
         var jsObject = top_error_check(data);
         jsObject = jsObject.item_cats.item_cat;

	     var sel = document.createElement('SELECT');
	     sel.setAttribute('name', 'pid_' + parent_id);
         sel.setAttribute('id', 'pid_' + parent_id);
         sel.setAttribute('class', 'input-xlarge');
	     var op = document.createElement('OPTION');
	     op.setAttribute('value', '');
	     op.innerHTML = '--请选择--';
	     sel.appendChild(op);
         var i;
		 for (i in jsObject) {
			if (jsObject[i].status == 'normal') {
				var option = document.createElement('OPTION');
				option.innerHTML = jsObject[i].name;
				option.setAttribute('value', jsObject[i].cid);
				option.is_parent=jsObject[i].is_parent;//判断是否是子类目
				sel.appendChild(option);
			}
		 }
         //在第二个label之前创建下拉菜单，即"关键属性"的label之前
         sel.onchange = function(){childCidList(this, parent_id);};
         $("#parentCidDiv")[0].appendChild(sel);

         $("#form_btn")[0].disabled = '';
         $("#ajaxPic")[0].innerHTML = '';
     },
     error: function(){
         alert("获取数据失败，请与网站管理员联系！");
         //setTimeout($.unblockUI, 20);
     }
  }); 
}
$(document).ready(function(){
    //页面启动的时候，就去调用增加类目选择下拉菜单的方法，只不过传入参数为0，即读取无父子节点的类节点
    createCidSelect(0);
    //页面启动的时候，就去调用增加店铺类目选择下拉菜单的方法，只不过传入参数为0，即读取一级店铺分类,该方法暂时不用，结合多店铺时再启用
    //createSellerCidSelect(0);
    //创建各分店名称下拉
    //createSubBranchSelect();

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

});
</script>  
</body>
</html>
