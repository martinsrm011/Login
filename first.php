<?php


@session_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
{
	die('This example should only be run from a Web Browser');
}
	

/** Include PHPExcel */
require '../dependencies/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


//$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
//PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

include('../DbConnection/dbConnect.php');;


$user=$_SESSION['lid'];
if($user=='') 
{
	header('location:../login.php');	
}
$region = $_SESSION['region'];
$user_name=$_SESSION['lid'];
//$per = $_SESSION['per'];

$trans_date_c = $_REQUEST['trans_date'];
$trans_date = date('Y-m-d', strtotime($_REQUEST['trans_date']));
$pday = date('Y-m-d', strtotime('-1 day', strtotime($trans_date)));
$cid = $_REQUEST['cust_name'];


$sqlc = "SELECT * FROM cust_details AS a INNER JOIN client_details AS b ON a.client_id=b.client_id WHERE a.cust_id=".$cid." AND a.status='Y' AND b.status='Y'";
$quc=mysqli_query($reportConnection,$sqlc);
$rc=mysqli_fetch_array($quc);


//$dclient_name = $rc['client_name'];
$dclient_id = $rc['client_id']; 
$name = str_replace(" ","_",$rc['client_name']);


//print_r($dclient_id);exit;

$region=$_SESSION['region'];
if($region=='') 
{
	$region=$_COOKIE['region1'];
}
if($region=='') 
{
	header("location:login.php");	
}
$cdate=date("Y-m-d",strtotime($_POST['fund_date']));
$sdate=$_POST['fund_date'];
$cid=$_POST['client_name'];
$reg=explode(",",$region);


$regions=array();
$sqlr="select * from region_master where status='Y'";
$qur=mysqli_query($reportConnection,$sqlr);
while($rr=mysqli_fetch_array($qur))
{
$regions[$rr['region_id']]=$rr['region_name'];
}
$sql_user = mysqli_query($reportConnection,"select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysqli_fetch_array($sql_user);
$login_regoin_exp = explode(',', $res_user['region']);
$login_regoin = $res_user['region'];

//echo json_encode($regions);exit;
// Create new PHPExcel object
$spreadsheet= new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

							 


$spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(0);
$row_no =1;
$alb = 'A';

$dclient_name="Ntex Transportation Day 0 Burial";
$spreadsheet->getActiveSheet()->setCellValue($alb.$row_no,"RADIANT CASH MANAGEMENT SERVICES PVT LTD ");
$spreadsheet->getActiveSheet()->mergeCells($alb.$row_no.':Z'.$row_no); $row_no++;


$spreadsheet->getActiveSheet()->setCellValue($alb.$row_no,"CLIENT NAME: ".$dclient_name);
$spreadsheet->getActiveSheet()->mergeCells($alb.$row_no.':Z'.$row_no); $row_no++;
$spreadsheet->getActiveSheet()->setCellValue($alb.$row_no,"Cash Pickup DATE : ".$trans_date);
$spreadsheet->getActiveSheet()->mergeCells($alb.$row_no.':Z'.$row_no); $row_no++;

function cellcenter($cells) {
    global $spreadsheet;
    $spreadsheet->getActiveSheet()->getStyle($cells)->getAlignment()->applyFromArray(
    array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,)
   );
}
cellcenter('A1:A3');

//$spreadsheet->getActiveSheet()->getStyle('A5:Z5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
//$spreadsheet->getActiveSheet()->getStyle('A5:Z5')->getFill()->getStartColor()->setARGB('1589FF');




$xlscol = 'A';
//$row_no = 4;


$spreadsheet->getActiveSheet()->setCellValue($xlscol.$row_no,"SNo");$xlscol++;
$spreadsheet->getActiveSheet()->setCellValue($xlscol.$row_no,"Customer Name");$xlscol++;                                                                           
foreach($regions as $value)
{
$spreadsheet->getActiveSheet()->setCellValue($xlscol.$row_no,$value);
$xlscol++;
}
$spreadsheet->getActiveSheet()->setCellValue($xlscol.$row_no,"Total");
$row_no++;


//Fill the Collection Details
$xlsRow = 4;
$i = 1;
$total = 0;
$tctotal = 0;
$cctotal = 0;

$sqlc = "SELECT * FROM cust_details WHERE client_id = " . $dclient_id . " AND status = 'Y' and cust_id=".$_REQUEST['cust_name']."";

$accounts = array();
$sqlacc = "SELECT DISTINCT(c.subcustomer_code),c.sol_id  FROM daily_trans AS a INNER JOIN  shop_details AS  c ON c.shop_id = a.pickup_code INNER JOIN cust_details AS d ON  c.cust_id = d.cust_id INNER JOIN location_master  AS e ON c.location=e.loc_id INNER JOIN  radiant_location AS f ON f.location_id=e.loc_id INNER JOIN region_master AS g ON g.region_id=f.region_id WHERE g.region_id IN(".$login_regoin.") AND a.status = 'Y' AND c.status = 'Y' AND e.status ='Y' AND f.status ='Y' AND g.status ='Y' AND d.status = 'Y' AND d.client_id = '$dclient_id' AND  a.type = 'Pickup' AND c.div_code='D+0' AND (c.service_type='Cash Pickup' OR c.service_type='Both')  AND a.pickup_date ='$trans_date'";
//echo $sqlacc;die;
//$sqlacc = "SELECT c.subcustomer_code,c.hierarchy_code  FROM daily_trans AS a INNER JOIN  shop_details AS  c ON c.shop_id = a.pickup_code INNER JOIN cust_details AS d ON  c.cust_id = d.cust_id INNER JOIN location_master  AS e ON c.location=e.loc_id INNER JOIN  radiant_location AS f ON f.location_id=e.loc_id INNER JOIN region_master AS g ON g.region_id=f.region_id WHERE g.region_id IN(".$login_regoin.") AND a.status = 'Y' AND c.status = 'Y' AND e.status ='Y' AND f.status ='Y' AND g.status ='Y' AND d.status = 'Y' AND c.cust_id = '$cust_id' AND  a.type = 'Pickup' AND  (c.service_type='Cash Pickup' OR c.service_type='Both')  AND a.pickup_date ='$newDate'";
//echo $sqlacc;die;
$quacc = mysqli_query($reportConnection,$sqlacc);
$nacc = mysqli_num_rows($quacc);

if($nacc >0)
{   while($racc = mysqli_fetch_array($quacc))
    {
    if(strpos($racc['subcustomer_code'],',')!==False)
	{
		$subcustomercode = explode(',',$racc['subcustomer_code']);
       // print_r($subcustomercode);die;

		$bankname = explode(',',$racc['sol_id']);
		for($i=0;$i<count($subcustomercode);$i++)
		{
		//$accounts[$racc['subcustomer_code']]['accountnumber']=$subcustomercode;
		//$accounts[$racc['subcustomer_code']]['bankname'] = $bankname;
		$accounts[$racc['subcustomer_code']]['accountnumber']=$subcustomercode[$i];
        $accounts[$racc['subcustomer_code']]['bankname'] = $bankname[$i];
        		//print_r($accounts);die;
      //  $acc[] = $subcustomercode[$i];
		
		}
		//print_r($acc);die;
	  
	}
	else{
    $accounts[$racc['subcustomer_code']]['accountnumber']=$racc['subcustomer_code'];
    $accounts[$racc['subcustomer_code']]['bankname'] = $racc['sol_id'];
	    }
    }
}
$accounts = array_unique($accounts, SORT_REGULAR);

$totalcash = array();
//print_r($accounts);die;
//$account = array_filter($accounts);
//print_r($account);die;

//echo $sqlc;exit;
//new //
$i=1;
$col = 'A';
$rowno = 4;

