<?php
	//echo "success"; 
	// require_once 'Classes/PHPExcel.php';
	// //require_once 'PHPExcel/Classes/PHPExcel.php';
    // require_once 'Classes/PHPExcel/IOFactory.php';
	// include 'Classes/PHPExcel/Writer/Excel2007.php';
	// include 'HRMS_Files/excel_user_defined_function.php';
	include('DbConnection/dbConnect.php');
	$pid = $_REQUEST['pid'];
	require 'dependencies/vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
	use PhpOffice\PhpSpreadsheet\Style\Alignment;
	if($pid =='hrms_pancard_details'){
		//echo "fail"; 
	
	 $region = $_REQUEST['region']; 
	$date_excel = $from_date;
	
// $report_name = $_POST['report_name'];
 
  // $branch_name=$_REQUEST['branch_name'];
 if($region!=''){


 	  $branch_id ="and region=".$region."";
	 
	 }else{
		 
	
		  $branch_id ="and region!=''"; 
		 
		 }
		 
		 	$desig=array("EXE"=>"Exectiuve","CE"=>"Cash Executive","MBC"=>"MBC","CVC"=>"Cash Van Custodian","GN"=>"Gunman","DR"=>"Driver",
"LD"=>"Loader","PR"=>"Processor","AM"=>"Assistant Manager","RM"=>"Risk Manager","MGR"=>"Manager","CHR"=>"Cashier","SE"=>"Senior Executive",
"BH"=>"Branch Head","RH"=>"Regional Head","ACHR"=>"Assistant cashier","DM"=>"Deputy Manager","SRM"=>"Senior Risk Manager","SMGR"=>"Senior Manager",
"ARM"=>"Assistant Risk Manager","SMBC"=>"Senior MBC","OH"=>"Office Assistant/HouseKeeping ","GM"=>"General Manager","AGM"=>"Asst. General Manager","SV"=>"Supervisor","DIR"=>"Director","CFO"=>"Chief Finance Officer","CTO"=>"Chief Technology Officer","GUD"=>"Guards","CVCE"=>"CVCE");

$dep=array("OP"=>"Operations","IT"=>"Information technology","BD"=>"Business Development","DM"=>"Data Management","CR"=>"Customer Relation
","BILL"=>"Billing","AF"=>"Accounts & Finance","AUD"=>"Audit","BNK"=>"Banking","ADM"=>"Admin","HR"=>"Human Resource","PAY"=>"Payroll","VLT"=>"Vault");
 
 $visib = array('Applied', 'Not Applied', '');
	// $branch_id =$_POST['branch']; 
	   $from_date = date('Y=m-d',strtotime($_REQUEST['from_date'])); 
	     $to_date = date('Y-m-d',strtotime($_REQUEST['to_date']));
//echo	$branch_id = implode(",",$_POST['branch']); die;
	//$location_id = implode(",",$_POST['location']);
	$where = "";
$last_cell='O';$mid_cell='H';
$spreadsheet = new Spreadsheet();
// Set document properties
$spreadsheet->getProperties()->setCreator("Maarten Balliauw")
             ->setLastModifiedBy("Maarten Balliauw")
             ->setTitle("Office 2007 XLSX Test Document")
             ->setSubject("Office 2007 XLSX Test Document")
             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
             ->setKeywords("office 2007 openxml php")
             ->setCategory("Test result file");

		$spreadsheet->getActiveSheet()->setTitle("CE");
		// $spreadsheet->getActiveSheet()->getDefaultStyle()->applyFromArray(array(
		// 				'fill' => array(
		// 					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
		// 					'color' => array('argb' => 'FFFFFF')
		// 				),
		// 			)
		// 		);
		$spreadsheet->getActiveSheet()->mergeCells("A1:O1");
		///$spreadsheet->getActiveSheet()->setCellValue("A1","Radiant Cash Management Service-Employee New Joiners Details- ".date('M-Y'),strtotime($from_date));
		//SetCellFont("A1","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
		$spreadsheet->getActiveSheet()->setCellValue("A2","Radiant Cash Management Service");
		//$spreadsheet->getActiveSheet()->mergeCells("A1:A2");
	//	$spreadsheet->getRowDimension('1')->setRowHeight(-1);
	
	/*$gdImage = imagecreatefromjpeg('img/logo.png');
		$spreadsheet->getActiveSheet()->mergeCells('A1:'.$last_cell.'1');
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setPath('img/logo.png');
		$objDrawing->setCoordinates($mid_cell.'1');*/
	
	
		//$objDrawing->setWidth('50');
		//$objDrawing->setHeight('50');
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(80);
		//$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
		//$offsetX =1500 - $objDrawing->getWidth();
		//$objDrawing->setOffsetX($offsetX);
		//$objDrawing->setWorksheet($spreadsheet->getActiveSheet());

		$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
		$spreadsheet->getActiveSheet()->setCellValue("A3","SNo");
		//SetCellFont("A2:G3","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->setCellValue("A3","SNo");
		$spreadsheet->getActiveSheet()->setCellValue("B3","Emp ID");
		$spreadsheet->getActiveSheet()->setCellValue("C3","Emp Name");
		$spreadsheet->getActiveSheet()->setCellValue("D3","Department");
		$spreadsheet->getActiveSheet()->setCellValue("E3","Designation");
		$spreadsheet->getActiveSheet()->setCellValue("F3","Location");
		$spreadsheet->getActiveSheet()->setCellValue("G3","Pancard No");
		$spreadsheet->getActiveSheet()->setCellValue("H3","Account No");
		$spreadsheet->getActiveSheet()->setCellValue("I3","Bank Name");
		$spreadsheet->getActiveSheet()->setCellValue("J3","Branch Name");
		$spreadsheet->getActiveSheet()->setCellValue("K3","IFSC Code");
		$spreadsheet->getActiveSheet()->setCellValue("L3","Aadhar Card No");
		$spreadsheet->getActiveSheet()->setCellValue("M3","ESI No");
		$spreadsheet->getActiveSheet()->setCellValue("N3","EPF No");
		$spreadsheet->getActiveSheet()->setCellValue("O3","UAN No");
		//SetBackgroundColor("G3","FF0000");
	//	SetAlignment("A1:O3","horizontal_center","vertical_center");

		cellcenterv("A1:O3");
		cellcenterh("A1:O3");
	$data_array = array();
	
//	echo " select * from hrms_mpdet where approved_date between '".$from_date."' and '".$to_date."' and region='".$branch_id."' "; die;
	
	//echo" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
//echo 	" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
	//echo " select * from hrms_empdet where  status='Y' $branch_id  "; die;
		 $sql=mysql_query(" select * from hrms_empdet where  status='Y' $branch_id and (pdesig1='CE' or pdesig1='CCE' or pdesig1='CVCE') and wstatus!='Dormant' ");
		 

//echo " select pay.emp_id,pay.emp_name,pay.emp_doj,pay.designation,pay.department,pay.m_days,pay.sal_days,pay.total_present,pay.sundays,pay.holidays,pay.tele_allce,pay.oth_allce,emp.plocation,emp.mobile1,emp.dob,emp.father_name,emp.pan_card_no,emp.bank_name,emp.account_no,emp.branch_name,emp.ifsc_code,emp.gross_sal  from hrms_attendance pay join hrms_empdet emp on pay.emp_id=emp.emp_id where pay.attendance_month_year = '".$from_date."' and pay.branch='".$branch_id."' and pay.status='Y'group by emp.emp_id "; die;
		$sno=1;
		$rowcont = 4;
		//SetWrapText("A3:O3");
		$spreadsheet->getActiveSheet()->getStyle("A3:O3")->getAlignment()->setWrapText(true);
			if(mysql_num_rows($sql) > 0){
			while($row = mysql_fetch_array($sql)){
			 	$total_working_days = $row['total_present'] + $row['sundays'] + $row['holidays']; 
				$spreadsheet->getActiveSheet()->setCellValue("A".$rowcont,$sno);
				$spreadsheet->getActiveSheet()->setCellValue("B".$rowcont,$row['emp_id']);
				$spreadsheet->getActiveSheet()->setCellValue("C".$rowcont,$row['cname']);
				$spreadsheet->getActiveSheet()->setCellValue("D".$rowcont,$dep[$row['pdesig']]);
				$spreadsheet->getActiveSheet()->setCellValue("E".$rowcont,$desig[$row['pdesig1']]);
				$spreadsheet->getActiveSheet()->setCellValue("F".$rowcont,$row['plocation']);
				$spreadsheet->getActiveSheet()->setCellValue("G".$rowcont,$row['pan_card_no']);//account_no
				$spreadsheet->getActiveSheet()->setCellValue("H".$rowcont,$row['account_no']);
				 if($row['pan_card_no']=="Applied" ){
SetBackgroundColor("G".$rowcont,"CD5C5C");}
 if($row['account_no']=="" || $row['account_no']=="Applied" ){
SetBackgroundColor("H".$rowcont,"CD5C5C");}


				$spreadsheet->getActiveSheet()->setCellValue("I".$rowcont,$row['bank_name']);
				$spreadsheet->getActiveSheet()->setCellValue("J".$rowcont,$row['branch_name']);
				$spreadsheet->getActiveSheet()->setCellValue("K".$rowcont,$row['ifsc_code']);
				$spreadsheet->getActiveSheet()->setCellValue("L".$rowcont,$row['aadhar_card_no']);
				$spreadsheet->getActiveSheet()->setCellValue("M".$rowcont,$row['esi_no']);//account_no
				$spreadsheet->getActiveSheet()->setCellValue("N".$rowcont,$row['epf_no']);
			    $spreadsheet->getActiveSheet()->setCellValue("O".$rowcont,$row['uan_no']);//account_no
				


				$sno++;
				$rowcont++;
			}
	
		//	//SetCellFont("A".$rowcont.":BG".$rowcont.,"Arial","10",true,false,"none","000000");
			//$spreadsheet->getActiveSheet()->setCellValue("AD".$rowcont,'=SUM(AD4:AD'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AE".$rowcont,'=SUM(AE4:AE'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AF".$rowcont,'=SUM(AF4:AF'.($rowcont-1).')');
			}
			
			//SetCellborder("A1","");
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1);//date('H:i',strtotime($trans_date)
		$spreadsheet->getActiveSheet()->setTitle("RPF");
		
			$spreadsheet->getActiveSheet()->mergeCells("A1:H1");
		///$spreadsheet->getActiveSheet()->setCellValue("A1","Radiant Cash Management Service-Employee New Joiners Details- ".date('M-Y'),strtotime($from_date));
		//SetCellFont("A1","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->mergeCells("A2:H2");
		$spreadsheet->getActiveSheet()->setCellValue("A2","Radiant Cash Management Service");
		//$spreadsheet->getActiveSheet()->mergeCells("A1:A2");
	//	$spreadsheet->getRowDimension('1')->setRowHeight(-1);

	/*  $gdImage = imagecreatefromjpeg('img/logo.png');
		$spreadsheet->getActiveSheet()->mergeCells('A1:'.$last_cell.'1');
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setPath('img/logo.png');
		$objDrawing->setCoordinates($mid_cell.'1'); */

		//$objDrawing->setWidth('50');
		//$objDrawing->setHeight('50');
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(80);
		//$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
		//$offsetX =1500 - $objDrawing->getWidth();
		//$objDrawing->setOffsetX($offsetX);
		//$objDrawing->setWorksheet($spreadsheet->getActiveSheet());
		$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
		//SetCellFont("A2:O3","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->setCellValue("A3","SNo");
		$spreadsheet->getActiveSheet()->setCellValue("B3","Emp ID");
		$spreadsheet->getActiveSheet()->setCellValue("C3","Emp Name");
		$spreadsheet->getActiveSheet()->setCellValue("D3","Department");
		$spreadsheet->getActiveSheet()->setCellValue("E3","Designation");
		$spreadsheet->getActiveSheet()->setCellValue("F3","Location");
		$spreadsheet->getActiveSheet()->setCellValue("G3","Pancard No");
		$spreadsheet->getActiveSheet()->setCellValue("H3","Account No");
		$spreadsheet->getActiveSheet()->setCellValue("I3","Bank Name");
		$spreadsheet->getActiveSheet()->setCellValue("J3","Branch Name");
		$spreadsheet->getActiveSheet()->setCellValue("K3","IFSC Code");
		$spreadsheet->getActiveSheet()->setCellValue("L3","Aadhar Card No");
		$spreadsheet->getActiveSheet()->setCellValue("M3","ESI No");
		$spreadsheet->getActiveSheet()->setCellValue("N3","EPF No");
		$spreadsheet->getActiveSheet()->setCellValue("O3","UAN No");
		//SetAlignment("A1:O3","horizontal_center","vertical_center");
		cellcenterv("A1:O3");
		cellcenterh("A1:O3");
	$data_array = array();
	
//	echo " select * from hrms_mpdet where approved_date between '".$from_date."' and '".$to_date."' and region='".$branch_id."' "; die;
	
	//echo" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
//echo 	" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
	//echo " select * from hrms_empdet where  status='Y' $branch_id  "; die;
		 $sql=mysql_query(" select * from hrms_empdet where  status='Y' $branch_id and (pdesig1='GUD' or pdesig1='DR' or pdesig1='GN' ) and wstatus!='Dormant' ");
		 

//echo " select pay.emp_id,pay.emp_name,pay.emp_doj,pay.designation,pay.department,pay.m_days,pay.sal_days,pay.total_present,pay.sundays,pay.holidays,pay.tele_allce,pay.oth_allce,emp.plocation,emp.mobile1,emp.dob,emp.father_name,emp.pan_card_no,emp.bank_name,emp.account_no,emp.branch_name,emp.ifsc_code,emp.gross_sal  from hrms_attendance pay join hrms_empdet emp on pay.emp_id=emp.emp_id where pay.attendance_month_year = '".$from_date."' and pay.branch='".$branch_id."' and pay.status='Y'group by emp.emp_id "; die;
		$sno=1;
		$rowcont = 4;
		//SetWrapText("A3:O3");
		$spreadsheet->getActiveSheet()->getStyle("A3:O3")->getAlignment()->setWrapText(true);
	
			if(mysql_num_rows($sql) > 0){
			while($row = mysql_fetch_array($sql)){
			 	$total_working_days = $row['total_present'] + $row['sundays'] + $row['holidays']; 
				$spreadsheet->getActiveSheet()->setCellValue("A".$rowcont,$sno);
				$spreadsheet->getActiveSheet()->setCellValue("B".$rowcont,$row['emp_id']);
				$spreadsheet->getActiveSheet()->setCellValue("C".$rowcont,$row['cname']);
				$spreadsheet->getActiveSheet()->setCellValue("D".$rowcont,$dep[$row['pdesig']]);
				$spreadsheet->getActiveSheet()->setCellValue("E".$rowcont,$desig[$row['pdesig1']]);
				$spreadsheet->getActiveSheet()->setCellValue("F".$rowcont,$row['plocation']);
				$spreadsheet->getActiveSheet()->setCellValue("G".$rowcont,$row['pan_card_no']);
				$spreadsheet->getActiveSheet()->setCellValue("H".$rowcont,$row['account_no']);
		if($row['pan_card_no']=="Applied"){
SetBackgroundColor("G".$rowcont,"CD5C5C");}
if($row['account_no']=="Applied" || $row['account_no']==""){
SetBackgroundColor("H".$rowcont,"CD5C5C");}

				$spreadsheet->getActiveSheet()->setCellValue("I".$rowcont,$row['bank_name']);
				$spreadsheet->getActiveSheet()->setCellValue("J".$rowcont,$row['branch_name']);
				$spreadsheet->getActiveSheet()->setCellValue("K".$rowcont,$row['ifsc_code']);
				$spreadsheet->getActiveSheet()->setCellValue("L".$rowcont,$row['aadhar_card_no']);
				$spreadsheet->getActiveSheet()->setCellValue("M".$rowcont,$row['esi_no']);//account_no
				$spreadsheet->getActiveSheet()->setCellValue("N".$rowcont,$row['epf_no']);
			    $spreadsheet->getActiveSheet()->setCellValue("O".$rowcont,$row['uan_no']);//account_no

				$sno++;
				$rowcont++;
			}
	
		//	//SetCellFont("A".$rowcont.":BG".$rowcont.,"Arial","10",true,false,"none","000000");
			//$spreadsheet->getActiveSheet()->setCellValue("AD".$rowcont,'=SUM(AD4:AD'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AE".$rowcont,'=SUM(AE4:AE'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AF".$rowcont,'=SUM(AF4:AF'.($rowcont-1).')');
			}
			//SetCellborder("A1","");
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(2);//date('H:i',strtotime($trans_date)
		$spreadsheet->getActiveSheet()->setTitle("STAFF");
		
			
			$spreadsheet->getActiveSheet()->mergeCells("A1:O1");
		///$spreadsheet->getActiveSheet()->setCellValue("A1","Radiant Cash Management Service-Employee New Joiners Details- ".date('M-Y'),strtotime($from_date));
		//SetCellFont("A1","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
		
		//$spreadsheet->getActiveSheet()->mergeCells("A1:A2");
	//	$spreadsheet->getRowDimension('1')->setRowHeight(-1);


	/*$gdImage = imagecreatefromjpeg('img/logo.png');
		$spreadsheet->getActiveSheet()->mergeCells('A1:'.$last_cell.'1');
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setPath('img/logo.png');
		$objDrawing->setCoordinates($mid_cell.'1'); */

		//$objDrawing->setWidth('50');
		//$objDrawing->setHeight('50');
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(80);
		//$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
		//$offsetX =1500 - $objDrawing->getWidth();
		//$objDrawing->setOffsetX($offsetX);
	//	$objDrawing->setWorksheet($spreadsheet->getActiveSheet());
		$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
		$spreadsheet->getActiveSheet()->setCellValue("A2","Radiant Cash Management Service");
		//SetCellFont("A2:O3","Arial","10",true,false,"none","000000");
		$spreadsheet->getActiveSheet()->setCellValue("A3","SNo");
		$spreadsheet->getActiveSheet()->setCellValue("B3","Emp ID");
		$spreadsheet->getActiveSheet()->setCellValue("C3","Emp Name");
		$spreadsheet->getActiveSheet()->setCellValue("D3","Department");
		$spreadsheet->getActiveSheet()->setCellValue("E3","Designation");
		$spreadsheet->getActiveSheet()->setCellValue("F3","Location");
		$spreadsheet->getActiveSheet()->setCellValue("G3","Pancard No");
		$spreadsheet->getActiveSheet()->setCellValue("H3","Account No");
		$spreadsheet->getActiveSheet()->setCellValue("I3","Bank Name");
		$spreadsheet->getActiveSheet()->setCellValue("J3","Branch Name");
		$spreadsheet->getActiveSheet()->setCellValue("K3","IFSC Code");
		$spreadsheet->getActiveSheet()->setCellValue("L3","Aadhar Card No");
		$spreadsheet->getActiveSheet()->setCellValue("M3","ESI No");
		$spreadsheet->getActiveSheet()->setCellValue("N3","EPF No");
		$spreadsheet->getActiveSheet()->setCellValue("O3","UAN No");
		//SetAlignment("A1:O3","horizontal_center","vertical_center");
		cellcenterv("A1:O3");
		cellcenterh("A1:O3");
	$data_array = array();
	
//	echo " select * from hrms_mpdet where approved_date between '".$from_date."' and '".$to_date."' and region='".$branch_id."' "; die;
	
	//echo" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
//echo 	" select * from hrms_empdet where approved_date between '".$from_date."' and '".$to_date."'  ".$branch_id." and status='Y' "; die;
	//echo " select * from hrms_empdet where  status='Y' $branch_id  "; die;
		 $sql=mysql_query(" select * from hrms_empdet where  status='Y' $branch_id and pdesig1!='CE' and pdesig1!='GN' and pdesig1!='GUD' and pdesig1!='DR' and pdesig1!='CCE' and wstatus!='Dormant' ");
		 

//echo " select pay.emp_id,pay.emp_name,pay.emp_doj,pay.designation,pay.department,pay.m_days,pay.sal_days,pay.total_present,pay.sundays,pay.holidays,pay.tele_allce,pay.oth_allce,emp.plocation,emp.mobile1,emp.dob,emp.father_name,emp.pan_card_no,emp.bank_name,emp.account_no,emp.branch_name,emp.ifsc_code,emp.gross_sal  from hrms_attendance pay join hrms_empdet emp on pay.emp_id=emp.emp_id where pay.attendance_month_year = '".$from_date."' and pay.branch='".$branch_id."' and pay.status='Y'group by emp.emp_id "; die;
		$sno=1;
		$rowcont = 4;
		//SetWrapText("A3:O3");

		$spreadsheet->getActiveSheet()->getStyle("A3:O3")->getAlignment()->setWrapText(true);
	
			if(mysql_num_rows($sql) > 0){
			while($row = mysql_fetch_array($sql)){
			 	$total_working_days = $row['total_present'] + $row['sundays'] + $row['holidays']; 
				$spreadsheet->getActiveSheet()->setCellValue("A".$rowcont,$sno);
				$spreadsheet->getActiveSheet()->setCellValue("B".$rowcont,$row['emp_id']);
				$spreadsheet->getActiveSheet()->setCellValue("C".$rowcont,$row['cname']);
				$spreadsheet->getActiveSheet()->setCellValue("D".$rowcont,$dep[$row['pdesig']]);
				$spreadsheet->getActiveSheet()->setCellValue("E".$rowcont,$desig[$row['pdesig1']]);
				$spreadsheet->getActiveSheet()->setCellValue("F".$rowcont,$row['plocation']);
				$spreadsheet->getActiveSheet()->setCellValue("G".$rowcont,$row['pan_card_no']);
				$spreadsheet->getActiveSheet()->setCellValue("H".$rowcont,$row['account_no']);
		if($row['pan_card_no']=="Applied"){
SetBackgroundColor("G".$rowcont,"CD5C5C");}
if($row['account_no']=="" || $row['account_no']=="Applied" ){
SetBackgroundColor("H".$rowcont,"CD5C5C");}
				$spreadsheet->getActiveSheet()->setCellValue("I".$rowcont,$row['bank_name']);
				$spreadsheet->getActiveSheet()->setCellValue("J".$rowcont,$row['branch_name']);
				$spreadsheet->getActiveSheet()->setCellValue("K".$rowcont,$row['ifsc_code']);
				$spreadsheet->getActiveSheet()->setCellValue("L".$rowcont,$row['aadhar_card_no']);
				$spreadsheet->getActiveSheet()->setCellValue("M".$rowcont,$row['esi_no']);//account_no
				$spreadsheet->getActiveSheet()->setCellValue("N".$rowcont,$row['epf_no']);
			    $spreadsheet->getActiveSheet()->setCellValue("O".$rowcont,$row['uan_no']);//account_no
				$sno++;
				$rowcont++;
			}
	
		//	//SetCellFont("A".$rowcont.":BG".$rowcont.,"Arial","10",true,false,"none","000000");
			//$spreadsheet->getActiveSheet()->setCellValue("AD".$rowcont,'=SUM(AD4:AD'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AE".$rowcont,'=SUM(AE4:AE'.($rowcont-1).')');
			//$spreadsheet->getActiveSheet()->setCellValue("AF".$rowcont,'=SUM(AF4:AF'.($rowcont-1).')');
			}
			//SetCellborder("A1","");
			
	}

	function SetBackgroundColor($cells, $color) {
		global $spreadsheet;
		
		
		$spreadsheet->getActiveSheet()->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color);
	}

	function cellcenterv($cells) {
		global $spreadsheet;
	   $spreadsheet->getActiveSheet()->getStyle($cells)->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
	}

