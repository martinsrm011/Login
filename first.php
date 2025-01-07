<?php
include ('CommonReference/date_picker_link.php');
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$state = $_REQUEST['state'];


$user=$_SESSION['lid'];

$sql_user = mysql_query("select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysql_fetch_array($sql_user);
$client_prev = $res_user['client_id'];

function getshop_id($readConnection)
{
  //$sql5 = mysql_query("SELECT shop_id FROM shop_details ORDER BY shop_id DESC LIMIT 0,1");
  $sql5 = mysql_query("SELECT COALESCE( MAX( CAST( SUBSTRING( shop_id, LOCATE(  'C', shop_id ) +1, 10 ) AS UNSIGNED ) ) , 0 ) +1 AS nos
FROM shop_details");
  if (mysql_num_rows($sql5) > 0) {
    $res5 = mysql_fetch_object($sql5);

    //$lshop_id1=substr($res5->shop_id,3,5)+1;
    $lshop_id1 = $res5->nos;

    $digot1 = abs(strlen($lshop_id1) - 5);
    if (strlen($lshop_id1) <= 4) {
      $s = sprintf("%0" . $digot1 . "d", '0') . $lshop_id1;
    } else {
      $s =   $lshop_id1;
    }
    $pshop_id = "RSC" . $s;
  } else {
    $pshop_id = "RSC00001";
  }
  return $pshop_id;
}
?>

<style type="text/css">
.wrap_point {
    word-wrap: break-word;
    width: 200px;
}
</style>
<link rel="stylesheet" type="text/css" href="css/bootstrap-multiselect.css" />
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class="portlet">
                <h3 class="portlet-title"> <u>Radiant Customer&acute;s Pickup / Delivery Point Master</u> </h3>
                <!--<div style="width:50%; float:left;">
        	<?php
          $active = $inactive = $lost = $ready = $temp_inactive = 0;
          $sql_shop_details = mysql_query("SELECT point_type, COUNT(shop_id) AS active FROM shop_details WHERE  status='Y' GROUP BY point_type");
          while ($res_shop_details = mysql_fetch_assoc($sql_shop_details)) {
            if ($res_shop_details['point_type'] == 'Active') {
              $active = $res_shop_details['active'];
            }
            if ($res_shop_details['point_type'] == 'Inactive') {
              $inactive = $res_shop_details['active'];
            }
            if ($res_shop_details['point_type'] == 'Lost') {
              $lost = $res_shop_details['active'];
            }
            if ($res_shop_details['point_type'] == 'Ready') {
              $ready = $res_shop_details['active'];
            }
          }
          /*$sql_active = mysql_query("SELECT COUNT(shop_id) AS active FROM shop_details WHERE point_type='Active' AND status='Y'");
				$res_active = mysql_fetch_assoc($sql_active);
							
				
				$sql_ready = mysql_query("SELECT COUNT(shop_id) AS ready FROM shop_details WHERE point_type='Ready' AND status='Y'");
				$res_ready = mysql_fetch_assoc($sql_ready);
				
				$sql_inactive = mysql_query("SELECT COUNT(shop_id) AS inactive FROM shop_details WHERE point_type='Inactive' AND status='Y'");
				$res_inactive = mysql_fetch_assoc($sql_inactive);
				
				$sql_lost = mysql_query("SELECT COUNT(shop_id) AS lost FROM shop_details WHERE point_type='Lost' AND status='Y'");
				$res_lost = mysql_fetch_assoc($sql_lost);
				
				$sql_temp_inactive = mysql_query("SELECT COUNT(shop_id) AS temp_inactive FROM shop_details WHERE point_type='Temp Inactive' AND status='Y'");
				$res_temp_inactive = mysql_fetch_assoc($sql_temp_inactive);*/
          ?>
            <table cellpadding="5" cellspacing="5" border="1" class="table table-striped table-bordered table-hover" style="width:500px;" width="200">
            <thead>
            	<tr>
                	<th>Active Points</th><th>Ready Points</th><th>Inactive Points</th><th>Lost Points</th><th>Temp Inactive Points</th>
                </tr>
                </thead>
                <tbody>
                <tr><td><?php echo $active; ?></td><td><?php echo $ready; ?></td><td><?php echo $inactive; ?></td><td><?php echo $lost; ?></td><td><?php echo $temp_inactive; ?></td></tr>
                </tbody>
            </table>
      		</div>
            <div style="width:50%; float:left;">
            <?php
            $cash_pickup = $cash_delivery = $boths = $cheque_pickup = $mbc  = 0;
            $sql_pick = mysql_query("SELECT service_type, COUNT(shop_id) AS type_counts FROM shop_details WHERE  point_type='Active' AND status='Y' group by service_type");
            while ($res_pick = mysql_fetch_assoc($sql_pick)) {
              if ($res_pick['service_type'] == 'Cash Pickup') {
                $cash_pickup = $res_pick['type_counts'];
              }
              if ($res_pick['service_type'] == 'Cash Delivery') {
                $cash_delivery = $res_pick['type_counts'];
              }
              if ($res_pick['service_type'] == 'Both') {
                $boths = $res_pick['type_counts'];
              }
              if ($res_pick['service_type'] == 'Cheque Pickup') {
                $cheque_pickup = $res_pick['type_counts'];
              }
              if ($res_pick['service_type'] == 'MBC') {
                $mbc = $res_pick['type_counts'];
              }
            }
            /*$sql_cash_pickup = mysql_query("SELECT COUNT(shop_id) AS cash_pickup FROM shop_details WHERE service_type='Cash Pickup' AND point_type='Active' AND status='Y'");
			$res_cash_pickup = mysql_fetch_assoc($sql_cash_pickup);
			
			$sql_cash_delivery = mysql_query("SELECT COUNT(shop_id) AS cash_delivery FROM shop_details WHERE service_type='Cash Delivery' AND point_type='Active' AND status='Y'");
			$res_cash_delivery = mysql_fetch_assoc($sql_cash_delivery);
			
			$sql_both = mysql_query("SELECT COUNT(shop_id) AS boths FROM shop_details WHERE service_type='Both' AND point_type='Active' AND status='Y'");
			$res_both = mysql_fetch_assoc($sql_both);
			
			$sql_cheque_pickup = mysql_query("SELECT COUNT(shop_id) AS cheque_pickup FROM shop_details WHERE service_type='Cheque Pickup' AND point_type='Active' AND status='Y'");
			$res_cheque_pickup = mysql_fetch_assoc($sql_cheque_pickup);
			
			$sql_mbc = mysql_query("SELECT COUNT(shop_id) AS mbc FROM shop_details WHERE service_type='MBC' AND point_type='Active' AND status='Y'");
			$res_mbc = mysql_fetch_assoc($sql_mbc);*/
            ?>
            <table cellpadding="5" cellspacing="5" border="1" class="table table-striped table-bordered table-hover" style="width:500px;" width="200">
            <thead>
            	<tr>
                	<th>Cash Pickup</th><th>Cash Delivery</th><th>Both</th><th>Cheque Pickup</th><th>MBC</th>
                </tr>
                </thead>
                <tbody>
                <tr><td><?php echo $cash_pickup; ?></td><td><?php echo $cash_delivery; ?></td><td><?php echo $boths; ?></td><td><?php echo $cheque_pickup; ?></td><td><?php echo $mbc; ?></td></tr>
                </tbody>
            </table>
       		</div>-->
                <div class="clear"></div>
                <div id="load_lod_shop1" style="display:none;float:left; width:100%; text-align:center; padding:3px;"
                    class="alert alert-danger"></div>

                <div id="load_lod_shop" style="display:none;float:left; width:100%;" class="alert"></div>

                <?php if ($nav != '') { ?>
                <div class="message_cu">
                    <div style="padding: 7px;" class="alert <?php if ($nav == 2||$nav==5) {
                                                      echo 'alert-danger';
                                                    } else {
                                                      echo 'alert-success';
                                                    } ?>" align="center"> <a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
                            <?php
                $status_cu = array(1 => 'New Point Details Successfully Added', 2 => 'Sorry, Please Try Again', 3 => 'Select Point Details Updated', 4 => 'Given Point Details Already Avilable. Please Search And Update',5=>'Please upload the file same as sample file');
                echo $status_cu[$nav];
                ?>
                        </b> </div>
                </div>
                <?php }

        $sel_day = array();
        if ($id != '') {
          $sql_shop = mysql_query("SELECT * FROM shop_details WHERE shop_id='" . $id . "'");
          $res_shop = mysql_fetch_object($sql_shop);
          $sel_beat = $res_shop->selected_beat_days;
          $sel_day = explode(",", $sel_beat);
        }
        ?>
                <div class="clear"></div>
                <form id="demo-validation" action="<?php echo 'CommonReference/add_details.php?pid=' . $pid; ?>" method="post"
                    data-validate="parsley" class="form parsley-form">
                    <div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Customer Name
                            </label>
                            <select id="cust_id" name="cust_id" class="form-control parsley-validated chosen-select"
                                tabindex="1">
                                <option value="">Select Customer Name</option>
                                <?php
                $unique_customer_name='';
                $sql_cust = mysql_query("SELECT a.client_name, b.cust_id, b.cust_name,b.unique_cust_name FROM client_details AS a JOIN cust_details AS b ON a.client_id=b.client_id WHERE b.status='Y' AND b.status='Y' ORDER BY a.client_name, b.cust_name");
                while ($res_cus = mysql_fetch_object($sql_cust)) {
                  echo '<option value="' . $res_cus->cust_id . '" ';
                  if ($res_shop->cust_id == $res_cus->cust_id) {
                    echo 'selected="selected"';
                    $unique_customer_name=$res_cus->unique_cust_name;
                  }
                  echo '>' . $res_cus->client_name . ' - ' . $res_cus->cust_name . '</option>';
                }
                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Division Name
                            </label>
                            <select id="cust_div" name="cust_div" class="form-control parsley-validated chosen-select"
                                tabindex="2">
                                <option value="NIL">NIL</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point ID
                            </label>
                            <input type="text" id="shop_id1" name="shop_id1" class="form-control parsley-validated"
                                value="<?php if ($id != '') echo $id;
                                                                                                              else echo getshop_id($readConnection); ?>"
                                placeholder="Customer Name" tabindex="3" readonly>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point State Name
                            </label>
                            <select id="state" name="state" class="form-control parsley-validated chosen-select"
                                onchange="load_state()" tabindex="4">
                                <option value="">Select Point State Name</option>
                                <?php
                $sqls2 = mysql_query("SELECT DISTINCT(a.state) FROM location_master AS a INNER JOIN radiant_location AS b ON a.loc_id=b.location_id
 WHERE b.region_id IN (" . $login_regoin . ") AND a.`status`='Y' AND b.`status`='Y'  GROUP BY state");
                while ($res2 = mysql_fetch_object($sqls2)) {
                  echo '<option value="' . $res2->state . '" ';
                  if ($state == $res2->state) {
                    echo 'selected="selected"';
                  }
                  echo '>' . $res2->state . '</option>';
                }
                ?>
                            </select>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point PinCode
                            </label>
                            <input type="text" id="pincode" name="pincode" class="form-control parsley-validated"
                                placeholder="Point PinCode" value="<?php echo $res_shop->pincode; ?>" tabindex="6">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Location
                            </label>
                            <select id="location" name="location" class="form-control parsley-validated chosen-select"
                                onchange="load_city()" tabindex="6">
                                <option value="">Select Point Location</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Radiant LOI ID
                            </label>
                            <input type="text" id="loi_id" name="loi_id" class="form-control parsley-validated"
                                placeholder="Radiant LOI ID" value="<?php echo $res_shop->loi_id; ?>" tabindex="7">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;*</label>
                                LOI Date
                            </label>
                            <input type="text" id="popupDatepicker" autocomplete="off" name="loi_date"
                                class="form-control parsley-validated" placeholder="LOI Date"
                                value="<?php if ($res_shop->loi_date != '0000-00-00' && $res_shop->loi_date != '') {
                                                                                                                                                                echo date('d-m-Y', strtotime($res_shop->loi_date));
                                                                                                                                                              } ?>" tabindex="8">
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Customer Code
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="cust_code"
                                name="cust_code" tabindex="9"
                                placeholder="Customer Code"><?php echo $res_shop->customer_code; ?></textarea>

                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Customer&acute;s Point Code
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="shop_code"
                                name="shop_code" tabindex="10"
                                placeholder="Customer&acute;s Point Code"><?php echo $res_shop->shop_code; ?></textarea>

                            <span class="load_errorsss" style="color:red; display:none;">Please enter at least 10
                                characters.</span>

                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Location Code
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="loc_code"
                                name="loc_code" tabindex="11"
                                placeholder="Point Location Code"><?php echo $res_shop->loc_code; ?></textarea>


                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Name
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="shop_name"
                                name="shop_name" tabindex="12"
                                placeholder="Point Name"><?php echo $res_shop->shop_name; ?></textarea>

                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Address
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="address"
                                name="address" tabindex="13"
                                placeholder="Point Address"><?php echo $res_shop->address; ?></textarea>
                        </div>



                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Hierarchy Code
                            </label>
                            <input type="text" id="hier_code" name="hier_code" class="form-control parsley-validated"
                                placeholder="Hierarchy Code" value="<?php echo $res_shop->hierarchy_code; ?>"
                                tabindex="14">
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Sub Customer Code
                            </label>
                            <input type="text" id="subcust_code" name="subcust_code"
                                class="form-control parsley-validated" placeholder="Sub Customer Code"
                                value="<?php echo $res_shop->subcustomer_code; ?>" tabindex="15">
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Sol ID
                            </label>
                            <input type="text" id="sol_id" name="sol_id" class="form-control parsley-validated"
                                placeholder="Sol ID" value="<?php echo $res_shop->sol_id; ?>" tabindex="16">
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Division Code
                            </label>
                            <input type="text" id="div_code" name="div_code" class="form-control parsley-validated"
                                placeholder="Division Code" value="<?php echo $res_shop->div_code; ?>" tabindex="17">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Phone No
                            </label>
                            <input type="text" id="phone" name="phone" class="form-control parsley-validated"
                                placeholder="Point Phone No" value="<?php echo $res_shop->phone; ?>" tabindex="18">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Contact Name
                            </label>
                            <input type="text" id="contact_name" name="contact_name"
                                class="form-control parsley-validated" placeholder="Contact Name"
                                value="<?php echo $res_shop->contact_name; ?>" tabindex="19">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Contact Mobile No
                            </label>
                            <input type="text" id="contact_no" name="contact_no" class="form-control parsley-validated"
                                placeholder="Contact Mobile No" value="<?php echo $res_shop->contact_no; ?>"
                                tabindex="20">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Email ID
                            </label>
                            <input type="text" id="email_id" name="email_id" class="form-control parsley-validated"
                                placeholder="Email ID" value="<?php echo $res_shop->email_id; ?>" tabindex="21">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Service Type
                            </label>
                            <select id="service_type" name="service_type"
                                class="form-control parsley-validated chosen-select" onchange="change_comp(this.value)"
                                tabindex="22">
                                <option value="">Select Service Type</option>
                                <option value="Cash Pickup"
                                    <?php if ($res_shop->service_type == "Cash Pickup") echo "Selected='Selected'"; ?>>
                                    Cash Pickup</option>
                                <option value="Evening Pickup"
                                    <?php if ($res_shop->service_type == "Evening Pickup") echo "Selected='Selected'"; ?>>
                                    Evening Pickup</option>
                                <option value="Cash Delivery"
                                    <?php if ($res_shop->service_type == "Cash Delivery") echo "Selected='Selected'"; ?>>
                                    Cash Delivery</option>
                                <option value="Both"
                                    <?php if ($res_shop->service_type == "Both") echo "Selected='Selected'"; ?>>Both
                                </option>
                                <option value="Cheque Pickup"
                                    <?php if ($res_shop->service_type == "Cheque Pickup") echo "Selected='Selected'"; ?>>
                                    Cheque Pickup</option>
                                <option value="DD Delivery"
                                    <?php if ($res_shop->service_type == "DD Delivery") echo "Selected='Selected'"; ?>>
                                    DD Delivery</option>
                            </select>
                            <br />
                            <div id="mbc_types" style=" <?php if ($res_shop->service_type != "MBC") {
                                            echo 'display:none;';
                                          } ?>">
                                <label class="compulsory">&nbsp;</label>
                                Cash

                                </label>
                                <input type="checkbox" data-mincheck="2" name="mbc_type" <?php if ($res_shop->mbc_type == '1') {
                                                                            echo 'checked';
                                                                          } ?> class="parsley-validated" value="1"
                                    tabindex="22">
                                <label class="compulsory">&nbsp;</label>
                                Cheque
                                </label>
                                <input type="checkbox" data-mincheck="2" name="mbc_type" class="parsley-validated" <?php if ($res_shop->mbc_type == '2') {
                                                                                                      echo 'checked';
                                                                                                    } ?> value="2"
                                    tabindex="22">
                                <label class="compulsory">&nbsp;</label>
                                Attendance
                                </label>
                                <input type="checkbox" data-mincheck="2" name="mbc_type" class="parsley-validated" <?php if ($res_shop->mbc_type == '3') {
                                                                                                      echo 'checked';
                                                                                                    } ?> value="3"
                                    tabindex="22">
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory"><span id="process_comp">*</span></label>
                                Process
                            </label>
                            <select class="form-control parsley-validated chosen-select" id="process" name="process">
                                <option <?php if ($res_shop->process == "") echo "Selected='Selected'"; ?> value="">
                                    Select Process</option>
                                <option <?php if ($res_shop->process == "CCV") echo "Selected='Selected'"; ?>
                                    value="CCV">CCV</option>
                                <option <?php if ($res_shop->process == "Seal Bag") echo "Selected='Selected'"; ?>
                                    value="Seal Bag">Seal Bag</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Cash Max. Limit
                            </label>
                            <input type="text" id="cash_limit" name="cash_limit" class="form-control parsley-validated"
                                placeholder="Cash Max. Limit" value="<?php echo $res_shop->cash_limit; ?>"
                                tabindex="22">
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Deposit Bank Type
                            </label>
                            <select id="bank_type" name="bank_type" class="form-control parsley-validated chosen-select"
                                tabindex="23">
                                <option value="">Select Deposit Bank Type</option>
                                <option <?php if ($res_shop->dep_bank == "Burial") echo "Selected='Selected'"; ?>
                                    value="Burial">Burial</option>
                                <option <?php if ($res_shop->dep_bank == "Partner Bank") echo "Selected='Selected'"; ?>
                                    value="Partner Bank">Partner Bank</option>
                                <option <?php if ($res_shop->dep_bank == "Client Bank") echo "Selected='Selected'"; ?>
                                    value="Client Bank">Client Bank</option>
                                <option <?php if ($res_shop->dep_bank == "Vault") echo "Selected='Selected'"; ?>
                                    value="Vault">Vault</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Account No.
                            </label>
                            <input type="checkbox" data-mincheck="2" name="uacc_no" class="parsley-validated" value="Y"
                                tabindex="24">
                            <input type="text" id="account_no" name="account_no" class="form-control parsley-validated"
                                placeholder="Account No." value="<?php echo $res_shop->acc_id; ?>" tabindex="25">
                        </div>
                        <input type="hidden" name="acc_id" id="acc_id" value="<?php if ($id != '') {
                                                                    echo $res_shop->acc_id;
                                                                  } else {
                                                                    echo '0';
                                                                  } ?>" />
                        <div class="form-group  col-sm-1"><a id="update_122988" data-toggle="modal" href="#basicModal"
                                class="btn update_locid">
                                <button type="submit" class="btn btn-danger search_btn" name="submit" value="submit"
                                    tabindex="26" style="margin-top:25px;">Get IDs</button>
                            </a></div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Pickup Type
                            </label>
                            <select id="pickup_type" name="pickup_type"
                                class="form-control parsley-validated chosen-select" tabindex="27">
                                <option value="">Select Pickup Type</option>
                                <option value="Request"
                                    <?php if ($res_shop->pickup_type == "Request") echo "Selected='Selected'"; ?>>
                                    Request</option>
                                <option value="Beat"
                                    <?php if ($res_shop->pickup_type == "Beat") echo "Selected='Selected'"; ?>>Beat
                                </option>
                                <option value="Evening Beat"
                                    <?php if ($res_shop->pickup_type == "Evening Beat") echo "Selected='Selected'"; ?>>
                                    Evening Beat</option>
                                <option value="Selected Beat"
                                    <?php if ($res_shop->pickup_type == "Selected Beat") echo "Selected='Selected'"; ?>>
                                    Selected Beat</option>

                            </select>

                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Feasibility Check
                            </label>

                            <select id="feasibility" name="feasibility"
                                class="form-control parsley-validated chosen-select" tabindex="28">
                                <option value="">Select Feasibility Check</option>
                                <option value="NIL"
                                    <?php if ($res_shop->feasibility == "NIL") echo "Selected='Selected'"; ?>>NIL
                                </option>
                                <option value="D+0"
                                    <?php if ($res_shop->feasibility == "D+0") echo "Selected='Selected'"; ?>>D+0
                                </option>
                                <option value="D+1"
                                    <?php if ($res_shop->feasibility == "D+1") echo "Selected='Selected'"; ?>>D+1
                                </option>
                                <option value="D+2"
                                    <?php if ($res_shop->feasibility == "D+2") echo "Selected='Selected'"; ?>>D+2
                                </option>
                                <option value="D+3"
                                    <?php if ($res_shop->feasibility == "D+3") echo "Selected='Selected'"; ?>>D+3
                                </option>
                                <option value="D+4"
                                    <?php if ($res_shop->feasibility == "D+4") echo "Selected='Selected'"; ?>>D+4
                                </option>
                                <option value="D+5"
                                    <?php if ($res_shop->feasibility == "D+5") echo "Selected='Selected'"; ?>>D+5
                                </option>
                            </select>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Point Type
                            </label>
                            <select id="point_type" name="point_type"
                                class="form-control parsley-validated chosen-select" tabindex="29">
                                <option value="">Select Point Type</option>
                                <option value="Active"
                                    <?php if ($res_shop->point_type == "Active") echo "Selected='Selected'"; ?>>Active
                                </option>
                                <option value="Ready"
                                    <?php if ($res_shop->point_type == "Ready") echo "Selected='Selected'"; ?>>Ready
                                </option>
                                <option value="Inactive"
                                    <?php if ($res_shop->point_type == "Inactive") echo "Selected='Selected'"; ?>>
                                    Inactive</option>
                                <option value="Temp Inactive"
                                    <?php if ($res_shop->point_type == "Temp Inactive") echo "Selected='Selected'"; ?>>
                                    Temp Inactive</option>
                                <option value="Lost"
                                    <?php if ($res_shop->point_type == "Lost") echo "Selected='Selected'"; ?>>Lost
                                </option>
                            </select>

                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Remarks
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="remarks"
                                placeholder="Remarks" name="remarks"
                                tabindex="30"><?php echo $res_shop->remarks; ?></textarea>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Point Activation Date
                            </label>
                            <input readonly="readonly" type="text" id="popupDatepicker1" name="sact_date"
                                class="form-control parsley-validated" placeholder="Point Activation Date"
                                value="<?php if ($res_shop->sact_date != '0000-00-00' && $res_shop->sact_date != '') {
                                                                                                                                                                                echo date('d-m-Y', strtotime($res_shop->sact_date));
                                                                                                                                                                              } ?>" tabindex="31">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Point Deactivation Date
                            </label>
                            <input readonly="readonly" type="text" id="popupDatepicker2" name="sdeact_date"
                                class="form-control parsley-validated" placeholder="Point Deactivation Date"
                                value="<?php if ($res_shop->sdeact_date != '0000-00-00' && $res_shop->sdeact_date != '') {
                                                                                                                                                                                    echo date('d-m-Y', strtotime($res_shop->sdeact_date));
                                                                                                                                                                                  } ?>"
                                tabindex="32">
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                Radiant Served Location
                            </label>

                            <select id="served_location" name="served_location"
                                class="form-control parsley-validated chosen-select" tabindex="33">
                                <option value="">Select Radiant Served Location</option>
                                <?php
                /* $sqls4 = mysql_query("SELECT b.loc_id, b.location FROM radiant_location AS a INNER JOIN location_master AS b ON a.location_id=b.loc_id WHERE a.state_name='".$state."' AND a.status='Y' AND b.status='Y'");
                     while($qus4 = mysql_fetch_object($sqls4)) {
                        echo '<option value="'.$qus4->loc_id.'" ';
                        if($res_shop->served_location==$qus4->loc_id) { echo 'selected="selected"'; }
                        echo '>'.$qus4->location.'</option>'; 
                     }*/
                ?>
                            </select>
                        </div>
                        <?php

            if ($per == "Admin" || $per == "Manager" || $user == 'gowri' || $user == 'vijayraj' || $user == 'Rajmohan' || $user == 'mukeshkumar' || $user == 'Prema') {

            ?>

                        <div class="form-group col-sm-2">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Pin Number
                            </label>
                            <?php if ($user == 'admin' || $user == 'surya' || $user == 'naveensv' || $user == 'thirupurasundarik') {
                  $readonly = "";
                } else {
                  $readonly = " readonly='readonly'";
                } ?>
                            <input type="text" id="pin_number" name="pin_number" class="form-control parsley-validated"
                                <?php echo $readonly; ?> placeholder="Pin Number" value="<?php echo $res_shop->cpin; ?>"
                                tabindex="34">
                        </div>
                        <?php if ($user == 'admin' || $user == 'surya' || $user == 'naveensv' || $user == 'thirupurasundarik') { ?>
                        <div class="form-group  col-sm-1"><button type="button" class="btn btn-secondary demo-element"
                                onclick="pin_gen()" tabindex="34" style="margin-top:25px; padding:2px;">Generate
                                Pin</button></div>
                        <?php  }
            } ?>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Inactive Date
                            </label>
                            <input readonly="readonly" type="text" id="popupDatepicker4" name="inactive_date"
                                class="form-control parsley-validated" placeholder="Inactive Date"
                                value="<?php if ($res_shop->inactive_date != '0000-00-00' && $res_shop->inactive_date != '') {
                                                                                                                                                                            echo date('d-m-Y', strtotime($res_shop->inactive_date));
                                                                                                                                                                          } ?>"
                                tabindex="8">
                        </div>

                        <!--  OTHER PICKUP -->
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <!-- <label class="compulsory">*</label> -->
                                Other Pickup
                            </label>
                            <select id="other_pickup" name="other_pickup"
                                class="form-control parsley-validated chosen-select" tabindex="35">
                                <option value="">Select</option>
                                <option
                                    <?php if ($res_shop->other_pickup == "Coin Pickup") echo "Selected='Selected'"; ?>
                                    value="Coin Pickup">Coin Pickup</option>
                                <option <?php if ($res_shop->other_pickup == "Merged") echo "Selected='Selected'"; ?>
                                    value="Merged">Merged</option>
                                <option <?php if ($res_shop->other_pickup == "D+2") echo "Selected='Selected'"; ?>
                                    value="D+2">D+2</option>
                            </select>
                        </div>

                        <!--- NEW --->
                        <!--  Multi Select PICKUP -->
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <!-- <label class="compulsory">*</label> -->
                                Seleted Beat Pickup Days
                            </label>
                            <select multiple="multiple" class="multisel checks checks_driver" name="seleted_beat[]"
                                id="seleted_beat" style="width:230px;height:90px" tabindex="36">
                                <option value="Mon" <?php if (in_array("Mon", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Monday</option>
                                <option value="Tue" <?php if (in_array("Tue", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Tuesday</option>
                                <option value="Wed" <?php if (in_array("Wed", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Wednesday</option>
                                <option value="Thu" <?php if (in_array("Thu", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Thursday</option>
                                <option value="Fri" <?php if (in_array("Fri", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Friday</option>
                                <option value="Sat" <?php if (in_array("Sat", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Saturday</option>
                                <option value="Sun" <?php if (in_array("Sun", $sel_day)) echo "Selected='Selected'"; ?>>
                                    Sunday</option>
                            </select>
                            <!--- Multi Select PICKUP End  --->
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory"></label>
                                City Name
                            </label>
                            <select id="location2" name="location2" class="form-control parsley-validated chosen-select"
                                tabindex="37">
                                <?php
                if ($id != '') {
                  $sh_loc = mysql_query("SELECT location_id,location_name FROM shop_city_loc where status ='Y' ");
                  if (mysql_num_rows($sh_loc) > 0) {
                    echo '<option value="">Select</option>';

                    while ($shop_loc = mysql_fetch_object($sh_loc)) {
                      $sel = '';
                      if ($shop_loc->location_id == $res_shop->city_name) {
                        $sel = 'selected="selected"';
                      }
                      echo '<option value="' . $shop_loc->location_id . '" ' . $sel . ' >' . $shop_loc->location_name . '</option>';
                    }
                  } else {
                    echo '<option value="">No Records Found</option>';
                  }
                } else {
                  echo '<option value="">No Records Found</option>';
                }
                ?>
                            </select>
                        </div>


                        <!--rbi compliance -->
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <!-- <label class="compulsory">*</label> -->
                                RBI Compliance
                            </label>
                            <select id="rbicompliance" name="rbicompliance"
                                class="form-control parsley-validated chosen-select" tabindex="38">
                                <option value="">Select RBICompliance</option>
                                <option <?php if ($res_shop->rbicompliance == "RBI Compliance")
                          echo "Selected ='Selected'"; ?> value="RBI Compliance">RBI Compliance</option>
                                <!--<option value="RBI Compliance">RBI Compliance</option>-->
                            </select>
                        </div>
                        <!-- rbi compliance -->
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Disposal Location
                            </label>
                            <input type="text" id="disposal_location" name="disposal_location"
                                class="form-control parsley-validated" placeholder="Disposal Location"
                                value="<?php echo $res_shop->disposal_location; ?>" tabindex="39">
                        </div>
                        <div class="clear"></div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                Choose TIER
                            </label>
                            <select name="tier" id="tier" class="form-control parsley-validated chosen-select">
                                <option value="">CHOOSE TIER</option>
                                <option <?php if ($res_shop->tier == "Tier I") echo "Selected='Selected'"; ?>
                                    value="Tier I">TIER I</option>
                                <option <?php if ($res_shop->tier == "Tier II") echo "Selected='Selected'"; ?>
                                    value="Tier II">TIER II</option>
                                <option <?php if ($res_shop->tier == "Tier III") echo "Selected='Selected'"; ?>
                                    value="Tier III">TIER III</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">*</label>
                                QR-Status
                            </label>
                            <select id="qr_status" name="qr_status" class="form-control parsley-validated chosen-select"
                                tabindex="38">
                                <option value="">Select QR-Status</option>
                                <option <?php if ($res_shop->qr_status == "No") echo "Selected='Selected'"; ?>
                                    value="No">Manual</option>
                                <option <?php if ($res_shop->qr_status == "Radiant") echo "Selected='Selected'"; ?>
                                    value="Radiant">Radiant</option>
                                <option <?php if ($res_shop->qr_status == "ICICI") echo "Selected='Selected'"; ?>
                                    value="ICICI">ICICI</option>
                                <option <?php if ($res_shop->qr_status == "Reliance") echo "Selected='Selected'"; ?>
                                    value="Reliance">Reliance</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                QR JSON
                            </label>
                            <textarea class="form-control parsley-validated" rows="2" cols="10" id="qr_json"
                                name="qr_json" tabindex="13"
                                placeholder="Enter JSON"><?php echo $res_shop->qr_json; ?></textarea>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <!-- <label class="compulsory">*</label> -->
                                Slip upload option
                            </label>
                            <select class="form-control parsley-validated chosen-select" id="show_slip"
                                name="show_slip">
                                <option <?php if ($res_shop->show_slip == "") echo "Selected='Selected'"; ?> value="">
                                    Select Slip upload option</option>
                                <option <?php if ($res_shop->show_slip == "Y") echo "Selected='Selected'"; ?> value="Y">
                                    YES</option>
                                <option <?php if ($res_shop->show_slip == "N") echo "Selected='Selected'"; ?> value="N">
                                    NO</option>
                            </select>
                        </div>

                        <!--start latitude longitude-->
                        <div class="clear"></div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Latitude
                            </label>
                            <input type="text" id="latitude" name="latitude" class="form-control parsley-validated"
                                placeholder="Latitude" value="<?php echo $res_shop->latitude; ?>" tabindex="39">
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Longitude
                            </label>
                            <input type="text" id="longitude" name="longitude" class="form-control parsley-validated"
                                placeholder="Longitude" value="<?php echo $res_shop->longitude; ?>" tabindex="39">
                        </div>

                        <div class="form-group col-sm-3">
                            <label for="name">Lat - Long Flag</label>
                            <select id="latlongflag" name="latlongflag"
                                class="form-control parsley-validated chosen-select" tabindex="38">
                                <option <?php if ($res_shop->latlongflag == "0") echo "Selected='Selected'"; ?>
                                    value="0">NO</option>
                                <option <?php if ($res_shop->latlongflag == "1") echo "Selected='Selected'"; ?>
                                    value="1">YES</option>
                            </select>
                        </div>
                        <!--end latitude longitude-->


                        <!----   Unique Customer Name Start  -->

                        <div class="form-group col-sm-3">
                            <label for="name">
                                <!-- <label class="compulsory">*</label> -->
                                Unique Customer Name</label>
                            <textarea id="centraliz_cust_name" name="centraliz_cust_name"
                                class="form-control parsley-validated" placeholder="Unique Customer Name" tabindex="40"
                                readonly><?php echo $unique_customer_name; ?></textarea>
                        </div>

                        <!---- Unique Customer Name End ------>

                        <div class="form-group col-sm-3">
              <label for="name">
                <label class="compulsory">*</label>
                Vendor Name
              </label>
              <select id="vendor_id" name="vendor_id" class="form-control parsley-validated chosen-select" tabindex="38">
                <option value="">Select</option>
				
				<?php
					 $sql_vend = mysql_query("select vendor_name,vendor_id from vendor_details where status='Y'");
					while ($res_vend = mysql_fetch_array($sql_vend)) {
					
				    $sql_user = mysql_query("select * from login where user_name='".$user."' and status='Allowed'");
					$res_user = mysql_fetch_array($sql_user);
				 if($_GET['id']=='' || $res_shop->vendor_id=='') { ?>
					
					 <option <?php if (19 == $res_vend['vendor_id'] ) echo "Selected='Selected'"; ?> value="<?php echo $res_vend['vendor_id']; ?>"><?php echo $res_vend['vendor_name']; ?></option>
				

<?php					
					 } 
else {					 
						
						
				?>
                <option <?php if ($res_shop->vendor_id == $res_vend['vendor_id'] ) echo "Selected='Selected'"; ?> value="<?php echo $res_vend['vendor_id']; ?>"><?php echo $res_vend['vendor_name']; ?></option>
				
				<?php 
					} } ?>
              </select>
            </div>
                        <?php if ($res_shop->approve_by != '') {
            ?>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Approved By
                            </label>
                            <input type="text" class="form-control parsley-validated" id="approve_by" name="approve_by"
                                value="<?php echo $res_shop->approve_by; ?>" readonly>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="name">
                                <label class="compulsory">&nbsp;</label>
                                Approved Date
                            </label>
                            <input type="text" class="form-control parsley-validated" id="approve_by" name="approve_by"
                                value="<?php echo $res_shop->approve_date; ?>" readonly>
                        </div>
                        <?php } ?>
                        <div class="clear"></div>

                        <div class="form-group col-sm-3">
                            <?php

              if ($id != '') {
              ?>
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <?php
              }
              //echo $per;
              if ($per == "Admin" || $per == "Manager" || $user == 'gowri' || $user == 'vijayraj' || $user == 'Rajmohan' || $user == 'mukeshkumar' || $user == 'Prema') { ?>
                            <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn"
                                tabindex="34"><?php if ($id != '') echo 'Update Shop Details';
                                                                                                                else  echo 'Save Shop Details'; ?></button>
                            <button type="button" name="submit1" id="submit1" class="btn btn-danger search_btn"
                                style="display:none;"
                                tabindex="34"><?php if ($id != '') echo 'Update Shop Details';
                                                                                                                                        else  echo 'Save Shop Details'; ?></button>


                            <?php
                if ($per == 'Admin' || $user == 'surya') {
                  echo '<br />' . $res_shop->update_by . ' - ' . $res_shop->update_date . ' ' . $res_shop->upd_time;
                }
              }    ?>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.portlet-body -->
            <!--id="view_mod"-->
            <div id="basicModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="basicModal_view"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="save_loa" data-dismiss="modal">Select
                                Account Number</button>
                        </div>
                        <!-- /.modal-footer -->

                    </div>
                    <!-- /.modal-content -->

                </div>
                <!-- /.modal-dialog -->

            </div>
            <div class="clear"></div>



            <?php
      if (!empty($other_permissions)) {
        $SpecialPrivilege = explode(',', $other_permissions);
        if (in_array('PincodeReport', $SpecialPrivilege)) {
      ?>
            <div class="portlet">
                <h3 class="portlet-title"> <u>Download Pincode Report</u> </h3>
                <form action="pincode.php" method="post" enctype="multipart/form-data" data-validate="parsley"
                    class="form parsley-form">
                    <div class="form-group col-sm-3">
                        <label>Select Date</label>
                        <input type="text" class="form-control parsley-validated to_control_report" name="date"
                            id='popupDatepicker5' placeholder='Select Date' />
                    </div>
                    <div class="form-group  col-sm-2" align="center">
                        <button type="submit" name="submit" class="btn btn-danger search_btn to_disable_btn"
                            style="margin-top: 23px;">Export Pincode Report</button>
                    </div>
                </form>
                <!-- <a href="pincode.php"><button type="button" class="btn btn-danger">Pincode Report</button></a> -->
            </div>
            <div class="clear"></div>
            <?php
        }
      }
      ?>


            <?php
      if ($per == "Admin" && $id == '') { ?>
            <br />
            <div class="portlet">
                <h3 class="portlet-title"> <u>Bulk Upload</u> </h3>

                <form enctype="multipart/form-data" method="post" action="CommonReference/add_details.php?pid=m_shop1"
                    data-validate="parsley" class="form parsley-form" id="uploadForm">
                    <div class="form-group col-sm-3">
                        <label for="name">
                            <label class="compulsory">&nbsp;</label>
                            Upload Your Excel
                        </label>
                        <input type="file" name="files" id="files" />
                        <div class="to_style_samplefile">
                            <a href="format/Shop_Detials.xls">Sample File</a>
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <button type="submit" name="submit" class="btn btn-danger search_btn to_disable_upload_btn"
                            style="margin-top: 23px;">Upload Shop Details</button>
                    </div>
                </form>

            </div>
            <?php } ?>
            <div class="clear"></div>
            <div class="portlet">
                <h3 class="portlet-title"> <u>Customize Search</u> </h3>
                <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg">
                    <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
                    <b>Selected Point Details Deleted</b> </div>
                <form id="demo-validation" action="#" data-validate="parsley" class="form parsley-form">
                    <div class="form-group col-sm-2">
                        <label for="name"><label class="compulsory to_hidesrch">*</label>Search Criteria </label>
                        <select id="search" name="search" class="form-control parsley-validated chosen-select"
                            data-required="true">
                            <option value="">Select Option</option>
                            <option value="All">All</option>
                            <option value="Shop ID">Point ID</option>
                            <option value="Shop Code">Point Location Code</option>
                            <option value="Shop Name">Point Name</option>
                            <option value="Point Address">Point Address</option>
                            <option value="point type">Point Type</option>
                            <option value="Customer Name">Customer Name</option>
                            <option value="Customer Code">Customer Code</option>
                            <option value="Customer Point Code">Customer Point Code</option>
                            <option value="Client Name">Client Name</option>
                            <option value="Point Location">Point Location</option>
                            <option value="Region Name">Branch Name</option>
                            <option value="unmap">Unmapped Shops</option>
                            <option value="sol id">Sol Id</option>
                            <option value="qr_status">QR-Status</option>
                            <option value="pincode">Pincode</option>

                        </select>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="name"><label class="compulsory to_hidekey">*</label>Enter Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="form-control parsley-validated"
                            data-required="true" placeholder="Enter Keyword">
                    </div>
                    <div id='to_append_pagination_data' class="form-group col-sm-2">

                    <label for="name"><label class="compulsory to_hidesrch">*</label>Select Limit </label>
                        <select id="Select_Limit" name="Select_Limit" class="form-control parsley-validated chosen-select"
                            data-required="true">
                            <!-- <option value="">Select Limit</option>
                            <option value="0,10">0 to 10</option>
                            <option value="10,20"> 10 to 20</option>
                            <option value="20,30"> 20 to 30</option> -->
                            <?php
                             $sql1="SELECT shop_id from shop_details where status='Y'";
        
                             $qud1=mysql_query($sql1);
                             $total_rec = mysql_num_rows($qud1);
                             $totalRecords = $total_rec; 
                             $halves = 10; 
                             $interval = ceil($totalRecords / $halves);
                            for ($i = 0; $i < $halves; $i++) {
                                $start = $i * $interval;
                                $end = min(($start + $interval), $totalRecords);

                               
                                echo '<option value="' . $start . ',' . $end . '">';
                                echo $start . ' to ' . $end;
                                echo '</option>';
                            }

                            echo '</select>';
                            ?>
                            <!-- </select> -->
                        
                    </div>
                    <div class="form-group  col-sm-3">
                        <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;"
                            onclick="search_key('1', '0')">Search Points</button>
                    </div>
                </form>





                <div class="clear"></div>
                <div id="view_status"></div>
                <br />
                <div class="clear"></div>
                <div id="view_details_indu"></div>
            </div>
        </div>
        <!-- /.portlet -->

    </div>
    <!-- /.col -->

</div>
<!-- /.row -->

</div>

<?php //include ('dataTableScript.php'); ?>
<!-- /.container -->
<style type="text/css">
#cust_id-error,
#location-error,
#state-error,
#location-error,
#service_type-error,
#bank_type-error,
#pickup_type-error,
#feasibility-error,
#point_type-error,
#popupDatepicker-error {
    left: 10px;
    top: 65px;
    position: absolute;
}

.to_style_samplefile {
    padding-top: 6%;
    font-size: 17px;
}
</style>



<script type="text/javascript">
$(document).ready(function() {


   
    //var total_rec_new="<?php echo $total_rec;?>";
 
    //alert(total_rec_new);

    $('#uploadForm').on('submit', function(e) {
        var fileInput = $('#files').val();
        var allowedExtensions = /(\.xls)$/i;
        if (fileInput) {
            if (!allowedExtensions.test(fileInput)) {
                alert('Only .xls files are allowed!');
                e.preventDefault();
            } else {

            }
        } else {
            alert('Please select a file.');
            e.preventDefault();
        }
    });
    $('#popupDatepicker5').datepick();

    $('#popupDatepicker5').on('click', function() {
        $(".to_disable_btn").removeAttr('disabled');
    });


    if ($('.to_control_report').val() == '') {
        //$(".to_disable_btn").attr("disabled", true);

        $(".to_disable_btn").prop('disabled', true);
    }

    if ($('#files').val() == '') {
        $(".to_disable_upload_btn").prop('disabled', true);
    }

    $('#files').on('change', function() {

        if ($('#files').val() == '') {
            $(".to_disable_upload_btn").prop('disabled', true);
        } else {
            $(".to_disable_upload_btn").removeAttr('disabled');
        }
    });

    $("#search").on('change', function() {
        if ($('#search').val() == 'All'||$('#search').val()=='unmap') {
            $('.to_hidekey').hide();
            $('#to_append_pagination_data').show();
        } else {
            $('.to_hidekey').show();
            $('#to_append_pagination_data').hide();
        }
    });

    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    setTimeout(function() {
        $('.message_cu').fadeOut('fast');
    }, 3000);
    $.validator.setDefaults({
        ignore: ":hidden:not(select)"
    });
    $.validator.addMethod("phoneUS", function(phone, element) {
        phone = phone.replace(/\s+/g, "");
        return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
    }, "Enter valid phone number.");

    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var check = false;
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Use only uppercase and lowercase and numbers"
    );

    $("#demo-validation").validate({
        rules: {
            cust_id: {
                required: true
            },

            // start lat-long validate
            latitude: {
                required: {
                    depends: function(element) {
                        return $("#latlongflag").val() === "1";
                    }
                }
            },
            longitude: {
                required: {
                    depends: function(element) {
                        return $("#latlongflag").val() === "1";
                    }
                }
            },
            // end lat-long validate

            shop_id1: {
                required: true
            },
            state: {
                required: true
            },
            location: {
                required: true
            },
            tier: {
                required: true
            },
            loi_id: {
                required: true
            },
            loi_date: {
                required: true
            },
            pincode: {
                required: true,
                number: true,
                minlength: 6, // will count space 
                maxlength: 6

            },
            cust_code: {
                required: true
            },
            shop_code: {
                required: true
            },
            loc_code: {
                required: true
            },
            shop_name: {
                required: true,
                regex: /^[A-Za-z\d\w\n ,.]+$/
            },
            address: {
                required: true,
                regex: /^[A-Za-z\d\w\n ,.]+$/
            },
            phone: {
                required: true
            },
            contact_name: {
                required: true
            },
            contact_no: {
                required: true,
                phoneUS: true
            },
            service_type: {
                required: true
            },
            cash_limit: {
                required: true,
            },
            bank_type: {
                required: true
            },
            pickup_type: {
                required: true
            },
            feasibility: {
                required: true
            },
            qr_status: {
                required: true
            },
            vendor_id: {
                required: true
            },
            point_type: {
                required: true
            },
            process: {
                required: function(element) {
                    var ser_type = $("#service_type").val();
                    return ser_type == 'Cash Pickup' || ser_type == 'Both' || ser_type ==
                        'Evening Pickup';
                }
            }
        },
        messages: {
            cust_id: {
                required: 'Select Client Name.'
            },

            // start lat-long message
            latitude: {
                required: "Latitude is required"
            },
            longitude: {
                required: "Longitude is required"
            },
            // end lat-long message 

            shop_id1: {
                required: 'Enter Point ID.'
            },
            state: {
                required: 'Select Point State Name.'
            },
            location: {
                required: 'Enter Point Location.'
            },
            loi_id: {
                required: 'Enter Radiant LOI ID.'
            },
            pincode: {
                required: 'Enter Pincode'
            },
            cust_code: {
                required: 'Enter Customer Code.'
            },
            shop_code: {
                required: 'Enter Customer´s Point Code.'
            },
            loc_code: {
                required: 'Enter Radiant LOI ID.'
            },
            cust_code: {
                required: 'Enter Customer Code.'
            },
            shop_name: {
                required: 'Enter Point Name..Without Special characters'
            },
            address: {
                required: 'Enter Point Address..Without Special characters'
            },
            phone: {
                required: 'Enter Point Phone No.'
            },
            contact_name: {
                required: 'Enter Contact Name.'
            },
            contact_no: {
                required: 'Enter Contact Mobile No.'
            },
            service_type: {
                required: 'Select Service Type.'
            },
            cash_limit: {
                required: 'Select  Cash Max. Limit.'
            },
            bank_type: {
                required: 'Select Deposit Bank Type'
            },
            pickup_type: {
                required: 'Select Pickup Type'
            },
            feasibility: {
                required: 'Select Feasibility Check'
            },
            tier: {
                required: 'Select Tier'
            },
            point_type: {
                required: 'Select Point Type'
            },
            process: {
                required: 'Select Process'
            },
            qr_status: {
                required: 'Select QR Status'
            },
            vendor_id: {
        required: 'Select Vendor Name'
      }
        },
        submitHandler: function(form) {
            // All fields are valid, disable the submit button
            $("#submit").attr("disabled", true);

            // Proceed with form submission
            form.submit();
        }
    });
    $("body").on("change", "#cust_id", function() {
        $.ajax({
            type: "POST",
            url: "Clients/AjaxReference/ClientsLoadData.php",
            data: 'types=1&pid=m_shop&cust_id=' + $('#cust_id').val(),
            success: function(msg) {
                $('#cust_div').html(msg);
                $('#cust_div').trigger("chosen:updated");
                to_append_unique_name($('#cust_id').val());
            }
        });
    });


    function to_append_unique_name(custid) {
        $.ajax({
            type: "POST",
            url: "Clients/AjaxReference/ClientsLoadData.php",
            data: 'types=check_uniquecust_name&pid=m_shop&cust_id=' + custid,
            success: function(msg_data) {
                $('#centraliz_cust_name').val(msg_data);
            }
        });
    }

    /*$( "body" ).on( "blur", "#shop_code", function() {			
    	$.ajax({
    		type: "POST",
    		url: "ajax/load_data.php",
    		data: 'types=8&pid=m_shop&cust_id='+$('#cust_id').val(),
    		success: function(msg){					
    			if(msg=='Suc') {						
    				if(($('#shop_code').val().length)!=10) {
    					alert('Please enter at least 10 characters.');					
    				}						
    			}
    		}
    	});
    	
    });*/

    /*$( "body" ).on( "blur", "#pincode", function() {
    	cust_id = $('#cust_id').val();
    	pincode = $('#pincode').val();
    	if(pincode!='') {
    	$.ajax({
    		type: "POST",
    		url: "ajax/load_data.php",
    		data: 'types=7&pid=m_shop&cust_id='+cust_id+'&pincode='+pincode,
    		success: function(msg){
    			if(msg!='') {
    				$('#submit1').css('display', '');
    				$('#submit').css('display', 'none');
    				$('#load_lod_shop1').css('display', '');
    				$('#load_lod_shop1').html('Warning: It appears that you are trying to create a duplicate point!<br />For your Convenience the list of points served in the same Pin Code are listed below please check if this point appears in the list.');						
    				$('#load_lod_shop').css('display', '');
    				$('#load_lod_shop').html(msg);
    			}
    			else {
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
    });*/



    $("body").on("change", "#service_type", function() {
        if ($('#service_type').val() == 'MBC') {

            $('#mbc_types').css('display', '');
        } else {
            $('#mbc_types').css('display', 'none');
        }
    });
    $("body").on("change", "#search", function() {
        $('#keyword').val('');
        if ($('#search').val() == 'qr_status') {
            $('#keyword').attr('placeholder', 'Enter Yes or No');

        }


    });
    $("body").on("change", "#state", function() {

    });
    $("body").on("click", ".update_locid", function() {
        $.ajax({
            type: "POST",
            url: "Clients/AjaxReference/ClientsLoadData.php",
            data: 'types=5&pid=m_shop&con=acc_id&bank_type=' + $('#bank_type').val(),
            success: function(msg) {
                $('#basicModal_view').html(msg);
            }
        });
    });
    $("body").on("click", ".empid", function() {
        chek_lng = $('.empid:checkbox:checked').length;
        if (chek_lng > 1) {
            alert('Select Only One!');
            $(this).removeAttr('checked');
        }
    });
    $("body").on("click", "#save_loa", function() {
        var staff_de = "";
        $('#basicModal_view input[type=checkbox]').each(function() {
            if (this.checked) {
                staff_de += $(this).val();
            }
        });
        $('#account_no').val(staff_de);
        $('#acc_id').val(staff_de);

    });
});
<?php
  if ($id != '') {
  ?>
load_state()
<?php
  }
  ?>

function load_state() {
    $.ajax({
        type: "POST",
        url: "Clients/AjaxReference/ClientsLoadData.php",
        data: 'types=2&pid=m_shop&state=' + $('#state').val() +
            '<?php echo '&locat=' . $res_shop->location; ?>',
        success: function(msg) {
            $('#location').html(msg);
            $('#location').trigger("chosen:updated");
        }
    });
    $.ajax({
        type: "POST",
        url: "Clients/AjaxReference/ClientsLoadData.php",
        data: 'types=6&pid=m_shop&state=' + $('#state').val() +
            '<?php echo '&served_location=' . $res_shop->served_location ?>',
        success: function(msg) {
            $('#served_location').html(msg);
            $('#served_location').trigger("chosen:updated");
        }
    });
    /* $.ajax({
    	type: "POST",
    	url: "ajax/load_data.php",
    	data: 'types=10&pid=m_shop&state='+$('#state').val()+'<?php echo '&city_localtion=' . $res_shop->city_name ?>',
    	success: function(msg){
    		$('#location2').html(msg);
    		$('#location2').trigger("chosen:updated");
    	}
    }); */

}

function load_city() {
    $.ajax({
        type: "POST",
        url: "Clients/AjaxReference/ClientsLoadData.php",
        data: 'types=11&pid=m_shop&state=' + $('#state').val() + '&point_loc=' + $('#location').val(),
        success: function(msg) {
            $('#location2').html(msg);
            $('#location2').trigger("chosen:updated");

        }
    });

}



function search_key(search_type, page_start) {
   
   if($('#search').val() == ''){
    alert('Please select search criteria');
   }


   if ($('#search').val() == 'All'||$('#search').val() == 'unmap') {
       if ($('#Select_Limit').val() == '') {
           alert('Please Select Limit');
           return false;
       } else {
           var new_pagination_limit = $('#Select_Limit').val();
       }
   } else {
       var new_pagination_limit = '';
   }

  
   if ($('#keyword').val() != '' || $('#search').val() == 'All' || $('#search').val() == 'unmap') {
       $('#keyword').removeClass('error_dispaly');

       $.ajax({
           type: "POST",
           url: "Clients/AjaxReference/ClientsLoadData.php",
           data: {
               'types': 4,
               'pid': 'm_shop',
               'search': $('#search').val(),
               'keyword': $('#keyword').val(),
               'new_pagination_limit': new_pagination_limit
           },
           beforeSend: function() {
               
               $('#view_details_indu').html('<img src="img/loading.gif" alt="Loading...">');
           },
           success: function(response) {
            

              
               $('#view_details_indu').html(response);

                   $("#Load_shops_data").DataTable({
                       ordering: false
                   });
               
           },
           error: function(xhr, status, error) {
               
               alert('An error occurred: ' + error);
           }
       });
   } else {
     
       $('#keyword').addClass('error_dispaly');
   }
}


function pin_gen() {
    $('#pin_number').val(Math.floor(1000 + Math.random() * 9000));
}
$("#seleted_beat").multiselect({
    includeSelectAllOption: true,
    enableFiltering: true,
    buttonWidth: '240px',
    maxHeight: 200,
    inheritClass: true,
    nonSelectedText: 'Select Days'
});


$('#popupDatepicker').keydown(function(e) {
    var code = e.keyCode || e.which;
    if (code == '9') return true;
    else event.preventDefault();
});

$('#process_comp').hide();

function change_comp(val) {
    if (val == "Cash Pickup" || val == "Both") {
        $('#process_comp').show();
    } else {
        $('#process_comp').hide();
    }
}
</script>