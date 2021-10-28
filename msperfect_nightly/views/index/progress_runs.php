<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $runs_active = array() ?>
<?php $runs_completed = array() ?>

<?php foreach ($runs as $run): ?>
	<?php if ($run->is_completed): ?>
		<?php $runs_completed[] = $run ?>
	<?php else: ?>
		<?php $runs_active[] = $run ?>
	<?php endif ?>
<?php endforeach ?>

<?php $runs = array() ?>
<?php foreach ($runs_active as $run): ?>
	<?php $runs[] = $run ?>
<?php endforeach ?>
<?php foreach ($runs_completed as $run): ?>
	<?php $runs[] = $run ?>
<?php endforeach ?>

<?php $has_runs = count($runs) > 0 ?>

<?php arr::alternator() ?>
<table class="grid">
	<colgroup>
		<col class="icon"></col>
		<col></col>
		<col class="comment"></col>
	</colgroup>
	<?php for ($i = 0; $i < count($runs); $i++): ?>
	<?php $run = $runs[$i] ?>
	<tr class="<?php echo  arr::alternator('odd', 'even') ?>">
		<?php $show_separator = false ?>
		<?php if ($i + 1 < count($runs)): ?>
			<?php if (!$run->is_completed && $runs[$i + 1]->is_completed): ?>
				<?php $show_separator = true ?>
			<?php endif ?>
		<?php endif ?>
		<td class="icon <?php echo  $show_separator ? 'separator' : $show_separator ?>">
			<?php if ($run->is_plan): ?>
			<img src="%RESOURCE%:images/report-assets/plan16.svg"
				width="16" height="16" alt="" />
			<?php else: ?>
			<img src="%RESOURCE%:images/report-assets/run16.svg"
				width="16" height="16" alt="" />
			<?php endif ?>
		</td>
		<td class="<?php echo  $show_separator ? 'separator' : $show_separator ?>">
			<?php if ($run->is_plan): ?>
				<?php if ($show_links): ?>
					<a target="_top" href="<?php echo  "%LINK%:/plans/progress/$run->id" ?>"><?php echo h( $run->name )?></a>
				<?php else: ?>
					<?php echo h( $run->name )?>
				<?php endif ?>
				<span class="secondary"><?php echo  lang('runs_is_plan') ?></span>
			<?php else: ?>
				<?php if ($show_links): ?>
					<a target="_top" href="<?php echo  "%LINK%:/runs/progress/$run->id" ?>"><?php echo h( $run->name )?></a>
				<?php else: ?>
					<?php echo h( $run->name )?>
				<?php endif ?>
				<?php if ($run->config): ?>
				<span class="configuration">(<?php echo h( $run->config )?>)</span>
				<?php endif ?>
			<?php endif ?>
		</td>
		<td class="right comment <?php echo  $show_separator ? 'separator' : $show_separator ?>">
			<?php if ($run->is_completed): ?>
				<span class="secondary">
					<?php echo  langf('layout_messages_completedon', 
						date::format_short_date($run->completed_on)) ?>
				</span>
			<?php else: ?>
				<?php if ($run->progress && $run->progress->projected_end_date !== null): ?>
				<span class="secondary">
					<?php echo  langf('layout_messages_forecastedon', 
						date::format_short_date($run->progress->projected_end_date)) ?>
				</span>
				<?php else: ?>
				<span class="secondary">
					<?php echo  lang('milestones_progress_unknown_forecast') ?>
				</span>
				<?php endif ?>
			<?php endif ?>
		</td>
	</tr>
	<?php endfor ?>
</table>
