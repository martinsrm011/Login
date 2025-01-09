<?php

session_start();
ob_start();

date_default_timezone_set("Asia/Kolkata");
include('../logout.php');

require '../dependencies/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
if (isset($_REQUEST['pid']))	$pid = $_REQUEST['pid'];
//if(isset($_REQUEST['id']))	$id = $_REQUEST['id'];
$deleted_by = $_SESSION['lid'];
$deleted_date_time = date('Y-m-d H:i:s');
$per = $_SESSION['per'];
$id = $_REQUEST['id'];
$emp_id = $_REQUEST['emp_id'];

//echo $pid; die;
//$emp_id=$_SESSION['emp_id'];
if ($pid == "emp_data" || $pid == "emp_data_ce" || $pid == "emp_data_rpf" || $pid == "emp_data_rce") {

	$app_id = $_POST['app_id'];
	$emp_id = $_POST['emp_id'];
	$cname = $_POST['cname'];
	$pdesig = $_POST['pdesig'];
	$pdesig1 = $_POST['pdesig1'];
	$pbranch = $_POST['pbranch'];
	$plocation = $_POST['plocation'];
	$doj = date("Y-m-d", strtotime($_POST['doj']));


	$ini_sal = $_POST['ini_sal'];
	$report_to = $_POST['report_to'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$pin = $_POST['pin'];
	$cstate = $_POST['cstate'];


	$mobile1 = $_POST['mobile1'];
	$mobile2 = $_POST['mobile2'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];

	$dob = date("Y-m-d", strtotime($_POST['dob']));
	// if($dob=="1970-01-01")
	// {
	// 	$dob="0000-00-00";
	// }

	$today_date = date("Y-m-d");
	$diff = abs(strtotime($today_date) - strtotime($dob));

	$years = floor($diff / (365 * 60 * 60 * 24));


	$doa = date("Y-m-d", strtotime($_POST['doa']));
	// if($doa=="1970-01-01")
	// {
	// 	$doa="0000-00-00";
	// }

	$gender = $_POST['gender'];
	$mstatus = $_POST['mstatus'];
	$native = $_POST['native'];
	$religion = $_POST['religion'];
	$nation = $_POST['nation'];
	$place = $_POST['place'];
	$father_name = $_POST['father_name'];
	$father_occu = $_POST['father_occu'];
	$region1 = $_POST['region1'];
	$course1 = $_POST['course1'];
	$course2 = $_POST['course2'];
	$course3 = $_POST['course3'];
	$course4 = $_POST['course4'];
	$spec1 = $_POST['spec1'];
	$spec2 = $_POST['spec2'];
	$spec3 = $_POST['spec3'];
	$spec4 = $_POST['spec4'];
	$period1 = $_POST['period1'];
	$period2 = $_POST['period2'];
	$period3 = $_POST['period3'];
	$period4 = $_POST['period4'];
	$univ1 = $_POST['univ1'];
	$univ2 = $_POST['univ2'];
	$univ3 = $_POST['univ3'];
	$univ4 = $_POST['univ4'];
	$per1 = $_POST['per1'];
	$per2 = $_POST['per2'];
	$per3 = $_POST['per3'];
	$per4 = $_POST['per4'];

	$other1 = $_POST['other1'];
	$other2 = $_POST['other2'];
	$mtongue = $_POST['mtongue'];
	$lang = $_POST['lang'];
	$notice_period = $_POST['notice_period'];
	$ctg1=$_POST['category_type'];
	$eyears = $_POST['eyears'];
	$emonth = $_POST['emonth'];

	$from1 = $_POST['from1'];
	$from2 = $_POST['from2'];
	$from3 = $_POST['from3'];
	$from4 = $_POST['from4'];
	$from5 = $_POST['from5'];
	$emp1 = $_POST['emp1'];
	$emp2 = $_POST['emp2'];
	$emp3 = $_POST['emp3'];
	$emp4 = $_POST['emp4'];
	$emp5 = $_POST['emp5'];
	$position1 = $_POST['position1'];
	$position2 = $_POST['position2'];
	$position3 = $_POST['position3'];
	$position4 = $_POST['position4'];
	$position5 = $_POST['position5'];
	$rep1 = $_POST['rep1'];
	$rep2 = $_POST['rep2'];
	$rep3 = $_POST['rep3'];
	$rep4 = $_POST['rep4'];
	$rep5 = $_POST['rep5'];
	$job1 = $_POST['job1'];
	$job2 = $_POST['job2'];
	$job3 = $_POST['job3'];
	$job4 = $_POST['job4'];
	$job5 = $_POST['job5'];
	$sal1 = $_POST['sal1'];
	$sal2 = $_POST['sal2'];
	$sal3 = $_POST['sal3'];
	$sal4 = $_POST['sal4'];
	$sal5 = $_POST['sal5'];
	$reason1 = $_POST['reason1'];
	$reason2 = $_POST['reason2'];
	$reason3 = $_POST['reason3'];
	$reason4 = $_POST['reason4'];
	$reason5 = $_POST['reason5'];
	$update = date("Y-m-d");
	$blood_group = $_POST['blood_group'];
	$bank_name = $_POST['bank_name'];
	$account_no = $_POST['account_no'];
	$branch_name = $_POST['branch_name'];
	$ifsc_code = $_POST['ifsc_code'];
	$pan_card_no = $_POST['pan_card_no'];
	$docket_no = $_POST['docket_no'];
	$app_type = $_POST['app_type'];
	$replace_id = $_POST['replace_id'];
	$dor = $_POST['dor'];
	$gross_sal1 = $_POST['gross_sal1'];
	$ce_status = $_POST['ce_status'];
	$aadhar_card_no = $_POST['aadhar_card_no'];
	$basic_pay = $_POST['basic_pay'];

	$hra = $_POST['hra'];
	$conveyance = $_POST['conveyance'];
	$bonus = $_POST['bonus'];
	$oth_all = $_POST['oth_all'];
	$medical = $_POST['medical'];



	$epf_no = $_POST['epf_no'];
	$esi_no = $_POST['esi_no'];
	$uan_no = $_POST['uan_no'];
	$allowance_eligible = $_POST['allowance_eligible'];

	$division = $_POST['division'];

	$aggr_date = date('Y-m-d', strtotime($_POST['agg_date']));
	$path = $_POST['path'];

	$educational_qual=$_POST['edu_qual'];

    $employment_type=$_POST['employment_types'];
    $vendor_name=$_POST['vend_apnd'];

	if ($id == '')
	{
		$inactive_status_remark=$_POST['inactv_remarks'];
	}
	else
	{
		if($_POST['ce_status']=='Dormant')
		{
			$inactive_status_remark=$_POST['inactv_remarks'];
		}
		else
		{
			$inactive_status_remark='';
		}
	}
	
	// image
	$target_path = "emp_doc/";
	$update = date("Y-m-d");



	if ($id == '') {

		$valid_exts = array('jpeg', 'jpg', 'png', 'pdf');
		$max_file_size = 3000 * 1024;
		$nw = $nh = 500;
		$target_path = "emp_docs/";

		if (
			!$_FILES['emppic']['error'] && $_FILES['emppic']['size'] < $max_file_size
			&& !$_FILES['empsign']['error'] && $_FILES['empsign']['size'] < $max_file_size
			&& !$_FILES['backfrm']['error'] && $_FILES['backfrm']['size'] < $max_file_size
			&& !$_FILES['empappfrm']['error'] && $_FILES['empappfrm']['size'] < $max_file_size
			&& !$_FILES['aadharcard']['error'] && $_FILES['aadharcard']['size'] < $max_file_size
			&& !$_FILES['pancard']['error'] && $_FILES['pancard']['size'] < $max_file_size
		) {
			// insert query for emp_details
			$sql1 = "INSERT INTO hrms_empdet (docket_no,app_type,replace_id,reliv_date,emp_id, cname, dob,age, gender, pdesig, pdesig1,pbranch, region,plocation, doj, ini_sal, report_to, ctg1, mstatus, mobile1,mobile2, phone, email, address, city, pin, state, native,religion,nation, place, father_name, father_occu, mtongue, lang,doa,blood_group,bank_name,account_no,branch_name,exp_sal
			,ifsc_code,pan_card_no,aadhar_card_no,created_by,created_date,gross_sal,basic_pay,hra,convey,bonus,other_allc,medical,wstatus,status,epf_no,esi_no,uan_no,allowance_eligible,division,aggr_date,path,education_qualif,employment_type,vendor_name,inactive_status_remarks) VALUES ('" . $docket_no . "','" . $app_type . "','" . $replace_id . "','" . $dor . "','" . $emp_id . "','" . $cname . "','" . $dob . "','" . $years . "','" . $gender . "','" . $pdesig . "','" . $pdesig1 . "','" . $pbranch . "','" . $region1 . "','" . $plocation . "','" . $doj . "','" . $ini_sal . "','" . $report_to . "','" . $ctg1 . "', '" . $mstatus . "','" . $mobile1 . "','" . $mobile2 . "','" . $phone . "','" . $email . "', '" . $address . "', '" . $city . "', '" . $pin . "', '" . $cstate . "','" . $native . "','" . $religion . "','" . $nation . "','" . $place . "','" . $father_name . "','" . $father_occu . "','" . $mtongue . "','" . $lang . "','" . $doa . "','" . $blood_group .
						"','" . $bank_name . "','" . $account_no . "','" . $branch_name . "','" . $notice_period . "','" . $ifsc_code . "','" . $pan_card_no . "','" . $aadhar_card_no . "','" . $user_name . "','" . $update . "','" . $gross_sal1 . "','" . $basic_pay . "','" . $hra . "','" . $conveyance . "','" . $bonus . "','" . $oth_all . "','" . $medical . "','" . $ce_status . "','U','" . $epf_no . "','" . $esi_no . "','" . $uan_no . "','" . $allowance_eligible . "','" . $division . "','" . $aggr_date . "','" . $path . "','".$educational_qual."','".$employment_type."','".$vendor_name."','".$inactive_status_remark."')";
			$con = mysqli_query($writeConnection,$sql1);
			$last_id = mysqli_insert_id($writeConnection);

			$doc_type1 = "04";
			$filename1 = $_FILES['pancard']['name'];
			$base1 = basename($filename1);
			$exts1 = explode(".", $base1);
			$n1 = count($exts1);
			$ext1 = $exts1[$n1];
			$file1 = $last_id . "-" . $doc_type1 . "-1";
			$target1 = $file1 . "." . $exts1[1];

			$doc_type2 = "06";
			$filename2 = $_FILES['aadharcard']['name'];
			$base2 = basename($filename2);
			$exts2 = explode(".", $base2);
			$n2 = count($exts2);
			$ext2 = $exts2[$n2];
			$file2 = $last_id . "-" . $doc_type2 . "-1";
			$target2 = $file2 . "." . $exts2[1];

			$doc_type3 = "14";
			$filename3 = $_FILES['empappfrm']['name'];
			$base3 = basename($filename3);
			$exts3 = explode(".", $base3);
			$n3 = count($exts3);
			$ext3 = $exts3[$n3];
			$file3 = $last_id . "-" . $doc_type3 . "-1";
			$target3 = $file3 . "." . $exts3[1];

			$doc_type4 = "10";
			$filename4 = $_FILES['backfrm']['name'];
			$base4 = basename($filename4);
			$exts4 = explode(".", $base4);
			$n4 = count($exts4);
			$ext4 = $exts4[$n4];
			$file4 = $last_id . "-" . $doc_type4 . "-1";
			$target4 = $file4 . "." . $exts4[1];

			$doc_type5 = "09";
			$filename5 = $_FILES['emppic']['name'];
			$base5 = basename($filename5);
			$exts5 = explode(".", $base5);
			$n5 = count($exts5);
			$ext5 = $exts5[$n5];
			$file5 = $last_id . "-" . $doc_type5 . "-1";
			$target5 = $file5 . "." . $exts5[1];

			$doc_type6 = "20";
			$filename6 = $_FILES['empsign']['name'];
			$base6 = basename($filename6);
			$exts6 = explode(".", $base6);
			$n6 = count($exts6);
			$ext6 = $exts5[$n6];
			$file6 = $last_id . "-" . $doc_type6 . "-1";
			$target6 = $file6 . "." . $exts6[1];

			if (!empty($_FILES['empsign']["name"])) {
				move_uploaded_file($_FILES["empsign"]["tmp_name"], "emp_docs/" . $target6);

				$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type6 . "','','" . $target6 . "','" . $user_name . "','" . $update . "','Y')";

				$sql = mysqli_query($writeConnection,$sql1);
			}

			if (!empty($_FILES['emppic']["name"])) {
				move_uploaded_file($_FILES["emppic"]["tmp_name"], "emp_docs/" . $target5);

				$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type5 . "','','" . $target5 . "','" . $user_name . "','" . $update . "','Y')";

				$sql = mysqli_query($writeConnection,$sql1);
			}

			if (!empty($_FILES['backfrm']["name"])) {
				move_uploaded_file($_FILES["backfrm"]["tmp_name"], "emp_docs/" . $target4);

				$sqlx1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type4 . "','','" . $target4 . "','" . $user_name . "','" . $update . "','Y')";

				$sqlx = mysqli_query($writeConnection,$sqlx1);
			}

			if (!empty($_FILES['empappfrm']["name"])) {
				move_uploaded_file($_FILES["empappfrm"]["tmp_name"], "emp_docs/" . $target3);

				$sqlxxxx1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type3 . "','','" . $target3 . "','" . $user_name . "','" . $update . "','Y')";

				$sqlxxxx = mysqli_query($writeConnection,$sqlxxxx1);
			}

			if (!empty($_FILES['aadharcard']["name"])) {
				move_uploaded_file($_FILES["aadharcard"]["tmp_name"], "emp_docs/" . $target2);

				$sqlxxx1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type2 . "','','" . $target2 . "','" . $user_name . "','" . $update . "','Y')";

				$sqlxxx = mysqli_query($writeConnection,$sqlxxx1);
			}

			if (!empty($_FILES['pancard']["name"])) {
				move_uploaded_file($_FILES["pancard"]["tmp_name"], "emp_docs/" . $target1);

				$sqlxx1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $last_id . "','" . $doc_type1 . "','','" . $target1 . "','" . $user_name . "','" . $update . "','Y')";

				$sqlxx = mysqli_query($writeConnection,$sqlxx1);
			}

			//insert couece detail	
			$course = $_POST['course'];

			if (count($course) > 0) {

				foreach ($course as $key => $val) {
					if (empty($_POST['emp_edu_id'][$key])) {

						$sql = "insert into hrms_edu_details(r_id,course,spec,from_period,to_period,univ,per,created_by,created_date,status)values('" . $last_id . "','" . $val . "','" . $_POST['special'][$key] . "','" . $_POST['from_period'][$key] . "','" . $_POST['to_period'][$key] . "','" . $_POST['institution'][$key] . "','" . $_POST['percentage'][$key] . "','" . $per . "','" . date("Y-m-d") . "','Y')";

						$edu_sql = mysqli_query($writeConnection,$sql);
					} else {

						$sql = mysqli_query($writeConnection,"update hrms_edu_details set r_id='" . $id . "',course='" . $val . "',spec='" . $_POST['special'][$key] . "',from_period='" . $_POST['from_period'][$key] . "',to_period='" . $_POST['to_period'][$key] . "',univ='" . $_POST['institution'][$key] . "',per='" . $_POST['percentage'][$key] . "' where id='" . $_POST['edu_id'][$key] . "'");
					}
				}
			}
			//insert exp detail
			$years = $_POST['years'];
			//print_r($years); die;
			if (count($years) > 0) {

				foreach ($years as $key => $val) {
					//echo "success"; die;
					if (empty($_POST['emp_exp_id'][$key])) {

						$sql = "insert into hrms_exp_details (r_id,eyear,org,position,rep,sal,reason,created_by,created_date,status) values ('" . $last_id . "','" . $val . "','" . $_POST['name_address'][$key] . "','" . $_POST['positon'][$key] . "','" . $_POST['reporting_to'][$key] . "','" . $_POST['gross_sal'][$key] . "','" . $_POST['reason_for_leaving'][$key] . "','$user','" . date("Y-m-d") . "','Y')";
						$exp = mysqli_query($writeConnection,$sql);
					} else {
						$sql = mysqli_query($writeConnection,"update hrms_exp_details set r_id='" . $id . "',eyear='" . $val . "',org='" . $_POST['name_address'][$key] . "',position='" . $_POST['positon'][$key] . "',rep='" . $_POST['reporting_to'][$key] . "',sal='" . $_POST['gross_sal'][$key] . "',reason='" . $_POST['reason_for_leaving'][$key] . "' where exp_id='" . $_POST['emp_exp_id'][$key] . "'");
					}
				}
			}
			if ($con == TRUE) {
				

				header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
			} else {
				
				//echo mysqli_error();
				header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
			}
		} else {
			
			//echo mysqli_error();
			header('Location:../index.php?pid=' . $pid . '&nav=3_3_3');
		}
	} else {
		//echo $sql1;

		//update query for emp_details

		//if($user_name=='admin' || $user_name=='shaila' || $user_name=='riyas' || $user_name=='harishg' || $user_name=='surya')

		//if($user_name=='admin' || $user_name=='shaila' || $user_name=='harishg' || $user_name=='surya' || $user_name=='Joy' || $user_name=='Shahul' || $user_name=='prashanthr' ||  $user_name=='blessyshalom')
		if ($per == 'Admin' || $per == 'HR') {
			if ($pdesig1 == 'GN' || $pdesig1 == 'DR' || $pdesig1 == 'GUD') {
				$sql2 = "update hrms_empdet set emp_id= '" . $emp_id . "',cname='" . $cname . "',dob='" . $dob . "',age='" . $years . "',gender='" . $gender . "',pdesig='" . $pdesig . "',pdesig1='" . $pdesig1 . "',pbranch= '" . $pbranch . "',region='" . $region1 . "',plocation='" . $plocation . "',doj='" . $doj . "',ini_sal='" . $ini_sal . "', report_to= '" . $report_to . "',ctg1='" . $ctg1 . "',mstatus='" . $mstatus . "', mobile1='" . $mobile1 . "', mobile2='" . $mobile2 . "', phone='" . $phone . "',email='" . $email . "', address='" . $address . "', city='" . $city . "', pin='" . $pin . "', state='" . $cstate . "', native='" . $native . "', religion='" . $religion . "', nation='" . $nation . "', place='" . $place . "', father_name='" . $father_name . "', father_occu='" . $father_occu . "',blood_group='" . $blood_group . "',bank_name='" . $bank_name . "',account_no='" . $account_no . "',branch_name='" . $branch_name . "',ifsc_code='" . $ifsc_code . "',pan_card_no='" . $pan_card_no . "',aadhar_card_no='" . $aadhar_card_no . "',app_type='" . $app_type . "',replace_id='" . $replace_id . "',reliv_date='" . $dor . "',gross_sal='" . $gross_sal1 . "',basic_pay='" . $basic_pay . "',hra='" . $hra . "',convey='" . $conveyance . "',bonus='" . $bonus . "',other_allc='" . $oth_all . "',exp_sal='" . $notice_period . "',wstatus='" . $ce_status . "',epf_no='" . $epf_no . "',esi_no='" . $esi_no . "',uan_no='" . $uan_no . "',allowance_eligible='" . $allowance_eligible . "',division='" . $division . "',aggr_date='" . $aggr_date . "',path='" . $path . "',updated_by='" . $user_name . "',updated_date='" . date('Y-m-d H:i:s') . "',education_qualif='".$educational_qual."',employment_type='".$employment_type."',vendor_name='".$vendor_name."',inactive_status_remarks='".$inactive_status_remark."'  where r_id='" . $id . "'";
			} else {
				$sql2 = "update hrms_empdet set emp_id= '" . $emp_id . "',cname='" . $cname . "',dob='" . $dob . "',age='" . $years . "',gender='" . $gender . "',pdesig='" . $pdesig . "',pdesig1='" . $pdesig1 . "',pbranch= '" . $pbranch . "',region='" . $region1 . "',plocation='" . $plocation . "',doj='" . $doj . "',ini_sal='" . $ini_sal . "', report_to= '" . $report_to . "',ctg1='" . $ctg1 . "',mstatus='" . $mstatus . "', mobile1='" . $mobile1 . "', mobile2='" . $mobile2 . "', phone='" . $phone . "',email='" . $email . "', address='" . $address . "', city='" . $city . "', pin='" . $pin . "', state='" . $cstate . "', native='" . $native . "', religion='" . $religion . "', nation='" . $nation . "', place='" . $place . "', father_name='" . $father_name . "', father_occu='" . $father_occu . "',blood_group='" . $blood_group . "',bank_name='" . $bank_name . "',account_no='" . $account_no . "',branch_name='" . $branch_name . "',ifsc_code='" . $ifsc_code . "',pan_card_no='" . $pan_card_no . "',aadhar_card_no='" . $aadhar_card_no . "',app_type='" . $app_type . "',replace_id='" . $replace_id . "',reliv_date='" . $dor . "',exp_sal='" . $notice_period . "',wstatus='" . $ce_status . "',epf_no='" . $epf_no . "',esi_no='" . $esi_no . "',uan_no='" . $uan_no . "',allowance_eligible='" . $allowance_eligible . "',division='" . $division . "',aggr_date='" . $aggr_date . "',path='" . $path . "',updated_by='" . $user_name . "',updated_date='" . date('Y-m-d H:i:s') . "',education_qualif='".$educational_qual."',employment_type='".$employment_type."',vendor_name='".$vendor_name."',inactive_status_remarks='".$inactive_status_remark."'  where r_id='" . $id . "'";
			}
		} else {

			$sql2 = "update hrms_empdet set pbranch= '" . $pbranch . "',region='" . $region1 . "',plocation='" . $plocation . "',ini_sal='" . $ini_sal . "', report_to= '" . $report_to . "',ctg1='" . $ctg1 . "',mstatus='" . $mstatus . "', mobile2='" . $mobile2 . "', phone='" . $phone . "',email='" . $email . "', address='" . $address . "', city='" . $city . "', pin='" . $pin . "', state='" . $cstate . "', native='" . $native . "', religion='" . $religion . "', nation='" . $nation . "', place='" . $place . "',pan_card_no='" . $pan_card_no . "',aadhar_card_no='" . $aadhar_card_no . "', father_name='" . $father_name . "', father_occu='" . $father_occu . "',blood_group='" . $blood_group . "',app_type='" . $app_type . "',replace_id='" . $replace_id . "',reliv_date='" . $dor . "',exp_sal='" . $notice_period . "',epf_no='" . $epf_no . "',esi_no='" . $esi_no . "',uan_no='" . $uan_no . "',division='" . $division . "',aggr_date='" . $aggr_date . "',path='" . $path . "',updated_by='" . $user_name . "',updated_date='" . date('Y-m-d H:i:s') . "',education_qualif='".$educational_qual."',employment_type='".$employment_type."',vendor_name='".$vendor_name."',inactive_status_remarks='".$inactive_status_remark."' where r_id='" . $id . "'";
		}
		//update query for edu_detail

		$course = $_POST['course'];

		if (count($course) > 0) {

			foreach ($course as $key => $val) {
				if (empty($_POST['emp_edu_id'][$key])) {

					$sql = "insert into hrms_edu_details(r_id,course,spec,from_period,to_period,univ,per,created_by,created_date,status)values('" . $id . "','" . $val . "','" . $_POST['special'][$key] . "','" . $_POST['from_period'][$key] . "','" . $_POST['to_period'][$key] . "','" . $_POST['institution'][$key] . "','" . $_POST['percentage'][$key] . "','" . $per . "','" . date("Y-m-d") . "','Y')";

					$edu_sql = mysqli_query($writeConnection,$sql);
				} else {
					$sql = mysqli_query($writeConnection,"update hrms_edu_details set  r_id='" . $id . "',course='" . $val . "',spec='" . $_POST['special'][$key] . "',from_period='" . $_POST['from_period'][$key] . "',to_period='" . $_POST['to_period'][$key] . "',univ='" . $_POST['institution'][$key] . "',per='" . $_POST['percentage'][$key] . "' where edu_id='" . $_POST['emp_edu_id'][$key] . "'");
				}
			}
		}

		//update query for exp_details
		$years = $_POST['years'];
		//print_r($years); die;
		if (count($years) > 0) {

			foreach ($years as $key => $val) {
				//echo "success"; die;
				if (empty($_POST['emp_exp_id'][$key])) {


					$sql = "insert into hrms_exp_details (r_id,eyear,org,position,rep,sal,reason,created_by,created_date,status) values ('" . $id . "','" . $val . "','" . $_POST['name_address'][$key] . "','" . $_POST['positon'][$key] . "','" . $_POST['reporting_to'][$key] . "','" . $_POST['gross_sal'][$key] . "','" . $_POST['reason_for_leaving'][$key] . "','$user','" . date("Y-m-d") . "','Y')";
					$exp = mysqli_query($writeConnection,$sql);
				} else {
					$sql = mysqli_query($writeConnection,"update hrms_exp_details set r_id='" . $id . "',eyear='" . $val . "',org='" . $_POST['name_address'][$key] . "',position='" . $_POST['positon'][$key] . "',rep='" . $_POST['reporting_to'][$key] . "',sal='" . $_POST['gross_sal'][$key] . "',reason='" . $_POST['reason_for_leaving'][$key] . "' where exp_id='" . $_POST['emp_exp_id'][$key] . "'");
				}
			}
		}

		$sql_location = mysqli_query($writeConnection,"select rl.location_id from location_master lm join radiant_location rl on rl.location_id=lm.loc_id  inner join region_master rm on rm.region_id=rl.region_id where lm.location='" . $plocation . "' and lm.status='Y' and rl.status='Y' and rm.status='Y' and rm.region_name='" . $region1 . "'");
		$sql_fetch = mysqli_fetch_object($sql_location);

		$up_sql = mysqli_query($writeConnection,"select pdesig1,emp_id from hrms_empdet where r_id='" . $id . "'");
		$up_ce = mysqli_fetch_object($up_sql);

		$desig_array = array('CE', 'DR', 'GN', 'MBC', 'LD', 'SMBC', 'GUD');
		if (!in_array($up_ce->pdesig1, $desig_array)) {

			$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $pdesig1 . "'");
			$deg_row = mysqli_fetch_object($sql_deg);
			$desig1 = $deg_row->desig;

			//if($user_name=='admin' || $user_name=='shaila' || $user_name=='riyas' || $user_name=='harishg' || $user_name=='surya')

			//if($user_name=='admin' || $user_name=='shaila' || $user_name=='harishg' || $user_name=='surya' || $user_name=='Joy' || $user_name=='Shahul' ||  $user_name=='prashanthr'||  $user_name=='blessyshalom')
			if ($per == 'Admin' || $per == 'HR') {
				$sql_up = mysqli_query($writeConnection,"update radiant_staff set loc_id='" . $sql_fetch->location_id . "',emp_name='" . $cname . "',gender='" . $gender . "',dob='" . $dob . "',doj='" . $doj . "',design='" . $desig1 . "',father_name='" . $father_name . "',address='" . $address . "',pincode='" . $pin . "',panno='" . $pan_card_no . "',marital='" . $mstatus . "',email_id='" . $email . "',mobile1='" . $mobile1 . "',mobile2='" . $mobile2 . "',entry_by='" . $user_name . "',update_date='" . date("Y-m-d") . "' where emp_id='" . $up_ce->emp_id . "' ");
			} else {
				$sql_up = mysqli_query($writeConnection,"update radiant_staff set loc_id='" . $sql_fetch->location_id . "',father_name='" . $father_name . "',address='" . $address . "',pincode='" . $pin . "',marital='" . $mstatus . "',email_id='" . $email . "',mobile2='" . $mobile2 . "',entry_by='" . $user_name . "',update_date='" . date("Y-m-d") . "' where emp_id='" . $up_ce->emp_id . "' ");
			}
		} else {
			$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $pdesig1 . "'");
			$deg_row = mysqli_fetch_object($sql_deg);

			if ($pdesig1 == 'CE') {
				$desig1 = $deg_row->desig_code;
			} else {

				$desig1 = $deg_row->desig;
			}

			//if($user_name=='admin' || $user_name=='shaila' || $user_name=='riyas' || $user_name=='harishg' || $user_name=='surya')

			//if($user_name=='admin' || $user_name=='shaila' || $user_name=='harishg' || $user_name=='surya'  || $user_name=='Joy' || $user_name=='Shahul'  ||  $user_name=='prashanthr'||  $user_name=='blessyshalom')
			if ($per == 'Admin' || $per == 'HR') {
				$sql_up = mysqli_query($writeConnection,"update radiant_ce set loc_id='" . $sql_fetch->location_id . "',ce_name='" . $cname . "',gender='" . $gender . "',dob='" . $dob . "',doj='" . $doj . "',design='" . $desig1 . "',father_name='" . $father_name . "',address='" . $address . "',pincode='" . $pin . "',panno='" . $pan_card_no . "',marital='" . $mstatus . "',email_id='" . $email . "',mobile1='" . $mobile1 . "',mobile2='" . $mobile2 . "',wstatus='" . $ce_status . "',entry_by='" . $user_name . "',update_date='" . date("Y-m-d") . "' where ce_id='" . $up_ce->emp_id . "' ");
			} else {

				$sql_up = mysqli_query($writeConnection,"update radiant_ce set loc_id='" . $sql_fetch->location_id . "',father_name='" . $father_name . "',address='" . $address . "',pincode='" . $pin . "',marital='" . $mstatus . "',email_id='" . $email . "',mobile2='" . $mobile2 . "',entry_by='" . $user_name . "',update_date='" . date("Y-m-d") . "' where ce_id='" . $up_ce->emp_id . "' ");
			}
		}
		$qu2 = mysqli_query($writeConnection,$sql2);
		if ($qu2 == TRUE) {
			
			header('Location:../index.php?pid=' . $pid . '&nav=2_3_1');
		} else {
			
			//echo mysqli_error();
			header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
		}
	}
} elseif ($pid == "emp_verification") {
	$r_id = $_POST['id'];
	$veri_date = date("Y-m-d", strtotime($_POST['veri_date']));
	$veri_by = $_POST['veri_by'];
	$police_veri = $_POST['police_veri'];
	$address_veri = $_POST['address_veri'];
	$identity_veri = $_POST['identity_veri'];
	$other_veri = $_POST['other_veri'];
	$veri_remarks = $_POST['veri_remarks'];
	$entry_date = date("Y-m-d");

	if ($id == '') {

		$upd = mysqli_query($writeConnection,"update hrms_empdet set background_verify='Yes' where r_id='" . $r_id . "' ");

		$sql1 = "INSERT INTO hrms_empveri (r_id,veri_by,veri_date,police_veri,address_veri,identity_veri,other_veri,veri_remarks,update_by,update_date,status) VALUES (" . $r_id . ",'" . $veri_by . "','" . $veri_date . "','" . $police_veri . "','" . $address_veri . "','" . $identity_veri . "','" . $other_veri . "','" . $veri_remarks . "','" . $per . "','" . $entry_date . "','Y')";
		if (mysqli_query($writeConnection,$sql1) == TRUE) {
			
			header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
		} else {
			
			//echo mysqli_error();
			header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
		}
	} else {

		$upd = mysqli_query($writeConnection,"update hrms_empdet set background_verify='Yes' where r_id='" . $r_id . "' ");
		$sql1 = "update  hrms_empveri set r_id=" . $r_id . ",veri_by='" . $veri_by . "',veri_date='" . $veri_date . "',police_veri='" . $police_veri . "',address_veri='" . $address_veri . "',identity_veri='" . $identity_veri . "',other_veri='" . $other_veri . "',veri_remarks='" . $veri_remarks . "',update_by='" . $per . "',update_date='" . $entry_date . "',status='Y' where r_id='" . $id . "'";
		if (mysqli_query($writeConnection,$sql1) == TRUE) {
			
			header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
		} else {
			
			//echo mysqli_error();
			header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
		}
	}
}
 elseif ($pid == "emp_doc" || $pid=="rce_emp_doc") {


	$valid_exts = array('jpeg', 'jpg', 'png', 'pdf');
	$max_file_size = 3000 * 1024; #3mb
	$nw = $nh = 500; # image with # height
	$id1 = $_POST['doc_id'];
	$doc_empid = $_POST['doc_empid'];
	$doc_type = $_POST['id_proof'];
	$doc_no = $_POST['proof_no'];
	$image = $_POST['image'];
	$proof_doc = $_POST['proof_doc'];
	$update = date("Y-m-d");
	$filename = $_FILES['image']['name'];
	$base1 = basename($filename);
	$exts = explode(".", $base1);
	$n = count($exts);
	$ext = $exts[$n];

	$file_name = $_FILES["proof_doc"]["name"];
	$base11 = basename($file_name);
	$exts1 = explode(".", $base11);
	$n1 = count($exts1);
	$ext1 = $exts1[$n1];

	$sqlp = "select * from hrms_empdoc where r_id=" . $id1 . " and doc_type='" . $doc_type . "'";
	$qup = mysqli_query($writeConnection,$sqlp);
	$np = mysqli_num_rows($qup);
	$np = $np + 1;
	$target_path = "../emp_docs/";
	$file1 = $doc_empid . "-" . $doc_type . "-" . $np;
	$target = $target . $file1 . "." . $exts[1];
	$target1 = $target1 . $file1 . "." . $exts1[1];

	//$target_path = $target . $target;
	// if($doc_type=='10' || $doc_type=='12' || $doc_type=='14' || $doc_type=='21' || $doc_type=='06' || $doc_type=='15' || $doc_type=='22' || $doc_type=='23' || $doc_type=='24')  commented by hariharan
	if ($doc_type == '01' || $doc_type == '02' || $doc_type == '03' || $doc_type == '04' || $doc_type == '05' || $doc_type == '06' || $doc_type == '07' || $doc_type == '08' || $doc_type == '10' || $doc_type == '11' || $doc_type == '12' || $doc_type == '14' || $doc_type == '15' || $doc_type == '16' || $doc_type == '17' || $doc_type == '18' || $doc_type == '19' || $doc_type == '21' || $doc_type == '22' || $doc_type == '23' || $doc_type == '24' || $doc_type == '25') {

		if (!empty($_FILES['proof_doc']["name"])) {
			$fileName = $_FILES['proof_doc']['name']; 
            $fileTmpPath = $_FILES['proof_doc']['tmp_name'];
			$zipFileName = uniqid('compressed_', true) . '.zip';
			$zip = new ZipArchive();
			if ($zip->open($target_path . $zipFileName, ZipArchive::CREATE) === true) {
				if (!empty($fileTmpPath)) {
					$zip->addFile($fileTmpPath, $fileName);
					$zip->close();
				}
				move_uploaded_file($target_path . $zipFileName, $target_path . $zipFileName);
			}

			move_uploaded_file($_FILES["proof_doc"]["tmp_name"], "../emp_docs/" . $target1);
			$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $id1 . "','" . $doc_type . "','" . $doc_no . "','" . $target1 . "','" . $per . "','" . $update . "','Y')";

			$sql = mysqli_query($writeConnection,$sql1);
		}
	} else {

		if (isset($_FILES['image'])) {

			if (!$_FILES['image']['error'] && $_FILES['image']['size'] < $max_file_size) {

				$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
				if (in_array($ext, $valid_exts)) {
					//C:\wamp\www\RCMS\HRMS\js\plugins\img_crop\uploads
					//$path = 'js/plugins/img_crop/uploads/' . uniqid() . '.' . $ext;
					$path1 = '../js/plugins/img_crop/uploads/' . $target /*. '.' . $ext*/;
					$path2 = '../emp_docs/' . $target /*. '.' . $ext*/;
					$size = getimagesize($_FILES['image']['tmp_name']);
					$size = getimagesize($_FILES['image']['tmp_name']);
					$x = (int) $_POST['x'];
					$y = (int) $_POST['y'];
					$w = (int) $_POST['w'] ? $_POST['w'] : $size[0];
					$h = (int) $_POST['h'] ? $_POST['h'] : $size[1];
					$data = file_get_contents($_FILES['image']['tmp_name']);
					$vImg = imagecreatefromstring($data);
					//echo $dstImg; die;
					$dstImg = imagecreatetruecolor($nw, $nh);
					//echo $x.' ',$y.' ',$w.' ',$h.' ',$nh.' ',$nw.' '; die;
					imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
					//imagejpeg($dstImg, $path);
					imagejpeg($dstImg, $path1);
					imagejpeg($dstImg, $path2);

					//imagedestroy($dstImg);
					//	echo "<img src='$path2' />";
					//if(move_uploaded_file(imagejpeg($dstImg, $path1), $target_path)){
					if (imagejpeg($dstImg, $path2)) {
						//echo $doc_type;
						//echo $sql1="INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('".$id1."','".$doc_type."','".$doc_no."','".$target."','".$per."','".$update."','Y')"; die;
						move_uploaded_file($_FILES['image']['tmp_name'], "../emp_docs/" . $target);
						$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $id1 . "','" . $doc_type . "','" . $doc_no . "','" . $target . "','" . $user_name . "','" . $update . "','Y')";

						$sql = mysqli_query($writeConnection,$sql1);
					}
					//die;
				}
			}
		}
	}
	if ($sql == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&id=' . $id1 . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&id=' . $id1 . '&nav=2_2_1');
	}
} elseif ($pid == "leave_apply") {
	$location = $_POST['location'];
	$emp_name = $_POST['emp_id'];
	$leave_date = date("Y-m-d", strtotime($_POST['leave_date']));
	$leave_date1 = date("Y-m-d", strtotime($_POST['leave_date1']));
	$leave_sch = $_POST['leave_sch'];
	$leave_type = $_POST['leave_type'];
	$time_now = time(date('h') + 5, date('i') + 30, date('s'));
	$time = date('h:i:s A', $time_now);
	$from1 = date("d-m-Y");
	$from = $from1 . ", " . $time;

	$sql1 = "INSERT INTO hrms_leaveapp (emp_id,pbranch,leave_date,leave_date1,leave_sch,leave_type,leave_app,update_by,update_time,status) VALUES ('" . $emp_name . "','" . $location . "','" . $leave_date . "','" . $leave_date1 . "','" . $leave_sch . "','" . $leave_type . "','" . $per . "','" . $per . "','" . $from . "','Y')";
	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
} elseif ($pid == "leave_approval") {
	$app_id = $_POST['id'];
	$app_by = $_POST['app_by'];
	$time_now = time(date('h') + 5, date('i') + 30, date('s'));
	$time = date('h:i:s A', $time_now);
	$from1 = date("d-m-Y");
	$from = $from1 . ", " . $time;
	$applied_by = $_POST['applied_by'];

	if ($app_by == "Y") {


		$sql1 = "update hrms_leaveapp set leave_approve='" . $applied_by . "',app_time='" . $from . "' where app_id=" . $app_id . " and status='Y'";
	} elseif ($app_by == "N") {

		$sql1 = "update hrms_leaveapp set leave_approve='',app_time='' where app_id=" . $app_id . " and status='Y'";
	}
	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
}
 elseif ($pid == 'attendance') {

	//print_r($_POST); die;
	//echo $file_name = $_FILES["attendance_file"]["name"]; die;
	$branch = $_POST['branch_id'];
	//echo $_POST['date_of_sal']; die;
	$date_arr = explode("-", date('m-Y', strtotime($_POST['date_of_sal'])));
	$up_date = date('Y-m-d');

	//print_r($date_arr);
	/* $select_holiday_amt = mysqli_query($writeConnection,"select holiday_amt from atm_branch_holiday_amt where status='Y' and branch_id = '".$branch."'");
		if(mysqli_num_rows($select_holiday_amt)>0){
			$row_amt = mysqli_fetch_object($select_holiday_amt);
			$holiday_amt = $row_amt->holiday_amt;
		}else{
			$holiday_amt = 0;
		} */

	if ($branch != 'Delhi') {
		$holiday_amt = 0;
		$current_month_day = cal_days_in_month(CAL_GREGORIAN, $date_arr[0], $date_arr[1]);
		//echo $current_month_day; die;
		$dos = $date_arr[1] . "-" . $date_arr[0] . "-" . $current_month_day;
		//echo $dos; die;
		//$dos = date('Y-m-d',strtotime($date_of_salary)); 
		$attendance_month_year = date('M-Y', strtotime($_POST['date_of_sal']));

		$radiant_id = $_POST['radiant_id'];
		$radiant_id_selected = array();
		$radiant_id_selected = array_filter(explode("%", $radiant_id));
		//	print_r($radiant_id_selected); die;
		//echo $file_name = $_FILES["attendance_file"]["name"]; die;
		$file_name = $_SESSION['attendance_request'];
		move_uploaded_file($_FILES["attendance_file"]["tmp_name"], "HRMS_Files/Upload/" . $file_name);
		//echo $file_name; die;
		$inputFileName = 'HRMS_Files/Upload/' . $file_name;
		//echo $inputFileName; die;
		try {
			$spreadsheet = IOFactory::load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
		$xcelActiveData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		$xcelActiveCount = count($xcelActiveData);
		if (count($radiant_id_selected) > 0) {

			$sno = 0;
			for ($i = 10; $i <= $xcelActiveCount; $i++) {
				if (trim($xcelActiveData[$i]["B"]) != '') {
					if (in_array(trim($xcelActiveData[$i]["B"]), $radiant_id_selected)) {
						$j = 1;
						$select_sal = mysqli_query($writeConnection,"select emp_id,cname,doj,pdesig,pdesig1,gross_sal from hrms_empdet where status='Y' and trim(emp_id) = '" . trim($xcelActiveData[$i]["B"]) . "'");
						if (mysqli_num_rows($select_sal) > 0) {
							$rd = mysqli_fetch_object($select_sal);
							$radiant_id = $rd->emp_id;
							$emp_name = $rd->cname;
							$rad_doj = $rd->doj;
							$designation = $rd->pdesig1;
							$department = $rd->pdesig;

							if ($branch != 'Mumbai') {
								$gross_salary_fixed = $rd->gross_sal;
								$basic_fixed = round(($rd->gross_sal * (50 / 100)), 0);
								$hra_fixed = round(($basic_fixed * (50 / 100)), 0);
								$conveyance_fixed = 800;
								$bonus_fixed = round(($gross_salary_fixed * (8.33 / 100)), 0);
								if ($rd->gross_sal == 30000) {

									$medical_fixed = 1250;
								} else {
									$medical_fixed = 0;
								}
							} else {

								//mumbai pay slip 
								$gross_salary_fixed = $rd->gross_sal;
								$basic_fixed = $rd->basic_pay;
								$hra_fixed = round(($basic_fixed * (5 / 100)), 0);
								$conveyance_fixed = 800;
								$bonus_fixed = round(($gross_salary_fixed * (8.33 / 100)), 0);
								if ($rd->gross_sal == 30000) {

									$medical_fixed = 1250;
								} else {
									$medical_fixed = 0;
								}
							}
							$array_alpha = array(1 => 'G', 2 => 'H', 3 => 'I', 4 => 'J', 5 => 'K', 6 => 'L', 7 => 'M', 8 => 'N', 9 => 'O', 10 => 'P', 11 => 'Q', 12 => 'R', 13 => 'S', 14 => 'T', 15 => 'U', 16 => 'V', 17 => 'W', 18 => 'X', 19 => 'Y', 20 => 'Z', 21 => 'AA', 22 => 'AB', 23 => 'AC', 24 => 'AD', 25 => 'AE', 26 => 'AF', 27 => 'AG', 28 => 'AH', 29 => 'AI', 30 => 'AJ', 31 => 'AK', 32 => 'AL', 33 => 'AM', 34 => 'AN', 35 => 'AO', 36 => 'AP', 37 => 'AQ', 38 => 'AR', 39 => 'AS', 40 => 'AT', 41 => 'AU', 42 => 'AV', 43 => 'AW', 44 => 'AX', 45 => 'AY', 46 => 'AZ', 47 => 'BA', 48 => 'BB', 49 => 'BC', 50 => 'BD');
							$month_days_cell = $array_alpha[$current_month_day + 1];
							$tot_present_days_cell = $array_alpha[$current_month_day + 10];
							$tot_sun_working_cell = $array_alpha[$current_month_day + 2];
							$tot_tele_allce = $array_alpha[$current_month_day + 11];
							$tot_incentive = $array_alpha[$current_month_day + 12];
							$tot_sunallc = $array_alpha[$current_month_day + 13];
							$tot_othallc = $array_alpha[$current_month_day + 14];
							$tot_ot_hrs_cell = $array_alpha[$current_month_day + 16];
							$sal_adv_cell = $array_alpha[$current_month_day + 17];
							$tot_misc_amt_cell = $array_alpha[$current_month_day + 18];
							$oth_ded_cell = $array_alpha[$current_month_day + 19];
							$tot_hol_working_cell = $array_alpha[$current_month_day + 3];

							$other_all_fixed = $gross_salary_fixed - ($basic_fixed + $hra_fixed + $conveyance_fixed  + $bonus_fixed);
							//Gross Pay 
							$total_present_days = trim($xcelActiveData[$i][$tot_present_days_cell]) + trim($xcelActiveData[$i][$tot_sun_working_cell]) + trim($xcelActiveData[$i][$tot_hol_working_cell]);

							//echo $total_present_days." ".$xcelActiveData[$i]["AL"]; die;
							$div = ($xcelActiveData[$i][$month_days_cell] * $total_present_days);
							//$basic_pay =  ($basic_fixed /  $div); 
							//$basic_pay = round( ($basic_fixed / (trim($xcelActiveData[$i]["AL"]) ) * $total_present_days,0);
							$basic_pay = round(($basic_fixed / (trim($xcelActiveData[$i][$month_days_cell]))) * $total_present_days, 0);
							$hra_pay = round(($hra_fixed / (trim($xcelActiveData[$i][$month_days_cell]))) * $total_present_days, 0);
							$conveyance_pay = round(($conveyance_fixed / (trim($xcelActiveData[$i][$month_days_cell]))) * $total_present_days, 0);
							$bonus_pay = round(($bonus_fixed / (trim($xcelActiveData[$i][$month_days_cell]))) * $total_present_days, 0);
							$other_all_pay = round(($other_all_fixed / (trim($xcelActiveData[$i][$month_days_cell]))) * $total_present_days, 0);

							$excel_radiant_id =	trim($xcelActiveData[$i]["B"]);
							foreach ($array_alpha as $key => $stack_value) {

								if (trim($xcelActiveData[$i][$stack_value]) == 'RE') {
									$query = "Update hrms_empdet set status='N',e_date='" . $up_date . "' where emp_id='" . $excel_radiant_id . "'";
									$resign_status = mysqli_query($writeConnection,$query);
								}
							}

							if (trim($xcelActiveData[$i][$tot_ot_hrs_cell]) > 0 && trim($xcelActiveData[$i][$tot_ot_hrs_cell]) != '') {

								$pre_ot_amt = (trim($xcelActiveData[$i][$tot_ot_hrs_cell]));
							} else {
								$pre_ot_amt = "0";
							}

							/*	if(trim($xcelActiveData[$i][$tot_ot_hrs_cell]) > 0 && trim($xcelActiveData[$i][$tot_ot_hrs_cell]) != ''){
								$per_day1_sal = round($gross_salary_fixed/26,0);
								$per_hr_sal = round($per_day1_sal/9,0);
								$pre_ot_amt = round(trim($xcelActiveData[$i][$tot_ot_hrs_cell]) * $per_hr_sal,0);
							}else{
								$pre_ot_amt = "0";	
							}*/
							$pre_salary_fixed = $rd->gross_sal;

							//$pre_holiday_amt = trim($xcelActiveData[$i][$tot_hwork_working_cell]) * $holiday_amt;
							$variable_allowance = $pre_ot_amt + trim($xcelActiveData[$i][$tot_misc_amt_cell]) +  trim($xcelActiveData[$i][$tot_tele_allce]) + trim($xcelActiveData[$i][$tot_incentive]) + trim($xcelActiveData[$i][$tot_sunallc]) + trim($xcelActiveData[$i][$tot_othallc]);
							$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $el_pay + $bonus_pay + $other_all_pay + $variable_allowance, 0);

							//echo "Gross Pay".$pre_gross_pay; die;
							//Deductions
							$epf = round(($basic_pay * (12 / 100)), 0);
							$esi = round(($pre_gross_pay * (1.75 / 100)), 0);
							$p_tax = 0;
							$tds = 0;
							$salary_advance = trim($xcelActiveData[$i][$sal_adv_cell]);
							$other_deductions = trim($xcelActiveData[$i][$oth_ded_cell]);

							$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions, 0);
							$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);
						}
						$field_name = $colmn = "";
						//echo $field_name; die;
						for ($f1 = 1; $f1 <= $current_month_day; $f1++) {
							$field_name .= "d_" . $f1 . ",";
							$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1]]) . "',";
						}
						//echo $array_alpha[$f1]; die;
						//$colmn .= trim($xcelActiveData[$i][$array_alpha[$f1]]).",";

						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						//$colmn .= "'".trim($xcelActiveData[$i][$array_alpha[$f1++]])."',";
						$colmn = substr($colmn, 0, -1);
						//echo $colmn; die;
						//	echo "insert into hrms_attendance (branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department,".$field_name."m_days,sundays,holidays,sal_days,present_days,total_el,total_cl,total_sl,total_ml,total_present,tele_allce,incentive,oth_allce,lop,ot_hrs,sal_advance,misc_amt,other_ded,remarks,holiday_amt,pre_gross_fixed,pre_ot_fixed,pre_holiday_amt,pre_gross_pay,pre_net_pay,status,created_by,created_date) values ('".$branch."','".$dos."','".trim($xcelActiveData[$i]["B"])."','".trim($emp_name)."','".$attendance_month_year."','".date('Y-m-d',strtotime(trim($rad_doj)))."','".trim($designation)."','".trim($department)."',".$colmn.",'".$holiday_amt."','".$pre_salary_fixed."','".$pre_ot_amt."','".$pre_holiday_amt."','".$pre_gross_pay."','".$pre_net_payable."','Y','$user',now())"; die;
						$sql_insert = mysqli_query($writeConnection,"insert into hrms_attendance (branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department," . $field_name . "m_days,sundays,holidays,sal_days,present_days,total_el,total_cl,total_sl,total_ml,total_present,tele_allce,incentive,oth_allce,lop,ot_hrs,sal_advance,misc_amt,other_ded,remarks,holiday_amt,pre_gross_fixed,pre_ot_fixed,pre_holiday_amt,pre_gross_pay,pre_net_pay,status,created_by,created_date) values ('" . $branch . "','" . $dos . "','" . trim($xcelActiveData[$i]["B"]) . "','" . trim($emp_name) . "','" . $attendance_month_year . "','" . date('Y-m-d', strtotime(trim($rad_doj))) . "','" . trim($designation) . "','" . trim($department) . "'," . $colmn . ",'" . $holiday_amt . "','" . $pre_salary_fixed . "','" . $pre_ot_amt . "','" . $pre_holiday_amt . "','" . $pre_gross_pay . "','" . $pre_net_payable . "','Y','$user',now())");
						$sno++;
						$msg = "Attendance Uploaded Successfully";
					}
				}
			}
		}
		if ($sql_insert) {
			echo json_encode(array("result_response" => "success", "rows" => $sno, "msg" => $msg));
		} else {
			echo json_encode(array("result_response" => "failure", "msg" => "Attendance Failed to Insert"));
		}
	} else {
		// Delhi Attendance
		$holiday_amt = 0;
		$current_month_day = cal_days_in_month(CAL_GREGORIAN, $date_arr[0], $date_arr[1]);
		//echo $current_month_day; die;
		$dos = $date_arr[1] . "-" . $date_arr[0] . "-" . $current_month_day;
		//echo $dos; die;
		//$dos = date('Y-m-d',strtotime($date_of_salary)); 
		$attendance_month_year = date('M-Y', strtotime($_POST['date_of_sal']));

		$radiant_id = $_POST['radiant_id'];
		$radiant_id_selected = array();
		$radiant_id_selected = array_filter(explode("%", $radiant_id));
		//	print_r($radiant_id_selected); die;
		//echo $file_name = $_FILES["attendance_file"]["name"]; die;
		$file_name = $_SESSION['attendance_request'];
		move_uploaded_file($_FILES["attendance_file"]["tmp_name"], "HRMS_Files/Upload/" . $file_name);
		//echo $file_name; die;
		$inputFileName = 'HRMS_Files/Upload/' . $file_name;
		//echo $inputFileName; die;
		try {
			$spreadsheet = IOFactory::load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
		$xcelActiveData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		$xcelActiveCount = count($xcelActiveData);
		if (count($radiant_id_selected) > 0) {

			$sno = 0;
			for ($i = 4; $i <= $xcelActiveCount; $i++) {
				if (trim($xcelActiveData[$i]["B"]) != '') {
					if (in_array(trim($xcelActiveData[$i]["B"]), $radiant_id_selected)) {
						$j = 1;

						$select_sal = mysqli_query($writeConnection,"select emp_id,cname,doj,pdesig,pdesig1,gross_sal from hrms_empdet where status='Y' and trim(emp_id) = '" . trim($xcelActiveData[$i]["B"]) . "'");
						if (mysqli_num_rows($select_sal) > 0) {
							$rd = mysqli_fetch_object($select_sal);
							$radiant_id = $rd->emp_id;
							$emp_name = $rd->cname;
							$rad_doj = $rd->doj;
							$designation = $rd->pdesig1;
							$department = $rd->pdesig;
							$gross_salary_fixed = $rd->gross_sal;

							$basic_fixed = round(($rd->gross_sal * (50 / 100)), 0);
							$hra_fixed = round(($basic_fixed * (50 / 100)), 0);
							$conveyance_fixed = 800;
							$bonus_fixed = round(($gross_salary_fixed * (8.33 / 100)), 0);
							if ($rd->gross_sal == 30000) {

								$medical_fixed = 1250;
							} else {
								$medical_fixed = 0;
							}

							$array_alpha = array(1 => 'G', 2 => 'H', 3 => 'I', 4 => 'J', 5 => 'K', 6 => 'L', 7 => 'M', 8 => 'N', 9 => 'O', 10 => 'P', 11 => 'Q', 12 => 'R', 13 => 'S', 14 => 'T', 15 => 'U', 16 => 'V', 17 => 'W', 18 => 'X', 19 => 'Y', 20 => 'Z', 21 => 'AA', 22 => 'AB', 23 => 'AC', 24 => 'AD', 25 => 'AE', 26 => 'AF', 27 => 'AG', 28 => 'AH', 29 => 'AI', 30 => 'AJ', 31 => 'AK', 32 => 'AL', 33 => 'AM', 34 => 'AN', 35 => 'AO', 36 => 'AP', 37 => 'AQ', 38 => 'AR', 39 => 'AS', 40 => 'AT', 41 => 'AU', 42 => 'AV', 43 => 'AW', 44 => 'AX', 45 => 'AY', 46 => 'AZ', 47 => 'BA', 48 => 'BB', 49 => 'BC', 50 => 'BD');
							$month_days_cell = $array_alpha[$current_month_day + 1];
							$tot_present_days_cell = $array_alpha[$current_month_day + 10];
							$tot_sun_working_cell = $array_alpha[$current_month_day + 2];
							$tot_tele_allce = $array_alpha[$current_month_day + 11];
							$tot_incentive = $array_alpha[$current_month_day + 12];
							$tot_othallc = $array_alpha[$current_month_day + 13];
							$tot_ot_hrs_cell = $array_alpha[$current_month_day + 15];
							$sal_adv_cell = $array_alpha[$current_month_day + 16];
							$tot_misc_amt_cell = $array_alpha[$current_month_day + 17];
							$oth_ded_cell = $array_alpha[$current_month_day + 18];
							$tot_hol_working_cell = $array_alpha[$current_month_day + 3];

							$other_all_fixed = $gross_salary_fixed - ($basic_fixed + $hra_fixed + $conveyance_fixed  + $bonus_fixed);
							//Gross Pay 
							$total_present_days = trim($xcelActiveData[$i]["H"]);

							//echo $total_present_days." ".$xcelActiveData[$i]["AL"]; die;
							$div = ($xcelActiveData[$i][$month_days_cell] * $total_present_days);
							//$basic_pay =  ($basic_fixed /  $div); 
							//$basic_pay = round( ($basic_fixed / (trim($xcelActiveData[$i]["AL"]) ) * $total_present_days,0);
							$basic_pay = round(($basic_fixed / (trim($xcelActiveData[$i]["G"]))) * $total_present_days, 0);
							$hra_pay = round(($hra_fixed / (trim($xcelActiveData[$i]["G"]))) * $total_present_days, 0);
							$conveyance_pay = round(($conveyance_fixed / (trim($xcelActiveData[$i]["G"]))) * $total_present_days, 0);
							$bonus_pay = round(($bonus_fixed / (trim($xcelActiveData[$i]["G"]))) * $total_present_days, 0);
							$other_all_pay = round(($other_all_fixed / (trim($xcelActiveData[$i]["G"]))) * $total_present_days, 0);

							$excel_radiant_id =	trim($xcelActiveData[$i]["B"]);

							/*	
							if(trim($xcelActiveData[$i][$tot_ot_hrs_cell]) > 0 && trim($xcelActiveData[$i][$tot_ot_hrs_cell]) != ''){
								$per_day1_sal = round($gross_salary_fixed/26,0);
								$per_hr_sal = round($per_day1_sal/9,0);
								$pre_ot_amt = round(trim($xcelActiveData[$i][$tot_ot_hrs_cell]) * $per_hr_sal,0);
							}else{
								$pre_ot_amt = "0";	
							}*/
							$pre_salary_fixed = $rd->gross_sal;

							$pre_ot_amt = trim($xcelActiveData[$i]["q"]);
							$variable_allowance = $pre_ot_amt + trim($xcelActiveData[$i]["N"]) +  trim($xcelActiveData[$i]["O"]) + trim($xcelActiveData[$i]["P"]) + trim($xcelActiveData[$i]["Q"]);
							$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $el_pay + $bonus_pay + $other_all_pay + $variable_allowance, 0);

							//echo "Gross Pay".$pre_gross_pay; die;
							//Deductions
							$epf = round(($basic_pay * (12 / 100)), 0);
							$esi = round(($pre_gross_pay * (1.75 / 100)), 0);
							$p_tax = 0;
							$tds = 0;
							$salary_advance = trim($xcelActiveData[$i]["R"]);
							$other_deductions = trim($xcelActiveData[$i]["S"]);
							$penal = trim($xcelActiveData[$i]["T"]);

							$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions + $penal, 0);
							$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);
						}
						$field_name = $colmn = "";
						//echo $field_name; die;
						for ($f1 = 1; $f1 <= $current_month_day; $f1++) {
							$field_name .= "d_" . $f1 . ",";
							$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1]]) . "',";
						}
						//echo $array_alpha[$f1]; die;
						//$colmn .= trim($xcelActiveData[$i][$array_alpha[$f1]]).",";

						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						$colmn .= "'" . trim($xcelActiveData[$i][$array_alpha[$f1++]]) . "',";
						//$colmn .= "'".trim($xcelActiveData[$i][$array_alpha[$f1++]])."',";
						$colmn = substr($colmn, 0, -1);
						//echo $colmn; die;
						//echo "insert into hrms_attendance (branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department,m_days,total_el,total_cl,total_sl,total_ml,total_present,tele_allce,incentive,oth_allce,lop,sal_advance,misc_amt,other_ded,remarks,holiday_amt,pre_gross_fixed,pre_ot_fixed,pre_holiday_amt,pre_gross_pay,pre_net_pay,status,created_by,created_date) values ('".$branch."','".$dos."','".trim($xcelActiveData[$i]["B"])."','".trim($emp_name)."','".$attendance_month_year."','".date('Y-m-d',strtotime(trim($rad_doj)))."','".trim($designation)."','".trim($department)."','".trim($xcelActiveData[$i]["G"])."','".trim($xcelActiveData[$i]["J"])."','".trim($xcelActiveData[$i]["K"])."','".trim($xcelActiveData[$i]["L"])."','".trim($xcelActiveData[$i]["M"])."','".trim($xcelActiveData[$i]["H"])."','".trim($xcelActiveData[$i]["N"])."','".trim($xcelActiveData[$i]["O"])."','".trim($xcelActiveData[$i]["P"])."','".trim($xcelActiveData[$i]["I"])."','".trim($xcelActiveData[$i]["R"])."','".trim($xcelActiveData[$i]["S"])."','".trim($xcelActiveData[$i]["T"])."','".trim($xcelActiveData[$i]["U"])."','".$holiday_amt."','".$pre_salary_fixed."','".trim($xcelActiveData[$i]["Q"])."','".$pre_holiday_amt."','".$pre_gross_pay."','".$pre_net_payable."','Y','$user',now())"; die;
						$sql_insert = mysqli_query($writeConnection,"insert into hrms_attendance (branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department,m_days,total_el,total_cl,total_sl,total_ml,present_days,total_present,tele_allce,incentive,oth_allce,lop,sal_advance,misc_amt,other_ded,remarks,holiday_amt,pre_gross_fixed,pre_ot_fixed,pre_holiday_amt,pre_gross_pay,pre_net_pay,status,created_by,created_date) values ('" . $branch . "','" . $dos . "','" . trim($xcelActiveData[$i]["B"]) . "','" . trim($emp_name) . "','" . $attendance_month_year . "','" . date('Y-m-d', strtotime(trim($rad_doj))) . "','" . trim($designation) . "','" . trim($department) . "','" . trim($xcelActiveData[$i]["G"]) . "','" . trim($xcelActiveData[$i]["J"]) . "','" . trim($xcelActiveData[$i]["K"]) . "','" . trim($xcelActiveData[$i]["L"]) . "','" . trim($xcelActiveData[$i]["M"]) . "','" . trim($xcelActiveData[$i]["H"]) . "','" . trim($xcelActiveData[$i]["H"]) . "','" . trim($xcelActiveData[$i]["N"]) . "','" . trim($xcelActiveData[$i]["O"]) . "','" . trim($xcelActiveData[$i]["P"]) . "','" . trim($xcelActiveData[$i]["I"]) . "','" . trim($xcelActiveData[$i]["R"]) . "','" . trim($xcelActiveData[$i]["S"]) . "','" . trim($xcelActiveData[$i]["T"]) . "','" . trim($xcelActiveData[$i]["U"]) . "','" . $holiday_amt . "','" . $pre_salary_fixed . "','" . trim($xcelActiveData[$i]["Q"]) . "','" . $pre_holiday_amt . "','" . $pre_gross_pay . "','" . $pre_net_payable . "','Y','$user',now())");
						$sno++;
						$msg = "Attendance Uploaded Successfully";
					}
				}
			}
		}
		if ($sql_insert) {
			echo json_encode(array("result_response" => "success", "rows" => $sno, "msg" => $msg));
		} else {
			echo json_encode(array("result_response" => "failure", "msg" => "Attendance Failed to Insert"));
		}
	}
}

 elseif ($pid == "salary_master") {
	$emp_id = $_POST['emp_id'];
	$r_id = $_POST['id'];
	$basic_pay = $_POST['basic_pay'];
	$hra = $_POST['hra'];
	$conveyance = $_POST['conveyance'];


	$medical = $_POST['medical'];
	$bonus = $_POST['bonus'];
	$oth_all = $_POST['oth_all'];
	$gross_sal = $_POST['gross_sal'];

	$epf = $_POST['epf'];
	$esi = $_POST['esi'];
	$p_tax = $_POST['p_tax'];
	$tds = $_POST['tds'];
	$sal_adv = $_POST['sal_adv'];
	$gross_dedu = $_POST['gros_dedu'];


	$entry_date = date("Y-m-d");


	$sql1 = "INSERT INTO hrms_salary_master (r_id,emp_id,basic_pay,hra,	conveyance,medical,bonus,oth_all,gross_sal,epf,esi,p_tax,tds,sal_adv,gross_dedu,created_by,created_date,status) VALUES (" . $r_id . ",'" . $emp_id . "','" . $basic_pay . "','" . $hra . "','" . $conveyance . "','" . $medical . "','" . $bonus . "','" . $oth_all . "','" . $gross_sal . "', '" . $epf . "', '" . $esi . "', '" . $p_tax . "','" . $tds . "','" . $sal_adv . "', '" . $gross_dedu . "', '" . $per . "', '" . $entry_date . "','Y')";

	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
} elseif ($pid == "paymaster") {

	$from_date = date("Y-m-d", strtotime($_POST['from_date']));
	$dr_allowance = $_POST['dr_allowance'];
	$hr_allowance = $_POST['hr_allowance'];
	$con_allowance = $_POST['con_allowance'];
	$spl_allowance = $_POST['spl_allowance'];
	$pr_fund = $_POST['pr_fund'];
	$pay_remarks = $_POST['pay_remarks'];

	$sql1 = "INSERT INTO hrms_paymaster (from_date,dr_allowance,hr_allowance,con_allowance,spl_allowance,pr_fund,pay_remarks) VALUES (" . $from_date . ",'" . $dr_allowance . "','" . $hr_allowance . "','" . $con_allowance . "','" . $spl_allowance . "','" . $pr_fund . "','" . $pay_remarks . "')";
	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
}