$spreadsheet->getActiveSheet()->setCellValue($col.$rowno,'Sl No');$col++;
$spreadsheet->getActiveSheet()->setCellValue($col.$rowno,'Customer Name');$col++;
foreach($regions as $val)
{
    $spreadsheet->getActiveSheet()->setCellValue($col.$rowno,$val);
    $col++;
}
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':'.$col.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':'.$col.$rowno.'')->getFill()->getStartColor()->setARGB('ffff99');
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':'.$col.$rowno.'')->getFont()->setBold( true );
$spreadsheet->getActiveSheet()->setCellValue($col.$rowno,'Total');
    $rowno++;
   // $rowno = 6;
    $strow = $rowno;
    foreach($accounts AS $accno  =>$accval)
{
   // print_r($accval);die;  
    $sqlc = "SELECT * FROM cust_details WHERE cust_id = '8635' AND status ='Y'";
    $quc = mysqli_query($reportConnection,$sqlc);
    while($rc =  mysqli_fetch_array($quc))
    {   $custname = $rc["cust_name"];
        $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$i);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Ntex Transportation Day 0 - Burial '.$accval['bankname'].' ('.$accval['accountnumber'].')'.' FUNDING');

       // $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,$rc["cust_name"].'('.$accval.')'.$accno);
        $alb = 'C'; 
        foreach($regions as $key =>$value) 
        {   $total = 0;
            $sqlpc =  "SELECT DISTINCT(daily_trans.trans_id),sum(pick_amount) as total FROM daily_collection INNER JOIN 
            daily_trans  ON  daily_collection.trans_id = daily_trans.trans_id  
            INNER JOIN  shop_details ON daily_trans.pickup_code =  shop_details.shop_id 
			INNER JOIN cust_details ON shop_details.cust_id = cust_details.cust_id
            INNER JOIN radiant_location ON shop_details.location = radiant_location.location_id
            INNER JOIN  region_master ON radiant_location.region_id = region_master.region_id
            WHERE  daily_collection.dep_type1 = 'Burial' AND daily_trans.pickup_date ='".$trans_date."' AND 
            region_master.region_id ='".$key."' AND $key IN(".$login_regoin.")  AND shop_details.div_code ='D+0' AND 
            cust_details.client_id = '".$dclient_id."' AND  shop_details.subcustomer_code = '".$accval['accountnumber']."' AND 
            daily_trans.status = 'Y' AND
            daily_collection.status = 'Y' AND shop_details.status ='Y' AND radiant_location.status ='Y' 
            AND region_master.status ='Y'";
            //echo $sqlpc;die;
            $qupc  = mysqli_query($reportConnection,$sqlpc);
            while($rpc=mysqli_fetch_array($qupc))
            {
               // print_r($rpc);die;
                $total = $rpc["total"];
                //$spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,$total);

            }
            if($total == "")
            {
                $total = 0;
                //$spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,$total);

            }
            $ptotal +=$total;
            $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,$total);
            $alb++;
        }   
           // $endalb = chr(ord($alb) - 1);

            $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'=SUM(C'.$rowno.':Y'.$rowno.')');
            $totalcash[] = $rowno;
 
           // $lastalb = $alb;
            $i++;
            $rowno++;

    }
           // print_r($totalcash);die;


    //$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':'.$lastalb.$rowno.'')->getFont()->setBold( true );
    //$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':'.$lastalb.$rowno.'')->getFont()->setBold( true );
    $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,"");
    $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Burial Total');
    /* total burial */
    $alb = 'C';
    foreach($regions as $key =>$value) 
    {   $btotal = 0;
        $sqlbt =  "SELECT  DISTINCT(daily_trans.trans_id),sum(pick_amount) as total FROM daily_collection INNER JOIN 
        daily_trans  ON  daily_collection.trans_id = daily_trans.trans_id  
        INNER JOIN  shop_details ON daily_trans.pickup_code =  shop_details.shop_id 
		INNER JOIN cust_details ON shop_details.cust_id = cust_details.cust_id
        INNER JOIN radiant_location ON shop_details.location = radiant_location.location_id
        INNER JOIN  region_master ON radiant_location.region_id = region_master.region_id
        WHERE  daily_collection.dep_type1 = 'Burial' AND daily_trans.pickup_date ='".$trans_date."' AND 
        region_master.region_id ='".$key."' AND $key IN(".$login_regoin.") AND  cust_details.client_id = '".$dclient_id."' AND shop_details.subcustomer_code = '".$accval['accountnumber']."' AND 
        daily_trans.status = 'Y' AND shop_details.div_code='D+0' AND 
        daily_collection.status = 'Y' AND shop_details.status ='Y' AND radiant_location.status ='Y'
        AND region_master.status ='Y'";
        $qubt  = mysqli_query($reportConnection,$sqlbt);
        while($rbt=mysqli_fetch_array($qubt))
        {
            $btotal = $rbt["total"];
        }
        if($btotal == "")
        {
            $btotal = 0;
        }
        $bgtotal +=$btotal; 
        
        $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'=SUM('.$alb.$strow.':'.$alb.($rowno-1).')');

        $alb++;
    } 
  
        $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'=SUM('.$alb.$strow.':'.$alb.($rowno-1).')');
       
}   
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );

$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('99CCFF'); 
        $rowno +=3;
   /* total burial */
/* client bank */
/* client bank */
$lastalb = 'Z';
//$lastalb = chr(ord($alb)-1);    
/*$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'');
$spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Partner Bank Deposit amount');
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('00CD00');
$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );



$rowno++;
$i = 1;

$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$i);
$spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,$custname);
$alb = 'C';
foreach($regions as $key =>$value)
{
    $sqlpb =  "SELECT DISTINCT(daily_trans.trans_id),sum(pick_amount) as total FROM daily_collection INNER JOIN 
    daily_trans  ON  daily_collection.trans_id = daily_trans.trans_id  
    INNER JOIN  shop_details ON daily_trans.pickup_code =  shop_details.shop_id 
    INNER JOIN radiant_location ON shop_details.location = radiant_location.location_id
    INNER JOIN  region_master ON radiant_location.region_id = region_master.region_id
    WHERE  daily_collection.dep_type1 = 'Partner Bank' AND daily_trans.pickup_date ='".$newDate."' AND 
    region_master.region_id ='".$key."' AND $key IN(".$login_regoin.") AND 
    shop_details.cust_id = '".$cust_id."' AND 
    daily_trans.status = 'Y' AND
    daily_collection.status = 'Y' AND shop_details.status ='Y' AND radiant_location.status ='Y'
    AND region_master.status ='Y'";
    
    
    $qupb = mysqli_query($sqlpb);
    while($rpb = mysqli_fetch_array($qupb))
    {
        $pbtotal = $rpb["total"];
    }
    if($pbtotal == "")
    {
        $pbtotal = 0;
    }
    $gpbtotal+= $pbtotal;
    $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,$pbtotal);
    $alb++;

}
  // $endalb = ord(chr($alb-1));   
$spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'');
$rowno++; 
/* total clientbank deposit 
$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,"");
$spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Total partner bank deposit');
$alb = 'C';
foreach($regions as $key =>$value)
{   
    $sqlgpb =  "SELECT  DISTINCT(daily_trans.trans_id),sum(pick_amount) as total FROM daily_collection INNER JOIN 
    daily_trans  ON  daily_collection.trans_id = daily_trans.trans_id  
    INNER JOIN  shop_details ON daily_trans.pickup_code =  shop_details.shop_id 
    INNER JOIN radiant_location ON shop_details.location = radiant_location.location_id
    INNER JOIN  region_master ON radiant_location.region_id = region_master.region_id
    WHERE  daily_collection.dep_type1 = 'Partner Bank' AND daily_trans.pickup_date ='".$newDate."' AND 
    region_master.region_id ='".$key."' AND $key IN(".$login_regoin.") AND  shop_details.cust_id = '".$cust_id."' AND 
    daily_trans.status = 'Y' AND
    daily_collection.status = 'Y' AND shop_details.status ='Y' AND radiant_location.status ='Y'
    AND region_master.status ='Y'";
   
    $qugpb = mysqli_query($sqlgpb);
    while($rgpb = mysqli_fetch_array($qugpb))
    {
        $gpbtotal = $rgpb["total"];
    }
    if($gpbtotal == "")
    {
        $gpbtotal = 0;
    }
    $gtpb += $gpbtotal;

    
    $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'=SUM('.$alb.($rowno-1).')');
    $alb++;

} 
   $spreadsheet->getActiveSheet()->setCellValue($alb.$rowno,'');
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('99CCFF');
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );
  
   $rowno+=2;
   

/* total clientbank deposit */
/* amendments  */
  /* $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Amendments:'.$trans_date);  
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('00CD00');
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );
    
   $rowno++;
   $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );

   $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Sl No');
   $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Customer Name');
   $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,'Location');
   $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,'Client Code');
   $spreadsheet->getActiveSheet()->setCellValue('E'.$rowno,'Date');
   $spreadsheet->getActiveSheet()->setCellValue('F'.$rowno,'Reported Reciept');
   $spreadsheet->getActiveSheet()->setCellValue('G'.$rowno,'Reported Amount');
   $spreadsheet->getActiveSheet()->setCellValue('H'.$rowno,'Corrected Reciept');
   $spreadsheet->getActiveSheet()->setCellValue('I'.$rowno,'Corrected Amount');
   $spreadsheet->getActiveSheet()->setCellValue('J'.$rowno,'Difference in amount');
   $spreadsheet->getActiveSheet()->setCellValue('K'.$rowno,'Remarks'); 
   $spreadsheet->getActiveSheet()->setCellValue('L'.$rowno,'Total Amount Funded'); 
   $rowno++;
   $i = 1; */
   foreach($accounts as $key=>$value)
   {
	$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Amendments:'.$trans_date.' ( '.$value['bankname'].' ('.$value['accountnumber'].') FUNDING LOCATIONS)');
	//$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Sl No');
    $spreadsheet->getActiveSheet()->mergeCells('A'.$rowno.':D'.$rowno);
    //$spreadsheet->getActiveSheet()->mergeCells('A2:D2');
    //$spreadsheet->getActiveSheet()->mergeCells('A3:D3');
  
	$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('00CD00');
	$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );
	 
	$rowno++;
	$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );
 
	$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Sl No');
	$spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Customer Name');
	$spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,'Location');
	$spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,'Client Code');
	$spreadsheet->getActiveSheet()->setCellValue('E'.$rowno,'Date');
	$spreadsheet->getActiveSheet()->setCellValue('F'.$rowno,'Reported Reciept');
	$spreadsheet->getActiveSheet()->setCellValue('G'.$rowno,'Reported Amount');
	$spreadsheet->getActiveSheet()->setCellValue('H'.$rowno,'Corrected Reciept');
	$spreadsheet->getActiveSheet()->setCellValue('I'.$rowno,'Corrected Amount');
	$spreadsheet->getActiveSheet()->setCellValue('J'.$rowno,'Difference in amount');
	$spreadsheet->getActiveSheet()->setCellValue('K'.$rowno,'Funding Date');
	$spreadsheet->getActiveSheet()->setCellValue('L'.$rowno,'Remarks'); 
	$spreadsheet->getActiveSheet()->setCellValue('M'.$rowno,'Total Amount Funded'); 
	$rowno++;
	$i = 1;
	  
