<link rel="stylesheet" type="text/css" href="js/plugins/img_crop/css/imgareaselect-animated.css" />
<script type="text/javascript" src="js/plugins/img_crop/js/1.7.1_jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/img_crop/js/jquery.imgareaselect.pack.js"></script>
<script type="text/javascript" src="js/plugins/img_crop/js/script.js"></script>

<?php
if (!isset($_SESSION)) {
	session_start();
}
$id = $_REQUEST['id'];
$nav = $_REQUEST['nav'];
$region_load = $_REQUEST['region'];
$state = $_REQUEST['state'];
$aemp_id = $_REQUEST['aemp_id'];
$name1 = $_REQUEST['ce_name'];
$url = $_REQUEST['url'];
$ace_id = $_REQUEST['ace_id'];
$id = $_REQUEST['id'];
include('CommonReference/date_picker_link.php');
require_once __DIR__ . '/../DbConnection/dbConnect.php';

$id = $_REQUEST['id'];
$cont_type = 0;
$result = 'RCS00001';

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

				<h3 class="portlet-title">
					<u>RCE KYC Document Details </u>
				</h3>
				<?php if ($nav != '') { ?>
					<div class="message_cu">
						<div style="padding: 7px;"
							class="alert <?php if ($nav == '2_2_1' || $nav == '2_2_2' || $nav == '2_2_3' || $nav == '2_2_4') {
												echo 'alert-danger';
											} else {
												echo 'alert-success';
											} ?>"
							align="center">
							<a aria-hidden="true" href="../HRMS/components-popups.html#" data-dismiss="alert"
								class="close">×</a>
							<b><?php
								$status_cu = array(
									'2_1_1' => 'New Document Upload Sucessfully',
									'2_2_1' => 'Sorry, Please Try Again ',
									'2_5' => '"CE ID: ' . $id1 . ', Already Available, Sorry Please Try Again'
								);
								echo $status_cu[$nav];
								?> </b>
						</div>
					</div>
				<?php }
				?>
				<div class="tab-content" id="myTab1Content">

					<?php
					if ($id != '') { ?>
						<form action="CommonReference/hrms_add_details.php?pid=rce_emp_doc" method="post" enctype="multipart/form-data"
							name="form1" id="form1">
							<input type="hidden" name="doc_id" id="doc_id" value="<?php echo $id; ?>">

							<?php
							$nav = $_REQUEST['nav'];
							if (isset($nav)) {
							?>
							<?php
							}
							$sqlt = "select * from hrms_empdet where r_id=" . $id . " and status!='N'";
							$row = mysqli_query($readConnection, $sqlt);
							$row1 = mysqli_fetch_object($row);
							?>
							<div id="shop_details_div">

								<input type="hidden" name="doc_empid" id="doc_empid" value="<?php echo $row1->emp_id; ?>">
								<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%"
									class="shop_details">
									<tr>
										<td colspan="6" align="center"><label style="color:#C12E2A; font-weight:bold;">
												DETAILS</label></td>
									</tr>
									<tr>
										<td width="10%"><label>Employee Id</label></td>
										<td align="center" width="3%"><b>:</b></td>
										<td width="40%"><?php echo $row1->emp_id; ?></b></td>
										<td><label id="point_type"></label></td>
										<td align="right"><label>Employee Name</label></td>
										<td align="center" width="3%"><b>:</b></td>
										<td><?php echo $row1->cname; ?></td>
										<td align="center"></b></td>
										<td><label id="point_pin"></label></td>
									</tr>
									<tr>
										<td><label>Employee Designation</label></td>
										<td align="center" width="3%"><b>:</b></td>
										<td><?php echo $row1->pdesig; ?></td>
										<td><label id="cust_code"></label></td>
										<td align="right"><label>Employee Location</label></td>
										<td align="center"><b>:</b></td>
										<td><?php echo $row1->plocation; ?></td>
										<td align="center"></td>
										<td><label id="point_phone"></label></td>
									</tr>
								</table>
								<div class="caption">
									<p>
										<!--<a href="javascript:;" class="btn btn-success btn-sm btn-sm" id="ce_proof" onclick="AddRow(this);">Add Row</a>&nbsp;
                                        <a href="javascript:;" class="btn btn-warning btn-sm btn-sm" id="ce_proofs" onclick="DeleteRow(this);">Delete Row</a>-->
									</p>
								</div>
								<div class="table-responsive">
									<table class="table table-striped table-bordered thumbnail-table" width="100%"
										id="ce_proof1">
										<thead>
											<tr>
												<th></th>
												<th>SNo</th>
												<th>Document Upload</th>
												<th>Card No</th>
												<th>Proof Document</th>
												<th>Proof Document Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td align="center"><input type="checkbox" class="chkbox" /></td>
												<td align="center">1<input type="hidden" value="" name="ce_proof_aid[]" />
												</td>
												<td>
													<select id="id_proof" name="id_proof"
														class="form-control parsley-validated chosen-select" required
														onchange="load_verify();">
														<option value="" selected="selected">Select</option>
														<option value="01">Education Documents</option>
														<option value="02">Experience Documents</option>
														<option value="03">Resume</option>
														<option value="04">Pan Card</option>
														<option value="05">Voter Card</option>
														<option value="06">Aadhar Card</option>
														<option value="07">Driving License</option>
														<option value="08">Address Proof</option>
														<option value="09">Photo</option>
														<option value="10">Background Verification</option>
														<option value="11">Annual Post Employment Verification</option>
														<option value="12">Police Verification Letter</option>
														<!--  <option value="13"> Verification</option>
                                  <option value="14"> Cash Pickup Agreement</option>-->
														<option value="14"> Service Provider Agreement</option>
														<option value="15">Employee application</option>
														<option value="16">Induction Trainning Form</option>
														<option value="17">Recruitment process report</option>
														<option value="18">Bank Passbook/Cancelled Cheque Leaf</option>
														<option value="19">MBC Agreement</option>
														<option value="20">Signature</option>
														<option value="21">Appointment Order</option>
														<option value="22">Gun License</option>
														<option value="23">Renewal Letter(SPA)</option>
														<option value="24">Court Record Check</option>
														<option value="25">COD</option>

													</select>
												</td>
												<td>
													<input type="text" id="proof_no" name="proof_no"
														class="form-control parsley-validated" value=""
														placeholder="Identity Proof No">
												</td>
												<td align="center">
													<!--<input id="proof_doc" type="file" name="proof_doc[]" />-->
													<input id="uploadImage" type="file" accept="image/jpeg" name="image" />


													<input id="proof_doc" type="file" accept=".pdf" name="proof_doc" />
													<span id="error_msg" style="color:red;">Please Upload only pdf
														files</span>
													<!-- hidden inputs -->
													<input type="hidden" id="x" name="x" />
													<input type="hidden" id="y" name="y" />
													<input type="hidden" id="w" name="w" />
													<input type="hidden" id="h" name="h" />
												</td>
												<td></td>
												<td class="text-center"><a href="#" class="disableClick" rel="0%hrm_info%0"
														onclick="delete_data_row(this);"><i class="fa fa-trash"
															title="Remove"></i></a></td>
											</tr>
										</tbody>
									</table>
									<div id="modal" class="mymodel" align="center" style="display:none;">
										<div class="modal-content crop">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"
													onclick="show1()"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">Crop Image</h4>
											</div>
											<div class="modal-body">
												<img id="uploadPreview" align="center" style="display:none;" />
											</div>
											<!--<div class="modal-footer">
<!-- <button type="button" class="btn btn-default" data-dismiss="mymodel" onclick="show1()">Close</button>
        <button type="button" class="btn btn-primary" onclick="show2()">Crop</button>	  
	   </div>-->
										</div>
									</div>
								</div>

								<div class="clear"></div>

								<div class="form-group col-sm-3">
									<input type="hidden" name="id" value="" />
									<input type="hidden" name="emp_id" value="" />
									<input type="hidden" name="ce_id" value="" />
									<input type="hidden" name="ce_name" value="" />

									<button type="submit" name="submit" id="submit" class="btn btn-danger search_btn"
										style="margin-top: 10px;">Save Employee Documents</button>
								</div>
							</div>
						</form>
				</div> <!-- /.Identity tab-pane -->

				<div class="clear"></div>

				<div align="center" class="page_title">
					<h3>Uploaded Documents</h3>
				</div>

				<table border="0" align="center" cellpadding="2" cellspacing="0"
					class="table table-hover table-nomargin table-striped table-bordered " width="100%">
					<tbody>
						<tr>
							<th width="87" height="36">
								<div align="center">Doc ID </div>
							</th>
							<th width="197">
								<div align="center"><strong>Document Name </strong></div>
							</th>
							<th width="317">
								<div align="center"><strong>Document Remarks </strong></div>
							</th>
							<th width="173">
								<div align="center"><strong>Document Path </strong></div>
							</th>
							<th width="60">
								<div align="center"><strong>Download</strong></div>
							</th>
							<th width="60">
								<div align="center"><strong>Delete </strong></div>
							</th>
						</tr>
						<?php

						$doc = array("01" => "Education Documents", "02" => "Experience Documents", "03" => "Resume", "04" => "Pan Card Documents", "05" => "Voter Card
", "06" => "Aadhar Card", "07" => "Driving License", "08" => "Address Proof", "09" => "Photo", "10" => "Background Verification", "11" => "Annual Post Employment Verification", "12" => "Police Verification", "13" => "Verification", "14" => "Service Provider Agreement", "15" => "Employee application", "16" => "Induction Trainning Form", "17" => "Recruitment process report", "18" => "Bank Passbook/Cancelled Cheque Leaf", "19" => "MBC Agreement", "20" => "Signature", "21" => "Appointment Order", "22" => "Gun License", "23" => "Renewal Letter(SPA)", "24" => "Court Record Check", "25" => "COD");

						$i = 1;
						$sqls1 = "select * from hrms_empdoc where r_id=" . $id . " and status='Y'";
						$qs1 = mysqli_query($readConnection, $sqls1);
						while ($rw1 = mysqli_fetch_array($qs1)) {

						?>
							<tr id="<?php echo $rw1['r_id']; ?>">
								<td height="28" nowrap="nowrap">
									<div align="center"><?php echo $i; ?></div>
								</td>
								<td nowrap="nowrap">
									<div align="center"><?php echo $doc[$rw1['doc_type']]; ?></div>
								</td>
								<td nowrap="nowrap">
									<div align="center"><?php echo $rw1['doc_remarks']; ?></span></div>
								</td>
								<td nowrap="nowrap">
									<div align="center"><?php echo $rw1['doc_path']; ?></div>
								</td>
								<td nowrap="nowrap">
									<div align="center"><a
											href="<?php if ($rw1['doc_path'] != "") {
														if (file_exists("emp_docs/" . $rw1['doc_path'])) {
															echo "emp_docs/" . $rw1['doc_path'];
														} else {
															echo "http://49.249.173.254/rcms/emp_docs/" . $rw1['doc_path'];
														}
													} else {
														echo "#";
													} ?>"
											target="_blank">
											<!--<img src="images/docs.png" width="20" height="21" border="0" /> --><span
												class="label label-secondary demo-element">View</span>
										</a></div>
								</td>
								<td>
									<div align="center"> <span onclick="delete_data_rce(<?php echo $rw1['doc_id']; ?>,51,this)"
											class="label label-danger demo-element">Delete</span> </div>
								</td>
							</tr>
						<?php
							$i++;
						}
						?>
					</tbody>
				</table>
			</div>

			</form>
		<?php }
		?>
		<div class="clear"></div>
		<div class="portlet">
			<h3 class="portlet-title">
				<u>Customize Search</u>
			</h3>
			<form id="demo-validation" action="" data-validate="parsley" class="form parsley-form">
				<div class="form-group col-sm-3">
					<label class="compulsory">*</label>
					<label for="name"> Search Criteria </label>
					<select id="search" name="search"
						class="form-control parsley-validated chosen-select searchCriteria" data-required="true"
						tabindex="57">
						<option value="">Select</option>
						<option value="all">All</option>
						<option value="emp_id">Employee Id</option>
						<option value="emp_name">Employee Name</option>
						<option value="pdesig1">Designation</option>
						<option value="design">Department</option>
					</select>
					<span class="selectboxErr" style="color:red;display:none"> * Select any criteria </span>
				</div>
				<div class="form-group col-sm-3">
					<label for="name">
						<label class="compulsory"></label>
						Region
					</label>
					<select id="region" name="region"
						class="form-control parsley-validated chosen-select searchRegion" tabindex="58">
						<option value="">Select</option>
						<?php

						if ($region != '') {
							$sql_reg = "select region_id,region_name from region_master where region_id in (" . $region . ")";
							$reg_sql = mysqli_query($readConnection, $sql_reg);
							if (mysqli_num_rows($reg_sql) > 0) {
								while ($log_region = mysqli_fetch_object($reg_sql)) {
						?>
									<option value="<?php echo $log_region->region_name; ?>"
										<?php if ($log_region->region_name == $res_emp->region_name) echo "Selected='Selected'"; ?>>
										<?php echo $log_region->region_name; ?></option>
						<?php
								}
							}
						}

						?>
					</select>
					<span class="selectregErr" style="color:red;display:none"> * Select region </span>
				</div>
				<div class="form-group col-sm-3">
					<label for="name">Enter Keyword</label>
					<input type="text" id="keyword" name="keyword" class="form-control parsley-validated "
						data-required="true" placeholder="Enter Keyword" tabindex="59">
					<span class="keywordErr" style="color:red;display:none"> * Enter keyword </span>
				</div>
				<div class="form-group  col-sm-3">
					<button type="button" class="btn btn-danger search_btn" id="search_criteria"
						style="margin-top: 23px;" onclick="search_key('1', '0')" tabindex="60">Search</button>
				</div>
			</form>
			<div class="clear"></div><br />

			<div class="clear"></div>
			<div id="view_details_indu"></div>

		</div>
		</div> <!-- /.portlet -->

	</div> <!-- /.col -->

