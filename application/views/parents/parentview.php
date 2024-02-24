
<!doctype html>
<html class="fixed sidebar-left-sm sidebar-light">
    
<?php $this->load->view('layout/header.php');?>

<header class="header">
    <div class="logo-env">
        <a href="https://hanymagdy-thelegend.com/dashboard" class="logo">
            <img src="https://hanymagdy-thelegend.com/uploads/app_image/logo-small.png" height="40">
		</a>
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
            float: left;
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
            .container2 {
                padding: 0 !important;
            }
            .dataTables_wrapper .dt-buttons.btn-group {
                justify-content: left;
                margin-top: 20px;
            }
            .dataTables_wrapper .pagination {
                float: left;
            }
        }

        .display_none {
            display: none;
        }
        .display_block {
            display: block;
        }
        .profile-head::before {
            background: none;
            display: none;
        }
        .new_chart {
            max-height: 100%;
            width: 100%;
        }
        .new_chart canvas{
            max-height: 100%;
            width: 100%;
        }
        .panel-body {
            border-radius: 25px;
            margin-bottom: 20px;
        }
        .panel-heading {
            background: unset;
        }
        .panel-title {
            font-size: 26px;
            font-weight: bold;
            /* float: right; */
        }
        .float_r {
            float: right;
            padding-bottom: 10px;
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


<div class="container2" style="margin-top: 90px;">
    <div class="col-md-6 col-sm-12 mb-lg">
        <div class="panel-body">
            <h4 class="panel-title chart-title mb-xs float_r"> تفاصيل الطالب  <i class="fas fa-user-graduate"></i></h4>
            <!-- <div class="panel-heading">
                <h4 class=" mb-xs"><i class="fas fa-user-graduate"></i> تفاصيل الطالب</h4>
            </div> -->
            <div class="profile-head" style="float:left;">
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

    <div class="col-md-6 col-sm-12 mb-lg exam_marks_web">
        <div class="panel-body" style="height: 332px;">
            <h4 class="panel-title chart-title mb-xs float_r"> أداء الطالب <i class="fas fa-chart-line"></i></h4>
            <canvas id="myChart1" class="new_chart"></canvas>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx1 = document.getElementById('myChart1').getContext('2d');

                const DATA_COUNT1 = 12;
                const labels1 = [];
                for (let i = 0; i < DATA_COUNT1; ++i) {
                    labels1.push(i.toString());
                }
                const datapoints1 = <?php echo json_encode($marks_chart); ?>;
                const data1 = {
                    labels: labels1,
                    datasets: [
                        {
                            data: datapoints1,
                            backgroundColor:'#ffbd2e45',
                            borderColor: '#ffbd2e',
                            fill: true,
                            cubicInterpolationMode: 'monotone',
                            tension: 0.4
                        }
                    ]
                };

                new Chart(ctx1, {
                    type: 'line',
                    data: data1,
                    options:
                    {          
                        maintainAspectRatio: false,
                        responsive: true,
                        animation: {
                            duration: 2500
                        },
                        legend: {
                            display: false
                        },
                        tooltips: { 
                            mode: 'label', 
                            label: 'mylabel', 
                            callbacks: { 
                                label: function(tooltipItem, data) { 
                                    return tooltipItem.xLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                },
                            }
                        },                    
                        responsive: true,
                        plugins: {
                            title: {
                                display: false,
                                text: ''
                            },
                            legend: {
                                display: false
                            },
                        },
                        interaction: {
                            intersect: false
                        },
                        scales: {
                            x: {
                                display: false,
                            },
                            y: {
                                display: false,
                            }
                        },
                        elements: {
                            line: {
                                borderWidth: 2
                            },
                            point: {
                                radius: 0
                            }
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 40
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>


    <div class="col-md-6 mb-lg exam_marks_web">
        <section class="pg-fw" style="margin-bottom: 80px;">
            <div class="panel-body">
                <h4 class="panel-title chart-title mb-xs float_r"> درجات الطالب <i class="fas fa-clipboard-list"></i></h4>
                <div class="">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>Exam Name</th>
                                <th>Date</th>
                                <th>Exam Type</th>
                                <th>Marks</th>                                                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($marks as $row): ?>
                            <tr>                       
                                <td><?php echo $row['exam_name']; ?></td>                                                                
                                <td><?php echo date_format(date_create($row['exam_date']), 'd/m/Y') ?></td>                                                                
                                <td><?php echo $row['exam_period']; ?></td>   
                                
                                <?php
                                    // if($row['mark'] > ($row['total_mark'] / 2)) {
                                    //     $mark_status = '<span class="text-success">Succeeded</span>';
                                    // } else {
                                    //     $mark_status = '<span class="text-danger">Failed</span>';
                                    // }
                                ?>
                                <td><?php echo $row['mark'] . '/' . $row['total_mark'] /* . ' - ' . $mark_status;*/ ?></td>                                                                
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-6 mb-lg exam_marks_web">
        <div class="panel-body">
            <!-- <div class="panel-heading">
                <h4 class=" mb-xs"><i class="fa-brands fa-whatsapp"></i> WhatsApp Groups</h4>
            </div> -->
            <h4 class="panel-title chart-title mb-xs float_r"> جروبات الواتساب <i class="fab fa-whatsapp"></i> </h4>
            <div class="profile-head">
                <ul>
                    <?php foreach($whatsapp as $single): ?>
                        <li><div class="icon-holder"><i class="fa-brands fa-whatsapp" style="font-weight: bold;"></i></div><a target="_blank" href="<?php echo $single['link']; ?>"><?php echo $single['name']; ?></a></li>                    
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

  

    <div class="col-md-12 col-lg-12 col-xl-12 exam_marks_mob">
        <section class="pg-fw">
            <div class="panel-body">
                <h4 class="panel-title chart-title mb-xs float_r"> أداء الطالب <i class="fas fa-chart-line"></i></h4>
                <div>
                    <canvas id="myChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('myChart').getContext('2d');

                    const DATA_COUNT = 12;
                    const labels = [];
                    for (let i = 0; i < DATA_COUNT; ++i) {
                        labels.push(i.toString());
                    }
                   
                    
                    const datapoints = <?php echo json_encode($marks_chart); ?>;
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                data: datapoints,
                                backgroundColor:'#ffbd2e45',
                                borderColor: '#ffbd2e',
                                fill: true,
                                cubicInterpolationMode: 'monotone',
                                tension: 0.4
                            }
                        ]
                    };

                    new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options:
                        {          
                            maintainAspectRatio: true,
                            responsive: true,
                            animation: {
                                duration: 2500
                            },
                            legend: {
                                display: false
                            },
                            tooltips: { 
                                mode: 'label', 
                                label: 'mylabel', 
                                callbacks: { 
                                    label: function(tooltipItem, data) { 
                                        return tooltipItem.xLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    },
                                }
                            },                    
                            responsive: true,
                            plugins: {
                                title: {
                                    display: false,
                                    text: ''
                                },
                                legend: {
                                    display: false
                                },
                            },
                            interaction: {
                                intersect: false
                            },
                            scales: {
                                x: {
                                    display: false,
                                },
                                y: {
                                    display: false,
                                }
                            },
                            elements: {
                                line: {
                                    borderWidth: 2
                                },
                                point: {
                                    radius: 0
                                }
                            },
                            layout: {
                                padding: {
                                    top: 10
                                }
                            }
                        }
                    });
                </script>
            </div>
        </section>





        <section class="pg-fw exam_marks_mob">
            <div class="panel-body">
                <h4 class="panel-title chart-title mb-xs" style="width: 100%;">
                    <span class="float_r">درجات الامتحان <i class="fas fa-clipboard-list"></i> </span>
                    <input type="text" class="form-control exam_date" style="width: 100%; float:left;background:#fff;margin-top:10px;" readonly name="date" id="attDate" value="<?=set_value('date', date('F Y'))?>" autocomplete="off"/>
                    
                </h4>
                <div class="">
                    
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th style="width: 70px;">Type</th>
                                <th style="width: 70px;">Marks</th>                                                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($marks as $row): ?>
                            <tr>                       
                                <td><?php echo date_format(date_create($row['exam_date']), 'd/m/Y'); ?></td>                                                               
                                <td><?php echo $row['exam_period']; ?></td>   
                                <td><?php echo $row['mark'] . '/' . $row['total_mark']; ?></td>                                                                
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        
    </div>
    
    <div class="col-md-6 mb-lg exam_marks_mob">
        <div class="panel-body">
            <!-- <div class="panel-heading">
                <h4 class=" mb-xs"><i class="fa-brands fa-whatsapp"></i> WhatsApp Groups</h4>
            </div> -->
            <h4 class="panel-title chart-title mb-xs float_r"> جروبات الواتساب <i class="fab fa-whatsapp"></i> </h4>
            <div class="profile-head">
                <ul>
                    <?php foreach($whatsapp as $single): ?>
                        <li><div class="icon-holder"><i class="fa-brands fa-whatsapp" style="font-weight: bold;"></i></div><a target="_blank" href="<?php echo $single['link']; ?>"><?php echo $single['name']; ?></a></li>                    
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>


</div>
 

<script>
    $(document).ready(function(){
        window.mobileAndTabletCheck = function() {
            let check = false;
            (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
            return check;
        };
        if(!window.mobileAndTabletCheck()){ //web
            $('.exam_marks_mob').addClass('display_none'); 
            $('.exam_marks_web').addClass('display_block');
        } else { //mob
            $('.exam_marks_web').addClass('display_none');
            $('.exam_marks_mob').addClass('display_block');
        }
        $(document).on('change','.exam_date',function(){
            
        })
        var datePicker = $("#attDate").datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            format: 'MM yyyy',
            minViewMode: "months",
		});
    })
</script>
<?php $this->load->view('layout/script.php');?>
       
</body>
</html>

