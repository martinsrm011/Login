
<?php 
if(!isset($_SESSION)) {	session_start(); }
// include('bar/BarcodeGenerator.php');
// include('bar/BarcodeGeneratorPNG.php');
// include('bar/BarcodeGeneratorSVG.php');
// include('bar/BarcodeGeneratorHTML.php');

$pid = $_REQUEST['pid'];
include('DbConnection/dbConnect.php');
$user=$_REQUEST['user_name'];
	// $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
	// $generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
	// $generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
	
	
?>
<html>
<style>

p{
	font-size:19px;
}
.doc_st{
	line-height:1.3em;
	text-align:justify;
}

  
@media print{
    body{
        counter-reset: pageNumber;	
	}
	#foot4{
   width:100%;
   position:static;
   margin-top:480px;
	}
	#foot3{
   width:100%;
   position:static;
   margin-top:00px;
	}
    .pager{
        min-height: 400px;		
    }
    .page-breaker{
        page-break-after: always;
        text-align: right;	
    }
    .page-breaker:before{
		 counter-increment: pageNumber;
        content: "Page:" counter(pageNumber) " of  8";
		
    }
	
}
</style>

<?php 
$ce_id=$_REQUEST['id'];
 
 //echo "select emp_id from login where user_name='".$user_name."' and status='Allowed'";
$sql_l=mysql_query("select emp_id from login where user_name='".$user."' and status='Allowed'");
$row_l=mysql_fetch_object($sql_l);

//echo "select emp.cname,dm.desig from hrms_empdet emp inner join desig_master dm on dm.desig_code=emp.pdesig1 and emp.status='Y' and dm.status='Y' and emp.emp_id='".$row_l->emp_id."' ";
$sql_emp=mysql_query("select emp.cname,dm.desig from hrms_empdet emp inner join desig_master dm on dm.desig_code=emp.pdesig1 and emp.status='Y' and dm.status='Y' and emp.emp_id='".$row_l->emp_id."' ");
$row_emp=mysql_fetch_object($sql_emp);



$sql=mysql_query("select * from hrms_empdet where r_id='".$ce_id."' and status='Y'");
$row=mysql_fetch_object($sql);
$rr=$row->emp_id;
$rr_age  =$row->dob;
$today = date("Y-m-d");
$diff = date_diff(date_create($rr_age), date_create($today));
$cl_age=$diff->format('%y');



$sel3=mysql_query("select * from region_master where status='Y' and region_name='".$row->region."'");
$res3=mysql_fetch_array($sel3);

$sel_reg=mysql_query("select * from hrms_empdet where status='Y' and cname='".$res3['head_name']."'");
$res_reg=mysql_fetch_array($sel_reg);
?>
 <div id="printableArea">
 
<body style="padding:5% 10% 6% 1%;">
<div style="margin-bottom: 25em;"></div>
<div class="pager">
<table width="800px" align="center"  cellspacing="0" cellpadding="5">
<thead>
<tr>
<th align="center"><p>&nbsp;</p><p>&nbsp;</p></th>
</tr>
</thead>
<tbody>
<tr>
<td align="center">[To be executed on stamp paper of applicable value]</td>
</tr>
<tr>
<td align="center"><strong>Agreement</strong></td>
</tr>
<tr>
<td><p>This Agreement is entered into as of  <strong><?php echo date('dS',strtotime($row->aggr_date)); ?></strong> day of
<strong><?php echo date('F',strtotime($row->aggr_date)); ?></strong> of <strong><?php echo date('Y',strtotime($row->aggr_date)); ?> at <?php echo $row->region.'.';?></strong> 

 </p>
 </td>
</tr>
<tr><td><p>By and Between: <br></p></td>
<tr><td><p class="doc_st"><strong>1. Radiant Cash Management Services Ltd.,</strong> is situated at <?php echo $res3['address']; ?>, a private company incorporated in under Companies Act, 1956, having its registered address at No 28, Vijayaraghava Road, T Nagar, Chennai- 600017  (the <strong>"Company"</strong> which expression shall, unless the context requires otherwise, mean and include its successors and permitted assigns); and</p>
</td></tr>
<tr><td><p class="doc_st"><strong>2. Mr <?php echo $row->cname;?></strong> son of <strong><?php echo $row->father_name;?></strong>, aged about <strong><?php echo $cl_age;?></strong> years and currently residing at <strong><?php echo ucwords($row->address);?></strong>, (hereinafter referred to as the <strong>"Service Provider").</strong> 
 <p class="page-breaker"></p>
 

