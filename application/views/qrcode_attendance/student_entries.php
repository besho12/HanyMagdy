<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><?=translate('select_ground')?></h4>
	</header>
	<?php echo form_open($this->uri->uri_string());?>
	<div class="panel-body">
		<div class="row mb-sm">
		<?php if (is_superadmin_loggedin() ): ?>
			<div class="col-md-3 mb-sm">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)' id='branchID'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
				<span class="error"><?=form_error('branch_id')?></span>
			</div>
		<?php endif; ?>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$arrayClass = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
					 	data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"><?=form_error('class_id')?></span>
				</div>
			</div>
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group">
					<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
					<?php
						$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
						echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
					?>
					<span class="error"><?=form_error('section_id')?></span>
				</div>
			</div>			
			<div class="col-md-<?php echo $widget; ?> mb-sm">
				<div class="form-group <?php if (form_error('date')) echo 'has-error'; ?>">
					<label class="control-label"><?=translate('date')?> <span class="required">*</span></label>
					<div class="input-group">
						<input type="text" class="form-control" name="date" id="attDate" value="<?=set_value('date', date("Y-m-d"))?>" autocomplete="off"/>
						<span class="input-group-addon"><i class="icon-event icons"></i></span>
					</div>
					<span class="error"><?=form_error('date')?></span>
				</div>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-md-offset-10 col-md-2">
				<button type="submit" name="search" value="1" class="btn btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
			</div>
		</div>
	</footer>
	<?php echo form_close();?>
</section>


<?php if ($validation == true): ?>
<div class="col-md-6 col-sm-12" style="padding-left: 0;">
	<section class="panel">

		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-qrcode"></i> <?=translate('qr_code') . " " . translate('attendance')?>
				<button style="float:right;margin-top:45px;" type="button" class="close_attendance btn btn btn-danger">
					<i class="far fa-list-alt"></i> <?=translate('close_attendance') ?>							
				</button>
			</h4>
		</header>

		<div class="panel-body">
		<div class="row qrcode-scan">
			<!-- <div class="col-md-4 col-sm-12 mb-md">
			<div class="form-group box mt-md">
				<span class="title"><?=translate('scan_qr_code')?></span>
				<div class="justify-content-md-center" id="reader" width="300px" height="300px"></div>
				<span class="text-center" id='qr_status'><?php echo translate('scanning')?></span>
			</div>
			</div> -->	
	
			<div class="col-md-4">
				<div class="form-group add_student_attendance_form">
					<label class="control-label">Student ID</label>
					<input type="text" class="form-control student_code" />
					<button style="margin-top:5px;" type="button" class="add_barcode_manually btn btn btn-default">
						<i class="fas fa-plus-circle"></i> <?=translate('add_manually') ?>							
					</button>
					
					<span class="error"></span>
				</div>
			</div>
			<br>
	
	
	
			<div class="col-md-12 col-sm-12  mt-md">
				<table class="table table-bordered table-hover table-condensed table-question nowrap"  cellpadding="0" cellspacing="0" width="100%" >
					<thead>
						<tr>
							<th>#</th>
							<th><?=translate('name')?></th>
							<th><?=translate('student_id')?></th>
							<th style="width: 120px;"><?=translate('date')?></th>
							<th style="width: 80px;"><?=translate('exam')?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>
</div>


<div class="col-md-6 col-sm-12" style="padding-right: 0;">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-users"></i> <?=translate('student_absent')?></h4>
		</header>
		<div class="panel-body">
		<div class="row qrcode-scan">


			<div class="col-md-12 col-sm-12  mt-md">
				<table class="table table-bordered table-hover table-condensed table-studentabsent nowrap"  cellpadding="0" cellspacing="0" width="100%" >
					<thead>
						<tr>
							<th>#</th>
							<th><?=translate('name')?></th>
							<th style="width: 120px;"><?=translate('student_id')?></th>
							<th style="width: 80px;"><?=translate('date')?></th>
							<th style="width: 50px;"><?=translate('message')?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</section>
</div>


