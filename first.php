<?php
if (!isset($_SESSION)) {
  session_start();
}

$HrmsJoinDatePermission = "HrmsJoiningDatePermission";
$people_join_date_permission = array();
if (!empty($_SESSION['other_permissions'])) {
  $people_join_date_permission = explode(',', $_SESSION['other_permissions']);
}



$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$state = $_REQUEST['state'];
$aemp_id = $_REQUEST['aemp_id'];
$name1 = $_REQUEST['ce_name'];
$url = $_REQUEST['url'];
$ace_id = $_REQUEST['ace_id'];
$ce_id = $_REQUEST['ce_id'];
$per = $_SESSION['per'];

include('CommonReference/date_picker_link.php');
require_once __DIR__ . '/../DbConnection/dbConnect.php';

$visib = array('Admin', 'HR');
/* **********auto increment*********/
$query = "select max(docket_no) as id from hrms_empdet";
$exec = mysql_query($query);
$max_id = mysql_fetch_object($exec);
if ($max_id->id != NULL) {

  $query2 = "select docket_no from hrms_empdet where docket_no='" . $max_id->id . "'";
  $exec = mysql_query($query2);
  $row_btn_code = mysql_fetch_object($exec);
  //$array = explode('_',$row_btn_code->shop_id);
  //$array[1] = str_pad($array[1]+1, 4, '0', STR_PAD_LEFT);
  $a = substr($row_btn_code->docket_no, 6);
  $b = substr($row_btn_code->docket_no, 0, 6);
  //echo $a+1; die;
  //echo $add=$a+00001;
  $res = str_pad($a + 1, 3, '0', STR_PAD_LEFT);
  $result = date('ymd') . $res;
} else {
  $result = date('ymd') . '001';
}
?>

<style type="text/css">
  #ce_status_chosen {
    width: 100% !important;

  }

  #id_proof_chosen,
  #bank_acc_chosen,
  #branch_id_chosen,
  #emp_current_status_chosen,
  #emp_bank_chosen {
    width: 100% !important;
  }

  .autocomplete-suggestions {
    border: 1px solid #999;
    background: #fff;
    cursor: default;
    overflow: auto;
  }

  .autocomplete-suggestion {
    padding: 10px 5px;
    font-size: 1.0em;
    white-space: nowrap;
    overflow: hidden;
  }

  .autocomplete-selected {
    background: #f0f0f0;
  }

  .autocomplete-suggestions strong {
    font-weight: normal;
    color: #3399ff;
  }
</style>


<?php
$id = $_REQUEST['id'];
if ($r_id != '') {

  $edu = mysql_query("select * from hrms_edu_details where r_id ='" . $id . "' ");

  $select_cont_type = mysql_query("select * from hrms_empdata where r_id ='" . $id . "' ");
  if (mysql_num_rows($select_cont_type) > 0) {
    $cont_type = 1;
  } else {
    $cont_type = 0;
  }
}
if ($id != '') {
  //echo "select * from cm_emp_basic_info ebi join checkmate_pay_info cpi on ebi.emp_i=cpi.id where ebi.id='".$id."' and ebi.status='Y'"; die;
  //$select_data = mysql_query("select * from cm_emp_basic_info ebi join checkmate_pay_info cpi on ebi.emp_id=cpi.id where ebi.id='".$id."' and ebi.status='Y'");

  $select_data = mysql_query("select * from hrms_empdet where r_id ='" . $id . "' and status='Y'");
  $res_emp = mysql_fetch_object($select_data);




  $edu = mysql_query("select * from hrms_edu_details where r_id ='" . $id . "' and status='Y' ");
  if (mysql_num_rows($edu) > 0) {
    while ($res_edu = mysql_fetch_object($edu)) {
      $edu_id = $res_edu->edu_id;
      $course = $res_edu->course;
      $spec = $res_edu->spec;
      $frm_pre = $res_edu->from_period;
      $to = $res_edu->to_period;
      $inst = $res_edu->univ;
      $pree = $res_edu->per;
    }
  }


  $edu1 = mysql_query("select * from hrms_exp_details where r_id ='" . $id . "' and status='Y' ");
  $res_exp = mysql_fetch_object($edu1);


  //$sql_location = mysql_query("SELECT * FROM location_master WHERE loc_id='".$res_emp->location_id."' AND stastus='Y'");
  //$res_location = mysql_fetch_object($sql_location);

  //	$sql_region = mysql_query("SELECT * FROM region_master WHERE states LIKE '%".$res_location->state."%'");
  //	$res_region = mysql_fetch_object($sql_region);

  //echo $res_emp->rad_doj; die;

}
?>

<!------------------------ For New DatePicker Start------------------------------->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!------------------------New DatePicker End-------------------------------------->
<!--<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>-->
<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="js/currency-autocomplete.js"></script>
<div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-11">
      <div class="portlet">
        <h3 class="portlet-title"> <u>Basic Cash Executive Details</u> </h3>
        <div id="load_lod_shop1" style="display:none;float:left; width:100%; text-align:center; padding:3px;" class="alert alert-danger"></div>
        <div id="load_lod_shop" style="display:none;float:left; width:100%;" class="alert"></div>
        <?php if ($nav != '') { ?>
          <div class="message_cu">
            <div style="padding: 7px;" class="alert <?php if ($nav == '2_2_1' || $nav == '2_2_2' || $nav == '2_2_3' || $nav == '2_2_4') {
                                                      echo 'alert-danger';
                                                    } else {
                                                      echo 'alert-success';
                                                    } ?>" align="center"> <a aria-hidden="true" href="../HRMS/components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
                <?php
                $status_cu = array(
                  '2_1_1' => 'New Employee Details Saved',
                  '2_3_1' => 'Employee Details Update Sucessfully',
                  '2_2_1' => 'Sorry, Please Try Again Employee Personal Details',
                  '2_2_2' => 'Sorry, Please Try Again Employee Educational Details',
                  '2_3_6' => 'Selected Employee Contract Details Updated',
                  '2_5' => '"CE ID: ' . $id1 . ', Already Available, Sorry Please Try Again',
                  '2_13' => 'Employee Details Deleted Sucessfully'
                );
                echo $status_cu[$nav]; 
                ?>
              </b> </div>
          </div>
        <?php }
        ?>


        <div class="tab-content" id="myTab1Content">
          <div id="personal" class="tab-pane fade active in">
            <form id="demo-validation" method="post" action="<?php echo 'CommonReference/hrms_add_details.php?pid=' . $pid . '&data=1'; ?>" data-validate="parsley" autocomplete='off' class="form parsley-form" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />

              <?php if ($res_emp->emp_id == '') { ?>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory">*</label>
                    Ref.Docket.No
                  </label>
                </div>
                <div class="form-group col-sm-3">
                  <input type="text" id="docket_no" name="docket_no" class="form-control parsley-validated" value="<?php echo $res_emp->docket_no;
                                                                                                                    echo $result ?>" placeholder="Employee ID" tabindex="1" style="background-color : #FBD5EC;" readonly>
                </div>
                <div class="form-group col-sm-1"></div>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory">*</label>
                    Appointment Type
                  </label>
                </div>
                <div class="form-group col-sm-3">
                  <select id="app_type" name="app_type" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
                    <option value="">Select </option>
                    <option value="New" <?php if ($res_emp->app_type == 'New')  echo 'selected="selected"';  ?>>New</option>
                    <option value="Replacement" <?php if ($res_emp->app_type == 'Replacement')  echo 'selected="selected"'; ?>>Replacement</option>
                  </select>
                </div>
                <div class="clear"></div>
                <div id="Replacement" class="repl_staff" style="display:none">
                  <div class="form-group col-sm-2">
                    <label for="name">
                      <label class="compulsory">*</label>
                      Replacement CE Id
                    </label>
                  </div>
                  <div class="form-group col-sm-3">

                    <input type="text" id="replace_id" name="replace_id" class="form-control parsley-validated" value="<?php echo $res_emp->replace_id; ?>" <?php if ($id == '') { ?> onblur="dublicate_replaceid()" <?php } ?> onkeyup="nospaces(this)" placeholder="Replacement CE Id" tabindex="2">
                  </div>
                  <div class="form-group col-sm-1"></div>
                  <div class="form-group col-sm-2">
                    <label for="name">
                      <label class="compulsory">*</label>
                      Relieving Date
                    </label>
                  </div>
                  <div class="form-group col-sm-3">

                    <input type="text" id="popupDatepicker3" name="dor" class="form-control parsley-validated" placeholder="Relieving Date" value="<?php echo $res_emp->reliv_date; ?>" tabindex="2">
                  </div>
                </div><?php } else { ?>

                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory">*</label>
                    CE Id
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <?php
                      //$visib = array('admin', 'shaila', 'riyas','harishg','surya');
                      // $visib = array('admin','shaila','harishg','surya','Joy','prashanthr','Shahul','sasi','blessyshalom');
                  ?>
                  <input type="hidden" id="replace_id" name="replace_id" class="form-control parsley-validated" value="<?php echo $res_emp->replace_id; ?>">
                  <input type="text" id="emp_id" name="emp_id" class="form-control parsley-validated" value="<?php echo $res_emp->emp_id; ?>" placeholder="CE ID" tabindex="1" readonly="readonly">
                </div><?php } ?>

              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  CE Name
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="cname" name="cname" class="form-control parsley-validated" value="<?php echo $res_emp->cname; ?>" placeholder="CE Name" tabindex="2" <?php if (!in_array($per, $visib) && $id != '') { ?>readonly <?php } ?>>
              </div>
              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Gender
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select id="gender" name="gender" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2" <?php if (!in_array($per, $visib) && $id != '') { ?> disabled="disabled" <?php } ?>>
                  <option value="">Select Gender</option>
                  <option value="Male" <?php if ($res_emp->gender == 'Male')  echo 'selected="selected"';  ?>>Male</option>
                  <option value="Female" <?php if ($res_emp->gender == 'Female')  echo 'selected="selected"';  ?>>Female</option>
                </select>
              </div>
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Date of Birth
                </label>
              </div>
              <div class="form-group col-sm-3">
                <input type="text" id="popupDatepicker1_modified" name="dob" class="form-control parsley-validated" placeholder="Date Of Birth" value="<?php if ($id) echo date('d-m-Y', strtotime($res_emp->dob)); ?>" tabindex="3" <?php if (!in_array($per, $visib) && $id != '') { ?>readonly <?php } ?>>
              </div>
              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Age
                </label>
              </div>
              <div class="form-group col-sm-3">
                <input type="text" id="age" name="age" class="form-control parsley-validated" placeholder="Age" style="background-color : #FBD5EC;" tabindex="4" readonly>
                <span id="age_id" style="color:red;"></span>
              </div>
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Department
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select name="pdesig" class="form-control parsley-validated chosen-select" id="pdesig" tabindex="5" <?php if (!in_array($per, $visib) && $id != '') { ?> disabled="disabled" <?php } ?>>
                  <option value="">Select</option>
                  <option value="OP" <?php if ($res_emp->pdesig == 'OP')  echo 'selected="selected"'; ?>>Operations</option>
                </select>
              </div>
              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Designation
                </label>
              </div>
              <div class="form-group col-sm-3">
                <select name="pdesig1" class="form-control parsley-validated chosen-select pdesig1" id="pdesig1" onchange="load_status()" tabindex="6" <?php if (!in_array($per, $visib) && $id != '') { ?> disabled="disabled" <?php } ?>>
                  <option value="" selected="selected">Select</option>
                  <?php
                  //$sql_region = mysql_query("SELECT region_name FROM region_master WHERE status='Y'");
                  $desis_sql = "select desig_code,desig from desig_master where status='Y' AND (desig_code='CE' OR desig_code='CCE' OR desig_code='CVCE')  ";
                  $deg_s = mysql_query($desis_sql);
                  while ($res_deg = mysql_fetch_object($deg_s)) {
                  ?>
                    <option value="<?php echo $res_deg->desig_code; ?>" <?php if ($res_deg->desig_code == $res_emp->pdesig1) echo "Selected='Selected'"; ?>><?php echo $res_deg->desig; ?></option>
                  <?php

                  }
                  ?>
                </select>
              </div>
               <!--New for align ---->
               <div class="clear"></div>
              <!-- align END----->
              <div class="status_ld">
                <div class="form-group col-sm-2">
                  <label for="name"><label class="compulsory">*</label>Status</label>
                </div>
                <div class="form-group col-sm-2">
                  <select id="ce_status" name="ce_status" class="form-control parsley-validated chosen-select" data-required="true" tabindex="27" <?php if (!in_array($per, $visib) && $id != '') { ?> disabled="disabled" <?php } ?>>
                    <option value="">Select Status</option>

                    <option <?php if ($res_emp->wstatus == "Ready") echo "Selected='Selected'"; ?> value="Ready">Ready</option>
                    <option <?php if ($res_emp->wstatus == "Active") echo "Selected='Selected'"; ?> value="Active">Active</option>
                    <option <?php if ($res_emp->wstatus == "Dormant") echo "Selected='Selected'"; ?> value="Dormant">Inactive / Dormant</option>
                    <!--<option <?php if ($res_emp->wstatus == "Inactive") echo "Selected='Selected'"; ?> value="Inactive">Inactive</option>-->
                    <!--  <option <?php if ($res_emp->wstatus == "Backup") echo "Selected='Selected'"; ?> value="Backup">Terminated</option>-->
                  </select>
                </div>
              </div>
    <!-------------For Status Remarks------------------------------------->

            <div id="to_append_inactive_status" class="to_append_inactive_status_tw" style="display:none">
              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                              <label for="name">
                                <label class="compulsory">*</label>
                                Status Remarks
                              </label>
                            </div>
              <div class="form-group col-sm-3">
                              <input type="text" id="inactv_remarks" name="inactv_remarks" class="form-control parsley-validated" placeholder="Remarks" value="<?php echo $res_emp->inactive_status_remarks;  ?>"  tabindex="28" >
                              <span id="inactv_remarks" style="color:red;"></span>
                            </div>
              </div>