function cellcenterh($cells) {
	global $spreadsheet;

	$spreadsheet->getActiveSheet()->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}    
	
	$current_date = date('d-M-Y H:i:s');
	$file_name = "HRMS Pan Card Upload Details ".$region.".xlsx";
	
	
	ob_end_clean();
	$writer = new Xlsx($spreadsheet);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
	ob_end_clean();
	$writer->save("php://output");
			exit;
	
	function convert_numbers($number)
		{
			if (($number < 0) || ($number > 999999999))
			{
			throw new Exception("Number is out of range");
			}
			
			$Cn = floor($number / 10000000);  /* Crores (Hundred Lakhs) */
			$number -= $Cn * 10000000;
			$Ln = floor($number / 100000);  /* Lakhs (Hundred thousand) */
			$number -= $Ln * 100000;
			$kn = floor($number / 1000);     /* Thousands (kilo) */
			$number -= $kn * 1000;
			$Hn = floor($number / 100);      /* Hundreds (hecto) */
			$number -= $Hn * 100;
			$Dn = floor($number / 10);       /* Tens (deca) */
			$n = $number % 10;               /* Ones */
			
			$res = "";
			
			if ($Cn)
			{
			   $res .= convert_numbers($Cn) . " Crore";
			}
			if ($Ln)
			{
			   $res .= convert_numbers($Ln) . " Lakh";
			}
			if ($Gn)
			{
			   $res .= convert_numbers($Gn) . " Million";
			}
			
			if ($kn)
			{
			   $res .= (empty($res) ? "" : " ") .
				   convert_numbers($kn) . " Thousand";
			}
			
			if ($Hn)
			{
			   $res .= (empty($res) ? "" : " ") .
				   convert_numbers($Hn) . " Hundred";
			}
			
			$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
			   "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
			   "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
			   "Nineteen");
			$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
			   "Seventy", "Eigthy", "Ninety");
			
			if ($Dn || $n)
			{
			   if (!empty($res))
			   {
				   $res .= " and ";
			   }
			
			   if ($Dn < 2)
			   {
				   $res .= $ones[$Dn * 10 + $n];
			   }
			   else
			   {
				   $res .= $tens[$Dn];
			
				   if ($n)
				   {
					   $res .= " " . $ones[$n];
				   }
			   }
			}
			
			if (empty($res))
			{
			   $res = "zero";
			}
			
			return $res;
		}	
	
	

?>