$sqlamd = "SELECT  tr.trans_id as tr_transid,amd_tr.trans_id as amd_transid,amd_tr.t_rec, 
col.multi_rec,cust_details.cust_name,tr.location,shop_details.customer_code,col.hcl_no as reprec,col_amend.hcl_no as corrrec,tr.pickup_date,amd_tr.amend_amount,
amd_tr.fund_amount,amd_tr.prev_amount,amd_tr.fund_amount,amd_tr.diff_amount,amd_tr.fund_date,
amd_tr.fund_remarks  from daily_trans tr
inner join daily_collection col on tr.trans_id=col.trans_id left join daily_amends amd_tr
on tr.trans_id =amd_tr.trans_id  inner join daily_collection_amend 
col_amend on amd_tr.trans_id=col_amend.trans_id    inner join 
shop_details on tr.pickup_code = shop_details.shop_id inner join 
location_master on shop_details.location=location_master.loc_id inner join cust_details on 
shop_details.cust_id=cust_details.cust_id  inner join region_master on tr.region = region_master.region_name 
where region_master.region_id IN(".$login_regoin.") AND amd_tr.fund_date='".$trans_date."' and shop_details.subcustomer_code='".$value['accountnumber']."' and 
cust_details.client_id='".$dclient_id."' and shop_details.div_code='D+0' and amd_tr.status='Y'  and col.status = 'Y' and  tr.status='Y' and shop_details.status='Y' and location_master.status='Y' and cust_details.status='Y'";
//echo $sqlamd;die;
   $quamd = mysqli_query($reportConnection,$sqlamd);
   $namd = mysqli_num_rows($quamd);
   $tamendamount = 0;
   $grandtotalamount = 0;
   $reprec = 0;
   $corrrec = 0;
  // $i = 1;

  
   if($namd >0)
   {      while($ramd = mysqli_fetch_array($quamd))
            { 
              // print_r($ramd);die;
             //  if($ramd['multi_rec'] == 'Y')
              // {
               // $amdtransid[] = 0;
                if($ramd['tr_transid'] !='' && $ramd['amd_transid'] !=''){


                  if($ramd['multi_rec'] == 'Y'){
                        
                    $sqlmulamd = "SELECT  *  FROM  daily_collectionmul_amend WHERE trans_id = ".$ramd['amd_transid']." AND  rec_id =".$ramd['t_rec']." AND  status = 'Y'";
                    //echo $sqlmulamd;die;
                    $sqlmul = "SELECT * FROM daily_collectionmul WHERE trans_id = ".$ramd['amd_transid']."  AND rec_id =".$ramd['t_rec']." AND  status = 'Y'"; 
                    $qumul = mysqli_query($reportConnection,$sqlmul);
                    $resmul = mysqli_fetch_array($qumul);
                     //echo $sqlmul; die;
                    $qumulamd = mysqli_query($reportConnection,$sqlmulamd);
                   // $amdtransid = $ramd['amd_transid'];
                      while($resmulamd = mysqli_fetch_array($qumulamd))
                      {
                       // print_r($resmulamd);die;
                      // this one daily_collectionmul_amend receipt
                      $tamendamount+=$ramd['amend_amount'];
					  $tprevamount+=$ramd['prev_amount'];
					  $tdiffamount+=$ramd['diff_amount'];
					  $tfundamount+=$ramd['fund_amount'];
                      $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$i);
                      $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Ntex Transportation Day 0 - Burial '.$value['bankname'].' '.$value['accountnumber']);
                      $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,$ramd['location']);
                      $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,$ramd['customer_code']);
                      $spreadsheet->getActiveSheet()->setCellValue('E'.$rowno,date("d-m-Y", strtotime($ramd['pickup_date'])));
                      $spreadsheet->getActiveSheet()->setCellValue('F'.$rowno,$resmul['hcl_no']);
                      $spreadsheet->getActiveSheet()->setCellValue('G'.$rowno,$ramd['prev_amount']);
                      $spreadsheet->getActiveSheet()->setCellValue('H'.$rowno,$resmulamd['hcl_no']);
                      $spreadsheet->getActiveSheet()->setCellValue('I'.$rowno,$ramd['amend_amount']);
                      $spreadsheet->getActiveSheet()->setCellValue('J'.$rowno,$ramd['diff_amount']);
					  $spreadsheet->getActiveSheet()->setCellValue('K'.$rowno,$ramd['fund_date']);

                      $spreadsheet->getActiveSheet()->setCellValue('L'.$rowno,$ramd['fund_remarks']);
                      $spreadsheet->getActiveSheet()->setCellValue('M'.$rowno,$ramd['fund_amount']);
                      $rowno++; 
                      $i++;
                      }
                    }
					// multiple receipt end //
                    else{
                      //this one for single daily_collection_amend receipt
                      $tamendamount+=$ramd['amend_amount'];
					  $tprevamount+=$ramd['prev_amount'];
					  $tdiffamount+=$ramd['diff_amount'];
					  $tfundamount+=$ramd['fund_amount'];
                     
                      $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$i);
                      $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Ntex Transportation Day 0 - Burial '.$value['bankname'].' '.$value['accountnumber']);
                      $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,$ramd['location']);
                      $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,$ramd['customer_code']);
                      $spreadsheet->getActiveSheet()->setCellValue('E'.$rowno,date("d-m-Y", strtotime($ramd['pickup_date'])));
                      $spreadsheet->getActiveSheet()->setCellValue('F'.$rowno,$ramd['reprec']);
                      $spreadsheet->getActiveSheet()->setCellValue('G'.$rowno,$ramd['prev_amount']);
                      $spreadsheet->getActiveSheet()->setCellValue('H'.$rowno,$ramd['corrrec']);
                      $spreadsheet->getActiveSheet()->setCellValue('I'.$rowno,$ramd['amend_amount']);
                      $spreadsheet->getActiveSheet()->setCellValue('J'.$rowno,$ramd['diff_amount']);
					  $spreadsheet->getActiveSheet()->setCellValue('K'.$rowno,date("d-m-Y",strtotime($ramd['fund_date'])));
                      $spreadsheet->getActiveSheet()->setCellValue('L'.$rowno,$ramd['fund_remarks']);
                      $spreadsheet->getActiveSheet()->setCellValue('M'.$rowno,$ramd['fund_amount']);
                      $rowno++;
                      $i++;
                    }
					//single recipt end //
                }
            }
			$spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':M'.$rowno)->getFont()->setBold( true );

			$spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Total Amendments Amount');
			$spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('E'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('F'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('G'.$rowno,$tprevamount);
			$spreadsheet->getActiveSheet()->setCellValue('H'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('I'.$rowno,$tamendamount);
			$spreadsheet->getActiveSheet()->setCellValue('J'.$rowno,$tdiffamount);
			$spreadsheet->getActiveSheet()->setCellValue('K'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('L'.$rowno,"");
			$spreadsheet->getActiveSheet()->setCellValue('M'.$rowno,$tfundamount);
		   $rowno++;

        } 
		else{
         
		  $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'No Amendments found');
		  $rowno++;
 
		}
    
			
}
   /* if($namd = 0)
    {
        $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'no amendments found');
  
    } */
     //print_r($trtransid);die;
   $rowno = $rowno+2;
/* amendments */ 
/* funding summary */
  $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'Funding Summary'); 
  $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
  $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFill()->getStartColor()->setARGB('00CD00');
  $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );
  $rowno++;
  $spreadsheet->getActiveSheet()->getStyle('A'.$rowno.':Z'.$rowno.'')->getFont()->setBold( true );

  $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,'SNo'); 
  $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Item'); 
  $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,'Amount'); 
  $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,'Remarks'); 
  $rowno++;


  $index = 1;
  $ind = array();
 // $totalamendment = array();
 foreach($accounts as $accno =>$accval)
  {
    $sqltamd = "SELECT  SUM(amd_tr.diff_amount) As totalamendment
    
    
    from daily_trans tr
    inner join daily_collection col on tr.trans_id=col.trans_id left join daily_amends amd_tr
    on tr.trans_id =amd_tr.trans_id  inner join daily_collection_amend 
    col_amend on amd_tr.trans_id=col_amend.trans_id    inner join 
    shop_details on tr.pickup_code = shop_details.shop_id inner join 
    location_master on shop_details.location=location_master.loc_id inner join cust_details on 
    shop_details.cust_id=cust_details.cust_id  inner join region_master on tr.region = region_master.region_name 
    where region_master.region_id IN(".$login_regoin.") AND amd_tr.fund_date='".$trans_date."' and shop_details.subcustomer_code='".$accval['accountnumber']."' and 
    cust_details.client_id='".$dclient_id."' and amd_tr.status='Y'  and col.status = 'Y' and  tr.status='Y' and shop_details.status='Y' and location_master.status='Y' and cust_details.status='Y'";
   // echo $sqltamd;die;
    $sqltamdex = mysqli_query($reportConnection,$sqltamd);
    if(mysqli_num_rows($sqltamdex)>0 )
    {
    $rsqltamdex = mysqli_fetch_array($sqltamdex);

    //$totalamendment[] = array('Total amendment'=>$rsqltamdex ['totalamendment']);

    }
   // print_r($totalamendment);die;
    //echo $totalamendment;die;
   $ind[] = array("Total Cash" =>"Total Cash Collection (".$accval['accountnumber'].") ".$accval['bankname']." A/C Funding","Amount"=>"Amount","Remarks"=>"","Amendment"=>"Amendments Amount (".$accval['bankname'].") ".$accval['accountnumber'],"Total Amount"=>"Total Amount to be funded ","Total Amendment"=>$rsqltamdex ['totalamendment']);
   // print_r($ind);die;
  }
    $index =1;
   if(count($ind) >0) 
   {
    for($i =0;$i<count($ind);$i++)  
    {
      $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$index); 
 
      $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,$ind[$i]['Total Cash']); 
      $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,'=Z'.$strow); 
      $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,''); 
      //$strow++;
      $rowno++; 
      $index++;
      $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$index); 

      $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,$ind[$i]['Amendment']); 
      $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,!empty($ind[$i]['Total Amendment'])?$ind[$i]['Total Amendment']:"0"); 
      $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,' '); 
     // $strow++;
      $rowno++; 
      $index++;
      $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$index); 
      $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,$ind[$i]['Total Amount'] .($index-2).'+'.($index-1)); 
      //'=SUM(C'.$row_no.':X'.$row_no.')'

      $spreadsheet->getActiveSheet()->setCellValue('C'.$rowno,'=SUM(C'.($rowno-2).':C'.($rowno-1).')'); 
      $spreadsheet->getActiveSheet()->setCellValue('D'.$rowno,' '); 
      $tfrowno[] = $rowno;
      //print_r($tfrowno);die;
      //echo $tfrowno;die;
      $strow++;
      $rowno++;
      $index++;
    }
    //print_r($tfrowno);die;
    $spreadsheet->getActiveSheet()->setCellValue('A'.$rowno,$index); 
    $spreadsheet->getActiveSheet()->setCellValue('B'.$rowno,'Total amount to be funded'); 
   }
    $alb = array('C');
