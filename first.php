<?php

include('../DbConnection/dbConnect.php');


error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
session_start();
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require '../dependencies/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$date=$_GET['trans_date'];
$newDate = date("Y-m-d", strtotime($date));

$cust_id=$_GET['cust_name'];








// Create new PHPExcel object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator("Maarten Balliauw")
             ->setLastModifiedBy("Maarten Balliauw")
             ->setTitle("Office 2007 XLSX Test Document")
             ->setSubject("Office 2007 XLSX Test Document")
             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
             ->setKeywords("office 2007 openxml php")
             ->setCategory("Test result file");


// Add some data

$i = 0;

$spreadsheet->setActiveSheetIndex(0);   
  
    $rows = 4;
    
    
    
    $HeaderArray = array('S.No','State Name','Region','Transaction Date (Pickup Date)','Customer  Name','Location','Pickup Point Address',
    'Cash Limit','Beat / On Call','Deposit Mode','Bank Name','Bank A/C Number','SBFC Branch Code','Transaction Reference (HCI Slip No)',
    'Transaction Amt (Pickup Amount)','Seal Tag No','Cash Depositor_Branch_Name','2000','1000','500','200','100','50','20','10','5','Others',
    'Total','Difference','Remarks','Point ID');
            
        $Alpha = 'A';
    FOREACH($HeaderArray AS $HeaderValue){
          $spreadsheet->getActiveSheet()->setCellValue($Alpha.$rows,$HeaderValue);
      $Alpha++;
    }
    
    $rows++;

    $sql = "SELECT daily_trans.region,daily_trans.trans_id,shop_details.state,shop_details.subcustomer_code,shop_details.sol_id,
    shop_details.dep_bank,daily_trans.location,daily_trans.pickup_name,shop_details.hierarchy_code,daily_trans.cust_name,daily_trans.pickup_date,
    shop_details.loi_date,shop_details.cash_limit,shop_details.pickup_type,daily_collection.hcl_no,daily_collection.pick_amount,daily_collection.c_code,
    daily_collection.dep_amount1,daily_collection.rec_no,daily_collection.pis_hcl_no,daily_collection.gen_slip,daily_collection.dep_type1,daily_collection.dep_branch,daily_collection.2000s,daily_collection.1000s,daily_collection.500s,
    daily_collection.200s,daily_collection.100s,daily_collection.50s,daily_collection.20s,daily_collection.10s,daily_collection.5s,
    daily_collection.coins,daily_collection.coll_remarks,daily_collection.multi_rec,shop_details.point_type,
    shop_details.shop_id FROM daily_trans LEFT JOIN daily_collection ON daily_trans.trans_id=daily_collection.trans_id INNER JOIN 
    shop_details ON daily_trans.pickup_code=shop_details.shop_id INNER JOIN cust_details ON cust_details.cust_id=shop_details.cust_id 
    WHERE daily_trans.pickup_date='$newDate' AND shop_details.cust_id='$cust_id' AND daily_trans.status='Y'";

    $result=mysql_query($sql);
    $n=mysql_num_rows($result);
    if($n>0)
    {
        $i=1;
        while($out=mysql_fetch_array($result)) {
            if($out['multi_rec']=='N' || $out['multi_rec']=='') {

                $total_amount = ($out['2000s'] * 2000) + ($out['500s'] * 500) + ($out['200s'] * 200) + ($out['100s'] * 100) +($out['50s'] * 50) + ($out['20s'] * 20) + ($out['10s'] * 10) + ($out['5s'] * 5) + ($out['coins']);

                /*$dep_type1    = !empty($out['dep_type1'])?$out['dep_type1']:"0";    //hariharan

                if($dep_type1 == 'Vault') {
                      $vault_amount= $out['pick_amount'];
                    } else {
                      $vault_amount= 0;
                    }

                if($out['loi_date']!='' && $out['loi_date']!='00-00-0000') {
                        $req_date   =   date("d-m-Y",strtotime($out['loi_date']));
                    } else {
                        $req_date = date('d-m-Y',strtotime($date));
                    }


                if($dep_type1 == 'Vault') {
                      $deposit_amount= 0;
                    } else {
                      $deposit_amount= $out['pick_amount'];
                    }*/      //hariharan




                        $spreadsheet->getActiveSheet()->setCellValue('A'.$rows,$i);
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$rows,$out['state']);
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$rows,$out['region']);
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$rows,date("d-m-Y",strtotime($out['pickup_date'])));
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$rows,$out['cust_name']);
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$rows,$out['location']);
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$rows,$out['pickup_name']);
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$rows,$out['cash_limit']);
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$rows,$out['pickup_type']);
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$rows,$out['hierarchy_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$rows,$out['subcustomer_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('L'.$rows,$out['sol_id']);
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$rows,$out['c_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$rows,$out['gen_slip']);
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$rows,$out['pick_amount']);
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$rows,$out['pis_hcl_no']);
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$rows,$out['dep_branch']);
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$rows,is_null($out['2000s'])?0:$out['2000s']);
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$rows,'0');
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$rows,is_null($out['500s'])?0:$out['500s']);
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$rows,is_null($out['200s'])?0:$out['200s']);
                        $spreadsheet->getActiveSheet()->setCellValue('V'.$rows,is_null($out['100s'])?0:$out['100s']);
                        $spreadsheet->getActiveSheet()->setCellValue('W'.$rows,is_null($out['50s'])?0:$out['50s']);
                        $spreadsheet->getActiveSheet()->setCellValue('X'.$rows,is_null($out['20s'])?0:$out['20s']);
                        $spreadsheet->getActiveSheet()->setCellValue('Y'.$rows,is_null($out['10s'])?0:$out['10s']);
                        $spreadsheet->getActiveSheet()->setCellValue('Z'.$rows,is_null($out['5s'])?0:$out['5s']);
                        $spreadsheet->getActiveSheet()->setCellValue('AA'.$rows,is_null($out['coins'])?0:$out['coins']);
                        $spreadsheet->getActiveSheet()->setCellValue('AB'.$rows,$total_amount);
                        $spreadsheet->getActiveSheet()->setCellValue('AC'.$rows,$out['pick_amount']-$total_amount);
                        $spreadsheet->getActiveSheet()->setCellValue('AD'.$rows,is_null($out['coll_remarks'])?'Report Awaiting':$out['coll_remarks']);
                        $spreadsheet->getActiveSheet()->setCellValue('AE'.$rows,$out['shop_id']); 
                        
                        
                        $rows++;
                        $i++;
            } else {

                $sql1 ="SELECT pick_amount,2000s,500s,200s,100s,50s,20s,10s,5s,coins,hcl_no,rec_no,gen_slip,pis_hcl_no,c_code,pis_hcl_no,mul_remarks FROM daily_collectionmul WHERE trans_id='".$out['trans_id']."' AND status='Y'";

                $result1=mysql_query($sql1);

                while ($out1=mysql_fetch_array($result1)) {
                 $total_amount1 = ($out1['2000s'] * 2000) + ($out1['500s'] * 500) + ($out1['200s'] * 200) + ($out1['100s'] * 100) +($out1['50s'] * 50) + ($out1['20s'] * 20) + ($out1['10s'] * 10) + ($out1['5s'] * 5) + ($out1['coins']);

                /*$dep_type2    = !empty($out['dep_type1'])?$out['dep_type1']:"0";  //hariharan

                if($dep_type2 == 'Vault') {
                      $vault_amount1= $out1['pick_amount'];
                    } else {
                      $vault_amount1= 0;
                    }

                if($dep_type2 == 'Vault') {
                      $deposit_amount2= 0;
                    } else {
                      $deposit_amount2= $out1['pick_amount'];
                    }*/   //hariharan

                        $spreadsheet->getActiveSheet()->setCellValue('A'.$rows,$i);
                        $spreadsheet->getActiveSheet()->setCellValue('B'.$rows,$out['state']);
                        $spreadsheet->getActiveSheet()->setCellValue('C'.$rows,$out['region']);
                        $spreadsheet->getActiveSheet()->setCellValue('D'.$rows,date("d-m-Y",strtotime($out['pickup_date'])));
                        $spreadsheet->getActiveSheet()->setCellValue('E'.$rows,$out['cust_name']);
                        $spreadsheet->getActiveSheet()->setCellValue('F'.$rows,$out['location']);
                        $spreadsheet->getActiveSheet()->setCellValue('G'.$rows,$out['pickup_name']);
                        $spreadsheet->getActiveSheet()->setCellValue('H'.$rows,$out['cash_limit']);
                        $spreadsheet->getActiveSheet()->setCellValue('I'.$rows,$out['pickup_type']);
                        $spreadsheet->getActiveSheet()->setCellValue('J'.$rows,$out['hierarchy_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('K'.$rows,$out['subcustomer_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('L'.$rows,$out['sol_id']);
                        $spreadsheet->getActiveSheet()->setCellValue('M'.$rows,$out1['c_code']);
                        $spreadsheet->getActiveSheet()->setCellValue('N'.$rows,$out1['gen_slip']);
                        $spreadsheet->getActiveSheet()->setCellValue('O'.$rows,$out1['pick_amount']);
                        $spreadsheet->getActiveSheet()->setCellValue('P'.$rows,$out1['pis_hcl_no']);
                        $spreadsheet->getActiveSheet()->setCellValue('Q'.$rows,$out['dep_branch']);
                        $spreadsheet->getActiveSheet()->setCellValue('R'.$rows,is_null($out1['2000s'])?0:$out1['2000s']);
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$rows,'0');
                        $spreadsheet->getActiveSheet()->setCellValue('T'.$rows,is_null($out1['500s'])?0:$out1['500s']);
                        $spreadsheet->getActiveSheet()->setCellValue('U'.$rows,is_null($out1['200s'])?0:$out1['200s']);
                        $spreadsheet->getActiveSheet()->setCellValue('V'.$rows,is_null($out1['100s'])?0:$out1['100s']);
                        $spreadsheet->getActiveSheet()->setCellValue('W'.$rows,is_null($out1['50s'])?0:$out1['50s']);
                        $spreadsheet->getActiveSheet()->setCellValue('X'.$rows,is_null($out1['20s'])?0:$out1['20s']);
                        $spreadsheet->getActiveSheet()->setCellValue('Y'.$rows,is_null($out1['10s'])?0:$out1['10s']);
                        $spreadsheet->getActiveSheet()->setCellValue('Z'.$rows,is_null($out1['5s'])?0:$out1['5s']);
                        $spreadsheet->getActiveSheet()->setCellValue('AA'.$rows,is_null($out1['coins'])?0:$out1['coins']);
                        $spreadsheet->getActiveSheet()->setCellValue('AB'.$rows,$total_amount1);
                        $spreadsheet->getActiveSheet()->setCellValue('AC'.$rows,$out1['pick_amount']-$total_amount1);
                        $spreadsheet->getActiveSheet()->setCellValue('AD'.$rows,is_null($out1['mul_remarks'])?'Report Awaiting':$out1['mul_remarks']);
                        $spreadsheet->getActiveSheet()->setCellValue('AE'.$rows,$out['shop_id']);
                        

                        $rows++;
                        $i++;
                    }

                  }

        }

                       $spreadsheet->getActiveSheet()->setCellValue('D'.$rows, 'Grand Total');

                      $end_row=$rows-1;
                     $spreadsheet->getActiveSheet()->setCellValue('O'.$rows,'=SUM(O5:O'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('R'.$rows,'=SUM(R5:R'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('S'.$rows,'=SUM(S5:S'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('T'.$rows,'=SUM(T5:T'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('U'.$rows,'=SUM(U5:U'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('V'.$rows,'=SUM(V5:V'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('W'.$rows,'=SUM(W5:W'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('X'.$rows,'=SUM(X5:X'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('Y'.$rows,'=SUM(Y5:Y'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('Z'.$rows,'=SUM(Z5:Z'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('AA'.$rows,'=SUM(AA5:AA'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('AB'.$rows,'=SUM(AB5:AB'.$end_row.')');
                     $spreadsheet->getActiveSheet()->setCellValue('AC'.$rows,'=SUM(AC5:AC'.$end_row.')');
    }





//-----------------excelheader-------------------------------//     
$spreadsheet->getActiveSheet()->setCellValue('A1','RADIANT CASH MANAGEMENT SERVICES LTD');
$spreadsheet->getActiveSheet()->setCellValue('A2','SBFC');
$spreadsheet->getActiveSheet()->setCellValue('A3','PICKUP DATE:'.$date);


//-----------------excelheader(end)-------------------------------//        

$spreadsheet->getActiveSheet()->mergeCells('A1:AE1');
$spreadsheet->getActiveSheet()->mergeCells('A2:AE2');
$spreadsheet->getActiveSheet()->mergeCells('A3:AE3');

$spreadsheet->getActiveSheet()->getStyle('A4:AE4')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('D'.$rows)->getFont()->setBold(true);

//wrap text
$spreadsheet->getActiveSheet()->getStyle('A4:AE4')->getAlignment()->setWrapText(true);

for ($i=1; $i <=$rows ; $i++) { 
    $spreadsheet->getActiveSheet()->getStyle('A'.$i.':AE'.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

}

function cellColor($cells,$color){
    global $spreadsheet;

    $spreadsheet->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


cellColor('A4:AE4', 'FFFF99');
cellColor('A1:A3', 'FFFF99');
cellColor('A'.$rows.':AE'.$rows, 'FF0000');


function cellcenter($cells) {
    global $spreadsheet;
    $spreadsheet->getActiveSheet()->getStyle($cells)->getAlignment()->applyFromArray(
    array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,)
   );
}
cellcenter('A1');
cellcenter('A2');
cellcenter('A3');
cellcenter('A4:AE4');



















    $spreadsheet->setActiveSheetIndex(0);
$spreadsheet->getActiveSheet()->setTitle('SBFC');


$file_name = 'SBFC'.$date.'.xlsx';


// Redirect output to a client’s web browser (Excel2007)





// If you're serving to IE 9, then the following may be needed


// If you're serving to IE over SSL, then the following may be needed
// Date in the past
// always modified
// HTTP/1.1
// HTTP/1.0

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
ob_end_clean();
$writer->save("php://output");
exit;