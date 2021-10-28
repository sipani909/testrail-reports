<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php if (!$milestone->is_completed): ?>
<div class="statsContainer statsContainerHighlighted">
	<div class="statsIcon" style="float: left">
		<img src="%RESOURCE%:images/report-assets/goal.svg" height="32" width="32" alt="" />
	</div>
	<div class="statsRight" style="width: 225px">
		<?php if ($progress->projected_end_date === null): ?>
			<span class="estimate"><?php echo  lang('milestones_progress_estimate_unknown') ?></span><br />
			<span class="secondary"><em><?php echo  lang('milestones_progress_estimate_no_accuracy_nohelp') ?></em></span>
		<?php else: ?>
			<span class="estimate"><?php echo  date::format_short_date($progress->projected_end_date) ?></span><br />
			<?php if ($progress->forecast_accuracy == TP_FORECAST_ACCURACY_HIGH): ?>
			<span class="secondary"><em><?php echo  lang('milestones_progress_estimate_high_accuracy_nohelp') ?></em></span>
			<?php else: ?>
			<span class="secondary"><em><?php echo  lang('milestones_progress_estimate_low_accuracy_nohelp') ?></em></span>
			<?php endif ?>
		<?php endif ?>
	</div>
	<div class="statsContent" style="max-width: 450px; margin-right: 240px">
		<p style="margin: 0"><?php echo  lang('milestones_progress_estimate_desc') ?></p>
	</div>
	<div style="clear: both"></div>
</div>
<?php endif ?>

<div class="statsContainer">
	<div class="statsIcon">
		<img src="%RESOURCE%:images/report-assets/time.svg" height="32" width="32" alt="" />
	</div>
	<div class="statsRight" style="width: 225px">
		<?php if ($progress->test_count > 0): ?>
			<?php $tests_completed_percent = (int) (($progress->tests_completed / $progress->test_count) * 100) ?>
		<?php else: ?>
			<?php $tests_completed_percent = 0 ?>
		<?php endif ?>
		<p style="margin: 0"><?php echo  lang('milestones_progress_running_completed') ?>
			<?php echo  $tests_completed_percent ?>%
			(<?php echo  $progress->tests_completed ?>/<?php echo  $progress->test_count ?>)<br />
		<?php if (isset($progress->elapsed)): ?>
			<?php echo  lang('milestones_progress_running_elapsed') ?>
				<?php echo  timespan::format_hours($progress->elapsed) ?><br />
		<?php endif ?>
		<?php echo  lang('milestones_progress_running_tests_day') ?> <?php echo  $progress->tests_per_day ?><br />
		<?php echo  lang('milestones_progress_running_hours_day') ?>
		<?php if ($progress->hours_per_day !== null): ?>
			<?php echo  $progress->hours_per_day ?>
		<?php else: ?>
			<?php echo  lang('layout_not_avail') ?>
		<?php endif ?>
		</p>
	</div>
	<div class="statsContent" style="max-width: 450px; margin-right: 240px">
		<?php if ($milestone->is_completed): ?>
		<p style="margin: 0"><?php echo  langf('milestones_progress_running_since_completed', 
			timespan::format_diff($milestone->completed_on, $progress->started_on), 
			date::format_short_date($progress->started_on),
			date::format_short_date($milestone->completed_on)) ?></p>
		<?php else: ?>		
		<p style="margin: 0"><?php echo  langf('milestones_progress_running_since', 
			timespan::format_diff(date::now(), $progress->started_on), 
			date::format_short_date($progress->started_on)) ?></p>
		<?php endif ?>
	</div>
	<div style="clear: both"></div>
</div>

<div class="statsContainer">
	<div class="statsIcon">
		<img src="%RESOURCE%:images/report-assets/stats.svg" height="32" width="32" alt="" />
	</div>
	<div class="statsContent">
		<table class="grid">
			<colgroup>
				<col style="width: 33%"></col>
				<col style="width: 33%"></col>
				<col style="width: 33%"></col>
			</colgroup>			
			<tr class="header noBorder" style="background: #E0E0E0">
				<th><?php echo  lang('milestones_progress_stats_metric') ?></th>
				<th><?php echo  lang('milestones_progress_stats_by_estimate') ?></th>
				<th><?php echo  lang('milestones_progress_stats_by_forecast') ?></th>
			</tr>
			<tr class="noBorder">
				<td><?php echo  lang('milestones_progress_stats_completed') ?></td>
				<td><?php echo h( timespan::format_hours($progress->estimate_completed) )?></td>
				<td>
				<?php if ($progress->forecast_completed !== null): ?>
					<?php echo h( timespan::format_hours($progress->forecast_completed) )?>
				<?php else: ?>
					<?php echo  lang('layout_not_avail') ?>
				<?php endif ?>
				</td>
			</tr>
			<tr class="noBorder">
				<?php if ($milestone->is_completed): ?>
				<td><?php echo  lang('milestones_progress_stats_notcompleted') ?></td>
				<?php else: ?>
				<td><?php echo  lang('milestones_progress_stats_todo') ?></td>
				<?php endif ?>
				<td><?php echo h( timespan::format_hours($progress->estimate_todo) )?></td>
				<td>
				<?php if ($progress->forecast_todo !== null): ?>
					<?php echo h( timespan::format_hours($progress->forecast_todo) )?>
				<?php else: ?>
					<?php echo  lang('layout_not_avail') ?>
				<?php endif ?>
				</td>				
			</tr>
			<tr class="noBorder">
				<td><?php echo  lang('milestones_progress_stats_total') ?></td>
				<td><?php echo h( timespan::format_hours($progress->estimate) )?><td>
				<?php if ($progress->forecast !== null): ?>
					<?php echo h( timespan::format_hours($progress->forecast) )?>
				<?php else: ?>
					<?php echo  lang('layout_not_avail') ?>
				<?php endif ?>
				</td>
			</tr>
		</table>
	</div>
	<div style="clear: both"></div>
</div>