foreach($alb as $key=>$val){
    if(is_array($tfrowno) && count($tfrowno) > 0)
    {
        foreach($tfrowno as $value) {
            //print_r($value);
            $load_result.= $val.$value.',';
        }
    $load_result = substr($load_result, 0, -1);
    $spreadsheet->getActiveSheet()->setCellValue($val.$rowno, '=SUM('.$load_result.')');
    $load_result = '';
}
}
  
  

  
  
  for ($i=4; $i <=$rowno ; $i++) { 
    $spreadsheet->getActiveSheet()->getStyle('A'.$i.':Z'.$i)->getBorders()->
    getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}
/* funding summary */
//-----------------excelheader-------------------------------//     
//$spreadsheet->getActiveSheet()->setCellValue('A1','RADIANT CASH MANAGEMENT SERVICES LTD');
//$spreadsheet->getActiveSheet()->setCellValue('A2','CLIENT NAME:NtexTransportation Day0 Burial');
//$spreadsheet->getActiveSheet()->setCellValue('A3','Cash Pickup Date:'.$newDate);

//-----------------excelheader(end)-------------------------------//        
$spreadsheet->getActiveSheet()->mergeCells('A1:Z1');
$spreadsheet->getActiveSheet()->mergeCells('A2:Z2');
$spreadsheet->getActiveSheet()->mergeCells('A3:Z3');
//$spreadsheet->getActiveSheet()->getStyle('A4:AJ4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);

//new //


$spreadsheet->getActiveSheet()->setTitle("Funding Summary");


$spreadsheet->setActiveSheetIndex(0);
$spreadsheet->createSheet();			 


$spreadsheet->setActiveSheetIndex(1);
// Add some data
$sheet=$spreadsheet->getActiveSheet();

include('excel_header.php');

//Zone Details
$sql_zone = mysqli_query($reportConnection,"SELECT zone_id, zone_name FROM zone_master WHERE status='Y'");
while($res_zone = mysqli_fetch_assoc($sql_zone)) 
{
	$zone[$res_zone['zone_id']] = $res_zone['zone_name'];
}
//Account Details
$sql_acc = "SELECT acc_id, branch_name,account_no,bank_name, bank_type FROM bank_master WHERE status='Y'";
$qu_acc = mysqli_query($reportConnection,$sql_acc);
while($r_acc = mysqli_fetch_array($qu_acc)) 
{
	$dep_branchs[$r_acc['acc_id']]['branch_name'] =	$r_acc['branch_name'];
	$dep_branchs[$r_acc['acc_id']]['account_no'] =	$r_acc['account_no'];
	$dep_branchs[$r_acc['acc_id']]['bank_name'] =	$r_acc['bank_name'];
	$dep_branchs[$r_acc['acc_id']]['bank_type'] =	$r_acc['bank_type'];
}

