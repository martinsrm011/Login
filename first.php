<style type="text/css">
.teacherpage { page-break-after:always;margin-top:-100px; }
.mleft { margin-left: 68px; }
.mleftsec { margin-left: 98px;margin-top:-43px; }
.mleftthird { margin-left: 100px; }
.mleftthird1 { margin-left: 140px;margin-top:-43px; }
p    {text-align:justify; font-family: Cambria; font-size: 21px;}
u    {font-size: 26px;font-family: Cambria;}
u    {font-size: 26px;font-family: Cambria;}
</style>
<?php
if(!isset($_SESSION)) {	session_start(); }
$id = $_REQUEST['id'];
include('rcms_function_pages2.php');
//include('dbconnect_rcmsdata.php');
include('date_picker_link.php');
include('DbConnection/dbConnect.php');
$agg_date=date("Y-m-d");
$initm=date('Y-m-d', strtotime("+350 days")); 
$end_date=date('Y-m-d', strtotime("+365 days")); 
$id1=$_REQUEST['id'];
// aggrement end date
echo "<p style='margin:300px 0 0 0'></p>";
$end_date=date('Y-m-d', strtotime("+350 days")); 

 $sql1="select * from hrms_empdet where r_id='".$id."' and status='Y'"; 

$qu1=mysqli_query($readConnection,$sql1);

while($r1=mysqli_fetch_array($qu1))
{
	$ss=mysqli_query($readConnection,"select * from hrms_increment_details where status='Y' and emp_id='".$r1['emp_id']."' order by id desc limit 1");
	$num_row=mysqli_num_rows($ss); 
	$rr=mysqli_fetch_array($ss);
	
	if($num_row>0)
	{
		$gross=$rr['fixed_salary'];
	}
	else{
		$gross=$r1['gross_sal'];
	}
	
	
	?>

	<style type="text/css">
	.teacherpage { page-break-after:always;margin-top:-100px; }
	</style>
<div style="width:100%; margin-left:10px;" >
<p style="font-family: Cambria; font-size: 21px; margin-left:30px;">Date  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?php echo date('d-M-Y',strtotime($r1['doj']));?><br>Emp Code  &nbsp;&nbsp;: &nbsp;<?php echo $r1['emp_id'];?><br>
Name  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?php if($r1['gender']=="Male")echo "Mr. "; elseif($r1['gender']=="Female") echo "Ms. ";echo $r1['cname'].",";?><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo wordwrap($r1['address'], 100, "<br>\n");?><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r1['city']." - ".$r1['pin'];?><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $r1['state'];?>
</p>


    <center><strong><u>APPOINTMENT ORDER</u></strong></center>

<p style="margin-left:30px;">
    Dear <?php if($r1['gender']=="Male")echo "Mr. "; elseif($r1['gender']=="Female") echo "Ms. ";echo $r1['cname'].",";?><strong><u> </u></strong>
</p>
<p>
    <strong><u></u></strong>
</p>
<p>
    <p style="margin-left:30px;">With reference to your application and the subsequent interview with us, we are pleased to appoint you as  <strong><?php echo get_desig($readConnection,$r1['pdesig1'])." - ".get_dep($readConnection,$r1['pdesig']);?> </strong>, <?php echo $r1['pbranch'];?>. You will be reporting to the  <strong> <?php echo $r1['report_to']; ?> </strong> and placed at <?php echo $r1['region'];?> during your service. The terms and conditions of your appointment are here under:</p>
</p>
    <div style="margin-left:30px;"><p><span style="margin-left: 30px; margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">1.</span> <strong><u>Date of Joining:</u></strong><br>
    </p>
  <p class="mleft">Your date of joining is <?php echo date('d-M-Y',strtotime($r1['doj']));?>.
</p>
<p>
    <strong><u></u></strong>
</p>
<p>
     <span style="margin-left: 30px;margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">2.</span> <strong><u>Probation</u></strong><strong>: </strong>
</p>

  <p class="mleft" >a)</p><p class="mleftsec">You will be on probation for a period of Six months from the date of your joining the Company. The management reserves the right to extend this period if required. </p>