</div> <!-- /.row -->

</div> <!-- /.container -->

<script type="text/javascript">
	$(document).ready(function() {
		$('#error_msg').hide();
		$('#proof_doc').hide();
		$('#uploadImage').hide();
		$("a[name=addRow]").click(function() {
			// Code between here will only run when the a link is clicked and has a name of addRow
			$("table#table1 tr:last").after(
				'<tr><td><img class="delete" alt="delete" src="@Url.Content("~/content/delete_icon.png")" /></td></tr>'
			);
			return false;
		});

		$(".chosen-select").chosen({
			no_results_text: 'Oops, nothing found!'
		}, {
			disable_search_threshold: 10
		});
		<?php if ($url == '') { ?>
			setTimeout(function() {
				$('.message_cu').fadeOut('fast');
			}, 3000);
		<?php } ?>
		$.validator.setDefaults({
			ignore: ":hidden:not(select)"
		});
		$.validator.addMethod("phoneUS", function(phone, element) {
			phone = phone.replace(/\s+/g, "");
			return this.optional(element) || phone.match(/^[ 0-9-+,/+-]*$/);
		}, "Enter valid phone number."); //cash,cheque,normal

		$("#form1").validate({
			rules: {
				id_proof: {
					required: true
				},
				cname: {
					required: true,
					number: false
				},
				region1: {
					required: true
				},
				pbranch: {
					required: true
				},

				cstate: {
					required: true
				},
				plocation: {
					required: true
				},
				pdesig: {
					required: true
				},
				pdesig1: {
					required: true
				},
				address1: {
					required: true
				},
				mobile1: {
					required: true,
					number: true,
					minlength: 10
				},
				pan_card_no: {
					required: true
					/*minlength:10*/
				},
				/*aadhar_card_no:{
					number:true,
					minlength:12
				},	*/
				dob: {
					required: true
				},
				doj: {
					required: true
				},
				father_name: {
					required: true,
					number: false
				},
				gender: {
					required: true
				},
				phone: {
					number: true
				},
				pin: {
					required: true,
					number: true
				},
				phone11: {
					required: true
				},
				mobile2: {
					number: true,
					minlength: 10
				},
				email: {
					email: true
				},
				/*ce_status:{
					required:true		
				}*/
			},
			messages: {
				id_proof: {
					required: 'Select Document Type.'
				},
				cname: {
					required: 'Enter The Employee Name.',
					number: 'Enter valid Employee Name.'
				},
				region1: {
					required: 'Select The Region.'
				},
				pbranch: {
					required: 'Select The Branch.'
				},
				cstate: {
					required: 'Select State.'
				},
				plocation: {
					required: 'Select The Location'
				},
				pdesig: {
					required: 'Select Dep.'
				},
				pdesig1: {
					required: 'Select Designation '
				},
				address: {
					required: 'Enter Address'
				},
				/*ce_status:{
					required:'Select CE Status'	
				},*/
				mobile1: {
					required: 'Enter Mobile No.',
					number: 'Enter Valid Mobile No'
				},
				pan_card_no: {
					required: 'Enter PanCard No.'
				},
				dob: {
					required: 'Select Date.'
				},
				doj: {
					required: 'Select Date.'
				},
				father_name: {
					required: 'Enter Father Name.',
					number: 'Enter valid Employee Name.'
				},
				gender: {
					required: 'Select Gender.'
				},
				phone: {
					number: 'Enter Valid Mobile No'
				},
				pin: {
					required: 'Enter Pin Code .',
					number: 'Enter Numeric Only'
				},
				email: {
					email: 'Enter Vaild Email.'
				},
				types: {
					required: 'Select Type.'
				},
				mobile2: {
					required: 'Enter Mobile No.',
					number: 'Enter Valid Mobile No'
				},
				/*aadhar_card_no:{
					required:'Enter Aadhar No.'	,
						number:'Enter Valid Aadhar No'
				},*/

			}
		});


	});

	$("#proof_doc").change(function() {
		val12 = $('#id_proof').val();
		if (val12 == 10 || val12 == 12 || val12 == 14 || val12 == 21 || val12 == 06 || val12 == 15 || val12 == 22 ||
			val12 == 23 || val12 == 24 || val12 == 25) {
			var file = $("#proof_doc").val();
			if (file.substr(file.lastIndexOf('.') + 1).toUpperCase() == "PDF") {
				//alert("valid");

				$("#error_msg").hide();
			} else {
				//alert('not valid');
				$("#error_msg").show();
				$("#proof_doc").val("");
			}
		}
	});

	function load_verify() {
		var id_proof = $('#id_proof').val();
		if (id_proof == '01' || id_proof == '02' || id_proof == '03' || id_proof == '04' || id_proof == '05' || id_proof ==
			'06' || id_proof == '07' || id_proof == '08' || id_proof == '10' || id_proof == '11' || id_proof == '12' ||
			id_proof == '14' || id_proof == '15' || id_proof == '16' || id_proof == '17' || id_proof == '18' || id_proof ==
			'19' || id_proof == '21' || id_proof == '22' || id_proof == '23' || id_proof == '24' || id_proof == '25') {
			$('#proof_doc').show();
			$('#uploadImage').hide();
			$('.crop').hide();
		} else {
			$('#uploadImage').show();
			$('#proof_doc').hide();
			$('.crop').show();
		}
	}
	$(".searchCriteria").on('change', function() {
		$('#keyword').val('');
		if ($('#search').val() == '') {
			$(".selectboxErr").css('display', 'inline');
			$(".selectregErr").css('display', 'none');
			$('.keywordErr').css('display', 'none');

		} else if ($('#search').val() == 'all') {
			if ($('#region').val() == '') {
				$(".selectregErr").css('display', 'inline');
				$("#search_criteria").prop("disabled", true);
			} else {
				$(".selectregErr").css('display', 'none');
				$("#search_criteria").prop("disabled", false);
			}
			$(".selectboxErr").css('display', 'none');
			$('.keywordErr').css('display', 'none');
		} else if ($('#search').val() == 'emp_id') {
			$(".selectboxErr").css('display', 'none');
			$('.keywordErr').css('display', 'inline');
			$(".selectregErr").css('display', 'none');
			$("#search_criteria").prop("disabled", true);

			$('#keyword').keyup(function() {
				if ($.trim($('#keyword').val()) == '') {
					$('.keywordErr').css('display', 'inline');
					$('#search_criteria').prop('disabled', true);
				} else {
					$('.keywordErr').css('display', 'none');
					$('#search_criteria').prop('disabled', false);
				}
			});
		} else {
			if ($('#region').val() == '') {
				$(".selectregErr").css('display', 'inline');
			} else {
				$(".selectregErr").css('display', 'none');
			}
			$("#search_criteria").prop("disabled", true);
			$(".selectboxErr").css('display', 'none');
			$('.keywordErr').css('display', 'inline');

			$('#keyword').keyup(function() {
				if ($.trim($('#keyword').val()) == '') {
					$('.keywordErr').css('display', 'inline');
					$('#search_criteria').prop('disabled', true);
				} else {
					$('.keywordErr').css('display', 'none');
					$('#search_criteria').prop('disabled', false);
				}
			});
		}

	});
	$(".searchRegion").on('change', function() {
		if ($('#region').val() == '') {
			$(".selectregErr").show();
		} else {
			if ($('#search').val() == 'emp_name' || $('#search').val() == 'pdesig1' || $('#search').val() ==
				'design') {
				$(".selectregErr").hide();
				$('#keyword').keyup(function() {
					if ($.trim($('#keyword').val()) == '') {
						$('.keywordErr').css('display', 'inline');
						$('#search_criteria').prop('disabled', true);
					} else {
						$('.keywordErr').css('display', 'none');
						$('#search_criteria').prop('disabled', false);
					}
				});
			} else {
				$(".selectregErr").hide();
				$("#search_criteria").prop("disabled", false);
			}
		}
	});

	function search_key(search_type, page_start) {
		if ($('#keyword').val() != '' || $('#search').val() != '' || $('#search').val() == 'all') {

			tbl_search = '';

			$.ajax({
				type: "POST",
				url: "RCE/AjaxReference/rceLoadData.php",
				data: 'pgn=1&start_limit=' + page_start + '&tbl_search=' + tbl_search + '&per_page=' + $(
						'#per_page').val() + '&end_limit=10&types=2&load=1&pid=rec_emp_doc&search=' + $('#search')
					.val() + '&keyword=' + $('#keyword').val() + '&region=' + $('#region').val(),
				beforeSend: function() {
					$('#view_details_indu').html('<img src="" alt="">');
				},
				success: function(msg) {
					$('#view_details_indu').html(msg);
					$('.search_field').css('display', '');


					$("#to_load_kyc_rce").DataTable({
						ordering: false
					});


				}
			});
		} else {
			// $('#keyword').addClass('error_dispaly');
			$(".selectboxErr").css('display', 'inline');
		}
	}

	function AddRow(obj) {
		var id = $(obj).attr('id') + "1";
		var data = "<tr>" + $("#" + id + " tbody tr:first").html() + "</tr>";
		$("#" + id + " tbody").append(data);
		sno = ($("#" + id + " tbody tr").length);
		$("#" + id + " tbody tr:last").find('td:eq(1)').html(sno);
		clear_row(id);

	}

	function DeleteRow(obj) {
		var ids = $(obj).attr('id');
		table_id = ids.substr(0, ids.length - 1) + "1";
		con = confirm("Do you want to Delete the Record?");
		if (con) {
			$('#' + table_id + ' tbody tr').find('.chkbox:checked').each(function() {
				var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
				closest_tr = $(this).closest('tr');
				var rel = $(this).closest('tr').find('td:last').find('a').attr('rel');
				data = rel.split('%');
				var id = data[0];
				var pid = data[1];
				var dtype = data[2];

				if (id != 0) {
					$.ajax({
						url: 'transaction_delete.php',
						data: {
							id: id,
							pid: pid,
							dtype: dtype
						},
						method: 'post',
						success: function(result) {
							var res = $.parseJSON(result);
							$(".alert").show();
							if (res['result_response'] == 'success') {
								if (tbl_tr_len == 1) {
									var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() +
										"</tr>";
									closest_tr.remove();
									$("#" + table_id + " tbody").append(data);
									clear_row(table_id);
								} else {
									closest_tr.remove();
								}
							}
						}
					});
				} else {
					var tbl_tr_len = $('#' + table_id + ' tbody tr').length;

					if (tbl_tr_len == 1) {
						var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
						closest_tr.remove();
						$("#" + table_id + " tbody").append(data);
						clear_row(id);
					} else {
						closest_tr.remove();
					}
				}
			});
		}
	}

	function delete_data_row(obj) {
		var table_id = $(obj).closest('table').attr('id');
		var tbl_tr_len = $('#' + table_id + ' tbody tr').length;
		con = confirm("Do you want to Delete the Record?");
		if (con) {
			var rel = $(obj).prop("rel");
			// alert(rel);
			data = rel.split('%');
			var id = data[0];
			var pid = data[1];
			var dtype = data[2];
			if (id != 0) {
				$.ajax({
					url: 'transaction_delete.php',
					data: {
						id: id,
						pid: pid,
						dtype: dtype
					},
					method: 'post',
					success: function(result) {
						//alert(result);
						var res = $.parseJSON(result);
						$(".alert").show();
						if (res['result_response'] == 'success') {
							//alert("success");
							if (tbl_tr_len == 1) {
								var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
								$(obj).closest('tr').remove();
								$("#" + table_id + " tbody").append(data);
								clear_row(table_id);
							} else {
								$(obj).closest('tr').remove();
							}
						}
					}
				});
			} else {
				if (tbl_tr_len == 1) {
					var data = "<tr>" + $("#" + table_id + " tbody tr:first").html() + "</tr>";
					$(obj).closest('tr').remove();
					$("#" + table_id + " tbody").append(data);
					clear_row(table_id);
				} else {
					$(obj).closest('tr').remove();
				}
			}
		} else {
			return false;
		}
	}