//All Bank Details
$sql_bank = "SELECT bank_code, bank_name FROM all_banks WHERE status='Y'";
$qu_bank = mysqli_query($reportConnection,$sql_bank);
while($res_bank = mysqli_fetch_assoc($qu_bank)) 
{
	$bank_all[$res_bank['bank_name']] = $res_bank['bank_code']	;
}



$row_no = 4;
$start_rows = 5;
$calc = 4;
$gtotal = 0;
$report_type = 0;

$alb_a = range('A', 'Z');
$alb_b = range('A', 'Z');
foreach($alb_b as $val2) {
	$alb_b1[]='A'.$val2;
}
$alb_c = array_merge($alb_a,$alb_b1);

$cllor_title = 'da9694';
$cllor_reg = '00FFFF';
$cllor_gtotal = '92D050';


$alb = array();
//Title
	$cllor_title = '7867d3';
	$cllor_reg = '28edff';
	$cllor_gtotal = '009dd3';
	$end_filed = 'AA';

	
	$title = array('SL.No','Pickup Date','Customer Name','Customer Code','Region','Location','Pickup Point','Arrangement','Cash Limit','Funding Account details ( 007405006646 / 007405501932 )','Radiant Receipt / HCI Slip No','Pickup Amount','Burial deposit Amount','2000','500','200','100','50','20','10','5','Others','Total','Denominations (Diff)','Pickup Point Code','Remarks','Point ID');


if(!empty($title)) {
	foreach($title as $key3=>$val3) {
		$spreadsheet->getActiveSheet()->setCellValue($alb_c[$key3].$row_no, $val3);
	}
}

$row_no++;
$start_rows = 5;


	$sql="SELECT a.trans_no,a.pickup_date,a.trans_id, a.pickup_amount, b.pickup_type, a.`type`, 
	b.point_type, b.shop_id,b.customer_code, b.loc_code, b.div_code, b.shop_name, b.address, 
	b.shop_code, b.shop_id, b.subcustomer_code, b.hierarchy_code, b.sol_id, b.cash_limit, b.acc_id, 
	b.point_type, b.dep_bank, b.loi_date, c.cust_name, d.location, f.region_name, f.zone_id  FROM
	daily_trans AS a  INNER JOIN  shop_details AS b ON a.pickup_code=b.shop_id INNER JOIN
	 cust_details AS c ON b.cust_id=c.cust_id  INNER JOIN location_master  AS d ON 
	 b.location=d.loc_id INNER JOIN  radiant_location AS e ON e.location_id=d.loc_id INNER JOIN 
	 region_master AS f ON e.region_id=f.region_id WHERE a.pickup_date='".$trans_date."' AND c.client_id='".$dclient_id."' AND (b.service_type='Cash Pickup' OR b.service_type='Both') AND type='Pickup' and b.div_code='D+0' AND b.dep_bank='Burial'  AND a.`status`='Y' AND b.`status`='Y' AND c.`status`='Y' AND e.status='Y' AND f.`status`='Y'  ORDER BY f.region_name, c.cust_name ,d.location";	
	

$result=mysqli_query($reportConnection,$sql);
$n=mysqli_num_rows($result);
$i = 1;
if($n>0) {
	$start = $row_no;
	while($r=mysqli_fetch_array($result)) {
		
		$loi_date = '-';
		
		$pickup_amount	=	!empty($r['pickup_amount'])?$r['pickup_amount']:"0";
		$pickup_type	=	!empty($r['pickup_type'])?$r['pickup_type']:"";
		
		
		if($r['loi_date']!='0000-00-00') {
			$loi_date 	=	!empty($r['loi_date'])?date('d-m-Y', strtotime($r['loi_date'])):"";	
		}		
		if($r['inactive_date']!='0000-00-00') {
			$inactive_date 	=	!empty($r['inactive_date'])?date('d-m-Y', strtotime($r['inactive_date'])):"";	
		}		
		if($r['reactivation_date']!='0000-00-00') {
			$reactivation_date 	=	!empty($r['reactivation_date'])?date('d-m-Y', strtotime($r['reactivation_date'])):"";	
		}		
		$trans[$r['trans_id']]['cust_id_cu'] = !empty($r['cust_id'])?$r['cust_id']:"";
		$trans[$r['trans_id']]['shop_id'] = !empty($r['shop_id'])?$r['shop_id']:"-";
		$trans[$r['trans_id']]['trans_no'] = !empty($r['trans_no'])?$r['trans_no']:"-";
		$trans[$r['trans_id']]['pickup_amount'] = !empty($r['pickup_amount'])?$r['pickup_amount']:"0";
		$trans[$r['trans_id']]['region_name'] = !empty($r['region_name'])?$r['region_name']:"";
		$trans[$r['trans_id']]['location'] = !empty($r['location'])?$r['location']:"-"; 
		$trans[$r['trans_id']]['loc_code'] = !empty($r['loc_code'])?$r['loc_code']:"-";
		$trans[$r['trans_id']]['div_code'] = !empty($r['div_code'])?$r['div_code']:"-";
		$trans[$r['trans_id']]['customer_code'] = !empty($r['customer_code'])?$r['customer_code']:"-";
		$trans[$r['trans_id']]['c_code'] = !empty($r['customer_code'])?$r['customer_code']:"-";

		$trans[$r['trans_id']]['shop_code'] = !empty($r['shop_code'])?$r['shop_code']:"-";
		$trans[$r['trans_id']]['cust_name'] = !empty($r['cust_name'])?$r['cust_name']:"";
		$trans[$r['trans_id']]['shop_name'] = !empty($r['shop_name'])?$r['shop_name']:"";
		$trans[$r['trans_id']]['address'] = !empty($r['address'])?$r['address']:"-";
		$trans[$r['trans_id']]['sol_id'] = !empty($r['sol_id'])?$r['sol_id']:"-";
		$trans[$r['trans_id']]['subcustomer_code'] = !empty($r['subcustomer_code'])?$r['subcustomer_code']:"-";
		$trans[$r['trans_id']]['hierarchy_code'] = !empty($r['hierarchy_code'])?$r['hierarchy_code']:"-";
		$trans[$r['trans_id']]['cash_limit'] = !empty($r['cash_limit'])?$r['cash_limit']:"0";
		$trans[$r['trans_id']]['zone_name'] = !empty($r['zone_id'])?$zone[$r['zone_id']]:"";
		$trans[$r['trans_id']]['pickup_type'] = !empty($r['pickup_type'])?$r['pickup_type']:"-";
		$trans[$r['trans_id']]['shop_acc_id'] = !empty($r['acc_id'])?$r['acc_id']:"-";
		$trans[$r['trans_id']]['point_type'] = !empty($r['point_type'])?$r['point_type']:"-";
		$trans[$r['trans_id']]['s_dep_bank'] = !empty($r['dep_bank'])?$r['dep_bank']:"-";
		$trans[$r['trans_id']]['shop_remark'] = !empty($r['remarks'])?$r['remarks']:"-";
		$trans[$r['trans_id']]['pickup_date'] = !empty($r['pickup_date'])?$r['pickup_date']:"-";
		$trans[$r['trans_id']]['remark'] = 'Report Awating';
		$trans[$r['trans_id']]['pick_amount'] = "0";
		$trans[$r['trans_id']]['2000s'] = "0";
		$trans[$r['trans_id']]['1000s'] = "0";
		$trans[$r['trans_id']]['500s'] = "0";
		$trans[$r['trans_id']]['100s'] = "0";
		$trans[$r['trans_id']]['200s'] = "0";
		$trans[$r['trans_id']]['50s'] = "0";
		$trans[$r['trans_id']]['20s'] = "0";
		$trans[$r['trans_id']]['10s'] = "0";
		$trans[$r['trans_id']]['5s'] = "0";
		$trans[$r['trans_id']]['coins'] = "0";
		$trans[$r['trans_id']]['o1000s'] = "0";
		$trans[$r['trans_id']]['o500s'] = "0";
		$trans[$r['trans_id']]['dep_amount2'] = "0";
		$trans[$r['trans_id']]['client_amt'] = "0";
		$trans[$r['trans_id']]['burial_amt'] = "0";
		$trans[$r['trans_id']]['partner_amt'] = "0";
		$trans[$r['trans_id']]['valut_amt'] = "0";	
		$trans1[$r['shop_id']]['prev_valut_amt'] = '0';
		$trans1[$r['trans_id']]['denominationDiff'] = '0';
		
		$trans[$r['trans_id']]['loi_date'] = $loi_date;
		$trans[$r['trans_id']]['inactive_date'] = $inactive_date;
		$trans[$r['trans_id']]['reactivation_date'] = $reactivation_date;
		
		if($r['point_type']=='Inactive' || $r['point_type']=='Lost') 
		{
			$trans[$r['trans_id']]['remark'] = 'Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';
			$trans[$r['trans_id']]['remarks'] = 'Point Inactive for a long period and in case the transaction takes place for this point the ERP will automatically report the same';;
		}
		else 
		{
			if($pickup_type=='Request' && $pickup_amount==0) 
			{
				$trans[$r['trans_id']]['remarks'] = 'No Request';
				$trans[$r['trans_id']]['remark'] = 'No Request';
			}
			else 
			{
				$trans[$r['trans_id']]['remarks'] = 'Report Awating';
				$trans[$r['trans_id']]['remark'] = 'Report Awating';
			}
		}
		$trans_id[] = $r['trans_id'];
		$shop_id[] = $r['shop_id'];
		
	}
}