<!-------------Status Remarks End------------------------------------->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Date of Joining
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="popupDatepickerr8" name="doj" class="form-control parsley-validated" placeholder="Date Of Joinig" value="<?php if ($id) {
                                                                                                                                                  echo date('d-m-Y', strtotime($res_emp->doj));
                                                                                                                                                } else {
                                                                                                                                                  echo date('d-m-Y');
                                                                                                                                                } ?>" tabindex="7" <?php if (!in_array($HrmsJoinDatePermission, $people_join_date_permission)) {
                                                                                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                                                                                              } ?>>
              </div>
              <div class="form-group col-sm-1"></div>

              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Division :
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select id="division" name="division" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
                  <option value="">Select Type</option>
                  <option value="RCMS" <?php if ($res_emp->division == 'RCMS') {
                                          echo 'selected="selected"';
                                        }  ?>>RCMS</option>
                  <option value="RVL" <?php if ($res_emp->division == 'RVL') {
                                        echo 'selected="selected"';
                                      }  ?>>RVL</option>
                </select>
              </div>

              <!-- clear -->
              <div class="clear"></div>

              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Agreement Date
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="popupDatepicker4" name="agg_date" class="form-control parsley-validated" placeholder="Agreement Date" value="<?php if ($id) echo date('d-m-Y', strtotime($res_emp->aggr_date)); ?>" tabindex="8" <?php //if(!in_array($user_name, $visib) && $id!='') { 
                                                                                                                                                                                                                                      ?><?php //} 
                                                                                                                                                                                                                                                                                                ?>>
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Father/Spouse Name
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="father_name" name="father_name" class="form-control parsley-validated" placeholder="Father/Spouse Name" value="<?php echo $res_emp->father_name; ?>" tabindex="9">

              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Residential Pin Code
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="pin" name="pin" class="form-control parsley-validated" placeholder="Current Residential Pin Code" value="<?php echo $res_emp->pin; ?>" tabindex="10" maxlength="6" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;">
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Pan Card Number
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="pan_card_no" name="pan_card_no" class="form-control parsley-validated" placeholder="Pan Card Number" value="<?php echo $res_emp->pan_card_no; ?>" <?php if ($id == '') { ?> onblur="dublicate_pan()" <?php } ?>tabindex="11" maxlength="10">

              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Aadhar Card Number
                </label>
              </div>
              <div class="form-group col-sm-3">
                <input type="text" id="aadhar_card_no" name="aadhar_card_no" class="form-control parsley-validated" placeholder="Aadhar Card Number" value="<?php echo $res_emp->aadhar_card_no; ?>" tabindex="12" maxlength="12" onkeypress="return IsAlphaNumeric(event);" <?php if ($id == '') { ?> onblur="dublicate_number()" <?php } ?> onpaste="return false;">
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <!--			  <div class="form-group col-sm-3 wrap"> 
                <label for="name">
                <label class="compulsory">*</label>
                Upload Pan Card Scan Copy
                </label>
         <input id="uploaddoc" type="file" name="uploaddoc"   title="<?php echo $res_emp->pan_image_name; ?>"   />
			  </div>
