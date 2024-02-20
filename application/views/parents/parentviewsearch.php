




<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width,initial-scale=1" name="viewport">
	<meta name="keywords" content="">
	<title><?php echo translate('login');?></title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    
    <!-- Web Fonts  -->
	<link href="<?php echo is_secure('fonts.googleapis.com/css?family=Signika:300,400,600,700');?>" rel="stylesheet"> 
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
	<script src="<?php echo base_url('assets/vendor/jquery/jquery.js');?>"></script>
	
	<!-- sweetalert js/css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">
	<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>
	<!-- login page style css -->
	<link rel="stylesheet" href="<?php echo base_url('assets/login_page/css/style.css');?>">
	<script type="text/javascript">
		var base_url = '<?php echo base_url() ?>';
	</script>

    <style>
        .sign-area {
            background-color: #fff;
            box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) !important;
            color: #000 !important;
        }
        .sign-area::before {
            border-right: 60px solid #fff;
        }
        select {
            height: 40px !important;
            background: #fff !important;
            color: #000 !important;
            border-radius: 30px !important;
            padding-left: 35px !important;
        }
        select option {
            width: 80%; /* Apply the same border radius to the options */
        }
    </style>
</head>
	<body style="background-color: #ddd;">
        <div class="auth-main">
            <div class="container">
                <div class="slideIn">
                    <!-- image and information -->
                    <div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 no-padding fitxt-center">
                        <div class="image-area">
                        <div class="content">
                            <div class="image-hader">
                                <h2><?php echo translate('welcome_to');?></h2>
                            </div>
                            <div class="center img-hol-p">
                                <img src="https://hanymagdy-thelegend.com/uploads/app_image/logo.png" height="60" alt="School">
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
                        <div class="sign-area">
                            <div class="sign-hader">
                                <img src="https://hanymagdy-thelegend.com/uploads/app_image/logo.png" height="54" alt="">
                                <h3 style="font-weight: bold; text-decoration:underline;">PARENTS SERVICE CENTER</h3>
                            </div>
                            <?php echo form_open($this->uri->uri_string()); ?>

                                

                                <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon">
                                                <i class="fas fa-school-flag"></i>
                                            </span>
                                        </span>
                                        
                                        
                                        <select class="form-control" id="branch_id" name="branch_id">
                                            <option value="">Select Center</option>
                                        </select>

                                    </div>
                                    <span class="error"><?php echo form_error('email'); ?></span>
                                </div>
                               
                                <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon">
                                                <i class="fas fa-school-flag"></i>
                                            </span>
                                        </span>
                                        
                                        
                                        <select class="form-control" id="section_id" name="section_id">
                                            <option value="">Select Year</option>
                                        </select>

                                    </div>
                                    <span class="error"><?php echo form_error('email'); ?></span>
                                </div>

                                <div class="form-group <?php if (form_error('email')) echo 'has-error'; ?>">
                                    <div class="input-group input-group-icon">
                                        <span class="input-group-addon">
                                            <span class="icon">
                                                <i class="fas fa-barcode"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" style="background:#fff;font-size:16px;color:#000;" name="student_code" placeholder="Student Code" />
                                    </div>
                                    <span class="error"><?php echo form_error('email'); ?></span>
                                </div>
                               

                                <button type="submit" id="btn_submit" class="btn btn-block btn-round" style="color:#fff !important;">
                                    <i class="fas fa-search"></i> <?php echo translate('search');?>
                                </button>

                                <?php if(isset($not_found)): ?>
                                    <div class="text-danger" style="padding-top:10px;">
                                        This Student Doesn't Exist
                                    </div>
                                <?php endif ?>

                                <?php if(isset($fill_data)): ?>
                                    <div class="text-danger" style="padding-top:10px;">
                                        Please Fill Student Data First
                                    </div>
                                <?php endif ?>
                           
                                
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            $(document).ready(function(){
                $.ajax({
                    url: '<?=base_url('parentreview/getBranches')?>',
                    type: 'POST',
                    data:{
                        parent_view_screen: 'parent_view_screen'
                    },
                    success: function (data){
                        $('#branch_id').html(data);
                    }
                });

                $(document).on('change','#branch_id',function(){
                    $.ajax({
                        url: '<?=base_url('parentreview/getSectionByBranch')?>',
                        type: 'POST',
                        data:{
                            branch_id: $('#branch_id').val(),
                            parent_view_screen: 'parent_view_screen'
                        },
                        success: function (data){
                            $('#section_id').html(data);
                        }
                    });
                });
            })
        </script>
		<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/vendor/jquery-placeholder/jquery-placeholder.js');?>"></script>
		<!-- backstretch js -->
		<script src="<?php echo base_url('assets/login_page/js/jquery.backstretch.min.js');?>"></script>
		<script src="<?php echo base_url('assets/login_page/js/custom.js');?>"></script>

		<?php
		$alertclass = "";
		if($this->session->flashdata('alert-message-success')){
			$alertclass = "success";
		} else if ($this->session->flashdata('alert-message-error')){
			$alertclass = "error";
		} else if ($this->session->flashdata('alert-message-info')){
			$alertclass = "info";
		}
		if($alertclass != ''):
			$alert_message = $this->session->flashdata('alert-message-'. $alertclass);
		?>
			<script type="text/javascript">
				swal({
					toast: true,
					position: 'top-end',
					type: '<?php echo $alertclass;?>',
					title: '<?php echo $alert_message;?>',
					confirmButtonClass: 'btn btn-default',
					buttonsStyling: false,
					timer: 8000
				})
			</script>
		<?php endif; ?>
	</body>
</html>