<p class="mleft" >
  b)</p><p class="mleftsec"> During the period of probation, your performance will be evaluated on regular basis and if the same is not as per the expected standard, your appointment is liable to be terminated without any notice, notice pay and assigning any reason thereon.
</p>
<p>
      <span style="margin-left: 30px;margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">3.</span> <strong><u>Compensation</u></strong><strong>: </strong>
</p>
<p class="mleft">
  Your monthly gross salary will be Rs.<?php echo moneyFormatIndia($gross);?>/-(Rupees <?php echo convert_number($gross); ?> Only) per month.<a name="_GoBack"></a><strong></strong>You will also be eligible for PF <?php if($r1['gross_sal']*12 <=252000) { ?>, ESI, <?php } ?> and Gratuity as per the company rules.
</p>
<p>
    <strong></strong>
</p>
<p>
      <span style="margin-left: 30px;margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">4.</span> <strong><u>Performance Review:</u></strong>
</p>
<p class="mleft">
    Your salary will be reviewed normally once in a year and is subject to your confirmation, efficiency, regularity and other performance parameters, and at the sole discretion of the management. Increments can also be accelerated for exceptionally good performance.
</p>
<p>
     <span style="margin-left: 30px;margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">5.</span> <strong><u>Leave:</u></strong>
</p>
<p>
     <p class="mleft">You are entitled leave while working in the Company as per the existing policy. However, grant of any leave will depend upon the pressure of work and shall be at the discretion of the management. For availing such leave you shall apply in advance and seek prior permission of your reporting for availing. While applying for leave, you will have to state the reasons for so doing. In case it is found at any time that reason stated for leave was false, you will be liable for discharge/dismissal from services.</p>
</p>
<div class="teacherpage"></div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<p>
      <span style="margin-left: 30px; margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">6.</span> <strong><u>Overtime:</u></strong>
</p>
<p class="mleft">
    This is a position of continuous responsibility and does not entail payment of extra time or overtime.
</p>
<p>
      <span style="margin-left: 30px; margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">7.</span> <strong><u>Transfer:</u></strong>
</p>
    <p class="mleft">Your services are liable to be transferred to any other division, activity or geographical location of this Company or any of its associates in present or come in existence in future. In such an eventuality, you shall be governed by terms and  conditions and the remuneration as applicable to such new place to which your service may be temporarily or permanently transferred. Therefore you will not be entitled to any additional compensation.In case you fail to report for duties at the transferred place, the management will be within its right may draw a presumption that you have abandoned the services on your accord and this contract will be terminated without any notice or salary in lieu of notice.</p>
<p>
     <span style="margin-left: 30px; margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">8.</span> <strong><u>Termination:</u></strong><strong></strong>
</p>
<p class="mleft" >
a)</p><p class="mleftsec"> After confirmation, your services may be terminated by giving one month’s notice or compensation in lieu thereof on either side. If adequate notice as aforesaid is not given by you while resigning from the services of the Company, appropriate deduction will be made to cover the notice period. However, the decision on adjusting the notice period will be based on discretion of the management.
</p>
<p class="mleft">
  b)</p><p class="mleftsec"> Your absence for a period of 10 days (including absence when leave though applied for but not granted) will entail an automatic loss of your lien on job without any notice and information by the management. The management will presume that you have abandoned the services on your own accord and you shall be liable to give one month’s salary in lieu of notice for abandoning the services in such a manner.
</p>
<p class="mleft">
     c)</p><p class="mleftsec"> Your services may be terminated by the Company, without notice or payment in lieu of notice in case of under mentioned stipulations:
</p>
<p class="mleftthird">
   i)</p><p class="mleftthird1"> Theft, forgery deception, dishonesty with Company property with intent for personal or professional gain.
