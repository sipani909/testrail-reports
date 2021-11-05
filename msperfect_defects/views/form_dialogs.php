<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $test_columns_for_user = validation::get_plain('custom_tests_columns') ?>
<?php if (!$test_columns_for_user): ?>
	<?php $test_columns_for_user = array() ?>
<?php endif ?>

<?php
$temp['columns'] = $test_columns;
$temp['columns_for_user'] = $test_columns_for_user;
$GI->load->view('report_plugins/controls/columns/select/add_dialog', $temp);
?>

<?php
$GI->load->view('report_plugins/controls/runs/select/add_dialog');
?>

<?php
$GI->load->view('report_plugins/controls/runs/select/filter_bubble');
?>
