<?php
@session_start();
include ('CommonReference/date_picker_link.php');
$user=$_SESSION['lid'];
$region = $_SESSION['region'];
$per = $_SESSION['per'];
$sql_user = mysqli_query($readConnection,"select * from login where user_name='".$user."' and status='Allowed'");
$res_user = mysqli_fetch_array($sql_user);
$login_region_exp = explode(',', $res_user['region']);
$login_region = $res_user['region'];
?>


<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class=" portlet">
                <h3 class="portlet-title"> <u>RIC Mobile Login PIN</u> </h3>
                <div class="clear"></div>
                <div id="pin_loader"></div>
                <div id="pin_output"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
$(document).ready(function() {
    view_pin();
});

function view_pin() {
    $.ajax({
        url: "InstaCredit/AjaxReference/load_aceMobilePin.php?type=view_pin",
        type: "get",
        beforeSend: function() {
            $('#pin_loader').html('<img src="img/loading.gif" alt="Radiant.">');
        },
        success: function(data) {
            $('#pin_loader').hide();
            $("#pin_output").html(data);
            $("#pin_output table").DataTable({
                ordering: false
            });
        },
        error: function(xhr, status, error) {
            $('#pin_loader').hide();
            $("#pin_output").html('Error:' + error);
        }
    });
}
</script>