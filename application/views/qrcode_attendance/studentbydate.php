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
						//set_value('branch_id')
						echo form_dropdown("branch_id", $arrayBranch, 1, "class='form-control' onchange='getClassByBranch(this.value)' id='branchID'
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

<?php if (isset($attendancelist)): ?>
	<section class="panel appear-animation mt-sm" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fas fa-users"></i> <?=translate('attendance_report')?></h4>
		</header>
		<div class="panel-body">
			<style type="text/css">
				table.dataTable.table-condensed > thead > tr > th {
				  padding-right: 3px !important;
				}
			</style>
			<!-- hidden school information prints -->
			<div class="export_title">Daily Attendance Sheet on <?=_d($date); ?></div>
			<div class="row">
				<div class="col-md-12">
					<div class="mb-lg">
						<table class="table table-bordered table-hover table-condensed mb-none text-dark table-export">
							<thead>
								<tr>
									<th>#</th>
									<th><?=translate('student')?></th>
                                    <th>Student ID</th>
                                    <th>Quiz Remark</th>
                                    <th>Quiz Degree</th>
<!--									<th>--><?//=translate('register_no')?><!--</th>-->
<!--									<th>--><?//=translate('roll')?><!--</th>-->
									<th><?=translate('attendance')?></th>
									<th><?=translate('in_time')?></th>
<!--									<th>--><?//=translate('remarks')?><!--</th>-->
                                    <th>Center</th>
                                    <th>Phone</th>
                                    <th>Parent Phone</th>
                                    <th>Quiz Remarks</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$count = 1; $totalStudent = $totalPresent = $totalAbsent = 0;
							foreach ($attendancelist as $row):
							?>
								<tr>
									<td><?php echo $count++ ?></td>
									<td><?php echo $row->fullname; ?></td>
                                    <td><?php echo $row->register_no; ?></td>
                                    <td>
                                        <?php
                                            $quiz_found = false;
                                            foreach ($quiz_result_list as $value) {
                                                if ($value->student_id == $row->student_id) {
                                                    $result = $value->quiz_mark * 100 / $value->quiz_degree;
                                                    if ($result < 50) {
                                                        echo 'Failed';
                                                    } else if ($result < 90) {
                                                        echo 'Good';
                                                    } else {
                                                        echo 'Excellent';
                                                    }
                                                    $quiz_found = true;
                                                    break;
                                                }
                                            }

                                            if (!$quiz_found) {
                                                echo 'No Result';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $quiz_found = false;
                                        foreach ($quiz_result_list as $value) {
                                            if ($value->student_id == $row->student_id) {
                                                $result = $value->quiz_mark * 100 / $value->quiz_degree;
                                                echo $value->quiz_mark . '/' . $value->quiz_degree;
                                                $quiz_found = true;
                                                break;
                                            }
                                        }

                                        if (!$quiz_found) {
                                            echo 'No Result';
                                        }
                                        ?>
                                    </td>
<!--									<td>--><?php //echo $row->register_no; ?><!--</td>-->
<!--									<td>--><?php //echo $row->roll; ?><!--</td>-->
									<td><?php
									if($row->status == "P")
										echo '<span class="label label-success">'.translate('present').'</span>';
									if($row->status == "A")
										echo '<span class="label label-danger">'.translate('absent').'</span>';
									 if($row->status == "L")
										echo '<span class="label label-warning">'.translate('late').'</span>';
									?></td>
									<td><?php echo _d($row->created_at) . " " . date('h:i A', strtotime($row->created_at)) ?></td>
<!--									<td>--><?php //echo empty($row->remark) ? '-' : $row->remark; ?><!--</td>-->
                                    <td><?php echo $row->center; ?></td>
                                    <td><?php echo $row->mobileno; ?></td>
                                    <td><?php echo $row->parent_mobileno; ?></td>
                                    <td><button class="btn btn-success quiz_remark" data-student-id="<?= $row->student_id; ?>" data-class-id="<?= set_value('class_id'); ?>" data-section-id="<?= set_value('section_id'); ?>">Quiz</button></td>
									<?php endforeach; ?>
								</tr>
							</tbody>
				
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

    <div class="modal fade" tabindex="-1" role="dialog" id="quiz_remark_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-dialog-centered">
                <div class="modal-header">
                    <h5 class="modal-title">Quiz Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Student Degree</label>
                            <input type="number" class="form-control" name="quiz_mark" id="quiz_mark">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Quiz Degree</label>
                            <input type="number" class="form-control" name="quiz_degree" id="quiz_degree_input" style="margin-bottom: 10px;">
                            <button class="btn btn-sm btn-primary quiz_marks" data-id="20" style="margin-right: 10px;">20</button><button class="btn btn-sm btn-primary quiz_marks" data-id="40" style="margin-right: 10px;">40</button>
                            <button class="btn btn-sm btn-primary quiz_marks" data-id="50" style="margin-right: 10px;">50</button><button class="btn btn-sm btn-primary quiz_others">Others</button>
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
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
<script type="text/javascript">
    $('.quiz_remark').on('click', function () {
        let student_id = $(this).data('student-id');
        let class_id = $(this).data('class-id');
        let section_id = $(this).data('section-id');

        $('#quiz_student_id').val(student_id)
        $('#quiz_class_id').val(class_id)
        $('#quiz_section_id').val(section_id)
        $('#quiz_degree_input').prop('disabled', true)
        $('#quiz_degree_input').val("")
        $('#quiz_mark').val("");
        $('#quiz_remark_modal').modal("show");
    })

    $('.quiz_others').on('click', function () {
        $('#quiz_degree_input').prop('disabled', false)
    })

    $('.quiz_marks').on('click', function () {
        $('#quiz_degree_input').prop('disabled', true)
        $('#quiz_degree_input').val($(this).data('id'))
    })

    $('.save_quiz_result').on('click', function () {
        let formdata = new FormData();
        let student_id = $('#quiz_student_id').val();
        let class_id = $('#quiz_class_id').val();
        let section_id = $('#quiz_section_id').val();
        let quiz_degree = $('#quiz_degree_input').val();
        let quiz_mark = $('#quiz_mark').val();

        if (quiz_mark.trim() && quiz_degree.trim() && Number(quiz_mark) <= Number(quiz_degree)) {
            formdata.append('student_id', student_id);
            formdata.append('class_id', class_id);
            formdata.append('section_id', section_id);
            formdata.append('quiz_degree', quiz_degree);
            formdata.append('quiz_mark', quiz_mark);
            formdata.append('date', '<?= set_value('date', date("Y-m-d")); ?>')
            axios.post('<?= site_url('qrcode_attendance/save_quiz_result'); ?>', formdata)
                .then(function (response) {
                    if (response.data == 'success') {
                        $('#quiz_remark_modal').modal("hide");
                        window.location.reload();
                    }
                })
        } else {
            alert('Student Degree should be less or equal than Quiz Degree')
        }
    })

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
    
    $(function() {
        var branchID = $('#branchID').val();
		getClassByBranch(branchID);
		setInterval(function() {
		    if($('#class_id').val() != 1) {
		        $('#class_id').val(1).trigger('change')
		    }
		   
		}, 1000)
    })
    
	$('select#branchID').change(function() {
		var branchID = $(this).val();
		$.ajax({
			url: base_url + "attendance/getWeekendsHolidays",
			type: 'POST',
			dataType: "json",
			data: {
				branch_id: branchID,
			},
			success: function (data) {
				$('#attDate').val("");
				$('#attDate').datepicker('setDaysOfWeekDisabled', data.getWeekends);
				$('#attDate').datepicker('setDatesDisabled', JSON.parse(data.getHolidays));
			}
		});
	});
</script>