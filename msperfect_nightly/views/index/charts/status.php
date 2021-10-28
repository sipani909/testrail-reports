<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $statuses = $GI->cache->get_objects('status') ?>

<?php
$temp = array();
$temp['stats'] = $milestone;
$temp['statuses'] = $statuses;
$GI->load->view('report_plugins/charts/status', $temp);
?>
