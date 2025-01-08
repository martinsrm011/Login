<?php
$id 	= $_REQUEST['id'];
$nav 	= $_REQUEST['nav'];
$cust 	= $_REQUEST['cust'];
$state 	= $_REQUEST['state'];
$location = $_REQUEST['location'];
$shop_idss = $_REQUEST['shop_id'];
$ab = $_REQUEST['ab']
?>
<style type="text/css">
	.mapped {
		color: green;
		font-weight: bold;
	}
	.unmapped {
		color: red;
		font-weight: bold;
	}
</style>
<div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-11">

          <div class="portlet">	    

            <h3 class="portlet-title">
              <u>RCE Mapping</u>
            </h3>
            <?php if($nav!='') { ?>
               <div class="message_cu">
              <div style="padding: 7px;" class="alert <?php if($nav==2 || $nav==4) { echo 'alert-danger'; } else { echo 'alert-success'; } ?>" align="center">
                  <a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
                  <b><?php
                  $status_cu = array(1=>'New CE Point Mapping Details Successfully Added', 2=>'Sorry, Please Try Again', 3=>'Selected CE Point Mapping Details Updated', 4=>'Sorry, This Point Already Mapped', 5=>'Select Point Successfully Un Mapped', 6=>'Unable To Unmapped');
                  echo $status_cu[$nav];
                  ?> </b>
              </div>
              </div>
              <?php }
			  if($id!='') {
				  	$sql_shopce = mysqli_query($readConnection,"SELECT * FROM shop_cemap WHERE map_id='".$id."'");
					$res_shopce = mysqli_fetch_object($sql_shopce);
					$shop_id = $res_shopce->shop_id;
			  }
			   ?>
			<div class="clear"></div>
			<form id="demo-validation" action="<?php echo 'CommonReference/add_details.php?pid='.$pid; ?>" method="post" data-validate="parsley" class="form parsley-form">
				<div >
	            	<div class="form-group col-sm-3">
	                  <label for="name"><label class="compulsory">*</label> Customer Name</label>	                  
	                  <select id="cust_id" name="cust_id" class="form-control parsley-validated chosen-select" tabindex="1" disabled>
				  <option value="">Select Customer Name</option>
                  <?php
				  
				  $sql_cust = mysqli_query($readConnection,"SELECT a.client_name, b.cust_id, b.cust_name FROM client_details AS a JOIN cust_details AS b ON a.client_id=b.client_id WHERE b.status='Y' AND b.status='Y' ORDER BY a.client_name, b.cust_name");
				  while($res_cus = mysqli_fetch_object($sql_cust)) {
						echo '<option value="'.$res_cus->cust_id.'" ';						
						if($cust==$res_cus->cust_id) { echo 'selected="selected"'; }						
						echo '>'.$res_cus->client_name.' - '.$res_cus->cust_name.'</option>';
				  }
				  ?>
				   </select>
	              </div>
	              <div class="form-group col-sm-3">
	                  <label for="name"><label class="compulsory">*</label> Branch</label>
                      <select id="branch" name="branch" class="form-control parsley-validated chosen-select"  onchange="branch_load()" tabindex="2" disabled>
                      <option value="">Select Branch</option>
				  		<?php
						$sql_region = mysqli_query($readConnection,"SELECT region_id, region_name FROM region_master WHERE region_id IN (".$login_regoin.") AND  status='Y'");
						while($res_region = mysqli_fetch_object($sql_region)) {
							echo '<option value="'.$res_region->region_id.'" ';
							if($state==$res_region->region_id) { echo 'selected="selected"'; }
							echo '>'.$res_region->region_name.'</option>';	
						}
						?>
                      </select>
	               </div>
                  <div class="form-group col-sm-3">
	                  <label for="name"><label class="compulsory">*</label> Location</label>
                      <select id="location" name="location" class="form-control parsley-validated location chosen-select" onchange="location_load()"  tabindex="2" disabled>
                      	<option value="">Select Location</option>
                      </select>
	               </div>
	               <div class="form-group col-sm-3">
	                  <label for="name"><label class="compulsory">*</label> Point Name</label>
                      <select id="shop" name="shop" class="form-control parsley-validated chosen-select" onchange="shop_load('1')" tabindex="2" disabled>
                      	<option value="">Select Point Name</option>
                      </select>
                      
	                  
	               </div>
	               <div class="clear"></div>
                   <div class="form-group col-sm-12">
                   <style type="text/css">
				   	.shop_details td {
						padding:3px;
					}
				   </style>
	              <table cellpadding="0" cellspacing="0" border="0" align="center" width="80%" class="shop_details">
                  	<tr><td colspan="6" align="center"><label>Point Location ID : <span id="point_id"><?php echo $shop_id; ?></span><input name="shop_code" type="hidden" id="shop_code" value="<?php echo $shop_id;?>" /></label></td></tr>
                  	<tr><td colspan="6" align="center"><label style="color:#C12E2A; font-weight:bold;">SHOP DETAILS</label></td></tr>
                    <tr><td width="200" align="right"><label>Point Type</label></td><td width="20" align="center"><b>:</b></td><td width="350"><label id="point_type"></label></td><td width="250" align="right"><label>Point PinCode</label></td><td width="20" align="center"><b>:</b></td><td width="350"><label id="point_pin"></label></td></tr>                   
                   
                    <tr><td align="right"><label>Customer Code</label></td><td align="center"><b>:</b></td><td><label id="cust_code"></label></td><td align="right"><label>Point Phone No</label></td><td align="center"><b>:</b></td><td><label id="point_phone"></label></td></tr>
                    
                    <tr><td align="right"><label>Customer Point Code</label></td><td align="center"><b>:</b></td><td><label id="cust_point_code"></label></td><td align="right"><label>Contact Name</label></td><td align="center"><b>:</b></td><td><label id="contact_name"></label></td></tr>
                    
                    <tr><td align="right"><label>Point Name</label></td><td align="center"><b>:</b></td><td><label id="point_name"></label></td><td align="right"><label>Contact Mobile No</label></td><td align="center"><b>:</b></td><td><label id="contact_mobile"></label></td></tr>
                    
                    <tr><td align="right"><label>Point Address</label></td><td align="center"><b>:</b></td><td><label  id="point_address"></label></td><td align="right"><label>Service Type Point Code</label></td><td align="center"><b>:</b></td><td><label id="service_type"></label></td></tr>
                  </table>
	              </div>
                  <div class="clear"></div>
                  <div class="form-group col-sm-3">
	                  <label for="name"><label class="compulsory">*</label> CE Location</label>
                      <select id="location1" name="location1" class="form-control parsley-validated location chosen-select"  tabindex="2">
                      	<option value="">Select Location</option>
                      </select>
	               </div>
                  <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Select Primary CE</label>
	                  <input type="text" id="ces_1" name="primary_ce" class="form-control parsley-validated"  style ="background-color:#eee" data-required="true" value="<?php echo $res_shopce->pri_ce; ?>" placeholder="Sub Contractor Agreement End Date"  onkeydown="return false;">                
	               </div>
                  <div class="form-group  col-sm-1"><a data-toggle="modal" id="ce_1" href="#basicModal" class="btn update_locid"><button type="submit" class="btn btn-danger search_btn" name="submit" value="submit"  tabindex="14" style="margin-top: 23px;">Get IDs</button></a></div>
                  <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">*</label> Select Secondary CE</label>
	                  <input type="text" id="ces_2" name="secondary" class="form-control parsley-validated" data-required="true" value="<?php echo $res_shopce->sec_ce; ?>" placeholder="Sub Contractor Agreement End Date" readonly="readonly" >                
	               </div>
                  <div class="form-group  col-sm-1"><a data-toggle="modal" id="ce_2" href="#basicModal" class="btn update_locid"><button type="submit" class="btn btn-danger search_btn" name="submit" value="submit"  tabindex="14" style="margin-top: 23px;">Get IDs</button></a></div>
                  <div class="form-group col-sm-2">
	                  <label for="name"><label class="compulsory">&nbsp;</label> Select Additional CE</label>
	                  <input type="text" id="ces_3" name="additional" class="form-control parsley-validated" data-required="true" value="<?php echo $res_shopce->add_ce; ?>" placeholder="Sub Contractor Agreement End Date" readonly="readonly" >                
	               </div>
                  <div class="form-group  col-sm-1"><a data-toggle="modal" id="ce_3" href="#basicModal" class="btn update_locid"><button type="submit" class="btn btn-danger search_btn" name="submit" value="submit"  tabindex="14" style="margin-top: 23px;">Get IDs</button></a></div>
	               
	               <div class="clear"></div>
	               <div class="form-group col-sm-2">
                   <?php if($id!='') {
						?>
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<?php  
					  }?>
	                  <button type="submit" name="submit" id="submit" class="btn btn-danger search_btn" tabindex="18">Save CE mapping Details</button>
                      
	               </div>
                   <?php if($res_shopce->pri_ce!='') { ?>
                    <div class="form-group col-sm-3">
                    	<a href="CommonReference/add_details.php?pid=rce_unmap&submit=submit&id=<?php echo $id; ?>"><button type="button" name="submit" id="submit" class="btn btn-danger search_btn" tabindex="18">Un Mapped This Point</button></a>
	
                    </div>
					
					  <?php    } ?>
			 <div class="clear"></div>

			 <?php 
			 
	if($per=='Admin' || $user=='surya') {
	echo '<br>'.$res_shopce->update_by.' - '.$res_shopce->update_date;
	} 	
    
	?>
		         </div>

	        </form>
			
				
            </div> <!-- /.portlet-body -->
            
            <div id="basicModal" class="modal fade">

        <div class="modal-dialog">

          <div class="modal-content">

            <div   id="basicModal_view"></div>
           <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="save_loa"  data-dismiss="modal">Select Head Name</button>
            </div> <!-- /.modal-footer -->

          </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

      </div>
            <div class="clear"></div>
            <div class="portlet">
            	<h3 class="portlet-title">
	              <u>Customize Search</u>
	            </h3>
	            
	             <div align="center" style="padding: 7px; display: none;" class="alert alert-success message_cu del_msg">
            	<a aria-hidden="true" href="components-popups.html#" data-dismiss="alert" class="close">×</a>
            	<b>Selected CE Point Mapping Details Deleted</b>
            </div>
	            
	            <form id="demo-validation" action="#" data-validate="parsley" class="form parsley-form">

                <div class="form-group col-sm-2">
                  <label for="name">Search Criteria </label>
                  <select id="search" name="search" class="form-control parsley-validated chosen-select" data-required="true">
                  	<option value="">Select Option</option>
                  	<option value="All">All Mapping</option>
                    <option value="Shop ID">Point ID </option>
                    <option value="Shop Code">Point Location Code </option>
                    <option value="Shop Name">Point Name</option>
                    <option value="Primary CE ID">Primary CE ID</option>
                    <option value="Sec CE ID">Sec CE ID</option>
                    <option value="Add CE ID">Add CE ID</option>
                    <option value="Location Name">Location Name</option>
                    <option value="Region Name">Branch Name</option>
                  </select>
               </div>
               <div class="form-group col-sm-2">
                  <label for="name">Enter Keyword</label>
                  <input type="text" id="keyword" name="keyword" class="form-control parsley-validated" data-required="true" placeholder="Enter Keyword">
               </div>

				<div class="form-group  col-sm-2">
                  <button type="button" class="btn btn-danger search_btn" style="margin-top: 23px;" onclick="search_key('1', '0')">Search Mapping</button>
                </div>
				</form>
                <div class="clear"></div>
                 <div id="view_status12"></div><br />
				<?php //include("search_field.php"); ?>
					<div class="clear"></div>
				<div id="view_details_indu"></div>
            </div>
            
            <div class="clear"></div>
            <?php
			if($per=='Admin' || $per=='Super User') {
			?>
            <!--<div class="portlet">
            	<h3 class="portlet-title">
	              <u>Map & Un Map Shop Details</u>
	            </h3>
                <div class="form-group col-sm-3">
                  <label for="name">Customer Name</label>
                  <select id="custo_id" class="form-control parsley-validated chosen-select" onchange="custo_id('1', '0')">
                  	<option value="">Select Customer Name</option>  
                  	<?php
                  	 $sql_cust = mysqli_query($readConnection,"SELECT a.client_name, b.cust_id, b.cust_name FROM client_details AS a JOIN cust_details AS b ON a.client_id=b.client_id WHERE b.status='Y' AND b.status='Y' ORDER BY a.client_name, b.cust_name");
				  while($res_cus = mysqli_fetch_object($sql_cust)) {
								
						echo '<option value="'.$res_cus->cust_id.'" ';						
						if($cust==$res_cus->cust_id) { echo 'selected="selected"'; }						
						echo '>'.$res_cus->client_name.'-'.$res_cus->cust_name.'</option>'; 
				  }
				  ?>                	
                  </select>
               </div>
               <div class="clear"></div>
               <?php //include("search_field1.php"); ?>
			  <div class="clear"></div>
               <div id="load_shop"></div>
               <br /><br /><br /><br /><br /><br />
            </div>-->    
            <?php
			}			
			?>
          </div> <!-- /.portlet -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->

    </div> <!-- /.container -->
	<style type="text/css">
		#cust_id-error,#branch-error,#location-error,#shop-error,#location1-error,#ces_1-error{
			left: 10px;
			top: 77px;
			position: absolute;
		}
	</style>
