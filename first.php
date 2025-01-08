<?php
include('CommonReference/date_picker_link.php');
$id = $_REQUEST['id'];
$user = $_SESSION['lid'];
$user_name = $_SESSION['lid'];
$per = $_SESSION['per'];
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class=" portlet">
                <h3 class="portlet-title"> <u>Customer Approval</u> </h3>
                <div class="success_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Data Saved
                            Successfully</b>
                    </div>
                </div>
                <div class="fail_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-danger" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Data Failed To
                            Save</b>
                    </div>
                </div>
                <div class="update_success_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Data Updated
                            Successfully</b>
                    </div>
                </div>
                <div class="update_fail_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-danger" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Data Failed to
                            Update</b>
                    </div>
                </div>
                <div class="delete_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Data Deleted
                            Successfully </b>
                    </div>
                </div>
                <form id="customer_form">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">Customer Details</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="300">
                                    <label for="customer_name"><label class="compulsory">*</label>Business / Shop /
                                        Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control" id="customer_name"
                                        placeholder="Customer Name">
                                    <p class="validation-message" id="customer_name_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="owner_directors_name"><label
                                            class="compulsory"></label>Owner's/Director's
                                        Name</label>
                                    <input type="text" name="owner_directors_name" class="form-control"
                                        id="owner_directors_name" placeholder="Owner/Directors
                                        Name">
                                    <p class="validation-message" id="owner_directors_name_error" style="color: red;">
                                    </p>
                                </td>
                                <td width="200">
                                    <label for="customer_id">Customer ID</label>
                                    <input type="text" name="customer_id" id="customer_id" class="form-control"
                                        readonly>
                                </td>
                                <td width="200">
                                    <label for="constitution"><label class="compulsory">*</label> Ownership </label>
                                    <select id="constitution" name="constitution" class="form-control chosen-select">
                                        <option value="">Select Ownership</option>
                                        <option value="Limited">Limited</option>
                                        <option value="Private Ltd">Private Ltd</option>
                                        <option value="LLP">LLP</option>
                                        <option value="Partnership">Partnership</option>
                                        <option value="Proprietor">Proprietor</option>
                                        <option value="Individual">Individual</option>
                                    </select>
                                    <p class="validation-message" id="constitution_error" style="color: red;"></p>
                                </td>
                            </tr>
                            <tr>
                                <td width="200">
                                    <label for="nature_of_business"><label class="compulsory">*</label> Nature Of
                                        Business/Business Category
                                    </label>
                                    <textarea type="text" id="nature_of_business" name="nature_of_business"
                                        class="form-control" placeholder="Nature Of Business"></textarea>
                                    <p class="validation-message" id="nature_of_business_error" style="color: red;"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">Billing / Owner Address</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200">
                                    <label for="state"><label class="compulsory">*</label> State </label>
                                    <input type="text" id="state" name="state" class="form-control" placeholder="State">
                                    <p class="validation-message" id="state_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="district"><label class="compulsory">*</label> District </label>
                                    <input type="text" id="district" name="district" class="form-control"
                                        placeholder="District">
                                    <p class="validation-message" id="district_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="pickup_location"><label class="compulsory">*</label> Pickup Location
                                    </label>
                                    <input type="text" id="pickup_location" name="pickup_location" class="form-control"
                                        placeholder="Location">
                                    <p class="validation-message" id="pickup_location_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="pincode"><label class="compulsory">*</label> Pincode </label>
                                    <input type="text" id="pincode" name="pincode" class="form-control"
                                        placeholder="Pincode">
                                    <p class="validation-message" id="pincode_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="customer_address"><label class="compulsory">*</label> Address
                                    </label>
                                    <textarea class="form-control" rows="2" cols="10" id="customer_address"
                                        name="customer_address" placeholder="Address"></textarea>
                                    <p class="validation-message" id="customer_address_error" style="color: red;"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">KYC Verification</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="300" style="text-align: center;">
                                    <label for="customer_pan_number"><label class="compulsory">*</label>
                                        Owner's/Director's PAN Number</label>
                                    <input type="text" id="customer_pan_number" name="customer_pan_number"
                                        class="form-control" placeholder="Owner PAN Number">
                                    <input type="hidden" name="pan_verify_status" id="pan_verify_status">
                                    <p class="validation-message" id="customer_pan_number_error" style="color: red;">
                                    </p>
                                    <a data-toggle='modal' href='#pan_modal' id="a_pan">
                                        <input type="button" class="btn btn-primary btn-sm" id="verify_pan_no"
                                            value="Verify PAN" onclick="verify_pan()">
                                    </a>
                                </td>
                                <td width="300" style="text-align: center;">
                                    <label for="aadhar_number"><label class="compulsory"></label> Owner's/Director's
                                        Aadhar Number
                                    </label>
                                    <input type="text" id="aadhar_number" name="aadhar_number" class="form-control"
                                        placeholder="Aadhar Number">
                                    <p class="validation-message" id="aadhar_number_error" style="color: red;"></p>
                                </td>
                                <td width="300" style="text-align: center;">
                                    <label for="gstin"><label class="compulsory"></label> GSTIN </label>
                                    <input type="text" id="gstin" name="gstin" class="form-control"
                                        placeholder="GST Number">
                                    <input type="hidden" name="gst_verify_status" id="gst_verify_status">
                                    <p class="validation-message" id="gst_error" style="color: red;"></p>
                                    <a data-toggle='modal' href='#gst_modal' id="a_gst">
                                        <input type="button" class="btn btn-primary btn-sm" id="verify_gst_no"
                                            value="Verify GST" onclick="verify_gst()">
                                    </a>
                                </td>
                                <td width="200"></td>
                                <td width="200"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">Contact Details</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200">
                                    <label for="name"><label class="compulsory">*</label> Owner's/Director's Mobile
                                        Number
                                    </label>
                                    <input type="text" id="customer_mobile_number" name="customer_mobile_number"
                                        class="form-control" placeholder="Mobile Number">
                                    <p class="validation-message" id="customer_mobile_number_error" style="color: red;">
                                    </p>
                                </td>
                                <td width="200">
                                    <label for="secondary_mobile_number"><label class="compulsory"></label> Secondary
                                        Mobile
                                        Number
                                    </label>
                                    <input type="text" id="secondary_mobile_number" name="secondary_mobile_number"
                                        class="form-control" placeholder="Secondary Mobile">
                                    <p class="validation-message" id="secondary_mobile_number_error"
                                        style="color: red;"></p>
                                </td>
                                <td width="250">
                                    <label for="email_id"><label class="compulsory"></label> Email ID </label>
                                    <input type="text" id="email_id" name="email_id" class="form-control"
                                        placeholder="Email">
                                    <p class="validation-message" id="email_id_error" style="color: red;"></p>
                                </td>
                                <td width="100"></td>
                                <td width="100"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">Approval Details</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200">
                                    <label for="point_status"><label class="compulsory">*</label>Point
                                        Status</label>
                                    <select id="point_status" name="point_status" class="form-control chosen-select">
                                        <option value="">Select Point Status</option>
                                        <option value="0">Activate</option>
                                        <option value="1">Deactivate</option>
                                    </select>
                                    <p class="validation-message" id="point_status_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="approve_status"><label class="compulsory">*</label>Approve
                                        Status</label>
                                    <select id="approve_status" name="approve_status"
                                        class="form-control chosen-select">
                                        <option value="">Select Approve Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Denied">Denied</option>
                                    </select>
                                    <p class="validation-message" id="approve_status_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="remarks"><label class="compulsory">*</label> Remarks </label>
                                    <textarea class="form-control" rows="2" cols="10" id="remarks" name="remarks"
                                        placeholder="Remarks"></textarea>
                                    <p class="validation-message" id="remarks_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="referred_by"><label class="compulsory"></label> Referred By </label>
                                    <textarea class="form-control" rows="2" cols="10" id="referred_by"
                                        name="referred_by" placeholder="Referred By"></textarea>
                                </td>
                                <td width="200">
                                    <label for="referrers_mobile_number"><label class="compulsory"></label> Referrer's
                                        Mobile Number
                                    </label>
                                    <input type="text" id="referrers_mobile_number" name="referrers_mobile_number"
                                        class="form-control" placeholder="Referrer's Mobile Number">
                                    <p class="validation-message" id="referrers_mobile_number_error"
                                        style="color: red;"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <input type="hidden" id="form_id" name="form_id" value="0">
                            <input type="hidden" id="cust_id" name="cust_id" value="0">
                            <input type="button" class="btn btn-danger" id="save_data" value="Save Data">
                            <!-- <p id="last_updated_by"></p> -->
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
                <!-- customer pan modal start -->
                <div id="pan_modal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                                <h3 class="modal-title">Customer PAN Verification</h3>
                                <div class="message_pan" style='display:none;'>
                                    <div style="padding: 7px;" class="alert alert-success" align="center"><a
                                            aria-hidden="true" href="components-popups.html#" data-dismiss="alert"
                                            class="close">×</a> <b>PAN
                                            Verified Successfully </b>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body pan_success_body">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label for="pan_verified">Verified Status</label>
                                        <input type="text" class="form-control" name="pan_verified" id="pan_verified"
                                            placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-8">
                                        <label for="pan_message_verify">Verification Message</label>
                                        <input type="text" class="form-control" name="pan_message_verify"
                                            id="pan_message_verify" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="pan_name_verify">PAN Name</label>
                                        <input type="text" class="form-control" name="pan_name_verify"
                                            id="pan_name_verify" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="pan_status_verify">PAN Status</label>
                                        <input type="text" class="form-control" name="pan_status_verify"
                                            id="pan_status_verify" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="pan_status_code">PAN Status Code</label>
                                        <input type="text" class="form-control" name="pan_status_code"
                                            id="pan_status_code" placeholder="" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body pan_error_body" style="display:none;">
                                <div class="row">
                                    <div style="padding: 7px;" align="center">
                                        <button type=" button" class="btn btn-primary" id="pan_error_msg"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer pan_footer">
                                <div class="" id="pan_loader" style="float:left;"></div>
                                <button type="button" class="btn btn-secondary" id="confirm_pan_no" data-dismiss=""
                                    onclick="confirm_pan()">Confirm</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- customer pan modal end -->

                <!-- gstin modal start -->
                <div id="gst_modal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
                                <h3 class="modal-title">GSTIN Verification</h3>
                                <div class="message_gst" style='display:none;'>
                                    <div style="padding: 7px;" class="alert alert-success" align="center"><a
                                            aria-hidden="true" href="components-popups.html#" data-dismiss="alert"
                                            class="close">×</a> <b>GSTIN
                                            Verified Successfully </b>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body gst_success_body">
                                <div class="row">
                                    <div class="form-group col-sm-5">
                                        <label for="gst_business_constitution">Business Constitution</label>
                                        <textarea class="form-control" name="gst_business_constitution"
                                            id="gst_business_constitution" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-7">
                                        <label for="gst_business_legal_name">Business Legal Name</label>
                                        <textarea class="form-control" name="gst_business_legal_name"
                                            id="gst_business_legal_name" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="gst_business_trade_name">Business Trade Name</label>
                                        <textarea class="form-control" name="gst_business_trade_name"
                                            id="gst_business_trade_name" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_centre_jurisdiction">Centre Jurisdiction</label>
                                        <textarea class="form-control" name="gst_centre_jurisdiction"
                                            id="gst_centre_jurisdiction" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_state_jurisdiction">State Jurisdiction</label>
                                        <textarea class="form-control" name="gst_state_jurisdiction"
                                            id="gst_state_jurisdiction" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_registration_date">Registration Date</label>
                                        <input type="text" class="form-control" name="gst_registration_date"
                                            id="gst_registration_date" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_tax_payer_date">Tax Payer Date</label>
                                        <input type="text" class="form-control" name="gst_tax_payer_date"
                                            id="gst_tax_payer_date" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_tax_payer_type">Tax Payer Type</label>
                                        <input type="text" class="form-control" name="gst_tax_payer_type"
                                            id="gst_tax_payer_type" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_status">GSTIN Status</label>
                                        <input type="text" class="form-control" name="gst_status" id="gst_status"
                                            placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_cancellation_date">Cancellation Date</label>
                                        <input type="text" class="form-control" name="gst_cancellation_date"
                                            id="gst_cancellation_date" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label for="gst_e-invoicing_status">E-Invoicing Status</label>
                                        <input type="text" class="form-control" name="gst_e-invoicing_status"
                                            id="gst_e-invoicing_status" placeholder="" value="" readonly>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="gst_business_activities">Nature Of Business
                                            Activities</label>
                                        <textarea class="form-control" name="gst_business_activities"
                                            id="gst_business_activities" placeholder="" value="" readonly></textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="gst_principal_address">Principal Place Address</label>
                                        <textarea class="form-control" name="gst_principal_address"
                                            id="gst_principal_address" placeholder="" value="" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body gst_error_body" style="display:none;">
                                <div class="row">
                                    <div style="padding: 7px;" align="center">
                                        <button type=" button" class="btn btn-primary" id="gst_error_msg"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer gst_footer">
                                <div class="" id="gst_loader" style="float:left;"></div>
                                <button type="button" class="btn btn-secondary" id="confirm_gst" data-dismiss=""
                                    onclick="confirm_gst()">Confirm</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- gstin modal end -->
            </div>
            <br />
            <br />
            <div class="portlet">
                <div class="portlet" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="portlet-title"><u>View RIC Single Customer Points</u></h3>
                    <a href="InstaCredit/exportCustDataRIC.php" class="btn btn-danger">Export</a>
                </div>
                <div id="customer_loader"></div>
                <div id="customer_output"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
