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
                <h3 class="portlet-title"> <u>OTP Master</u> </h3>
                <form id="otp_form">
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="otp_date"><label class="compulsory">*</label>Date</label>
                            <input type="text" id="popupDatepicker" value="<?php echo date('Y-m-d'); ?>" name="otp_date"
                                class="form-control">
                            <p class="validation-message" id="otp_date_error" style="color: red;"></p>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="otp_type"><label class="compulsory">*</label>OTP Type</label>
                            <select id="otp_type" name="otp_type" class="form-control chosen-select">
                                <option value="">Select OTP Type</option>
                                <option value="Login">Login</option>
                                <option value="Customer Register">Customer Register</option>
                                <option value="Staff Register">Staff Register</option>
                                <option value="Bank Account">Bank Account</option>
                                <option value="Bank Account Suspend">Bank Account Suspend</option>
                                <option value="Transaction">Transaction</option>
                                <option value="Forgot Pin">Forgot Pin</option>
                                <option value="Profile">Profile</option>
                            </select>
                            <p class="validation-message" id="otp_type_error" style="color: red;"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <input type="button" class="btn btn-danger" id="get_otp" value="Get Data">
                        </div>
                    </div>
                </form>
            </div>
            </br>
            </br>
            <div id="otp_loader"></div>
            <div id="otp_output"></div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
$(document).ready(function() {
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    $("#get_otp").click(function() {

        var date = $('#popupDatepicker').val();
        var otp_type = $('#otp_type').val();
        var errors = false;

        if (otp_type === "") {
            $("#otp_type_error").text("Select OTP Type");
            errors = true;
        } else {
            $("#otp_type_error").text("");
        }
        if (errors) {
            return;
        }
        $.ajax({
            url: "InstaCredit/AjaxReference/loadOtpMaster.php?&otp_type=" + otp_type + "&date=" + date,
            type: "get",
            beforeSend: function() {
                $('#otp_loader').html('<img src="img/loading.gif" alt="Radiant.">');
                $('#otp_loader').show();
            },
            success: function(data) {
                $('#otp_loader').hide();
                $("#otp_output").html(data);
                $("#otp_output table").DataTable({
                    ordering: false
                });
            }
        });
    });
    $("#otp_type")
        .change(function() {
            var id = $(this).attr('id');
            var errorId = id + "_error";
            $("#" + errorId).text("");
        });
});
</script>