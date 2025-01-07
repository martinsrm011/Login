<?php

include('logout.php');
$pid = $_REQUEST['pid'];
$ismobile = 0;
if($user == 'admin') {
    $ismobile = 0;
    $container = $_SERVER['HTTP_USER_AGENT'];
    $useragents = array('Blazer' ,'Palm' ,'Handspring' ,'Nokia' ,'Kyocera','Samsung' ,'Motorola' ,'Smartphone','Windows CE' ,'Blackberry' ,'WAP' ,'SonyEricsson','PlayStation Portable','LG','MMP','OPWV','Symbian','EPOC','Android'
    );

    foreach ($useragents as $useragents) {
        if(strstr($container, $useragents)) {
            $ismobile = 1;
        }
    }
    if ($ismobile == 1) {
    }
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Radiant Cash Management Services - Universe 1.0 (Beta Version)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="js/libs/jquery-1.10.2.min.js"></script>
    <script src="js/libs/bootstrap.min.js"></script>
    <link rel="stylesheet" media="screen" href="css/screen.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/additional-methods.min.js"></script>
    <script type="text/javascript">
    <?php if($user_name == 'admin') { ?>
    $(document).ready(function(e) {
        $("body").on("mouseover", ".mainnav-menu .dropdown", function() {
            $(".mainnav-menu .dropdown ul").children().css('display', 'inline-block');
        });
    });
    <?php } ?>

    <?php if ($ismobile == 1) { ?>
        $(".dropdown-backdrop").remove();
    <?php } ?>
</script>
<?php if ($ismobile == 1) { ?>
<style type="text/css">
.dropdown-backdrop {
    position: static !important;
}
</style>
<?php } ?>


    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="css/mvpready-admin.css">
    <link rel="stylesheet" href="css/mvpready-flat.css">
    <link rel="stylesheet" href="css/chosen.css">
    <style type="text/css" media="all">
    /* fix rtl for demo */

    .chosen-rtl .chosen-drop {
        left: -9000px;
    }
    </style>
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/icon.ico">
</head>
<!--oncopy="return false" oncut="return false" onpaste="return false"-->

<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner" style="width:100%;">
            <div class="container" style="padding-bottom:0;">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span> <i class="fa fa-cog"></i> </button>
                    <div style="float:left; color:#FFF; position:absolute; width:300px;  margin-top: 5px;">
                        <?php echo date("l | d-M-Y, g:i:s A") ?></div>
                    <div
                        style="color: #fff; float: right; position: relative;  text-align: right; width: 260px;  margin-top: 6px;">
                        Help</div>

                    <a href="./" class="navbar-brand navbar-brand-img">
                        <?php if($user == 'rpfprd' || $user == 'rpfhrd') { // raman 2019-08-02?><img
                            src="img/rpf_login_logos.png" style="margin-top: -14px;"
                            alt="Radiant Cash Management Services" width="100%"> <?php } else {?> <img
                            src="img/login_logos.png" style="margin-top: -14px;" alt="Radiant Cash Management Services"
                            width="100%"> <?php } ?> </a>



                </div>
                <!-- /.navbar-header -->
                <?php
$user_name = $_SESSION['lid'];
$query_header =  "select user_name from login where user_name ='$user_name'";
$run_query = mysql_query($query_header);
$result = mysql_fetch_assoc($run_query);
?>
                <nav class="collapse navbar-collapse" role="navigation">
                    <ul class="nav navbar-nav noticebar navbar-left">
                        <li class="dropdown" id="notify_id" rel="<?php echo $result["user_name"]; ?>"> <a
                                href="page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
                            </a>
                            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" id="event_list" role="menu">

                                <li> <a href="./?pid=cal&id=<?php // echo $res1->id;?>" class="noticebar-item"> <span
                                            class="noticebar-item-image"> <i
                                                class="fa fa-arrow-circle-right text-success"></i> </span> <span
                                            class="noticebar-item-body"> <strong class="noticebar-item-title">Name :
                                                <?php // echo ucfirst($res1->name);?></strong> <span
                                                class="noticebar-item-text">Date :
                                                <?php // echo date("d M Y",strtotime($res1->date));?></span> <span
                                                class="noticebar-item-time"><i class="fa fa-clock-o"></i> Created :
                                                <?php // echo date("d M Y H:i:s",strtotime($res1->created_time));?></span>
                                        </span> </a> </li>
                                <?php /*}*/ ?>

                                <!-- <li class="noticebar-menu-view-all">
                <a href="page-notifications.html">View All Notifications</a>
              </li>-->
                            </ul>
                        </li>

                        <!-- <li class="dropdown">
            <a href="page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope"  style="color: #FFFFFF;"></i>
              <span class="navbar-visible-collapsed">&nbsp;Messages&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">                
              <li class="nav-header">
                <div class="pull-left">
                  Messages
                </div>

                <div class="pull-right">
                  <a href="javascript:;">New Message</a>
                </div>
              </li>

              <li>
                <a href="page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="img/avatars/avatar-1-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                  </span>
                </a>
              </li>

              <li>
                <a href="page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="img/avatars/avatar-2-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 5 hours ago</span>
                  </span>
                </a>
              </li>

              <li class="noticebar-menu-view-all">
                <a href="page-notifications.html">View All Messages</a>
              </li>
            </ul>
          </li>-->

                        <!--<li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-exclamation-triangle" style="color: #FFFFFF;"></i>
              <span class="navbar-visible-collapsed">&nbsp;Alerts&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">                
              <li class="nav-header">
                <div class="pull-left">
                  Alerts
                </div>

              </li>

              <li class="noticebar-empty">                  
                <h4 class="noticebar-empty-title">No alerts here.</h4>
                <p class="noticebar-empty-text">Check out what other makers are doing on Explore!</p>                
              </li>
            </ul>
          </li>-->

                    </ul>
                    <!--<a href="./" class="navbar-brand navbar-brand-img">
	          <img src="img/img2.png" alt="MVP Ready" align="center" style="margin-left: 110px;">
	        </a>-->
                    <ul class="nav navbar-nav navbar-right" style="margin-top: -21px; margin-right: 44px;">
                        <li> <a href="javsacript:;">Welcome <?php echo $result["user_name"]; ?> |</a> </li>
                    </ul>
                    <div style="clear:both;"></div>
                    <ul class="nav navbar-nav navbar-right" style="margin-top: -54px;">
                        <!--<li style="margin-top: 20px; margin-right: 10px;"><i class="fa fa-font" style="color: #FFF; cursor: pointer;"></i></li>
			<li style="margin-top: 19px; margin-right: 10px;"><i class="fa fa-font" style="color: #FFF; font-size: 17px; cursor: pointer;"></i></li>
			<li style="margin-top: 17px; margin-right: 10px;"><i class="fa fa-font" style="color: #FFF; font-size: 19px; cursor: pointer;"></i></li>
			<li style="margin-top: 16px; margin-right: 10px;"><i class="fa fa-font" style="color: #FFF; font-size: 21px; cursor: pointer;"></i></li>-->

                        <li style="clear:both;"></li>
                        <li> <a data-toggle="modal" href="?pid=change_password" class="update_locid change_pwd"
                                onClick="change_pwd()">Change Password </a> </li>
                        <li><a>|</a></li>
                        <li><a <?php if($user == 'rpfprd' || $user == 'rpfhrd') {  ?> href="rpf_login.php?nav=0_6"
                                <?php } else { ?>href="login.php?nav=0_6" <?php } ?>> Sign out</a> </li>

                        <li class="dropdown navbar-profile" style="margin-top: -10px;"> <a class="dropdown-toggle"
                                data-toggle="dropdown" href="javascript:;"> <img src="img/avatars/avatar-1-xs.jpg"
                                    class="navbar-profile-avatar" alt=""> <span class="navbar-profile-label">rod@rod.me
                                    &nbsp;</span> <i class="fa fa-caret-down"></i> </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if($user == 'admin') { ?>
                                <li> <a href="?pid=email_setting"> <i class="fa fa-cogs"></i> &nbsp;&nbsp;Email Settings
                                    </a> </li>
                                
                                <li class="divider"></li>
                                <?php } ?>
                                <!-- <li> <a href="login.php"> <i class="fa fa-sign-out"></i> &nbsp;&nbsp;Logout </a> </li> -->
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- /.container -->

        </header>
        <div class="mainnav">
            <div class="container"> <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
                    <span class="sr-only">Toggle navigation</span> <i class="fa fa-bars"></i> </a>
                <nav class="collapse mainnav-collapse" role="navigation">
                    <form class="mainnav-form pull-right" role="search" style="display: none;">
                        <input type="text" class="form-control input-md mainnav-search-query" placeholder="Search">
                        <button class="btn btn-sm mainnav-form-btn"><i class="fa fa-search"></i></button>
                    </form>
                    <ul class="mainnav-menu">
                        <?php
    if($pid == '') {
        $pid = 'dash';
    }
$query_page = "SELECT menu_name, page_name, folder_name FROM page_creation WHERE page_id='".$pid."'";
$sql_page = mysql_query( $query_page);
$res_page = mysql_fetch_assoc($sql_page);


$query_menu =  "SELECT menu_name,page_title,page_name,page_id, menu_icon,other_link FROM page_creation WHERE status='Y' AND page_title!='' AND page_order!='0' AND sub_menu_order='0' ORDER BY page_order_main, page_order ASC";
$sql_menu = mysql_query( $query_menu);
while($res_menu = mysql_fetch_assoc($sql_menu)) {
    $menu_details[$res_menu['menu_name']]['page_title'][] = $res_menu['page_title'];
    $menu_details[$res_menu['menu_name']]['page_name'][] = $res_menu['page_name'];
    $menu_details[$res_menu['menu_name']]['page_id'][] = $res_menu['page_id'];
    $menu_details[$res_menu['menu_name']]['other_link'][] = $res_menu['other_link'];
    $menu_details[$res_menu['menu_name']]['menu_icon'][] = $res_menu['menu_icon'];
    $query_menu1 = "SELECT menu_name,page_title,page_name,page_id, menu_icon, menu_creation FROM page_creation WHERE status='Y' AND sub_menu_order!='0' AND menu_creation='".$res_menu['page_title']."' ORDER BY  	sub_menu_order ASC";

    $sql_menu1 = mysql_query( $query_menu1);
    while($res_menu1 = mysql_fetch_assoc($sql_menu1)) {
        $menu_details[$res_menu['menu_name']]['multi'][$res_menu1['menu_creation']]['page_title'][] = $res_menu1['page_title'];
        $menu_details[$res_menu['menu_name']]['multi'][$res_menu1['menu_creation']]['page_name'][] = $res_menu1['page_name'];
        $menu_details[$res_menu['menu_name']]['multi'][$res_menu1['menu_creation']]['page_id'][] = $res_menu1['page_id'];
        $menu_details[$res_menu['menu_name']]['multi'][$res_menu1['menu_creation']]['other_link'][] = $res_menu1['other_link'];
        $menu_details[$res_menu['menu_name']]['multi'][$res_menu1['menu_creation']]['menu_icon'][] = $res_menu1['menu_icon'];

    }
}

$sql_auth = mysql_query( "SELECT * FROM auth_t WHERE user_name='".$user."'");
$res_auth = mysql_fetch_assoc($sql_auth);
$page_acc = explode(',', $res_auth['pid']);

if(!empty($menu_details)) {
    foreach($menu_details as $key => $val) {
        $submenu_count = count($val['page_title']);

        $load_page = $val['page_id'];
        $result = array_intersect($page_acc, $load_page);
        $count_resul = count($result);
        if($count_resul > 0) {
            ?>
                        <li class="dropdown <?php if($key == $res_page['menu_name']) {
                            echo 'active';
                        }?>"> <a
                                href="<?php if(count($val['other_link']) == '1') {
                                    echo './';
                                } ?>"
                                class="dropdown-toggle" <?php if($submenu_count > 0) { ?>data-toggle="dropdown"
                                data-hover="dropdown"
                                <?php } else {
                                    echo 'style="padding-top: 9px;"';
                                }  ?>><?php echo $key; ?>
                                <?php if($submenu_count > 0) { ?>
                                <i class="mainnav-caret"></i>
                                <?php } ?>
                            </a>
                            <?php if($submenu_count >= 1) {
                                ?>
                            <ul class="dropdown-menu" role="menu">
                                <?php if(!empty($val['page_title'])) {
                                    foreach($val['page_title'] as $key1 => $val1) {
                                        if(in_array($val['page_id'][$key1], $page_acc)) {
                                            //echo $val1;&& count($menu_details[$key]['multi'][$val1]['page_title'])>0
                                            ?>
                                <li <?php if($user_name == 'admin') { ?>class="dropdown-submenu" <?php } ?>> <a
                                        href="<?php if($val['page_id'][$key1] == 'securefile') {
                                            echo 'http://203.196.171.245/HOST/';
                                        } else {
                                            echo '?pid='.$val['page_id'][$key1];
                                        } ?>"
                                        <?php if($val['page_id'][$key1] == 'securefile' || $key == 'Reports') { ?>target="_blank"
                                        <?php } ?>> <i class="fa <?php echo $val['menu_icon'][$key1]; ?>"></i>
                                        &nbsp;&nbsp;<?php echo $val1 ?> </a>

                                    <?php
                    if($user_name == 'admin') {

                        ?>
                                    <ul class="dropdown-menu">
                                        <?php
                        foreach($menu_details[$key]['multi'][$val1]['page_title'] as $key3 => $val3) {
                            ?>

                                        <li>
                                            <a
                                                href="<?php echo '?pid='.$menu_details[$key]['multi'][$val1]['page_id'][$key3]; ?>">
                                                <i
                                                    class="fa <?php echo $menu_details[$key]['multi'][$val1]['menu_icon'][$key3]; ?>"></i>
                                                &nbsp;&nbsp;<?php echo $menu_details[$key]['multi'][$val1]['page_title'][$key3]; ?>
                                            </a>
                                        </li>
                                        <?php }
                        ?>

                                    </ul>
                                    <?php
                    }
                                            ?>

                                </li>
                                <?php
                                        }
                                    }
                                } ?>
                            </ul>
                            <?php } ?>
                        </li>

                        <?php
        }
    }
}