<script type="text/javascript">
	$(document).ready(function() {
		$(".chosen-select").chosen({no_results_text:'Oops, nothing found!'},{disable_search_threshold: 10});
		setTimeout(function() {
			$('.message_cu').fadeOut('fast');
		}, 3000);
		$.validator.setDefaults({ ignore: ":hidden:not(select)" });
		$("#demo-validation").validate({
		rules:{
			cust_id:{
				required:true
			},
			branch:{
				required:true
			},
			location:{
				required:true
			},
			phone:{
				required:true
			},
			shop:{
				required:true
			},
			location1:{
				required:true
			},
			primary_ce:{
				required:true
			}
		},
		messages:{
			cust_id:{
				required:'Select Customer Name.'
			},
			branch:{
				required:'Select Branch.'
			},
			location:{
				required:'Select Location.'
			},
			shop:{
				required:'Select Point Name.'
			},			
			location1:{
				required:'Select CE Location.'				
			},			
			primary_ce:{
				required:'Select Primary CE.'				
			}
		}
		});		
		$( "body" ).on( "click", ".update_locid", function() {

			ids = this.id;
			ids1 = ids.split('_');
			$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'types=4&pid=rce_mapping&location1='+$('#location1').val()+'&load_type='+ids1[1],
				success: function(msg){
					$('#basicModal_view').html(msg);
				}
			});	
			
		});
		$( "body" ).on( "click", ".staff_name", function() {
			chek_lng = $('.staff_name:checkbox:checked').length;
			if(chek_lng>1) {
				alert('Select Only One!');
				$(this).removeAttr('checked');
			}
		});
		$( "body" ).on( "change", "#search", function() {
			$('#keyword').val('');
		});
		$( "body" ).on( "click", "#save_loa", function() {
			
			var staff_de = "";
			$('#basicModal_view input[type=checkbox]').each(function () {
				 if (this.checked) {
					staff_de+=$(this).val(); 
				 }
			});
			load_type = $('#load_type').val();
			$('#ces_'+load_type).val(staff_de);
		});
		
	});
	<?php if($state!='') { ?>
	window.onload = function() {	
 		branch_load();
	};
	 <?php } ?>
	function branch_load() {
		$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'types=1&pid=rce_mapping&branch='+$('#branch').val()+'&cust_id='+$('#cust_id').val()+'&cu_location=<?php echo $location; ?>',
				success: function(msg){
					$('.location').html(msg);
					$('.location').trigger("chosen:updated");
					<?php if($shop_idss=='' && $ab!='1') {?>		
					$('#shop').html('');
					$('#shop').trigger("chosen:updated");
					$('#shop_code').val('');					
					$('#point_id').html('');
					$('#point_type').html('');
					$('#cust_code').html('');
					$('#cust_point_code').html('');
					$('#point_name').html('');
					$('#point_address').html('');
					$('#point_pin').html('');
					$('#point_phone').html('');
					$('#contact_name').html('');
					$('#contact_mobile').html('');
					$('#service_type').html('');
					<?php
					}
					 if($location!='') { ?>	
						location_load();	
					<?php } ?>
				}
			});
		$.ajax({
			type: "POST",
			url: "RCE/AjaxReference/rceLoadData.php",
			data: 'types=9&pid=rce_mapping&branch='+$('#branch').val()+'&cu_location=<?php echo $location; ?>',
			success: function(msg){
				$('#location1').html(msg);
				$('#location1').trigger("chosen:updated");					
				
			}
		});
	}
	 function location_load() {
		 <?php
		 if($shop_idss!='') {
			 $shop_id11 = $shop_idss;
		 }
		 else {
			 $shop_id11 = $shop_id;
		 }
		 ?>
		$.ajax({
			type: "POST",
			url: "RCE/AjaxReference/rceLoadData.php",
			data: 'types=2&pid=rce_mapping&cust_id='+$('#cust_id').val()+'&location='+$('#location').val()+'&shop_id=<?php echo $shop_id11; ?>',
			success: function(msg){
				
				$('#shop').html(msg);
				$('#shop').trigger("chosen:updated");
				<?php if($shop_idss=='' && $ab!='1') {?>		
					$('#shop_code').val('');					
					$('#point_id').html('');
					$('#point_type').html('');
					$('#cust_code').html('');
					$('#cust_point_code').html('');
					$('#point_name').html('');
					$('#point_address').html('');
					$('#point_pin').html('');
					$('#point_phone').html('');
					$('#contact_name').html('');
					$('#contact_mobile').html('');
					$('#service_type').html('');
				<?php
				}
				 if($id!='' && $ab!=1) { ?>	
					shop_load();	
				<?php } ?>
			}
		});
	}
	<?php
	if($shop_idss!='') {
		?>
		shop_load('<?php echo $shop_idss ?>');
		<?php	
	}
	
	?>
	function shop_load (shop_id) {
		
		if(shop_id==1) {
			<?php
			if($shop_id!='') {
				?>
				var shop_ids = 	'<?php echo $shop_id; ?>';
				<?php
				
			}else {
				?>
				var shop_ids = 	$('#shop').val();
				<?php	
			}
			?>
			
			
		}
		else {
			//alert(shop_id);
			var shop_ids = shop_id;
		}
		
		//alert(shop_ids);
		/*if(shop_ids!='') {*/
		$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'types=3&pid=rce_mapping&shop='+shop_ids,
				success: function(msg){
					shop_det = msg.split('^%');
					$('#shop_code').val(shop_det[0]);					
					$('#point_id').html(shop_det[0]);
					$('#point_type').html(shop_det[1]);
					$('#cust_code').html(shop_det[2]);
					$('#cust_point_code').html(shop_det[3]);
					$('#point_name').html(shop_det[4]);
					$('#point_address').html(shop_det[5]);
					$('#point_pin').html(shop_det[6]);
					$('#point_phone').html(shop_det[7]);
					$('#contact_name').html(shop_det[8]);
					$('#contact_mobile').html(shop_det[9]);
					$('#service_type').html(shop_det[10]);
				}
			});
		//}
	}
	
	function search_key (search_type, page_start) {
	  if($('#keyword').val()!='' || $('#search').val()=='All') {
			$('#keyword').removeClass('error_dispaly');
			tbl_search = '';

			$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'pgn=1&start_limit='+page_start+'&tbl_search='+tbl_search+'&per_page='+$('#per_page').val()+'&end_limit=10&types=7&pid=rce_mapping&search='+$('#search').val()+'&keyword='+$('#keyword').val(),
				beforeSend: function(){				
					$('#view_details_indu').html('<img src="img/loading.gif" alt="Radiant.">');
				},
				success: function(msg){
					$('#view_details_indu').html(msg);
					$('.search_field').css('display', '');

					$("#to_load_rce_cemap").DataTable({ ordering: false});
				}
			});
		}
		else {
			$('#keyword').addClass('error_dispaly');
		}
	}
	
	function custo_id (search_type, page_start) {
	
		if(search_type==1) {
				$('#tbl_search1').val('');
				tbl_search = '';
			}
			else {
				tbl_search = $('#tbl_search1').val();
			}
		$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'pgn=1&start_limit='+page_start+'&tbl_search='+tbl_search+'&per_page='+$('#per_page1').val()+'&end_limit=10&types=8&pid=rce_mapping&custo_id='+$('#custo_id').val(),
				success: function(msg){
					$('.search_field1').css('display', '');
					$('#load_shop').html(msg);
				}
			});	
		
	}
</script>