
<!doctype html>
<html class="fixed sidebar-left-sm sidebar-light">
    
<?php $this->load->view('layout/header.php');?>

<header class="header">
    <div class="logo-env">
        <a href="http://localhost/HanyMagdy/dashboard" class="logo">
            <img src="http://localhost/HanyMagdy/uploads/app_image/logo-small.png" height="40">
		</a>
        
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

    <style>
        .profile-head a {
            color: #000 !important;
        }
        .container2 {
            padding: 0px 60px;
        }
        .panel-body {
            box-shadow: 3px 5px 10px rgb(0 0 0 / 28%) !important
        }
        .profile-head {
            background-image: unset;
            /* background: #fff; */
            /* box-shadow: 3px 5px 10px rgb(0 0 0 / 28%) !important; */
        }
        .profile-head h5 , .profile-head p , .profile-head ul li{
            color: #000;
        }
        .profile-head::before {
            width: 80% !important;
        }
        .image-content-center.user-pro {
            /* background: unset;
            background-color: unset !important;
            box-shadow: unset;
            border: unset; */
            height: 217px;
        }
        .image-content-center .preview img {
            /* top: 25%; */
        }
        @media (max-width: 991px){
            .image_container {
                display: none;     
            }
            .profile-head::before {
                width: 100% !important;
            }
        }
        .profile-head::before {
            background: none;
            display: none;
        }
    </style>
</header>

<!-- <body class="loading-overlay-showing" data-loading-overlay> -->
<?php if ($global_config['preloader_backend'] == 1) { ?>
<body class="loading-overlay-showing" data-loading-overlay>
	<!-- page preloader -->
	<div class="loading-overlay dark">
		<div class="ring-loader">
			Loading <span></span>
		</div>
	</div>
<?php } else { ?>
<body>
<?php } ?>


<div class="container2" style="margin-top: 100px;">

    <div class="col-md-6 mb-lg">
        <div class="panel-body">
            <div class="panel-heading">
                <h4 class=" mb-xs"><i class="fas fa-user-graduate"></i> Student Details</h4>
            </div>
            <div class="profile-head">
                <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 image_container">
                    <div class="image-content-center user-pro">
                        <div class="preview">
                            <img src="http://localhost/HanyMagdy/uploads/app_image/defualt.png">
                        </div>
                    </div>
                </div> -->

                    <h5><?php echo $student->first_name . ' ' .$student->last_name; ?></h5>
                    <p>Student / </p>
                    <ul>
                        <li><div class="icon-holder" data-toggle="tooltip" data-original-title="Class"><i class="fas fa-school"></i></div>  <?php echo $student->class_name; ?> ( <?php echo $student->section_name; ?> )</li>
                        <!-- <li><div class="icon-holder" data-toggle="tooltip" data-original-title="Mobile No"><i class="fas fa-phone-volume"></i></div> <?php //echo $student->mobileno; ?> - رقم الطالب</li> -->
                        <!-- <li><div class="icon-holder" data-toggle="tooltip" data-original-title="Parent Mobile No"><i class="fas fa-phone-volume"></i></div> <?php //echo $student->parent_mobileno; ?> - رقم ولي الأمر</li> -->
                        <li><div class="icon-holder" data-toggle="tooltip" data-original-title="School"><i class="fa-solid fa-school-flag"></i></div> مدرسة (<?php echo $student->school; ?>)</li>
                        <li><div class="icon-holder" data-toggle="tooltip" data-original-title="Center"><i class="fa-solid fa-building-flag"></i></div> سنتر (<?php echo $student->center; ?>)</li>
                        <li><div class="icon-holder" data-toggle="tooltip" data-original-title="Center"><i class="fas fa-barcode"></i></div> Student Code: (<?php echo $student->register_no; ?>)</li>
                    </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-lg">
        <div class="panel-body">
            <div class="panel-heading">
                <h4 class=" mb-xs"><i class="fas fa-user-graduate"></i> Whatsapp Groups</h4>
            </div>
            <div class="profile-head">
                <ul>
                    <?php foreach($whatsapp as $single): ?>
                        <li><div class="icon-holder"><i class="fa-brands fa-whatsapp" style="font-weight: bold;"></i></div><a target="_blank" href="<?php echo $single['link']; ?>"><?php echo $single['name']; ?></a></li>                    
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel pg-fw">
            <div class="panel-body">
                <h4 class="panel-title chart-title mb-xs"><i class="fas fa-user-graduate"></i> Exam Marks</h4>
                <div class="">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Exam Name</th>
                                <th>Date</th>
                                <th>Exam Type</th>
                                <th>Marks</th>                                                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($marks as $row): ?>
                            <tr>                       
                                <td><?php echo $row['register_no']; ?></td>                                                                
                                <td><?php echo $row['first_name']; ?></td>                                                                
                                <td><?php echo $row['exam_name']; ?></td>                                                                
                                <td><?php echo $row['exam_date']; ?></td>                                                                
                                <td><?php echo $row['exam_period']; ?></td>                                                                
                                <td>                            
                                <?php $distributions = json_decode($row['mark'], true);
                                    if(!$distributions){
                                        echo '<span class="text-warning">Absent</span>';
                                    } else {                                
                                        foreach ($distributions as $i => $value) { 
                                            $distributions_masterData = get_distributions_masterData($row['exam_id']);  
                                            $distributions_masterData = ($distributions_masterData[0]['mark_distribution']); 
                                            $distributions_masterData = json_decode($distributions_masterData,true);
                                            $full_mark = $distributions_masterData[$i]['full_mark'];
                                            $pass_mark = $distributions_masterData[$i]['pass_mark'];                                
                                        
                                            echo get_type_name_by_id('exam_mark_distribution', $i) . " (" . $value . '/' . $full_mark . ")";
                                            if($value >= $pass_mark){
                                                echo '<span class="text-success" style="margin-left:5px;">Succeeded</span>';
                                            } else {
                                                echo '<span class="text-danger" style="margin-left:5px;">Failed</span>';
                                            }                                   
                                            echo '<br>';
                                        }                                 
                                    }
                                ?>
        
                                </td>                                                                
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    



</div>
 
<?php $this->load->view('layout/script.php');?>
       
</body>
</html>