<p class="doc_st"><br>The Company and the Service provider are individually referred to as <strong>"Party"</strong> and collectively as <strong>"Parties"</strong>.<br>
<br>As a condition of the Service provider  being engaged (or his engagement being continued) with the Company, and in consideration of his relationship with the Company and the receipt of the compensation now and hereafter paid/ payable to the Service provider  by the Company, the Service provider  hereby agrees to the following:</p>
<ul style="list-style:decimal;text-align:justify;">
	<li style="font-size: 18px;"><strong>Service provider  Relationship: </strong>
	<p>On and subject to the terms and conditions of this Agreement and in accordance with applicable law, the Company hereby agrees to engage the Service provider, and the Service provider hereby agrees to be engaged by the Company as a Service provider  (or such other position as may be designated by the Company and accepted by the Service provider  from time to time). </p>
	</li>	
	<li style="font-size: 18px;"><strong>Performance of Duties: </strong>The Service provider  shall perform the following duties:
		<ul style="list-style:lower-roman;">
			<li><p>	The Service provider will be responsible for the collection of cash/cheque/instruments/demand drafts/gold and other valuables including foreign currency <strong>("Valuables")</strong> from the pick-up point allocated to him at the specified time and date. </p></li>
			<li><p>The Service provider assures to deliver/deposit Valuables immediately at the allotted time and place, if delayed the same need to be periodically updated to the respective Manager with cause of delay. </p></li><p class="" id="foot1"></p>
			<li><p>During the period of his engagement with the Company, the Service provider  assures to devote his best efforts to the interests of the Company and is well aware not to engage in any activities like theft, malpractices, fraudulent behaviours which leading to material loss and detrimental to the best interests of the Company.  </p></li>
			<li><p>The Service provider acknowledges Company norms to maintain proper registers/ records of the daily cash collections handled with necessary vouchers, receipts and supporting documents. The accounts, records and other documents will be inspected by the Company's representatives every quarter. </p></li>
		</ul>
	</li>
	
	<li style="font-size: 18px;"><strong>Confidentiality and Privacy:</strong>
	<p>During the term of engagement and for a period of 12 (twelve) months thereafter, the Service provider  shall not use or disclose any Confidential Information of the Company to a third party without the express, prior and written consent or direction of the Company. The Service provider shall use the Confidential Information only in connection with and to the extent required for the  <p class="page-breaker"></p>purposes of this Agreement and shall not use the Confidential Information for any purpose or circulate it with any other team member without Company's instruction which will eventually lead to disciplinary action upon Company's sole decision depending on the incident analysis report. For this purpose, <strong>"Confidential Information"</strong> shall mean any oral or written information (in whatever media or form, whether tangible or otherwise) disclosed by or on behalf of the Company to the Service provider  that is marked or designated as confidential, is treated by the Company as confidential, or any other information of such nature as may be reasonably construed to be confidential, and includes without limitation: 
	</p>
		<ul style="list-style:lower-roman;">
			<li><p>all information of the Company marked or otherwise designated confidential, restricted, proprietary or with a similar designation;</p></li>
			<li><p>information which relates to the financial position of the Company or the internal management and structure of the Company, or the personnel, policies and strategies of the Company; </p></li>
			<li><p>Company intellectual property, trade secrets, past, present and future business strategies, business facilities, resources, operations, requirements, methods, customer information, know-how, inventions, discoveries, and improvements of the Company;</p></li>
			<li><p>employee information of the Company, including names, salaries, employee agreements, employee profiles and other information; </p></li>
			<li><p>any other information provided to the Service provider  in the course of his engagement with the Company, including information relating to any customers of the Company;</p></li>
			<li><p>this Agreement; or</p></li>
			<li><p>any other information of such nature as may be reasonably construed to be confidential or proprietary.</p></li>
		</ul>
		<p>Upon discovery of or reasonable suspicion of any unauthorized use or disclosure of Confidential Information, Service provider should inform the Company immediately and prevent further unauthorized use. 
		</p>
	</li>
	<li style="font-size: 18px;"><strong>Remuneration:</strong> In consideration of your compliance with the terms of this Agreement and your engagement with the Company, the following are the terms of the remuneration payable by the Company to the Service provider :
	<br>
	<br>
		<table width="100%" align="center"  cellspacing="0" cellpadding="5" border="1" style="border-collapse:collapse;">
	  <tr>
		<th>Particulars</th>
		<th>Amount (Rs.)</th>
	  </tr>
	  <tr>
	  <td>Service Charges</td>
	  <td></td>
	  </tr>
	  <tr>
	  <td>Telephone Charges</td>
	  <td></td>
	  </tr>	 
	  <tr>
	  <td align="center"><b>Total</b></td>
	  <td></td>
	  </tr>
	</table> <p class="page-breaker"></p>
	<p>Other reimbursement as per actuals.       </p>
	<p>In the performance of this Agreement, the Service provider shall act as an independent contractor, and he understands and acknowledges that he will not be entitled to any other benefits other than mentioned above  </p>
	</li>
	
	<li style="font-size: 18px;"><strong>Indemnity: </strong>
	<p>The Service provider  further agrees to defend, indemnify, hold harmless and keep indemnified without limitation the Company against any and all actual or alleged loss, damage or third-party claim which the Company may suffer arising from or relating to any loss, theft or embezzlement of Valuables or any breach of this Agreement by the Service provider  in connection with 
	</p>