$(document).ready(function() {
    view_customer();
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    $("#save_data").click(function() {
        var customer_name = $("#customer_name").val();
        var constitution = $("#constitution").val();
        var nature_of_business = $("#nature_of_business").val();
        var state = $("#state").val();
        var district = $("#district").val();
        var pickup_location = $("#pickup_location").val();
        var customer_address = $("#customer_address").val();
        var pincode = $("#pincode").val();
        var aadhar_number = $("#aadhar_number").val();
        var gstin = $("#gstin").val();
        var customer_pan_number = $("#customer_pan_number").val();
        var email_id = $("#email_id").val();
        var customer_mobile_number = $("#customer_mobile_number").val();
        var secondary_mobile_number = $("#secondary_mobile_number").val();
        var point_status = $("#point_status").val();
        var approve_status = $("#approve_status").val();
        var remarks = $("#remarks").val();
        var id = $("#form_id").val();
        var errors = false;

        if (customer_name === "") {
            $("#customer_name_error").text("Enter Customer Name");
            errors = true;
        } else {
            $("#customer_name_error").text("");
        }
        if (constitution === "") {
            $("#constitution_error").text("Select Constitution");
            errors = true;
        } else {
            $("#constitution_error").text("");
        }
        if (nature_of_business === "") {
            $("#nature_of_business_error").text("Enter Nature of Business");
            errors = true;
        } else {
            $("#nature_of_business_error").text("");
        }
        if (customer_address === "") {
            $("#customer_address_error").text("Enter Address");
            errors = true;
        } else {
            $("#customer_address_error").text("");
        }
        if (pincode === "") {
            $("#pincode_error").text("Enter Pincode");
            errors = true;
        } else {
            $("#pincode_error").text("");
        }
        if (state === "") {
            $("#state_error").text("Enter State");
            errors = true;
        } else {
            $("#state_error").text("");
        }
        if (district === "") {
            $("#district_error").text("Enter District");
            errors = true;
        } else {
            $("#district_error").text("");
        }
        if (pickup_location === "") {
            $("#pickup_location_error").text("Enter Pickup Location");
            errors = true;
        } else {
            $("#pickup_location_error").text("");
        }
        if (customer_mobile_number === "") {
            $("#customer_mobile_number_error").text("Enter Mobile Number");
            errors = true;
        } else {
            $("#customer_mobile_number_error").text("");
        }
        if (customer_pan_number === "") {
            $("#customer_pan_number_error").text("Enter Customer PAN Number");
            errors = true;
        } else {
            $("#customer_pan_number_error").text("");
        }
        if (point_status === "") {
            $("#point_status_error").text("Select Point Status");
            errors = true;
        } else {
            $("#point_status_error").text("");
        }
        if (approve_status === "") {
            $("#approve_status_error").text("Select Approve Status");
            errors = true;
        } else {
            $("#approve_status_error").text("");
        }
        if ((approve_status === "Pending" || approve_status === "Denied") && remarks.trim() === "") {
            $("#remarks_error").text("Enter Remarks");
            errors = true;
        } else {
            $("#remarks_error").text("");
        }
        if (errors) {
            return;
        }
        if (id == 0) {
            var user_name = <?php echo json_encode($user_name); ?>;
            $.ajax({
                url: "InstaCredit/AjaxReference/loadCustRegData.php?type=add_customer&user_name=" + user_name,
                type: "post",
                data: $("#customer_form").serialize(),
                beforeSend: function() {
                    $('#save_data').prop('disabled', true);
                },
                success: function(data) {
                    console.log(data);
                    if (data == '1') {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.success_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.success_alert').fadeOut('fast');
                        }, 3000);
                        $("#customer_form")[0].reset();
                        $("#form_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        view_customer();
                    } else {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.fail_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.fail_alert').fadeOut('fast');
                        }, 3000);
                        $("#customer_form")[0].reset();
                        $("#form_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        view_customer();
                    }
                }
            });
        } else {
            var user_name = <?php echo json_encode($user_name); ?>;
            $.ajax({
                url: "InstaCredit/AjaxReference/loadCustRegData.php?type=update_customer&user_name=" + user_name,
                type: "post",
                data: $("#customer_form").serialize(),
                beforeSend: function() {
                    $('#save_data').prop('disabled', true);
                },
                success: function(data) {
                    console.log(data);
                    if (data == '1') {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.update_success_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.update_success_alert').fadeOut('fast');
                        }, 3000);
                        $("#customer_form")[0].reset();
                        $("#form_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        $('#save_data').val("Save Data");
                        $('#referred_by').prop('readonly', false);
                        $('#referrers_mobile_number').prop('readonly', false);
                        view_customer();
                    } else {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.update_fail_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.update_fail_alert').fadeOut('fast');
                        }, 3000);
                        $("#customer_form")[0].reset();
                        $("#form_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        $('#save_data').val("Save Data");
                        $('#referred_by').prop('readonly', false);
                        $('#referrers_mobile_number').prop('readonly', false);
                        view_customer();
                    }
                }
            });
        }
    });

    $(document).on("click", ".edit_customer", function() {
        var id = $(this).data("id");
        var dataString = 'id=' + id + '&type=edit_customer';
        $.ajax({
            url: 'InstaCredit/AjaxReference/loadCustRegData.php',
            type: 'post',
            data: dataString,
            success: function(data) {
                //console.log(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    $("#form_id").val(obj[0].id);
                    $("#customer_id").val(obj[0].customer_id);
                    $("#customer_name").val(obj[0].customer_name);
                    $("#owner_directors_name").val(obj[0].owner_directors_name);
                    $("#constitution").val(obj[0].constitution).trigger(
                        "chosen:updated");
                    $("#nature_of_business").val(obj[0].nature_of_business);
                    $("#customer_address").val(obj[0].customer_address);
                    $("#pincode").val(obj[0].pincode);
                    $("#state").val(obj[0].state);
                    $("#district").val(obj[0].district);
                    $("#cust_id").val(obj[0].cust_id);
                    $("#pickup_location").val(obj[0].pickup_location);
                    $("#customer_mobile_number").val(obj[0].customer_mobile_number);
                    $("#secondary_mobile_number").val(obj[0].secondary_mobile_number);
                    $("#email_id").val(obj[0].email_id);
                    $("#customer_pan_number").val(obj[0].customer_pan_number);
                    $("#aadhar_number").val(obj[0].aadhar_number);
                    $("#gstin").val(obj[0].gstin);
                    $("#point_status").val(obj[0].point_status).trigger(
                        "chosen:updated");
                    $("#approve_status").val(obj[0].approve_status).trigger(
                        "chosen:updated");
                    $("#remarks").val(obj[0].remarks);
                    $("#referred_by").val(obj[0].referred_by);
                    if (obj[0].referred_by != "") {
                        $('#referred_by').prop('readonly', true);
                    } else {
                        $('#referred_by').prop('readonly', false);
                    }
                    $("#referrers_mobile_number").val(obj[0].referrers_mobile_number);
                    if (obj[0].referrers_mobile_number != "") {
                        $('#referrers_mobile_number').prop('readonly', true);
                    } else {
                        $('#referrers_mobile_number').prop('readonly', false);
                    }
                    $('#pan_verify_status').val(obj[0].pan_verify_status);
                    $('#gst_verify_status').val(obj[0].gst_verify_status);
                    if (obj[0].pan_verify_status == "verified") {
                        $('#verify_pan_no').removeClass('btn-primary').addClass(
                            'btn-secondary').val('Verified');
                        $('#verify_pan_no').prop('disabled', true);
                        $('#a_pan').removeAttr('href');

                    } else {
                        $('#verify_pan_no').removeClass('btn-secondary').addClass(
                            'btn-primary').val('Verify PAN');
                        $('#verify_pan_no').prop('disabled', false);
                        $('#a_pan').attr('href', '#pan_modal');
                    }
                    if (obj[0].gst_verify_status == "verified") {
                        $('#verify_gst_no').removeClass('btn-primary').addClass(
                            'btn-secondary').val('Verified');
                        $('#verify_gst_no').prop('disabled', true);
                        $('#a_gst').removeAttr('href');
                    } else {
                        $('#verify_gst_no').removeClass('btn-secondary')
                            .addClass(
                                'btn-primary').val('Verify GST');
                        $('#verify_gst_no').prop('disabled', false);
                        $('#a_gst').attr('href', '#gst_modal');
                    }
                }
                $('#save_data').val("Update Data");
            }
        });
    });

    $(document).on("click", ".delete_customer", function() {
        var user_name = <?php echo json_encode($user_name); ?>;
        if (confirm('Are you sure Want to Delete the Point') == false) {
            return false;
        }
        var del = $(this);
        var id = $(this).attr("data-id");
        $.ajax({
            url: "InstaCredit/AjaxReference/loadCustRegData.php?type=delete_customer&user_name=" + user_name,
            type: "post",
            data: {
                id: id
            },
            success: function() {
                del.closest("tr").hide();
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                $('.delete_alert').css('display', 'inline');
                setTimeout(function() {
                    $('.delete_alert').fadeOut('fast');
                }, 3000);
                view_customer();
            }
        });
    });

    $("#customer_name, #constitution, #nature_of_business, #state, #district, #pickup_location, #customer_address, #pincode, #customer_pan_number, #customer_mobile_number, #point_status, #approve_status,#remarks")
        .change(function() {
            var id = $(this).attr('id');
            var errorId = id + "_error";
            $("#" + errorId).text("");
        });

});

