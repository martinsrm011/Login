<?php
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$url = $_REQUEST['url'];
$btn=$_POST['btn'];
$emp_id=$_SESSION['emp_id'];
$region_id =$_REQUEST['region_id'];
//include('date_picker_link.php');
include('../DbConnection/dbConnect.php');
$vault = '';

if ($user == 'satishn' || $user == 'rohitr') {
    $vault = 'Nagpur';
} elseif ($user == 'bongi' || $user == 'maruti') {
    $vault = 'Pune';
}

$query = "SELECT * FROM vault_frez_status WHERE frze_date = '" . date('Y-m-d') . "' AND status='Y' AND region IN(" . $region . ") AND frz_status=1";

if ($vault != '') {
    $query .= " AND vault='" . $vault . "'";
}

$fz = mysqli_num_rows(mysqli_query($readConnection, $query));


?>
<style type="text/css">
#id_proof_chosen, #bank_acc_chosen, #branch_id_chosen, #emp_current_status_chosen, #emp_bank_chosen {
	width: 100% !important;
}

@media screen {
  #header, #footer {
    display: none;
  }
}

@media print {
  #header{
	font-size: 13px;
    width: 100%;
    height: 100px;
    position: fixed;
    top: 0px;
    z-index: 1;
  }
}

#content{
    width: 100%;
    position: absolute;
    top: 100px; /*Height of header*/
    z-index: 0;
}

#footer{
    width: 100%;
    height: 100px;
    position: absolute;
    bottom: 0px;
}

/*For demo only*/
#header, #footer{
    border: 3px dashed #333333;
    background: #FFFFFF;
}

#content{
    background: #CCCCCC;
    height: 200px;
}
}

</style>

<div class="container">
  <div class="row">
    <div class="col-md-12 col-sm-11">
      <div class="portlet">
        <div class="portlet-body">
          <h3 class="portlet-title"><u>Cash In Entry Amendments </u></h3>
		     <div align="center">
			 
			 <?php
				    $now = date('H:i:s');
		if(( $now > '12:55:00' && $now < '13:10:00' ) || ( $now > '16:55:00' && $now < '17:10:00' )){
			
			?>
			<div style="text-align:justify;">Dear Team,<br>

You were not allowed to enter the transactions on the following times.<br>

Here below mentioned Timing’s for reference<br>


1.     12.55  to 1.10 PM<br>

2.     4.55  to 5.10 PM</div>
			
		<?php } ?>
		  
			 
            <?php if($fz>0)
		  {
		   ?>
		  <div style="padding: 7px;" class="alert alert-danger"  align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>  Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.</b> </div>
		  
		  <?php } 
						  $nav=$_REQUEST['nav'];
						  if($nav!="")
						  { ?>
            <div class="message_cu">
              <div style="padding: 7px;" class="alert <?php if($nav=='2_2' || $nav=='2_4' || $nav=='2_7'|| $nav=='2_6') { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center" > <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>
                <?php
				if($nav=="2_1")
				echo "New Details Successfully Updated";
				elseif($nav=="2_2")
				echo "Sorry, Please Try Again";
				elseif($nav=="2_3")
				echo "Sucessfuly Delete the Details";
				elseif($nav=="2_6")
				echo "Select Details Can't deposit In ERP";
				elseif($nav=="2_7")
				echo " Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.";
				?>
                </b> </div>
            </div>
            <?php }			?>
          </div>
           <form action="<?php echo '?pid='.$pid?>" method="post" enctype="multipart/form-data" name="frm" id="frm">
              <input type="hidden" name="frz" id="frz" value="<?php echo $fz; ?>">
              <input type="hidden" name="now1" id="now1" value="<?php echo $now ?>">
<div class="form-group col-sm-2">
             <input type="hidden" id="region_id" value="<?php echo $region; ?>"  />
            <label for="name"><label class="compulsory">*</label>From Date</label>
            <input type="text" id="popupDatepicker" autocomplete ='off'  placeholder ='dd-MM-YYYY' name="from_date" class="form-control parsley-validated" data-required="true" value="" tabindex="1" readonly>
			<span id="from_date_id" style="color:red;"></span>
          </div>
          
          <div class="form-group col-sm-2">
             <input type="hidden" id="region_id" value="<?php echo $region; ?>"  />
             <input type="hidden" id="per" value="<?php echo $per; ?>"  />
            <label for="name"><label class="compulsory">*</label>To Date</label>
            <input type="text" id="popupDatepicker1"  autocomplete ='off' placeholder ='dd-MM-YYYY' name="to_date" class="form-control parsley-validated" data-required="true" value="" tabindex="1" readonly><span id="to_date_id" style="color:red;"></span>
          </div>
		  
               
         <div class="form-group  col-sm-3">
            <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;"  onclick="search_key('1', '0')"  tabindex="12">Get Data</button> 
          </div>
        </form>
		
         <div class="clear"></div>
		  <div style="padding: 7px;" class="alert alert-danger"  id="freeze" align="center"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a><b>  Dear User<br>
You cannot  to enter transactions for the date for which the EOD has been initiated.<br>
If this is with regard to amendments you may contact HO IT Team.</b>  </div>


<div style="text-align:justify;" id="frr">Dear Team,<br>

