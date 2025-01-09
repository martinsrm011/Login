else if($pid == 'Service_Master'){
		$service_id = $_POST['service_id'];
		if($_POST['group_name'] == 'others'){
			$group = $_POST['other_group'];
		}else{
			$group = $_POST['group_name'];
		}
		if($service_id==''){
			$sql = "insert into cashvan_services(region_id,client_id,service_code,service_name,group_name,service_type,description,start_date,end_date,working_hours,update_by,update_time,status) values('".$_POST['region_id']."','".$_POST['client_id']."','".$_POST['service_code']."','".$_POST['service_name']."','".$group."','".$_POST['service_type']."','".$_POST['description']."','".date('Y-m-d',strtotime($_POST['start_date']))."','".$_POST['end_date']."','".$_POST['working_hours']."','".$user."','now()','Y')";
			$nav = '2_1_1';
		}else{
			$sql = "update cashvan_services set region_id='".$_POST['region_id']."',client_id='".$_POST['client_id']."',service_name='".$_POST['service_name']."',group_name='".$group."',service_type='".$_POST['service_type']."',description='".$_POST['description']."',start_date='".date('Y-m-d',strtotime($_POST['start_date']))."',end_date='".$_POST['end_date']."',working_hours='".$_POST['working_hours']."',update_by='".$user."',update_time=now() where service_id='".$_POST['service_id']."' and status='Y'";
			$nav = '2_1_2';
		}
		$excs = mysql_query($sql);
		if($excs){
			mysql_close($conn);
			header("Location:./?pid=Service_Master&nav=".$nav);
		}else{
			mysql_close($conn);
			header("Location:./?pid=Service_Master&nav=2_3");
		}
	}