</p>
<p class="mleftthird"> ii)</p><p class="mleftthird1"> Breach of conduct including drunkenness within the organization or in the vicinity thereof.
</p>
<p class="mleftthird"> iii)</p><p class="mleftthird1"> Persistent late arrival or early departure, persistent absence without notice and temporary absence from  workplace during office hours without
    approval from your superiors.
</p>
<p class="mleftthird"> iv)</p><p class="mleftthird1"> Indecent assault of the Company employee.
</p>
<p class="mleftthird"> v)</p><p class="mleftthird1"> Criminal conviction by court of law.
</p>
<p class="mleftthird"> vi)</p><p class="mleftthird1"> Habitual neglect in your duties.
</p>
<p class="mleftthird"> vii)</p><p class="mleftthird1"> Any activity, which brings the disrepute to the Company.
</p>
<p class="mleftthird"> viii)</p><p class="mleftthird1"> Any wilful misconduct towards the members of the opposite sex.
</p>
<div class="teacherpage"></div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<p>

      <span style="margin-left: 30px; margin-right:20px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">9.</span> <strong><u>Confidentiality:</u></strong><strong></strong>
</p>
<p class="mleft">
    While in service you will access new techniques /business/ know how / trainings, you will be required to comply with the  confidentiality norms of the Company. Therefore, please ensure that you maintain all information as secret and confidential, including your Terms of Employment and compensation package and shall not use or divulge or disclose any such information except as may be required under the obligation of law or as may be required by the Company in the course of employment.
</p>
<p>
     <span style="margin-left: 30px;margin-right:6px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">10.</span> <strong><u>Obligations:</u></strong><strong></strong>
</p>
<p class="mleft">
      a)</p><p class="mleftsec"> While serving the Company, you shall give and devote the whole of your work day exclusively to your duties with the Company and shall not engage yourself, directly or indirectly without prior consent in writing of the Company  with or without remuneration in any trade, business, occupation,employment, service or calling which is similar to or the same as that carried out by the Company nor shall you undertake any activities which are contrary to or ;inconsistent either with your duties and obligations under this appointment or with the Company’s interests.
</p>
<p class="mleft">
      b)</p><p class="mleftsec"> You shall not, at any time during the continuance or after the termination of your employment hereunder, divulge either directly to any person, firm or Company or use for yourself or another any knowledge, information, formulate, processes, methods, compositions, ideas or documents, concerning the business and affairs of the Company or any of its dealings, transactions or affairs which you may acquire the Company or have to your knowledge during the course of and incidental to your employment.
</p>
<p>
      <span style="margin-left: 30px; margin-right:6px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">11.</span> <strong><u>Attendance:</u></strong><strong></strong>
</p>
<p class="mleft">
   You are expected to remain on duty throughout the business / working hours of the organization and be present in time for any meeting or get together scheduled by the Company.
</p>

<p>
      <span style="margin-left: 30px; margin-right:6px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">12.</span> <strong><u>Governing Laws:</u></strong><strong></strong>
</p>
<p class="mleft">
    You shall be governed from time to time by the laws of the land as applicable to an employee in the Company’s service.
</p>
<p>
     <span style="margin-left: 30px; margin-right:6px;font-size: 20px;text-align: justify;font-family: Cambria; font-weight:bold;">13.</span> <strong><u>General:</u></strong><strong></strong>
</p>
<p class="mleft">
    a)</p><p class="mleftsec">  You will be governed by the Service Rules and Regulations of the Company that are in force during your association with the Company.
</p>

<p class="mleft">
b)</p><p class="mleftsec">  The Company will prescribe or modify your working hours to achieve the goals of the Company so as to employ you for 48 hours in a week.
</p>

<p class="mleft">
   c)</p><p class="mleftsec">  You will keep the Company informed in writing of any change in your permanent or present residential address within 7 days of such change.
</p>
<div class="teacherpage"></div>
<p class="mleft">
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
   d)</p><p class="mleftsec">  Any notice / letter sent to your address last available on Company’s record will be treated as served on you.