if ($pid == "applied") {


	$name = $_POST['name'];
	$pdesig = $_POST['pdesig'];
	$plocation = $_POST['plocation'];


	$address = $_POST['address'];
	$city = $_POST['city'];
	$pin = $_POST['pin'];
	$cstate = $_POST['cstate'];
	$mobile1 = $_POST['mobile1'];
	$mobile2 = $_POST['mobile2'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];

	$dob = date("Y-m-d", strtotime($_POST['dob']));
	$gender = $_POST['gender'];
	$mstatus = $_POST['mstatus'];
	$native = $_POST['native'];
	$religion = $_POST['religion'];
	$nation = $_POST['nation'];
	$place = $_POST['place'];
	$father_name = $_POST['father_name'];
	$father_occu = $_POST['father_occu'];

	$course1 = $_POST['course1'];
	$course2 = $_POST['course2'];
	$course3 = $_POST['course3'];
	$course4 = $_POST['course4'];
	$spec1 = $_POST['spec1'];
	$spec2 = $_POST['spec2'];
	$spec3 = $_POST['spec3'];
	$spec4 = $_POST['spec4'];
	$period1 = $_POST['period1'];
	$period2 = $_POST['period2'];
	$period3 = $_POST['period3'];
	$period4 = $_POST['period4'];
	$univ1 = $_POST['univ1'];
	$univ2 = $_POST['univ2'];
	$univ3 = $_POST['univ3'];
	$univ4 = $_POST['univ4'];
	$per1 = $_POST['per1'];
	$per2 = $_POST['per2'];
	$per3 = $_POST['per3'];
	$per4 = $_POST['per4'];



	$other1 = $_POST['other1'];
	$other2 = $_POST['other2'];
	$mtongue = $_POST['mtongue'];
	$lang = $_POST['lang'];
	$exp_sal = $_POST['exp_sal'];

	$ctg1 = $_POST['ctg1'];



	$from1 = $_POST['from1'];
	$to1 = $_POST['to1'];

	$diff1 = abs(strtotime($from1) - strtotime($to1));

	$year1 = floor($diff1 / (365 * 60 * 60 * 24));
	$month1 = floor(($diff1 - $year1 * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$day1 = floor(($diff1 - $year1 * 365 * 60 * 60 * 24 - $month1 * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	//echo $year1."<br>";
	//echo $month1."....<br>";
	$from2 = $_POST['from2'];
	$to2 = $_POST['to2'];

	$diff2 = abs(strtotime($from2) - strtotime($to2));
	$year2 = floor($diff2 / (365 * 60 * 60 * 24));
	$month2 = floor(($diff2 - $year2 * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$day2 = floor(($diff2 - $year2 * 365 * 60 * 60 * 24 - $month2 * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	//echo $year2."<br>";
	//echo $month2."<br>";
	$from3 = $_POST['from3'];
	$to3 = $_POST['to3'];

	$diff3 = abs(strtotime($from3) - strtotime($to3));
	$year3 = floor($diff3 / (365 * 60 * 60 * 24));
	$month3 = floor(($diff3 - $year3 * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$day3 = floor(($diff3 - $year3 * 365 * 60 * 60 * 24 - $month3 * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	$from4 = $_POST['from4'];
	$to4 = $_POST['to4'];
	//echo $year3."<br>";
	//echo $month3."<br>";

	$diff4 = abs(strtotime($from4) - strtotime($to4));
	$year4 = floor($diff4 / (365 * 60 * 60 * 24));
	$month4 = floor(($diff4 - $year4 * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$day4 = floor(($diff4 - $year4 * 365 * 60 * 60 * 24 - $month4 * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	//echo $year4."<br>";
	//echo $month4."<br>";
	$from5 = $_POST['from5'];
	$to5 = $_POST['to5'];

	$diff5 = abs(strtotime($from5) - strtotime($to5));
	$year5 = floor($diff5 / (365 * 60 * 60 * 24));
	$month5 = floor(($diff5 - $year5 * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$day5 = floor(($diff5 - $year5 * 365 * 60 * 60 * 24 - $month5 * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	//echo $year5."<br>"; 
	//echo $month5."<br>";

	$year_all = $year1 + $year2 + $year3 + $year4 + $year5;
	$month_all = $month1 + $month2 + $month3 + $month4 + month5;

	if ($month_all > 0) {
		$year_cal1 = $month_all / 12;
		$month_all1 = $month_all % 12;
	}
	//echo $year_cal1."<br>";
	$year_all1 = floor($year_cal1);
	$tot = $year_all + $year_all1;
	$totexp = $tot . "  Year(s) and    " . $month_all1 . "  Month(s)";
	//echo $totexp; 
	$emp1 = $_POST['emp1'];
	$emp2 = $_POST['emp2'];
	$emp3 = $_POST['emp3'];
	$emp4 = $_POST['emp4'];
	$emp5 = $_POST['emp5'];
	$position1 = $_POST['position1'];
	$position2 = $_POST['position2'];
	$position3 = $_POST['position3'];
	$position4 = $_POST['position4'];
	$position5 = $_POST['position5'];
	$rep1 = $_POST['rep1'];
	$rep2 = $_POST['rep2'];
	$rep3 = $_POST['rep3'];
	$rep4 = $_POST['rep4'];
	$rep5 = $_POST['rep5'];
	$job1 = $_POST['job1'];
	$job2 = $_POST['job2'];
	$job3 = $_POST['job3'];
	$job4 = $_POST['job4'];
	$job5 = $_POST['job5'];
	$sal1 = $_POST['sal1'];
	$sal2 = $_POST['sal2'];
	$sal3 = $_POST['sal3'];
	$sal4 = $_POST['sal4'];
	$sal5 = $_POST['sal5'];
	$reason1 = $_POST['reason1'];
	$reason2 = $_POST['reason2'];
	$reason3 = $_POST['reason3'];
	$reason4 = $_POST['reason4'];
	$reason5 = $_POST['reason5'];
	$update = date("Y-m-d");

	$sql1 = "INSERT INTO hrms_applied_new (cname, dob, gender, pdesig, plocation, ctg1, exp_sal, mstatus, mobile1, mobile2, phone, email, address, city, pin, state, native,religion,nation, place, father_name, father_occu,mtongue, lang,created_by,created_date,status) VALUES ('" . $name . "','" . $dob . "','" . $gender . "','" . $pdesig . "','" . $plocation . "','" . $ctg1 . "','" . $exp_sal . "','" . $mstatus . "','" . $mobile1 . "','" . $mobile2 . "','" . $phone . "','" . $email . "', '" . $address . "', '" . $city . "', '" . $pin . "', '" . $cstate . "','" . $native . "','" . $religion . "','" . $nation . "','" . $place . "','" . $father_name . "','" . $father_occu . "','" . $mtongue . "','" . $lang . "','" . $per . "' ,'" . $update . "', 'Y')";
	//insert couece detail
	$max = count($_POST['course']);
	if ($max > 0) { //if more  row avails
		$query = "insert into hrms_app_edudet(r_id,course,spec,from_period,to_period,univ,per,created_by,created_date,status)values";

		foreach ($_POST['course'] as $row => $val) {

			$parent_content = mysqli_real_escape_string($writeConnection,$_POST['course'][$row]);
			$special = mysqli_real_escape_string($writeConnection,$_POST['special'][$row]);
			$from_period = mysqli_real_escape_string($writeConnection,$_POST['from_period'][$row]);
			$to_period = mysqli_real_escape_string($writeConnection,$_POST['to_period'][$row]);
			$institution = mysqli_real_escape_string($writeConnection,$_POST['institution'][$row]);
			$percentage = mysqli_real_escape_string($writeConnection,$_POST['percentage'][$row]);

			$query .= "('" . $last_id . "','" . $parent_content . "','" . $special . "','" . $from_period . "','" . $to_period . "','" . $institution . "','" . $percentage . "','$per','" . date("Y-m-d") . "','Y'),";
		} // max loop end
		$query = substr($query, 0, -1);
		$sql = mysqli_query($writeConnection,$query);
	}

	//insert exp detail
	$max = count($_POST['years']);
	if ($max > 0) { //if more  row avails
		$query1 = "insert into hrms_app_expdet(r_id,eyear,org,position,rep,sal,reason,created_by,created_date,status)values";

		foreach ($_POST['years'] as $row => $val) {

			$eyear = mysqli_real_escape_string($writeConnection,$_POST['years'][$row]);
			$org = mysqli_real_escape_string($writeConnection,$_POST['name_address'][$row]);
			$position = mysqli_real_escape_string($writeConnection,$_POST['positon'][$row]);
			$rep = mysqli_real_escape_string($writeConnection,$_POST['reporting_to'][$row]);
			$sal = mysqli_real_escape_string($writeConnection,$_POST['gross_sal'][$row]);
			$reason = mysqli_real_escape_string($writeConnection,$_POST['reason_for_leaving'][$row]);
			$query1 .= "('" . $last_id . "','" . $eyear . "','" . $org . "','" . $position . "','" . $rep . "','" . $sal . "','" . $reason . "','$per','" . date("Y-m-d") . "','Y'),";
		} // max loop end
		$query1 = substr($query1, 0, -1);

		$sql = mysqli_query($writeConnection,$query1);
	}

	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
} elseif ($pid == "app_review") {
	$r_id = $_POST['id'];
	$int_date = date("Y-m-d", strtotime($_POST['int_date']));
	$inform_date = $_POST['inform_date'];
	$last_date = $_POST['last_date'];
	$posi_name = $_POST['position'];
	$report_to = $_POST['report_to'];
	$salary = $_POST['salary'];
	$remarks = $_POST['remarks'];
	$entry_date = date("Y-m-d");
	$status = $_POST['status'];

	$sql1 = "INSERT INTO hrms_join_new (app_id,position,int_date,inform_date,last_date,salary,report_to,remarks,entry_date,entry_by,status) VALUES (" . $r_id . ",'" . $posi_name . "','" . $int_date . "','" . $inform_date . "','" . $last_date . "'," . $salary . ",'" . $report_to . "','" . $remarks . "', '" . $entry_date . "', '" . $user . "', '" . $status . "')";

	if (mysqli_query($writeConnection,$sql1) == TRUE) {
		$sql2 = "update hrms_applied_new set status='A' where r_id=" . $id . "";
		$qu2 = mysqli_query($writeConnection,$sql2);
		
		header('Location:../index.php?pid=' . $pid . '&nav=2_1_1&id1=' . $r_id);
	} else {
		
		//echo mysqli_error();
		header('Location:../index.php?pid=' . $pid . '&nav=2_2_1');
	}
} elseif ($pid == 'unapprove') {

	//echo '<pre>'; print_r($_POST); die;
	$id = $_POST['id'];
	$appr_date = date("Y-m-d", strtotime($_POST['app_date']));
	$appr_by = $_POST['appr_by'];
	$remarks = $_POST['remarks'];
	$region_name = $_POST['region_name'];
	$app_status = $_POST['app_status'];
	$desig = $_POST['desig'];
	$emp_state = $_POST['state'];
	$emp_name = $_POST['emp_name'];
	$emp_doj = $_POST['emp_doj'];
	$emp_dob = $_POST['emp_dob'];
	$mobile11 = $_POST['mobile11'];
	$mobile2 = $_POST['mobile21'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$gender = $_POST['gender'];
	$pin = $_POST['pin'];
	$pan_card_no = $_POST['pan_card_no'];
	$mobilew = $_POST['mobile2'];
	$father_name = $_POST['father_name'];
	$location = $_POST['emp_location'];

	$mstatus = $_POST['mstatus'];
	$wstatus = $_POST['wstatus'];
	//echo "select loc_id from location_master where location='".$location."'"; die;
	//echo  "select rl.location_id from location_master lm join radiant_location rl on rl.location_id=lm.loc_id where lm.location='".$location."' and lm.status='Y' and rl.status='Y'  "; die;

	//echo "select rl.location_id from location_master lm join radiant_location rl on rl.location_id=lm.loc_id where lm.location='".$location."' and lm.status='Y' and rl.status='Y'"; die;

	$sql_location = mysqli_query($writeConnection,"select rl.location_id from location_master lm join radiant_location rl on rl.location_id=lm.loc_id where lm.location='" . $location . "' and lm.status='Y' and rl.status='Y'");
	$sql_fetch = mysqli_fetch_object($sql_location);
	/* **********auto increment*********/
	if ($app_status == 'Y') {
		if ($desig == 'GUD' || $desig == 'DR' || $desig == 'GN') {
			if ($region_name != 'RADIANT H.O') {
				$sql2 = mysqli_query($writeConnection,"SELECT state_code FROM state_code WHERE state_name='" . $emp_state . "' AND status='Y'");
				$res_ste = mysqli_fetch_object($sql2);
				$state_code = $res_ste->state_code;


				//$sql3 = mysqli_query($writeConnection,"SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RPF-".$state_code."%' AND state='".$emp_state."' and status='Y' and (pdesig1='GUD' OR pdesig1='DR' OR pdesig1='GN'  ) ORDER BY emp_id DESC");
				//raghu 27-11-2019 start
				// echo "SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RPF-".$state_code."%' AND state='".$emp_state."' and (pdesig1='GUD' OR pdesig1='DR' OR pdesig1='GN'  ) ORDER BY r_id DESC";

				$sql3 = mysqli_query($writeConnection,"SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RPF-" . $state_code . "%' AND state='" . $emp_state . "' and (pdesig1='GUD' OR pdesig1='DR' OR pdesig1='GN'  ) ORDER BY r_id DESC");
				//raghu 27-11-2019 end

				$n3 = mysqli_num_rows($sql3);
				while ($res_s = mysqli_fetch_assoc($sql3)) {
					$empLastId[] = substr($res_s['emp_id'], 7, 4);
				}
				$lastId = max($empLastId);

				//$cu_ce_id = explode('-', $res_s['emp_id']);
				//print_r($cu_ce_id);
				//$cu_ce_id1 = count($cu_ce_id); 

				//$n3 = $cu_ce_id[2]+1;

				$n3 = $lastId + 1;
				$digot1 = abs(strlen($n3) - 4);
				if (strlen($n3) <= 3) {
					$s = sprintf("%0" . $digot1 . "d", '0') . $n3;
				} else {
					$s = 	$n3;
				}
				$emp_id = "RPF-" . $state_code . "-" . $s;

				//exit;	
			} else {
				//echo $region_name; die;
				//echo "SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RPF-CHEN%' AND region='RADIANT H.O' and status='Y' and (pdesig1='GUD' OR pdesig1='DR' OR pdesig1='GN'  ) ORDER BY emp_id DESC"; 
				// previously order by emp_id changed into r_id

				$sql3 = mysqli_query($writeConnection,"SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RPF-CHEN%' AND region='RADIANT H.O' and status='Y' and (pdesig1='GUD' OR pdesig1='DR' OR pdesig1='GN'  ) ORDER BY r_id DESC");
				$n3 = mysqli_num_rows($sql3);
				while ($res_s = mysqli_fetch_assoc($sql3)) {
					$empLastId[] = substr($res_s['emp_id'], 9, 4);
				}
				$lastId = max($empLastId);



				/* $cu_ce_id = explode('-', $res_s['emp_id']);
				print_r($cu_ce_id);
				$cu_ce_id1 = count($cu_ce_id); 
				
				$n3 = $cu_ce_id[2]+1; */

				$n3 = $lastId + 1;
				$digot1 = abs(strlen($n3) - 4);
				if (strlen($n3) <= 3) {
					$s = sprintf("%0" . $digot1 . "d", '0') . $n3;
				} else {
					$s = 	$n3;
				}
				$emp_id = "RPF-CHEN-" . $s;
				// die();
			}
		} elseif ($desig == 'CE') {
			$sql2 = mysqli_query($writeConnection,"SELECT state_code FROM state_code WHERE state_name='" . $emp_state . "' AND status='Y'");
			$res_ste = mysqli_fetch_object($sql2);
			$state_code = $res_ste->state_code;

			//$sql3 = mysqli_query($writeConnection,"SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RAD-CE-".$state_code."%' AND state='".$emp_state."' and status='Y' and pdesig1='CE' and emp_id NOT IN ('RAD-GJ-150','RAD-GJ-151','RAD-GJ-152','RAD-GJ-153','RAD-GJ-154','RAD-GJ-155','RAD-GJ-156','RAD-GJ-157','RAD-GJ-158 ','RAD-GJ-159','RAD-GJ-158','RAD-AP-533') ORDER BY emp_id DESC");

			//echo "SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RAD-CE-".$state_code."%' AND state='".$emp_state."' and pdesig1='CE' and emp_id NOT IN ('RAD-GJ-150','RAD-GJ-151','RAD-GJ-152','RAD-GJ-153','RAD-GJ-154','RAD-GJ-155','RAD-GJ-156','RAD-GJ-157','RAD-GJ-158 ','RAD-GJ-159','RAD-GJ-158','RAD-AP-533') ORDER BY emp_id DESC";

			//raghu 27-11-2019 start
			$sql3 = mysqli_query($writeConnection,"SELECT emp_id FROM hrms_empdet  WHERE emp_id like 'RAD-CE-" . $state_code . "%' AND state='" . $emp_state . "' and pdesig1='CE' and emp_id NOT IN ('RAD-GJ-150','RAD-GJ-151','RAD-GJ-152','RAD-GJ-153','RAD-GJ-154','RAD-GJ-155','RAD-GJ-156','RAD-GJ-157','RAD-GJ-158 ','RAD-GJ-159','RAD-GJ-158','RAD-AP-533') ORDER BY emp_id DESC");
			//raghu 27-11-2019 end


			$n3 = mysqli_num_rows($sql3);
			$res_s = mysqli_fetch_assoc($sql3);

			$cu_ce_id = explode('-', $res_s['emp_id']);
			print_r($cu_ce_id);
			$cu_ce_id1 = count($cu_ce_id);

			$n3 = $cu_ce_id[3] + 1;

			$digot1 = abs(strlen($n3) - 4);
			if (strlen($n3) <= 3) {
				$s = sprintf("%0" . $digot1 . "d", '0') . $n3;
			} else {
				$s = 	$n3;
			}
			$emp_id = "RAD-CE-" . $state_code . "-" . $s;
		} else {

			//$query = "select emp_id as id from hrms_empdet where pdesig1!='CE' and pdesig1!='GUD' and pdesig1!='DR' and pdesig1!='GN' and emp_id!='' ORDER BY r_id DESC "; 
			//select max(emp_id) as id from hrms_empdet where pdesig1!='CE' and pdesig1!='GUD' and pdesig1!='DR' and pdesig1!='GN' and emp_id!='' and  r_id>10710

			$query = "select max(emp_id) as id from hrms_empdet where pdesig1!='CE' and pdesig1!='GUD' and pdesig1!='DR' and pdesig1!='GN' and emp_id!='' and  r_id > 10710 ";

			$exec = mysqli_query($writeConnection,$query);
			$max_id = mysqli_fetch_object($exec);
			if ($max_id->id != NULL) {

				$query2 = "select emp_id from hrms_empdet where emp_id='" . $max_id->id . "'";
				$exec = mysqli_query($writeConnection,$query2);
				$row_btn_code = mysqli_fetch_object($exec);
				$lastid = $row_btn_code->emp_id;
				$a = substr($row_btn_code->emp_id, 3);
				//SELVA DEC 02 2019
				$newId = $a + 1;

				$checkExitsId = "select right(emp_id,4) as empid from hrms_empdet where r_id <= 10710 ";
				$exec1 = mysqli_query($writeConnection,$checkExitsId);
				$empids = array();

				while ($resId = mysqli_fetch_object($exec1)) {
					$empids[] = $resId->empid;
				}

				// function findExistsId($p1, $p2)
				// {
				// 	if (!in_array($p1, $p2)) {
				// 		return $p1;
				// 	} else {
				// 		$new_id = $p1 + 1;
				// 		return findExistsId($new_id, $p2);
				// 	}
				// }
				// $newEmpId = findExistsId($newId, $empids);

				function findExistsId($p1, $p2)
				{
					while (in_array($p1, $p2)) {
						$p1++; 
					}
					return $p1;
				}

                 $newEmpId = findExistsId($newId, $empids);


				//echo $eee;
				//exit;
				//$array = explode('_',$row_btn_code->shop_id);
				//$array[1] = str_pad($array[1]+1, 4, '0', STR_PAD_LEFT);

				//$b=substr($row_btn_code->emp_id,0,3);
				//echo $a+1; die;
				//echo $add=$a+00001;
				//echo  $res=str_pad($a+1, 3, '0', STR_PAD_LEFT);

				$emp_id = "RAD" . $newEmpId;
				//SELVA DEC 02 2019
			} else {
				$emp_id = 'RAD001';
			}
		}
	}
	$satus_array = array("Y" => '2_1_1', "H" => "2_1_2", "R" => "2_1_3");
	if ($emp_id != '' && $emp_id != null) {
		$sql = "update hrms_empdet  set emp_id='" . $emp_id . "',approved_by='" . $appr_by . "',doj='" . $appr_date . "',approved_date='" . $appr_date . "',remarks='" . $remarks . "',status='" . $app_status . "' where r_id='" . $id . "' ";
		$qur = mysqli_query($writeConnection,$sql);
	} else {
		$sql = "update hrms_empdet  set approved_by='" . $appr_by . "',doj='" . $appr_date . "',approved_date='" . $appr_date . "',remarks='" . $remarks . "',status='" . $app_status . "' where r_id='" . $id . "' ";
		$qur = mysqli_query($writeConnection,$sql);
	}


	$desig_array = array('CE', 'DR', 'GN', 'MBC', 'LD', 'SMBC', 'GUD');
	if (!in_array($desig, $desig_array)) {
		$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $desig . "'");
		$deg_row = mysqli_fetch_object($sql_deg);
		$desig1 = $deg_row->desig;

		$ce_sql = "insert into radiant_staff(loc_id,emp_id,emp_name,gender,dob,doj,design,father_name,address,pincode,panno,qualification,marital,email_id,mobile1,mobile2,remarks,entry_by,update_date,status)values('" . $sql_fetch->location_id . "','" . $emp_id . "','" . $emp_name . "','" . $gender . "','" . $emp_dob . "','" . $emp_doj . "','" . $desig1 . "','" . $father_name . "','" . $address . "','" . $pin . "','" . $pan_card_no . "','" . $qulification . "','" . $mstatus . "','" . $email . "','" . $mobile11 . "','" . $mobile2 . "','" . $remarks . "','" . $user . "','" . $appr_date . "','Y')";

		$ce_qur = mysqli_query($writeConnection,$ce_sql);
	} else {

		//echo "select desig,desig_code from desig_master where desic_code='".$desig."'"; die;
		$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $desig . "'");
		$deg_row = mysqli_fetch_object($sql_deg);

		if ($desig == 'CE' && $desig == 'CC') {
			$desig1 = $deg_row->desig_code;
		} else {

			$desig1 = $deg_row->desig;
		}
		if ($emp_id != '' && $emp_id != null) {
			$ce_sql = "insert into radiant_ce(loc_id,ce_id,ce_name,gender,dob,doj,design,father_name,address,pincode,panno,qualification,marital,email_id,mobile1,mobile2,remarks,entry_by,update_date,wstatus,status)values('" . $sql_fetch->location_id . "','" . $emp_id . "','" . $emp_name . "','" . $gender . "','" . $emp_dob . "','" . $emp_doj . "','" . $desig1 . "','" . $father_name . "','" . $address . "','" . $pin . "','" . $pan_card_no . "','" . $qulification . "','" . $mstatus . "','" . $email . "','" . $mobile11 . "','" . $mobile2 . "','" . $remarks . "','" . $user . "','" . $appr_date . "','" . $wstatus . "','Y')";
			$ce_qur = mysqli_query($writeConnection,$ce_sql);
		}
	}
	if ($qur) {
		
		header('Location:../?pid=' . $pid . '&nav=' . $satus_array[$app_status] . '&id=' . $id . '&emp_id=' . $emp_id);
	} else {
		
		//echo mysqli_error();
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
} elseif ($pid == "empdata_merge") {
	$emp_id = $_POST['emp_id'];
	$emp_name = $_POST['cname'];
	$dob = date('Y-m-d', strtotime($_POST['dob']));
	$doj = date('Y-m-d', strtotime($_POST['doj']));
	$pancard = $_POST['pancard'];
	$pin = $_POST['pin'];
	$address = $_POST['address'];
	$father_name = $_POST['father_name'];
	$mobile1 = $_POST['mobile1'];
	$mobile2 = $_POST['mobile2'];
	$email = $_POST['email'];
	$statu = $_POST['status'];
	$location = $_POST['location'];
	$desig = $_POST['desig'];
	$gender = $_POST['gender'];
	$wstatus = $_POST['wstatus'];
	$appr_date = date("Y-m-d");
	$sql_location = mysqli_query($writeConnection,"select rl.location_id from location_master lm join radiant_location rl on rl.location_id=lm.loc_id where lm.location='" . $location . "' and lm.status='Y' and rl.status='Y'");
	$sql_fetch = mysqli_fetch_object($sql_location);
	$desig_array = array('CE', 'DR', 'GN', 'MBC', 'LD', 'SMBC', 'GUD', 'CVC', 'CVCE');
	if (!in_array($desig, $desig_array)) {
		$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $desig . "'");
		$deg_row = mysqli_fetch_object($sql_deg);
		$desig1 = $deg_row->desig;

		$ce_sql = "insert into radiant_staff(loc_id,emp_id,emp_name,gender,dob,doj,design,father_name,address,pincode,panno,qualification,marital,email_id,mobile1,mobile2,remarks,entry_by,update_date,status)values('" . $sql_fetch->location_id . "','" . $emp_id . "','" . $emp_name . "','" . $gender . "','" . $dob . "','" . $doj . "','" . $desig1 . "','" . $father_name . "','" . $address . "','" . $pin . "','" . $pancard . "','" . $qulification . "','" . $mstatus . "','" . $email . "','" . $mobile1 . "','" . $mobile2 . "','" . $remarks . "','" . $user . "','" . $appr_date . "','Y')";

		$ce_qur = mysqli_query($writeConnection,$ce_sql);
	} else {

		//echo "select desig,desig_code from desig_master where desic_code='".$desig."'"; die;
		$sql_deg = mysqli_query($writeConnection,"select desig,desig_code from desig_master where desig_code='" . $desig . "'");
		$deg_row = mysqli_fetch_object($sql_deg);

		if ($desig == 'CE' || $desig == 'CVCE') {
			$desig1 = $deg_row->desig_code;
		} else {

			$desig1 = $deg_row->desig;
		}
		$ce_sql = "insert into radiant_ce(loc_id,ce_id,ce_name,gender,dob,doj,design,father_name,address,pincode,panno,qualification,marital,email_id,mobile1,mobile2,remarks,entry_by,update_date,wstatus,status)values('" . $sql_fetch->location_id . "','" . $emp_id . "','" . $emp_name . "','" . $gender . "','" . $dob . "','" . $doj . "','" . $desig1 . "','" . $father_name . "','" . $address . "','" . $pin . "','" . $pancard . "','" . $qulification . "','" . $mstatus . "','" . $email . "','" . $mobile1 . "','" . $mobile2 . "','" . $remarks . "','" . $user . "','" . $appr_date . "','" . $wstatus . "','Y')";
		$ce_qur = mysqli_query($writeConnection,$ce_sql);
	}
} else if ($pid == "view_att") {
	$sql = "Update hrms_attendance set status='N', deleted_by='" . $deleted_by . "', deleted_date_time='" . $deleted_date_time . "' where id in (" . $_REQUEST['id'] . ")";
	$qr = mysqli_query($writeConnection,$sql);
	if ($qr) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2');
	}
} else if ($pid == "view_att_rpf") {
	$sql = "Update hrms_attendance_rpf set status='N', deleted_by='" . $deleted_by . "', deleted_date_time='" . $deleted_date_time . "' where id in (" . $_REQUEST['id'] . ")";
	$qr = mysqli_query($writeConnection,$sql);
	if ($qr) {
		
		header('Location:../?pid=view_att&nav=2_1');
	} else {
		
		header('Location:../?pid=view_att&nav=2_2');
	}
} else if ($pid == "view_att_ce") {
	$branch = $_REQUEST['bb'];
	if ($branch != 'Kolkata') {
		$sql = "Update hrms_ce_attn_pay set status='N', deleted_by='" . $deleted_by . "', deleted_date_time='" . $deleted_date_time . "' where id in (" . $_REQUEST['id'] . ")";
	} else {
		$sql = "Update hrms_ce_attn_pay_kolkata set status='N', deleted_by='" . $deleted_by . "', deleted_date_time='" . $deleted_date_time . "' where id in (" . $_REQUEST['id'] . ")";
	}
	$qr = mysqli_query($writeConnection,$sql);
	if ($qr) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2');
	}
} else if ($pid == "emp_resign") {
	$r_id = $_POST['id'];
	$releave_date = date("Y-m-d", strtotime($_POST['releave_date']));
	$appr_date = date("Y-m-d");
	$releave_time = $_POST['releave_time'];
	$releave_by = $_POST['releave_by'];
	$reason = $_POST['reason'];
	$form_completed = $_POST['form_completed'];
	$feedback = $_POST['feedback'];
	$currt_date = date('Y-m-d');
	$files = $_POST['upload_doc'];
	$file_name = ($_FILES["upload_doc"]["name"]);

	$max_file_size = 300000 * 1024; #300mb
	if (!$_FILES['resig_lett']['error'] && $_FILES['resig_lett']['size'] < $max_file_size) {

		//resig_letter
		$doc_type7 = "26";
		$filename7 = $_FILES['resig_lett']['name'];
		$base7 = basename($filename7);
		$exts7 = explode(".", $base7);
		$n7 = count($exts7);
		$ext7 = $exts7[$n7];
		$file7 = $r_id . "-" . $doc_type7 . "-1";
		$target7 = $file7 . "." . $exts7[1];

		if (!empty($_FILES['resig_lett']["name"])) {
			move_uploaded_file($_FILES["resig_lett"]["tmp_name"], "emp_docs/" . $target7);
			$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $r_id . "','" . $doc_type7 . "','','" . $target7 . "','" . $user_name . "','" . $currt_date . "','Y')";
			$sql = mysqli_query($writeConnection,$sql1);
		}
	}
	if (!$_FILES['no_due']['error'] && $_FILES['no_due']['size'] < $max_file_size) {

		//no due
		$doc_type8 = "27";
		$filename8 = $_FILES['no_due']['name'];
		$base8 = basename($filename8);
		$exts8 = explode(".", $base8);
		$n8 = count($exts8);
		$ext8 = $exts8[$n8];
		$file8 = $r_id . "-" . $doc_type8 . "-1";
		$target8 = $file8 . "." . $exts8[1];

		if (!empty($_FILES['no_due']["name"])) {
			move_uploaded_file($_FILES["no_due"]["tmp_name"], "emp_docs/" . $target8);
			$sql1 = "INSERT INTO hrms_empdoc (r_id,doc_type,doc_remarks,doc_path,upload_by,upload_date,status) VALUES ('" . $r_id . "','" . $doc_type8 . "','','" . $target8 . "','" . $user_name . "','" . $currt_date . "','Y')";
			$sql = mysqli_query($writeConnection,$sql1);
		}
	}

	//$target_dir = "hrms_Resignation/";

	//$target_file = $target_dir . basename($_FILES["upload_doc"]["name"]);

	/*if(move_uploaded_file($_FILES["upload_doc"]["tmp_name"],$target_file))
	{

	echo "uploaded";

	}
	else {
	echo "error";

	}*/

	$sql = "insert into hrms_empresign(r_id,releave_date,releave_time,releave_by,reason,form_completed,feedback,files,update_by,update_date,status)values(" . $r_id . ",'" . $releave_date . "','" . $releave_time . "','" . $releave_by . "','" . $reason . "','" . $form_completed . "','" . $feedback . "','','" . $user_name . "','" . $appr_date . "','Y')";


	// new
	$sql_ce_detail = mysqli_query($writeConnection,"SELECT emp_id,cname FROM hrms_empdet WHERE r_id='$r_id'");
	$res_ce_detail = mysqli_fetch_assoc($sql_ce_detail);
	$upd_ce_id = $res_ce_detail['emp_id'];
	$name = $res_ce_detail['cname'];


	$sql_ce_map = mysqli_query($writeConnection,"SELECT * FROM shop_cemap a inner join shop_details b on a.shop_id=b.shop_id  WHERE a.pri_ce='" . $upd_ce_id . "' AND a.status='Y' and b.status='Y' group by a.shop_id");
	$row_ce_map = mysqli_num_rows($sql_ce_map);
	if ($row_ce_map > 0) {
		
		header('Location:../?pid=' . $pid . '&nav=6&id=' . $id . '&url=1&ce_name=' . $name . '&total_points=' . $row_ce_map);
		$nn = 1;
	} else {

		$sql_ce_cih = mysqli_fetch_array(mysqli_query($writeConnection,"SELECT dep_date,b_amount,pb_amount,cb_amount FROM cih_current WHERE STATUS = 'Y' AND dep_date<='" . $currt_date . "' and `ce_id`='" . $upd_ce_id . "' order by dep_date desc limit 0,1"));
		$total_cih = $sql_ce_cih['b_amount'] + $sql_ce_cih['pb_amount'] + $sql_ce_cih['cb_amount'];
		if ($total_cih != 0) {
			
			header('Location:../?pid=' . $pid . '&nav=7&id=' . $id . '&url=1&ce_name=' . $name . '&total_cih=' . $total_cih);
		} else {
			$sql1 = "update hrms_empdet set status='N',e_date='" . $releave_date . "' where r_id='" . $r_id . "'";
			$rac_ce = mysqli_query($writeConnection,"update radiant_ce set status='N',update_date='" . $currt_date . "' where ce_id='" . $upd_ce_id . "'");
			$rac_staff = mysqli_query($writeConnection,"update radiant_staff set status='N',update_date='" . $currt_date . "' where emp_id='" . $upd_ce_id . "'");

			$qr1 = mysqli_query($writeConnection,$sql1);
			$qr = mysqli_query($writeConnection,$sql);
			if ($qr) {
				
				header('Location:../?pid=' . $pid . '&nav=2_1_1');
			} else {
				
				header('Location:../?pid=' . $pid . '&nav=2_2_1');
			}
		}
	}
} elseif ($pid == 'hrms_ce_pickup') {

	//echo "vimal"; die;
	//print_r($_POST); die;
	//echo $file_name = $_FILES["attendance_file"]["name"]; die;
	$branch = $_POST['branch_id'];
	$_POST['dos'];
	$date_arr = explode("-", date('m-Y', strtotime($_POST['date_of_sal'])));
	// '<PRE>'; print_r($date_arr); 

	// holiday 
	//$select_holiday_amt = mysqli_query($writeConnection,"select holiday_amt from atm_branch_holiday_amt where status='Y' and branch_id = '".$branch."'");
	/*	if(mysqli_num_rows($select_holiday_amt)>0){
			$row_amt = mysqli_fetch_object($select_holiday_amt);
			$holiday_amt = $row_amt->holiday_amt;
		}else{
			$holiday_amt = 0;
		}*/

	$current_month_day = cal_days_in_month(CAL_GREGORIAN, $date_arr[0], $date_arr[1]);
	$dos = $date_arr[1] . "-" . $date_arr[0] . "-" . $current_month_day;
	$attendance_month_year = date('M-Y', strtotime($_POST['date_of_sal']));

	//echo $dos; die;
	//$dos = date('Y-m-d',strtotime($date_of_salary)); 
	//	echo $attendance_month_year = date('M-Y',strtotime($_POST['dos'])); die;
	$radiant_id = $_POST['radiant_id'];
	$radiant_id_selected = array();
	$radiant_id_selected = array_filter(explode("%", $radiant_id));
	//print_r($radiant_id_selected); die;
	$file_name = $_SESSION['attendance_request'];
	move_uploaded_file($_FILES["attendance_file"]["tmp_name"], "HRMS_Files/Upload/" . $file_name);

	$inputFileName = 'HRMS_Files/Upload/' . $file_name;
	try {
		$spreadsheet = IOFactory::load($inputFileName);
	} catch (Exception $e) {
		die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
	}
	$xcelActiveData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	$xcelActiveCount = count($xcelActiveData);
	if (count($radiant_id_selected) > 0) {
		$sno = 0;
		for ($i = 4; $i <= $xcelActiveCount; $i++) {
			if (trim($xcelActiveData[$i]["M"]) != '') {
				if (in_array(trim($xcelActiveData[$i]["M"]), $radiant_id_selected)) {
					//echo "select sal.*,emp.emp_id,emp.cname,emp.doj,emp.pdesig,emp.pdesig1 from hrms_empdet emp join hrms_salary_master sal on sal.r_id=emp.r_id where sal.status='Y' and emp.status='Y'  and trim(emp.emp_id) = '".trim($xcelActiveData[$i]["I"])."'"; die;
					$select_sal = mysqli_query($writeConnection,"select sal.*,emp.emp_id,emp.cname,emp.doj,emp.pdesig,emp.pdesig1 from hrms_empdet emp join hrms_salary_master sal on sal.r_id=emp.r_id where sal.status='Y' and emp.status='Y'  and trim(emp.emp_id) = '" . trim($xcelActiveData[$i]["M"]) . "'");
					if (mysqli_num_rows($select_sal) > 0) {
						$row_icici = mysqli_fetch_object($select_sal);
						$radiant_id = $row_icici->emp_id;
						$emp_name = $row_icici->cname;
						$rad_doj = $row_icici->doj;
						$designation = $row_icici->pdesig1;
						$department = $row_icici->pdesig;
						$gross_salary_fixed = $row_icici->gross_sal;
						// rcms part
						$basic_fixed = $row_icici->basic_pay;
						$hra_fixed = $row_icici->hra;
						$conveyance_fixed = $row_icici->conveyance;
						$lta = $row_icici->lta;
						$other_all_fixed = $row_icici->oth_all;
						$total_present_days = trim($xcelActiveData[$i]["N"]);
						$div = ($xcelActiveData[$i]["M"] * $total_present_days);
						$basic_pay = round(($basic_fixed / (trim($xcelActiveData[$i]["M"]))) * $total_present_days);




						if (trim($xcelActiveData[$i]["BA"]) > 0 && trim($xcelActiveData[$i]["BA"]) != '') {
							$per_day1_sal = round($gross_salary_fixed / 26, 0);
							$per_hr_sal = round($per_day1_sal / 9, 0);
							$pre_ot_amt = round(trim($xcelActiveData[$i]["BA"]) * $per_hr_sal, 0);
						} else {
							$pre_ot_amt = "0";
						}
						$pre_salary_fixed = $row_icici->emp_grass_sal;
						$pre_holiday_amt = trim($xcelActiveData[$i]["BF"]) * $holiday_amt;
						$variable_allowance = $pre_ot_amt + $pre_holiday_amt + trim($xcelActiveData[$i]["BB"]);
						$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $el_pay + $bonus_pay + $other_all_pay + $variable_allowance, 0);
						//echo "Gross Pay".$pre_gross_pay; die;
						//Deductions
						$epf = round(($basic_pay * (12 / 100)), 0);
						$esi = round(($pre_gross_pay * (12 / 100)), 0);
						$p_tax = 0;
						$tds = 0;
						$salary_advance = trim($xcelActiveData[$i]["BA"]);
						$other_deductions = trim($xcelActiveData[$i]["BC"]);
						$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions, 0);
						$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);
					}

					if ($branch != 'Kolkata') {


						$sql_insert = mysqli_query($writeConnection,"insert into hrms_ce_attn_pay (region,date_of_salary,month_of_salary,ce_id,ce_name,location,loc_pin,beat_points,no_pickup_point,no_cd_point,tot_point,aggredate_distance,service_charge,m_days,work_days,leave_days,servc_due,tele_allc,trans_allc,sunday_allc,cd_allc,arrear,tot_amt,fax_charge,courier_charge,bag_charge,fuel_charge,taxi_charge,parking_charge,misc_charge,gross_pay,tds,medical,penal,loan,net_pay,mode_pay,prev_point,prev_request_points,prev_cd_points,prev_tot,prev_net_amt_paid,varis_point,vais_amount,remark,last_sal,last_gross_sal,created_by,created_date,status,ce_acno,ce_bank,ce_branch,ce_ifsc,no_cit_points, addition_beat_point, tot_sunday_worked, addition_point_allc, cit_allc, request_point_allc, misc_charge_remark, other_deduction, prev_addition_beat_point, prev_cit_point, gross_pay_amt, diff_gross_amt) values ('" . $branch . "','" . $dos . "','" . $attendance_month_year . "','" . trim($xcelActiveData[$i]["M"]) . "','" . trim($xcelActiveData[$i]["K"]) . "','" . trim($xcelActiveData[$i]["B"]) . "','" . trim($xcelActiveData[$i]["C"]) . "','" . trim($xcelActiveData[$i]["D"]) . "','" . trim($xcelActiveData[$i]["F"]) . "','" . trim($xcelActiveData[$i]["G"]) . "','" . trim($xcelActiveData[$i]["I"]) . "','" . trim($xcelActiveData[$i]["J"]) . "','" . trim($xcelActiveData[$i]["O"]) . "','" . trim($xcelActiveData[$i]["P"]) . "','" . trim($xcelActiveData[$i]["Q"]) . "','" . trim($xcelActiveData[$i]["S"]) . "','" . trim($xcelActiveData[$i]["T"]) . "','" . trim($xcelActiveData[$i]["U"]) . "','" . trim($xcelActiveData[$i]["V"]) . "','" . trim($xcelActiveData[$i]["W"]) . "','" . trim($xcelActiveData[$i]["Y"]) . "','" . trim($xcelActiveData[$i]["AB"]) . "','" . trim($xcelActiveData[$i]["AC"]) . "','','" . trim($xcelActiveData[$i]["AD"]) . "','','" . trim($xcelActiveData[$i]["AE"]) . "','" . trim($xcelActiveData[$i]["AF"]) . "','" . trim($xcelActiveData[$i]["AG"]) . "','" . trim($xcelActiveData[$i]["AH"]) . "','" . trim($xcelActiveData[$i]["AJ"]) . "','" . trim($xcelActiveData[$i]["AK"]) . "','','" . trim($xcelActiveData[$i]["AL"]) . "','" . trim($xcelActiveData[$i]["AM"]) . "','" . trim($xcelActiveData[$i]["AO"]) . "','" . trim($xcelActiveData[$i]["AP"]) . "','" . trim($xcelActiveData[$i]["AU"]) . "','" . trim($xcelActiveData[$i]["AW"]) . "','" . trim($xcelActiveData[$i]["AX"]) . "','" . trim($xcelActiveData[$i]["AZ"]) . "','" . trim($xcelActiveData[$i]["BB"]) . "','" . trim($xcelActiveData[$i]["BC"]) . "','" . trim($xcelActiveData[$i]["BE"]) . "','" . trim($xcelActiveData[$i]["BF"]) . "','" . trim($xcelActiveData[$i]["BG"]) . "','','$user',now(),'Y','" . substr($xcelActiveData[$i]["AS"], 1) . "','" . trim($xcelActiveData[$i]["AQ"]) . "','" . trim($xcelActiveData[$i]["AR"]) . "','" . trim($xcelActiveData[$i]["AT"]) . "','" . trim($xcelActiveData[$i]["H"]) . "','" . trim($xcelActiveData[$i]["E"]) . "','" . trim($xcelActiveData[$i]["R"]) . "','" . trim($xcelActiveData[$i]["X"]) . "','" . trim($xcelActiveData[$i]["Z"]) . "','" . trim($xcelActiveData[$i]["AA"]) . "','" . trim($xcelActiveData[$i]["AI"]) . "','" . trim($xcelActiveData[$i]["AN"]) . "','" . trim($xcelActiveData[$i]["AV"]) . "','" . trim($xcelActiveData[$i]["AY"]) . "','" . trim($xcelActiveData[$i]["BA"]) . "','" . trim($xcelActiveData[$i]["BD"]) . "')");
					} else {


						$sql_insert = mysqli_query($writeConnection,"insert into hrms_ce_attn_pay_kolkata (region,date_of_salary,month_of_salary,ce_id,ce_name,doj,dob,fathers_name,mobile_no,epf_acc_no,esi_acc_no,uan_no,pan_no,department,designation,loaction,bank,branch,acc_no,sal_acc_no,ifsc_code,basic_pay,hra,conveyance,bonus,medical,edn_other_allce,toatl_salary,total_no_days,days_worked,basic_pay_due,hra_due,conveyance_due,bonus_due,medical_due,edn_other_allce_due,variable_allce,gross_pay,esi_salary,epf,esi,p_tax,tds,salary_advance,insurance_deduction,other_deduction,gross_deductions,et_amt_payable,doj_epf_acc,date_last_incre,increment_amt,remarks_any,fuel,parking,bif_conveyance,fax_scan,sunday_holiday,courier,arrears,cd_charges,taxi_fare,total_allce,previous_month_total_allce,difference,remarks,employer_contri_epf,employer_contri_esi,ctc,created_by,created_date,status,ce_acno,ce_bank,ce_branch,ce_ifsc) values ('" . $branch . "','" . $dos . "','" . $attendance_month_year . "','" . trim($xcelActiveData[$i]["B"]) . "','" . trim($xcelActiveData[$i]["C"]) . "','" . trim($xcelActiveData[$i]["D"]) . "','" . trim($xcelActiveData[$i]["E"]) . "','" . trim($xcelActiveData[$i]["F"]) . "','" . trim($xcelActiveData[$i]["G"]) . "','" . trim($xcelActiveData[$i]["H"]) . "','" . trim($xcelActiveData[$i]["I"]) . "','" . trim($xcelActiveData[$i]["J"]) . "','" . trim($xcelActiveData[$i]["K"]) . "','" . trim($xcelActiveData[$i]["L"]) . "','" . trim($xcelActiveData[$i]["M"]) . "','" . trim($xcelActiveData[$i]["N"]) . "','" . trim($xcelActiveData[$i]["O"]) . "','" . trim($xcelActiveData[$i]["P"]) . "'," . trim($xcelActiveData[$i]["Q"]) . "'," . trim($xcelActiveData[$i]["Q"]) . "','" . trim($xcelActiveData[$i]["R"]) . "','" . trim($xcelActiveData[$i]["S"]) . "','" . trim($xcelActiveData[$i]["T"]) . "','" . trim($xcelActiveData[$i]["U"]) . "','" . trim($xcelActiveData[$i]["V"]) . "','" . trim($xcelActiveData[$i]["W"]) . "','" . trim($xcelActiveData[$i]["X"]) . "','" . trim($xcelActiveData[$i]["Y"]) . "','" . trim($xcelActiveData[$i]["Z"]) . "','" . trim($xcelActiveData[$i]["AA"]) . "','" . trim($xcelActiveData[$i]["AB"]) . "','" . trim($xcelActiveData[$i]["AC"]) . "','" . trim($xcelActiveData[$i]["AD"]) . "','" . trim($xcelActiveData[$i]["AE"]) . "','" . trim($xcelActiveData[$i]["AF"]) . "','" . trim($xcelActiveData[$i]["AG"]) . "','" . trim($xcelActiveData[$i]["AH"]) . "','" . trim($xcelActiveData[$i]["AI"]) . "','" . trim($xcelActiveData[$i]["AJ"]) . "','" . trim($xcelActiveData[$i]["AK"]) . "','" . trim($xcelActiveData[$i]["AL"]) . "','" . trim($xcelActiveData[$i]["AM"]) . "','" . trim($xcelActiveData[$i]["AN"]) . "','" . trim($xcelActiveData[$i]["AO"]) . "','" . trim($xcelActiveData[$i]["AP"]) . "','" . trim($xcelActiveData[$i]["AQ"]) . "','" . trim($xcelActiveData[$i]["AR"]) . "','" . trim($xcelActiveData[$i]["AS"]) . "','" . trim($xcelActiveData[$i]["AT"]) . "','" . trim($xcelActiveData[$i]["AU"]) . "','" . trim($xcelActiveData[$i]["AV"]) . "','" . trim($xcelActiveData[$i]["AW"]) . "','" . trim($xcelActiveData[$i]["AX"]) . "','" . trim($xcelActiveData[$i]["AY"]) . "','" . trim($xcelActiveData[$i]["AZ"]) . "','" . trim($xcelActiveData[$i]["BA"]) . "','" . trim($xcelActiveData[$i]["BB"]) . "','" . trim($xcelActiveData[$i]["BC"]) . "','" . trim($xcelActiveData[$i]["BD"]) . "','" . trim($xcelActiveData[$i]["BE"]) . "','" . trim($xcelActiveData[$i]["BF"]) . "','" . trim($xcelActiveData[$i]["BG"]) . "','" . trim($xcelActiveData[$i]["BH"]) . "','" . trim($xcelActiveData[$i]["BI"]) . "','" . trim($xcelActiveData[$i]["BJ"]) . "','" . trim($xcelActiveData[$i]["BK"]) . "','" . trim($xcelActiveData[$i]["BL"]) . "','" . trim($xcelActiveData[$i]["BM"]) . "','$user',now(),'Y','" . substr($xcelActiveData[$i]["AN"], 1) . "','" . trim($xcelActiveData[$i]["AL"]) . "','" . trim($xcelActiveData[$i]["AM"]) . "','" . trim($xcelActiveData[$i]["AO"]) . "')");
					}
					//	echo trim($xcelActiveData[$i]["AH"]); die;


					$sno++;
					$msg = "Attendance Uploaded Successfully";
				}
			}
		}
	}
	if ($sql_insert) {
		echo json_encode(array("result_response" => "success", "rows" => $sno, "msg" => $msg));
	} else {
		echo json_encode(array("result_response" => "failure", "msg" => "Attendance Failed to Insert"));
	}
} elseif ($pid == 'hrms_cvc_loader') {

	//echo "vimal"; die;
	//print_r($_POST); die;
	//echo $file_name = $_FILES["attendance_file"]["name"]; die;
	$branch = $_POST['branch_id'];
	$_POST['dos'];
	$date_arr = explode("-", date('m-Y', strtotime($_POST['date_of_sal'])));
	// '<PRE>'; print_r($date_arr); 

	// holiday 
	//$select_holiday_amt = mysqli_query($writeConnection,"select holiday_amt from atm_branch_holiday_amt where status='Y' and branch_id = '".$branch."'");
	/*	if(mysqli_num_rows($select_holiday_amt)>0){
			$row_amt = mysqli_fetch_object($select_holiday_amt);
			$holiday_amt = $row_amt->holiday_amt;
		}else{
			$holiday_amt = 0;
		}*/

	$current_month_day = cal_days_in_month(CAL_GREGORIAN, $date_arr[0], $date_arr[1]);
	$dos = $date_arr[1] . "-" . $date_arr[0] . "-" . $current_month_day;
	$attendance_month_year = date('M-Y', strtotime($_POST['date_of_sal']));

	//echo $dos; die;
	//$dos = date('Y-m-d',strtotime($date_of_salary)); 
	//	echo $attendance_month_year = date('M-Y',strtotime($_POST['dos'])); die;
	$radiant_id = $_POST['radiant_id'];
	$radiant_id_selected = array();
	$radiant_id_selected = array_filter(explode("%", $radiant_id));
	//print_r($radiant_id_selected); die;
	$file_name = $_SESSION['attendance_request'];
	move_uploaded_file($_FILES["attendance_file"]["tmp_name"], "HRMS_Files/Upload/" . $file_name);

	$inputFileName = 'HRMS_Files/Upload/' . $file_name;
	try {
		$spreadsheet = IOFactory::load($inputFileName);
	} catch (Exception $e) {
		die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
	}
	$xcelActiveData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	$xcelActiveCount = count($xcelActiveData);
	if (count($radiant_id_selected) > 0) {
		$sno = 0;
		for ($i = 4; $i <= $xcelActiveCount; $i++) {
			if (trim($xcelActiveData[$i]["K"]) != '') {
				if (in_array(trim($xcelActiveData[$i]["K"]), $radiant_id_selected)) {
					//echo "select sal.*,emp.emp_id,emp.cname,emp.doj,emp.pdesig,emp.pdesig1 from hrms_empdet emp join hrms_salary_master sal on sal.r_id=emp.r_id where sal.status='Y' and emp.status='Y'  and trim(emp.emp_id) = '".trim($xcelActiveData[$i]["I"])."'"; die;
					$select_sal = mysqli_query($writeConnection,"select sal.*,emp.emp_id,emp.cname,emp.doj,emp.pdesig,emp.pdesig1 from hrms_empdet emp join hrms_salary_master sal on sal.r_id=emp.r_id where sal.status='Y' and emp.status='Y'  and trim(emp.emp_id) = '" . trim($xcelActiveData[$i]["K"]) . "'");
					if (mysqli_num_rows($select_sal) > 0) {
						$row_icici = mysqli_fetch_object($select_sal);
						$radiant_id = $row_icici->emp_id;
						$emp_name = $row_icici->cname;
						$rad_doj = $row_icici->doj;
						$designation = $row_icici->pdesig1;
						$department = $row_icici->pdesig;
						$gross_salary_fixed = $row_icici->gross_sal;
						// rcms part
						$basic_fixed = $row_icici->basic_pay;
						$hra_fixed = $row_icici->hra;
						$conveyance_fixed = $row_icici->conveyance;
						$lta = $row_icici->lta;
						$other_all_fixed = $row_icici->oth_all;
						$total_present_days = trim($xcelActiveData[$i]["M"]);
						$div = ($xcelActiveData[$i]["L"] * $total_present_days);
						$basic_pay = round(($basic_fixed / (trim($xcelActiveData[$i]["L"]))) * $total_present_days);



						if (trim($xcelActiveData[$i]["AZ"]) > 0 && trim($xcelActiveData[$i]["AZ"]) != '') {
							$per_day1_sal = round($gross_salary_fixed / 26, 0);
							$per_hr_sal = round($per_day1_sal / 9, 0);
							$pre_ot_amt = round(trim($xcelActiveData[$i]["AZ"]) * $per_hr_sal, 0);
						} else {
							$pre_ot_amt = "0";
						}
						$pre_salary_fixed = $row_icici->emp_grass_sal;
						$pre_holiday_amt = trim($xcelActiveData[$i]["AX"]) * $holiday_amt;
						$variable_allowance = $pre_ot_amt + $pre_holiday_amt + trim($xcelActiveData[$i]["BB"]);
						$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $el_pay + $bonus_pay + $other_all_pay + $variable_allowance, 0);
						//echo "Gross Pay".$pre_gross_pay; die;
						//Deductions
						$epf = round(($basic_pay * (12 / 100)), 0);
						$esi = round(($pre_gross_pay * (12 / 100)), 0);
						$p_tax = 0;
						$tds = 0;
						$salary_advance = trim($xcelActiveData[$i]["BA"]);
						$other_deductions = trim($xcelActiveData[$i]["BC"]);
						$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions, 0);
						$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);
					}


					$sql_insert = mysqli_query($writeConnection,"insert into hrms_ce_attn_pay_kolkata (region,date_of_salary,month_of_salary,ce_id,ce_name,doj,dob,fathers_name,mobile_no,epf_acc_no,esi_acc_no,uan_no,pan_no,department,designation,loaction,bank,branch,acc_no,sal_acc_no,ifsc_code,basic_pay,hra,conveyance,bonus,medical,edn_other_allce,toatl_salary,total_no_days,days_worked,basic_pay_due,hra_due,conveyance_due,bonus_due,medical_due,edn_other_allce_due,variable_allce,gross_pay,esi_salary,epf,esi,p_tax,tds,salary_advance,insurance_deduction,other_deduction,gross_deductions,et_amt_payable,doj_epf_acc,date_last_incre,increment_amt,remarks_any,tele_allce,sunday_holiday,other_allce,ot,balance_increment,total_allce,previous_month_total_allce,difference,remarks,employer_contri_epf,employer_contri_esi,ctc,created_by,created_date,status) values ('Kolkata','" . $dos . "','" . $attendance_month_year . "','" . trim($xcelActiveData[$i]["B"]) . "','" . trim($xcelActiveData[$i]["C"]) . "','" . trim($xcelActiveData[$i]["D"]) . "','" . trim($xcelActiveData[$i]["E"]) . "','" . trim($xcelActiveData[$i]["F"]) . "','" . trim($xcelActiveData[$i]["G"]) . "','" . trim($xcelActiveData[$i]["H"]) . "','" . trim($xcelActiveData[$i]["I"]) . "','" . trim($xcelActiveData[$i]["J"]) . "','" . trim($xcelActiveData[$i]["K"]) . "','" . trim($xcelActiveData[$i]["L"]) . "','" . trim($xcelActiveData[$i]["M"]) . "','" . trim($xcelActiveData[$i]["N"]) . "','" . trim($xcelActiveData[$i]["O"]) . "','" . trim($xcelActiveData[$i]["P"]) . "'," . trim($xcelActiveData[$i]["Q"]) . "'," . trim($xcelActiveData[$i]["Q"]) . "','" . trim($xcelActiveData[$i]["R"]) . "','" . trim($xcelActiveData[$i]["S"]) . "','" . trim($xcelActiveData[$i]["T"]) . "','" . trim($xcelActiveData[$i]["U"]) . "','" . trim($xcelActiveData[$i]["V"]) . "','" . trim($xcelActiveData[$i]["W"]) . "','" . trim($xcelActiveData[$i]["X"]) . "','" . trim($xcelActiveData[$i]["Y"]) . "','" . trim($xcelActiveData[$i]["Z"]) . "','" . trim($xcelActiveData[$i]["AA"]) . "','" . trim($xcelActiveData[$i]["AB"]) . "','" . trim($xcelActiveData[$i]["AC"]) . "','" . trim($xcelActiveData[$i]["AD"]) . "','" . trim($xcelActiveData[$i]["AE"]) . "','" . trim($xcelActiveData[$i]["AF"]) . "','" . trim($xcelActiveData[$i]["AG"]) . "','" . trim($xcelActiveData[$i]["AH"]) . "','" . trim($xcelActiveData[$i]["AI"]) . "','" . trim($xcelActiveData[$i]["AJ"]) . "','" . trim($xcelActiveData[$i]["AK"]) . "','" . trim($xcelActiveData[$i]["AL"]) . "','" . trim($xcelActiveData[$i]["AM"]) . "','" . trim($xcelActiveData[$i]["AN"]) . "','" . trim($xcelActiveData[$i]["AO"]) . "','" . trim($xcelActiveData[$i]["AP"]) . "','" . trim($xcelActiveData[$i]["AQ"]) . "','" . trim($xcelActiveData[$i]["AR"]) . "','" . trim($xcelActiveData[$i]["AS"]) . "','" . trim($xcelActiveData[$i]["AT"]) . "','" . trim($xcelActiveData[$i]["AU"]) . "','" . trim($xcelActiveData[$i]["AV"]) . "','" . trim($xcelActiveData[$i]["AW"]) . "','" . trim($xcelActiveData[$i]["AX"]) . "','" . trim($xcelActiveData[$i]["AY"]) . "','" . trim($xcelActiveData[$i]["AZ"]) . "','" . trim($xcelActiveData[$i]["BA"]) . "','" . trim($xcelActiveData[$i]["BB"]) . "','" . trim($xcelActiveData[$i]["BC"]) . "','" . trim($xcelActiveData[$i]["BD"]) . "','" . trim($xcelActiveData[$i]["BE"]) . "','" . trim($xcelActiveData[$i]["BF"]) . "','" . trim($xcelActiveData[$i]["BG"]) . "','" . trim($xcelActiveData[$i]["BH"]) . "','" . trim($xcelActiveData[$i]["BI"]) . "','$user',now(),'Y')");

					$sno++;
					$msg = "Attendance Uploaded Successfully";
				}
			}
		}
	}
	if ($sql_insert) {
		echo json_encode(array("result_response" => "success", "rows" => $sno, "msg" => $msg));
	} else {
		echo json_encode(array("result_response" => "failure", "msg" => "Attendance Failed to Insert"));
	}
} else if ($pid == "new_attendance") {
	$emp_id = $_POST['emp_id'];
	$doj = date("Y-m-d", strtotime($_POST['doj']));
	$sal_month = date("Y-m-d", strtotime($_POST['sal_month']));
	$entry_date = date("Y-m-d");
	$cname = $_POST['cname'];
	$pdesig1 = $_POST['pdesig1'];
	$pdesig = $_POST['pdesig'];
	$region1 = $_POST['region1'];

	$attendance_month_year = date('M-Y', strtotime($_POST['sal_month']));
	$tot_el = $_POST['tot_el'];
	$tot_cl = $_POST['tot_cl'];
	$tot_sl = $_POST['tot_sl'];
	$tot_ml = $_POST['tot_ml'];
	$hol_days = $_POST['hol_days'];
	$tot_day = $_POST['tot_day'];

	$tele_allc = $_POST['tele_allc'];
	$incentive = $_POST['incentive'];
	$oth_allc = $_POST['oth_allc'];
	$ot_amt = $_POST['ot_amt'];
	$sal_adv = $_POST['sal_adv'];
	$misc_amt = $_POST['misc_amt'];

	$oth_dedu = $_POST['oth_dedu'];
	$remark = $_POST['remark'];


	$sql = mysqli_query($writeConnection,"insert into hrms_attendance(branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department,m_days,holidays,sal_days,present_days,total_el,total_cl,total_sl,total_ml,total_present,tele_allce,incentive,oth_allce,sal_advance,misc_amt,other_ded,pre_ot_fixed,remarks,created_by,created_date,status)values('" . $region1 . "','" . $sal_month . "','" . $emp_id . "','" . $cname . "','" . $attendance_month_year . "','" . $doj . "','" . $pdesig1 . "','" . $pdesig . "','" . $tot_day . "','" . $hol_days . "','" . $tot_day . "','" . $tot_day . "','" . $tot_el . "','" . $tot_cl . "','" . $tot_sl . "','" . $tot_ml . "','" . $tot_day . "','" . $tele_allc . "','" . $incentive . "','" . $oth_allc . "','" . $sal_adv . "','" . $misc_amt . "','" . $oth_dedu . "','" . $ot_amt . "','" . $remark . "','" . $user_name . "','" . $entry_date . "','Y')");


	if ($sql) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
} else if ($pid == "hrms_daily_attendance") {

	//echo "succ"; die;

	//echo '<pre>'; print_r($_POST); die;
	$type = $_POST['type'];
	$date = $_POST['date'];
	$id = $_POST['id'];
	$month_date = date(('M-Y'), strtotime($date));
	$chk_date = date('d-m-Y', strtotime($date));
	$month_curr = date(('M'), strtotime($date));
	$region = $_POST['region'];
	//$m_days=$_POST['m_days'];
	$lop = $_POST['iop'];
	$curr_date = date('Y-m-d');
	//echo array_count_values($id);die;

	foreach ($_POST['m_days'] as $key => $value) {
		foreach ($_POST['id'] as $key2 => $value2) {
		} //die;
		// echo '<pre>'; print_r($value2);  die;
		// echo $key."-".'<br>'; print_r($_POST);  

		if (empty($value2)) {


			$sql = "insert into hrms_attendance(branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj,designation,department,m_days,sal_days,present_days,lop,total_present,mob_edu,cih,sal_advance,other_ded,remarks,status,created_by,created_date,sal_ac_no,sal_ac_bank,sal_ac_ifsc,sal_ac_branch)values('" . $region . "','" . $date . "','" . $key . "','" . $_POST['emp_name'][$key] . "','" . $month_date . "','" . $_POST['dob'][$key] . "','" . $_POST['desig'][$key] . "','" . $_POST['dep'][$key] . "','" . $_POST['m_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['lop'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['mob_edu'][$key] . "','" . $_POST['cih'][$key] . "','" . $_POST['sal_advance'][$key] . "','" . $_POST['other_ded'][$key] . "','" . $_POST['remarks_other_dec'][$key] . "','Y','" . $user_name . "','" . $curr_date . "','" . $_POST['acno'][$key] . "','" . $_POST['bank'][$key] . "','" . $_POST['ifsc'][$key] . "','" . $_POST['branch'][$key] . "')";
		} else {
			if ($type == 'Allowance') {
				//echo "select gross_sal from hrms_empdet where emp_id='".$key."'"; die;

				$basic_sal = mysqli_query($writeConnection,"select gross_sal from hrms_empdet where emp_id='" . $key . "'");
				$basic_sal_row = mysqli_fetch_array($basic_sal);

				$gross_pay = $basic_sal_row['gross_sal'];
				$basic_fixed = ceil(($gross_pay * (50 / 100)));
				$hra_fixed = ceil(($basic_fixed * (50 / 100)));
				$conveyance_fixed = 800;
				$bonus_fixed = round(($gross_pay * (8.33 / 100)), 0);
				if ($gross_pay == 30000) {
					$medical_fixed = 1250;
				} else {
					$medical_fixed = 0;
				}
				$other_all_fixed = $gross_pay - ($basic_fixed + $hra_fixed + $conveyance_fixed  + $bonus_fixed);
				$total_present_days = $_POST['m_days'][$key];
				$month_days = $_POST['tele_allcvi'][$key];
				//echo $_POST['tele_allcvi'][$key]."vimal"; die;

				//current
				$basic_pay = ceil(($basic_fixed / $month_days) * $total_present_days);
				$hra_pay = ceil(($hra_fixed / $month_days) * $total_present_days);
				$conveyance_pay = round(($conveyance_fixed / $month_days) * $total_present_days, 0);
				$bonus_pay = round(($bonus_fixed / $month_days) * $total_present_days, 0);
				$other_all_pay = round(($other_all_fixed / $month_days) * $total_present_days, 0);

				$variable_allowance = round($_POST['tele_allc'][$key] + $_POST['ince'][$key] + $last_row['sun_allc'] + $_POST['ather_allc'][$key] + $_POST['holiday_allow'][$key] + $_POST['ot_amt'][$key]);
				$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $bonus_pay + $other_all_pay + $variable_allowance);
				//deduction

				$epf = round(($basic_pay * (12 / 100)), 0);
				if ($gross_pay <= 15000) {

					//raghu start 30-07-2019
					if (strtotime($chk_date) < strtotime('01-07-2019')) {
						$esi = ceil(($pre_gross_pay * (1.75 / 100)));
					} else {
						$esi = ceil(($pre_gross_pay * (0.75 / 100)));
					}
					//raghu end 30-07-2019
				}
				$p_tax = 0;
				$tds = 0;
				$salary_advance = $_POST['sal_adv'][$key];
				$other_deductions = $_POST['other_dec'][$key];
				$misc_amt = $_POST['penal_dec'][$key];

				$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions + $misc_amt, 0);
				$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);

				//SENTHIL 22/8/22
				$incre_status = $_POST['incre_status'][$key];
				$p_tax_chan = $_POST['p_tax'][$key];
				if ($region == 'Chennai' || $region == 'ROTN' || $region == 'RADIANT H.O') {
					if (($month_curr == 'Mar' || $month_curr == 'Sep') && $incre_status == '0') {
						$p_tax_chan += 2;
					}
				}


				$sql = "update hrms_attendance set tele_allce='" . $_POST['tele_allc'][$key] . "',incentive='" . $_POST['ince'][$key] . "',oth_allce='" . $_POST['ather_allc'][$key] . "',pre_ot_fixed='" . $_POST['ot_amt'][$key] . "',holiday_amt='" . $_POST['holiday_allow'][$key] . "',sunday_allce='" . $_POST['sunday_allcee'][$key] . "',holiday_pick='" . $_POST['holiday_pickk'][$key] . "',eve_pick='" . $_POST['evening'][$key] . "',trav_allce='" . $_POST['trav_allow'][$key] . "',park_char='" . $_POST['park_char'][$key] . "',fuel_char='" . $_POST['fuel_char'][$key] . "',arrears='" . $_POST['arrears'][$key] . "',misc_amt='" . $_POST['penal_dec'][$key] . "',p_tax='" . $p_tax_chan . "',pre_gross_pay='" . $pre_gross_pay . "',pre_net_pay='" . $_POST['net_pay'][$key] . "',incre_status='1' where id='" . $_POST['id'][$key] . "'";
			} else {

				$sql = "update hrms_attendance set branch='" . $region . "',date_of_salary='" . $date . "',emp_id='" . $key . "',emp_name='" . $_POST['emp_name'][$key] . "',attendance_month_year='" . $month_date . "',emp_doj='" . $_POST['dob'][$key] . "',designation='" . $_POST['desig'][$key] . "',department='" . $_POST['dep'][$key] . "',m_days='" . $_POST['m_days'][$key] . "',sal_days='" . $_POST['p_days'][$key] . "',present_days='" . $_POST['p_days'][$key] . "',mob_edu='" . $_POST['mob_edu'][$key] . "',cih='" . $_POST['cih'][$key] . "',sal_advance='" . $_POST['sal_advance'][$key] . "',other_ded='" . $_POST['other_ded'][$key] . "',remarks='" . $_POST['remarks_other_dec'][$key] . "',total_present='" . $_POST['p_days'][$key] . "',status='Y',lop='" . $_POST['lop'][$key] . "',sal_ac_no='" . $_POST['acno'][$key] . "',sal_ac_bank='" . $_POST['bank'][$key] . "',sal_ac_ifsc='" . $_POST['ifsc'][$key] . "',sal_ac_branch='" . $_POST['branch'][$key] . "' where id='" . $_POST['id'][$key] . "'";;
			}
		}

		$sql1 = mysqli_query($writeConnection,$sql);
	}


	if ($sql1) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1&region_f=' . $region . '&att_mon=' . $month_date);
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}


}

// daily attendance
else if ($pid == "hrms_daily_attendance_new") {

	//echo "succ"; die;

	//echo '<pre>'; print_r($_POST); die;
	$type = $_POST['type'];
	$date = $_POST['date'];
	$id = $_POST['id'];
	$month_date = date(('M-Y'), strtotime($date));
	$region = $_POST['region'];
	//$m_days=$_POST['m_days'];
	$lop = $_POST['iop'];
	//echo array_count_values($id);die;

	$gg = 1;
	foreach ($_POST['m_days'] as $key => $value) {
		$g = 1;
		$t = '';
		$tup = '';
		$tot = '';
		foreach ($_POST['totmonth' . $gg] as $key2 => $value2) {
			$t .= 'd_' . $g . ',';
			$tup .= "d_" . $g . "='" . $_POST["totmonth" . $gg][$key2] . "',";
			$tot .= "'" . $_POST["totmonth" . $gg][$key2] . "',";
			$g++;
		}
		$totm = rtrim($tot, ',');
		$tq = rtrim($t, ',');
		$tupdate = rtrim($tup, ',');
		$sqlchk = "SELECT * FROM hrms_attendance where emp_id='" . $key . "' and attendance_month_year='" . $month_date . "' and status='Y'";
		$m = mysqli_query($writeConnection,$sqlchk);
		$p = mysqli_num_rows($m);
		if ($p == 0) {
			$sql = "insert into hrms_attendance(branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj, 	designation,department,m_days,sal_days,present_days,lop,total_el,total_cl,total_sl,total_ml,total_present,status,tele_allce,incentive,oth_allce,sal_advance,other_ded,misc_amt,remarks,pre_ot_fixed,$tq)values('" . $region . "','" . $date . "','" . $key . "','" . $_POST['emp_name'][$key] . "','" . $month_date . "','" . $_POST['dob'][$key] . "','" . $_POST['desig'][$key] . "','" . $_POST['dep'][$key] . "','" . $_POST['m_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['lop'][$key] . "','" . $_POST['el'][$key] . "','" . $_POST['cl'][$key] . "','" . $_POST['sl'][$key] . "','" . $_POST['ml'][$key] . "','" . $_POST['p_days'][$key] . "','Y','" . $_POST['tele_allc'][$key] . "','" . $_POST['ince'][$key] . "','" . $_POST['ather_allc'][$key] . "','" . $_POST['sal_adv'][$key] . "','" . $_POST['other_dec'][$key] . "','" . $_POST['penal_deduct'][$key] . "','" . $_POST['remarks'][$key] . "','" . $_POST['ot_amt'][$key] . "',$totm)";
		} else {



			$sql = "update hrms_attendance set branch='" . $region . "',date_of_salary='" . $date . "',emp_id='" . $key . "',emp_name='" . $_POST['emp_name'][$key] . "',attendance_month_year='" . $month_date . "',emp_doj='" . $_POST['dob'][$key] . "',designation='" . $_POST['desig'][$key] . "',department='" . $_POST['dep'][$key] . "',m_days='" . $_POST['m_days'][$key] . "',sal_days='" . $_POST['p_days'][$key] . "',present_days='" . $_POST['p_days'][$key] . "',total_el='" . $_POST['el'][$key] . "',total_cl='" . $_POST['cl'][$key] . "',total_sl='" . $_POST['sl'][$key] . "',total_ml='" . $_POST['ml'][$key] . "',total_present='" . $_POST['p_days'][$key] . "',status='Y',lop='" . $_POST['lop'][$key] . "',tele_allce='" . $_POST['tele_allc'][$key] . "',incentive='" . $_POST['ince'][$key] . "',oth_allce='" . $_POST['ather_allc'][$key] . "',pre_ot_fixed='" . $_POST['ot_amt'][$key] . "',sal_advance='" . $_POST['sal_adv'][$key] . "',other_ded='" . $_POST['other_dec'][$key] . "',misc_amt='" . $_POST['penal_deduct'][$key] . "',remarks='" . $_POST['remarks'][$key] . "',$tupdate where id='" . $_POST['id'][$key] . "'";
		}

		if ($type == 'Allowance') {


			$sql = "update hrms_attendance set tele_allce='" . $_POST['tele_allc'][$key] . "',incentive='" . $_POST['ince'][$key] . "',oth_allce='" . $_POST['ather_allc'][$key] . "',pre_ot_fixed='" . $_POST['ot_amt'][$key] . "',sal_advance='" . $_POST['sal_adv'][$key] . "',other_ded='" . $_POST['other_dec'][$key] . "',remarks='" . $_POST['remarks_other_dec'][$key] . "' where id='" . $_POST['id'][$key] . "'";
		}
		$sql1 = mysqli_query($writeConnection,$sql);

		$gg++;
	}


	if ($sql1) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
} else if ($pid == "hrms_ce_attnpay_screen") {
	// echo '<pre>'; print_r($_POST); die;
	//area
	$area = $_POST['area'];
	$ce_name = $_POST['ce_name'];
	$rad = $_POST['rad'];
	$location = $_POST['location'];
	$f_name = $_POST['f_name'];
	$dob = $_POST['dob'];
	$doj = $_POST['doj'];
	$mobile_no = $_POST['mobile_no'];
	$status = $_POST['status'];
	$bank = $_POST['bank'];
	$branch = $_POST['branch'];
	$ifsc_code = $_POST['ifsc_code'];
	$acc_no = $_POST['acc_no'];
	$pu_points = $_POST['pu_points'];
	$cd_points = $_POST['cd_points'];
	$tot_points = $_POST['tot_points'];
	$pre_points = $_POST['pre_points'];
	$var_points = $_POST['var_points'];
	$dom = $_POST['dom'];
	$dow = $_POST['dow'];
	$tot_km = $_POST['tot_km'];
	//

	$service_ch = $_POST['service_ch'];
	$sc_due = $_POST['sc_due'];
	$location = $_POST['location'];
	$mobile = $_POST['mobile'];
	$trans = $_POST['trans'];
	$sun = $_POST['sun'];
	$cd = $_POST['cd'];
	$arr = $_POST['arr'];
	$t_amt = $_POST['t_amt'];
	$fax = $_POST['fax'];
	$courier = $_POST['courier'];
	$bag = $_POST['bag'];
	$fuel = $_POST['fuel'];
	$taxi = $_POST['taxi'];
	$parking = $_POST['parking'];
	$others = $_POST['others'];
	$gr_pay = $_POST['gr_pay'];
	$tds = $_POST['tds'];
	$med_ins = $_POST['med_ins'];
	$penal = $_POST['penal'];
	$mis_ch = $_POST['mis_ch'];
	//
	$loan = $_POST['loan'];
	$t_recovery = $_POST['t_recovery'];
	$net_pay = $_POST['net_pay'];
	$pre_amt = $_POST['pre_amt'];
	$var_amt = $_POST['var_amt'];
	$remarks = $_POST['remarks'];
	$sal_date = date('Y-m-d', strtotime($_POST['pay_date']));
	$sal_mon = date('M-Y', strtotime($sal_date));
	$pay_mode = $_POST['pay_mode'];
	$crr_date = date('Y-m-d');
	$c_id = $_POST['c_id'];
	if ($c_id == '') {

		$sql = "INSERT INTO `hrms_ce_attn_pay` (`region`, `date_of_salary`, `month_of_salary`, `ce_id`, `ce_name`, `location`, `loc_pin`, `no_pickup_point`, `no_cd_point`, `tot_point`, `service_charge`, `m_days`, `work_days`,  `servc_due`, `tele_allc`, `trans_allc`, `sunday_allc`, `cd_allc`, `arrear`, `tot_amt`, `fax_charge`, `courier_charge`, `bag_charge`, `fuel_charge`, `taxi_charge`, `parking_charge`, `misc_charge`, `gross_pay`, `tds`, `medical`, `penal`, `loan`, `net_pay`, `mode_pay`, `prev_point`, `prev_tot`, `varis_point`, `vais_amount`, `remark`,  `created_by`, `created_date`, `status`) VALUES ( '" . $area . "', '" . $sal_date . "', '" . $sal_mon . "', '" . $rad . "', '" . $ce_name . "', '" . $location . "', '', '" . $pu_points . "', '" . $cd_points . "', '" . $tot_points . "', '" . $service_ch . "', '" . $dom . "', '" . $dow . "', '" . $sc_due . "', '" . $mobile . "', '" . $trans . "', '" . $sun . "', '" . $cd . "', '" . $arr . "', '" . $t_amt . "', '" . $fax . "', '" . $courier . "', '" . $bag . "', '" . $fuel . "', '" . $taxi . "', '" . $parking . "', '" . $mis_ch . "', '" . $gr_pay . "', '" . $tds . "', '" . $med_ins . "', '" . $penal . "', '" . $loan . "', '" . $net_pay . "', '" . $pay_mode . "', '" . $pre_points . "', '" . $pre_amt . "', '" . $var_points . "', '" . $var_amt . "', '" . $remarks . "', '" . $user_name . "', '" . $crr_date . "', 'Y')";
	} else {
		//$sql="update hrms_ce_attn_pay set region='".$area."',month_of_salary='".$sal_mon."',ce_id='".$rad."',ce_name='".$ce_name."',location='".$location."',loc_pin='".$area."',no_pickup_point='".$pu_points."',no_cd_point='".$cd_points."',tot_point='".$tot_points."',service_charge='".$service_ch."',work_days='".$dow."',servc_due='".$sc_due."',tele_allc='".$mobile."',trans_allc='".$trans."',sunday_allc='".$sun."',cd_allc='".$cd."',arrear='".$arr."',tot_amt='".$t_amt."',fax_charge='".$fax."',courier_charge='".$courier."',bag_charge='".$bag."',fuel_charge";  
		$sql = "update hrms_ce_attn_pay set region='" . $area . "',month_of_salary='" . $sal_mon . "',ce_id='" . $rad . "',ce_name='" . $ce_name . "',location='" . $location . "',loc_pin='" . $area . "',no_pickup_point='" . $pu_points . "',no_cd_point='" . $cd_points . "',tot_point='" . $tot_points . "',service_charge='" . $service_ch . "',work_days='" . $dow . "',servc_due='" . $sc_due . "',tele_allc='" . $mobile . "',trans_allc='" . $trans . "',sunday_allc='" . $sun . "',cd_allc='" . $cd . "',arrear='" . $arr . "',tot_amt='" . $t_amt . "',fax_charge='" . $fax . "',courier_charge='" . $courier . "',bag_charge='" . $bag . "',fuel_charge='" . $fuel . "',taxi_charge='" . $taxi . "',parking_charge='" . $parking . "',misc_charge='" . $mis_ch . "',gross_pay='" . $gr_pay . "',tds='" . $tds . "',medical='" . $med_ins . "',penal='" . $penal . "',loan='" . $loan . "',net_pay='" . $net_pay . "',mode_pay='" . $pay_mode . "',prev_point='" . $pre_points . "',prev_tot='" . $pre_amt . "',varis_point='" . $var_points . "',vais_amount='" . $var_amt . "',remark='" . $remarks . "',created_by='" . $user_name . "',created_date='" . $crr_date . "' where id='" . $c_id . "' and status='Y'";
	}

	$f_sql = mysqli_query($writeConnection,$sql);
	if ($f_sql) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
}
//hrms_ce_attnpay_screen

else if ($pid == "attnfrez") {
	$reg = $_POST['reg'];
	$data = date('Y-m-d');
	$att_mon = $_POST['att_mon'];
	$sql = mysqli_query($writeConnection,"insert into hrms_attendance_status(attn_month,region,frz_status,frez_by,frez_date,status)values('" . $att_mon . "','" . $reg . "','1','" . $user_name . "','" . $data . "','Y')");
} else if ($pid == "attnunfrez") {
	$reg = $_POST['reg'];
	$data = date('Y-m-d');
	$att_mon = date('M-Y', strtotime($_POST['att_mon']));
	//	 $sql=mysqli_query($writeConnection,"insert into hrms_attendance_status(attn_month,region,frz_status,frez_by,frez_date,status)values('".$att_mon."','".$reg."','1','".$user_name."','".$data."','Y')");
	//echo "update hrms_attendance_status set frez_by='".$user_name."',frez_date='".$data."',frz_status='0' where attn_month='".$data."' and region='".$reg."' and status='Y'"; die;

	$sql = mysqli_query($writeConnection,"update hrms_attendance_status set frez_by='" . $user_name . "',frez_date='" . $data . "',frz_status='0' where attn_month='" . $att_mon . "' and region='" . $reg . "' and status='Y'");
} else if ($pid == "hrms_staff_attnpay") {
	$sal_date = $_POST['sal_date'];
	$region = $_POST['region'];
	$id = $_POST['id'];
	$data = date('Y-m-d');
	$att_mon = date('M-Y', strtotime($sal_date));
	$m_days = date('t', strtotime($sal_date));

	$sql_emp = mysqli_query($writeConnection,"select * from hrms_empdet where emp_id='" . $id . "' and region='" . $region . "' and status='Y'");
	$row_emp = mysqli_fetch_array($sql_emp);



	$sql = mysqli_query($writeConnection,"insert into hrms_attendance(branch,emp_id,emp_name,attendance_month_year	,designation,department,m_days,created_by,created_date,status)values('" . $region . "','" . $row_emp['emp_id'] . "','" . $row_emp['cname'] . "','" . $att_mon . "','" . $row_emp['pdesig1'] . "','" . $row_emp['pdesig'] . "','" . $m_days . "','" . $user_name . "','" . $data . "','Y')");
	//echo "update hrms_attendance_status set frez_by='".$user_name."',frez_date='".$data."',frz_status='0' where attn_month='".$data."' and region='".$reg."' and status='Y'"; die;


	if ($sql) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
} else if ($pid == "hrms_rpf_attnpay") {
	$sal_date = $_POST['sal_date'];
	$region = $_POST['region'];
	$id = $_POST['id'];
	$data = date('Y-m-d');
	$att_mon = date('M-Y', strtotime($sal_date));
	$m_days = date('t', strtotime($sal_date));

	$sql_emp = mysqli_query($writeConnection,"select * from hrms_empdet where emp_id='" . $id . "' and region='" . $region . "' and status='Y'");
	$row_emp = mysqli_fetch_array($sql_emp);



	$sql = mysqli_query($writeConnection,"insert into hrms_attendance_rpf(branch,emp_id,emp_name,attendance_month_year	,designation,department,m_days,created_by,created_date,status)values('" . $region . "','" . $row_emp['emp_id'] . "','" . $row_emp['cname'] . "','" . $att_mon . "','" . $row_emp['pdesig1'] . "','" . $row_emp['pdesig'] . "','" . $m_days . "','" . $user_name . "','" . $data . "','Y')");
	//echo "update hrms_attendance_status set frez_by='".$user_name."',frez_date='".$data."',frz_status='0' where attn_month='".$data."' and region='".$reg."' and status='Y'"; die;


	if ($sql) {
		
		header('Location:../?pid=' . $pid . '&nav=2_1_1');
	} else {
		
		header('Location:../?pid=' . $pid . '&nav=2_2_1');
	}
}

// new data 
else if ($pid == "hrms_daily_attendance_rpf") {

	//echo "succ"; die;

	//echo '<pre>'; print_r($_POST); die;
	$type = $_POST['type'];
	$date = $_POST['date'];
	$id = $_POST['id'];
	$month_date = date(('M-Y'), strtotime($date));
	$chk_date = date('d-m-Y', strtotime($date));

	$region = $_POST['region'];
	//$m_days=$_POST['m_days'];
	$lop = $_POST['iop'];
	$curr_date = date('Y-m-d');
	//echo array_count_values($id);die;

	foreach ($_POST['m_days'] as $key => $value) {
		foreach ($_POST['id'] as $key2 => $value2) {
		} //die;
		// echo '<pre>'; print_r($value2);  die;
		// echo $key."-".'<br>'; print_r($_POST);  

		if (empty($value2)) {

			$sql = "insert into hrms_attendance_rpf(branch,date_of_salary,emp_id,emp_name,attendance_month_year,emp_doj, 	designation,department,m_days,sal_days,present_days,lop,tds,total_el,total_cl,total_sl,total_ml,total_present,status,created_by,created_date)values('" . $region . "','" . $date . "','" . $key . "','" . $_POST['emp_name'][$key] . "','" . $month_date . "','" . $_POST['dob'][$key] . "','" . $_POST['desig'][$key] . "','" . $_POST['dep'][$key] . "','" . $_POST['m_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['p_days'][$key] . "','" . $_POST['lop'][$key] . "','" . $_POST['tds'][$key] . "','" . $_POST['el'][$key] . "','" . $_POST['cl'][$key] . "','" . $_POST['sl'][$key] . "','" . $_POST['ml'][$key] . "','" . $_POST['p_days'][$key] . "','Y','" . $user_name . "','" . $curr_date . "')";
		} else {
			if ($type == 'Allowance') {
				//echo "select gross_sal from hrms_empdet where emp_id='".$key."'"; die;

				$basic_sal = mysqli_query($writeConnection,"select gross_sal from hrms_empdet where emp_id='" . $key . "'");
				$basic_sal_row = mysqli_fetch_array($basic_sal);

				$gross_pay = $basic_sal_row['gross_sal'];
				if ($region == 'Kolkata') {
					$basic_fixed = ceil(($gross_pay * (70 / 100)));
					$hra_fixed = ceil(($basic_fixed * (25 / 100)));
					$conveyance_fixed = 800;
					$bonus_fixed = round(($gross_pay * (8.33 / 100)), 0);
					if ($gross_pay == 30000) {
						$medical_fixed = 1250;
					} else {
						$medical_fixed = 0;
					}
				} else {
					$basic_fixed = ceil(($gross_pay * (50 / 100)));
					$hra_fixed = ceil(($basic_fixed * (50 / 100)));
					$conveyance_fixed = 800;
					$bonus_fixed = round(($gross_pay * (8.33 / 100)), 0);
					if ($gross_pay == 30000) {
						$medical_fixed = 1250;
					} else {
						$medical_fixed = 0;
					}
				}
				$other_all_fixed = $gross_pay - ($basic_fixed + $hra_fixed + $conveyance_fixed  + $bonus_fixed);
				$total_present_days = $_POST['m_days'][$key];
				$month_days = $_POST['tele_allcvi'][$key];
				//echo $_POST['tele_allcvi'][$key]."vimal"; die;

				//current
				$basic_pay = ceil(($basic_fixed / $month_days) * $total_present_days);
				$hra_pay = ceil(($hra_fixed / $month_days) * $total_present_days);
				$conveyance_pay = round(($conveyance_fixed / $month_days) * $total_present_days, 0);
				$bonus_pay = round(($bonus_fixed / $month_days) * $total_present_days, 0);
				$other_all_pay = round(($other_all_fixed / $month_days) * $total_present_days, 0);

				$variable_allowance = round($_POST['tele_allc'][$key] + $_POST['ince'][$key] + $last_row['sun_allc'] + $_POST['ather_allc'][$key] + $_POST['holiday_allow'][$key] + $_POST['ot_amt'][$key]);
				$pre_gross_pay = round($basic_pay + $hra_pay + $conveyance_pay + $bonus_pay + $other_all_pay + $variable_allowance);
				//deduction

				$epf = round(($basic_pay * (12 / 100)), 0);
				if ($gross_pay <= 15000) {
					//raghu start 30-07-2019
					if (strtotime($chk_date) < strtotime('01-07-2019')) {
						$esi = ceil(($pre_gross_pay * (1.75 / 100)));
					} else {
						$esi = ceil(($pre_gross_pay * (0.75 / 100)));
					}
					//raghu end 30-07-2019
				}
				$p_tax = 0;
				$tds = 0;
				$salary_advance = $_POST['sal_adv'][$key];
				$other_deductions = $_POST['other_dec'][$key];
				$misc_amt = $_POST['penal_dec'][$key];

				$gross_deductions = round($epf + $esi + $p_tax + $tds + $salary_advance + $other_deductions + $misc_amt, 0);
				$pre_net_payable = round($pre_gross_pay - $gross_deductions, 2);



				$sql = "update hrms_attendance_rpf set tele_allce='" . $_POST['tele_allc'][$key] . "',incentive='" . $_POST['ince'][$key] . "',oth_allce='" . $_POST['ather_allc'][$key] . "',pre_ot_fixed='" . $_POST['ot_amt'][$key] . "',sal_advance='" . $_POST['sal_advance'][$key] . "',other_ded='" . $_POST['other_dec'][$key] . "',holiday_amt='" . $_POST['holiday_allow'][$key] . "',misc_amt='" . $_POST['penality'][$key] . "',p_tax='" . $_POST['tds'][$key] . "',pre_gross_pay='" . $pre_gross_pay . "',pre_net_pay='" . $_POST['net_pay'][$key] . "',remarks='" . $_POST['remarks_other_dec'][$key] . "' where id='" . $_POST['id'][$key] . "'";
			} else {


				$sql = "update hrms_attendance_rpf set branch='" . $region . "',date_of_salary='" . $date . "',emp_id='" . $key . "',emp_name='" . $_POST['emp_name'][$key] . "',attendance_month_year='" . $month_date . "',emp_doj='" . $_POST['dob'][$key] . "',designation='" . $_POST['desig'][$key] . "',department='" . $_POST['dep'][$key] . "',m_days='" . $_POST['m_days'][$key] . "',sal_days='" . $_POST['p_days'][$key] . "',present_days='" . $_POST['p_days'][$key] . "',total_el='" . $_POST['el'][$key] . "',total_cl='" . $_POST['cl'][$key] . "',total_sl='" . $_POST['sl'][$key] . "',total_ml='" . $_POST['ml'][$key] . "',total_present='" . $_POST['p_days'][$key] . "',status='Y',lop='" . $_POST['lop'][$key] . "',tds='" . $_POST['tds'][$key] . "' where id='" . $_POST['id'][$key] . "'";
			}
		}

		$sql1 = mysqli_query($writeConnection,$sql);
	}

	if ($sql1) {
		
		header('Location:../?pid=hrms_rpf_attnpay_screen&nav=2_1_1&region_f=' . $region . '&att_mon=' . $month_date);
	} else {
		
		header('Location:../?pid=hrms_rpf_attnpay_screen&nav=2_2_1');
	}
}

?>