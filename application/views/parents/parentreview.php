
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


<div class="container" style="margin-top: 100px;">
    <section class="panel appear-animation fadeInUp appear-animation-visible" data-appear-animation="fadeInUp" data-appear-animation-delay="100" style="animation-delay: 100ms;">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-user-graduate"></i> Exam Marks</h4>
        </header>
        <div class="panel-body mb-md">
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
    </section>
</div>
 
<?php $this->load->view('layout/script.php');?>
       
</body>
</html>