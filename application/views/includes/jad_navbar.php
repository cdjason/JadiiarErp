<div class="navbar">
   <div class="navbar-inner">
       <ul class="nav pull-right">
          <li><a href="#" class="hidden-phone visible-tablet visible-desktop" role="button">设置</a></li>
          <li id="fat-menu" class="dropdown">
          <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-user"></i>
            <?php $user_array = $this->flexi_auth->get_user_by_id($this->flexi_auth->get_user_id())->row_array();echo $user_array['upro_first_name'].' '.$user_array['upro_last_name']; ?> 
            <i class="icon-caret-down"></i>
          </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="<?php echo $base_url;?>index.php/jad_auth_public/update_account">我的账户</a></li>
                            <li class="divider"></li>
                            <!--<li><a tabindex="-1"  href="#">Settings</a></li>
                            <li class="divider visible-phone"></li>-->
                            <li><a tabindex="-1" href="<?php echo $base_url;?>index.php/jad_auth/logout">退出</a></li>
                        </ul>
                    </li>
                </ul>
                <a class="brand" href="index.html"><span class="first">Your</span> <span class="second">Company</span></a>
        </div>
    </div>
