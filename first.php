<?php 
include('../DbConnection/dbConnect.php');
include('CommonReference/date_picker_link.php');
$pid = $_REQUEST['pid'];
$nav = $_REQUEST['nav'];
?>

<link rel="stylesheet" type="text/css" href="css/bootstrap-multiselect.css" />
<script type="text/javascript" src="js/bootstrap-multiselect.js" ></script>
<div class="container">
	<div class="row">
        <div class="col-md-12 col-sm-11">
        	<div class="portlet">	    
            <h3 class="portlet-title"><u>Daily CashVan Details</u></h3>
                
            	<?php if($nav!='' ) { 	?>
               	<div class="message_cu">
              		<div style="padding: 7px;" class="alert <?php if($nav=='2_2_1' || $nav=='2_2_2' || $nav=='2_2_3' || $nav=='2_2_4') { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center">
                  		<a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
                  		<b><?php
						  $status_cu = array('2_1_1'=>'New Cash Van Details Saved',
											 '2_1_2'=>'Transaction Updated Successfully',
											 '2_1_3'=>'Record Deleted', 
											 '2_3_6'=>'Crew Assigned Successfully',
											 '2_2_4'=>'Sorry, Please Try Again'
											 );
						  echo $status_cu[$nav];
						  ?> </b>
                          <br />
                          
                    </div>
                </div>
              <?php } ?>	
				<div id="display_div">		
                    <form name="frmForm" id="frmForm" action="#" >
                        <table border="0" cellspacing="2" cellpadding="2" width="40%">
                            <div class="form-group col-sm-2">
                                <label for="name"><label class="compulsory">*</label>Date</label>
                                <input type="text" id="popupDatepicker" name="date" class="form-control parsley-validated" data-required="true" placeholder=" Date" value="<?php echo date('d-m-Y');?>" tabindex="1" >
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="name"><label class="compulsory">*</label> Select Client</label>
                                <select id="client" name="client" class="form-control parsley-validated chosen-select" data-required="true" tabindex="2" >
                                    <option value="">Select Client</option>
                                </select>
                            </div>
                           
                            <div class="form-group col-sm-1">
                                <button type="button" name="submit" id="submit" class="btn btn-danger search_btn" onClick="search_key(1, 0)"  tabindex="3" style="margin-top:31px">View </button>
                            </div>
                        </table>
                    </form>
                </div>
       		</div>
    	</div>
    </div>
    <div class="portlet">
        <div class="clear"></div>
        <div class="portlet-body"><br />
            <div id="view_status"></div>
            <?php //include("search_field.php"); ?>
            <div id="view_details_indu"></div>
        </div>
        <!-- Pouup Gunman-->
       	<div id="basicModal" class="modal fade" >
       		<div class="modal-dialog" style="width:1140px">
            	<div class="modal-content">
                	<div id="basicModal_view" style="width:950px;margin-left:20px;margin-top:10px"></div>
                </div>
            </div>
        </div>
       <!-- Pop up -Ends -->
    </div>  
</div>


<script>
$(document).ready(function(){

	//initializeDataTable("#trans_table");
	$(document).on('change', '#vehicle_type_tra', function() {
        console.log("Change event triggered"); 
        var selectedValue = $(this).val();
        console.log("Selected value: " + selectedValue); 

        if (selectedValue == 'Hired') {
            $('.vehicle_regni_type').show();
            $('.vehicle_model_asv').show();
            $('.cashvan_rbi').show();
            $('.cashvan_ac_asig').show();
			$("#cv_div").hide();
			$("#Second_button").show();
			$("#First_button").hide();
			$("#to_hide_cvdetails").hide();

			

			$('#vehicle_no option:selected').removeAttr('selected');
            $('#vehicle_no').val('');
            $('#vehicle_no').trigger("chosen:updated");


			$('#cv_details').html('');


        } else {
            $('.vehicle_regni_type').hide();
            $('.vehicle_model_asv').hide();
            $('.cashvan_rbi').hide();
            $('.cashvan_ac_asig').hide();
			$("#cv_div").show();
			$("#Second_button").hide();
			$("#First_button").show();
			$("#to_hide_cvdetails").show();

			$('#vec__reg_no_asi').val('');
			$('#vec_model_sv').val('');
			$('#vec_model_sv').val('');
			$('#guide_rbi').val('');
			$('#cashvan_ac').val('');

			$('#guide_rbi option:selected').removeAttr('selected');
            $('#guide_rbi').val('');
            $('#guide_rbi').trigger("chosen:updated");  


			$('#cashvan_ac option:selected').removeAttr('selected');
            $('#cashvan_ac').val('');
            $('#cashvan_ac').trigger("chosen:updated");  

			//$('#to_hide_cvdetails').show();  
        }
		document.getElementById('vec__reg_no_asi').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    });



$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});	
	
	setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		load_client();
});
function load_client(){
	$.ajax({
		url:"CashVan/AjaxReference/GetAllDetail.php",
		type:"POST",
		data:{pid:"View_Transaction_Logs",clients:"clients"},
		success: function(result){
			var res = $.parseJSON(result);
			$('#client').html(res['row_client']);
			$('#client').trigger("chosen:updated");
		}		
	});
}

