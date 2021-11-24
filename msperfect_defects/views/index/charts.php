<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $GI->load->view('report_plugins/charts/defaults') ?>

<?php $statuses = $GI->cache->get_objects('status') ?>
<?php $statuses_reversed = array_reverse($statuses) ?>

<?php
$temp = array();
$temp['chart_id'] = 'chart0';
$temp['statuses'] = $statuses;
$temp['statuses_reversed'] = $statuses_reversed;
$temp['runs'] = $runs;
$temp['runs_reversed'] = $runs_reversed;
$temp['show_coverage'] = false;
$temp['show_as_bar'] = true;
$temp['show_as_bar_treshold'] = 6;
$GI->load->view('report_plugins/charts/comparison', $temp);
?>