-->
              <!--<div class="form-group col-sm-3" >
                
            
              <?php if ($res_emp->pan_image_name != '') {  ?>
              <label for="name">
                <label class="compulsory">*</label>
                Download Pan Card
                </label>
           
              <a href="<?php if ($res_emp->pan_image_name != "") echo "emp_doc/" . $res_emp->pan_image_name;
                        else echo "#"; ?>" target="_blank"><span class="label label-secondary demo-element">Download</span></a>
              <label class="compulsory"></label>
               <?php echo $res_emp->pan_image_name; ?>
                </label>
              
              <?php } ?>
              
              </div>-->

              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Region
                </label>
              </div>

              <div class="form-group col-sm-3">

                <select id="region1" name="region1" class="form-control parsley-validated chosen-select" tabindex="13" onChange="load_location()">
                  <option value="">Select</option>
                  <?php
                  if ($region != '') {
                    $region1 = "select region_id,region_name from region_master where region_id in (" . $region . ")";
                    $reg = mysql_query($region1);
                    if (mysql_num_rows($reg) > 0) {
                      while ($res_region = mysql_fetch_object($reg)) {
                  ?>
                        <option value="<?php echo $res_region->region_name; ?>" <?php if ($res_region->region_name == $res_emp->region) echo "Selected='Selected'"; ?>><?php echo $res_region->region_name; ?></option>
                  <?php
                      }
                    }
                  }
                  ?>
                </select>
              </div>
              <!-- clear -->
              
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                              <label for="name">
                                <label class="compulsory"></label>
                                Category
                              </label>
                            </div>
                      
              <div class="form-group col-sm-3">
                                <select id="category_type" name="category_type" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
                                  <option value="">Select</option>
                                  <option value="Fresher" <?php if ($res_emp->ctg1 == 'Fresher') {
                                              echo 'selected="selected"';
                                            }?>>Fresher</option>
                                  <option value="Experience" <?php if ($res_emp->ctg1 == 'Experience') {
                                              echo 'selected="selected"';
                                            }?>>Experience</option>
                                </select>
                              </div>




              <div class="form-group col-sm-1"></div>

              <div class="form-group col-sm-2">
                              <label for="name">
                                <label class="compulsory"></label>
                                Education qualification
                              </label>
                            </div>
              <div class="form-group col-sm-3">
                              <input type="text" id="edu_qual" name="edu_qual" class="form-control parsley-validated" placeholder="Education Qualification"  value="<?php echo $res_emp->education_qualif; ?>" tabindex="4">
                              <span id="edu_qual" style="color:red;"></span>
                            </div>
              <div class="clear"></div>



              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Branch
                </label>
              </div>

              <div class="form-group col-sm-3">
                <select id="pbranch" name="pbranch" class="form-control parsley-validated chosen-select" tabindex="14">
                  <option value="">Select</option>
                  <?php
                  //$sql_region = mysql_query("SELECT region_name FROM region_master WHERE status='Y'");
                  $sql_region = mysql_query("select branch_name from hrms_branch where status='Y'");
                  while ($res_region = mysql_fetch_assoc($sql_region)) {
                  ?>
                    <option value="<?php echo $res_region['branch_name']; ?>" <?php if ($res_region['branch_name'] == $res_emp->pbranch) echo "Selected='Selected'"; ?>><?php echo $res_region['branch_name']; ?></option>
                  <?php
                  }

                  ?>
                </select>
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">

                  <label class="compulsory">*</label>State
                </label>
              </div>
              <div class="form-group col-sm-3">
                <select id="cstate" name="cstate" class="form-control parsley-validated chosen-select" tabindex="15">
                  <option value="">Select</option>
                  <?php
                  $sql3 = "select state_name from state_code where status='Y'";
                  $qu3 = mysql_query($sql3);
                  while ($r3 = mysql_fetch_array($qu3)) {
                  ?>
                    <option value="<?php echo $r3['state_name']; ?>" <?php if ($res_emp->state == $r3['state_name']) echo "Selected='Selected'"; ?>><?php echo $r3['state_name']; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Serving Location
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select type="text" id="plocation" name="plocation" class="form-control parsley-validated chosen-select" placeholder="Location" tabindex="16">
                  <option value="">Select Location</option>
                </select>
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Address
                </label>
              </div>
              <div class="form-group col-sm-3">

                <textarea class="form-control parsley-validated" rows="1" cols="1" placeholder="Address" name="address" id="address" tabindex="17" style="width: 266px; height: 35px;"><?php echo $res_emp->address; ?></textarea>

                <!--      <textarea class="form-control parsley-validated" rows="2" cols="4" id="address" name="address" tabindex="12" placeholder="Point Address" value="<?php echo $res_emp->address; ?>"></textarea>-->
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  City
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="city" name="city" class="form-control parsley-validated" placeholder="City" value="<?php echo $res_emp->city; ?>" tabindex="18">
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <font color="red">* </font>Mobile no1
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="mobile1" name="mobile1" class="form-control parsley-validated" placeholder="Mobile no1" value="<?php echo $res_emp->mobile1; ?>" tabindex="19" onblur="load_alert();" maxlength="10" onkeypress="return IsAlphaNumeric(event);" <?php if (!in_array($per, $visib) && $id != '') { ?>readonly <?php } ?>>
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Mobile no2
                </label>
              </div>

              <div class="form-group col-sm-3">

                <input type="text" id="mobile2" name="mobile2" class="form-control parsley-validated" placeholder="Mobile no2" value="<?php echo $res_emp->mobile2; ?>" tabindex="20" maxlength="10" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;">
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  E-mail Id
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="email" name="email" class="form-control parsley-validated" placeholder="E-mail Id" value="<?php echo $res_emp->email; ?>" tabindex="21">
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Report To
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select name="report_to" class="form-control parsley-validated chosen-select" id="report_to" tabindex="22">
                  <option value="Select" selected="selected">Select</option>

                  <option value="Manager" <?php if ($res_emp->report_to == 'Manager')  echo 'selected="selected"';  ?>>Manager</option>
                  <option value="Sr.Manager" <?php if ($res_emp->report_to == 'Sr.Manager')  echo 'selected="selected"';  ?>>Sr.Manager</option>
                  <option value="Risk Manager" <?php if ($res_emp->report_to == 'Risk Manager')  echo 'selected="selected"';  ?>>Risk Manager</option>
                  <option value="General Manager" <?php if ($res_emp->report_to == 'General Manager')  echo 'selected="selected"';  ?>>General Manager</option>

                  <option value="AGM" <?php if ($res_emp->report_to == 'AGM')  echo 'selected="selected"';  ?>>AGM</option>

                </select>
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Notice Period
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select name="notice_period" class="form-control parsley-validated chosen-select" id="notice_period" tabindex="23" disabled="disabled">
                  <option value="Select" selected="selected">Select</option>
                  <option value="Nil" <?php if ($res_emp->exp_sal == 'Nil')  echo 'selected="selected"';  ?>>Nil</option>
                  <option value="30 Days" <?php if ($res_emp->exp_sal == '30 Days')  echo 'selected="selected"';  ?>>30 Days</option>
                  <option value="45 Days" <?php if ($res_emp->exp_sal == '45 Days')  echo 'selected="selected"';  ?>>45 Days</option>
                  <option value="60 Days" <?php if ($res_emp->exp_sal == '60 Days')  echo 'selected="selected"';  ?>>60 Days</option>
                  <option value="90 Days" <?php if ($res_emp->exp_sal == '60 Days')  echo 'selected="selected"';  ?>>90 Days</option>
                </select>
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Marital Status
                </label>
              </div>
              <div class="form-group col-sm-3">
                <select id="mstatus" name="mstatus" class="form-control parsley-validated chosen-select" data-required="true" tabindex="24">
                  <option value="">Select </option>
                  <option value="Married" <?php if ($res_emp->mstatus == 'Married')  echo 'selected="selected"';  ?>>Married</option>
                  <option value="UnMarried" <?php if ($res_emp->mstatus == 'UnMarried')  echo 'selected="selected"';  ?>>UnMarried</option>
                </select>
              </div>
              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Blood Group
                </label>
              </div>
			  
          <div class="form-group col-sm-3">
                  <select id="blood_type" name="blood_type" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
                  <option value="">Select</option>
                    <option value="A+" <?php  if ($res_emp->blood_group == 'A+'){ echo 'selected="selected"'; }  ?>>A+</option>
                    <option value="A-" <?php  if ($res_emp->blood_group == 'A-'){ echo 'selected="selected"'; }  ?>>A-</option>
                    <option value="B+" <?php  if ($res_emp->blood_group == 'B+'){ echo 'selected="selected"'; }  ?> >B+</option>
                    <option value="B-" <?php  if ($res_emp->blood_group == 'B-'){ echo 'selected="selected"'; }  ?>>B-</option>
                    <option value="AB+" <?php  if ($res_emp->blood_group == 'AB+'){ echo 'selected="selected"'; }  ?>>AB+</option>
                    <option value="AB-" <?php  if ($res_emp->blood_group == 'AB-'){ echo 'selected="selected"'; }  ?>>AB-</option>
                    <option value="O+"  <?php  if ($res_emp->blood_group == 'O+'){ echo 'selected="selected"'; }  ?>>O+</option>
                    <option value="O-" <?php  if ($res_emp->blood_group == 'O-'){ echo 'selected="selected"'; }  ?>>O-</option>
                  </select>
                </div>
              <!--<div class="clear"></div>
              <div class="form-group col-sm-3">
                <h3 class="portlet-title"> <u>Personal Details</u> </h3>
              </div>-->

              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Religion
                </label>
              </div>
              <div class="form-group col-sm-3">

                <select id="religion" name="religion" class="form-control parsley-validated chosen-select" tabindex="26">
                  <option value="">Select </option>
                  <option value="Hinduism" <?php if ($res_emp->religion == 'Hinduism')  echo 'selected="selected"';  ?>>Hinduism</option>
                  <option value="Islam" <?php if ($res_emp->religion == 'Islam')  echo 'selected="selected"';  ?>>Islam</option>
                  <option value="Christianity" <?php if ($res_emp->religion == 'Christianity')  echo 'selected="selected"';  ?>>Christianity</option>
                  <option value="Sikhism" <?php if ($res_emp->religion == 'Sikhism')  echo 'selected="selected"';  ?>>Sikhism</option>
                  <option value="Buddhism" <?php if ($res_emp->religion == 'Buddhism')  echo 'selected="selected"';  ?>>Buddhism</option>
                  <option value="Jainism" <?php if ($res_emp->religion == 'Jainism')  echo 'selected="selected"';  ?>>Jainism</option>
                  <option value="Zoroastrianism" <?php if ($res_emp->religion == 'Zoroastrianism')  echo 'selected="selected"';  ?>>Zoroastrianism</option>
                  <option value="Judaism" <?php if ($res_emp->religion == 'Judaism')  echo 'selected="selected"';  ?>>Judaism</option>

                </select>
              </div>

              <!-- col.sm.1 -->

              <div class="form-group col-sm-1"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Bank Name
                </label>
              </div>
              <div class="form-group col-sm-3">
                <select id="bank_name" name="bank_name" class="form-control parsley-validated chosen-select" tabindex="27" <?php if (!in_array($per, $visib) && $id != '') { ?> disabled="disabled" <?php } ?>>
                  <option value="">Select Bank Name</option>
                  <?php
                  $sql_bank = mysql_query("select bank_name from hrms_bank_details where status='Y'");
                  // $qu3=mysql_query($sql_bank);
                  while ($r3_bank = mysql_fetch_array($sql_bank)) {
                  ?>
                    <option value="<?php echo $r3_bank['bank_name']; ?>" <?php if ($res_emp->bank_name == $r3_bank['bank_name']) echo "Selected='Selected'"; ?>><?php echo $r3_bank['bank_name']; ?></option>
                  <?php
                  }
                  ?>

                </select>
              </div>
              <!--Branch Name-->

              <!-- clear -->
              <div class="clear"></div>
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Branch Name
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="branch_name" name="branch_name" class="form-control parsley-validated" data-required="true" placeholder="Enter Branch" tabindex="28" value="<?php echo $res_emp->branch_name; ?>" onkeypress="return IsAlphaNumeric(event);" <?php if (!in_array($per, $visib) && $id != '') { ?> readonly="readonly" <?php } ?>>
              </div>

              <!-- col.sm.1 -->
              <div class="form-group col-sm-1"></div>
              <!--Account No-->
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  Account No
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="account_no" name="account_no" class="form-control parsley-validated" data-required="true" placeholder="Enter Account Number" tabindex="29" value="<?php echo $res_emp->account_no; ?>" <?php if ($id == '') { ?> onblur="dublicate_pan1()" <?php } ?> onkeypress="return IsAlphaNumeric(event);" <?php if (!in_array($per, $visib) && $id != '') { ?> readonly="readonly" <?php } ?>>
              </div>
              <!-- clear -->
              <div class="clear"></div>
              <!--IFSC Code-->
              <div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory"></label>
                  IFSC Code
                </label>
              </div>
              <div class="form-group col-sm-3">

                <input type="text" id="ifsc_code" maxlength="11" onkeyup="checkIfsc();" name="ifsc_code" class="form-control parsley-validated spl" data-required="true" placeholder="Enter IFSC Code" tabindex="30" value="<?php echo $res_emp->ifsc_code; ?>" onkeypress="return IsAlphaNumeric(event);" <?php if (!in_array($per, $visib) && $id != '') { ?> readonly="readonly" <?php } ?>>
                <small style="display: none; color: red; font-weight: bold;" id="alt-ifsc">Ifsc Code must be in 11 digits</small>
              </div>

              <div class="form-group col-sm-1"></div>
              <!---------------- new-->


              <!--epf No-->
              <?php if ($region == '8'  || $per == 'Admin') {
              ?>

                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    Fixed Salary
                  </label>
                </div>
              <?php } else { ?>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    Service Charges
                  </label>
                </div>
              <?php } ?>
              <div class="form-group col-sm-3">

                <input type="text" id="ini_sal" name="ini_sal" class="form-control parsley-validated" data-required="true" <?php if ($region == '8' || $per == 'Admin') { ?>placeholder="Fixed Salary" <?php } else { ?> placeholder="Service Charges" <?php } ?> tabindex="30" value="<?php echo $res_emp->ini_sal; ?>">
              </div>
              <!-- clear -->
              <div class="clear"></div>

              <!--esi Code-->
              <?php if ($region == '8'  || $per == 'Admin') { ?>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    Conveyance
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="text" id="conveyance" name="conveyance" class="form-control parsley-validated" data-required="true" placeholder="Conveyance" tabindex="30" value="<?php echo $res_emp->convey; ?>">
                </div>
              <?php } else { ?>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    Telephone Charges
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="text" id="path" name="path" class="form-control parsley-validated" data-required="true" placeholder="Telephone Charges" tabindex="30" value="<?php echo $res_emp->path; ?>">
                </div>

              <?php }
              if ($region == '8' || $per == 'Admin') { ?>

                <div class="form-group col-sm-1"></div>
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    EPF Number
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="text" id="epf_no" name="epf_no" class="form-control parsley-validated" data-required="true" placeholder="EPF Number" tabindex="30" value="<?php echo $res_emp->epf_no; ?>">
                </div>
                <!-- clear -->
                <div class="clear"></div>
                <!--esi Code-->
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    ESI Number
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="text" id="esi_no" name="esi_no" class="form-control parsley-validated" data-required="true" placeholder="ESI Number" tabindex="30" value="<?php echo $res_emp->esi_no; ?>">
                </div>


                <div class="form-group col-sm-1"></div>
                <!--UAN No-->
                <div class="form-group col-sm-2">
                  <label for="name">
                    <label class="compulsory"></label>
                    UAN Number
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="text" id="uan_no" name="uan_no" class="form-control parsley-validated" data-required="true" placeholder="UAN Number" tabindex="30" value="<?php echo $res_emp->uan_no; ?>">
                </div>
                <!-- clear -->
                <div class="clear"></div>


              <?php } ?>
 <div class="form-group col-sm-2">
                          <label for="name">
                            <label class="compulsory">*</label>
                            Employment Type
                          </label>
                        </div>
                  
          <div class="form-group col-sm-3">
                            <select id="employment_types" name="employment_types" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2">
                              <option value="">Select</option>
                              <option value="Radiant" <?php if ($res_emp->employment_type == 'Radiant')  echo 'selected="selected"';  ?>>Radiant</option>
                              <option value="Out sourced" <?php if ($res_emp->employment_type == 'Out sourced')  echo 'selected="selected"';  ?>>Out sourced</option>
                            </select>
                          </div>

 <div id="to_append_agency" class="to_append_agency_name" style="display:none">
<div class="form-group col-sm-1"></div>
<div class="form-group col-sm-2">
                <label for="name">
                  <label class="compulsory">*</label>
                  Agency  Name
                </label>
              </div>
<div class="form-group col-sm-3">
                <input type="text" id="vend_apnd" name="vend_apnd" class="form-control parsley-validated" placeholder="Agency  Name" value="<?php echo $res_emp->vendor_name; ?>"  tabindex="4">
                <span id="vend_apnd" style="color:red;"></span>
              </div>
</div>
              <div class="clear"> </div>
              <?php
              if ($_GET["id"] == "") {
              ?>
                <div class="form-group col-sm-7">
                  <h3 class="portlet-title"> <u>KYC Document Uploads <span class="text-danger">(File Size must be lesser than 3MB)</span></u> </h3>
                </div>
                <div class="clear"></div>
                <!--PAN CARD-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Pan Card
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="pancard" name="pancard" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->

                <!--AADHAAR CARD-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Aadhaar Card
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="aadharcard" name="aadharcard" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->
                <div class="clear"> </div>
                <!--Employee Application form-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Employee Application Form
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="empappfrm" name="empappfrm" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->

                <!--Background verification-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Background Verification Form
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="backfrm" name="backfrm" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->
                <div class="clear"> </div>
                <!--Pic documents-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Employee Photo
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="emppic" name="emppic" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->
                <!--Signature documents-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Employee Signature
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="empsign" name="empsign" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->
                <!--Driving licence-->
                <div class="form-group col-sm-3">
                  <label for="name">
                    <label class="compulsory"></label>
                    <font color="red">* </font> Driving licence
                  </label>
                </div>
                <div class="form-group col-sm-3">

                  <input type="file" id="driving" name="driving" class="form-control parsley-validated" data-required="true" tabindex="30" required>
                </div>
                <!-- <div class="form-group col-sm-1"></div> -->
              <?php
              }
              ?>





              <!--<div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory">&nbsp;</label>
                Father's / Spouse's Occupation
                </label>
                <input type="text" id="father_occu" name="father_occu" class="form-control parsley-validated"  placeholder="Father's / Spouse's Occupation" value="<?php echo $res_emp->father_occu; ?>" tabindex="25">
              </div>
              <div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory">&nbsp;</label>
                Blood Group
                </label>
                <input type="text" id="blood_group" name="blood_group" class="form-control parsley-validated"  placeholder="Blood Group" value="<?php echo $res_emp->blood_group; ?>" tabindex="26">
              </div>-->


              <!--<div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory">&nbsp;</label>
                Date Of Marriage
                </label>
                <input type="text" id="popupDatepicker2" name="doa" class="form-control parsley-validated"  placeholder="Date Of Marriage" value="<?php echo $res_emp->doa; ?>" tabindex="28">
              </div>-->
              <div class="clear"> </div>

              <!--<div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory">&nbsp;</label>
                Place / State of Domocile
                </label>
                <input type="text" id="place" name="place" class="form-control parsley-validated"  placeholder="Place / State of Domocile" value="<?php echo $res_emp->place; ?>" tabindex="30">
              </div>-->



              <!--<div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory"></label>
                Natoinality
                </label>
                <select id="nation" name="nation" class="form-control parsley-validated chosen-select"  tabindex="32">
                  <option value="Select" selected="selected">Select </option>
                  <option  value="Indian"<?php if ($res_emp->nation == 'Indian')  echo 'selected="selected"';  ?>>Indian</option>
                  <option  value="Others"<?php if ($res_emp->nation == 'Others(Please Specify)')  echo 'selected="selected"';  ?>>Others (Please Specify)</option>
                </select>
              </div>
            <div id="Others" class='colors1' style="display:none">
			<div class="clear"> </div>
			<div class="form-group col-sm-3">
                <label for="name">
                <label class="compulsory">&nbsp;</label>
                Other Nation
                </label>
                <input type="text" id="Others" name="Others" class="form-control parsley-validated"  placeholder="Other Nation" value="<?php echo $res_emp->Others; ?>" tabindex="33">
              </div>
			  </div>-->
              </table>
              <div class="clear"> </div>
              <div class="form-group col-sm-7" style="display:none">
                <h3 class="portlet-title"> <u>EDUCATION DETAILS (List most recent first)</u> </h3>
              </div>
              <div class="clear"></div>
              <div>
                <div class="caption" style="display:none">
                  <p> <a href="javascript:;" class="btn btn-success btn-sm btn-sm" id="emp_edu" onclick="AddRow(this);">Add Row</a>&nbsp; <a href="javascript:;" class="btn btn-warning btn-sm btn-sm" id="emp_edus" onclick="DeleteRow(this);">Delete Row</a> </p>
                </div>
                <div class="table-responsive" style="display:none">
                  <table class="table table-striped table-bordered thumbnail-table" width="100%" id="emp_edu1">
                    <thead>
                      <tr>
                        <th></th>
                        <th>SNo</th>
                        <th>Course</th>
                        <th>Specialisation</th>
                        <th>Period From</th>
                        <th>Period To</th>
                        <th>Institution</th>
                        <th>Percentage</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($id != '') {
                        $edu = mysql_query("select * from hrms_edu_details where r_id ='" . $id . "' and status='Y' limit 0,1 ");
                        if (mysql_num_rows($edu) > 0) {
                          while ($res_edu = mysql_fetch_object($edu)) {
                            $edu_id = $res_edu->edu_id;
                            $course = $res_edu->course;
                            $spec = $res_edu->spec;
                            $frm_pre = $res_edu->from_period;
                            $to = $res_edu->to_period;
                            $inst = $res_edu->univ;
                            $pree = $res_edu->per;
                      ?>
                            <tr>
                              <td align="center"><input type="checkbox" class="chkbox" /></td>
                              <td align="center">1
                                <input type="hidden" value="<?php echo $edu_id; ?>" name="emp_edu_id[]" />
                              </td>
                              <input type="hidden" value="<?php echo $id; ?>" name="r_id[]" /></td>
                              <td><input type="text" id="course" name="course[]" class="form-control parsley-validated" data-required="true" value="<?php echo $course; ?>" placeholder="course" tabindex="34">
                                <!--<input type="hidden" name="r_id" id="r_id" value="<?php echo $id; ?>">-->
                              </td>
                              <td><input type="text" id="special" name="special[]" class="form-control parsley-validated" data-required="true" value="<?php echo $spec ?>" placeholder="Specialisation" tabindex="35"></td>
                              <td><input type="text" id="from_period" name="from_period[]" class="form-control parsley-validated" data-required="true" value="<?php echo $frm_pre; ?>" placeholder="Period From" tabindex="36"></td>
                              <td><input type="text" id="to_period" name="to_period[]" class="form-control parsley-validated" data-required="true" value="<?php echo $to; ?>" placeholder="Period To" tabindex="37"></td>
                              <td><input type="text" id="institution" name="institution[]" class="form-control parsley-validated" data-required="true" value="<?php echo $inst; ?>" placeholder="Institution" tabindex="38"></td>
                              <td align="center"><input id="percentage" type="text" name="percentage[]" class="form-control parsley-validated" value="<?php echo $pree; ?>" placeholder="Percentage" tabindex="39" /></td>
                              <td class="text-center"><a href="#" class="disableClick" rel="0%hrm_info%0" onclick="delete_data_row(this);"><i class="fa fa-trash" title="Remove"></i></a></td>
                            </tr>
                        <?php
                          }
                        }
                      } else {
                        ?>

                        <tr>
                          <td align="center"><input type="checkbox" class="chkbox" /></td>
                          <td align="center">1
                            <input type="hidden" value="<?php echo $res_exp -> exp_id; ?>" name="emp_exp_id[]" />
                          </td>
                          <td><input type="text" id="course" name="course[]" class="form-control parsley-validated" data-required="true" value="<?php echo $course; ?>" placeholder="course" tabindex="34">
                            <!--<input type="hidden" name="r_id" id="r_id" value="<?php echo $id; ?>">-->
                          </td>
                          <td><input type="text" id="special" name="special[]" class="form-control parsley-validated" data-required="true" value="<?php echo $spec ?>" placeholder="Specialisation" tabindex="35"></td>
                          <td><input type="text" id="from_period" name="from_period[]" class="form-control parsley-validated" data-required="true" value="<?php echo $frm_pre; ?>" placeholder="Period From" tabindex="36"></td>
                          <td><input type="text" id="to_period" name="to_period[]" class="form-control parsley-validated" data-required="true" value="<?php echo $to; ?>" placeholder="Period To" tabindex="37"></td>
                          <td><input type="text" id="institution" name="institution[]" class="form-control parsley-validated" data-required="true" value="<?php echo $inst; ?>" placeholder="Institution" tabindex="38"></td>
                          <td align="center"><input id="percentage" type="text" name="percentage[]" class="form-control parsley-validated" value="<?php echo $pree; ?>" placeholder="Percentage" tabindex="39" /></td>
                          <td class="text-center"><a href="#" class="disableClick" rel="0%hrm_info%0" onclick="delete_data_row(this);"><i class="fa fa-trash" title="Remove"></i></a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
                <div class="clear"> </div>

                <!--Special Achievements-->
                <div class="form-group col-sm-4" style="display:none">
                  <label for="name">
                    <label class="compulsory">&nbsp;</label>
                    Special achievements in academics or co-curricular activities , if any
                  </label>
                  <textarea class="form-control parsley-validated" rows="2" cols="10" id="emp_spl_achieve" name="emp_spl_achieve" data-minlength="5" data-required="true" tabindex="40"></textarea>
                </div>
                <!--<div class="form-group col-sm-1"> </div>-->
                <!--Special Kills-->
                <div class="form-group col-sm-4" style="display:none">
                  <label for="name">
                    <label class="compulsory">&nbsp;</label>
                    Special skills/(Computer, Area of Expertise etc) obtained by training/hands-on- experience
                  </label>
                  <textarea class="form-control parsley-validated" rows="2" cols="10" id="emp_spl_kills" name="emp_spl_kills" data-minlength="5" data-required="true" tabindex="41"></textarea>
                </div>
                <div class="form-group col-sm-3" style="display:none">
                  <label for="name">
                    <label class="compulsory"></label>
                    Category
                  </label>
                  <select id="ctg1" name="ctg1" class="form-control parsley-validated chosen-select" tabindex="43">
                    <option value="">Select </option>
                    <option value="Fresher" <?php if ($res_emp->ctg1 == 'Fresher')  echo 'selected="selected"';  ?>>Fresher</option>
                    <option value="Experience" <?php if ($res_emp->ctg1 == 'Experience')  echo 'selected="selected"';  ?>>Experience</option>
                  </select>
                </div>
                <!--<div class="form-group col-sm-3">
                  <label for="name">
                  <label class="compulsory"></label>
                  Mother Tongue
                  </label>
                  <input type="text" id="mtongue" name="mtongue" class="form-control parsley-validated"  placeholder="Mother Tongue" value="<?php echo $res_emp->mtongue; ?>" tabindex="42">
                </div>-->

                <div class="clear"></div>
                <div id="Experience" class="colors" style="display:none">
                  <div class="form-group col-sm-7">
                    <h3 class="portlet-title"> <u>PREVIOUS EXPERIENCE (List most recent first)</u> </h3>
                  </div>
                  <div class="clear"></div>
                  <div>
                    <div class="caption">
                      <p> <a href="javascript:;" class="btn btn-success btn-sm btn-sm" id="emp_exp" onclick="AddRow1(this);">Add Row</a>&nbsp; <a href="javascript:;" class="btn btn-warning btn-sm btn-sm" id="emp_exps" onclick="DeleteRow1(this);">Delete Row</a> </p>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered thumbnail-table" width="100%" id="emp_exp1">
                        <thead>
                          <tr>
                            <th></th>
                            <th>SNo</th>
                            <th>Year(s) (Month/Year to Month/Year)</th>
                            <th>Name & Organisation</th>
                            <th>Position</th>
                            <th>Reporting To</th>
                            <th>Responsibility</th>
                            <th>Gross Salary Per Month</th>
                            <th>Reason for Leaving</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php if ($id != '') {
                            $edu1 = mysql_query("select * from hrms_exp_details where r_id ='" . $id . "' and status='Y' limit 0,1 ");
                            while ($res_exp = mysql_fetch_object($edu1)) {
                          ?>
                              <tr>
                                <td align="center"><input type="checkbox" class="chkbox" /></td>
                                <td align="center">
                                  <input type="hidden" value="<?php echo $res_exp->edu_id; ?>" name="emp_exp_id[]" />
                                </td>
                                <td><input type="text" id="years" name="years[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->eyear; ?>" placeholder="Year(s)" tabindex="44"></td>
                                <td><input type="text" id="name_address" name="name_address[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->org; ?>" placeholder="Name &amp; Address" tabindex="45"></td>
                                <td><input type="text" id="positon" name="positon[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->position; ?>" placeholder="Position" tabindex="46"></td>
                                <td><input type="text" id="reporting_to" name="reporting_to[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->rep; ?>" placeholder="Reporting To" tabindex="47"></td>
                                <td><input type="text" id="responsible" name="responsible[]" class="form-control parsley-validated" data-required="true" value="" placeholder="Responsible" tabindex="48"></td>
                                <td align="center"><input id="gross_sal" type="text" name="gross_sal[]" class="form-control parsley-validated" placeholder="Gross Salary" tabindex="49" value="<?php echo $res_exp->sal; ?>" /></td>
                                <td align="center"><input id="reason_for_leaving" type="text" name="reason_for_leaving[]" class="form-control parsley-validated" placeholder="Reason for Leaving" tabindex="50" value="<?php echo $res_exp->reason; ?>" /></td>
                                <td class="text-center"><a href="#" class="disableClick" rel="0%hrm_info%0" onclick="delete_data_row1(this);"><i class="fa fa-trash" title="Remove" tabindex="51"></i></a></td>
                              </tr>
                            <?php }
                          } else { ?>

                            <tr>
                              <td align="center"><input type="checkbox" class="chkbox" /></td>
                              <td align="center">
                                <input type="hidden" value="<?php echo $res_exp->edu_id; ?>" name="emp_exp_id[]" />
                              </td>
                              <td><input type="text" id="years" name="years[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->eyear; ?>" placeholder="Year(s)" tabindex="44"></td>
                              <td><input type="text" id="name_address" name="name_address[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->org; ?>" placeholder="Name &amp; Address" tabindex="45"></td>
                              <td><input type="text" id="positon" name="positon[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->position; ?>" placeholder="Position" tabindex="46"></td>
                              <td><input type="text" id="reporting_to" name="reporting_to[]" class="form-control parsley-validated" data-required="true" value="<?php echo $res_exp->rep; ?>" placeholder="Reporting To" tabindex="47"></td>
                              <td><input type="text" id="responsible" name="responsible[]" class="form-control parsley-validated" data-required="true" value="" placeholder="Responsible" tabindex="48"></td>
                              <td align="center"><input id="gross_sal" type="text" name="gross_sal[]" class="form-control parsley-validated" placeholder="Gross Salary" tabindex="49" value="<?php echo $res_exp->sal; ?>" /></td>
                              <td align="center"><input id="reason_for_leaving" type="text" name="reason_for_leaving[]" class="form-control parsley-validated" placeholder="Reason for Leaving" tabindex="50" value="<?php echo $res_exp->reason; ?>" /></td>
                              <td class="text-center"><a href="#" class="disableClick" rel="0%hrm_info%0" onclick="delete_data_row1(this);"><i class="fa fa-trash" title="Remove" tabindex="51"></i></a></td>
                            </tr>
                          <?php } ?>

                        </tbody>
                      </table>
                    </div>
                  </div>

                </div>
                <!--bank details-->


                <!--<div class="clear"></div>
              	<span class="hide_sal">
                 <h3 class="portlet-title"> <u>Salary Details </u> </h3>
                                        <div class="form-group col-sm-2">
                                          <label for="name">Basic Pay<?php if ($region == 4) { ?>(BP)+DA <?php } ?>
 </label>
                                          <input name="basic_pay" type="text" class="form-control pay gud_pay" id="basic_pay" size="10" value="<?php echo $res_emp->basic_pay ?>"  <?php if ($region != 12 and $region != 16 and $region != 4) { ?> readonly="readonly"  <?php } ?> onkeyup="gud_pay()"/>
                                        </div>
                                        <div class="form-group col-sm-2">
                                          <label for="name">HRA
 </label>
                                          <input name="hra" type="text" class="form-control pay gud_pay" id="hra" size="10" value="<?php echo $res_emp->hra ?>"  <?php if ($region != 16 and $region != 4) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?>  onkeyup="gud_pay()"/>
                                        </div>
                                        <div class="form-group col-sm-2">
                                          <label for="name">Conveyance 
 </label>
                                          <input name="conveyance" type="text" class="form-control pay gud_pay" id="conveyance" size="10" value="<?php echo $res_emp->convey ?>" <?php if ($region != 16 and $region != 4) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?> onkeyup="gud_pay()" />
                                        </div>
                                        <?php if ($region != 16 and $region != 4) { ?>
                                        <div class="form-group col-sm-2">
                                          <label for="name">Medical
</label>
                                          <input name="medical" type="text" class="form-control pay gud_pay" id="medical" size="10" value="<?php echo $sal->medical ?>" <?php if ($region != 16 and $region != 4) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?> onkeyup="gud_pay()"/>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group col-sm-2">
                                          <label for="name">Bonus
 </label>
                                          <input name="bonus" type="text" class="form-control pay gud_pay" id="bonus" size="10" value="<?php echo $res_emp->bonus ?>" <?php if ($region != 16) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?> onkeyup="gud_pay()"/>
                                        </div>
                                        <div class="form-group col-sm-2">
                                          <label for="name"><?php if ($region != 4) { ?> Other Allowance <?php } else { ?>Communication Allw<?php } ?>
 </label>
                                          <input name="oth_all" type="text" class="form-control pay gud_pay" id="oth_all" size="10" value="<?php echo $res_emp->other_allc ?>" <?php if ($region != 16 and $region != 4) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?> onkeyup="gud_pay()" />
                                        </div>
                                       <!-- <div class="form-group col-sm-2">
                                          <label for="name">5 (s) </label>
                                          <input name="amt_5" type="text" class="form-control" id="amt_5" size="10" value="<?php echo $row1->amt_5; ?>" onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" />
                                        </div>
                                        <div class="form-group col-sm-2">
                                          <label for="name">Coin (s) </label>
                                          <input name="coins" type="text" class="form-control" id="coins" size="10" value="<?php echo $row1->coins; ?>"  onkeydown="cal_deno()" onchange="cal_deno()" onkeyup="cal_deno()" onkeypress="cal_deno()" />
                                        </div>
                                        <<div class="form-group col-sm-2">
                                          <label for="name">Fixed Salary </label>
                                          <input name="gross_sal1" type="text" class="form-control" id="gross_sal1" size="10" value="<?php echo $res_emp->gross_sal ?>"  <?php if ($region != 16) { ?> onkeyup="cal_deno()" <?php }
                                                                                                                                                                                                                        if ($region == 16 || $region == 4) { ?> style="background-color : #FBD5EC;" readonly="readonly" <?php } ?> />
                                        </div>
                                        </span>
          -->
                <div class="clear"></div>
                <div class="form-group col-sm-3">

                  <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn" style="margin-top: 25px;" tabindex="56"><?php if ($id == '') { ?> Save Details <?php } else { ?>Update Details</button><?php if ($per == 'Admin') {
                                                                                                                                                                                                                        $sql = mysql_query("select  * from hrms_empdet hr join radiant_ce rc on rc.ce_id=hr.emp_id where hr.r_id='" . $_GET['id'] . "'");
                                                                                                                                                                                                                        $n = mysql_num_rows($sql);
                                                                                                                                                                                                                        if ($n > 0) {
                                                                                                                                                                                                                      ?>
                      <button type="button" name="submit1" id="submit1" class="btn btn-danger search_btn" style="margin-top: 25px;" tabindex="56" disabled>Merge</button>
                    <?php
                                                                                                                                                                                                                        } else { ?>
                      <button type="button" name="submit1" id="submit1" class="btn btn-danger search_btn" style="margin-top: 25px;" tabindex="56">Merge</button>
                      <?php
                                                                                                                                                                                                                        }

                      ?><?php } ?>
                    <?php  } ?>
                    <br>
                    <?php if ($per == 'Admin') {
                      echo $res_emp->updated_by . '-' . $res_emp->updated_date;
                    }
                    ?>
                </div>
              </div>
            </form>
          </div>

          <div class="clear"></div>
          <!----  ////////////////////////// entry statement//////////////////////////////////--->
          <form>
          </form>
        </div>
      </div>
      </form>
      <div class="clear"></div>
      <div class="portlet">
        <h3 class="portlet-title"> <u>Customize Search</u> </h3>
        <form id="demo-validation" action="" data-validate="parsley" class="form parsley-form">
          <div class="form-group col-sm-3">
            <label for="name">Search Criteria </label>
            <select id="search" name="search" class="form-control parsley-validated chosen-select searchCriteria" data-required="true" tabindex="57">
              <option value="">Select</option>
              <option value="all">All</option>
              <option value="emp_id">Employee Id</option>
              <option value="emp_name">Employee Name</option>
              <option value="pdesig1">Designation</option>
              <option value="design">Department</option>
            </select>
            <span class="selectboxErr" style="color:red;display:none"> * Select any criteria </span>
          </div>
          <div class="form-group col-sm-3">
            <label for="name">
              <label class="compulsory"></label>
              Branch
            </label>
            <select id="region" name="region" class="form-control parsley-validated chosen-select searchRegion" tabindex="58">
              <option value="">Select</option>
              <?php
              if ($region != '') {
                $sql_reg = "select region_id,region_name from region_master where region_id in (" . $region . ")";
                $reg_sql = mysql_query($sql_reg);
                if (mysql_num_rows($reg_sql) > 0) {
                  while ($log_region = mysql_fetch_object($reg_sql)) {
              ?>
                    <option value="<?php echo $log_region->region_name; ?>" <?php if ($log_region->region_name == $res_emp->region_name) echo "Selected='Selected'"; ?>><?php echo $log_region->region_name; ?></option>
              <?php
                  }
                }
              }

              ?>
            </select>
            <span class="selectregErr" style="color:red;display:none"> * Select region </span>
          </div>
          <div class="form-group col-sm-3">
            <label for="name">Enter Keyword</label>
            <input type="text" id="keyword" name="keyword" class="form-control parsley-validated " data-required="true" placeholder="Enter Keyword" tabindex="59">
            <span class="keywordErr" style="color:red;display:none"> * Enter keyword </span>
          </div>
          <div class="form-group  col-sm-3">
            <button type="button" class="btn btn-danger search_btn" id="search_criteria" style="margin-top: 23px;" onclick="search_key('1', '0')" tabindex="60">Search</button>
          </div>
        </form>
        <div class="clear"></div>
        <!--<div id="view_statuss"></div>--><br />

        <div class="clear"></div>
        <?php //include("search_field.php"); ?>
        <div id="view_details_indu"></div>
      </div>
    </div>
    <!-- /.portlet -->

  </div>
  <!-- /.col -->

</div>
<!-- /.row -->

</div>
<!-- /.container -->


<style type="text/css">
  #branch_name-error,
  #state-error,
  #location-error,
  #designation-error,
  #gender-error,
  #popupDatepicker-error,
  #popupDatepicker1_modified-error {
    left: 10px;
    top: 77px;
  }

  #popupDatepicker1_modified:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
      
    }
