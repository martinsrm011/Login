<?php

@session_start();

require_once __DIR__ . '/DbConnection/dbConnect.php';

if(isset($_POST['submit'])) {
    $no_att = $_SESSION['no_att'];
    $page = $_REQUEST['pid'];
    $user = $_POST['username'];
    $pass = $_POST['pass'];

    function findDiff($date1, $date2)
    {
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }


    $user_name = mysqli_real_escape_string($readConnection, $_POST['user_name']);
    $sql = mysqli_query($readConnection, "SELECT * FROM login where user_name='".$user_name."' and password=BINARY '".mysqli_real_escape_string($readConnection, $_POST['upassword'])."' and status='Allowed'");
    $row = mysqli_num_rows($sql);
    $res_user = mysqli_fetch_assoc($sql);
    $resgg = explode(',', $res_user['region']);
    $ip 		=	$_SERVER['REMOTE_ADDR'];
    $time_now = time();
    $time = date('h:i:s A', $time_now);
    $from1 = date("d-M-Y");

    $from		=	$from1.", ".$time;
    $password_changed_at = $res_user['password_changed_at'];
    $today_date = date("Y-m-d");
    $password_date = date("Y-m-d", strtotime($password_changed_at));
    $diffdate = findDiff($password_date, $today_date);

    if($res_user['update_status'] == '0') {
        header("Location:change_password_new.php?pid=change_password&uname=".$user_name);
        exit;
    }

    if($diffdate > 45 && $row > 0) {
        header("Location:change_password_new.php?pid=force_change_password&uname=".$user_name); 
        exit;
    }

    $sql_open_t = mysqli_query($readConnection, "SELECT to_t FROM open_t WHERE `user_name`='".$user_name."' ORDER BY open_id DESC  LIMIT 0, 1");
    $res_open_t = mysqli_fetch_assoc($sql_open_t);


	$loagin_to = '2016-08-02 13:49:50'; // write here for single  user signin

    if($loagin_to == '0000-00-00 00:00:00') {
        header('location:login.php?nav=0_3');

    } else {
        $sqlt = "INSERT INTO open_t (`user_name`, `from_t`, `to_t`, `ip_add`) values ('".$user_name."','".$from."','','".$ip."')";
        $qut = mysqli_query($writeConnection, $sqlt);


        if($row > 0) {


            if($res_open_t['to_t'] == '') {
                $last_logout = $res_user['logout_status'];
                if(($last_logout + 1) <= 3) {
                    $logout_time = $last_logout + 1;
                    $statuss = 'Allowed';
                } else {
                    $logout_time = '0';
                    $statuss = 'Denied';
                }
            } else {
                $statuss = 'Allowed';
            }

			$sqlp1 = "SELECT open_id FROM open_t";
			$qup1 = mysqli_query($readConnection, $sqlp1);
			$open_id = mysqli_num_rows($qup1);
			$open_id = $open_id + 1;

            $sql_upd_login = mysqli_query($writeConnection, "UPDATE login SET login_from='".date('Y-m-d H:i:s')."', login_to='0000-00-00 00:00:00' WHERE user_name='".$user_name."'");

            $_SESSION['open_id'] = $open_id;
            $_SESSION['lid'] = $user_name;
            $_SESSION['per'] = $res_user['per'];
            $_SESSION['region'] = $res_user['region'];
            $_SESSION['emp_id'] = $res_user['emp_id'];
            $_SESSION['other_permissions'] = $res_user['other_permissions'];
			$_SESSION['vendor_id']=$res_user['vendor_id'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $isms_per = $res_user['isms_per'];

            if($isms_per == 1) {
                header("Location:./");
            } else {

                if($res_user['emp_id']==''){
                    header("Location:./");
                }
                else
                {
                    header("Location:isms_index.php"); // terms & Conditions
                }
               
            }

        } else {
            $sql1 = "SELECT user_name FROM login WHERE user_name='".$user_name."' AND status='Allowed'";
            $m1 = mysqli_query($readConnection, $sql1);
            $p1 = mysqli_num_rows($m1);
            $r1 = mysqli_fetch_assoc($m1);

            if($p1 == 1) {
                $no_att++;
                $_SESSION['no_att'] = $no_att;
            }

            if($no_att == 3) {
                $sql2 = "UPDATE login SET status='Denied' WHERE user_name='".$user_name."'";
                $m2 = mysqli_query($writeConnection, $sql2);
            }
            if($no_att >= 3) {
                $_SESSION['no_att'] = 0;
                header("Location:login.php?nav=0_2");
            } else {
                header("Location:login.php?nav=0_1");
            }

        }
    }

}


if(isset($_POST['submit1'])) {
    $no_att = $_SESSION['no_att'];

    $page = $_REQUEST['pid'];
    $user = $_POST['username'];
    $pass = $_POST['pass'];


    $user_name = mysqli_real_escape_string($readConnection, $_POST['user_name']);
    $sql = mysqli_query($readConnection, "SELECT * FROM login where user_name='".$user_name."' and password=BINARY '".mysqli_real_escape_string($readConnection, $_POST['upassword'])."' and status='Allowed'");
    $row = mysqli_num_rows($sql);
    $res_user = mysqli_fetch_assoc($sql);
    $resgg = explode(',', $res_user['region']);

    $ip 		=	$_SERVER['REMOTE_ADDR'];
    $time_now	=	time();
    $time		=	date('h:i:s A', $time_now);
    $from1		=	date("d-M-Y");
    $from		=	$from1.", ".$time;

    if($res_user['update_status'] == '0') {
        header("Location:change_password_new.php?uname=".$user_name); 
        exit;
    }

    //No of Login
    $sqlp1 = "SELECT open_id FROM open_t";
    $qup1 = mysqli_query($readConnection, $sqlp1);
    $open_id = mysqli_num_rows($qup1);
    $open_id = $open_id + 1;

    $sql_open_t11 = mysqli_query($readConnection, "SELECT to_t FROM open_t WHERE `user_name`='".$user_name."'");
    $res_open_t11 = mysqli_num_rows($sql_open_t11);

    $sql_open_t = mysqli_query($readConnection, "SELECT to_t FROM open_t WHERE `user_name`='".$user_name."' ORDER BY open_id DESC  LIMIT 0, 1");
    $res_open_t = mysqli_fetch_assoc($sql_open_t);




    $sql2 = mysqli_query($readConnection, "SELECT login_to FROM login where user_name='".$user_name."' and password= BINARY '".$_POST['upassword']."' and status='Allowed' AND per='Data Management' AND user_name!='admin'");
    $row2 = mysqli_num_rows($sql2);
    if($row2 > 0) {
        $res_user2 = mysqli_fetch_assoc($sql2);
        $loagin_to = '2016-08-02 13:49:50';
    } else {
        $loagin_to = '2016-08-02 13:49:50';
    }

    if($loagin_to == '0000-00-00 00:00:00') {
        header('location:rpf_login.php?nav=0_3');

    } else {
        $sqlt = "INSERT INTO open_t (`user_name`, `from_t`, `to_t`, `ip_add`) values ('".$user_name."','".$from."','','".$ip."')";
        $qut = mysqli_query($writeConnection, $sqlt);
        if($row > 0) {


            if($res_open_t['to_t'] == '') {
                $last_logout = $res_user['logout_status'];
                if(($last_logout + 1) <= 3) {
                    $logout_time = $last_logout + 1;
                    $statuss = 'Allowed';
                } else {
                    $logout_time = '0';
                    $statuss = 'Denied';
                }
            } else {
                $statuss = 'Allowed';
            }


            $sql_upd_login = mysqli_query($writeConnection, "UPDATE login SET login_from='".date('Y-m-d H:i:s')."', login_to='0000-00-00 00:00:00' WHERE user_name='".$user_name."'");

            $_SESSION['open_id'] = $open_id;
            $_SESSION['lid'] = $user_name;
            $_SESSION['per'] = $res_user['per'];
            $_SESSION['region'] = $res_user['region'];
            $_SESSION['emp_id'] = $res_user['emp_id'];
            $_SESSION['other_permissions'] = $res_user['other_permissions'];
            $ip = $_SERVER['REMOTE_ADDR'];

            $res = mysqli_fetch_assoc($sql);
            $_SESSION['lid'] = $user_name;

            $isms_per = $res_user['isms_per'];
            if($isms_per == 1) {
                header("Location:./".$alert);
            } else {
                header("Location:isms_index.php");
            }

        } else {
            $sql1 = "SELECT user_name FROM login WHERE user_name='".$user_name."' AND status='Allowed'";
            $m1 = mysqli_query($readConnection, $sql1);
            $p1 = mysqli_num_rows($m1);
            $r1 = mysqli_fetch_assoc($m1);

            if($p1 == 1) {
                $no_att++;
                $_SESSION['no_att'] = $no_att;
            }

            if($no_att == 3) {
                $sql2 = "UPDATE login SET status='Denied' WHERE user_name='".$user_name."'";
                $m2 = mysqli_query($writeConnection, $sql2);
            }
            if($no_att >= 3) {
                $_SESSION['no_att'] = 0;
                header("Location:rpf_login.php?nav=0_2");
            } else {
                header("Location:rpf_login.php?nav=0_1");
            }

        }
    }
}