if(!empty($trans_id)) 
{
	$cu_trans_id = implode(',',$trans_id);
	
	//Daily Collection 
	$sql2="SELECT * FROM daily_collection WHERE trans_id IN (".$cu_trans_id.") AND status='Y' ";
	
	$qu2=mysqli_query($reportConnection,$sql2);
	$n2=mysqli_num_rows($qu2);
	while($r2=mysqli_fetch_array($qu2)) 
	{
		
		$agency_code = "NIL";
		$dbank	=	"";
		
		$pick_amount = $dep_amount = $client_amt = $burial_amt = $partner_amt = $valut_amt = $prev_vault = $kotak_dep = 0;
		
		$coll_remarks=	!empty($r2['coll_remarks'])?$r2['coll_remarks']:"";
	  	$other_remarks=	!empty($r2['other_remarks'])?$r2['other_remarks']:"";
		$dep_type1	=   !empty($r2['dep_type1'])?$r2['dep_type1']:"1";
		$dep_amount 	=	!empty($r2['dep_amount2'])?$r2['dep_amount2']:"0";
		$pick_amount	=	!empty($r2['pick_amount'])?$r2['pick_amount']:"0";
		
		$trans[$r2['trans_id']]['entry'] = '1';
		$trans[$r2['trans_id']]['multi_rec'] = !empty($r2['multi_rec'])?$r2['multi_rec']:"";
		$trans[$r2['trans_id']]['rec_no'] = !empty($r2['rec_no'])?$r2['rec_no']:"0";
		$trans[$r2['trans_id']]['pis_hcl_no'] = !empty($r2['pis_hcl_no'])?$r2['pis_hcl_no']:"0";
		$trans[$r2['trans_id']]['hcl_no'] = !empty($r2['hcl_no'])?$r2['hcl_no']:"0";
		$trans[$r2['trans_id']]['cust_field1'] = !empty($r2['cust_field1'])?$r2['cust_field1']:"0";
		$trans[$r2['trans_id']]['cust_field2'] = !empty($r2['cust_field2'])?$r2['cust_field2']:"0";
		$trans[$r2['trans_id']]['cust_field3'] = !empty($r2['cust_field3'])?$r2['cust_field3']:"0";
		$trans[$r2['trans_id']]['cust_field4'] = !empty($r2['cust_field4'])?$r2['cust_field4']:"0";
		$trans[$r2['trans_id']]['cust_field5'] = !empty($r2['cust_field5'])?$r2['cust_field5']:"0";
		$trans[$r2['trans_id']]['pick_time'] = !empty($r2['pick_time'])?$r2['pick_time']:"-";
		$trans[$r2['trans_id']]['pick_amount'] = !empty($r2['pick_amount'])?$r2['pick_amount']:"0";
		$trans[$r2['trans_id']]['dep_type1'] = !empty($r2['dep_type1'])?$r2['dep_type1']:"";
		$trans[$r2['trans_id']]['c_code'] = !empty($r2['c_code'])?$r2['c_code']:"";
		$trans[$r2['trans_id']]['point_codes'] = !empty($r2['point_codes'])?$r2['point_codes']:"";
		$trans[$r2['trans_id']]['dep_accid'] = !empty($r2['dep_accid'])?$r2['dep_accid']:"0";
		$trans[$r2['trans_id']]['dep_branch_dep'] = !empty($r2['dep_branch'])?$r2['dep_branch']:"-";
		$trans[$r2['trans_id']]['dep_amount'] = !empty($r2['dep_amount2'])?$r2['dep_amount2']:"0";
		$trans[$r2['trans_id']]['dep_slip'] = !empty($r2['dep_slip'])?$r2['dep_slip']:"0";
		$trans[$r2['trans_id']]['gen_slip'] = !empty($r2['gen_slip'])?$r2['gen_slip']:"0";
		$trans[$r2['trans_id']]['2000s'] = !empty($r2['2000s'])?$r2['2000s']:"0";
		$trans[$r2['trans_id']]['1000s'] = !empty($r2['1000s'])?$r2['1000s']:"0";
		$trans[$r2['trans_id']]['500s'] = !empty($r2['500s'])?$r2['500s']:"0";
		$trans[$r2['trans_id']]['200s'] = !empty($r2['200s'])?$r2['200s']:"0";
		$trans[$r2['trans_id']]['100s'] = !empty($r2['100s'])?$r2['100s']:"0";
		$trans[$r2['trans_id']]['50s'] = !empty($r2['50s'])?$r2['50s']:"0";
		$trans[$r2['trans_id']]['20s'] = !empty($r2['20s'])?$r2['20s']:"0";
		$trans[$r2['trans_id']]['10s'] = !empty($r2['10s'])?$r2['10s']:"0";
		$trans[$r2['trans_id']]['5s'] = !empty($r2['5s'])?$r2['5s']:"0";
		$trans[$r2['trans_id']]['coins'] = !empty($r2['coins'])?$r2['coins']:"0";
		$trans[$r2['trans_id']]['o1000s'] = !empty($r2['o1000s'])?$r2['o1000s']:"0";
		$trans[$r2['trans_id']]['o500s'] = !empty($r2['o500s'])?$r2['o500s']:"0";
		$explodeC_code = explode(',',$r2['c_code']);
		if(count($explodeC_code)>1)
		{ 
			$trans[$r2['trans_id']]['denominationDiff'] = '0'; 
		}
		else
		{  
			$trans[$r2['trans_id']]['denominationDiff'] = ''; 
		}

		$trans[$r2['trans_id']]['dep_amount2'] = !empty($r2['dep_amount2'])?$r2['dep_amount2']:"0";
		$trans[$r2['trans_id']]['dep_bank2'] = !empty($r2['dep_bank2'])?$r2['dep_bank2']:"-";
		$trans[$r2['trans_id']]['other_remarks2'] = !empty($r2['other_remarks2'])?$r2['other_remarks2']:"-";
		if($coll_remarks=='Others') 
		{
			$remark = $other_remarks;
		}
		else
		{
			$remark = $coll_remarks;
		}
	
	
		$trans[$r2['trans_id']]['remark'] = !empty($remark)?$remark:"";
		
		if($dep_type1=='Client Bank') 
		{ 
			$client_amt=$pick_amount; 
		}
		else if($dep_type1=='Burial') { $burial_amt=$pick_amount; }else if($dep_type1=='Partner Bank') { $partner_amt=$pick_amount; } else if($dep_type1=='Vault') { $valut_amt=$pick_amount; $dep_amount = 0; }
		if($dclient_id==20) {
			if($dep_type1=='Partner Bank' || $dep_type1=='Client Bank') { $partner_amt=$pick_amount; }
		}
		$trans[$r2['trans_id']]['client_amt'] = !empty($client_amt)?$client_amt:"0";
		$trans[$r2['trans_id']]['burial_amt'] = !empty($burial_amt)?$burial_amt:"0";
		$trans[$r2['trans_id']]['partner_amt'] = !empty($partner_amt)?$partner_amt:"0";
		$trans[$r2['trans_id']]['valut_amt'] = !empty($valut_amt)?$valut_amt:"0";	
		
		
	}
	
	//Daily Collection Multi
	$sql_m = mysqli_query($reportConnection,"SELECT * FROM daily_collectionmul WHERE trans_id IN (".$cu_trans_id.") AND status='Y' ORDER BY rec_id");
	while($rm = mysqli_fetch_assoc($sql_m)) {
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['c_code'] = !empty($rm['c_code'])?$rm['c_code']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['point_codes'] = !empty($rm['point_codes'])?$rm['point_codes']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['rec_no'] = !empty($rm['rec_no'])?$rm['rec_no']:"0";		
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['pis_hcl_no'] = !empty($rm['pis_hcl_no'])?$rm['pis_hcl_no']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['hcl_no'] = !empty($rm['hcl_no'])?$rm['hcl_no']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field1'] = !empty($rm['cust_field1'])?$rm['cust_field1']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field2'] = !empty($rm['cust_field2'])?$rm['cust_field2']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field3'] = !empty($rm['cust_field3'])?$rm['cust_field3']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field4'] = !empty($rm['cust_field4'])?$rm['cust_field4']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['cust_field5'] = !empty($rm['cust_field5'])?$rm['cust_field5']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['pick_amount'] = !empty($rm['pick_amount'])?$rm['pick_amount']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['dep_slip'] = !empty($rm['dep_slip'])?$rm['dep_slip']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['2000s'] = !empty($rm['2000s'])?$rm['2000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['1000s'] = !empty($rm['1000s'])?$rm['1000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['500s'] = !empty($rm['500s'])?$rm['500s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['200s'] = !empty($rm['200s'])?$rm['200s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['100s'] = !empty($rm['100s'])?$rm['100s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['50s'] = !empty($rm['50s'])?$rm['50s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['20s'] = !empty($rm['20s'])?$rm['20s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['10s'] = !empty($rm['10s'])?$rm['10s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['5s'] = !empty($rm['5s'])?$rm['5s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['coins'] = !empty($rm['coins'])?$rm['coins']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['o1000s'] = !empty($rm['o1000s'])?$rm['o1000s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['o500s'] = !empty($rm['o500s'])?$rm['o500s']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['remark'] = !empty($rm['mul_remarks'])?$rm['mul_remarks']:"0";
		
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['gen_slip'] = !empty($rm['gen_slip'])?$rm['gen_slip']:"0";
		$trans[$rm['trans_id']]['multi'][$rm['rec_id']]['denominationDiff'] = '';

	}
	
	
	//Filed
	$sql_field = mysqli_query($reportConnection,"SELECT * FROM daily_collectionannex WHERE trans_id IN (".$cu_trans_id.")  AND status='Y'");
   	while($res_field = mysqli_fetch_assoc($sql_field)) {
		$trans[$res_field['trans_id']]['field1'] = !empty($res_field['field1'])?$res_field['field1']:"-";
		$trans[$res_field['trans_id']]['field2'] = !empty($res_field['field2'])?$res_field['field2']:"-";
		$trans[$res_field['trans_id']]['field3'] = !empty($res_field['field3'])?$res_field['field3']:"-";
		$trans[$res_field['trans_id']]['field4'] = !empty($res_field['field4'])?$res_field['field4']:"-";
	}
}




//Prev. Day Vault Amount Desposit
// if(!empty($shop_id)) {
// 	$cu_shop_id = "'".implode("','",$shop_id)."'";	
// 	$sql_prev = mysqli_query("SELECT b.pick_amount,a.pickup_code, a.trans_id, dep_amount1, dep_amount2, dep_accid, dep_branch FROM daily_trans AS a INNER JOIN daily_collection AS b ON a.trans_id=b.trans_id WHERE a.pickup_code IN (".$cu_shop_id.") AND a.pickup_date='".$pday."'   AND a.`status`='Y' AND b.`status`='Y'");
// 	if(mysqli_num_rows($sql_prev)>0) {
// 		while($res_prev = mysqli_fetch_assoc($sql_prev)) {
// 			$trans1[$res_prev['pickup_code']]['prev_valut_amt'] = !empty($res_prev['dep_amount2'])?$res_prev['dep_amount2']:"";
// 			$trans1[$res_prev['pickup_code']]['pick_amount'] = !empty($res_prev['pick_amount'])?$res_prev['pick_amount']:"0";
// 			$trans1[$res_prev['pickup_code']]['prev_dep_accid'] = !empty($res_prev['dep_accid'])?$res_prev['dep_accid']:"";
// 			$trans1[$res_prev['pickup_code']]['prev_dep_branch'] = !empty($res_prev['dep_branch'])?$res_prev['dep_branch']:"";
// 		}
// 	}
// }
//print'<pre>';print_r($trans);die;

if(!empty($trans_id)) {
	$i = 1;
	foreach($trans as $key4=>$val4) {
		$customer_code_m = explode(',',$val4['customer_code']);
			if($i==1) {
			
				$alb = array('L','M','N','O','P','Q','R','S','T','U','V','W');
			}
			
			$region_names = $val4['region_name'];
			if($region_names!=$region_names1 && $i!=1) {
				$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, $region_names1.' Total');		
				foreach($alb as $key=>$val){
					$spreadsheet->getActiveSheet()->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
				}
				$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFont()->setBold(true);
				$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
				$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
				$gtotal_rows[]= $row_no;				
				$row_no++;
				$start_rows = $row_no;
			}
			
			$agency_code = 'NIL';
			if($val4['dep_type1']=="Burial")$agency_code="RCMSMUM";
			elseif($val4['dep_type1']=="Partner Bank") {
				$acc_idss = '';
				$acc_idss = $dep_branchs[$val4['shop_acc_id']]['bank_name'];
				if($bank_all[$acc_idss]=='SBI' || $bank_all[$acc_idss]=='PNB') {
					$agency_code = $bank_all[$acc_idss].'CASH'; 
				}
				else {
					$agency_code = $bank_all[$acc_idss]; 
				}
				//$agency_code = $bank_all[$acc_idss]; 
			
			}else $agency_code="NIL";
			$dbank = '';
			if($agency_code!="RCMSMUM") {
				if($val4['s_dep_bank']=="Partner Bank") $dbank="PB"; else $dbank=$val4['s_dep_bank'];	
			}
			else {
				if($val4['dep_type1']=="Partner Bank") $dbank="PB"; else $dbank=$val4['dep_type1'];
			}
			
			if($agency_code=="AXIS") 
            {
                $agency_code="UTI-BK";

            }
			if($val4['multi_rec']=='Y') {
				foreach($val4['multi'] as $key5=>$val5) {
					$spreadsheet->getActiveSheet()->setCellValue('A'.$row_no,$i);
					$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
                    //$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no,date('d-m-Y',strtotime($deposit_date)) );
					$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no, $val4['cust_name']);
					$spreadsheet->getActiveSheet()->setCellValue('D'.$row_no,$val5['c_code']);
                    $spreadsheet->getActiveSheet()->setCellValue('E'.$row_no,$val4['region_name']);
					$spreadsheet->getActiveSheet()->setCellValue('F'.$row_no, $val4['location']);
					
					$spreadsheet->getActiveSheet()->setCellValue('G'.$row_no, $val4['address']);
					$spreadsheet->getActiveSheet()->setCellValue('H'.$row_no, $val4['pickup_type']);
					$spreadsheet->getActiveSheet()->setCellValue('I'.$row_no, $val4['cash_limit']);
					$spreadsheet->getActiveSheet()->setCellValueExplicit('J'.$row_no, $val4['subcustomer_code'],PHPExcel_Cell_DataType::TYPE_STRING);
					$spreadsheet->getActiveSheet()->setCellValue('K'.$row_no, !empty($val5['hcl_no'])?$val5['hcl_no']:"0");
					$spreadsheet->getActiveSheet()->setCellValue('L'.$row_no, $val5['pick_amount']);
					$spreadsheet->getActiveSheet()->setCellValue('M'.$row_no,!empty($val4['dep_amount'])?$val4['dep_amount']:"0");
					$spreadsheet->getActiveSheet()->setCellValue('N'.$row_no, $val5['2000s']);
					$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, $val5['500s']);
					$spreadsheet->getActiveSheet()->setCellValue('P'.$row_no, $val5['200s']);
					$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, $val5['100s']);
					$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, $val5['50s']);
					$spreadsheet->getActiveSheet()->setCellValue('S'.$row_no, $val5['20s']);
					$spreadsheet->getActiveSheet()->setCellValue('T'.$row_no, $val5['10s']);
					$spreadsheet->getActiveSheet()->setCellValue('U'.$row_no, $val5['5s']);
					$spreadsheet->getActiveSheet()->setCellValue('V'.$row_no, $val5['coins']);
	
                    $totals = '=(N'.$row_no.'*2000)+(O'.$row_no.'*500)+(P'.$row_no.'*200)+(Q'.$row_no.'*100)+(R'.$row_no.'*50)+(S'.$row_no.'*20)+(T'.$row_no.'*10)+(U'.$row_no.'*5)+(V'.$row_no.')';

					$spreadsheet->getActiveSheet()->setCellValue('W'.$row_no, $totals);
					$spreadsheet->getActiveSheet()->setCellValue('X'.$row_no, $val5['denominationDiff']);
				    //$spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, !empty($trans1[$val4['shop_id']]['prev_valut_amt'])?$trans1[$val4['shop_id']]['prev_valut_amt']:"0");
					$spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, $val5['point_codes']);
					$spreadsheet->getActiveSheet()->setCellValue('Z'.$row_no, !empty($val5['remark'])?$val5['remark']:"");
					$spreadsheet->getActiveSheet()->setCellValue('AA'.$row_no, !empty($val4['shop_id'])?$val4['shop_id']:"");	
					$row_no++;
					$i++;
				}
			}
			else 
            {
                    $spreadsheet->getActiveSheet()->setCellValue('A'.$row_no,$i);
					$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, date('d-m-Y',strtotime($val4['pickup_date'])));
					$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no, $val4['cust_name']);
					$spreadsheet->getActiveSheet()->setCellValue('D'.$row_no,$val4['c_code']);
                    $spreadsheet->getActiveSheet()->setCellValue('E'.$row_no,$val4['region_name']);
					$spreadsheet->getActiveSheet()->setCellValue('F'.$row_no, $val4['location']);
					$spreadsheet->getActiveSheet()->setCellValue('G'.$row_no, $val4['address']);
					$spreadsheet->getActiveSheet()->setCellValue('H'.$row_no, $val4['pickup_type']);
					$spreadsheet->getActiveSheet()->setCellValue('I'.$row_no, $val4['cash_limit']);
					$spreadsheet->getActiveSheet()->setCellValueExplicit('J'.$row_no, $val4['subcustomer_code'],PHPExcel_Cell_DataType::TYPE_STRING);
					$spreadsheet->getActiveSheet()->setCellValue('K'.$row_no, !empty($val4['hcl_no'])?$val4['hcl_no']:"0");
					$spreadsheet->getActiveSheet()->setCellValue('L'.$row_no, $val4['pick_amount']);					
					$spreadsheet->getActiveSheet()->setCellValue('M'.$row_no,!empty($val4['dep_amount'])?$val4['dep_amount']:"0");
                    $spreadsheet->getActiveSheet()->setCellValue('N'.$row_no, $val4['2000s']);
					$spreadsheet->getActiveSheet()->setCellValue('O'.$row_no, $val4['500s']);
					$spreadsheet->getActiveSheet()->setCellValue('P'.$row_no, $val4['200s']);
					$spreadsheet->getActiveSheet()->setCellValue('Q'.$row_no, $val4['100s']);
					$spreadsheet->getActiveSheet()->setCellValue('R'.$row_no, $val4['50s']);
					$spreadsheet->getActiveSheet()->setCellValue('S'.$row_no, $val4['20s']);
					$spreadsheet->getActiveSheet()->setCellValue('T'.$row_no, $val4['10s']);
					$spreadsheet->getActiveSheet()->setCellValue('U'.$row_no, $val4['5s']);
					$spreadsheet->getActiveSheet()->setCellValue('V'.$row_no, $val4['coins']);

					$totals = '=(N'.$row_no.'*2000)+(O'.$row_no.'*500)+(P'.$row_no.'*200)+(Q'.$row_no.'*100)+(R'.$row_no.'*50)+(S'.$row_no.'*20)+(T'.$row_no.'*10)+(U'.$row_no.'*5)+(V'.$row_no.')';					
					$spreadsheet->getActiveSheet()->setCellValue('W'.$row_no, $totals);
                    						
					$spreadsheet->getActiveSheet()->setCellValue('X'.$row_no, $val4['denominationDiff']);
				   // $spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, !empty($trans1[$val4['shop_id']]['prev_valut_amt'])?$trans1[$val4['shop_id']]['prev_valut_amt']:"0");					
					//$spreadsheet->getActiveSheet()->setCellValue('AB'.$row_no, $trans1[$val4['shop_id']]['prev_valut_amt'] );
					$spreadsheet->getActiveSheet()->setCellValue('Y'.$row_no, $val4['point_codes']);
                    $spreadsheet->getActiveSheet()->setCellValue('Z'.$row_no, !empty($val4['remark'])?$val4['remark']:"");
                    $spreadsheet->getActiveSheet()->setCellValue('AA'.$row_no, !empty($val4['shop_id'])?$val4['shop_id']:$val4['shop_id']);

						
				$row_no++;
				$i++;
			}
			$region_names1 = $val4['region_name'];
			

		
	}
	
}