</p>
<p class="mleft">
   e)</p><p class="mleftsec">  On ceasing to be in employment of this Company for any reason you will promptly settle all account including the return of all Company properties,tools, electronic gadgets, documents, identity cards etc. without making or retaining any copies.
</p>
<p class="mleft">
    f)</p><p class="mleftsec">  During the notice period any leave exceeding 3 days will be treated as leave without pay.
</p>
<p class="mleft">
    g)</p><p class="mleftsec">  You will observe rule and regulation, conventions, standing orders or any other code of conduct enforced by the Company and amended or introduced from
    time to time.
</p>
<p class="mleft">
   h)</p><p class="mleftsec">  All matters pertaining to your appointment and compensation are strictly confidential and it should be treated as such. Any tax liability arising out of your compensation will be borne by you and it will be as per Income Tax Rule.
</p>

<p style="margin-left:30px;">
    You are requested to sign this Appointment Order in the format given below in duplicate and return one copy.
</p>
<p style="margin-left:30px;">
    We welcome you into the Company and look forward to a fruitful collaboration with you during your employment in the Company. We hope that you will
    contribute towards the success of Radiant Cash Management Services  Ltd. in achieving its goals.
</p>
<p style="margin-left:30px;">
    With Best Wishes,
</p>
<p style="margin-left:30px;">
    For<strong> Radiant Cash Management Services Ltd.</strong>
</p>
<br>
<br>
<p style="margin-left:30px;">
    Human Resources
</p>
<div>
</div>
<p style="border-top: 4px double #333;  padding: 10px 0; margin-left:30px; ">

    I, Mr./Ms. ...........…………………………………………………………………………………………, residing at
    ………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………………,
    Hereby confirm that I have read and understood, and accept the terms and conditions of the employment, contained in this Appointment Order. I am fully
    aware that my service in the Company will be governed by the service rules and regulations of the Company in force. The original of this Appointment Order
    issued to me is in my possession.
</p>
<p>
	&nbsp;
</p>
<p style="margin-left:30px;">
    (Signature)
</p>
<p style="margin-left:30px;">
    Place :
</p >
<p style="line-height: 5%; margin-left:30px;">
    Date :
</p></div></div>
<?php }
function convert_number($number) 
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
        $res .= convert_number($Cn) . " Crore"; 
    }
	if ($Ln) 
    { 
        $res .= convert_number($Ln) . " Lakh"; 
    }
	if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
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

$cheque_amt = 8747484 ; 
try
    {
   // echo convert_number($cheque_amt);
    }
catch(Exception $e)
    {
    //echo $e->getMessage();
    }
	
	

function convertcash1($num, $currency){ 
    if(strlen($num)>3){ 
            $lastthree = substr($num, strlen($num)-3, strlen($num)); 
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping. 

            $expunit = str_split($restunits, 2); 
            for($i=0; $i<sizeof($expunit); $i++){ 
            if($i==0) $explrestunits .= (int)$expunit[$i].","; // creates each of the 2's group and adds a comma to the end 
			else $explrestunits .= $expunit[$i].",";
            }    

            $thecash = $explrestunits.$lastthree; 
    } else { 
           $thecash = $num; 
    } 
    
    return $currency.$thecash.".00"; // writes the final format where $currency is the currency symbol. 
//}
 }
 
 
 
 

function moneyFormatIndia($num) {
   $explrestunits = "" ;
   if(strlen($num)>3) {
       $lastthree = substr($num, strlen($num)-3, strlen($num));
       $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
       $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
       $expunit = str_split($restunits, 2);
       for($i=0; $i<sizeof($expunit); $i++) {
           // creates each of the 2's group and adds a comma to the end
           if($i==0) {
               $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
           } else {
               $explrestunits .= $expunit[$i].",";
           }
       }
       $thecash = $explrestunits.$lastthree;
   } else {
       $thecash = $num;
   }
   return $thecash; // writes the final format where $currency is the currency symbol.
}
?>