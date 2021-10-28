<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php
$min_width = 0;

foreach ($test_columns_for_user as $key => $width)
{
	$min_width += $width ? $width : 300;
}

$min_width = max($min_width, 960);

$header = array(
	'project' => $project,
	'report' => $report,
	'meta' => $report_obj->get_meta(),
	'min_width' => $min_width,
	'show_links' => $show_links,
	'css' => array(
		'styles/reset.css' => 'all',
		'styles/view.css' => 'all',
		'styles/print.css' => 'print'
	),
	'js' => array(
		'js/jquery.js',
		'js/highcharts.js',
	)
);

$GI->load->view('report_plugins/layout/header', $header);
?>

<h1 class="main">
	<span><?php echo  langf('reports_ms_title', h($milestone->name))?></span>
</h1>

<?php tests::set_status_percents($milestone) ?>
<?php foreach ($runs as $run): ?>
	<?php tests::set_status_percents($run) ?>
	<?php if (isset($run->runs)): ?>
		<?php foreach ($runs as $r): ?>
			<?php tests::set_status_percents($r) ?>
		<?php endforeach ?>
	<?php endif ?>
<?php endforeach ?>

<?php $GI->load->view('report_plugins/charts/defaults') ?>

<?php if ($status_include): ?>
<?php
$temp = array();
$temp['milestone'] = $milestone;
$report_obj->render_view('index/charts/status', $temp);
?>
<?php endif ?>

<?php if ($milestone->description): ?>
	<div class="markdown" style="<?php echo  !$status_include ? 'margin: 1.5em 0' : '' ?>">
		<?php if ($show_links): ?>
		<?php echo  markdown::to_html($milestone->description) ?>
		<?php else: ?>
		<?php echo  markdown::to_html_nolinks($milestone->description) ?>
		<?php endif ?>
	</div>
<?php endif ?>

<div style="margin: 1.5em 0">
<?php
$temp = array();
$temp['milestone'] = $milestone;
$report_obj->render_view('index/attributes', $temp);
?>
</div>

<h1><img class="right noPrint" src="%RESOURCE%:images/report-assets/help.svg" width="16" height="16" alt="" title="<?php echo  lang('reports_ms_runs_info') ?>" /><?php echo  lang('reports_ms_runs') ?></h1>

<?php if ($runs): ?>
	<?php
	$temp = array();
	$temp['runs'] = $runs;
	$temp['run_rels'] = array();
	$temp['show_links'] = $show_links;
	$GI->load->view('report_plugins/runs/groups', $temp);
	?>
<?php else: ?>
	<p><?php echo  lang('reports_ms_runs_empty') ?></p>
<?php endif ?>

<?php if ($activities_include): ?>
	<h1><img class="right noPrint" src="%RESOURCE%:images/report-assets/help.svg" width="16" height="16" alt="" title="<?php echo  lang('reports_ms_activity_info') ?>" /><?php echo  lang('reports_ms_activity') ?></h1>
	<?php if ($activity): ?>
		<?php
		$temp = array();
		$temp['activity'] = $activity;
		$temp['from'] = $activities_from;
		$temp['to'] = $activities_to;
		$GI->load->view('report_plugins/charts/activity', $temp);
		?>
	<?php endif ?>
	<?php if ($activities): ?>
		<?php
		$temp = array();
		$temp['activities'] = $activities;
		$temp['activities_rels'] = $activities_rels;
		$temp['show_links'] = $show_links;
		$GI->load->view('report_plugins/tests/activities', $temp);
		?>
		<?php if ($activities_limit == count($activities)): ?>
		<p class="partial">
		<?php echo  langf('reports_ms_activity_more', $activities_limit) ?>
		</p>
		<?php endif ?>
	<?php else: ?>
		<p class="top"><?php echo  lang('reports_ms_activity_empty') ?></p>
	<?php endif ?>
<?php endif ?>

<?php if ($progress_include && $progress): ?>
	<h1><img class="right noPrint" src="%RESOURCE%:images/report-assets/help.svg" width="16" height="16" alt="" title="<?php echo  lang('reports_ms_progress_info') ?>" /><?php echo  lang('reports_ms_progress') ?></h1>
	<?php if ($burndown): ?>
		<?php
		$temp = array();
		$temp['progress'] = $progress;
		$temp['burndown'] = $burndown;
		$GI->load->view('report_plugins/charts/burndown', $temp);
		?>
	<?php endif ?>
	<h2 class="top"><?php echo  lang('reports_ms_progress_forecasts') ?></h2>
	<?php
	$temp = array();
	$temp['milestone'] = $milestone;
	$temp['progress'] = $progress;
	$report_obj->render_view('index/progress', $temp);
	?>
	<?php if ($runs): ?>
		<h2><?php echo  lang('reports_ms_progress_runs') ?></h2>
		<?php
		$temp = array();
		$temp['runs'] = $runs;
		$temp['show_links'] = $show_links;
		$report_obj->render_view('index/progress_runs', $temp);
		?>
	<?php endif ?>
<?php endif ?>

<?php $has_tests = false ?>
<?php if ($tests_include && $runs_noplan): ?>
	<?php $test_limit_current = $test_limit ?>
	<?php $test_limit_reached = false ?>
	<h1><img class="right noPrint" src="%RESOURCE%:images/report-assets/help.svg" width="16" height="16" alt="" title="<?php echo  lang('reports_ms_tests_info') ?>" /><?php echo  lang('reports_ms_tests') ?></h1>
	<?php foreach ($runs_noplan as $run): ?>
		<?php $run_outline = $report_helper->get_run_outline(
			$run->id,
			$run->content_id,
			null, // No ID filter
			$fields,
			$test_filters,
			$test_limit_current,
			$test_count,
			$test_count_partial,
			$test_ids
		) ?>
		<?php if ($run_outline): ?>
			<?php $has_tests = true ?>
			<h2>
				<?php echo h( $run->name )?>
				<?php if ($run->config): ?>
				<span class="secondary configuration">(<?php echo h( $run->config )?>)</span>
				<?php endif ?>
			</h2>
			<?php
			$temp = array();
			$temp['project'] = $project;
			$temp['test_ids'] = $test_ids;
			$temp['test_fields'] = $test_fields;
			$temp['test_columns'] = $test_columns;
			$temp['test_columns_for_user'] = $test_columns_for_user;
			$temp['case_fields'] = $case_fields;
			$temp['outline'] = $run_outline;
			$temp['show_links'] = $show_links;
			$report_obj->render_view('index/run', $temp);
			?>
			<?php if ($test_limit): ?>
				<?php $test_limit_current -= $test_count_partial ?>
				<?php if ($test_limit_current <= 0): ?>
					<?php $test_limit_reached = true ?>
					<?php break ?>
				<?php endif ?>
			<?php endif ?>
		<?php endif ?>
	<?php endforeach ?>
	<?php if ($test_limit_reached): ?>
	<p class="partial">
		<?php echo  langf('reports_ms_tests_more', $test_limit) ?>
	</p>
	<?php endif ?>
	<?php if (!$has_tests): ?>
		<p class="top"><?php echo  lang('reports_ms_tests_empty') ?></p>
	<?php endif ?>
<?php endif ?>

<?php
$temp = array();
$temp['report'] = $report;
$temp['meta'] = $report_obj->get_meta();
$temp['show_options'] = true;
$temp['show_report'] = true;
$GI->load->view('report_plugins/layout/footer', $temp);
?>