//Grand Total

if($gtotal==1) {
	if(!empty($alb)) {
		$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, 'Grand Total');
		foreach($alb as $key=>$val){
			$spreadsheet->getActiveSheet()->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
		}
		$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':N'.$row_no)->getFont()->setBold(true);
	}
}
else {
	if(!empty($alb)) {
		if($report_type==1) {
			$spreadsheet->getActiveSheet()->setCellValue('C'.$row_no, $cust_names1.' Total');	

		}
		else {
			$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, $region_names1.' Total');	
		}
		foreach($alb as $key=>$val){
			$spreadsheet->getActiveSheet()->setCellValue($val.$row_no, '=SUM('.$val.$start_rows.':'.$val.($row_no-1).')');
		}
		$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_reg);
		$gtotal_rows[] = $row_no;
		$row_no++;
	
		//print'<pre>';print_r($gtotal_rows);
		$spreadsheet->getActiveSheet()->setCellValue('B'.$row_no, 'Grand Total');		
		foreach($alb as $key=>$val){
			foreach($gtotal_rows as $val1) {
				$load_result.= $val.$val1.',';
			}
			$load_result = substr($load_result, 0, -1);
			$spreadsheet->getActiveSheet()->setCellValue($val.$row_no, '=SUM('.$load_result.')');
			$load_result = '';
		}
	}
}
$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':Y'.$row_no)->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->getFill()->getStartColor()->setARGB($cllor_gtotal);	
$spreadsheet->getActiveSheet()->getStyle('A'.$row_no.':'.$end_filed.$row_no)->applyFromArray(
																array(
																	'font'  => array(
																		'bold' => true,
																		'color' => array('rgb' => '000000')																		
																	)
																)
															);

