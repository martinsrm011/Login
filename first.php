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
                <h3 class="portlet-title"> <u>RIC Shop Approval</u> </h3>
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
                <form id="funding_form">
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
                                <td width="200">
                                    <label for="cust_name"><label class="compulsory">*</label>Business / Shop /
                                        Customer Name</label>
                                    <input type="text" name="cust_name" class="form-control" id="cust_name"
                                        placeholder="Customer Name" readonly>
                                    <p class="validation-message" id="cust_name_error" style="color: red;"></p>
                                </td>

                                <td width="200">
                                    <label for="constitution"><label class="compulsory">*</label> Ownership </label>
                                    <input type="text" name="constitution" class="form-control" id="constitution"
                                        placeholder="Constitution" readonly>
                                    <p class="validation-message" id="constitution_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="cid">Customer ID</label>
                                    <input type="text" name="cid" id="cid" class="form-control" readonly>
                                </td>
                                <td width="200">
                                    <label for="nature_of_business"><label class="compulsory">*</label> Nature Of
                                        Business/Business Category
                                    </label>
                                    <textarea type="text" id="nature_of_business" name="nature_of_business"
                                        class="form-control" placeholder="Nature Of Business" readonly></textarea>
                                    <p class="validation-message" id="nature_of_business_error" style="color: red;"></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7">
                                    <div align="center">Location Details</div>
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
                                    <label for="location"><label class="compulsory">*</label> Location </label>
                                    <input type="text" id="location" name="location" class="form-control"
                                        placeholder="Location">
                                    <p class="validation-message" id="location_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="pincode"><label class="compulsory">*</label> Pincode </label>
                                    <input type="text" id="pincode" name="pincode" class="form-control"
                                        placeholder="Pincode">
                                    <p class="validation-message" id="pincode_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="address"><label class="compulsory">*</label> Pickup Address </label>
                                    <textarea class="form-control" rows="2" cols="10" id="address" name="address"
                                        placeholder="Address"></textarea>
                                    <p class="validation-message" id="address_error" style="color: red;"></p>
                                </td>
                            </tr>
                            <tr>
                                <td width="200">
                                    <label for="landmark"><label class="compulsory"></label> Landmark </label>
                                    <textarea class="form-control" rows="2" cols="10" id="landmark" name="landmark"
                                        placeholder="Landmark"></textarea>
                                    <p class="validation-message" id="landmark_error" style="color: red;"></p>
                                </td>
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
                                    <label for="name"><label class="compulsory">*</label> Owner Mobile </label>
                                    <input type="text" id="mobile_number" name="mobile_number" class="form-control"
                                        placeholder="Mobile Number" readonly>
                                    <p class="validation-message" id="mobile_number_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="owner_address"><label class="compulsory">*</label> Owner Address
                                    </label>
                                    <textarea class="form-control" rows="2" cols="10" id="owner_address"
                                        name="owner_address" placeholder="Owner Address" readonly></textarea>
                                    <p class="validation-message" id="owner_address_error" style="color: red;">
                                    </p>
                                </td>
                                <td width="210">
                                    <label for="store_manager_name"><label class="compulsory">*</label>Contact Name
                                    </label>
                                    <input type="text" name="store_manager_name" class="form-control"
                                        id="store_manager_name" placeholder="Contact Name">
                                    <p class="validation-message" id="store_manager_name_error" style="color: red;"></p>
                                </td>
                                <td width="200">
                                    <label for="store_manager_mobile"><label class="compulsory">*</label> Contact Mobile
                                    </label>
                                    <input type="text" id="store_manager_mobile" name="store_manager_mobile"
                                        class="form-control" placeholder="Store Manager Mobile">
                                    <p class="validation-message" id="store_manager_mobile_error" style="color: red;">
                                    </p>
                                </td>
                                <td width="200">
                                    <label for="secondary_mobile"><label class="compulsory"></label> Secondary Mobile
                                    </label>
                                    <input type="text" id="secondary_mobile" name="secondary_mobile"
                                        class="form-control" placeholder="Secondary Mobile">
                                    <p class="validation-message" id="secondary_mobile_error" style="color: red;"></p>
                                </td>
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
                                    <label for="point_id"><label class="compulsory">*</label> Point ID </label>
                                    <input type="text" name="point_id" class="form-control" id="point_id"
                                        placeholder="Point ID" readonly>
                                    <input type="hidden" name="location_id" class="form-control" id="location_id">
                                    <p class="validation-message" id="point_id_error" style="color: red;"></p>
                                    <button type="button" id="btn2" class="btn btn-secondary btn-sm gen_shop"
                                        style="float:right" id="gen_shop"><span id="upload-content1"
                                            style="display: none;">Generating..</span><span id="upload-loader"
                                            style="display: none;"><i class="fa fa-spinner fa-spin"></i></span><span
                                            id="upload-content">Generate</span></button>
                                </td>
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
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <input type="hidden" id="funding_id" name="funding_id" value="0">
                            <input type="hidden" id="cust_id" name="cust_id" value="">
                            <input type="button" class="btn btn-danger" id="save_data" value="Save Data">
                            <!-- <p id="last_updated_by"></p> -->
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
                <br />
                <br />
                <div class="portlet">
                    <div class="portlet" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="portlet-title"><u>View RIC Points</u></h3>
                        <a href="export-ace-point.php" class="btn btn-danger">Export</a>
                    </div>
                    <div id="funding_loader"></div>
                    <div id="funding_output"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