function search_key (search_type, page_start) {

    if($('#popupDatepicker').val()==''){
		alert('Please Select Date');
		return false;
	}
	else if($('#client').val()==''){
		alert('Please Select Client');
		return false;
	}
	
	if($('#keyword').val()!='' || $('#search').val()=='unmapce') {
		$('#keyword').removeClass('error_dispaly');
		tbl_search = '';
		if(search_type==1) {
			tbl_search = '';
			$('#tbl_search').val('');
			$.ajax({
				type: "POST",
				url: "CashVan/AjaxReference/CashVanLoadData.php",
				data : 'types=1&pid=View_Transaction_Logs&date='+$('#popupDatepicker').val()+'&client='+$('#client').val()+'&tbl_search='+$('#tbl_search').val(),
				success: function(msg){
					$('#view_status').html(msg);
					$("#trans_table").DataTable({ ordering: false});
// 					setTimeout(function() {
// 						$("#trans_table").DataTable({ ordering: false});
// }, 100);


				}

			});
		}
		else {
			tbl_search = $('#tbl_search').val();
		}
		$.ajax({
			type: "POST",
			url: "CashVan/AjaxReference/CashVanLoadData.php",				
			data: 'pgn=1&start_limit='+page_start+'&tbl_search='+tbl_search+'&per_page='+$('#per_page').val()+'&end_limit=10&types=2&load=1&pid=View_Transaction_Logs&search='+$('#search').val()+'&keyword='+$('#keyword').val()+'&date='+$('#popupDatepicker').val()+'&client='+$('#client').val(),
			beforeSend: function(){				
				$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
			},
			success: function(msg){
				$('#view_details_indu').html(msg);
				$('.search_field').css('display', '');

				$("#trans_table").DataTable({ ordering: false});
			}
		});
	}
	else {
		$('#keyword').addClass('error_dispaly');
	}
}



// $('#vehicle_type_tra').on('change', function() {
// 	alert("hii");
//     var selectedValue = $(this).val();
// 	if(selectedValue=='Hired'){

// 		$('#vehicle_regni_type').show();
// 	 $('#vehicle_model_asv').show();
// 	 $('#cashvan_rbi').show();
// 	 $('#cashvan_ac_asig').show();
// 	}
// 	else
// 	{
// 		$('#vehicle_regni_type').hide();
// 	    $('#vehicle_model_asv').hide();
// 	    $('#cashvan_rbi').hide();
// 	    $('#cashvan_ac_asig').hide();
// 	}

                
// });