</style>
<script type="text/javascript">
  $(document).ready(function() {

    $.validator.setDefaults({
      ignore: ":hidden:not(select)"
    });
    $.validator.addMethod("phoneUS", function(mobile1, mobile2, element) {
      mobile1 = mobile1.replace(/\s+/g, "");
      return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
    }, "Enter valid phone number."); //cash,cheque,normal
    $("#demo-validation").validate({
      rules: {
        emp_id: {
          required: true
        },
		app_type: {
          required: true
        },
		gender: {
          required: true
        },
        replace_id: {
          required: true
        },
		city: {
          required: true
        },
		agg_date: {
          required: true
        },
		
        cname: {
          required: true,
          number: false
        },
        region1: {
          required: true
        },
        pbranch: {
          required: true
        },

        cstate: {
          required: true
        },
        plocation: {
          required: true
        },
        pdesig: {
          required: true
        },
        pdesig1: {
          required: true
        },
        address: {
          required: true
        },
        mobile1: {
          required: true,
          number: true,
          minlength: 10
        },
        pan_card_no: {
          required: true
          /*minlength:10*/
        },
        aadhar_card_no: {
          required: true,
          number: true,
          minlength: 12
        },
       ifsc_code: {
          required: function(element) {
                return $("#ifsc_code").val().trim() !== ''; // "required" if not empty
            },
           minlength: 11 
        },
        dob: {
          required: true
        },
        doj: {
          required: true
        },
        father_name: {
          required: true,
          number: false
        },
        gender: {
          required: true
        },
        phone: {
          number: true
        },
        pin: {
          required: true,
          number: true
        },
        phone11: {
          required: true
        },
        mobile2: {
          number: true,
          minlength: 10
        },
        email: {
          email: true
        },
        division: {
          required: true
        },
		employment_types:{
          required: true
        }
		
        /*ce_status:{
          required:true   
        }*/
      },
      messages: {
        emp_id: {
          required: 'Enter Employee ID.'
        },
		app_type: {
          required: 'Select The Apointment Type.'
        },
		gender: {
          required: 'Select The Gender.'
        },
		agg_date: {
          required: 'Select Agreement Date.'
        },
        replace_id: {
          required: 'Replacement Emp Id.'
        },
		city: {
          required: 'Select The City'
        },
        cname: {
          required: 'Enter The Employee Name.',
          number: 'Enter valid Employee Name.'
        },
        region1: {
          required: 'Select The Region.'
        },
        pbranch: {
          required: 'Select The Branch.'
        },
        cstate: {
          required: 'Select State.'
        },
        plocation: {
          required: 'Select The Location'
        },
        pdesig: {
          required: 'Select Dep.'
        },
        pdesig1: {
          required: 'Select Designation '
        },
        address: {
          required: 'Enter Address'
        },
        aadhar_card_no: {
          required: 'Enter Aadhar No.',
          number: 'Enter Valid Aadhar No'
        },
        mobile1: {
          required: 'Enter Mobile No.',
          number: 'Enter Valid Mobile No'
        },
        pan_card_no: {
          required: 'Enter PanCard No.'
        },
       ifsc_code: {
          //required: 'Enter valid IFSC Code'
          minlength: "IFSC Code must be in 11 digits."
        }, 
        dob: {
          required: 'Select Date.'
        },
        doj: {
          required: 'Select Date.'
        },
        father_name: {
          required: 'Enter Father Name.',
          number: 'Enter valid Employee Name.'
        },
        gender: {
          required: 'Select Gender.'
        },
        phone: {
          number: 'Enter Valid Mobile No'
        },
        pin: {
          required: 'Enter Pin Code .',
          number: 'Enter Numeric Only'
        },
        email: {
          email: 'Enter Vaild Email.'
        },
        types: {
          required: 'Select Type.'
        },
        mobile2: {
          required: 'Enter Mobile No.',
          number: 'Enter Valid Mobile No'
        },
        division: {
          required: 'Select Division'
        },
		 vend_apnd: {
                        required: "Agency Name is required"
                    },
        employment_types:{

          required: "Enter employment type"
        },
        inactv_remarks:{
          required: 'Enter Remarks'
        },
        ce_status:{
          required: 'Select Status'
        }
        /*aadhar_card_no:{
          required:'Enter Aadhar No.' ,
            number:'Enter Valid Aadhar No'
        },*/

      }
    });



    flatpickr("#popupDatepicker1_modified", {
    //enableTime: true,
    dateFormat: "d-m-Y",
    
  });

    $('.status_ld').hide();
    $("a[name=addRow]").click(function() {
      // Code between here will only run when the a link is clicked and has a name of addRow
      $("table#table1 tr:last").after('<tr><td><img class="delete" alt="delete" src="@Url.Content("~/content/delete_icon.png")" /></td></tr>');
      return false;
    });
    //load_reporto()
    load_status()
    load_location()
    //	cal_deno()
    $(".chosen-select").chosen({
      no_results_text: 'Oops, nothing found!'
    }, {
      disable_search_threshold: 10
    });
    <?php if ($url == '') { ?>
      setTimeout(function() {
        $('.message_cu').fadeOut('fast');
      }, 3000);
    <?php } ?>

    function getAge(birth) {
      var today = new Date();
      var curr_date = today.getDate();
      var curr_month = today.getMonth() + 1;
      var curr_year = today.getFullYear();

      var pieces = birth.split('-');
      var birth_date = pieces[0];
      var birth_month = pieces[1];
      var birth_year = pieces[2];

      if (curr_month == birth_month && curr_date >= birth_date) return parseInt(curr_year - birth_year);
      if (curr_month == birth_month && curr_date < birth_date) return parseInt(curr_year - birth_year - 1);
      if (curr_month > birth_month) return parseInt(curr_year - birth_year);
      if (curr_month < birth_month) return parseInt(curr_year - birth_year - 1);
    }
    $("#popupDatepicker1_modified").change(function() {
      var age1 = $('#popupDatepicker1_modified').val();
      var age = getAge(age1);
      $('#age').val(age);
      if (age < 18) {
        alert("Minor is Appointed! More than 18 years only eligible ");
        $("#age_id").text("More than 18 years only eligible");
        $("#submit").hide();
      } else {
        $("#age_id").text("");
        $("#submit").show();
      }
    });
    var age1 = $('#popupDatepicker1_modified').val();
    var age = getAge(age1);
    $('#age').val(age);
    if (age < 18) {
      alert("Minor is Appointed! More than 18 years only eligible ");
      $("#age_id").text("More than 18 years only eligible");
      $("#submit").hide();
    } else {
      $("#age_id").text("");
      $("#submit").show();
    }


  });

  $(".searchCriteria").on('change', function() {
    $('#keyword').val('');

    if ($('#search').val() == '') {
      $(".selectboxErr").css('display', 'inline');
      $(".selectregErr").css('display', 'none');
      $('.keywordErr').css('display', 'none');

    } else if ($('#search').val() == 'all') {
      if ($('#region').val() == '') {
        $(".selectregErr").css('display', 'inline');
        $("#search_criteria").prop("disabled", true);
      } else {
        $(".selectregErr").css('display', 'none');
        $("#search_criteria").prop("disabled", false);
      }
      $(".selectboxErr").css('display', 'none');
      $('.keywordErr').css('display', 'none');
    } else if ($('#search').val() == 'emp_id') {
      $(".selectboxErr").css('display', 'none');
      $('.keywordErr').css('display', 'inline');
      $(".selectregErr").css('display', 'none');
      $("#search_criteria").prop("disabled", true);

      $('#keyword').keyup(function() {
        if ($.trim($('#keyword').val()) == '') {
          $('.keywordErr').css('display', 'inline');
          $('#search_criteria').prop('disabled', true);
        } else {
          $('.keywordErr').css('display', 'none');
          $('#search_criteria').prop('disabled', false);
        }
      });
    } else {
      if ($('#region').val() == '') {
        $(".selectregErr").css('display', 'inline');
      } else {
        $(".selectregErr").css('display', 'none');
      }
      $("#search_criteria").prop("disabled", true);
      $(".selectboxErr").css('display', 'none');
      $('.keywordErr').css('display', 'inline');

      $('#keyword').keyup(function() {
        if ($.trim($('#keyword').val()) == '') {
          $('.keywordErr').css('display', 'inline');
          $('#search_criteria').prop('disabled', true);
        } else {
          $('.keywordErr').css('display', 'none');
          $('#search_criteria').prop('disabled', false);
        }

      });
    }

  });

  $(".searchRegion").on('change', function() {

    if ($('#region').val() == '') {
      $(".selectregErr").show();
    } else {
      if ($('#search').val() == 'emp_name' || $('#search').val() == 'pdesig1' || $('#search').val() == 'design') {
        $(".selectregErr").hide();
        $('#keyword').keyup(function() {
          if ($.trim($('#keyword').val()) == '') {
            $('.keywordErr').css('display', 'inline');
            $('#search_criteria').prop('disabled', true);
          } else {
            $('.keywordErr').css('display', 'none');
            $('#search_criteria').prop('disabled', false);
          }
        });
      } else {
        $(".selectregErr").hide();
        $("#search_criteria").prop("disabled", false);
      }
    }
  });

  function search_key(search_type, page_start) {
    if ($('#keyword').val() != '' || $('#search').val() != '' || $('#search').val() == 'all') {
      tbl_search = '';
 
      $.ajax({
        type: "POST",
        url: "HRMS/AjaxReference/hrmsLoadData.php",
        data: 'pgn=1&start_limit=' + page_start + '&tbl_search=' + tbl_search + '&per_page=' + $('#per_page').val() + '&end_limit=10&types=2&load=1&pid=emp_data_ce&search=' + $('#search').val() + '&keyword=' + $('#keyword').val() + '&region=' + $('#region').val(),
        beforeSend: function() {
          $('#view_details_indu').html('<img src="" alt="">');
        },
        success: function(msg) {
          $('#view_details_indu').html(msg);
          $('.search_field').css('display', '');


$("#to_add_empdata_datatable").DataTable({ ordering: false});
        }
      });
    } else {
      //  $('#keyword').addClass('error_dispaly');
      $(".selectboxErr").css('display', 'inline');
    }
  }

  function AddRow(obj) {

    var id = $(obj).attr('id') + "1";

    var data = "<tr>" + $("#" + id + " tbody tr:first").html() + "</tr>";
    $("#" + id + " tbody").append(data);
    sno = ($("#" + id + " tbody tr").length);
    $("#" + id + " tbody tr:last").find('td:eq(1)').html(sno);
    clear_row(id);

  }

  function clear_row(id) {
    $("#" + id + " tbody tr:last").find('input[type=text]').attr('value', '');
    ///$("#"+id+" tbody tr:last").find('input[type=hidden]').attr('value','');
    $("#" + id + " tbody tr:last").find('.hidden').attr('value', '');
    $("#" + id + " tbody tr:last").find('select').val('');
    $("#" + id + " tbody tr:last").find('input[type=checkbox]').attr('value', '0');
    //$("#"+id+" tbody tr:last").find('input[type=checkbox]').removeAttr("disabled");
    $("#" + id + " tbody tr:last").find('input[type=radio]').attr('value', '0');
    $("#" + id + " tbody tr:last").find('textarea').val('');
    $("#" + id + " tbody tr:last").find('td:last a').attr('rel', '0%0');
  }


  function DeleteRow(obj) {
    var ids = $(obj).attr('id');
    table_id = ids.substr(0, ids.length - 1) + "1";
    con = confirm("Do you want to Delete the Record?");
    if (con) {
      $('#' + table_id + ' tbody tr').find('.chkbox:checked').each(function() {
        var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
        closest_tr = $(this).closest('tr');
        var rel = $(this).closest('tr').find('td:last').find('a').attr('rel');
        data = rel.split('%');
        var id = data[0];
        var pid = data[1];
        var dtype = data[2];

        if (id != 0) {
          $.ajax({
            url: 'transaction_delete.php',
            data: {
              id: id,
              pid: pid,
              dtype: dtype
            },
            method: 'post',
            success: function(result) {
              var res = $.parseJSON(result);
              $(".alert").show();
              if (res['result_response'] == 'success') {
                if (tbl_tr_len == 1) {
                  var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
                  closest_tr.remove();
                  $("#" + table_id + " tbody").append(data);
                  clear_row(table_id);
                } else {
                  closest_tr.remove();
                }
              }
            }
          });
        } else {
          var tbl_tr_len = $('#' + table_id + ' tbody tr').length;

          if (tbl_tr_len == 1) {
            var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
            closest_tr.remove();
            $("#" + table_id + " tbody").append(data);
            clear_row(id);
          } else {
            closest_tr.remove();
          }
        }
      });
    }
  }

  function AddRow1(obj) {
    var id = $(obj).attr('id') + "1";

    var data = "<tr>" + $("#" + id + " tbody tr:first").html() + "</tr>";
    $("#" + id + " tbody").append(data);
    sno = ($("#" + id + " tbody tr").length);
    $("#" + id + " tbody tr:last").find('td:eq(1)').html(sno);
    clear_row(id);

  }

  function DeleteRow1(obj) {
    var ids = $(obj).attr('id');
    table_id = ids.substr(0, ids.length - 1) + "1";
    con = confirm("Do you want to Delete the Record?");
    if (con) {
      $('#' + table_id + ' tbody tr').find('.chkbox:checked').each(function() {
        var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
        closest_tr = $(this).closest('tr');
        var rel = $(this).closest('tr').find('td:last').find('a').attr('rel');
        data = rel.split('%');
        var id = data[0];
        var pid = data[1];
        var dtype = data[2];

        if (id != 0) {
          $.ajax({
            url: 'transaction_delete.php',
            data: {
              id: id,
              pid: pid,
              dtype: dtype
            },
            method: 'post',
            success: function(result) {
              var res = $.parseJSON(result);
              $(".alert").show();
              if (res['result_response'] == 'success') {
                if (tbl_tr_len == 1) {
                  var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
                  closest_tr.remove();
                  $("#" + table_id + " tbody").append(data);
                  clear_row(table_id);
                } else {
                  closest_tr.remove();
                }
              }
            }
          });
        } else {
          var tbl_tr_len = $('#' + table_id + ' tbody tr').length;

          if (tbl_tr_len == 1) {
            var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
            closest_tr.remove();
            $("#" + table_id + " tbody").append(data);
            clear_row(id);
          } else {
            closest_tr.remove();
          }
        }
      });
    }
  }


  function delete_data_row(obj) {
    var table_id = $(obj).closest('table').attr('id');
    var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
    con = confirm("Do you want to Delete the Record?");
    if (con) {
      var rel = $(obj).prop("rel");
      // alert(rel);
      data = rel.split('%');
      var id = data[0];
      var pid = data[1];
      var dtype = data[2];
      if (id != 0) {
        $.ajax({
          url: 'transaction_delete.php',
          data: {
            id: id,
            pid: pid,
            dtype: dtype
          },
          method: 'post',
          success: function(result) {
            //alert(result);
            var res = $.parseJSON(result);
            $(".alert").show();
            if (res['result_response'] == 'success') {
              //alert("success");
              if (tbl_tr_len == 1) {
                var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
                $(obj).closest('tr').remove();
                $("#" + table_id + " tbody").append(data);
                clear_row(table_id);
              } else {
                $(obj).closest('tr').remove();
              }
            }
          }
        });
      } else {
        if (tbl_tr_len == 1) {
          var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
          $(obj).closest('tr').remove();
          $("#" + table_id + " tbody").append(data);
          clear_row(table_id);
        } else {
          $(obj).closest('tr').remove();
        }
      }
    } else {
      return false;
    }
  }


  function delete_data_row1(obj) {
    var table_id = $(obj).closest('table').attr('id');
    var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
    con = confirm("Do you want to Delete the Record?");
    if (con) {
      var rel = $(obj).prop("rel");
      // alert(rel);
      data = rel.split('%');
      var id = data[0];
      var pid = data[1];
      var dtype = data[2];
      if (id != 0) {
        $.ajax({
          url: 'transaction_delete.php',
          data: {
            id: id,
            pid: pid,
            dtype: dtype
          },
          method: 'post',
          success: function(result) {
            //alert(result);
            var res = $.parseJSON(result);
            $(".alert").show();
            if (res['result_response'] == 'success') {
              //alert("success");
              if (tbl_tr_len == 1) {
                var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
                $(obj).closest('tr').remove();
                $("#" + table_id + " tbody").append(data);
                clear_row(table_id);
              } else {
                $(obj).closest('tr').remove();
              }
            }
          }
        });
      } else {
        if (tbl_tr_len == 1) {
          var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
          $(obj).closest('tr').remove();
          $("#" + table_id + " tbody").append(data);
          clear_row(table_id);
        } else {
          $(obj).closest('tr').remove();
        }
      }
    } else {
      return false;
    }
  }


  $(function() {
    $('#ctg1').change(function() {
      $('.colors').hide();
      $('#' + $(this).val()).show();
    });
  });

  $(function() {
    $('#nation').change(function() {
      $('.colors1').hide();
      $('#' + $(this).val()).show();
    });
  });

  $(function() {
    $('#app_type').change(function() {
      $('.repl_staff').hide();
      $('#' + $(this).val()).show();
    });
  });


  function setInfo(i, e) {
    $('#x').val(e.x1);
    $('#y').val(e.y1);
    $('#w').val(e.width);
    $('#h').val(e.height);
  }

  $(document).ready(function() {
	  
	   if($('#employment_types').val()=='Out sourced')
  {
    $('#vend_apnd').rules('add', {
                    required: true
                });
       $('#to_append_agency').show();
   }
   else
   {
       $('#to_append_agency').hide();
   }

	  
	  
    var p = $("#uploadPreview");

    // prepare instant preview
    $("#uploadImage").change(function() {
      // fadeOut or hide preview
      p.fadeOut();

      // prepare HTML5 FileReader
      var oFReader = new FileReader();
      oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

      oFReader.onload = function(oFREvent) {
        p.attr('src', oFREvent.target.result).fadeIn();
      };
    });

    // implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
    $('img#uploadPreview').imgAreaSelect({
      // set crop ratio (optional)
      aspectRatio: '1:1',
      onSelectEnd: setInfo
    });
  });

