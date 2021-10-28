<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $tab = 1 ?>

<input type="hidden" name="tab" id="tab" />

<div class="tabs">
	<div class="tab-header">
		<a href="javascript:void(0)" class="tab1 <?php echo  $tab == 1 ? 'current' : '' ?>" rel="1"
			onclick="App.Tabs.activate(this)"><?php echo  lang('reports_ms_form_milestone') ?></a>
		<a href="javascript:void(0)" class="tab2 <?php echo  $tab == 2 ? 'current' : '' ?>" rel="2"
			onclick="App.Tabs.activate(this)"><?php echo  lang('reports_ms_form_activity') ?></a>
		<a href="javascript:void(0)" class="tab3 <?php echo  $tab == 3 ? 'current' : '' ?>" rel="3"
			onclick="App.Tabs.activate(this)"><?php echo  lang('reports_ms_form_tests') ?></a>
	</div>
	<div class="tab-body tab-frame">
		<div class="tab tab1 <?php echo  $tab != 1 ? 'hidden' : '' ?>">
			<?php $report_obj->render_control(
				$controls,
				'milestones_select',
				array(
					'top' => true,
					'project' => $project
				)
			) ?>
			<p><?php echo  lang('reports_ms_form_details_include') ?></p>
			<div class="checkbox form-checkbox" style="margin-left: 15px">
				<label>
					<?php echo  lang('reports_ms_form_details_include_status') ?>
					<input type="checkbox" id="custom_status_include"
						name="custom_status_include" value="1"
						<?php echo  validation::get_checked('custom_status_include',1) ?> />
				</label>
			</div>
			<div class="checkbox form-checkbox" style="margin-left: 15px">
				<label>
					<?php echo  lang('reports_ms_form_details_include_activity') ?>
					<input type="checkbox" id="custom_activities_include"
						name="custom_activities_include" value="1"
						<?php echo  validation::get_checked('custom_activities_include',1) ?> />
				</label>
			</div>
			<div class="checkbox form-checkbox" style="margin-left: 15px">
				<label>
					<?php echo  lang('reports_ms_form_details_include_progress') ?>
					<input type="checkbox" id="custom_progress_include"
						name="custom_progress_include" value="1"
						<?php echo  validation::get_checked('custom_progress_include',1) ?> />
				</label>
			</div>
			<div class="checkbox" style="margin-left: 15px">
				<label>
					<?php echo  lang('reports_ms_form_details_include_tests') ?>
					<input type="checkbox" id="custom_tests_include"
						name="custom_tests_include" value="1"
						<?php echo  validation::get_checked('custom_tests_include',1) ?> />
				</label>
			</div>
		</div>
		<div class="tab tab2 <?php echo  $tab != 2 ? 'hidden' : '' ?>">
			<?php $report_obj->render_control(
				$controls,
				'activities_daterange',
				array(
					'top' => true
				)
			) ?>
			<?php $report_obj->render_control($controls, 'activities_statuses') ?>
			<?php $report_obj->render_control(
				$controls,
				'activities_limit',
				array(
					'intro' => lang('report_plugins_activities_limit'),
					'limits' => array(10, 25, 50, 100, 250, 500, 1000)
				)
			) ?>
		</div>
		<div class="tab tab3 <?php echo  $tab != 3 ? 'hidden' : '' ?>">
			<?php $report_obj->render_control(
				$controls,
				'tests_filter',
				array(
					'project' => $project,
					'top' => true
				)
			) ?>
			<?php $report_obj->render_control(
				$controls,
				'tests_columns',
				array(
					'columns' => $test_columns
				)
			) ?>
			<?php $report_obj->render_control(
				$controls,
				'tests_limit',
				array(
					'intro' => lang('report_plugins_tests_limit'),
					'limits' => array(10, 25, 50, 100, 250, 500, 1000, 0)
				)
			) ?>
		</div>
	</div>
</div>

<div style="margin-top: 1em">
	<?php $report_obj->render_control($controls, 'content_hide_links') ?>
</div>