$("#point_id").blur(function() {
    var pointid = $("#point_id").val();
    $.ajax({
        url: 'InstaCredit/AjaxReference/ace-check-pointid.php',
        type: 'post',
        data: {
            'point_id': pointid
        },
        success: function(data) {
            if (data != "0") {
                alert("Duplicate Point ID");
                $("#point_id").val("");
            }
        }
    });
});

$(document).ready(function() {
    view_funding();
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    $("#save_data").click(function() {
        var cust_name = $("#cust_name").val();
        var constitution = $("#constitution").val();
        var nature_of_business = $("#nature_of_business").val();
        var state = $("#state").val();
        var district = $("#district").val();
        var location = $("#location").val();
        var address = $("#address").val();
        var landmark = $("#landmark").val();
        var pincode = $("#pincode").val();
        var mobile_number = $("#mobile_number").val();
        var owner_address = $("#owner_address").val();
        var store_manager_name = $("#store_manager_name").val();
        var store_manager_mobile = $("#store_manager_mobile").val();
        var secondary_mobile = $("#secondary_mobile").val();
        var point_id = $("#point_id").val();
        var point_status = $("#point_status").val();
        var approve_status = $("#approve_status").val();
        var remarks = $("#remarks").val();
        var id = $("#funding_id").val();
        var errors = false;

        if (cust_name === "") {
            $("#cust_name_error").text("Enter Customer Name");
            errors = true;
        } else {
            $("#cust_name_error").text("");
        }
        if (store_manager_name === "") {
            $("#store_manager_name_error").text("Enter Store Manager Name");
            errors = true;
        } else {
            $("#store_manager_name_error").text("");
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
        if (location === "") {
            $("#location_error").text("Enter Location");
            errors = true;
        } else {
            $("#location_error").text("");
        }
        if (address === "") {
            $("#address_error").text("Enter Address");
            errors = true;
        } else {
            $("#address_error").text("");
        }
        if (pincode === "") {
            $("#pincode_error").text("Enter Pincode");
            errors = true;
        } else {
            $("#pincode_error").text("");
        }
        if (store_manager_mobile === "") {
            $("#store_manager_mobile_error").text("Enter Store Manager Mobile Number");
            errors = true;
        } else {
            $("#store_manager_mobile_error").text("");
        }
        if (point_id === "") {
            $("#point_id_error").text("Enter Point ID");
            errors = true;
        } else {
            $("#point_id_error").text("");
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
                url: "ajax/load-ace-point-api.php?type=add_funding&user_name=" + user_name,
                type: "post",
                data: $("#funding_form").serialize(),
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
                        $("#funding_form")[0].reset();
                        $("#funding_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        view_funding();
                    } else {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.fail_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.fail_alert').fadeOut('fast');
                        }, 3000);
                        $("#funding_form")[0].reset();
                        $("#funding_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        view_funding();
                    }
                }
            });
        } else {
            var user_name = <?php echo json_encode($user_name); ?>;
            $.ajax({
                url: "ajax/load-ace-point-api.php?type=update_funding&user_name=" + user_name,
                type: "post",
                data: $("#funding_form").serialize(),
                beforeSend: function() {
                    $('#save_data').prop('disabled', true);
                },
                success: function(data) {
                    //console.log(data);
                    if (data == '1') {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.update_success_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.update_success_alert').fadeOut('fast');
                        }, 3000);
                        $("#funding_form")[0].reset();
                        $("#funding_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        $('#save_data').val("Save Data");
                        view_funding();
                    } else {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.update_fail_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.update_fail_alert').fadeOut('fast');
                        }, 3000);
                        $("#funding_form")[0].reset();
                        $("#funding_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#save_data').prop('disabled', false);
                        $('#save_data').val("Save Data");
                        view_funding();
                    }
                    $("#point_id").prop('readonly', false);
                    $('#btn2').show();
                }
            });
        }
    });
    $(document).on("click", ".edit_funding", function() {
        var id = $(this).data("id");
        var dataString = 'id=' + id + '&type=edit_funding';
        $.ajax({
            url: 'InstaCredit/AjaxReference/load-ace-point-api.php',
            type: 'post',
            data: dataString,
            success: function(data) {
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                var obj = JSON.parse(data);
                console.log(obj);
                if (obj.length > 0) {
                    $("#cust_name").val(obj[0].cust_customer_name);
                    if (obj[0].cust_customer_name != "") {
                        $('#cust_name').prop('readonly', true);
                    } else {
                        $('#cust_name').prop('readonly', false);
                    }
                    $("#cid").val(obj[0].customer_id);
                    $("#store_manager_name").val(obj[0].store_manager_name);
                    $("#constitution").val(obj[0].cust_constitution);
                    if (obj[0].cust_constitution != "") {
                        $('#constitution').prop('readonly', true);
                    } else {
                        $('#constitution').prop('readonly', false);
                    }
                    $("#nature_of_business").val(obj[0].cust_nature_of_business);
                    if (obj[0].cust_nature_of_business != "") {
                        $('#nature_of_business').prop('readonly', true);
                    } else {
                        $('#nature_of_business').prop('readonly', false);
                    }
                    $("#state").val(obj[0].state);
                    $("#district").val(obj[0].district);
                    $("#location").val(obj[0].location);
                    $("#address").val(obj[0].address);
                    $("#landmark").val(obj[0].landmark);
                    $("#pincode").val(obj[0].pincode);
                    $("#mobile_number").val(obj[0].customer_mobile_number);
                    $("#owner_address").val(obj[0].customer_address);
                    $("#store_manager_mobile").val(obj[0].store_manager_mobile);
                    $("#secondary_mobile").val(obj[0].secondary_mobile);
                    $("#approve_status").val(obj[0].approve_status).trigger(
                        "chosen:updated");
                    $("#point_status").val(obj[0].act_status).trigger(
                        "chosen:updated");
                    $("#remarks").val(obj[0].remarks).trigger(
                        "chosen:updated");
                    $("#funding_id").val(obj[0].id);
                    $("#point_id").val(obj[0].points);
                    $('#location_id').val(obj[0].location_id);
                    if (obj[0].points != "") {
                        $('#point_id').prop('readonly', true);
                        $('#btn2').hide();
                    } else {
                        $('#point_id').prop('readonly', false);
                        $('#btn2').show();
                    }
                    $("#cust_id").val(obj[0].cust_id);

                }
                $('#save_data').val("Update Data");
            }
        });
    });
    $(document).on("click", ".delete_funding", function() {
        var user_name = <?php echo json_encode($user_name); ?>;
        if (confirm('Are you sure Want to Delete the Point') == false) {
            return false;
        }
        var del = $(this);
        var id = $(this).attr("data-id");
        $.ajax({
            url: "InstaCredit/AjaxReference/load-ace-point-api.php?type=delete_funding&user_name=" + user_name,
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
                view_funding();
            }
        });
    });

    $("#cust_name, #store_manager_name, #constitution, #nature_of_business, #state, #district, #location, #address, #pincode, #pan, #mobile_number, #owner_address, #store_manager_mobile, #point_id, #pickup_time, #point_status, #approve_status, #remarks")
        .change(function() {
            var id = $(this).attr('id');
            var errorId = id + "_error";
            $("#" + errorId).text("");
        });
});

