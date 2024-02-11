<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-qrcode"></i> <?=translate('qr_code') . " " . translate('attendance')?></h4>
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
			<div class="form-group">
				<label class="control-label">Student ID</label>
				<input type="text" class="form-control student_code" />
				<button style="margin-top:5px;" type="button" class="add_barcode_manually btn btn btn-default">
					<i class="fas fa-plus-circle"></i> Add Manually							
				</button>
				
				<span class="error"></span>
			</div>
		</div>



	    <div class="col-md-8 col-sm-12  mt-md">
			<table class="table table-bordered table-hover table-condensed table-question nowrap"  cellpadding="0" cellspacing="0" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th><?=translate('name')?></th>
						<th><?=translate('class')?></th>
						<th>Student ID</th>
						<th><?=translate('date')?></th>
					</tr>
				</thead>
			</table>
	    </div>
	</div>
</section>
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

<audio id="successAudio">
  <source src="<?php echo base_url('assets/vendor/qrcode/success.mp3') ?>" type="audio/ogg">
</audio>

<script type="text/javascript">
	var statusMatched = "<?php echo translate('matched')?>";
	var statusScanning = "<?php echo translate('scanning')?>";
	
	$(document).ready(function() {
		initDatatable('.table-question', 'qrcode_attendance/getStuListDT');
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

	// attendance stored in the database
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
</script>








<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.4/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/jbdemonte/barcode/jquery/jquery-barcode.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.student_code').focus();
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
				// Prevent the default behavior of the Enter key
				event.preventDefault();
								
				// Do something with the barcode value (e.g., display it, send it to a server, etc.)
				saveAttendanceToDatabase();
			}
		});

		$(document).on('click','.add_barcode_manually',function(){
			saveAttendanceToDatabase();
		})


		function saveAttendanceToDatabase(){
			modalOpen = 1;
			$('#attendanceRemark').val('');
			$('#chkAttendance').prop('checked', false);
			studentQuickViewBarcode($('.student_code').val());
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
</script>