function view_customer() {
    $.ajax({
        url: "InstaCredit/AjaxReference/loadCustRegData.php?type=view_customer",
        type: "get",
        beforeSend: function() {
            $('#customer_loader').html('<img src="img/loading.gif" alt="Radiant.">');
        },
        success: function(data) {
            $('#customer_loader').hide();
            $("#customer_output").html(data);
            $("#customer_output table").DataTable({
                ordering: false
            });
        }
    });
}

//customer pan verification process start
function verify_pan() {
    var name = $("#customer_name").val();
    var number = $("#customer_pan_number").val();
    $('#pan_loader').html('<img src="img/loading.gif" alt="Radiant.">').show();
    $.ajax({
        url: "AceMobile/verification/pan_verify.php?name=" + name + "&number=" + number,
        type: "get",
        beforeSend: function() {
            $('#pan_modal .pan_success_body').hide();
            $('#pan_modal .pan_error_body').hide();
            $('#confirm_pan_no').prop('disabled', true);
            $('#pan_loader').html(
                '<img src="img/loading.gif" alt="Radiant." style="height: 40px; width: 40px;border-radius:50%">'
            );
        },
        success: function(data) {
            $('#pan_loader').hide();
            var response = JSON.parse(data);
            if (response.error) {
                $('.pan_error_body').css('display', 'inline');
                $('#pan_error_msg').text(response.error.message);
                $('#confirm_pan_no').prop('disabled', true);
            } else if (response.result.verified === false) {
                $('#confirm_pan_no').prop('disabled', true);
                $('.pan_error_body').css('display', 'inline');
                $('#pan_error_msg').text(response.result.message);
            } else {
                $('#pan_modal .pan_success_body').show();
                $('#confirm_pan_no').prop('disabled', false);
                $('.pan_error_body').css('display', 'none');
                var result = response.result;
                $('#pan_verified').val(result.verified);
                $('#pan_message_verify').val(result.message);
                $('#pan_name_verify').val(result.upstreamName);
                $('#pan_status_verify').val(result.panStatus);
                $('#pan_status_code').val(result.panStatusCode);
            }
        }
    });
}

