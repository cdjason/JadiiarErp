<div class="sidebar-nav">
    <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-dashboard"></i>商品信息维护</a>
    <ul id="dashboard-menu" class="nav nav-list collapse">
        <li><a href="index.html">商品信息维护</a></li>
        <li class="active"><a href="users.html">Sample List</a></li>
        <li ><a href="user.html">Sample Item</a></li>
        <li ><a href="media.html">Media</a></li>
        <li ><a href="calendar.html">Calendar</a></li>
    </ul>

    <a href="#accounts-menu" class="nav-header" data-toggle="collapse"><i class="icon-briefcase"></i>Account<span class="label label-info">+3</span></a>
    <ul id="accounts-menu" class="nav nav-list collapse">
        <li ><a href="sign-in.html">Sign In</a></li>
        <li ><a href="sign-up.html">Sign Up</a></li>
        <li ><a href="reset-password.html">Reset Password</a></li>
    </ul>

    <a href="#error-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-exclamation-sign"></i>Error Pages <i class="icon-chevron-up"></i></a>
    <ul id="error-menu" class="nav nav-list collapse">
        <li ><a href="403.html">403 page</a></li>
        <li ><a href="404.html">404 page</a></li>
        <li ><a href="500.html">500 page</a></li>
        <li ><a href="503.html">503 page</a></li>
    </ul>
<a href="#item-data-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-globe"></i>商品信息维护<i class="icon-chevron-up"></i></a>
    <ul id="item-data-menu" class="nav nav-list collapse <?php if($this->uri->segment(2)=='manage_suppliers' || $this->uri->segment(2)=='manage_branches') echo 'in';else echo ''; ?>">
        <li <?php echo ($this->uri->segment(2)=='manage_suppliers')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_goods/manage_products/order_by/num_iid/order_parameter/desc">产品信息维护</a></li>
        <li <?php echo ($this->uri->segment(2)=='manage_suppliers')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_goods/items_catalogue/order_by/num_iid/order_parameter/desc">商品目录</a></li>
    </ul>

<a href="#order-data-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-globe"></i>订单信息维护 <i class="icon-chevron-up"></i></a>
    <ul id="order-data-menu" class="nav nav-list collapse <?php if($this->uri->segment(2)=='manage_suppliers' || $this->uri->segment(2)=='manage_branches') echo 'in';else echo ''; ?>">
        <li <?php echo ($this->uri->segment(2)=='manage_suppliers')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_orders/manage_orders">订单查询</a></li>
    </ul>

<a href="#other-data-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-globe"></i>其他信息维护 <i class="icon-chevron-up"></i></a>
    <ul id="other-data-menu" class="nav nav-list collapse <?php if($this->uri->segment(2)=='manage_suppliers' || $this->uri->segment(2)=='manage_branches') echo 'in';else echo ''; ?>">
        <li <?php echo ($this->uri->segment(2)=='manage_suppliers')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_dictionary/manage_suppliers">供应商信息维护</a></li>
    </ul>

    <a href="#users-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-user"></i>用户信息维护<i class="icon-chevron-up"></i></a>
    <ul id="users-menu" class="nav nav-list collapse <?php if($this->uri->segment(2)=='manage_user_accounts' || $this->uri->segment(2)=='manage_user_groups' || $this->uri->segment(2)=='manage_privileges') echo 'in';else echo ''; ?>">
        <li <?php echo ($this->uri->segment(2)=='manage_user_accounts')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_accounts">用户一览</a></li>
        <li <?php echo ($this->uri->segment(2)=='manage_user_groups')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_user_groups">角色维护</a></li>
        <li <?php echo ($this->uri->segment(2)=='manage_privileges')?"class = active":""; ?>><a href="<?php echo $base_url;?>index.php/jad_auth_admin/manage_privileges">权限维护</a></li>
    </ul>


    <a href="#legal-menu" class="nav-header" data-toggle="collapse"><i class="icon-legal"></i>Legal</a>
    <ul id="legal-menu" class="nav nav-list collapse">
        <li ><a href="privacy-policy.html">Privacy Policy</a></li>
        <li ><a href="terms-and-conditions.html">Terms and Conditions</a></li>
    </ul>
    <a href="help.html" class="nav-header" ><i class="icon-question-sign"></i>Help</a>
    <a href="faq.html" class="nav-header" ><i class="icon-comment"></i>Faq</a>
</div>