<ul style="list-style:lower-alpha;">
			<li style="margin-left: -15px !important;"><p>deficiency in services or act of commission / omission on the services</p></li>
			<li style="margin-left: -15px !important;"><p>breach of Clause 3 Confidentiality and Privacy obligation under this Agreement</p></li>
		</ul>
	</li>
	
	<li style="font-size: 18px;"><strong>Company's right to injunctive relief: </strong>
	<p>Notwithstanding the above, the Service provider  acknowledges that monetary damages may not be a sufficient remedy for the Company for any breach of any of the Service provider's obligations herein provided and the Service provider  further acknowledges that the Company is entitled to specific performance or injunctive relief, as may be appropriate, as a remedy for any breach or threatened breach of those obligations by the Service provider , in addition to any other remedies available to the Company in law or in equity.
	</p>
	</li>
	
	<li style="font-size: 18px;"><strong>Term and Termination:</strong>
	<p>This Agreement is valid for a period of 3 year from the effective date and shall be renewed with mutual consent. Either Party may terminate this Agreement, by providing a notice of one month to the other Party. The Company may, in lieu of such notice, pay to the Service provider the applicable remuneration for such period.
	</p>
	<p>However, in the following circumstances the Service provider's services may be terminated by the Company, without notice in case of the Company has reason to believe the Service provider has committed any of the following acts:
	</p>
	
		<ul style="list-style:lower-roman;text-align:justify;">
			<li><p>Theft, forgery, deception, dishonesty with Company property with intent for personal or professional gain;</p></li>
			<li><p>Misappropriation of customers cash in any manner, whether for temporary use and replacement or otherwise;</p></li>
			<li><p>Unauthorized failure to deposit the collected cash directly from collection point;  </p></li> <p class="page-breaker"></p>
			<li><p>Misuse or tampering of collection voucher/receipts in any manner; and</p></li>
			<li><p>Any form of misbehaviour with the co-employees, insubordination, drunkenness during service or found within the organization or in the vicinity thereof;</p></li>
			<li><p>Assisting/accompanying/deputing unauthorized person in dealing with customers cash;</p></li>
			<li><p>Indecent assault of any Company employee;</p></li>
			<li><p>Criminal conviction by court of law;</p></li>
			<li><p>Habitual neglecter of his/her duties, despite written warning;</p></li>
			<li><p>Any activity, which brings disrepute to the Company; and</p></li>
			<li><p>Any wilful misconduct towards the members of the opposite sex.</p></li>
			<li><p>Violation of SOPs on cash pick or other assigned activities.</p></li>
		</ul>
	
	</li>
	
	<li style="font-size: 18px;"><strong>Consequences of Termination: </strong>
		<p>In the case of termination of the Service provider's engagement with the Company for any reason whatsoever or on written request by Company, the Service provider  shall immediately and at the time of notification of cessation, return to the Company, all Valuables and Confidential Information and all papers and documents, information recorded or held electronically or otherwise, materials, equipment, properties and related items of the Company which may be in the Service provider's possession at such time in such manner deemed appropriate for disposal. The obligations of the Service provider under Clauses 3 (Confidential Information), and Clause 5 (Indemnity), the rights of the Company under Clause 6 (Company's right to injunctive relief) and Clause 14 (Code of Conduct) shall survive and continue to operate even after the termination of the engagement. </p>
	</li>
	
	<li style="font-size: 18px;"><strong>Governing Law and Jurisdiction:</strong>
		<p>This Agreement shall be governed by and be construed in accordance with the laws of [India] and the courts at [Chennai] shall have exclusive jurisdiction on the matters arising from the Agreement, without regard to the principles of conflicts of laws. </p>
	</li>
	
	<li style="font-size: 18px;"><strong>No Waiver: </strong>
		<p>The failure by the Company to insist, in one or more instances, upon strict performance of the obligations under this Agreement, or to exercise any rights contained herein, shall not be construed as waiver, or relinquishment for the future, of such obligation or right, which shall remain and continue in full force and effect. <p class="page-breaker"></p> No failure or delay by any party in exercising any right, power or privilege hereunder shall operate as a waiver thereof. The rights and remedies herein provided shall be cumulative and not exclusive of any rights or remedies provided by law. </p>
	</li>
	
	<li style="font-size: 18px;"><strong>Severability:</strong>
		<p>In the event that any restriction or limitation under this Agreement is found to be unreasonable or otherwise invalid in any jurisdiction, in whole or in part, the Service provider hereby acknowledges and agrees that such restriction or limitation shall remain and be valid in all other jurisdictions covered by the territorial scope of his obligations hereunder. </p>
	</li>
	
	<li style="font-size: 18px;"><strong>No Assignment:</strong>
		<p>This Agreement is personal in nature to the Service provider and the Service provider cannot assign this Agreement without the prior written consent of the Company. The Company shall be entitled to assign its rights and obligations under this Agreement without the prior written consent of the Service provider. </p>
	</li>
	
	<li style="font-size: 18px;"><strong>Representation:</strong>
		<p>Service provider  thereby guarantees to Company that he/she has obtained  necessary certificate as applicable to enter into and perform this Agreement, and that this Agreement will constitute a binding contract, enforceable against Service provider  in accordance with its terms in performance of its duties, responsibilities, and obligations under this Agreement in compliance with all applicable law also affirms that his/her representations to Company are complete and accurate, not misleading or deceptive and may be relied on by Company in entering into this Agreement. 

</p>
	</li>
	
	<li style="font-size: 18px;"><strong>Code of Conduct: </strong>
		<p>Service provider during the course of his engagement with the Company assures to maintain professional ethics and respect other employee's integrity by adhering to appropriate standards of code of conduct as set out in this clause in order to enhance the reputation of the Company. Service provider  assures to understand that breach of this Clause 14 will result in enforcement of disciplinary action as per Company policy not limiting to potential dismissal or termination of employment but any other legal action as available or all the above together, as applicable. While in Service under this Agreement or post termination, Service provider  ensures not to claim innocence with the given code:</p>
		
			<p>-	Be known that you are not permitted to carry on any business or profession or enter into any part time job in any capacity, or provide services or be employed by or engage with any other firm, company or person. You hereby oblige to be a full timer and devote your whole time and attention to perform and promote the interest of the Company;</p> <p class="page-breaker"></p>
			<p>-	Be known that you are to adhere to the moral standards and Company discipline as updated from time to time with no anticipated or actual participation directly or indirectly in any individual or concerted action or malicious or illegal activity against the Company or society, also ensure not to participate or be part of any illegal strike against Company.</p>
			<p>-	Be known that you shall not indulge in any activity(ies) which may affect, deteriorate, Lessen, shortage, harm, damage or cause injury to property(ies), belonging(s), asset[s), stock(s) and / or cause harm, harass, injury to person, contract staff, staff of the company directly or indirectly. </p>
			<p>-	Be known that you shall not indulge in any act(s) and / or activity(ies) which will result in moral turpitude, misconduct, illegal, unethical, immoral, antisocial, crime/fraudulent activities, mis-behavior, personal work at business hours, dishonesty, refuse to accept the orders of Management, high absenteeism or absconding without proper notice/approval of the management, misuse of Company’s resource, material misstatement, acceptance of bribe internally or externally, etc.</p>
			<p>-	Be known that you assure to accept and undertake the task(s) entrusted by the company on daily bases and abide by the Organizational objectives which will he laid out from time to time and work towards the betterment of the Company. </p>
			<p>-	Be known that you assure to accept and undertake the task(s) entrusted by the company on daily bases and abide by the Organizational objectives which will he laid out from time to time and work towards the betterment of the Company. </p>
			<p>-	Be known that you are to adhere to the maintenance of Company secrecy and confidentiality with regards to any day-to-day activity updates or sharing information about the company, and assure not to deal in any activity(ies) via espionage, theft, embezzlement or any other act(s) which will be detrimental to the interest(s) of the company, directly or indirectly now or later.</p>
		
	</li>
	
	
	
	
	
	<p class="page-breaker" id="foot3"></p>
	<p><Strong>IN WITNESS WHEREOF,</strong> the Parties have entered into this Agreement on the day, year and place first above written.


</p>
</ul>
</td>
</tr>

<tr>
<td>
	<table width="100%" align="center"  cellspacing="0" cellpadding="5" border="1">
	  <tr>
		<td width="50%"><p><strong>For Radiant Cash Management Services Ltd:</strong></p>
		<p>&nbsp;</p>
		<p>Authorized Signatory</p>
		<p>Name:</p>
		<p>Designation:</p>		
		</td>
		<td width="50%"><p><strong>For the Service provider :</strong></p>

		<p>Name: <?php echo $row->cname;?></p>
		<p>Date:</p>
		
		</td>
		
	  </tr>
	</table>
	
	
</td>
</tr>

<!--<tr style="float:left;">
<td>
<?php
// $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'phpqrcode/temp'.DIRECTORY_SEPARATOR;
    // $PNG_WEB_DIR = 'phpqrcode/temp/';

    // include "phpqrcode/qrlib.php";    
    
    // if (!file_exists($PNG_TEMP_DIR))
        // mkdir($PNG_TEMP_DIR);
    
    
    // $filename = $PNG_TEMP_DIR.'test.png';
    
   // $errorCorrectionLevel = 'L';
 // $matrixPointSize = 2;
// // $requ = $_REQUEST['ce_id']."/".$res1['ce_name']."/".$res1['created_date'];
// $requ = "CE Id : ".$row->emp_id."\nCE Name : ".$row->cname."\nLocation:".$row->plocation;
  // $filename = $PNG_TEMP_DIR.'test'.md5($requ.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        // QRcode::png($requ, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
  // $test=$PNG_WEB_DIR.basename($filename); 
   // $datetime=date('d-m-Y H:i:s');
   ?>
   <img src="<?php echo $test; ?>" style="max-width: 100%;float: right; text-align:right;padding-top:20px;margin-left:40px;"/>
   </td>
</tr>-->

</tbody>
<tfoot>
<tr>
            <!-- Add Page Numbering in HTML using &p; and &P 
            <td style="width: 90%">Page <span style="color: navy; font-weight: bold">&p;</span> of <span style="font-size: 16px; color: green; font-weight: bold">&P;</span> pages
            </td>-->
</tr>
</tfoot>
</table>
<p class="page-breaker" id="foot4"></p>
</body>
</div>
</div>
</html>

<script type="text/javascript">
function printDiv(divName) 
{
 
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
	
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;

}


</script>