You were not allowed to enter the transactions on the following times.<br>

Here below mentioned Timing’s for reference<br>


1.     12.55  to 1.10 PM<br>

2.     4.55  to 5.10 PM</div>
        </div>
        <div id="view_status"></div>
        <br />
        <?php // include("search_field.php"); ?>
        <div class="clear"></div>
		 <div align="center" style="padding: 7px; display: none;" class="alert alert-danger message_cu del_msg"> <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a> <b>Selected Point Details Deleted</b> </div>
        <!--<div align="right">-->
          <!--<input name="button" type="button" onclick="printDiv('printableArea')" value="Print" />-->
		 <!-- <a href="vault_print_ack_slip.php" target="_blank" style="color: black;text-decoration: none;"><input name="button" type="button" value="Print" /></a>
      </div>-->
         <div id="printableArea">
         
             <style>
               .pclass
			   {
 				 font-family:courier;
  				 font-size:10px;  
               }
               .border1
			   {
                 border-left:1px solid;
                 border-right:1px solid;
               }

         </style>
         
          <br />
        <div id="view_details_indu"></div>
        <div align="center" id="footer"><font face="courier" size="2">*This Report was Generated on <?php echo date('d-m-Y h:i:sa'); ?><br/> By User Name:<b><?php echo $user_name ?></b> &nbsp; &nbsp;  user Id:<b><?php echo $emp_id;  ?></b></font></div>
        </div>
        </div>
        <!-- /.portlet-body --> 
      </div>
      <!-- /.portlet --> 
    </div>
    <!-- /.col --> 
  </div>
  <!-- /.row --> 
</div>
<!-- /.container --> 


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
	
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/start/jquery-ui.css" rel="Stylesheet" type="text/css" />
			
<script type="text/javascript">
        $("#popupDatepicker").datepicker({
            dateFormat: "dd-mm-yy",
            maxDate: 0,
			minDate:'-1m',
            onSelect: function (date) {
                var dt2 = $('#popupDatepicker1');
                var startDate = $(this).datepicker('getDate');
                var minDate = $(this).datepicker('getDate');
                dt2.datepicker('setDate', minDate);
                startDate.setDate(startDate.getDate() + 30);
                //sets dt2 maxDate to the last day of 30 days window
                dt2.datepicker('option', 'maxDate', startDate);
                dt2.datepicker('option', 'minDate', minDate);
               // $(this).datepicker('option', 'minDate', minDate);
            }
        });
        $('#popupDatepicker1').datepicker({
            dateFormat: "dd-mm-yy",
			maxDate: 0,
			minDate:'-1m'
        });
function search_key(search_type, page_start) {
		var from_date=$('#popupDatepicker').val();
		var to_date=$('#popupDatepicker1').val();
		 var fz=$('#frz').val();
		 var now1=$('#now1').val();
		if(from_date=='')
		{
			$('#from_date_id').html("Select From Date");
			alert('Select From Date');
			return false;
		}
		else if(to_date=='')
		{
			$('#to_date_id').html("Select To Date");
			alert('Select To Date');
			return false;
		}
		else if(fz>0)
		 {
			  $('#freeze').show();
		 }
		 
		 else if(( now1 > '12:55:00' && now1 < '13:10:00' ) || ( now1 > '16:55:00' && now1 < '17:10:00' )){
			$('#frr').show(); 
		 }
		 
		else{
		$('#view_details_indu').css('display', '');
		$.ajax({
			type : "POST",
			url : "VaultModule/AjaxReference/vault_print_acknowledge.php",
			data : 'pgn=1&start_limit=' + page_start + '&per_page=' + $('#per_page').val() + '&end_limit=10&types=1&pid=<?php echo $pid;?>&from_date=' + $('#popupDatepicker').val()+'&to_date=' + $('#popupDatepicker1').val()+'&per=' + $('#per').val()+'&region_id=' + $('#region_id').val()+'&client_login=<?php echo $client_login; ?>',
			beforeSend : function() {
				$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
			},
			success : function(msg) {
				$('#view_details_indu').html(msg);
				$('.search_field').css('display', '');

				$("#load_cash_bag_ammend").DataTable({ ordering: false});

				//initializeDataTable("#load_cash_bag_ammend");
			}
		});
		}
	}

	$(document).ready(function() {

		

		$('#freeze').hide();
	$('#frr').hide();
		$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});
		<?php if($url=='') { ?>
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		<?php } ?>
		
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$.validator.addMethod("phoneUS", function (phone, element) {
        phone = phone.replace(/\s+/g, "");
        return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
    }, "Enter valid phone number.");
	$.validator.setDefaults({ ignore: ":hidden:not(select)" });
	$("#frm").validate({
		rules:{
			from_date:{
				required:true
			},
			to_date:{
				required:true
			},
			
		},
		messages:{
			from_date:{
				required:'Select Date .'
			},
		to_date:{
				required:'Select Vault Name.'
			},
			
		},errorPlacement: function(error, element) {
					if (element.attr("name") == "from_date" )
						error.appendTo('#from_date_id');
						else if  (element.attr("name") == "to_date" )
						error.appendTo('#to_date_id');
		}					
	});

	
$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});	
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);








	});
	


	
</script> 


