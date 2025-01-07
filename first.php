<?php
@session_start();
require_once __DIR__ . '/../DbConnection/dbConnect.php';
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$url = $_REQUEST['url'];
include('CommonReference/date_picker_link.php');
$user=$_SESSION['lid'];
?>
<style type="text/css">
#id_proof_chosen,
#bank_acc_chosen,
#branch_id_chosen,
#emp_current_status_chosen,
#emp_bank_chosen {
    width: 100% !important;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class="portlet">
                <div class="portlet-body">
                    <h3 class="portlet-title"><u>FUNDING APPROVAL CONSOLE</u></h3>
                    <form action="" method="post" enctype="multipart/form-data" data-validate="parsley"
                        class="form parsley-form" name="frm" id="frm">
                        <div style="display:block;">


                            <!-- <div class="form-group col-sm-2">
            <label for="name">Date</label>
            <input required ="required" type="text" autocomplete ="off" id="popupDatepicker2" name="date" class="form-control parsley-validated" data-required="true" value="<?=date('Y-m-d');?>" tabindex="3">
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
                                    value="<?=date('Y-m-d');?>" tabindex="3">
                            </div>
                            <div class="form-group  col-sm-3">
                                <label for="name"><label class="compulsory">*</label> API TYPE </label>
                                <?php
                                $sql_user = mysqli_query($readConnection,"select * from login where user_name='".$user."' and
                                status='Allowed'");
                                $res_user = mysqli_fetch_array($sql_user);
                                $privileges_exp = explode(',', $res_user['other_permissions']);
                                ?>
                                <select name="api_type" class="form-control chosen-select">
                                    <?php
              $selected1 = $selected0 = "";
              // if (isset($api_type)) {
              //   if ($api_type == "0") {
              //     $selected0 = "selected";
              //   } else {
              //     $selected1 = "selected";
              //   }
              // }
              ?>
                                    <?php if(in_array('ACEMONEY',$privileges_exp)){ ?>
                                    <option <?php echo $selected0; ?> value="0">ACEMONEY</option>
                                    <?php } if(in_array('INSTACREDIT-IDFC',$privileges_exp)){ ?>
                                    <option <?php echo $selected1; ?> value="1">IDFC</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group  col-sm-3">
                                <button type="button" name="submit" id="submit" onclick="loadPointsAxis();"
                                    class="btn btn-danger search_btn" style="margin-top: 23px;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.portlet-body -->
        </div>
        <!-- /.portlet -->
    </div>
    <!-- /.col -->
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Acemoney Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="modal_data_txn"></span>
                    <h4 class="text-center alert alert-success" id="ace-result" style="display: none;"></h4>
                </div>
            </div>
        </div>
    </div>



    <div class="col-lg-12">
        <div id="load-points"></div>
    </div>
</div>
<!-- /.row -->
</div>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script>
$(document).ready(function() {
    // loadPointsAxis();
    $(".chosen-select").chosen({
        no_results_text: 'Oops, nothing found!'
    }, {
        disable_search_threshold: 10
    });
    $.validator.setDefaults({
        ignore: ":hidden:not(select)"
    });

});
$.validator.setDefaults({
    ignore: ":hidden:not(select)"
});

function loadPointsAxis() {
    var gdate = $("#popupDatepicker2").val();
    var api_type = $("select[name=api_type]").val();
    $.ajax({
        type: "GET",
        url: "InstaCredit/AjaxReference/load-ace-approval.php?gdate=" + gdate + "&api_type=" + api_type,
        data: {},
        success: function(exe) {
            $("#load-points").html(exe);
            $("#load-points table").DataTable({
                ordering: false
            });
        }
    });
}

function callApiAce() {
    console.log("LOG:");
    var purl = $("#confirmacebtn").attr("lurl");
    console.log("URL:" + purl);
    $("#confirmacebtn").prop("disabled", true);
    $("#confirmacebtn").html("Loading");
    $.ajax({
        type: "GET",
        data: {},
        url: purl,
        success: function(res) {
            $("#ace-result").show("fadein");
            $("#ace-result").html(res);
            setTimeout(function() {
                window.location = location.href;
            }, 2000);
        }
    });
}
</script>