function view_funding() {
    $.ajax({
        url: "InstaCredit/AjaxReference/load-ace-point-api.php?type=view_funding",
        type: "get",
        beforeSend: function() {
            $('#funding_loader').html('<img src="img/loading.gif" alt="Radiant.">');
        },
        success: function(data) {
            $('#funding_loader').hide();
            $("#funding_output").html(data);
            $("#funding_output table").DataTable({
                ordering: false
            });
        }
    });
}

$(document).on("click", ".gen_shop", function() {
    var name = $('#cust_name').val();
    var add = $('#owner_address').val();
    var mob = $('#mobile_number').val();
    var cust_id = $('#cust_id').val();

    if (cust_id.trim() == "" || cust_id.trim() == 0) {
        alert("To Gerate Point ID Customer ID is mandatory");
        return false;
    }
    if (confirm('Are you sure Want to Add the Point') == false) {
        return false;
    }
    $('#upload-loader').show();
    $('#upload-content1').show();
    $('#upload-content').hide();
    $('#btn2').prop('disabled', true);
    $.ajax({
        url: "InstaCredit/AjaxReference/load-ace-point-api.php?type=add_shop",
        type: "POST",
        data: $("#funding_form").serialize(),
        success: function(data) {
            console.log(data);
            var res = JSON.parse(data);
            if (res.code != 0) {
                $('#point_id').prop('readonly', true);
                $('#point_id').val(res.shop_id);
                $('#btn2').hide();
                view_funding();
            } else {
                alert("Falied to create Point ID.Try Again!");
            }
            $('#upload-loader').hide();
            $('#btn2').prop('disabled', false);
            $('#upload-content1').hide();
            $('#upload-content').show();

        }
    });

});
</script>