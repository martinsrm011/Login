<?php
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$state = $_REQUEST['state'];
$aemp_id = $_REQUEST['aemp_id'];
$name1 = $_REQUEST['ce_name'];
$url = $_REQUEST['url'];
$ace_id = $_REQUEST['ace_id'];
$ce_id = $_REQUEST['ce_id'];
require_once __DIR__ . '/../DbConnection/dbConnect.php';
include('CommonReference/date_picker_link.php');

?>
<style type="text/css">

</style>
<html>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-11">
      <div class="portlet">
        <div class="portlet-body" id="main">
          <div id="new"></div>
          <h3 class="portlet-title"> <u>Employee Code Approval</u> </h3>
          <?php if($id!=''){ ?>
          <form id="demo-validation" method="post" action="<?php echo 'CommonReference/hrms_add_details.php?pid='.$pid.'&data=1'; ?>" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">
          <input type="hidden" name="id" id="id" value="<?php echo $id?>" />
          <?php
							$nav=$_REQUEST['nav'];
							$emp=$_REQUEST['emp_id'];
							if(isset($nav))
							{
								
								
							   if($nav!='') { ?>
          <div class="message_cu">
            <div style="padding: 7px;" class="alert <?php if($nav=='2_2_1' || $nav=='2_2_2' || $nav=='2_2_3' || $nav=='2_2_4') { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center"> <a aria-hidden="true" href="../HRMS/components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
              <?php
                  $status_cu = array('2_1_1'=>'Employee Details Approved Successfully And Employee ID:'.$emp,
				  						'2_1_2'=>'Employee Details Hold Successfully ',
										'2_1_3'=>'Employee Details Reject Successfully ',
									    '2_2_1'=>'Sorry, Please Try Again Employee Verification Details',
									    '2_5'=>'"CE ID: '.$id1.', Already Available, Sorry Please Try Again');
                  echo $status_cu[$nav];
                  ?>
              </b> </div>
          </div>
          <?php
         }
			  	 
							}
								$desig=array("EXE"=>"Exectiuve","CE"=>"Cash Executive","CVCE"=>"CVCE","CC"=>"Cash Collection Executive","MBC"=>"MBC","CVC"=>"Cash Van Custodian","GN"=>"Gunman","DR"=>"Driver",
"LD"=>"Loader","PR"=>"Processor","AM"=>"Assistant Manager","RM"=>"Risk Manager","MGR"=>"Manager","CHR"=>"Cashier","SE"=>"Senior Executive",
"BH"=>"Branch Head","RH"=>"Regional Head","DRH"=>"Deputy Regional Head","ACHR"=>"Assistant cashier","DM"=>"Deputy Manager","SRM"=>"Senior Risk Manager","SMGR"=>"Senior Manager",
"ARM"=>"Assistant Risk Manager","SMBC"=>"Senior MBC","OH"=>"Office Assistant/HouseKeeping ","GM"=>"General Manager","AGM"=>"Asst. General Manager","SV"=>"Supervisor","DIR"=>"Director","CFO"=>"Chief Finance Officer","CTO"=>"Chief Technology Officer","GUD"=>"Guards","IA"=>"Implementation Associate");

$dep=array("OP"=>"Operations","IT"=>"Information technology","BD"=>"Business Development","DM"=>"Data Management","CR"=>"Customer Relation
","BILL"=>"Billing","AF"=>"Accounts & Finance","AUD"=>"Audit","BNK"=>"Banking","ADM"=>"Admin","HR"=>"Human Resource","PAY"=>"Payroll","VLT"=>"Vault","RC"=>"Reconciliation","NF"=>"NOC & Fleet");
							
							  $sqlt="select * from hrms_empdet where r_id=".$id." "; 
							$row=mysqli_query($readConnection,$sqlt);
							$row1=mysqli_fetch_object($row);
							
            $doc = array();
						$duc_quer = "SELECT * FROM `hrms_empdoc` WHERE `r_id` = '$id'";
							$row_sql=mysqli_query($readConnection,$duc_quer);
							while ($docrow=mysqli_fetch_array($row_sql)) {
                 $doc[$docrow["doc_type"]] = $docrow["doc_path"];
               }
							
							?>
          <div id="shop_details_div">
          <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" class="shop_details"  >
             <div align="right">
                <?php if($emp!=''){?>
                                  
                                    <?php if($row1->pdesig1=='CE' || $row1->pdesig1=='CVCE') {?>
									  <a  name="Submit3"  class="button"  href="ce_agrement.php?&pid=ce-aggrement&id=<?php echo $id; ?> &act=1"  target="_blank" > <span class="label label-secondary demo-element">Print Agreement </span></a><?php } else {?>
                                        <a  name="Submit3"  class="button"  href="appointment_letter.php?&pid=appointment_letter&id=<?php echo $id;?>&act=1" target="_blank" > <span class="label label-secondary demo-element">Print Appoint Letter</span></a></div>
                                    
									<?php } }else{?>
                                     <a  name="Submit3"  class="button"  href="offer_letter.php?&pid=offer_letter&id=<?php echo $id;?>&act=1" target="_blank" > <span class="label label-secondary demo-element">Print Offer Letter</span></a></div><?php }?>
                                    
                                    
            <tr>
              <td  width="10%"><label>
                  <?php if($emp!=''){?>
                  Employee Id
                  <?php }else{?>
                  Ref.Docket.No
                  <?php }?>
                </label></td>
              <td align="center"width="3%"><b>:</b></td>
              <td width="40%"><?php if($emp==''){ echo $row1->docket_no;}else{ echo $emp;} ?>
                </b></td>
              <td><label id="point_type"></label></td>
              <td align="right"><label>Employee Name</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $row1->cname; if($emp!=''){ echo $emp; } ?></td>
              <td align="center"></b></td>
              <td><label id="point_pin"></label></td>
            </tr>
            <tr>
              <td ><label> Designation</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $desig[$row1->pdesig1]; ?></td>
              <td><label id="cust_code"></label></td>
              <td align="right"><label>Department </label></td>
              <td align="center"><b>:</b></td>
              <td><?php echo $dep[$row1->pdesig]; ?></td>
              <td align="center"></td>
              <td><label id="point_phone"></label></td>
            </tr>
            <tr>
              <td ><label>Location</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $row1->plocation; ?></td>
              <td><label id="cust_code"></label></td>
              <td align="right"><label>Pan Card No</label></td>
              <td align="center"><b>:</b></td>
              <td><?php echo $row1->pan_card_no; ?></td>
              <td align="center"></td>
              <td><label id="point_phone"></label></td>
            </tr>
            <tr>
              <td ><label>Date Of Join</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $row1->doj; ?></td>
              <td><label id="cust_code"></label></td>
              <td align="right"><label>Father's Name</label></td>
              <td align="center"><b>:</b></td>
              <td><?php echo $row1->father_name; ?></td>
              <td align="center"></td>
              <td><label id="point_phone"></label></td>
            </tr>
            <tr>
              <td ><label>Address</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $row1->address; ?></td>
              <td><label id="cust_code"></label></td>
              <td align="right"><label>Pin Code</label></td>
              <td align="center"><b>:</b></td>
              <td><?php echo $row1->pin; ?></td>
              <td align="center"></td>
              <td><label id="point_phone"></label></td>
            </tr>
            <tr>
              <td ><label>Appointment Type</label></td>
              <td align="center" width="3%"><b>:</b></td>
              <td><?php echo $row1->app_type; ?></td>
              <td><label id="cust_code"></label></td>
              <?php if($row1->app_type=='Replacement') {?>
              <td align="right"><label>Replace Id's</label></td>
              <td align="center"><b>:</b></td>
              <?php }?>
              <td><?php echo $row1->replace_id; ?></td>
              <td align="center"></td>
              <td><label id="point_phone"></label></td>
            </tr>
          </table>
          <h3 class="portlet-title"> <u>Document Upload Details</u> </h3>
          <div class="table-responsive">
            <table style="width:100%" class="table table-hover table-nomargin table-striped table-bordered ">
              <thead>
                <tr>
                  <th>Pan Card</th>
                  <th>Aadhaar Card</th>
                  <th>Employee Application Form</th>
                  <th>Background Verification Form</th>
                  <th>Employee Picture</th>
                  <th>Employee Sign</th>
                </tr>
              </thead>
              <tbody>
              <td>
                <?php
                if ($doc["04"] != "") {
                  if (file_exists("emp_docs/".$doc["04"])) {
                    $path1 = "emp_docs/".$doc["04"];
                  } else {
                    $path1 = "http://49.249.173.254/rcms/emp_docs/".$doc["04"];
                  }
                ?>
                <a href="<?php echo $path1;?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                <?php
                } else {
                  echo "No document available";
                }
                ?>
              </td>
                <td>
                  <?php
                  if ($doc["06"] != "") {
                    if (file_exists("emp_docs/".$doc["06"])) {
                      $path2 = "emp_docs/".$doc["06"];
                    } else {
                      $path2 = "http://49.249.173.254/rcms/emp_docs/".$doc["06"];
                    }
                  ?>
                  <a href="<?php echo $path2;?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                  <?php
                  } else {
                  echo "No document available";
                }
                  ?>
                </td>
               
                <td>
                  <?php
                  if ($doc["14"] != "") {
                    if (file_exists("emp_docs/".$doc["14"])) {
                      $path3 = "emp_docs/".$doc["14"];
                    } else {
                      $path3 = "http://49.249.173.254/rcms/emp_docs/".$doc["14"];
                    }
                  ?>
                  <a href="<?php echo $path3; ?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                  <?php
                  } else {
                  echo "No document available";
                }
                  ?>
                </td>
                <td>
                <?php
                if ($doc["10"] != "") {
                    if (file_exists("emp_docs/".$doc["10"])) {
                      $path4 = "emp_docs/".$doc["10"];
                    } else {
                      $path4 = "http://49.249.173.254/rcms/emp_docs/".$doc["10"];
                    }
                  ?>
                  <a href="<?php echo $path4; ?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                  <?php
                } else {
                  echo "No document available";
                }
                ?>
                </td>
                <td>
                  <?php
                  if ($doc["09"] != "") {
                    if (file_exists("emp_docs/".$doc["09"])) {
                      $path5 = "emp_docs/".$doc["09"];
                    } else {
                      $path5 = "http://49.249.173.254/rcms/emp_docs/".$doc["09"];
                    }
                  ?>
                  <a href="<?php echo $path5; ?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                  <?php
                  } else {
                  echo "No document available";
                }
                  ?>
                </td>
                <td>
                  <?php
                  if ($doc["20"] != "") {
                    if (file_exists("emp_docs/".$doc["20"])) {
                      $path6 = "emp_docs/".$doc["20"];
                    } else {
                      $path6 = "http://49.249.173.254/rcms/emp_docs/".$doc["20"];
                    }
                  ?>
                  <a href="<?php echo $path6; ?>" target="_blank"><span class="label label-secondary demo-element">View</span></a>
                  <?php
                  } else {
                  echo "No document available";
                }
                  ?>
                </td>
                  </tbody>
            </table>
          </div>
                <input type="hidden" name="state" value="<?php echo  $row1->state; ?>">
          <input type="hidden" name="desig" value="<?php  echo $row1->pdesig1; ?>">
           <input type="hidden" name="emp_name" value="<?php echo  $row1->cname; ?>">
          <input type="hidden" name="emp_doj" value="<?php  echo $row1->doj; ?>">
           <input type="hidden" name="emp_dob" value="<?php echo  $row1->dob; ?>">
          <input type="hidden" name="mobile11" value="<?php  echo $row1->mobile1; ?>">
           <input type="hidden" name="mobile21" value="<?php echo  $row1->mobile2; ?>">
          <input type="hidden" name="email" value="<?php  echo $row1->email; ?>">
             <input type="hidden" name="pin" value="<?php  echo $row1->pin; ?>">
           <input type="hidden" name="pan_card_no" value="<?php echo  $row1->pan_card_no; ?>">
          <input type="hidden" name="address" value="<?php  echo $row1->address; ?>">
           <input type="hidden" name="father_name" value="<?php echo  $row1->father_name; ?>">
          <input type="hidden" name="gender" value="<?php  echo $row1->gender; ?>">
           <input type="hidden" name="mstatus" value="<?php  echo $row1->mstatus; ?>">
		   <input type="hidden" name="emp_location" value="<?php  echo $row1->plocation; ?>">
              <input type="hidden" name="wstatus" value="<?php  echo $row1->wstatus; ?>">
			  <input type="hidden" name="region_name" value="<?php  echo $row1->region; ?>">
          <div class="form-group col-sm-3">
            <label for="name">
            <label class="compulsory"></label>
            Verification Date
            </label>
            <input type="text" id="popupDatepicker" name="app_date" class="form-control parsley-validated"  placeholder="Date Of Joinig" value="<?php echo date('d-m-Y') ?>" tabindex="1">
          </div>
          <div class="form-group col-sm-3">
            <label for="name">
            <label class="compulsory"></label>
            Verification By
            </label>
            <input type="text" id="veri_by" name="appr_by" class="form-control parsley-validated" value="<?php echo $user_name; ?>" placeholder="Employee Name" tabindex="2" readonly >
          </div>
          <div class="form-group col-sm-2">
            <label for="name">
            <label class="compulsory"></label>
            Status
            </label>
            <select id="app_status" name="app_status" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
              <option value="">Select </option>
              <option value="Y">Approved</option>
              <option value="H">Hold</option>
              <option value="R">Reject</option>
            </select>
          </div>
          <div class="form-group col-sm-3">
            <label for="name">
            <label class="compulsory"></label>
            Remarks
            </label>
            <textarea class="form-control parsley-validated" rows="2" cols="1" placeholder="Remarks" name="remarks" id="veri_remarks" value=""  tabindex="30"> <?php echo $row1->remarks; ?> </textarea>
          </div>
          <div class="clear"></div>
          <?php 
          if(!isset($_GET['emp_id'])){  ?>
          <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn" style="margin-top: 10px;">Save Employee Identity Details</button>
          <?php }}else{ ?>
  <table align="center"  class="table table-hover table-nomargin table-striped table-bordered " width="100%" id='to_load_unapprove_inline'>
  <thead>
    <tr>
    <th width="2%">S.No</th>
      <th  width="5%">Region</th>
      <th width="2%">Un Approved</th>
      <th  width="2%">Hold</th>
      <th  width="2%">Total</th>
    </tr>
  </thead>
  <tbody>
              <?php	
			$sql="select region_id,region_name from region_master where status='Y' ";
            $qud=mysqli_query($readConnection,$sql);
            $n=mysqli_num_rows($qud);	
            /*Display Details*/
            if($n>0) {
                $i = $start_limit+1;
                while($rd=mysqli_fetch_object($qud)) {   ?>
              <?php 
	    $select_all = "SELECT (SELECT count( r_id )FROM hrms_empdet WHERE status = 'U' AND region= '".$rd->region_name."') AS unpprove,
(SELECT count( r_id )FROM hrms_empdet WHERE status = 'H'AND region= '".$rd->region_name."') AS hold"; 
      $qud1=mysqli_query($readConnection,$select_all);
	  $row_det = mysqli_fetch_object($qud1);	
	   ?>
              <tr>
                <td align="center"><?php echo $i; ?></td>
                <td><?php echo $region=$rd->region_name; ?></td>
                <td align="center"><a href="#" rel="<?php echo $region=$rd->region_name;  ?>" class="unapproved" id="unapproved" onClick="unapproved('<?php echo $rd->region_name;?>')"><?php echo $row_det->unpprove; ?></a></td>
                <td align="center"><a href="#" rel="<?php echo $region=$rd->region_name;  ?>" class="hold" id="hold" onClick="hold('<?php echo $rd->region_name;?>')"><?php echo $row_det->hold; ?></a></td>
                <td align="center"><?php echo $row_det->unpprove+$row_det->hold; ?></td>
                <?php $i=$i+1; } ?>
              </tr>
              <?php		  
               
            }//else { ?>
              <!-- <tr>
                <td colspan="17" align="center"><b>Records Not Found</b></td>
              </tr> -->
              <?php	//}    ?>
            </tbody>
          </table>
          <div class="clear"></div>
          <div id="modal_msg"></div>
          <div class="clear"></div>
          <div class="portlet">
          <h3 class="portlet-title"> <u>Approved Details</u> </h3>
          <form id="demo-validation1" action="" data-validate="parsley" class="form parsley-form">
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory"></label>
              Region
              </label>
              <select id="region" name="region" class="form-control parsley-validated chosen-select" tabindex="58">
                <option value="">All</option>
                <?php
										//$sql_region = mysqli_query($readConnection,"SELECT region_name FROM region_master WHERE status='Y'");
										$sql_region= mysqli_query($readConnection,"select region_name from region_master where  status='Y'");
										while($res_region = mysqli_fetch_assoc($sql_region)) {
											?>
                <option <?php if($_REQUEST['region_name']==$res_region['region_name'])echo "Selected='Selected'";?> value="<?php echo $res_region['region_name']; ?>"><?php echo $res_region['region_name']; ?></option>
                <?php	
										}
										?>
              </select>
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory"></label>
              From Date
              </label>
              <input type="text" id="popupDatepicker" name="frm_date" class="form-control parsley-validated"  placeholder="From Date" value="<?php echo $res_emp->dob; ?>" tabindex="4">
            </div>
            <div class="form-group col-sm-3">
              <label for="name">
              <label class="compulsory"></label>
              To Date
              </label>
              <input type="text" id="popupDatepicker1" name="to_date" class="form-control parsley-validated"  placeholder="To Date" value="<?php echo $res_emp->dob; ?>" tabindex="4">
            </div>
            <div class="form-group  col-sm-3">
              <button type="button" class="btn btn-danger search_btn" id="search_criteria" style="margin-top: 23px;" onClick="search_key('1', '0')"tabindex="60">Search</button>
            </div>
          </form>
          <div class="clear"></div><br />
          <div class="clear"></div>
          <div id="view_details_indu"></div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
</div>
</div>
</body>
    </html><style type="text/css">
#checkbox-1-error, #pickup-error, #popupDatepicker-error, #customer-error {
	left: 10px;
	top: 77px;
	position: absolute;
}
</style>

    <script type="text/javascript">
function unapproved(regionnames){

$.ajax({
url:"HRMS/AjaxReference/hrmsLoadData.php",
type:"POST",
data:{pid:"unapproved", rel:regionnames},
success:function(msg){
  //alert(msg);
  $('#modal_msg').html(msg);



$("#to_load_unproved_details").DataTable({ ordering: false});
  
}
});
}

function hold(hold_region){
  $.ajax({
		url:"HRMS/AjaxReference/hrmsLoadData.php",
		type:"POST",
		data:{pid:"hold", rel:hold_region},
		success:function(msg){
			//alert(msg);
			$('#modal_msg').html(msg);



$("#to_load_hold_unprov").DataTable({ ordering: false});
			
		}
	});
}
	$(document).ready(function (){


    //initializeDataTable("#to_load_unapprove_inline");
    $("#to_load_unapprove_inline").DataTable({ ordering: false});
    
		$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$("#demo-validation").validate({
				rules:{
						
					client_id:	{
						required:true
					},
					
					region_id:{
						required:true
					}
					
				},
				messages:{
					
					client_id:{
						required:'Select Client.'
					},
					region_id:{
						required:'Select Region.'
					}
				}
			});	
    	}); 	
    </script>
    <script src="js/jquery.validate.js"></script>
    <script type="text/javascript">
$(document).ready(function(){
	// $(".unapproved").click(function(){
	// var rel=$(this).attr('rel');
	
	// });
});
$(document).ready(function(){
	// $(".hold").click(function(){
	// var rel=$(this).attr('rel');
	
	// });
});


function search_key(search_type, page_start) {

  var date1 = $('#popupDatepicker').val();
  var date2 = $('#popupDatepicker1').val();

  if(date1 == ""){
    alert("Select From date");return false;
  }
  if(date2 == ""){
    alert("Select To date");return false;
  }
  
		$('#view_details_indu').css('display', '');
		$.ajax({
			type : "POST",
			url : "HRMS/AjaxReference/hrmsLoadData.php",
			data : 'pgn=1&start_limit=' + page_start + '&per_page=' + $('#per_page').val() + '&end_limit=10&types=1&pid=<?php echo $pid; ?>&from_date=' + $('#popupDatepicker').val()+'&to_date=' + $('#popupDatepicker1').val()+'&region='+$('#region').val(),
			beforeSend : function() {
				$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
			},
			success : function(msg) {
				$('#view_details_indu').html(msg);
				$('.search_field').css('display', '');

        


$(".to_load_all_unapproved_data").DataTable({ ordering: false});
			}
		});
	}

	</script>
	
	