$last_row = $row_no;


$file_name = 'PICKUP_'.$name.'_day0_burial'.$trans_date_c.'.xlsx';
$sheet_name = 'NTex';



$spreadsheet->getActiveSheet()->getStyle('A1:'.$end_filed.'4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getFill()->getStartColor()->setARGB($cllor_title);
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->applyFromArray(
																array(
																	'font'  => array(
																		'bold' => true,
																		'color' => array('rgb' => 'FFFFFF')																		
																	)
																)
															); 

//$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getFont()->setColor('FFFFFF');
$spreadsheet->getActiveSheet()->getStyle('A4:'.$end_filed.'4')->getAlignment()->setWrapText(true); 



$spreadsheet->getActiveSheet()->mergeCells('A1:'.$end_filed.'1');
$spreadsheet->getActiveSheet()->mergeCells('A2:'.$end_filed.'2');
$spreadsheet->getActiveSheet()->mergeCells('A3:'.$end_filed.'3');




for($s = 1;$s<=$last_row;$s++){					
  $spreadsheet->getActiveSheet()->getStyle("A$s:".$end_filed.$s)->applyFromArray(array(
		  'borders' => array(
			  'allborders' => array(
				  'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
			  )
		  )
	  ));
}




$spreadsheet->getActiveSheet()->setTitle($sheet_name);
$spreadsheet->removeSheetByIndex(2);

// Rename worksheet



// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
ob_end_clean();
$writer->save("php://output");
exit;


?>