</script>
<script type="text/javascript">
	function setInfo(i, e) {
		$('#x').val(e.x1);
		$('#y').val(e.y1);
		$('#w').val(e.width);
		$('#h').val(e.height);
	}

	$(document).ready(function() {
		var p = $("#uploadPreview");
		$("#uploadImage").change(function() {
			p.fadeOut();
			var oFReader = new FileReader();
			oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
			oFReader.onload = function(oFREvent) {
				p.attr('src', oFREvent.target.result).fadeIn();
			};
		});

		$('img#uploadPreview').imgAreaSelect({
			aspectRatio: '1:1',
			onSelectEnd: setInfo
		});
	});

	function show() {

		document.getElementById('modal').style.display = 'block';

	}

	function show1() {
		document.getElementById('modal').style.display = 'none';
		document.getElementById('uploadPreview').style.display = 'none';
		$('#uploadPreview').load(document.URL + ' #uploadPreview');
		document.getElementById('uploadImage').value = '';
	}

	function show2() {
		document.getElementById('modal').style.display = 'none';
		document.getElementById('uploadPreview').style.display = 'none';
		document.getElementById('uploadPreview').style.boxShadow = "0px 0px 0px 0px rgba(0, 0, 0, 0);";
		document.getElementById('uploadPreview').style.boxShadow = "0px 0px 0px 0px rgba(0, 0, 0, 0);";
	}
	function delete_data_rce(delate_id, del_tab,button){
		var r = confirm("Are you sure you want to delete this record?");
		var row = button.closest('tr');

        if (r == true) {
            $.ajax({
                type: "POST",
                url: "CommonReference/delete_data.php",
                data: 'delate_id=' + delate_id + '&del_tab=' + del_tab,
                success: function(msg) {

                    if (msg == 'Suc') {
                          row.remove();
                    }else{
						alert("Something went wrong.Try again later!");
					}
                }
            });
        }
	}
</script>