<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php
$temp = array();
$temp['runs'] = $runs;
$temp['run_rels'] = $run_rels;
$temp['show_links'] = $show_links;
$temp['show_defects_and_total'] = true;
$temp['top'] = true;
$GI->load->view('report_plugins/runs/groups', $temp);
?>

<?php $run_count_partial = count($runs) ?>
<?php if ($run_count > $run_count_partial): ?>
	<p class="partial">
		<?php echo  langf('reports_ds_runs_more',
		$run_count - 
		$run_count_partial) ?>
	</p>
<?php endif ?>
