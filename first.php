<?php
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$up = $_REQUEST['up'];
$get_typ = $_REQUEST['get_typ'];
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <?php if($nav!='') { ?>
            <br />
            <br />
            <div class="message_cu">
                <div style="padding: 7px;"
                    class="alert <?php if($nav==2) { echo 'alert-danger'; } else { echo 'alert-success'; } ?>"
                    align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert"
                        class="close">×</a> <b>
                        <?php
                  $status_cu = array(1=>'New User Successfully Added', 2=>'Sorry, Please Try Again', 3=>'Select User Details Updated');
                  echo $status_cu[$nav];
                  ?>
                    </b> </div>
            </div>
            <?php } 
			  
			  if($id!='') {
				  if($get_typ==1) {
					 $cond = "emp_id='".$id."'"; 
				  }
				  else {
					 $cond = "user_name='".$id."'"; 
				  }
				 // echo "SELECT * FROM login WHERE $cond";
				$sql1 = mysql_query("SELECT * FROM login WHERE $cond");
				$r1 = mysql_fetch_object($sql1); 
				$cu_region = explode(',', $r1->region); 
				$client_id = explode(',', $r1->client_id); 
                $vendor_id = explode(',', $r1->vendor_id);
				$pgroup_name = explode(',', $r1->pgroup_name); 
				$dgroup_name = explode(',', $r1->dgroup_name); 
				$use_name_lo = $r1->user_name;
				$updateby = $r1->updated_by;
				$updateddate = $r1->updated_date;
			  }
			  ?>
            <form action="<?php echo 'CommonReference/add_details.php?pid='.$pid; ?>" method="post" enctype="multipart/form-data"
                id="user-form" data-validate="parsley" class="form parsley-form">
                <div class="portlet">
                    <h3 class="portlet-title"><u>Radiant User Management</u></h3>
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">*</label>
                            Staff ID
                        </label>
                        <input type="text" name="staff_id" id="staff_id" class="form-control parsley-validated"
                            placeholder="Staff ID" value="<?php echo $r1->emp_id; ?>">
                    </div>
                    <!--<div class="form-group col-sm-3">
            <button style="margin-top: 25px;" class="btn btn-danger get_user_info" type="button">Get</button>
          </div>-->
                    <div class="clear"></div>
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">*</label>
                            User Name
                        </label>
                        <input type="text" name="user_name" <?php if($id!='') { echo 'readonly="readonly"'; } ?>
                            id="user_name" class="form-control parsley-validated" placeholder="User Name"
                            value="<?php echo $r1->user_name; ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">*</label>
                            Password
                        </label>
                        <input type="<?php if($r1->status=='Denied') { echo 'password'; } else { echo 'password'; } ?>"
                            name="password" id="password" class="form-control parsley-validated" placeholder="Password"
                            value="<?php echo $r1->password; ?>">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">*</label>
                            Permission
                        </label>
                        <select id="permission" name="permission" class="form-control parsley-validated chosen-select"
                            data-required="true">
                            <option value="">Select Permission</option>
                            <option value="Staff" <?php if($r1->per=='Staff') { echo 'selected="selected"'; } ?>>Staff
                            </option>
                            <option value="Admin" <?php if($r1->per=='Admin') { echo 'selected="selected"'; } ?>>Admin
                            </option>
                            <option value="Manager" <?php if($r1->per=='Manager') { echo 'selected="selected"'; } ?>>
                                Manager</option>
                            <option value="Data Management"
                                <?php if($r1->per=='Data Management') { echo 'selected="selected"'; } ?>>Data Management
                            </option>
                            <option value="Banking" <?php if($r1->per=='Banking') { echo 'selected="selected"'; } ?>>
                                Banking</option>
                            <option value="HR" <?php if($r1->per=='HR') { echo 'selected="selected"'; } ?>>HR</option>

                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">*</label>
                            Status
                        </label>
                        <select id="status" name="status" onChange="selectChange(this)"
                            class="form-control parsley-validated chosen-select" data-required="true">
                            <option value="">Select Permission</option>
                            <option value="Allowed" <?php if($r1->status=='Allowed') { echo 'selected="selected"'; } ?>>
                                Allowed</option>
                            <option value="Denied" <?php if($r1->status=='Denied') { echo 'selected="selected"'; } ?>>
                                Denied</option>
                            <option value="Resigned"
                                <?php if($r1->status=='Resigned') { echo 'selected="selected"'; } ?>>Resigned</option>
                        </select>
                    </div>

                    <div class="clear"></div>
                    <div class="clear"></div>
                    <div class="clear"></div>


                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory"></label>
                            Special Privileges
                        </label>
                        <select id="other_permissions" name="other_permissions[]"
                            class="form-control parsley-validated chosen-select" multiple data-required="true">
                            <option value="">None</option>
                            <?php
			  $UsersPrivilege = array();
			  $SpecialPrivileges = array();
			  $QueryOfPrivilege = mysql_query(" SELECT GROUP_CONCAT(privilegeName) as Privilege_Name FROM special_privilege WHERE status = 'Y' " );
			  IF(mysql_num_rows($QueryOfPrivilege) > 0){
				  $FetchOfPrivilege = mysql_fetch_array($QueryOfPrivilege);
				  $SpecialPrivileges = explode(',',$FetchOfPrivilege['Privilege_Name']);
			  }
			  
			  //$SpecialPrivileges = array("Centralized Push","Hrms CE Cash Pickup Agreement","Hrms RPF Appointment Letter","Hrms Employee Appointment/Offer Letter");
			  if(!empty($r1->other_permissions)){  $UsersPrivilege = explode(',',$r1->other_permissions); }
			  foreach($SpecialPrivileges as $key=>$FetchPrivilege){
				  $RemoveSpace = str_replace(" ","",$FetchPrivilege);
				  $SelectedPrivilege = ''; if($id!=''){if(in_array($RemoveSpace,$UsersPrivilege)){ $SelectedPrivilege = 'selected="selected"'; }}
				  echo '<option value="'.$RemoveSpace.'" '.$SelectedPrivilege.'>'.$FetchPrivilege.'</option>';
				  
			  }
			  
			  
			  ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                    <?php
					$sql2 = mysql_query("select pid from auth_t where user_name='".$use_name_lo."' and status='Y'");
					$r2 = mysql_fetch_object($sql2);
					$pages = explode(",",$r2->pid); 
				
				 ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">SELECT REGIONS</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all"
                                        value="0" />
                                    Check All</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
				   $i = 0;
				  $sql_reg = mysql_query("SELECT region_id,region_name  FROM region_master WHERE status='Y' ORDER BY region_name");
				  while($res_reg = mysql_fetch_object($sql_reg)) {
					  $j = $i%7;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="region[]"';
					  if($id!=''){if(in_array($res_reg->region_id, $cu_region)) { echo 'checked="checked"'; }}
					  echo ' value="'.$res_reg->region_id.'" class="parsley-validated page_check0" data-mincheck="2">'.$res_reg->region_name.'</td>';
					  if($j==6){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
                        </tbody>
                    </table>

                    <?php
			$query_pg =  "SELECT menu_name,menu_creation,page_name,page_id, menu_icon FROM page_creation WHERE (status='Y'  OR status='Z')AND menu_creation!='' AND page_title!='Home' AND page_order!='0' ORDER BY  page_order_main, page_order ASC";
			  $sql_pg = mysql_query($query_pg);
			  while($res_pg = mysql_fetch_assoc($sql_pg)) {
				  $menu_det[$res_pg['menu_name']]['page_title'][] = $res_pg['menu_creation'];
				  $menu_det[$res_pg['menu_name']]['page_name'][] = $res_pg['page_name'];
				  $menu_det[$res_pg['menu_name']]['page_id'][] = $res_pg['page_id'];
				  $menu_det[$res_pg['menu_name']]['menu_icon'][] = $res_pg['menu_icon'];
				  
			  }
			$k = 1;
			
			  foreach($menu_det as $key=>$val) {
			?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center"><?php echo strtoupper($key); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all"
                                        value="<?php echo $k; ?>" />
                                    Check All</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
				   $i = 0;
				  foreach($val['page_title'] as $key1=>$val1) {
					  $j = $i%7;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="page[]"';
					  if($id!=''){if(in_array($val['page_id'][$key1], $pages)) { echo 'checked="checked"'; }}
					  echo ' value="'.$val['page_id'][$key1].'" class="parsley-validated page_check'.$k.'" data-mincheck="2">'.$val1.'</td>';
					  if($j==6){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
                        </tbody>
                    </table>
                    <?php
		  $k++;
			  }
			  
		  ?>

          			<!-- start vendors -->
			<table class="table table-bordered">
				<thead>
					<tr>
						<th colspan="7"><div align="center">ALL VENDORS</div></th>
					</tr>
					<tr>
						<td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all" value="vendor_new" />
						Check All</td>
					</tr>
				</thead>
            <tbody>
              <?php
			 	$sql_vendor = mysql_query("SELECT vendor_id, vendor_name FROM vendor_details WHERE status='Y'");
				   $i = 0;
				  while($res_vendor = mysql_fetch_object($sql_vendor)) {
					  $j = $i%6;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="vendor_id[]"';
					  if(is_array($vendor_id)&&in_array($res_vendor->vendor_id, $vendor_id)) { echo 'checked="checked"'; }
					  echo ' value="'.$res_vendor->vendor_id.'" class="parsley-validated page_checkvendor_new" data-mincheck="2">'.ucwords(strtolower($res_vendor->vendor_name)).'</td>';
					  if($j==5){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
            </tbody>
          </table>
			<!-- end vendors -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">ALL CLIENTS</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all"
                                        value="20" />
                                    Check All</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
			 // echo "SELECT client_id, client_name FROM client_details WHERE status='Y'";
			  	$sql_client = mysql_query("SELECT client_id, client_name FROM client_details WHERE status='Y'");
				   $i = 0;
				  while($res_client = mysql_fetch_object($sql_client)) {
					  $j = $i%6;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="client_id[]"';
					 if($id!=''){if(in_array($res_client->client_id, $client_id)) { echo 'checked="checked"'; }}
					  echo ' value="'.$res_client->client_id.'" class="parsley-validated page_check20" data-mincheck="2">'.ucwords(strtolower($res_client->client_name)).'</td>';
					  if($j==5){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
                        </tbody>
                    </table>
                    <div class="clear"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">PICKUP GROUPS</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all"
                                        value="21" />
                                    Check All</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
			  	$sql_group_p = mysql_query("SELECT group_name FROM cust_details WHERE group_name!='' AND status='Y' GROUP BY group_name");
				   $i = 0;
				  while($res_group_p = mysql_fetch_object($sql_group_p)) {
					  $j = $i%6;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="pgroup_name[]"';
					  if($id!=''){if(in_array($res_group_p->group_name, $pgroup_name)) { echo 'checked="checked"'; }}
					  echo ' value="'.$res_group_p->group_name.'" class="parsley-validated page_check21" data-mincheck="2">'.ucwords(strtolower($res_group_p->group_name)).'</td>';
					  if($j==5){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">DELIVERY GROUPS</div>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align:right;"><input type="checkbox" class="check_all"
                                        value="22" />
                                    Check All</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
			  	$sql_group_d = mysql_query("SELECT dgroup_name FROM cust_details WHERE dgroup_name!='' AND status='Y' GROUP BY dgroup_name");
				   $i = 0;
				  while($res_group_d = mysql_fetch_object($sql_group_d)) {
					  $j = $i%6;
					  if($j==0){
						  echo '<tr>';
					  }
					  echo '<td width="200"><input type="checkbox" name="dgroup_name[]"';
					  if(!empty($dgroup_name)) {
					  	if($id!=''){if(in_array($res_group_d->dgroup_name, $dgroup_name)) { echo 'checked="checked"'; }}
					  }
					  echo ' value="'.$res_group_d->dgroup_name.'" class="parsley-validated page_check22" data-mincheck="2">'.ucwords(strtolower($res_group_d->dgroup_name)).'</td>';
					  if($j==5){
						  echo '</tr>';
					  }
					  
					  $i++;
				  }
				  ?>
                        </tbody>
                    </table>
                    <div class="clear"></div>
                    <div class="form-group  col-sm-3">
                        <?php if($id!='') {
				?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <?php  
			  }?>
                        <button type="submit" class="btn btn-danger search_btn" name="submit" value="submit" id="submit"
                            tabindex="14">
                            <?php if($id!='') { echo 'Update'; } else { echo 'Add New'; } ?>
                            User</button>
                        <br />
                        <?php if($per == 'Admin' || 'Manager')
			 {
			 echo $updateby.'-'.$updateddate;
			 }
            ?>
                    </div>
                </div>
            </form>
            <br /><br />
            <div class="clear"></div>
            <!-- customize search -->
            <div class="portlet">
                <h3 class="portlet-title">
                    <u>Customize Search</u>
                </h3>
                <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg">
                    <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
                    <b>Selected Client's Customer Details Deleted</b>
                </div>
                <form id="userform" action="#" data-validate="parsley" class="form parsley-form">
                    <div class="form-group col-sm-3">
                        <label for="name"><label class="compulsory">*</label>Search Criteria </label>
                        <select id="search" name="search"
                            class="form-control parsley-validated chosen-select searchCriteria" data-required="true">
                            <option value="">Select Option</option>
                            <option value="All">All Users</option>
                            <option value="Staff ID">Employee ID</option>
                            <option value="Staff Name">Employee Name</option>
                        </select>
                        <span id="criteriaErr" style="color:red;display:none">Select any criteria </span>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="name"><label class="compulsory hid_brn">*</label>Branch Name </label>
                        <select id="region" name="region" class="form-control parsley-validated chosen-select"
                            data-required="true">
                            <option value="">select</option>
                            <option value="All">All Region</option>
                            <?php
					 $sql = "SELECT DISTINCT  region_name,region_id FROM region_master WHERE status = 'Y'";
					 $res_reg = mysql_query($sql);
					 while($rreg  = mysql_fetch_object($res_reg))
					 { ?>
                            <option value="<?php echo $rreg->region_name ?>"><?php echo  $rreg->region_name ?></option>
                            <?php
					 }
					 ?>
                        </select>
                        <span id="selectregErr" style="color:red;display:none">Select region </span>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="name"><label class="compulsory hide_brn_key" style="display:none;">*</label>Enter
                            Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="form-control parsley-validated"
                            data-required="true" placeholder="Enter Keyword">
                        <span id="keywordErr" style="color:red;display:none">Enter keyword </span>
                    </div>
                    <div class="form-group  col-sm-3">
                        <button type="button" class="btn btn-danger search_btn" id="search_criteria"
                            onclick="search_key('1','0')" style="margin-top: 23px;">
                            Search</button>
                    </div>
                </form>
                <div class="clear"></div>
                <div class="clear"></div>
                <div id="view_details_indu"></div>
            </div>
            <!--customize search -->

            <div class="clear"></div>
            <div id="view_details_indu"></div>

        </div>
        <!-- /.portlet -->

    </div>
    <!-- /.col -->

</div>
<!-- /.row -->

</div>
<!-- /.container -->

<style type="text/css">
#permission-error,
#status-error {
    top: 66px;
    position: relative;
    left: -48px;
}

.btn-styl {
    padding-left: 66pc;
}
</style>
<script type="text/javascript">
$(document).ready(function() {

    
  



    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    setTimeout(function() {
        $('.message_cu').fadeOut('fast');
    }, 3000);
    $("body").on("change", ".check_all", function() {
        vals = $(this).val();
        if ($(this).is(':checked')) {
            $('.page_check' + vals).prop('checked', 'checked');
        } else {
            $('.page_check' + vals).removeAttr('checked');
        }
    });
    $("body").on("click", ".get_user_info", function() {
        location.href = "?pid=m_user&id=" + $('#staff_id').val() + '&get_typ=1';
    });
    $.validator.setDefaults({
        ignore: ":hidden:not(select)"
    });
    $("#user-form").validate({
        rules: {
            staff_id: {
                required: true
            },
            user_name: {
                required: true
            },
            password: {
                required: true
            },
            permission: {
                required: true
            },
            status: {
                required: true
            }
        },
        messages: {
            staff_id: {
                required: 'Enter Staff ID'
            },
            user_name: {
                required: 'Enter User Name'
            },
            password: {
                required: 'Enter Password'
            },
            permission: {
                required: 'Select Permission'
            },
            status: {
                required: 'Select Status'
            }
        },
        submitHandler: function(form, event) {
        
        var selectedPermissions = $('#other_permissions').val();
        
        if (!selectedPermissions || selectedPermissions.length === 0) {
            alert('Please Select None Option if you dont need permission');
            return false; 
        } else {
            form.submit(); 
        }
    }
    });

});

$(".searchCriteria").on('change', function() {
    $('#keyword').val('');
    if ($('#search').val() == 'Staff Name') {
        $('.hid_brn').show();
    }
    if ($('#search').val() == '') {
        $("#criteriaErr").css('display', 'inline');
        $("#selectregErr").css('display', 'none');
        $('#keywordErr').css('display', 'none');
        $('.hide_brn_key').hide();
    } else if ($('#search').val() == 'All') {
        if ($('#region').val() == '') {
            $("#selectregErr").css('display', 'inline');
            $("#search_criteria").prop("disabled", true);
            $('.hid_brn').show();
            //$('.hid_brn_key').show();
        } else {
            $("#selectregErr").css('display', 'none');
            $("#search_criteria").prop("disabled", false);
            $('.hid_brn').hide();
            //$('.hid_brn_key').hide();
        }
        $("#criteriaErr").css('display', 'none');
        $('#keywordErr').css('display', 'none');
        $('.hide_brn_key').hide();
    } else if ($('#search').val() == 'Staff ID') {
        $("#criteriaErr").css('display', 'none');
        $('#keywordErr').css('display', 'inline');
        $('.hide_brn_key').show();
        $("#selectregErr").css('display', 'none');
        $("#search_criteria").prop("disabled", true);
        $('.hid_brn').hide();
        //$('.hid_brn').show();
        $('#keyword').keyup(function() {
            if ($.trim($('#keyword').val()) == '') {
                $('#keywordErr').css('display', 'inline');
                $('.hide_brn_key').show();
                $('#search_criteria').prop('disabled', true);
                //$('.hid_brn_key').show();
            } else {
                $('#keywordErr').css('display', 'none');
                $('.hide_brn_key').hide();
                $('#search_criteria').prop('disabled', false);
                //$('.hid_brn_key').hide();
            }
        });
    } else {
        if ($('#region').val() == '') {
            $("#selectregErr").css('display', 'inline');
        } else {
            $("#selectregErr").css('display', 'none');
        }
        $("#search_criteria").prop("disabled", true);
        $("#criteriaErr").css('display', 'none');
        $('#keywordErr').css('display', 'inline');
        $('.hide_brn_key').show();

        $('#keyword').keyup(function() {
            if ($.trim($('#keyword').val()) == '') {
                $('#keywordErr').css('display', 'inline');
                $('.hide_brn_key').show();
                $('#search_criteria').prop('disabled', true);
                //$('.hid_brn_key').show();
            } else {
                $('#keywordErr').css('display', 'none');
                $('.hide_brn_key').hide();
                $('#search_criteria').prop('disabled', false);
                //$('.hid_brn_key').hide();
            }
        });
    }
});
$("#region").on('change', function() {
    if ($('#region').val() == '') {
        $("#selectregErr").show();
    } else {
        if ($('#search').val() == 'Staff Name') {
            $('.hid_brn').show();
            //$('.hid_brn_key').show();
            $("#selectregErr").hide();
            $('#keyword').on('keyup', function() {
                if ($.trim($('#keyword').val()) == '') {
                    $('#keywordErr').css('display', 'inline');
                    $('.hide_brn_key').show();
                    $('#search_criteria').prop('disabled', true);
                } else {
                    $('#keywordErr').css('display', 'none');
                    $('.hide_brn_key').hide();
                    $('#search_criteria').prop('disabled', false);
                }
            });
        } else {
            $("#selectregErr").hide();
            $("#search_criteria").prop("disabled", false);
        }
    }
});

function search_key(search_type, page_start) {

    if ($('#search').val() == '') {
        $('#criteriaErr').show();
        return false;
    } else {
        $('#criteriaErr').hide();
    }

    if ($("#keyword").val() != '' || $("#region").val() != ' ' || $("#search").val() != ' ') {
        tbl_search = '';
		search = $("#search").val();
            region = $("#region").val();
            keyword = $("#keyword").val();
  
        $.ajax({
            type: "POST",
            url: "Locations/AjaxReference/locationsLoadData.php",
            data: 'pgn=1&search=' +search + '&region=' + region + '&per_page=' + $('#per_page').val() + '&keyword=' + keyword +
                '&types=4&pid=m_user',
            beforeSend: function() {
                $('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
            },
            success: function(msg) {
                //alert(msg);
                $('#view_details_indu').html(msg);
				$("#rcmsUsers").DataTable({ 
					ordering: false,
					language: {
                            emptyTable: "No data available"
                        }});

            },
            error: function(xhr) {
                alert("An error occured: " + xhr.status + " " + xhr.statusText);
            }
        });
    } else {
        //  $('#keyword').addClass('error_dispaly');
        $("#criteriaErr").css('display', 'inline');
    }
}

function release_data(user_id) {
    $.ajax({
        type: "POST",
        url: "Locations/AjaxReference/locationsLoadData.php",
        data: 'pgn=1&user_id=' + user_id + '&types=5&pid=m_user',
        success: function(msg) {
            $('#release_' + user_id).html(msg);
        }
    });
}
</script>

<script type="text/javascript">
function selectChange(element) {
    if (element.options[element.selectedIndex].value == 'Resigned')
        alert("Are you sure you want to choose Resigned option?");
}
</script>