if($user == 'rpfprd' || $user == 'rpfhrd') {
} else { 

    ?>
                        <li class="dropdown "> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"
                                data-hover="dropdown"> S/W User Guide<i class="mainnav-caret"></i> </a>
                            <ul class="dropdown-menu" role="menu">

                                <li class="dropdown-submenu"> <a tabindex="-1" href="?pid=cutof_time"> <i
                                            class="fa fa-bars"></i> &nbsp;&nbspReport Cutoff Time</a></li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbspPoint Operations</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Point Master
                                                Process (How to add Points?)</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;CE Mapping
                                                Process</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;Daily Transactions</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Daily
                                                Transaction Upload Process</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Daily Trans.
                                                MIS Update Process</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to send
                                                the Intimation/Emergency SMS sent from Software?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Bank Deposit
                                                Operation Process flow</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter
                                                bank Deposit in Various Deposits (Burial/PB/CB)?</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;Device Trans.</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Handheld
                                                Application</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Radiant
                                                Sandesh Application</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Radiant
                                                Darpan Application</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to
                                                Approve Mobile / Handheld Receipt in Region?</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;Banking</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Bank Account
                                                Creation</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Checked
                                                Report</a> </li>
                                        <li> <a href="?pid=sr_recon"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Reconciliation Process</a> </li>
                                        <li> <a href="?pid=float_recon"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Float Recon</a> </li>
                                    </ul>
                                </li>





                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;HRMS</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;HRMS -
                                                Staff/CE Appointment Process</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Update and
                                                Maintain Staff / CE Data</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Upload
                                                Staff/CE Documents in Software</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter
                                                the Daily staff attendance?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to upload
                                                the Monthly staff attendance?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to
                                                Generate the Payslip in Application?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to
                                                Generate the Regional Acutance Report?</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;Cash Van</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Cash Can
                                                Operation Process Flow</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;What is mean
                                                Cash Van Service?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to map
                                                the DCV Cash Van and assign crew members?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to Upload
                                                the CVR (Inter/Intra) Transactions?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to map
                                                the Cash Van for CVR Trans. and assign crew members?</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;MBC</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;MBC Process
                                                Flow</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to map
                                                the MBC Staff for MBC Point?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to Enter
                                                MBC Transaction in software?</a> </li>
                                    </ul>
                                </li><br>

                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;Vault</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="?pid=vault_flow"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Vault Process</a> </li>
                                        <!-- <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter Vault transaction from CE View?</a> </li>
        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter Information in Cashier View?</a> </li>
        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter Vault Cash Out Entry?</a> </li>-->
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i
                                            class="fa fa-bars"></i>&nbsp;&nbsp;BDS Application</a>
                                    <ul class="dropdown-menu">
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Process
                                                Flow</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Manual for
                                                Mobile Application Operations</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;How to enter
                                                Information in Cashier View?</a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Manual for
                                                Web Applications Operations</a> </li>
                                        <li> <a href="?pid=sr_bds"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Reconciliation Process</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;IT Team </a>
                                    <ul class="dropdown-menu">

                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Hardware
                                                Helpdesk </a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Incident
                                                Management </a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Network Issue
                                                / Software Slow </a> </li>
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;FAQ </a>
                                        </li><br />
                                        <li> <a href="#"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;Blog </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#"> <i class="fa fa-bars"></i>
                                        &nbsp;&nbsp;IT Policy </a>
                                    <ul class="dropdown-menu">

                                        <li> <a href="?pid=cmdvision"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;CMD Technology Vision 2012-13 </a> </li>
                                        <li> <a href="?pid=swplan"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Software Implementation Plan</a> </li>
                                        <li> <a href="?pid=faq_sop"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;RADIANT FAQ & SOP</a> </li>
                                        <li> <a href="?pid=itpolicy"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Hardware & Software Details </a> </li>
                                        <li> <a href="?pid=stakeholder"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Stake Holders Details </a> </li>
                                        <li> <a href="?pid=itteam"> <i class="fa fa-stack-exchange"></i> &nbsp;&nbsp;IT
                                                Team Members</a> </li>
                                        <li> <a href="?pid=apk_files"> <i class="fa fa-stack-exchange"></i>
                                                &nbsp;&nbsp;Android Apps Setup Files (.apk)</a> </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu"> <a tabindex="-1" href="#" onClick="view_isms_log();"> <i
                                            class="fa fa-bars"></i> &nbsp;&nbsp;ISMS Policy </a>
                                </li>
                                <li> <a tabindex="-1" href="?pid=erp"> <i class="fa fa-bars"></i> &nbsp;&nbsp;ERP
                                        Enhancement Suggestions</a>
                                </li>
                            </ul>
                        </li> <?php } ?>
                    </ul>
                </nav>
            </div>
            <!-- /.container -->

        </div>
        <!-- /.mainnav -->

        <div class="content">


            <?php
    $file_names_inc = $res_page['folder_name'].'/'.$res_page['page_name'];
