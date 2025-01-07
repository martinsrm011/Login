<?php
@session_start();
require_once __DIR__ . '/../DbConnection/dbConnect.php';
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$url = $_REQUEST['url'];
include('CommonReference/date_picker_link.php');
$user = $_SESSION['lid'];
?>
<style type="text/css">
#id_proof_chosen,
#bank_acc_chosen,
#branch_id_chosen,
#emp_current_status_chosen,
#emp_bank_chosen {
    width: 100% !important;
}

table,
th,
td {
    text-align: center;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class="portlet">
                <div class="portlet-body">
                    <h3 class="portlet-title"><u>Collection Transaction</u></h3>
                    <form method="post" enctype="multipart/form-data" data-validate="parsley" class="form parsley-form"
                        name="frm" id="frm">
                        <div style="display:block;">


                            <!-- <div class="form-group col-sm-2">
            <label for="name">Date</label>
            <input required ="required" type="text" autocomplete ="off" id="popupDatepicker2" name="date" class="form-control parsley-validated" data-required="true" value="<?= date('Y-m-d'); ?>" tabindex="3">
          </div> -->
                            <!-- <div class="form-group  col-sm-4">
		  <label>Upload file<span class="f_req"> *</span></label>
		  <input type="file" name="uploadfile" placeholder="Upload document" title="Upload document" required="">
      <br>
      <a href="acepoints/sample.xls">Download sample</a>
          </div>  
          <div class="form-group col-sm-2 mt-3">
            <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn" style="margin-top: 23px;">Upload</button>
          </div> -->

                        </div>
                        <div class="row  form-group  col-sm-12">
                            <div class="form-group  col-sm-3">
                                <label for="name">Date</label>
                                <input required="required" type="text" autocomplete="off" id="popupDatepicker2"
                                    name="date" class="form-control parsley-validated" data-required="true"
                                    value="<?= date('Y-m-d'); ?>" tabindex="3">
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="">API Type</label>
                                <?php
                                $sql_user = mysqli_query($readConnection,"select * from login where user_name='" . $user . "' and status='Allowed'");
                                $res_user = mysqli_fetch_array($sql_user);
                                $privileges_exp = explode(',', $res_user['other_permissions']);
                                ?>
                                <select id="api_type" name="api_type" required="required"
                                    class="form-control parsley-validated chosen-select" data-required="true"
                                    tabindex="7">
                                    <option value="">Select API Type</option>
                                    <?php if (in_array('ACEMONEY', $privileges_exp)) { ?>
                                    <option value="0">ACEMONEY</option>
                                    <?php }
                                    if (in_array('INSTACREDIT-IDFC', $privileges_exp)) { ?>
                                    <option value="1">IDFC</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group  col-sm-3">
                                <button type="button" name="submit" id="submit" class="btn btn-danger search_btn"
                                    style="margin-top: 23px;" onclick="get_error();">Load Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="view_statuss"></div>
            <!-- /.portlet-body -->
        </div>
        <!-- /.portlet -->
    </div>
    <!-- /.col -->
    <!-- Button trigger modal -->



    <div class="col-lg-12">
        <div id="load-points"></div>
    </div>
</div>
<!-- /.row -->
</div>

<script>
$(document).ready(function() {
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
});

function get_error() {
    var api_type = $("#api_type").val();
    var trans_date = $("#popupDatepicker2").val();
    if (api_type == '') {
        alert('Select Api Type');
        return;
    } else {

        $.ajax({
            type: "POST",
            data: {
                trans_date: trans_date,
                api_type: api_type,
                type: "view_collection_data"
            },
            url: "InstaCredit/AjaxReference/view_instacredit_data.php",
            beforeSend: function() {
                $('#view_statuss').html('<img src="img/loading.gif" alt="Radiant.">');
            },
            success: function(data) {
                //alert(data);
                $('#view_statuss').html(data);
            }
        });
    }
}

var statusOnProgress = false;

function check_status(button) {
    if (statusOnProgress == true) {
        alert("Check Status after the previous Request is Done");
        return false;
    }
    if (!confirm('Are you sure you want to check the status?')) {
        return false;
    }
    var data_id = button.getAttribute("data-id");

    const up_loader = button.querySelector('#upload-loader');
    const up_content1 = button.querySelector('#upload-content1');
    const up_content = button.querySelector('#upload-content');
    const but_text = document.getElementById("success-text" + data_id);

    if (up_loader && up_content1 && up_content) {
        up_loader.style.display = 'inline-block';
        up_content1.style.display = 'inline-block';
        up_content.style.display = 'none';
        button.disabled = true;
    }

    statusOnProgress = true;

    $.ajax({
        url: "InstaCredit/AjaxReference/view_instacredit_data.php",
        type: "POST",
        data: {
            data_id: data_id,
            type: "notification_status"
        },
        success: function(data) {
            // console.log(data);
            var arr_data = JSON.parse(data);
            alert(arr_data['status']);
            if (arr_data['code'] == "000") {
                button.style.display = 'none';
                $("#success-text" + data_id).show();
                $("#trans_status" + data_id).html('APPROVED');
            } else {
                up_loader.style.display = 'none';
                up_content1.style.display = 'none';
                up_content.style.display = 'inline-block';
                button.disabled = false;
            }
            statusOnProgress = false;

        }
    });

}
</script>