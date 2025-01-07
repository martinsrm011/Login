<?php
include "../DbConnection/dbConnect.php";
include('CommonReference/date_picker_link.php');
$dep_id = $_REQUEST['dep_id'];
$ce_ids = $_GET['ce_id'];
$trans_dates = $_GET['trans_date'];
$per=$_SESSION['per'];
?>
<style type="text/css">
.table {
    width: 50% !important;
}

.faq_question {
    color: #0033ff;
    font-family: Geneva, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: bold;
}

.warning_box_green {
    background-color: #1F77A3;
    border-radius: 5px;
    color: #000000;
    font-family: Geneva, Arial, Helvetica, sans-serif;
    font-size: 14px;
    padding: 8px 10px;
    width: 70%;
}

.button {
    background-color: #f2f2f2;
    border: 1px solid #c6c6c6;
    border-radius: 3px;
    color: #666666;
    font-family: Geneva, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: bold;
    padding: 8px 15px;
}

.warning_box_gray {
    background-color: #e7e7e7;
    border-radius: 5px;
    color: #000000;
    font-family: Geneva, Arial, Helvetica, sans-serif;
    font-size: 14px;
    padding: 8px 10px;
}

.warning_box_yellow {
    background-color: #fff6bf;
    border-radius: 5px;
    color: #000000;
    font-family: Geneva, Arial, Helvetica, sans-serif;
    font-size: 14px;
    padding: 8px 10px;
}

.tbl {
    background: #fff6bf;
    margin: 5%;
}

.ncfag {
    margin-left: 20%;
    color: red;
}

#dep_type_chosen {
    width: 180px !important;
}

#acc_id_cu_chosen {
    width: 360px !important;
}

.load_all_details td {
    padding: 5px;
}

#tce_id_chosen {
    width: 245px !important;
}
</style>
<?php