<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="qr_studentDetails">
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title">
				<i class="far fa-user-circle"></i> <?=translate('student_details')?>
			</h4>
		</header>
		<div class="panel-body">
			<div class="quick_image">
				<img alt="" class="user-img-circle" id="quick_image" src="<?=base_url('uploads/app_image/defualt.png')?>" width="120" height="120">
			</div>
			<div class="text-center">
				<h4 class="text-weight-semibold mb-xs" id="quick_full_name"></h4>
				<p><?=translate('student')?> / <span id="quick_category"></p>
			</div>
			<div class="table-responsive mt-md mb-md">
				<table class="table table-striped table-bordered table-condensed mb-none">
					<tbody>
						<tr>
							<th><?=translate('class')?></th>
							<td><span id="quick_class_name"></span></td>
							<th><?=translate('section')?></th>
							<td><span id="quick_section_name"></span></td>
						</tr>
						<tr>
							<th><?=translate('register_no')?></th>
							<td colspan="3"><span id="quick_register_no"></span></td>							
						</tr>
						<tr>
							<th><?=translate('admission_date')?></th>
							<td><span id="quick_admission_date"></span></td>
							<th><?=translate('date_of_birth')?></th>
							<td><span id="quick_date_of_birth"></span></td>
						</tr>
						<tr>
							<th><?=translate('email')?></th>
							<td colspan="3"><span id="quick_email"></span></td>
						</tr>
						<tr>
							<td colspan="4">
								<div class="mb-sm mt-sm ml-sm checkbox-replace">
									<label class="i-checks"><input type="checkbox" name="attendance" id="chkAttendance" value="L"><i></i> <?=translate('late')?></label>
									<div class="mt-sm">
										<input class="form-control" name="remark" id="attendanceRemark" autocomplete="off" type="text" placeholder="Remarks" value="">
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default btn-confirm mr-xs mt-xs" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="far fa-check-circle"></i> <?=translate('confirm')?></button>
					<button class="btn btn-default modal-dismiss mt-xs"><?=translate('close')?></button>
				</div>
			</div>
		</footer>
	</section>

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="quiz_remark_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-dialog-centered">
			<div class="modal-header">
				<h5 class="modal-title"><?=translate('exam_remarks')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 form-group">
						<label><?=translate('available_exams')?></label>
						<?php
							$keys = array_keys($exams_dropdown);
							echo form_dropdown("exam_id", $exams_dropdown, $keys[0], "class='form-control exam_option'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<div class="col-md-12 form-group">
						<label><?=translate('student_degree')?></label>
						<input type="number" class="form-control quiz_degree_input" name="quiz_degree" style="margin-bottom: 10px;">
					</div>
					<input type="hidden" id="quiz_student_id" name="quiz_student_id">
					<input type="hidden" id="quiz_class_id" name="quiz_class_id">
					<input type="hidden" id="quiz_section_id" name="quiz_section_id">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save_quiz_result">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- <audio id="successAudio">
  <source src="<?php //echo base_url('assets/vendor/qrcode/success.mp3') ?>" type="audio/ogg">
</audio> -->

<?php endif; ?>

<script type="text/javascript">
	var statusMatched = "<?php echo translate('matched')?>";
	var statusScanning = "<?php echo translate('scanning')?>";
	
	$(document).ready(function() {
		var test1 = initDatatable('.table-question', 'qrcode_attendance/getStuListDT',{
			'branch_id': '<?php echo $branch_id; ?>',
			'class_id': '<?php echo $class_id; ?>',
			'section_id': '<?php echo $section_id; ?>',
			'date': '<?php echo $date; ?>',
		});
		var test2 = initDatatable('.table-studentabsent', 'qrcode_attendance/getStuListDTAbsent',{
			'branch_id': '<?php echo $branch_id; ?>',
			'class_id': '<?php echo $class_id; ?>',
			'section_id': '<?php echo $section_id; ?>',
			'date': '<?php echo $date; ?>',
		});
	});

	var x = document.getElementById("successAudio");
	var lastResult, modalOpen = 0;
	const html5QrCode = new Html5Qrcode("reader");
	const qrCodeSuccessCallback = (decodedText, decodedResult) => {
		if (decodedText !== lastResult && modalOpen == 0) {
			x.play();
			lastResult = decodedText;
			modalOpen = 1;
			$('#attendanceRemark').val('');
			$('#chkAttendance').prop('checked', false);
			studentQuickView(decodedText);
	    $("#qr_status").html(statusMatched);
	    html5QrCode.clear();
		}
	};

	const formatsToSupport = [
		Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.CODE_128,
	];

	var config = { fps: 50, qrbox: 200};
	if($(window).width()  <= '400'){
		config = { fps: 50, qrbox: 150};
	}
	if($(window).width()  <= '370'){
		config = { fps: 50, qrbox: 120};
	}

	// if you want to prefer front camera
	html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback).catch((err) => {
	 	$("#qr_status").css("background", "red");
	 	$("#reader").addClass("camera-preview").html("Back camera not found.");
	 	$("#qr_status").html(err);
	  alert('Back camera not found.');
	 	console.log(err);
	});
	
	$('.btn-confirm').on('click', function() {
		var chkAttendance = $("#chkAttendance:checked").val();
		var attendanceRemark = $("#attendanceRemark").val();
	    var btn = $(this);
	    $.ajax({
	        url: base_url + 'qrcode_attendance/setStuAttendanceByQrcode',
	        type: 'POST',
	        data: {
	        	'data': lastResult,
	        	'late': chkAttendance,
	        	'attendanceRemark' : attendanceRemark
	        },
	        dataType: 'json',
	        beforeSend: function () {
	            btn.button('loading');
	        },
	        success: function (res) {
	        	if (res.status == 1) {
					$('.table-question').DataTable().ajax.reload();
	        	}
				$("#qr_status").html(statusScanning);
				modalOpen = 0;
	        },
	        complete: function (data) {
	            btn.button('reset'); 
	            $.magnificPopup.close();
	        },
	        error: function () {
	            btn.button('reset');
	        }
	    });
	});

	$('.modal-dismiss').on('click', function() {
		end_loader()
	});


	// attendance stored in the database
	// $(document).on('keydown', function(event) {
	// 	alert('hi');
	// 	if (event.keyCode === 13) { // Check if the Enter key is pressed
	// 		event.preventDefault(); // Prevent the default form submission
	// 		// Trigger the click event on the confirm button
	// 		alert('hi');
	// 	}
	// });