$("#employment_types").change(function(){


if($('#employment_types').val()=='Out sourced'){
      
  $('#vend_apnd').rules('add', {
                required: true
            });
      
  
      $('#to_append_agency').show();

      
}
else
{
  $('#to_append_agency').hide();

}

});

  function auto_id() {
    //alert($('#branch_name').html());
    //return false;
    var desig = $('#pdesig1').val();
    var id = $('#id').val();
    $.ajax({
      type: "POST",
      url: "ajax/hrms_get_all_detail.php",
      data: {
        loc: desig,
        pid: "auto_id"
      },
      success: function(res) {
        var res = $.parseJSON(res);
        $('#emp_id').html(res['row_data']);
        $('#emp_id').trigger("chosen:updated");
      }
    });
  }


  function cal_deno() {
    //alert('hai');
    var region = $('#region1').val();

    if (region == 'Mumbai') {

      var gross_sal = $('#gross_sal1').val();
      //alert(gross_sal);
      var basic_pay = $('#basic_pay').val();
      //alert(basic_pay);
      var hra = Math.round((basic_pay) * (5 / 100));
      var bonous = Math.round((gross_sal) * (8.33 / 100));
      $('#hra').val(hra);
      $('#conveyance').val(800);
      $('#bonus').val(bonous);

      //alert(oth_all);

      if (gross_sal >= 30000) {
        medical = 1250;
      } else {
        medical = 0;
      }
      $('#medical').val(medical);
      var oth_all = parseInt(gross_sal) - (parseInt(basic_pay) + parseInt(hra) + parseInt(bonous) + 800 + parseInt(medical));
      //var oth_all=(gross_sal)-(basic_pay+hra+bonous+800+medical);
      $('#oth_all').val(oth_all);

    } else if (region == 'Kolkata') {

      //	alert("hai");
      var gross_sal = $('#gross_sal1').val();
      var basic_pay = gross_sal / 2;
      var hra = basic_pay / 2;
      //alert(gross_sal);
      var bonous = 0;
      $('#basic_pay').val(basic_pay);
      $('#hra').val(hra);
      $('#conveyance').val(800);
      $('#bonus').val(bonous);

      var medical = 0;

      $('#medical').val(medical);
      var oth_all = (parseInt(gross_sal)) - (parseInt(basic_pay) + parseInt(hra) + parseInt(bonous) + 800 + parseInt(medical));
      $('#oth_all').val(oth_all)


    } else if (region == 'Pune') {

      //alert("hai");
      //var gross_sal =$('#gross_sal1').val();
      //var basic_pay=gross_sal/2;
      //	var hra=basic_pay/2;
      //alert(gross_sal);
      var bonous = 0;
      var p_basic = $('#basic_pay').val();
      var p_hra = $('#hra').val();
      var p_conv = $('#conveyance').val();
      var p_othall = $('#oth_all').val();

      var p_bonus = Math.round((p_basic) * (8.33 / 100));

      //alert(p_bonus);

      $('#bonus').val(p_bonus);

      var gross_sal1 = (parseInt(p_basic) + parseInt(p_hra) + parseInt(p_conv) + parseInt(p_othall) + parseInt(p_bonus));
      $('#gross_sal1').val(gross_sal1)


    } else {
      var gross_sal = $('#gross_sal1').val();
      var basic_pay = gross_sal / 2;
      var hra = basic_pay / 2;
      //alert(gross_sal);
      var bonous = Math.round((gross_sal) * (8.33 / 100));
      $('#basic_pay').val(basic_pay);
      $('#hra').val(hra);
      $('#conveyance').val(800);
      $('#bonus').val(bonous);


      if (gross_sal >= 30000) {
        medical = 1250;
      } else {
        medical = 0;
      }
      $('#medical').val(medical);
      var oth_all = (parseInt(gross_sal)) - (parseInt(basic_pay) + parseInt(hra) + parseInt(bonous) + 800 + parseInt(medical));
      $('#oth_all').val(oth_all);
      /*	var esi=Math.round((gross_sal)*(1.75/100));
      	$('#esi').val(esi);
      	var epf=Math.round((basic_pay)*(15/100));*/
    }
  }
  <?php if ($id == '') { ?>

    function load_alert() {
      ce_name = $('#namefdsf').val();
      dob = $('#popupDatepicker1_modified').val();
      doj = $('#popupDatepicker').val();
      mobile = $('#mobile1').val();
      pincode = $('#pin').val();
      pan_card = $('#pan_card_no').val();
      if (pincode != '') {
        $.ajax({
          type: "POST",
          url: "HRMS/AjaxReference/hrmsLoadData.php",
          data: 'pid=rad_ce&ce_name=' + ce_name + '&pincode=' + pincode + '&dob=' + dob + '&doj=' + doj + '&mobile=' + mobile,
          success: function(msg) {
            var res = $.parseJSON(msg);
            if (res['res'] == 'nil') {
              $('#load_lod_shop1').css('display', 'none');
              $('#load_lod_shop').css('display', 'none');
              $('#load_lod_shop1').html('');
              $('#load_lod_shop').html('');

            } else {
              $('#load_lod_shop1').css('display', '');
              $('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate Employee!<br />For your Convenience the list of Employee  in the same Pin Code,Mobile No,Date Of Birth and Date of Join are listed below please check if this Employee appears in the list.');
              $('#load_lod_shop').css('display', '');
              $('#load_lod_shop').html(res['res']);
              $('#submit').css('display', 'none');
              $('#submit').html('');
            }

          }
        });
      }
    }
  <?php } ?>

  function load_location() {
    $.ajax({
      type: "POST",

      url: "HRMS/AjaxReference/hrmsLoadData.php",
      data: 'pid=emp_data_location&region_name=' + $('#region1').val() + '&id=' + $('#id').val(),
      success: function(msg) {
        $('#plocation').html(msg);
        $('#plocation').trigger("chosen:updated");
      }
    });
  }

  function load_status() {
    var pdesig1 = $('.pdesig1').val();

    if (pdesig1 == 'MBC' || pdesig1 == 'CE' || pdesig1 == 'EXE' || pdesig1 == 'CVC' || pdesig1 == 'CCE' || pdesig1 == 'CVCE' || pdesig1 == 'GN' || pdesig1 == 'DR') {

      $('.status_ld').show();

      $('#ce_status').rules('add', {
                    required: true
                });
      if($('#ce_status').val()=='Dormant')
      {
           $('#inactv_remarks').rules('add', {
                    required: true
                });
          
                $('#to_append_inactive_status').show();
      }
      else{
        $('#to_append_inactive_status').hide();
       // $('#inactv_remarks').rules('remove', 'required');
      }
    } else {


      $('.status_ld').hide();
      $('#to_append_inactive_status').hide();
      $('#ce_status').rules('remove', 'required');
      $('#inactv_remarks').rules('remove', 'required');
    }
    if (pdesig1 == 'CE') {
      $(".hide_sal").show();
    }

  }
  $("#submit1").click(function() {
    var emp_id = $("#emp_id").val();
    var cname = $("#cname").val();
    var doj = $("#popupDatepicker").val();
    var dob = $("#popupDatepicker1_modified").val();
    var location = $("#plocation").val();
    var mobile1 = $("#mobile1").val();
    var mobile2 = $("#mobile2").val();
    var email = $("#email").val();
    var father_name = $("#father_name").val();
    var pancard = $("#pan_card_no").val();
    var pin = $("#pin").val();
    var address = $("#address").val();
    var desig = $("#pdesig1").val();
    var wstatus = $("#ce_status").val();
    var gender = $("#gender").val();

    $.ajax({
      url: "CommonReference/hrms_add_details.php",
      type: "POST",
      data: {
        pid: "empdata_merge",
        emp_id: emp_id,
        cname: cname,
        dob: dob,
        doj: doj,
        location: location,
        mobile1: mobile1,
        mobile2: mobile2,
        email: email,
        father_name: father_name,
        pancard: pancard,
        pin: pin,
        address: address,
        desig: desig,
        gender: gender,
        wstatus: wstatus
      },
      success: function(result) {

        alert("success");

      },
    });
  });
