<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php
$header = array(
	'project' => $project,
	'report' => $report,
	'meta' => $report_obj->get_meta(),
	'min_width' => 960,
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

<p><?php echo  lang('reports_ms_na')?></p>

<?php
$temp = array();
$temp['report'] = $report;
$temp['meta'] = $report_obj->get_meta();
$temp['show_options'] = true;
$temp['show_report'] = true;
$GI->load->view('report_plugins/layout/footer', $temp);
?>