function assign_van(obj){
	var serv_id = $(obj).attr('rel');
	var datey = $('#popupDatepicker').val(); 
	$.ajax({
		url:"CashVan/AjaxReference/GetAllDetail.php",
		type:"post",
		data:{pid:"View_Transaction_Logs",service_id:serv_id,dates:datey},
		success:function(msg){
			console.log(msg);
			$('#basicModal_view').html(msg);
			$("#basicModal_view .chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});	
			$("#basicModal_view .chosen-container").css('width','200px');
			$("#basicModal_view  .multisel").multiselect({
				enableFiltering: true,
				buttonWidth:'150px',
				maxHeight: 200,
				inheritClass: true,
				nonSelectedText: 'Select'
			});


		}
	});
	

}

function add_van(obj){
	var sid = $('#crew_form').find('#sid').val();
	var service_date = $('#crew_form').find('#service_date').val();
	var gun1 = $('#crew_form').find('.checks_gunman').val();
	var drive1 = $('#crew_form').find('.checks_driver').val();
	var load1 = $('#crew_form').find('.checks_loader').val();
	var ce1 = $('#crew_form').find('.checks_ce').val();
	var drive1 = $('#driver').val();
	var load1 = $('#loader').val();
	var ce1 = $('#ce').val();

	if($('#vehicle_no').val()==''){

		alert('Please select cashvan');
		return false;
	}
	
	$.ajax({
		url:"CashVan/AjaxReference/GetAllDetail.php",
		type:"post",
		data:"pid=View_Transaction_Logs&vehicle_no="+$('#crew_form').find('#vehicle_no').val()+"&gunman="+gun1+"&driver="+$('#crew_form').find('.checks_driver').val()+"&loader="+$('#crew_form').find('.checks_loader').val()+"&ce="+$('#crew_form').find('.checks_ce').val()+"&form_value=1"+"&sid="+sid+"&service_date="+service_date,
		success:function(result){
			console.log(result);
			var res = $.parseJSON(result);
			var htm = '<div align="center"><a href="./?pid=View_Logs&id='+res['sid']+'" target="_blank"><i class="fa fa-remove notavailable_trans" ></i></a></div>';
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#'+res['td_id']).html(htm);
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#assign_text'+res['sid']).html('Assigned Already');
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#veh_td'+res['sid']).append(res['cv']);
			//veh_td
			$('#crew_form').find('#close').trigger('click');
		}
	});

}
function count_man(obj){
	cnt=0;
	var type = $(obj).attr('rel');
	var type1 = type.split("_");
	if($(obj).val()!=''){
		cnts= $('.checks_'+type1[1]+' :selected').length;
		$('#'+type1[1]+'_no').text('');
		$('#'+type1[1]+'_no').text(cnts);
	}else{
		$('#'+type1[1]+'_no').text('0');
	}
}
function get_van_details(obj){
	var id = $('#id').val();
	var van_id = $(obj).val();
	$.ajax({
		type: "POST",
		url: "CashVan/AjaxReference/GetAllDetail.php",
		data: 'pid=Service_Map&id='+id+'&van_id='+van_id,
		success: function(msg){
			$(obj).closest('tr').find('#cv_details').html(msg);
		}
	});
}
function delete_datay(delate_id, del_tab){
	var r = confirm("Are you sure you want to delete this record?");
	if (r == true) {
		$.ajax({
			type: "POST",
			url: "CashVan/AjaxReference/CashVanLoadData.php",
			data: 'pid=View_Transaction_Logs&delate_id='+delate_id+'&del_tab='+del_tab+'&types=3',
			success: function(msg){	
			var res= $.parseJSON(msg);
				if(res['msg']=='Suc') {	
					$('#delete_data'+delate_id).remove();
				}
			}
		});
	}
}


function AddRow(obj){
	var id=$(obj).attr('id')+"1";
	var data = "<tr>"+$("#"+id+" tbody tr:first").html()+"</tr>";
	$("#"+id+" tbody").append(data);
	var len = $('#'+id+' tbody tr').length;
	//alert(len);
	var len1 = len-1;
	//gunman
	var namey1 = $('#'+id+' tr').eq(len1).find('#gunman').attr('name');
	var namey11 = parseInt(namey1.match(/\d+/))+1;
	$('#'+id+' tr').eq(len).find('#gunman').attr('name','gunman['+namey11+'][]');
	
	//driver
	var namey2 = $('#'+id+' tr').eq(len1).find('#driver').attr('name');
	var namey22 = parseInt(namey2.match(/\d+/))+1;
	$('#'+id+' tr').eq(len).find('#driver').attr('name','driver['+namey22+'][]');
	
	//Loader
	var namey3 = $('#'+id+' tr').eq(len1).find('#loader').attr('name');
	var namey33 = parseInt(namey3.match(/\d+/))+1;
	$('#'+id+' tr').eq(len).find('#loader').attr('name','loader['+namey33+'][]');
	
	//gunman
	var namey4 = $('#'+id+' tr').eq(len1).find('#ce').attr('name');
	var namey44 = parseInt(namey4.match(/\d+/))+1;
	$('#'+id+' tr').eq(len).find('#ce').attr('name','ce['+namey44+'][]');
	
	clear_row(id);
}

function clear_row(id){
	$("#"+id+" tbody tr:last").find('input[type=text]').attr('value','');
	$("#"+id+" tbody tr:last").find('.hidden').attr('value','');
	$("#"+id+" tbody tr:last").find('select').val('');
	$("#"+id+" tbody tr:last").find('input[type=checkbox]').attr('value','0');
	$("#"+id+" tbody tr:last").find('input[type=checkbox]').removeAttr("disabled");
	$("#"+id+" tbody tr:last").find('.disableClick').removeClass('disableClick');	
	$("#"+id+" tbody tr:last").find('textarea').val('');
	$("#"+id+" tbody tr:last").find('td:last a').attr('rel','0%0');		
}

function delete_row(obj){
	var len = $('#emp_edu1 tbody tr').length;
	if(len == 1){
		var new_tr = "<tr>"+$('#emp_edu1 tbody tr:first').html()+"</tr>";
		$(obj).closest('tr').remove();
		$("#emp_edu1 tbody").append(new_tr);
		clear_row(emp_edu1);
	}else{
		$(obj).closest('tr').remove();
	}
}

function add_van_hired(obj){

	var  vehicle_type_new=$('#vehicle_type_tra').val();
	var vehicle_regno_new=$('#vec__reg_no_asi').val();
	var vehicle_model_new=$('#vec_model_sv').val()
	var rbiguide_vals=$('#guide_rbi').val();
	var cashvantype_new=$('#cashvan_ac').val();

	//
	var sid = $('#crew_form').find('#sid').val();
	var service_date = $('#crew_form').find('#service_date').val();
	var gun1 = $('#crew_form').find('.checks_gunman').val();
	var drive1 = $('#crew_form').find('.checks_driver').val();
	var load1 = $('#crew_form').find('.checks_loader').val();
	var ce1 = $('#crew_form').find('.checks_ce').val();
	var drive1 = $('#driver').val();
	var load1 = $('#loader').val();
	var ce1 = $('#ce').val();

	if(vehicle_type_new=='')
    {
		alert("Vehicle Type is required");
		return false;
    }
	else if(vehicle_regno_new=='')
    {
        
		alert("Vehicle Registration No is Required");
		return false;
    }
	else if(vehicle_model_new=='')
	{
		alert("Vehicle Model No is Required");
		return false;
	}

	$.ajax({
		url:"CashVan/AjaxReference/GetAllDetail.php",
		type:"post",
		data:"pid=View_Transaction_Logs&vehicle_regno_po="+vehicle_regno_new+"&vehicle_model_po="+vehicle_model_new+"&rbi_guide_po="+rbiguide_vals+"&vehicle_type_po="+cashvantype_new+"&gunman="+gun1+"&driver="+$('#crew_form').find('.checks_driver').val()+"&loader="+$('#crew_form').find('.checks_loader').val()+"&ce="+$('#crew_form').find('.checks_ce').val()+"&form_value=1"+"&sid="+sid+"&service_date="+service_date+"&process_type=Hired",
		success:function(result){
			console.log(result);
			var res = $.parseJSON(result);
			var htm = '<div align="center"><a href="./?pid=View_Logs&id='+res['sid']+'" target="_blank"><i class="fa fa-remove notavailable_trans" ></i></a></div>';
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#'+res['td_id']).html(htm);
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#assign_text'+res['sid']).html('Assigned Already');
			$('#trans_table').find('#'+res['tr_id']).closest('tr').find('#veh_td'+res['sid']).append(res['cv']);
			//veh_td
			$('#crew_form').find('#close').trigger('click');
		}
	});

}


</script>