</script>








<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.4/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/jbdemonte/barcode/jquery/jquery-barcode.js"></script>

<script type="text/javascript">
	$(document).ready(function(){

		check_closed_attendance();
		function check_closed_attendance(){
			$.ajax({
				url: base_url + 'qrcode_attendance/checkCloseAttendance',
				type: "POST",
				dataType:'json',
				data: {														
					'branch_id': '<?php echo $branch_id; ?>',
					'class_id': '<?php echo $class_id; ?>',
					'section_id': '<?php echo $section_id; ?>',
					'date': '<?php echo $date; ?>',	
				},
				success:function(res) {
					if(res == '1'){
						$('.close_attendance').hide();
						$('.add_student_attendance_form').hide();
					}
				}
			});
		}


		setTimeout(function(){
			$('.student_code').focus();
		},200)

		// setTimeout(function(){
		// 	// Simulate barcode scanning by generating a random barcode value
		// 	const barcodeValue = Math.floor(Math.random() * 1000000000).toString();

		// 	// Set the barcode value to the input field
		// 	$('#barcodeInput').val(barcodeValue);

		// 	// Generate the barcode using jquery-barcode
		// 	$('#barcodeDisplay').barcode(barcodeValue, 'ean13');
		// },1000)


		// $(document).on('keydown',(e)=> {			
		// 	const textInput = e.key || String.fromCharCode(e.keyCode);
		// 	const targetName = e.target.localName;
		// 	let newUPC = '';
		// 	show_message=true;

		// 	if (textInput && textInput.length === 1 && typeof parseInt(e.key) == 'number' && !isNaN(parseInt(e.key))){
		// 		UPC += textInput;
		// 		event.preventDefault();
		// 	}

		// 	setTimeout(()=>{				
		// 		UPC = '';
		// 	},1000);
			
		// 	if (e.key == "Enter"  && UPC) {
		// 		alert(UPC);
		// 		event.preventDefault();
		// 		this.search_barcode = UPC;
		// 		UPC = '';
		// 	} 
		// });


		// Add an event listener to listen for keydown events
		$(document).on('keydown', function(event) {
			// Check if the key pressed is the Enter key (key code 13)
			if (event.keyCode === 13) {
				if (!isEventInPopupMarks(event) && !isEventInPopupAttendance(event)) {
					// Prevent the default behavior of the Enter key
					event.preventDefault();	
					// Do something with the barcode value (e.g., display it, send it to a server, etc.)
					saveAttendanceToDatabase();
				}

				//if inside the popup it means inside the mark popup
				if (isEventInPopupMarks(event)) {
					// Prevent the default behavior of the Enter key
					event.preventDefault();
					// Trigger the click event of the save button inside the popup
					$('.save_quiz_result').click();
				}

				//if inside the popup it means inside the mark popup
				if (isEventInPopupAttendance(event)) {
					// Prevent the default behavior of the Enter key
					event.preventDefault();
					// Trigger the click event of the save button inside the popup
					$('.btn-confirm').click();
				}
			}
		});

		function isEventInPopupMarks(event) {
			return $(event.target).closest('.modal-dialog').length > 0;
		}

		function isEventInPopupAttendance(event) {
			return $(event.target).closest('.mfp-content').length > 0;
		}

		$(document).on('click','.close_attendance',function(){
			swal({
				title: "Are You Sure?",
				text: "<?=translate('close') . ' ' . translate('the_attendance');?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-default swal2-btn-default",
				cancelButtonClass: "btn btn-default swal2-btn-default",
				confirmButtonText: "Yes, Continue",
				cancelButtonText: "Cancel",
				buttonsStyling: false,
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: base_url + 'qrcode_attendance/closeAttendance',
						type: "POST",
						data: {														
							'branch_id': '<?php echo $branch_id; ?>',
							'class_id': '<?php echo $class_id; ?>',
							'section_id': '<?php echo $section_id; ?>',
							'date': '<?php echo $date; ?>',	
						},
						success:function(data) {
							$('.table-studentabsent').DataTable().ajax.reload();	
							$('.close_attendance').hide();
						}
					});
				}
			});
		})

		$(document).on('click','.add_barcode_manually',function(){
			saveAttendanceToDatabase();
		})


		function saveAttendanceToDatabase(){
			modalOpen = 1;
			$('#attendanceRemark').val('');
			$('#chkAttendance').prop('checked', false);
			studentQuickViewBarcode($('.student_code').val(),'<?php echo $date; ?>', '<?php echo $section_id; ?>');
		}
	})

	// attendance stored in the database
	$('.btn-confirm').on('click', function() {
		var chkAttendance = $("#chkAttendance:checked").val();
		var attendanceRemark = $("#attendanceRemark").val();
	    var btn = $(this);
	    $.ajax({
	        url: base_url + 'qrcode_attendance/setStuAttendanceBybarcode',
	        type: 'POST',
	        data: {
	        	'data': $('.student_code').val(),
	        	'late': chkAttendance,
	        	'attendanceRemark' : attendanceRemark,
				'branch_id': '<?php echo $branch_id; ?>',
				'class_id': '<?php echo $class_id; ?>',
				'section_id': '<?php echo $section_id; ?>',
				'date': '<?php echo $date; ?>',
	        },
	        dataType: 'json',
	        beforeSend: function () {
	            btn.button('loading');
	        },
	        success: function (res) {
	        	if (res.status == 1) {
					$('.table-question').DataTable().ajax.reload();					
	        	}
				$("#qr_status").html(statusScanning);
				modalOpen = 0;
				after_finish_barcode_scan();		
	        },
	        complete: function (data) {
	            btn.button('reset'); 
	            $.magnificPopup.close();
				after_finish_barcode_scan();				
	        },
	        error: function () {
	            btn.button('reset');
				after_finish_barcode_scan();			
	        }
	    });
	});

	$('.modal-dismiss').on('click', function() {
		end_loader()
	});

	var dayOfWeekDisabled = "<?php echo $getWeekends ?>";
	$(document).ready(function () {
		var datePicker = $("#attDate").datepicker({
		    orientation: 'bottom',
		    todayHighlight: true,
		    autoclose: true,
		    format: 'yyyy-mm-dd',
		    daysOfWeekDisabled: dayOfWeekDisabled,
		    datesDisabled: ["<?php echo $getHolidays ?>"],
		});   
    });
