<?php
include ('CommonReference/date_picker_link.php');
@session_start();
date_default_timezone_set("Asia/Kolkata");
$pid=$_REQUEST['pid'];
$nav=$_REQUEST['nav'];
$id=$_REQUEST['id'];



$form_data = null;
if ($id != '') {
    $sql_form_data = mysqli_query($readConnection,"SELECT * FROM audit_form_details WHERE id = '$id'");
    if (mysqli_num_rows($sql_form_data) > 0) {
        $form_data = mysqli_fetch_assoc($sql_form_data);
    }
}


$file_directory='Audit_Document_uploads/';

?>
<style type="text/css">
#cust_name-error {
	left: 8px;
	position: absolute;
	top: 69px;
}

.message_cu {
  padding-left: 10%;
  padding-right: 10%;
}
/* #to_style_catehory{
    margin-left: 1px;
    
} */
#manual_category{
  width:22%;
}

</style>
<?php if ($nav != '') { ?>
    <div class="message_cu">
        <div style="padding: 15px;" class="alert <?php if ($nav == 2) {
                                                    echo 'alert-danger';
                                                } else {
                                                    echo 'alert-success';
                                                } ?>" align="center">
            <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
            <b>
                <?php
                $status_cu = array(1 => 'Record Saved Successfully', 2 => 'Sorry, Please Try Again',3=>'Record Updated Successfully',4=>'New Particular Name Added Successfully');
                echo $status_cu[$nav];
                ?>
            </b>
        </div>
    </div>