$sql_dep = mysql_query("SELECT * FROM daily_deposit WHERE dep_id='$dep_id'");
$res_dep = mysql_fetch_assoc($sql_dep);
if($ce_ids!='') { $sel_ce_id = $ce_ids; }
else { $sel_ce_id = $res_dep['staff_id']; }
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class="portlet">
                <h3 class="portlet-title"><u>Bank Deposit / Transfer Details</u></h3>
                <!--<form id="demo-validation" action="add_details.php?pid=bdep" method="post" data-validate="parsley" class="form parsley-form">-->
                <input type="hidden" id="eod_id" value="<?php echo $region; ?>">
                <input type="hidden" id="user_per" value="<?php echo $per; ?>">
                <input type="hidden" name="dep_id" value="<?php echo $dep_id; ?>" />
                <div class="form-group col-sm-12">
                    <div class="form-group col-sm-5">
                        <!-- eod details Msgs-->
                        <div id="eod_user" style="display:none">
                            <div style="padding: 7px;" class="alert alert-danger" align="center"> <a aria-hidden="true"
                                    href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b> Dear
                                    User<br>
                                    You cannot to enter transactions for the date for which the EOD has been
                                    initiated.<br>
                                    If this is with regard to amendments you may contact Banking Team.<a
                                        href="?pid=bdep"><i class="fa fa-refresh"></i></a></b> </div>
                        </div>


                        <div id="eod_user_seven" style="display:none">
                            <div style="padding: 7px;" class="alert alert-danger" align="center"> <a aria-hidden="true"
                                    href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b> Dear
                                    Team<br>
                                    The Deposit Entries can be only backtracked for 4 Days for the region can
                                    complete<br>
                                    the Deposit transactions of before 4 Days and press EOD and then proceeds for
                                    Current Transactions <a href="?pid=bdep"><i class="fa fa-refresh"></i></a>
                                </b> </div>
                        </div>
                        <!-- Eod details msgs -->



                        <div class="form-group col-sm-11">
                            <div class="form-group col-sm-8">
                                <label for="name">
                                    <label class="compulsory">*</label>
                                    Deposit / Transfer Date
                                </label>
                                <input readonly type="text" id="popupDatepicker"
                                    value="<?php if($res_dep['dep_date']!='0000-00-00' && $res_dep['dep_date']!='') { echo date('d-m-Y', strtotime($res_dep['dep_date'])); } else { if($trans_dates!='') { echo $trans_dates; } else { echo date('d-m-Y'); }} ?>"
                                    name="trans_date" class="form-control" placeholder="Transaction Date" tabindex="4"
                                    <?php if($region=='26') { ?> onblur="date_valid()" <?php } ?>>
                            </div>
                            <div class="clear"></div>
                            <?php
			
					IF($login_regoin !='' ){
					$sql_ce = mysql_query("SELECT a.ce_name, a.ce_id, lo.location FROM shop_cemap AS c INNER JOIN  radiant_ce AS a 
					ON a.ce_id=c.pri_ce  INNER JOIN radiant_location AS b ON a.loc_id=b.location_id INNER JOIN location_master AS lo ON lo.loc_id=b.location_id  WHERE  b.region_id IN (".$login_regoin.") AND a.ce_name!='' AND a.`status`='Y' AND b.`status`='Y' GROUP BY a.ce_id ORDER BY lo.location, a.ce_name ASC");
					IF(mysql_num_rows($sql_ce) > 0){
					while($res_ce = mysql_fetch_assoc($sql_ce)) {
					$ce_detils[$res_ce['ce_id']] = $res_ce['location'].' - '.$res_ce['ce_name'];
					}
					}
					}
							 
							?>
                            <div class="form-group col-sm-11">
                                <label for="name">
                                    <label class="compulsory">*</label>
                                    CE ID
                                </label>
                                <select tabindex="2" class="form-control parsley-validated chosen-select" name="ce_id"
                                    id="ce_id" <?php if($region=='26') { ?> onchange="date_valid()" <?php } ?>>
                                    <option value="">Select CE</option>
                                    <?php
									IF(!EMPTY($ce_detils)){
									foreach($ce_detils as $key=>$val) {
										?>
                                    <option value="<?php echo $key; ?>"
                                        <?php if($sel_ce_id==$key && $sel_ce_id!='') { echo 'selected'; } ?>>
                                        <?php echo $val.' - '.$key;  ?></option>
                                    <?php	
									}
									}
									?>
                                </select>
                            </div>
                            <div class="form-group col-sm-1">
                                <button style="padding: 3px; margin-top: 25px;" value="submit" id="load_trans"
                                    class="btn btn-danger search_btn" type="button" onClick="load_transation()">Get
                                    Data</button>
                            </div>
                            <div class="clear"></div>
                            <!--  <div class="form-group col-sm-8" id="load_ce_location" style="display:none;">
              <table cellpadding="5" cellspacing="5" border="0">
                <tr>
                  <td width="110"><label for="name"> Location Name </label></td>
                  <td width="10" align="center"><label>:</label></td>
                  <td id="load_location"></td>
                </tr>
              </table>
            </div>
            <div class="clear"></div>-->
                            <div class="load_opt" <?php if($dep_id=='') { ?> style="display:none;" <?php } ?>>
                                <div class="form-group col-sm-12">
                                    <label>
                                        <input name="ent_type" onChange="load_types(1)" type="radio" value="Deposit"
                                            tabindex="1" class="parsley-validated option_type" data-mincheck="2"
                                            <?php if($res_dep['ent_type']=='Deposit') { echo 'checked'; } ?>>
                                        Deposit&nbsp; </label>

                                    <?php 
        
                  if(!empty($other_permissions)){ 
                  $SpecialPrivilege = explode(',',$other_permissions);
                  
                  if(in_array('BankDepositTransfer',$SpecialPrivilege)){ 
                  ?>
                                    <label>
                                        <input type="radio" tabindex="2" name="ent_type"
                                            <?php if($res_dep['ent_type']=='Transfer') { echo 'checked'; } ?>
                                            onChange="load_types(2)" class="parsley-validated option_type"
                                            value="Transfer" data-mincheck="2" />
                                        Transfer(Inter Change) &nbsp; </label>
                                    <?php   
                  }
                }
                ?>
                                </div>
                                <div class="clear"></div>
                                <div class="form-group col-sm-12" id="another_ce" style="display:none;">
                                    <label for="name">
                                        <label class="compulsory">*</label>
                                        CE ID
                                    </label>
                                    <select tabindex="2" class="form-control parsley-validated chosen-select"
                                        name="tce_id" id="tce_id">
                                        <option value="">Select CE</option>
                                        <?php
									IF(!EMPTY($ce_detils)){
									foreach($ce_detils as $key=>$val) {
										?>
                                        <option value="<?php echo $key; ?>"
                                            <?php if($res_dep['ce_id']==$key) { echo 'selected'; } ?>>
                                            <?php echo $val.' - '.$key;  ?></option>
                                        <?php	
									}
									}
									?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                <div class="form-group col-sm-8" id="load_dep_type" style="display:none;">
                                    <label for="name"><label class="compulsory">*</label>Account Type</label>
                                    <!-- chosen-select -->
                                    <select tabindex="2" class="form-control parsley-validated" name="dep_type"
                                        onChange="dep_type1()" id="dep_type" style="width:150px;">
                                        <option value="">Select</option>
                                        <option value="Burial"
                                            <?php if($res_dep['dep_type']=='Burial') { echo 'selected'; } ?>>Burial
                                        </option>
                                        <option value="Client Bank"
                                            <?php if($res_dep['dep_type']=='Client Bank') { echo 'selected'; } ?>>Client
                                            Bank</option>
                                        <option value="Partner Bank"
                                            <?php if($res_dep['dep_type']=='Partner Bank') { echo 'selected'; } ?>>
                                            Partner Bank</option>

                                        <option value="Burial-Vault"
                                            <?php if($res_dep['dep_type']=='Burial-Vault') { echo 'selected'; } ?>>
                                            Burial-Vault</option>

                                    </select>
                                </div>
                                <div class="form-group col-sm-8" id="acc_ids" style="display:none;">
                                    <label for="name">
                                        <label class="compulsory">*</label>
                                        Select Account No/Vault :
                                    </label>
                                    <br />
                                    <select tabindex="2" class="form-control parsley-validated chosen-select"
                                        name="acc_id" id="acc_id_cu" style="width:300px !important;">
                                        <option value="select">choose </option>
                                        <?php
									//$part1 and 
										if($dep_id!='') {
										$qub=mysql_query("select acc_id, bank_name, branch_name, account_no from bank_master where  status='Y' order by bank_name,branch_name,account_no");
										//$sqlb="select * from bank_master inner join bffo_banks on bank_master.acc_id=bffo_banks.acc_id where (bank_master.location in (select loc_id from location_master where status='Y') or bank_master.location='0') and bffo_banks.acc_status='Active' and bffo_banks.rad_purpose!='Funding' AND bank_master='Y' order by bank_master.bank_name,bank_master.branch_name,bank_master.account_no";
										//$qub=mysql_query($sqlb);
										IF(mysql_num_rows($qub) > 0){
										while($rb=mysql_fetch_array($qub)) { ?>
                                        <option value="<?php echo $rb['acc_id'];?>"
                                            <?php if($res_dep['acc_id']==$rb['acc_id']) { echo 'selected'; } ?>>
                                            <?php echo $rb['bank_name']. " - ".$rb['branch_name']. " - ".$rb['account_no'];?>
                                        </option>
                                        <?php
										}
										}

										}
									?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                <div class="form-group col-sm-6" id="dep_amt_txt" style="display:none;">
                                    <label for="name">
                                        <label class="compulsory">*</label>
                                        Dep. / Trans. Amount
                                    </label>
                                    <?php //echo $res_dep['dep_amount']; ?>
                                    <input name="dep_amount" type="text" class="form-control parsley-validated"
                                        data-required="true" id="dep_amount" tabindex="5"
                                        value="<?php echo $res_dep['dep_amount']; ?>" />
                                    <input name="dep_amount" type="hidden" class="form-control parsley-validated"
                                        data-required="true" id="dep_amountss" tabindex="5"
                                        value="<?php echo $res_dep['dep_amount']; ?>" />
                                    <input type="hidden" name="prev_day_trans_cih" id="prev_day_trans_cih" />
                                </div>
                                <div class="form-group col-sm-6" id="dep_remarks_txt" style="display:none;">
                                    <label for="name">
                                        <label class="compulsory">*</label>
                                        Remarks For Chennai
                                    </label>
                                    <input name="remarks" type="text" class="form-control parsley-validated"
                                        data-required="true" id="remarks" tabindex="5"
                                        value="<?php echo $res_dep['remarks']; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="load_opt" <?php if($dep_id=='') { ?> style="display:none;" <?php } ?>>
                            <div class="form-group col-sm-4" style="margin-left:17.5%">
                                <input type="hidden" name="trans_ids" id="trans_ids" />
                                <div id="load_btnss">
                                    <button class="btn btn-danger search_btn upd_btnsss" id="bank-dep-btn"
                                        align="center" type="submit" name="submit"
                                        onClick="add_deposit('<?php echo $dep_id; ?>')">Submit </button>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group col-sm-12">
                            <div class="form-group col-sm-12" align="left">
                                <div class="warning_box_yellow" style="width:100%;" align="center"> Today Bank Deposits
                                    / Transfers </div>
                            </div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered"
                                style="width: 100% !important">
                                <thead>
                                    <tr>
                                        <th>Type </th>
                                        <th>Bank Type </th>
                                        <th>Deposit / Transfer To</th>
                                        <th>Dep Amount</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="today_dep">
                                    <tr>
                                        <td>
                                            <div align="center"></div>
                                        </td>
                                        <td>
                                            <div align="center"></div>
                                        </td>
                                        <td>
                                            <div align="left"> <strong>Total Amount </strong> </div>
                                        </td>
                                        <td>
                                            <div align="right"> 0 </div>
                                        </td>
                                        <td>
                                            <div align="right"> 0 </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if($dep_id=='') { ?>
                        <div class="form-group col-sm-12">
                            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="warning_box_gray"
                                id="deduction_status">
                                <thead>
                                    <tr>
                                        <th width="52%">
                                            <div align="right"> Deduction Status </div>
                                        </th>
                                        <th width="1%">
                                            <div align="center"> : </div>
                                        </th>
                                        <th>
                                            <div align="left" class="form-group col-sm-3">
                                                <input name="opt_ded" type="checkbox" id="opt_ded" value="Y"
                                                    onClick="click_deduction()" />
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="display:none;">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="27" colspan="3" align="center">
                                            <div class="warning_box_green"
                                                style="color:#FFF; width:96%; margin:0px 10px; font-weight:bold;">
                                                Deductions Amount </div>
                                            <div align="center"></div>
                                            <div align="left"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="25%">
                                            <div align="right"> Bank charges </div>
                                        </td>
                                        <td width="1%">
                                            <div align="center"> : </div>
                                        </td>
                                        <td width="48%">
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount1" type="text" class="form-control parsley-validated"
                                                    id="amount1" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="right"> CE Salary </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount2" type="text" class="form-control parsley-validated"
                                                    id="amount2" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="right"> Conveyence </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount3" type="text" class="form-control parsley-validated"
                                                    id="amount3" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="right"> Fax/Courier Charges </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount4" type="text" class="form-control parsley-validated"
                                                    id="amount4" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="right"> PB Defaced Notes </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount5" type="text" class="form-control parsley-validated"
                                                    id="amount5" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="right"> Cash Delivery </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount6" type="text" readonly
                                                    class="form-control parsley-validated" id="amount6" />
                                            </div>
                                        </td>
                                    </tr>
                                    <!--<tr>
                  <td ><div align="right"> Cash Van Expense </div></td>
                  <td><div align="center"> : </div></td>
                  <td><div align="left" class="form-group col-sm-8">
                      <input name="amount7" type="text"  class="form-control parsley-validated"  id="amount7" readonly="readonly" />
                    </div></td>
                </tr>-->
                                    <tr>
                                        <td>
                                            <div align="right"> Other Deductions </div>
                                        </td>
                                        <td>
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount8" type="text" class="form-control parsley-validated"
                                                    id="amount8" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">
                                            <div align="right"> Deduction Remarks </div>
                                        </td>
                                        <td valign="middle">
                                            <div align="center"> : </div>
                                        </td>
                                        <td valign="top">
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="other_remarks" type="text"
                                                    class="form-control parsley-validated" id="other_remarks" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <div align="right"> Burial Defaced Notes </div>
                                        </td>
                                        <td valign="top">
                                            <div align="center"> : </div>
                                        </td>
                                        <td valign="top">
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount9" type="text" class="form-control parsley-validated"
                                                    id="amount9" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <div align="right"> Cash Loss </div>
                                        </td>
                                        <td valign="top">
                                            <div align="center"> : </div>
                                        </td>
                                        <td valign="top">
                                            <div align="left" class="form-group col-sm-8">
                                                <input name="amount10" type="text"
                                                    class="form-control parsley-validated" id="amount10" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" colspan="3" align="center">
                                            <div align="center" id="load_deduct_msg" style="color:#0C0;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="form-group col-sm-4" style="margin-left:17.5%">
                                                <button class="btn btn-danger search_btn" id="bank-dep-btn"
                                                    align="center" type="submit" name="submit"
                                                    onClick="add_deduction()">Update Deductions Details</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="form-group col-sm-7">
                        <div class="form-group col-sm-12">
                            <table align="center" class="warning_box_gray" width="100%" cellspacing="1" cellpadding="1"
                                border="0" id="incoming_status">
                                <thead>
                                    <tr>
                                        <td width="52%">
                                            <div align="right" style="padding:10px 0;"> Collection Status </div>
                                        </td>
                                        <td width="4%">
                                            <div align="center"> : </div>
                                        </td>
                                        <td>
                                            <div align="left">
                                                <input id="opt_coll" type="checkbox" value="Y" name="opt_coll"
                                                    onClick="load_incoming()">
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody style="display:none;">
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="27" align="center" colspan="3" width="100%">
                                            <div style="background:#267f9f; width:96%; margin:0px 10px; color:#FFF; font-weight:bold;"
                                                class="warning_box_green"> Excess Cash Adjusments </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="17%">
                                            <div align="right"> Amount </div>
                                        </td>
                                        <td width="1%">
                                            <div align="center"> : </div>
                                        </td>
                                        <td width="48%">
                                            <div align="left" class="form-group col-sm-4">
                                                <input name="other_dep" type="text"
                                                    class="form-control parsley-validated" id="other_dep" value="" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-sm-12">
                            <div id="view_trans">
                                <div class="warning_box_yellow form-group col-sm-12" align="center"
                                    style="margin-bottom:30px;"> No of Transactions Available: 0 </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--</form>-->

            </div>
            <!-- /.portlet-body -->
            <div class="clear"></div>
        </div>
        <!-- /.col -->

    </div>
    <!-- /.row -->
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

        /*$("body").on("click", "#load_trans", function() {});*/

    });

    $.validator.setDefaults({
        ignore: ":hidden:not(select)"
    });
    $("#demo-validation").validate({
        rules: {
            trans_date: {
                required: true
            },
            ce_id: {
                required: true
            },
            ce_name: {
                required: true
            },
            acc_id: {
                required: true
            },
            sdate: {
                required: true
            },
            dep_amount: {
                required: true
            }
        },
        messages: {
            trans_date: {
                required: 'Enter Deposit / Transfer Date.'
            },
            ce_id: {
                required: 'Enter CE ID.'
            },
            ce_name: {
                required: 'Enter CE Name.'
            },
            acc_id: {
                required: 'Select Select Account No.'
            },
            sdate: {
                required: 'Enter Location Name.'
            },
            dep_amount: {
                required: 'Enter Dep. / Trans. Amount.'
            }
        }
    });
    $("body").on("click", "#bank-dep-btn", function() {
        $("#demo-validation").trigger("submit");
    });

    <?php
		if($dep_id!='') {			
			?>
    load_transation();
    <?php	
		}
		if($ce_ids!='' && $trans_dates!='') {			
		?>
    load_transation();
    <?php	
		}
		?>

    function load_transation() {

        var eod_reg = $('#eod_id').val();
        var user_per = $('#user_per').val();
        var old_date = $('#popupDatepicker').val();
        window.localStorage.setItem("old_date", old_date);


        $('.load_opt').css('display', 'none');
        $('#load_ce_location').css('display', 'none');
		var ceId = $('#ce_id').val();
		if(ceId == ""){
			alert("Please select CE ID");return false;
		}
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/load_daily_dep.php",
            data: 'types=1&pid=bdep&trans_date=' + $('#popupDatepicker').val() + '&ce_id=' + $('#ce_id').val(),
            beforeSend: function() {
                $('#view_trans').html('<img src="img/loading.gif" alt="Radiant.">');
            },
            success: function(msg) {
                $('#view_trans').html(msg);
                $('.load_opt').css('display', '');
            }
        });
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/load_daily_dep.php",
            data: 'types=4&pid=bdep&trans_date=' + $('#popupDatepicker').val() + '&ce_id=' + $('#ce_id').val(),
            success: function(msg) {
                $('#load_ce_location').css('display', '');
                $('#load_location').html(msg);
            }
        });
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/load_daily_dep.php",
            data: 'types=2&pid=bdep&trans_date=' + $('#popupDatepicker').val() + '&ce_id=' + $('#ce_id').val(),
            success: function(msg) {
                msg1 = msg.split('^');
                $('#amount1').val(msg1[0]);
                $('#amount2').val(msg1[1]);
                $('#amount3').val(msg1[2]);
                $('#amount4').val(msg1[3]);
                $('#amount5').val(msg1[4]);
                $('#amount6').val(msg1[5]);
                $('#amount7').val(msg1[6]);
                $('#amount8').val(msg1[7]);
                $('#other_remarks').val(msg1[8]);
                $('#amount9').val(msg1[9]);
                $('#amount10').val(msg1[10]);
                $('#other_dep').val(msg1[11]);


            }
        });
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/load_daily_dep.php",
            data: 'types=3&pid=bdep&trans_date=' + $('#popupDatepicker').val() + '&ce_id=' + $('#ce_id').val() +
                '&cl_region=' + eod_reg + '&user_per=' + user_per,
            success: function(msg) {

                $('#today_dep').html(msg);
            }
        });

        //<!---eod details msg-- >
        <?php if($per!='Admin' and $per!='Banking' ) { ?>
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/transactionReconLoadData.php",
            data: 'types=3&pid=recon_eod11&trans_date=' + $('#popupDatepicker').val() + '&ce_id=' + $('#ce_id')
                .val() + '&cl_region=' + eod_reg,
            success: function(msg) {
                //alert(msg);
                if (msg == 1) {
                    //alert(msg);eod_user_seven
                    $('#eod_user').css('display', 'block');
                    $('#load_trans').hide();
                    $('#bank-dep-btn').hide();
                    $('#amount1,#amount2,#amount3,#amount4,#amount5,#amount6,#amount8,#amount9,#other_dep,#amount10')
                        .prop('readonly', true);

                } else if (msg == 2) {
                    //alert("hai");

                    $('#eod_user_seven').css('display', 'block');
                    $('#load_trans').hide();
                    $('#bank-dep-btn').hide();
                    $('#amount1,#amount2,#amount3,#amount4,#amount5,#amount6,#amount8,#amount9,#other_dep,#amount10')
                        .prop('readonly', true);
                }


                //$('#today_dep').html(msg);
            }
        });

        <?php } ?>

            <?php
			if($dep_id=='') {
				
				?>
        $('#load_dep_type').css('display', 'none');
        $('#another_ce').css('display', 'none');
        $('#acc_ids').css('display', 'none');
        $('#another_ce').css('display', 'none');
        $('#dep_amt_txt').css('display', 'none');
        $('#dep_remarks_txt').css('display', 'none');

        $('#dep_amount').val('');
        $('#remarks').val('');
        $('.option_type').prop('checked', false);

        <?php } ?>

    }
    <?php
		if($dep_id!='') { 
		if($res_dep['ent_type']=='Deposit') { $load_dep = '1'; } else { $load_dep = '2'; }
			?>
    load_types(<?php echo $load_dep; ?>);
    <?php
		}
		?>

    function load_types(arg) {


        if (arg == 1) {
            $('#dep_amt_txt').css('display', 'inline');
            $('#dep_remarks_txt').css('display', 'inline');
            $('#load_dep_type').css('display', 'inline');
            $('#another_ce').css('display', 'none');
            $('#acc_ids').css('display', 'none');


        } else {
            $('#dep_amt_txt').css('display', 'inline');
            $('#dep_remarks_txt').css('display', 'inline');
            $('#load_dep_type').css('display', 'inline');
            $('#another_ce').css('display', 'inline');
            $('#acc_ids').css('display', 'none');



        }
    }
    <?php
		if($dep_id!='') {
			?>
    dep_type1();
    <?php
		}	
		?>

    function dep_type1() {
        option_type11 = $("input:radio[name=ent_type]:checked").val();

        if (option_type11 == 'Deposit') {
            $('#acc_ids').css('display', 'inline');
        }
        dep_type = $('#dep_type').val();
        if (dep_type == 'Burial-Vault') {

            //new

            $.ajax({
                type: "POST",
                url: "Transactions/AjaxReference/load_daily_dep.php",
                data: 'types=6&pid=bdep&dep_type=' + dep_type +
                    <?php echo "'&acc_ids=".$res_dep['acc_id']."'"; ?>,
                success: function(msg) {
                    $('#acc_id_cu').html(msg);
                    $('#acc_id_cu').trigger("chosen:updated");
                }
            });

        } else {
            $.ajax({
                type: "POST",
                url: "Transactions/AjaxReference/load_daily_dep.php",
                data: 'types=5&pid=bdep&dep_type=' + dep_type +
                    <?php echo "'&acc_ids=".$res_dep['acc_id']."'"; ?>,
                success: function(msg) {
                    $('#acc_id_cu').html(msg);
                    $('#acc_id_cu').trigger("chosen:updated");
                }
            });
        }
    }

    function click_deduction() {
        if ($("#opt_ded").is(':checked')) {
            $('#deduction_status tbody').css('display', '');
        } else {
            $('#deduction_status tbody').css('display', 'none');
        }
    }

    function load_incoming() {
        if ($("#opt_coll").is(':checked')) {
            $('#incoming_status tbody').css('display', '');
        } else {
            $('#incoming_status tbody').css('display', 'none');
        }
    }

    function add_deposit(upd_id) {
        var new_date = $('#popupDatepicker').val();
        var old_date = localStorage.getItem('old_date');
        if (old_date != new_date || old_date == "") {
            alert("The CE loaded date mismatch");
            window.location = location.href;
            return false;
        }
        // exit();
        //alert("hai");
        var dep_type = $('#dep_type').val();
        var op_typ = $("input:radio[name=ent_type]:checked").val();

        var acc_no = $('#acc_id_cu').val();
        if (op_typ == 'Transfer') {
            var ceid = $("#tce_id").val();
            if (ceid == "") {
                alert("Please select CE ID");
                return false;
            }
        }
        if (op_typ != 'Transfer') {
            if (dep_type == '') {
                alert("Please Select Deposit Type");
                return false;
            }
            if (acc_no == '') {
                alert("Please Select The Account No");
                return false;
            }
        }
        <?php
			
			 if($dep_id!='')  {
				?>
        var course_id = 1;
        <?php
			}
			else {
				?>
        var course_id = $('#course_id input:checkbox:checked').length;
        <?php } ?>

        if (course_id >= 1) {
            //alert($('#trans_ids').val());
            dep_date = $('#popupDatepicker').val();
            ce_id = $('#ce_id').val();
            option_type = $("input:radio[name=ent_type]:checked").val();
            dep_type = $('#dep_type').val();
            acc_id_cu = $('#acc_id_cu').val();
            dep_amount = $('#dep_amount').val();
            dep_amountss = $('#dep_amountss').val();
            remarks = $('#remarks').val();
            ent_type = option_type;
            tce_id = $('#tce_id').val();
            trans_ids = $('#trans_ids').val();
            prev_day_trans_cih = $('#prev_day_trans_cih').val();
            $.ajax({
                type: "POST",
                url: "Transactions/AjaxReference/add_deposit.php",
                data: 'types=1&pid=bdep&dep_date=' + dep_date + '&ce_id=' + ce_id + '&option_type=' +
                    option_type + '&dep_type=' + dep_type + '&acc_id_cu=' + acc_id_cu + '&dep_amount=' +
                    dep_amount + '&dep_amountss=' + dep_amountss + '&remarks=' + remarks + '&tce_id=' + tce_id +
                    '&ent_type=' + ent_type + '&upd_id=' + upd_id + '&trans_ids=' + trans_ids +
                    '&prev_day_trans_cih=' + prev_day_trans_cih,
                success: function(msg) {
                    $('#load_dep_type').css('display', 'none');
                    $('#another_ce').css('display', 'none');
                    $('#acc_ids').css('display', 'none');
                    $('#another_ce').css('display', 'none');
                    $('#dep_amt_txt').css('display', 'none');
                    $('#dep_remarks_txt').css('display', 'none');

                    $('#dep_amount').val('');
                    $('#remarks').val('');
                    $('#dep_type').html(
                        '<option value="">Select Account Type</option><option value="Burial">Burial</option><option value="Client Bank">Client Bank</option><option value="Partner Bank">Partner Bank</option>'
                        );
                    $('#dep_type').trigger("chosen:updated");
                    $('.option_type').prop('checked', false);
                    /*$('#load_dep_msg').css('display', '');
                    $('#load_dep_msg').html('Deductions Amount Update Successful');
                    setTimeout(function() {
                    	$('#load_dep_msg').fadeOut('fast');
                    }, 3000);*/

                    var pathname = window.location.href;
                    path_name = pathname.split('?');
                    window.location.href = path_name[0] + '?pid=bdep&ce_id=' + $('#ce_id').val() +
                        '&trans_date=' + $('#popupDatepicker').val()

                    load_transation();

                }
            });
        } else {
            alert("Select Any One Transaction");
        }



    }

    function add_deduction() {
        //alert("hai");
        dep_date = $('#popupDatepicker').val();
        ce_id = $('#ce_id').val();
        opt_coll = $('#opt_coll').val();
        option_type = $("input:radio[name=opt_coll]:checked").val();
        //alert(option_type);
        other_dep = $('#other_dep').val();
        amount1 = $('#amount1').val();
        amount2 = $('#amount2').val();
        amount3 = $('#amount3').val();
        amount4 = $('#amount4').val();
        amount5 = $('#amount5').val();
        amount6 = $('#amount6').val();
        //amount7 = $('#amount7').val();
        amount8 = $('#amount8').val();
        other_remarks = $('#other_remarks').val();
        amount9 = $('#amount9').val();
        amount10 = $('#amount10').val();
        $.ajax({
            type: "POST",
            url: "Transactions/AjaxReference/add_deposit.php",
            data: 'types=2&pid=bdep&dep_date=' + dep_date + '&ce_id=' + ce_id + '&amount1=' + amount1 +
                '&amount2=' + amount2 + '&amount3=' + amount3 + '&amount4=' + amount4 + '&amount5=' + amount5 +
                '&amount6=' + amount6 + '&amount8=' + amount8 + '&other_remarks=' + other_remarks +
                '&amount9=' + amount9 + '&amount10=' + amount10 + '&opt_coll=' + opt_coll + '&other_dep=' +
                other_dep,
            success: function(msg) {
                //alert(msg);
                $('#load_deduct_msg').css('display', '');
                $('#load_deduct_msg').html('Deductions Amount Update Successful');
                setTimeout(function() {
                    $('#load_deduct_msg').fadeOut('fast');
                }, 3000);
                load_transation();
            }
        });
    }

    function amount_mul() {
        var sum = 0;
        var trans_id = '';
        $('#trans_ids').val('');
        $('#course_id :checkbox:checked').each(function(idx, elm) {
            valss = elm.value.split('-')
            sum += parseInt(valss[0], 10);
            trans_id += parseInt(valss[1], 10) + ',';
            if (valss[2] == 'PB' || valss[2] == 'PPB' || valss[2] == 'PCB' || valss[2] == 'TPB' || valss[2] ==
                'TPPB' || valss[2] == 'TPCB') {
                $('#prev_day_trans_cih').val(valss[2]);
            } else {
                $('#prev_day_trans_cih').val('');
            }

        });
        $('#trans_ids').val(trans_id);

        $('#dep_amount').val(sum);
        $('#dep_amountss').val(sum);
        if (sum == '' || sum == 0) {
            $('#prev_day_trans_cih').val('');
        }
    }

    function date_valid() {
        curr_date = '01-09-2017';
        var trans_date = $("#popupDatepicker").val();
        if (trans_date >= curr_date) {
            //alert('ss');
            $('#load_trans').show();
        } else {
            $('#load_trans').hide();
        }

    }
    </script>