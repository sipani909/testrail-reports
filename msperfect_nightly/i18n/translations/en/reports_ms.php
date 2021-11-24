<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php

$lang['reports_ms_meta_label'] = 'Nightly Summary';
$lang['reports_ms_meta_group'] = 'MS Perfect';
$lang['reports_ms_meta_summary'] = 'Shows a status summary for a milestone.';
$lang['reports_ms_meta_description'] = 'Shows a summary and overview for a milestone. Please see the Report Options section below to configure the report specific options.';

$lang['reports_ms_form_milestone'] = 'Milestone &amp; Details';
$lang['reports_ms_form_activity'] = 'Activity';
$lang['reports_ms_form_progress'] = 'Progress';
$lang['reports_ms_form_tests'] = 'Tests';

$lang['reports_ms_form_details_include'] = 'Include the following details/sections:';
$lang['reports_ms_form_details_include_status'] = 'Status and test statistics';
$lang['reports_ms_form_details_include_activity'] = 'Activity (results over time)';
$lang['reports_ms_form_details_include_progress'] = 'Progress and remaining estimate/forecast';
$lang['reports_ms_form_details_include_tests'] = 'Tests and test results';

$lang['reports_ms_na'] = 'The milestone for this report no longer exists and may have been deleted.';
$lang['reports_ms_defects_empty'] = 'No defects found.';

$lang['reports_ms_title'] = 'Milestone: {0}';

$lang['reports_ms_attr_milestone'] = 'Milestone';
$lang['reports_ms_attr_completed'] = 'Completed';
$lang['reports_ms_attr_completedon'] = 'Completed On';
$lang['reports_ms_attr_dueon'] = 'Due On';

$lang['reports_ms_runs'] = 'Test Runs &amp; Plans';
$lang['reports_ms_runs_info'] = 'Shows the test runs associated with the milestone ordered by creation date.';
$lang['reports_ms_runs_empty'] = 'No test runs found.';

$lang['reports_ms_activity'] = 'Activity';
$lang['reports_ms_activity_info'] = 'Shows the activity (test results and comments) for the test runs of the milestone.';
$lang['reports_ms_activity_more'] = 'Only showing the latest {0} activities. There are more test results and comments that are not displayed.';
$lang['reports_ms_activity_empty'] = 'No activity found for the time frame and statuses.';

$lang['reports_ms_progress'] = 'Progress';
$lang['reports_ms_progress_info'] = 'Shows the progress (forecasts and estimates) and burndown chart for the test runs of the milestone.';
$lang['reports_ms_progress_forecasts'] = 'Forecasts &amp; Estimates';
$lang['reports_ms_progress_runs'] = 'Test Runs &amp; Plans';

$lang['reports_ms_tests'] = 'Tests &amp; Results';
$lang['reports_ms_tests_info'] = 'Shows the tests and their current statuses for the test runs of the milestone.';
$lang['reports_ms_tests_empty'] = 'No tests found.';
$lang['reports_ms_tests_more'] = 'Only showing {0} tests. There are more tests in the test runs that are not displayed.';

$lang['reports_ms_form_defects'] = 'Defects';
$lang['reports_ms_form_tests'] = 'Tests';
$lang['reports_ms_form_runs'] = 'Test Suites &amp; Runs';
$lang['reports_ms_form_runs_single'] = 'Sections &amp; Test Runs';

$lang['reports_ms_runs_header'] = 'Test Runs';
$lang['reports_ms_runs_header_info'] = 'Shows the test runs used for collecting the defect summary for the requested project and test suites.';
$lang['reports_ms_runs_empty'] = 'No test runs found with defects.';
$lang['reports_ms_runs_help'] = 'The statistics of this report only include results for tests linked to the selected defects.';
$lang['reports_ms_runs_more'] = 'There {0?{are}:{is}} {0} more {0?{test runs}:{test run}} that {0?{are}:{is}} not included.';

$lang['reports_ms_defects_header'] = 'Defects';
$lang['reports_ms_defects_header_info'] = 'Shows the found defects for the selected project, test suites and test runs, together with the associated tests and current test statuses.';
$lang['reports_ms_defects_empty'] = 'No defects found.';
$lang['reports_ms_defects_defects'] = 'Defects';
$lang['reports_ms_defects_more_defects'] = 'There {0?{are}:{is}} {0} more {0?{defects}:{defect}} that {0?{are}:{is}} not displayed.';
$lang['reports_ms_defects_more_tests'] = 'There {0?{are}:{is}} {0} more {0?{tests}:{test}} with references that {0?{are}:{is}} not displayed.';
$lang['reports_ms_defects_no_tests'] = 'No test.';