//echo $file_names_inc;

if($file_names_inc != '') {
    include($file_names_inc);
}

if($pid == 'change_password') {
    include('change_password.php');
} elseif($pid == 'clientrep1') {
    include('client_completion1.php');
} elseif($pid == 'view_bffo1') {
    include('view_misreports_bfo1.php');
} elseif($pid == 'dcollect1') {
    include('daily_collection2.php');
} elseif($pid == 'gmis2') {
    include('view_misreports1.php');
} elseif($pid == 'vtranslog1') {
    include('view_translogs1.php');
} elseif($pid == 'modify') {
    include('modify_daily_trans.php');
} elseif($pid == 'sr_recon') {
    include('sr_recon.php');
} elseif($pid == 'sr_bds') {
    include('sr_bds.php');
} elseif($pid == 'cutof_time') {
    include('SoftwareUserGuide/cutof_time.php');
} elseif($pid == 'float_recon') {
    include('float_recon.php');
} elseif($pid == 'vault_flow') {
    include('SoftwareUserGuide/vault_process_flow.php');
} elseif($pid == 'erp') {
    include('SoftwareUserGuide/erp_enhance.php');
} elseif($pid == 'bds_console') {
    include('bds_frontend_console.php');
} elseif($pid == 'vault_console') {
    include('vault_reset_console.php');
} elseif($pid == 'daily_missed_console') {
    include('daily_missed_console.php');
} elseif($pid == 'daily_mail_console') {
    include('daily_mail_console.php');
} elseif($pid == 'all_region_dupload') {
    include('Transactions/all_region_upload_excel.php');
} elseif($pid == 'all_region_dupload_bajaj') {
    include('Transactions/all_region_upload_bajaj.php');
} elseif($pid == 'bulk_points_mod') {
    include('bulk_points.php');
} elseif($pid == 'EmpDocsMove') {
    include('employeeDocsMove.php');
} elseif($pid == 'cons_otp_manual') {
    include('view_otp_console.php');
} elseif($pid == 'axis_console_api') {
    include('axis_console.php');
} elseif($pid == 'erp_hcid_update') {
    include('console_erp_db_hcid_update.php');
} elseif($pid == 'QrReportTrans') {
    include('qrcode_daily_report.php');
}
?>
        </div>
        <!-- .content -->

    </div>
    <!-- /#wrapper -->

    <footer class="footer">
        <div class="container">
            <div class="pull-right" style="text-align:right; float:right; margin-top: 7px;">Powered By </div>
            <div style="clear:both;"></div>
            <p class="pull-right" style="margin-top: 9px;"><a href="https://radiantcashservices.com/"
                    target="_blank"><img src="img/footre_logos.jpg" alt="" /></a></p>
            <div style="clear:both;"></div>
            <?php if($user == 'rpfprd' || $user == 'rpfhrd') {
            } else {?>
            <div class="pull-left "
                style="text-align:right; float:right; margin-top: 17px; font-weight:bold; color:#025797;  font-size:12px;">
                Copy Rights Reserved &copy; Radiant Cash Management Services Ltd</div><?php } ?>





            <div class="pull-left "
                style="text-align:right; float:right; margin-top: 17px; width:100%; font-weight:bold; color:#037dd2; font-size:12px;">
                <div align="left">Programmed to deliver best performance using Google chrome, Firefox &amp; Best Viewed
                    in 1024 X 768 or 1280 X 720 Resolution </div>
                <div style="float:right; width:17%;">Terms of use | Privacy Policy</div>
            </div>

            <!-- 
     
     -->

        </div>
    </footer>

    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Core JS -->

    <!--[if lt IE 9]>
