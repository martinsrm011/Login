<?php
include ('CommonReference/date_picker_link.php');
$id = $_REQUEST['id'];
$user=$_SESSION['lid'];
$user_name=$_SESSION['lid'];
$per = $_SESSION['per'];
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class=" portlet">
                <h3 class="portlet-title"> <u>Account Master</u> </h3>
                <div class="success_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Account Added
                            Successfully</b>
                    </div>
                </div>
                <div class="fail_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-danger" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Account Failed To
                            Add</b>
                    </div>
                </div>
                <div class="update_success_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Account Updated
                            Successfully</b>
                    </div>
                </div>
                <div class="update_fail_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-danger" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Account Failed to
                            Update</b>
                    </div>
                </div>
                <div class="delete_alert" style='display:none;'>
                    <div style="padding: 7px;" class="alert alert-success" align="center"><a aria-hidden="true"
                            href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Account Deleted
                            Successfully </b>
                    </div>
                </div>
                <form id="account_form">
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="customer_id"><label class="compulsory">*</label>Customer ID</label>
                            <select id="customer_id" name="customer_id" class="form-control chosen-select">
                                <option value="">Select Customer ID</option>
                                <?php
                                $sql = "SELECT customer_id,customer_name FROM ace_cust_points WHERE status='Y'";
                                $qry = mysqli_query($readConnection,$sql);
                                while($row = mysqli_fetch_assoc($qry)){ ?>
                                <option value="<?php echo $row['customer_id']; ?>">
                                    <?php echo $row['customer_id'].' - '.$row['customer_name'];?>
                                </option>
                                <?php } ?>
                            </select>
                            <p class="validation-message" id="customer_id_error" style="color: red;"></p>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="bank_name"><label class="compulsory">*</label>Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="bank_name"
                                placeholder="Bank Name">
                            <p class="validation-message" id="bank_name_error" style="color: red;"></p>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="account_number"><label class="compulsory">*</label>Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="account_number"
                                placeholder="Account Number">
                            <p class="validation-message" id="account_number_error" style="color: red;"></p>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="ifsc_code"><label class="compulsory">*</label>IFSC Code</label>
                            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                placeholder="IFSC Code">
                            <p class="validation-message" id="ifsc_code_error" style="color: red;"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="branch_name"><label class="compulsory">*</label>Branch Name</label>
                            <input type="text" class="form-control" name="branch_name" id="branch_name"
                                placeholder="Branch Name">
                            <p class="validation-message" id="branch_name_error" style="color: red;"></p>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="account_type"><label class="compulsory">*</label>Account Type</label>
                            <select id="account_type" name="account_type" class="form-control chosen-select">
                                <option value="">Select Account Type</option>
                                <option value="Savings Account (SA)" data-other="Savings Account">Savings Account (SA)</option>
                                <option value="Current Account (CA)" data-other="Current Account">Current Account (CA)</option>
                                <option value="Cash Credit (CC)" data-other="Cash Credit">Cash Credit (CC)</option>
                                <option value="Overdraft (OD)" data-other="Overdraft">Overdraft (OD)</option>
                            </select>
                            <p class="validation-message" id="account_type_error" style="color: red;"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <input type="hidden" id="acc_id" name="acc_id" value="0">
                            <input type="button" class="btn btn-danger" id="add_account" value="Add Account">
                        </div>
                    </div>
                </form>
            </div>
            </br>
            </br>
            <div class="portlet">
                <div class="portlet" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="portlet-title"><u>View Account Master</u></h3>
                    <a href="#" class="btn btn-danger">Export</a>
                </div>
                <div id="account_loader"></div>
                <div id="account_output"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
