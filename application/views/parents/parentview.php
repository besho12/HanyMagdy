
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
        .container2 {
            padding: 0px 60px;
        }
        .panel-body {
            box-shadow: 3px 5px 10px rgb(0 0 0 / 28%) !important
        }
        .profile-head {
            background-image: unset;
        }
        .profile-head h5 , .profile-head p , .profile-head ul li{
            color: #000;
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

    <div class="col-md-12 mb-lg">
		<div class="profile-head">
			<div class="col-md-12 col-lg-4 col-xl-3">
				<div class="image-content-center user-pro">
					<div class="preview">
						<img src="http://localhost/HanyMagdy/uploads/app_image/defualt.png">
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-5 col-xl-5">
				<h5><?php echo $student->first_name . ' ' .$student->last_name; ?></h5>
				<p>Student / </p>
				<ul>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="Class"><i class="fas fa-school"></i></div>  <?php echo $student->class_name; ?> ( <?php echo $student->section_name; ?> )</li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="Mobile No"><i class="fas fa-phone-volume"></i></div> <?php echo $student->mobileno; ?> - رقم الطالب</li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="Parent Mobile No"><i class="fas fa-phone-volume"></i></div> <?php echo $student->parent_mobileno; ?> - رقم ولي الأمر</li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="School"><i class="fa-solid fa-school-flag"></i></div> مدرسة (<?php echo $student->school; ?>)</li>
					<li><div class="icon-holder" data-toggle="tooltip" data-original-title="Center"><i class="fa-solid fa-building-flag"></i></div> سنتر (<?php echo $student->center; ?>)</li>
				</ul>
			</div>
		</div>
	</div>

    <div class="col-md-12 col-lg-4 col-xl-3">
        <section class="panel pg-fw">
            <div class="panel-body">
                <h4 class="chart-title mb-xs">Income Vs Expense Of February</h4>
                <div id="cash_book_transaction" _echarts_instance_="ec_1707934173157" style="-webkit-tap-highlight-color: transparent; user-select: none; position: relative;"><div style="position: relative; overflow: hidden; width: 372px; height: 293px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas data-zr-dom-id="zr_0" width="372" height="293" style="position: absolute; left: 0px; top: 0px; width: 372px; height: 293px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div style="position: absolute; display: none; border-style: solid; white-space: nowrap; z-index: 9999999; transition: left 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s, top 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s; background-color: rgba(50, 50, 50, 0.7); border-width: 0px; border-color: rgb(51, 51, 51); border-radius: 4px; color: rgb(255, 255, 255); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 5px; left: 70px; top: 79px;">Transaction <br>Expense : $ 0.00 (0%)</div></div>
                <div class="round-overlap"><i class="fab fa-sellcast"></i></div>
                <div class="text-center">
                    <ul class="list-inline">
                        <li>
                            <h6 class="text-muted"><i class="fa fa-circle text-blue"></i> Income</h6>
                        </li>
                        <li>
                            <h6 class="text-muted"><i class="fa fa-circle text-danger"></i> Expense</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-12 col-lg-8 col-xl-9">
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