</script>


<script>
	$(document).ready(function(){
		window.recordid = '';
		$(document).on('click','.quiz_remark',function(){
			window.recordid = $(this).data('recordid');
			$.ajax({
				url: base_url + 'qrcode_attendance/getStuExamMark',
				type: 'POST',
				data: {
					'recordid': window.recordid,
				},
				dataType: 'json',
				success: function (res) {
					$('#quiz_remark_modal').modal("show");
					$('.quiz_degree_input').val(res['mark']);
					$('.exam_option').val(res['exam_id']);

					setTimeout(function(){
						$('.quiz_degree_input').focus();
					},500)
				},
			});						
		})
		$(document).on('click','.save_quiz_result',function(){
			var parent = this;			
			var exam_id = '<?php echo $keys[0]; ?>';
			var mark = $(this).closest('.modal-dialog').find('.quiz_degree_input').val();
			$.ajax({
				url: base_url + 'qrcode_attendance/setStuExamMark',
				type: 'POST',
				data: {
					'recordid': window.recordid,
					'exam_id' : exam_id,
					'mark': mark,
				},
				dataType: 'json',
				success: function (res) {					
					// $('.table-question').DataTable().ajax.reload();
					$('.quiz_remark[data-recordid='+window.recordid+']').closest('td').find('.exam_result').html(' / ' + mark);
					$(".modal").modal("hide");
                    $(".modal-backdrop").remove(); 
					send_attendence_whatsapp_message(res);
				},
			});

			function send_attendence_whatsapp_message(res){	
				var message = ''
				+'اسم الطالب: ' + '' +res['student_name']+''  + '%0a'				
				+'ID: ' + '' +res['register_no']+''  + '%0a'				
				+'التاريخ: ' + '' +res['exam_date']+''  + '%0a'				
				+'السنتر: ' + '' +res['center']+''  + '%0a'				
				+'درجة الطالب: ' + '' +res['student_mark']+'/' + res['exam_mark'] + '%0a'				
				+'برجاء حفظ الرقم لإستكمال متابعة الطالب' + '%0a'				
				+'فريق عمل مستر هاني مجدي'			
				;
				var url = "whatsapp://send?phone=2" + res['parent_mobileno'] + "&text=" + (message);
				window.open(url);
			}
			
		});

		$(document).on('click','.message_absense',function(){			
			var recordid = $(this).data('recordid');
			$(this).css('background','orange');
			$.ajax({
				url: base_url + 'qrcode_attendance/get_student_details_notattend',
				type: 'POST',
				data: {
					'recordid': recordid,
				},
				dataType: 'json',
				success: function (res) {															
					send_notAttend_whatsapp_message(res);
				},
			});

			function send_notAttend_whatsapp_message(res){
				var message = ''
				+'اسم الطالب: ' + '' +res['student_name']+''  + '%0a'				
				+'ID: ' + '' +res['register_no']+''  + '%0a'				
				+'التاريخ: ' + '' +res['exam_date']+''  + '%0a'				
				+'السنتر: ' + '' +res['center']+''  + '%0a'				
				+'لم يتم الحضور اليوم' + '%0a'				
				+'برجاء حفظ الرقم لإستكمال متابعة الطالب' + '%0a'				
				+'فريق عمل مستر هاني مجدي'			
				;
				var url = "whatsapp://send?phone=2" + res['parent_mobileno'] + "&text=" + (message);
				window.open(url, '_blank');
			}
		})
	})

</script>