<script src="./js/libs/excanvas.compiled.js"></script>
<![endif]-->

    <!-- Plugin JS -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <!--<script src="js/plugins/flot/jquery.flot.pie.js"></script>-->
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- App JS -->
    <script src="js/mvpready-core.js"></script>
    <script src="js/mvpready-admin.js"></script>

    <!-- Plugin JS -->
    <!--<script src="js/demos/flot/line.js"></script> 
<script src="js/demos/flot/pie.js"></script> 
<script src="js/demos/flot/auto.js"></script> -->
    <script src="tree_view/jquery.tabelizer.js"></script>
    <script type="text/javascript" src="js/chosen.jquery.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
    <script type="text/javascript">
    /*if(window.top==window) {
	setInterval(function() {
		var r = confirm("Are you sure you want to delete this record?");
	if (r == true) {
		$.ajax({
			type: "POST",
			url: "ajax/delete_data.php",
			data: 'delate_id='+delate_id+'&del_tab='+del_tab,
			success: function(msg){
			}
		});
		
   	//alert("Message to alert every 5 seconds");
	 window.setTimeout('location.reload()'); 
	}
	else {
		alert('BB');
	}
}, 3000);
   //
}*/


    function delete_data(delate_id, del_tab) {
        var r = confirm("Are you sure you want to delete this record?");
        if (r == true) {
            $.ajax({
                type: "POST",
                url: "CommonReference/delete_data.php",
                data: 'delate_id=' + delate_id + '&del_tab=' + del_tab,
                success: function(msg) {

                    if (msg == 'Suc') {
                        $('.del_msg').css('display', '');
                        $('#delete_data' + delate_id).html('');
                    }
                }
            });
        }
    }

    function view_isms_log() {
        $.ajax({
            type: "POST",
            url: "isms_update.php",
            success: function(msg) {
                if (msg == 'Suc') {
                    window.open('isms_index.php?a=1', '_blank');
                }
            }
        });
    }

    function delete_data1(delate_id, del_tab, serv_type) {
        var r = confirm("Are you sure you want to delete this record?");
        if (r == true) {
            $.ajax({
                type: "POST",
                url: "ajax/delete_data.php",
                data: 'delate_id=' + delate_id + '&del_tab=' + del_tab,
                success: function(msg) {
                    if (msg == 'Suc') {
                        $('.del_msg').css('display', '');
                        setTimeout(function() {
                            $('.message_cu').fadeOut('fast');
                        }, 3000);
                        $('#delete_data_' + serv_type + delate_id).html('');
                    }
                }
            });
        }
    }

    function change_pwd() {
        $('.modal-dialog').css('width', '600px');
        $('.locad_change').css('display', '');
    }

    /*setTimeout(function(){
    	$.ajax({
    		type: "POST",
    		url: "ajax/load_data.php",
    		data: 'pid=time_out',
    		success: function(msg){
    			var x = location.href;
    			x1 = x.split('?');
    			window.location.href = x1[0]+'login.php'
    		}
    	});
    	
     //  window.location.reload(1);
    }, 1200000);*/
    </script>
</body>

</html>
<?php
//mysql_close($conn1);
?>