$(document).ready(function() {
    view_account();
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    $("#add_account").click(function() {
        var customer_id = $("#customer_id").val();
        var bank_name = $("#bank_name").val();
        var account_number = $("#account_number").val();
        var ifsc_code = $("#ifsc_code").val();
        var branch_name = $("#branch_name").val();
        var account_type = $("#account_type").val();
        var id = $("#acc_id").val();
        var errors = false;

        if (customer_id === "") {
            $("#customer_id_error").text("Select Customer ID");
            errors = true;
        } else {
            $("#customer_id_error").text("");
        }
        if (bank_name === "") {
            $("#bank_name_error").text("Enter Bank Name");
            errors = true;
        } else {
            $("#bank_name_error").text("");
        }
        if (account_number === "") {
            $("#account_number_error").text("Enter Account Number");
            errors = true;
        } else {
            $("#account_number_error").text("");
        }
        if (ifsc_code === "") {
            $("#ifsc_code_error").text("Enter IFSC Code");
            errors = true;
        } else {
            $("#ifsc_code_error").text("");
        }
        if (branch_name === "") {
            $("#branch_name_error").text("Enter Branch Name");
            errors = true;
        } else {
            $("#branch_name_error").text("");
        }
        if (account_type === "") {
            $("#account_type_error").text("Select Account Type");
            errors = true;
        } else {
            $("#account_type_error").text("");
        }
        if (errors) {
            return;
        }
        if (id == 0) {
            var user_name = <?php echo json_encode($user_name); ?>;
            $.ajax({
                url: "InstaCredit/AjaxReference/loadAccountMaster.php?type=add_account&user_name=" + user_name,
                type: "post",
                data: $("#account_form").serialize(),
                beforeSend: function() {
                    $('#add_account').prop('disabled', true);
                },
                success: function(data) {
                    //console.log(data);
                    if (data == '1') {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                        $('.success_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.success_alert').fadeOut('fast');
                        }, 3000);
                        $("#account_form")[0].reset();
                        $("#acc_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#add_account').prop('disabled', false);
                        view_account();
                    }
                }
            });
        } else {
            var user_name = <?php echo json_encode($user_name); ?>;
            $.ajax({
                url: "InstaCredit/AjaxReference/loadAccountMaster.php?type=update_account&user_name=" + user_name,
                type: "post",
                data: $("#account_form").serialize(),
                beforeSend: function() {
                    $('#add_account').prop('disabled', true);
                },
                success: function(data) {
                    //console.log(data);
                    if (data == '1') {
                        // $('html, body').animate({
                        //     scrollTop: 0
                        // }, 'slow');
                        $('.update_success_alert').css('display', 'inline');
                        setTimeout(function() {
                            $('.update_success_alert').fadeOut('fast');
                        }, 3000);
                        $("#account_form")[0].reset();
                        $("#acc_id").val("0");
                        $(".chosen-select").trigger("chosen:updated");
                        $('#add_account').prop('disabled', false);
                        $('#add_account').val("Add Account");
                        view_account();
                    }
                }
            });
        }
    });
    $(document).on("click", ".edit_account", function() {
        var id = $(this).data("id");
        var dataString = 'id=' + id + '&type=edit_account';
        $.ajax({
            url: 'InstaCredit/AjaxReference/loadAccountMaster.php',
            type: 'post',
            data: dataString,
            success: function(data) {
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                var obj = JSON.parse(data);
                
                if (obj.length > 0) {
                    $("#customer_id").val(obj[0].customer_id).trigger("chosen:updated");
                    $("#bank_name").val(obj[0].bank_name);
                    $("#account_number").val(obj[0].account_number);
                    $("#ifsc_code").val(obj[0].ifsc_code);
                    $("#branch_name").val(obj[0].branch_name);
                   // $("#account_type").val(obj[0].account_type).trigger("chosen:updated");  
                    $("#acc_id").val(obj[0].id);
                }
                var optionToSelect = $('#account_type option[data-other="' + obj[0].account_type + '"]');

                 if (optionToSelect.length > 0) {
                       $('#account_type').val(optionToSelect.val()).trigger("chosen:updated");
                    }
                $('#add_account').val("Update Account");
            }
        });
    });
    $(document).on("click", ".delete_account", function() {
        var user_name = <?php echo json_encode($user_name); ?>;
        if (confirm('Are you sure Want to Delete the Point') == false) {
            return false;
        }
        var del = $(this);
        var id = $(this).attr("data-id");
        $.ajax({
            url: "InstaCredit/AjaxReference/loadAccountMaster.php?type=delete_account&user_name=" + user_name,
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
    $("#customer_id, #bank_name, #account_number, #ifsc_code, #branch_name, #account_type")
        .change(function() {
            var id = $(this).attr('id');
            var errorId = id + "_error";
            $("#" + errorId).text("");
        });
});

function view_account() {
    $.ajax({
        url: "InstaCredit/AjaxReference/loadAccountMaster.php?type=view_account",
        type: "get",
        beforeSend: function() {
            $('#account_loader').html('<img src="img/loading.gif" alt="Radiant.">');
        },
        success: function(data) {
            $('#account_loader').hide();
            $("#account_output").html(data);
            $("#account_output table").DataTable({
                ordering: false
            });
        }
    });
}
</script>