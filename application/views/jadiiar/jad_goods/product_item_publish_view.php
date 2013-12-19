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
                <p class="stat" id="p_title"><span class="number">商家编码:</span><?php echo $productItem['item_id']; ?></p>
                <p class="stat" id="p_cid"><span class="number">CID:</span><?php echo $productItem['cid']; ?></p>
                <p class="stat" id="product_id"><span class="number">Product_ID:</span><?php echo $productItem['product_id']; ?></p>
                <p class="stat" id="product_title"><span class="number">产品标题:</span><?php echo $productItem['product_title']; ?></p>
</div>

            <h1 class="page-title">发布商品信息</h1>
        </div>
        
                <ul class="breadcrumb">
            <li><a href="index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">商品列表</a> <span class="divider">/</span></li>
            <li class="active">发布商品信息</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
<?php $this->load->view('includes/jad_message'); ?>  
    <?php $attributes = array('id' => 'test_form','onSubmit' => 'return checkAll(this)');echo form_open(current_url(), $attributes);?>  	
<div class="btn-toolbar">
  <button type="submit" class="btn btn-primary" id="form_btn" /><i class="icon-plus"></i> 发布 
  </button>
  <input type="hidden" name="publish_product_item" value="1" />
  <div class="btn-group">
  </div>
</div>

<div class="well">
    <div class="row-fluid">
        <div class="span2" ><b>宝贝类型</b></div>
        <div class="span10" >
             <input type="radio" name="stuffStatus" id="optionsRadios1" value="new" checked>全新&nbsp;&nbsp;&nbsp;&nbsp;
             <input type="radio" name="stuffStatus" id="optionsRadios2" value="second">二手
        </div>
    </div>              
    <div class="row-fluid">
        <div class="span2" ><b>价格</b></div>
        <div class="span10" ><input type="text" name="price" id="price" /></div>
    </div>              
    <div class="row-fluid">
        <div class="span2" ><b>数量</b></div>
        <div class="span10" ><input type="text" name="num" id="num" /></div>
    </div>              
    <div class="row-fluid">
        <div class="span2" ><b>采购地</b></div>
        <div class="span10" >
             <input type="radio" name="item_from" id="item_from1" value="option1" checked>国内&nbsp;&nbsp;&nbsp;&nbsp;
             <input type="radio" name="item_from" id="item_from2" value="option2">海外及港澳台
        </div>
    </div>              
    <div class="row-fluid">
        <div class="span2"><b>必选属性</b></div>
        <div class="span10" id="enum-must-props"></div>
    </div>
    <div class="row-fluid">
        <div class="span2" ><b>商品描述</b></div>
        <div class="span10" id="item_desc"><textarea id="input11" name="input11"><?php echo $productItem['product_desc'].$productItem['item_desc']; ?></textarea></div>
    </div>              
</div>

<input type="hidden" name = "cid" value = '<?php echo $productItem['cid']; ?>' />
<input type="hidden" name = "must_props" id = "must_props" value = '' />
<input type="hidden" name = "product_item_props" id = "product_item_props" value = '<?php echo $productItem['iprops']; ?>' />
<input type="hidden" name = "product_props" id = "product_props" value = '<?php echo $productItem['props']; ?>' />
<input type="hidden" name = "product_item_property_alias" id = "product_item_property_alias" value = '<?php echo $productItem['property_alias']; ?>' />
<input type="hidden" name = "product_inputs_str" id = "product_inputs_str" value = '<?php echo $productItem['inputs_str']; ?>' />
<input type="hidden" name = "product_inputs_pids" id = "product_inputs_pids" value = '<?php echo $productItem['inputs_pids']; ?>' />
<input type="hidden" name = "productTitle" id = "productTitle" value = '<?php echo $productItem['product_title']; ?>' />

	  <?php echo form_close();?>
<?php $this->load->view('includes/jad_footer'); ?>  
            </div>
        </div>
    </div>
<?php $this->load->view('includes/jad_scripts'); ?>
<script>
//获取非关键属性中必选属性链pid:vid
function checkAll(form){
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
    //获取该cid下的销售属性
    $.ajax({
        type:"post",
        data: "parentId=" + cid,
        url: "<?php echo base_url();?>index.php/jad_goods/get_itemprops_by_cid",
        success: function(data){
            var props = eval('('+data+')');
            if(props=='') return false;
            props = props.item_props.item_prop;
            var i;
            for (i in props) {
                //对数据进行处理,获取每一项销售属性
                if (props[i].is_sale_prop == false && props[i].is_key_prop == false && props[i].must == true) {
                    if (props[i].is_enum_prop) {
                        var sel = document.createElement('SELECT');
                        sel.setAttribute('name', 'pid_' + props[i].pid);
                        sel.setAttribute('id', 'pid_' + props[i].pid);
                        sel.setAttribute('class', 'input-xlarge');

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

                        var propNameDiv = document.createElement('div');
                        propNameDiv.setAttribute("class", "span3"); 
                        propNameDiv.appendChild(document.createTextNode(props[i].name+"："));

                        var propValueDiv = document.createElement('div');
                        propValueDiv.setAttribute("class", "span9"); 
                        propValueDiv.appendChild(sel);

                        propDiv.appendChild(propNameDiv);
                        propDiv.appendChild(propValueDiv);
                        
                        $("#enum-must-props")[0].appendChild(propDiv);
 
                    }
                }
            }
            
        },
        error: function(){
            alert("获取数据失败，请与网站管理员联系！");
        }
    }); 
}
$(document).ready(function(){
    $("#input11").cleditor(); 
    //页面启动的时候，就去调用增加类目选择下拉菜单的方法，只不过传入参数为0，即读取无父子节点的类节点
    createPropsForm(<?php echo $productItem['cid']; ?>);
});
</script>  
  </body>
</html>