<?php } ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-11">
            <div class="portlet">
                <div class="portlet-body">
                    <h3 class="portlet-title"><u>AUDIT FORM UPLOAD</u></h3>

                    <form action="AuditDocuments/add_audit_details.php?pid=audit_form_upload" method="post" enctype="multipart/form-data" name="frm" id="demo-validation">
    <div class="row" id='hide_while_adding_particular'>
        
        <div class="form-group col-md-3 col-sm-6 col-xs-12">
            <label for="particular"><label class="compulsory">*</label>Particular</label>
            <select id="particular" name="particular" class="form-control parsley-validated chosen-select" tabindex="1">
                <option value="">Select Particular</option>
                <?php
                $sql_region = mysqli_query($readConnection,"SELECT category_id, category_name FROM audit_form_categories WHERE status ='Y'");
                while($res_region = mysqli_fetch_assoc($sql_region)) {
                    $selected = ($form_data && $res_region['category_id'] == $form_data['category_id']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $res_region['category_id']; ?>" <?php echo $selected; ?>>
                        <?php echo $res_region['category_name']; ?>
                    </option>
                <?php } ?>
                <option value="Others">New</option>
            </select>
        </div>

        
        <div class="form-group col-md-3 col-sm-6 col-xs-12">
            <label for="form_name"><label class="compulsory">*</label>Form Name</label>
            <textarea id="form_name" name="form_name" class="form-control" placeholder="Enter Form Name"><?php echo $form_data ? $form_data['particular_name'] : ''; ?></textarea>

        </div>

  
        <div class="form-group col-md-3 col-sm-6 col-xs-12">
        <label for="files"><label class="compulsory">*</label>Choose file</label>
        <input type="file" id="files" name="files" class="form-control">

        <?php if ($form_data && $form_data['file_path']) { ?>
            <p>Uploaded File: <a href="<?php echo $file_directory.$form_data['file_path']; ?>" target="_blank">View File</a></p>
            <!-- <p>If you want to replace the file, choose another file.</p> -->
        <?php } ?>
    </div>

    <?php if($id==''){?>

        <div class="form-group col-md-3 col-sm-6 col-xs-12 text-center">
            <button type="submit" name="submit" class="btn btn-danger search_btn" id="hrms_upload" style="margin-top: 23px;">Submit</button>
        </div>

    <?php } else {?>

        <div class="form-group col-md-3 col-sm-6 col-xs-12 text-center">
            <input type='hidden' value="<?php echo $id?>" name='id'>
            <button type="submit" name="submit" class="btn btn-danger search_btn" id="hrms_upload" style="margin-top: 23px;">Update</button>
        </div>

        <?php } ?>

   

    </div>
    <br>
    <div class="to_hide_particular">
    <label for="manual_category"><label class="compulsory">*</label>Add New Particular Name</label>
   
        <input type="text" id="manual_category" name="manual_category" class="form-control" placeholder="Enter Particular Name">
        
            <button type="button" class="btn btn-primary" id="addCategoryButton" style="margin-left: 24%;margin-top: -45px;">Add</button>
   
</div>
</form>

<br>

    <div class="clear"></div>
    <div id="view_sel_field"></div>
            </div>
                <!-- /.portlet-body -->
                <h3 class="portlet-title"> <u>All Form Details</u> </h3>
                <table class="table table-hover table-nomargin table-striped table-bordered to_load_data" width="100%">
        <thead>
            <tr>
				<th>S No</th>	
                <th>Particular Name</th>
                <th>Form Name</th>
                <th class=""><div align="center">View</div></th>
                <th class=""><div align="center">Update</div></th>
				<th class=""><div align="center">Delete</div></th>
               
            </tr>
        </thead>		
        <tbody>
        <?php
						$i = 1;
						
					
							$sql_query = mysqli_query($readConnection,"SELECT audit_form_details.id,audit_form_details.particular_name,audit_form_details.file_path,audit_form_categories.category_name FROM audit_form_details INNER JOIN audit_form_categories ON audit_form_categories.category_id=audit_form_details.category_id WHERE audit_form_categories.status='Y' AND audit_form_details.status='Y' ORDER BY audit_form_categories.category_name");
							while($res_data = mysqli_fetch_array($sql_query)) {
							?>
	                <tr id="delete_data_<?php echo $res_data['id'];?>" >
                        <td align="center"><?php echo $i;?></td>
                        <td><?php echo $res_data['category_name'];?></td>
                        <td><?php echo $res_data['particular_name'];?></td>
                        <td>
                            <div align="center">
                            <a href="<?php echo $file_directory.$res_data['file_path']; ?>" target="_blank">
								<span class="label label-secondary demo-element">View File</span>
                                </a>
                            </div>
						</td>
                        <td>
                            <div align="center">
                            <a href="./?pid=audit_form_upload&id=<?php echo $res_data['id']; ?>">
								<span class="label label-secondary demo-element">Update</span>
                                </a>
                            </div>
						</td>
                        
						<td>
							<div align="center">
							 <span onclick="removeData('<?php echo $res_data['id']; ?>')" class="label label-danger demo-element">Delete</span>
                            </div>
                        </td>
                       
                   </tr>
                   <?php
							$i++;
							}
						
						?>
            </tbody>
     </table>

            </div>
            <!-- /.portlet -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
function initializeDataTable(tableId) {
    $(tableId).DataTable({
        ordering: false,
        // paging: true,
        // searching: true
    });
}
</script>
<!-- /.container -->
<script type="text/javascript">
function removeData(delid)
{

    var r = confirm("Are you sure you want to delete this record?");
	if (r == true) {
   
    $.ajax({

            url: 'AuditDocuments/AjaxReference/add_audit_form_details.php', 
            type: 'POST',
            data: { id: delid,pid:'delete_data'},
            success: function(response) {
                
                var data = typeof response === 'string' ? JSON.parse(response) : response;
                if(data.status==true){
                    
                    $('#delete_data_'+delid).hide();
                }
                else
                {
                    
                }
            }
        });

    }

}
$(document).ready(function() {





    initializeDataTable(".to_load_data");
    $('.to_hide_particular').hide();
    $("#particular").change(function(){
        
      if($("#particular").val()=='Others'){
        $('.to_hide_particular').show();

        $('#hide_while_adding_particular').hide();
      }
      else
      {
        $('.to_hide_particular').hide();
        $('#hide_while_adding_particular').show();
      }
}); 
    setTimeout(function() {
      $('.message_cu').fadeOut('fast');
    }, 3000);

    $(".chosen-select").chosen({ no_results_text: 'Oops, nothing found!' });

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    var isUpdate = <?php echo ($form_data && $form_data['file_path']) ? 'true' : 'false'; ?>;

// Initialize validation
$("#demo-validation").validate({
    rules: {
        particular: {
            required: true,
            checkOthers: true 
        },
        form_name: {
            required: true
        },
        files:{
            required: true
        }
    },
    messages: {
        particular: {
            required: 'Please select a particular.',
            checkOthers: 'Add other particular name below' 
        },
        form_name: {
            required: 'Please enter a form name.'
        },
        files:{
            required: 'Please upload a file.',
        }
    },
    errorElement: "div",
    errorPlacement: function(error, element) {
        error.addClass('text-danger');

        if (element.attr("name") == "particular") {
            error.insertAfter(element.next('.chosen-container'));
        } else {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form) {
        var particularValue = $('#particular').val(); 
        if (particularValue === 'Others') {
            $('#particular').after('<div class="text-danger">"Others" is not allowed.</div>');
            return false;
        } else {
            form.submit(); 
        }
    }
});


$.validator.addMethod("checkOthers", function(value, element) {
    return value !== 'Others'; 
});

    $("#particular").on("change", function() {
        $(this).valid(); 
    });

$('#addCategoryButton').click(function() {
            var ParticularName = $('#manual_category').val();
            if (ParticularName === '') {
                alert('Please Enter a ParticularName.');
            } else {
                
                $.ajax({
                    url: 'AuditDocuments/AjaxReference/add_audit_form_details.php',
                    type: 'POST',
                    data: { category_name: ParticularName,pid:'Add_category'},
                    success: function(response) {
                        
                        var data = typeof response === 'string' ? JSON.parse(response) : response;
                        if(data.status==true){
                            window.location.href="./?pid=audit_form_upload&nav=4";
                        }
                        else
                        {
                            window.location.href="./?pid=audit_form_upload&nav=2";
                        }
                    }
                });
                
            }
        });




});
</script>