</script>
<script>
  $(function() {

    $("input").keyup(function() {
      var yourInput = $(this).val();
      re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
      var isSplChar = re.test(yourInput);
      if (isSplChar) {
        var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
        $(this).val(no_spl_char);
      }
    });

  });
  var specialKeys = new Array();
  specialKeys.push(8); //Backspace
  specialKeys.push(9); //Tab
  specialKeys.push(32); //Tab
  specialKeys.push(46); //Delete	
  specialKeys.push(36); //Home
  specialKeys.push(35); //End
  specialKeys.push(37); //Left
  specialKeys.push(39); //Right
  function IsAlphaNumeric(e) {
    var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
    var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
    document.getElementById("error").style.display = ret ? "none" : "inline";
    return ret;
  }

  //load report to


  // guwahati pay role

  function gud_pay() {
    var res = 0;
    $("#demo-validation .gud_pay").each(function() {
      //alert($(this).val());
      if ($(this).val() != "") {
        var ans = parseFloat($(this).val());
        res += ans;
        $("#gross_sal1").val(res);

      }
    });
  }


  function dublicate_number() {

    aadhar_card_no = $('#aadhar_card_no').val();
    //if(pincode!='') {
    $.ajax({
      type: "POST",
      url: "HRMS/AjaxReference/load_data_new.php",
      data: 'types=4&pid=emp_data&aadhar_card_no=' + aadhar_card_no,
      success: function(msg) {
        //alert(msg);
        if (msg != '') {
          $('#submit1').css('display', '');
          $('#submit').css('display', 'none');
          $('#load_lod_shop1').css('display', '');
          $('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate Employee details!');
          $('#load_lod_shop').css('display', '');
          $('#load_lod_shop').html(msg);
        } else {
          $('#submit1').css('display', 'none');
          $('#submit').css('display', '');
          $('#load_lod_shop1').css('display', 'none');
          $('#load_lod_shop').css('display', 'none');
          $('#load_lod_shop1').html('');
          $('#load_lod_shop').html('');
        }

      }
    });
    //}
  }

  function dublicate_pan() {
  
    pan_card_no = $('#pan_card_no').val();
    //if(pincode!='') {
    $.ajax({
      type: "POST",
      url: "HRMS/AjaxReference/load_data_new.php",
      data: 'types=5&pid=emp_data&pan_card_no=' + pan_card_no,
      success: function(msg) {
        //alert(msg);
        if (msg != '') {
          $('#submit1').css('display', '');
          $('#submit').css('display', 'none');
          $('#load_lod_shop1').css('display', '');
          $('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate Employee details!');
          $('#load_lod_shop').css('display', '');
          $('#load_lod_shop').html(msg);
          $('#pan_card_no').val('');
          window.scrollTo(500, 0);
        } else {
          $('#submit1').css('display', 'none');
          $('#submit').css('display', '');
          $('#load_lod_shop1').css('display', 'none');
          $('#load_lod_shop').css('display', 'none');
          $('#load_lod_shop1').html('');
          $('#load_lod_shop').html('');
        }

      }
    });
    //}
  }

  function dublicate_pan1() {

    account_no = $('#account_no').val();
    $.ajax({
      type: "POST",
      url: "HRMS/AjaxReference/load_data_new.php",
      data: 'types=6&pid=emp_data&account_no=' + account_no,
      success: function(msg) {
        //alert(msg);
        if (msg != '') {
          $('#submit1').css('display', '');
          $('#submit').css('display', 'none');
          $('#load_lod_shop1').css('display', '');
          $('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate Employee details!');
          $('#load_lod_shop').css('display', '');
          $('#load_lod_shop').html(msg);
          $('#account_no').val('');
        } else {
          $('#submit1').css('display', 'none');
          $('#submit').css('display', '');
          $('#load_lod_shop1').css('display', 'none');
          $('#load_lod_shop').css('display', 'none');
          $('#load_lod_shop1').html('');
          $('#load_lod_shop').html('');
        }

      }
    });
    //}
  }

  //Dharanipathi //
  function dublicate_replaceid() {

    replace_id = $('#replace_id').val();

    $.ajax({
      type: "POST",
      url: "HRMS/AjaxReference/load_data_new.php",
      data: 'types=8&pid=emp_data&replace_id=' + replace_id,
      success: function(msg) {
        //alert(msg);
        if (msg != '') {
          $('#submit1').css('display', '');
          $('#submit').css('display', 'none');
          $('#load_lod_shop1').css('display', '');
          $('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate Employee details!');
          $('#load_lod_shop').css('display', '');
          $('#load_lod_shop').html(msg);
          $('#replace_id').val('');
        } else {
          $('#submit1').css('display', 'none');
          $('#submit').css('display', '');
          $('#load_lod_shop1').css('display', 'none');
          $('#load_lod_shop').css('display', 'none');
          $('#load_lod_shop1').html('');
          $('#load_lod_shop').html('');
        }

      }
    });
  }

  function nospaces(t) {
    if (t.value.match(/\s/g)) {
      t.value = t.value.replace(/\s/g, '');
    }
  }


  function checkIfsc() {
    var len = $("#ifsc_code").val().length;
    var code = $("#ifsc_code").val();
    if (len > 4) {
      var char = code[4];
      if (char != '0') {
        alert('Invalid IFSC Code (5th digit must be zero)');
        $("#ifsc_code").val("");
      }
    }
    if (len != 11) {
     // $("#alt-ifsc").show("fadeIn");
    } else {
      //$("#alt-ifsc").hide("fadeOut");
    }
  }
  $('.spl').bind('input', function() {
    var c = this.selectionStart,
      r = /[^a-z0-9]/gi,
      v = $(this).val();
    if (r.test(v)) {
      $(this).val(v.replace(r, ''));
      c--;
    }
    this.setSelectionRange(c, c);
  });


  /*******************************For Remarks */
$("#ce_status").change(function(){

if($('#ce_status').val()=='Dormant'){
    
    $('#inactv_remarks').rules('add', {
                  required: true
              });
        
    
        $('#to_append_inactive_status').show();
  
        
  }
  else
  {
    $('#to_append_inactive_status').hide();
    $('#inactv_remarks').rules('remove', 'required');
  
  }

});
</script>
