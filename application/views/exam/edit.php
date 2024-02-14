<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?=base_url('exam')?>"><i class="fas fa-list-ul"></i> <?=translate('exam_list')?></a>
			</li>
			<li class="active">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('edit_exam')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit'));?>
				<div class="form-horizontal form-bordered mb-lg">
					<input type="hidden" name="exam_id" value="<?=$exam['id']?>">
					<input type="hidden" name="branch_id" value="<?=$exam['branch_id']?>">
						
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('name')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="<?=$exam['name']?>" />
								<span class="error"></span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('total_mark')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<input type="number" class="form-control" name="total_mark" value="<?=$exam['total_mark']?>" />
								<span class="error"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('exam_date')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" class="form-control" name="exam_date" id="attDate" value="<?=$exam['exam_date']?>" autocomplete="off">
									<span class="input-group-addon"><i class="icon-event icons"></i></span>
								</div>
								<span class="error"></span>
							</div>
						</div>

						<div class="form-group" style="display: none;">
							<label class="col-md-3 control-label"><?=translate('exam_type')?></label>
							<div class="col-md-6">
								<?php
									$arrayType = array(
										'' => translate('select'), 
										'1' => translate('marks'), 
										'2' => translate('grade'), 
										'3' => translate('marks_and_grade'), 
									);
									echo form_dropdown("type_id", $arrayType, '1', "class='form-control' id='type_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
								?>
								<span class="error"></span>
							</div>
						</div>
						<?php if(1==2): ?>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('term')?></label>
								<div class="col-md-6">
									<?php
										$array = $this->app_lib->getSelectByBranch('exam_term', $exam['branch_id']);
										echo form_dropdown("term_id", $array, $exam['term_id'], "class='form-control' id='term_id'
										data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
									?>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('mark_distribution')?></label>
								<div class="col-md-6">
									<?php
										$sel = json_decode($exam['mark_distribution'], true);
										$arraySection = array();
										$result = $this->db->where('branch_id', $exam['branch_id'])->get('exam_mark_distribution')->result();
										foreach ($result as $row) {
											$arraySection[$row->id] = $row->name;
										}
										echo form_dropdown("mark_distribution[]", $arraySection, $sel, "class='form-control' multiple id='mark_distribution'
										data-plugin-selectTwo data-width='100%'");
									?>
									<span class="error"></span>
								</div>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="col-md-3 control-label">Exam Period</label>							
							<div class="col-md-6">
								<div class="radio-custom radio-warning radio-inline mb-xs">
									<input type="radio" value="Weekly" name="exam_period" <?=($exam['exam_period'] == 'Weekly' ? 'checked' : '')?> />
									<label for="sstatus_1">Weekly</label>
								</div>

								<div class="radio-custom radio-warning radio-inline">
								<input type="radio" value="Monthly" name="exam_period" <?=($exam['exam_period'] == 'Monthly' ? 'checked' : '')?> />
									<label for="sstatus_2">Monthly</label>
								</div>
							</div>							
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?=translate('remarks')?></label>
							<div class="col-md-6 mb-sm">
								<textarea rows="2" class="form-control" name="remark"><?=$exam['remark']?></textarea>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('update')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '#branch_id', function() {
			var branchID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getDataByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'exam_term'
				},
				success: function (data) {
					$('#term_id').html(data);
				}
			});

			$.ajax({
				url: "<?=base_url('exam/getDistributionByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
				},
				success: function (data) {
					$('#mark_distribution').html(data);
				}
			});
		});

		setTimeout(function(){
			var datePicker = $("#attDate").datepicker({
				orientation: 'bottom',
				todayHighlight: true,
				autoclose: true,
				format: 'yyyy-mm-dd',
			});  
		})
	});
</script>