function confirm_pan() {
    $('#confirm_pan_no').prop('disabled', true);
    $('.message_pan').css('display', 'inline');
    setTimeout(function() {
        $('.message_pan').fadeOut('fast');
    }, 3000);
    $('#pan_verify_status').val('verified');
    $('#verify_pan_no').removeClass('btn-primary').addClass('btn-secondary').val('Verified').prop(
        'disabled', true);
    $('#a_pan').removeAttr('href');
    $('.pan_error_body').css('display', 'none');
}
//customer pan verification process end

//gstin verification process start
function verify_gst() {
    var gstin = $("#gstin").val();
    $('#gst_loader').html('<img src="img/loading.gif" alt="Radiant.">').show();
    $.ajax({
        url: "AceMobile/verification/gst_verify.php?gstin=" + gstin,
        type: "get",
        beforeSend: function() {
            $('#gst_modal .gst_success_body').hide();
            $('#gst_modal .gst_error_body').hide();
            $('#confirm_gst').prop('disabled', true);
            $('#gst_loader').html(
                '<img src="img/loading.gif" alt="Radiant." style="height: 40px; width: 40px;border-radius:50%">'
            );
        },
        success: function(data) {
            $('#gst_loader').hide();
            var response = JSON.parse(data);
            if (response.error) {
                $('.gst_error_body').css('display', 'inline');
                $('#gst_error_msg').text(response.error.message);
                $('#confirm_gst').prop('disabled', true);
            } else {
                $('#gst_modal .gst_success_body').show();
                $('#confirm_gst').prop('disabled', false);
                $('.gst_error_body').css('display', 'none');
                var result = response.result.gstnDetailed;
                $('#confirm_gst').prop('disabled', false);
                $('#gst_business_constitution').val(result.constitutionOfBusiness);
                $('#gst_business_legal_name').val(result.legalNameOfBusiness);
                $('#gst_business_trade_name').val(result.tradeNameOfBusiness);
                $('#gst_centre_jurisdiction').val(result.centreJurisdiction);
                $('#gst_state_jurisdiction').val(result.stateJurisdiction);
                $('#gst_registration_date').val(result.registrationDate);
                $('#gst_tax_payer_date').val(result.taxPayerDate);
                $('#gst_tax_payer_type').val(result.taxPayerType);
                $('#gst_status').val(result.gstinStatus);
                $('#gst_cancellation_date').val(result.cancellationDate);
                $('#gst_business_activities').val(result.natureOfBusinessActivities);
                $('#gst_principal_address').val(result.principalPlaceAddress['address']);
                $('#gst_e-invoicing_status').val(result['e-invoicingStatus']);
            }
        }
    });
}

function confirm_gst() {
    $('#confirm_gst').prop('disabled', true);
    $('.message_gst').css('display', 'inline');
    setTimeout(function() {
        $('.message_gst').fadeOut('fast');
    }, 3000);
    $('#gst_verify_status').val('verified');
    $('#verify_gst_no').removeClass('btn-primary').addClass('btn-secondary').val('Verified').prop(
        'disabled', true);
    $('#a_gst').removeAttr('href');
    $('.gst_error_body').css('display', 'none');
}
//gstin verification process end
</script>