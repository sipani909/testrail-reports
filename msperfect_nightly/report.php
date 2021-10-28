<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php

/**
 * Milestone Summary report for TestRail
 *
 * Copyright Gurock Software GmbH. All rights reserved.
 *
 * This is the TestRail report for displaying a summary/overview for a
 * milestone.
 *
 * http://www.gurock.com/testrail/
 */

class Msperfect_nightly_report_plugin extends Report_plugin
{
	private $_controls;

	// The controls and options for those controls that are used on
	// the form of this report.
	private static $_control_schema = array(
		'milestones_select' => array(
			'namespace' => 'custom_milestones',
		),
		'activities_daterange' => array(
			'type' => 'dateranges_select',
			'namespace' => 'custom_activities'
		),
		'activities_statuses' => array(
			'type' => 'statuses_select',
			'namespace' => 'custom_activities_statuses',
		),
		'activities_limit' => array(
			'type' => 'limits_select',
			'namespace' => 'custom_activities',
			'min' => 0,
			'max' => 1000,
			'default' => 100
		),
		'tests_filter' => array(
			'namespace' => 'custom_tests'
		),
		'tests_columns' => array(
			'type' => 'columns_select',
			'namespace' => 'custom_tests',
			'default' => array(
				'tests:id' => 75,
				'cases:title' => 0
			)
		),
		'tests_limit' => array(
			'type' => 'limits_select',
			'namespace' => 'custom_tests',
			'min' => 0,
			'max' => 1000,
			'default' => 100
		),
		'content_hide_links' => array(
			'namespace' => 'custom_content',
		)
	);

	// The resources to copy to the output directory when generating a
	// report.
	private static $_resources = array(
		'images/report-assets/plan16.svg',
		'images/report-assets/run16.svg',
		'images/report-assets/help.svg',
		'images/report-assets/goal.svg',
		'images/report-assets/stats.svg',
		'images/report-assets/time.svg',
		'js/highcharts.js',
		'js/jquery.js',
		'styles/print.css',
		'styles/reset.css',
		'styles/view.css'
	);

	public function __construct()
	{
		parent::__construct();
		$this->_controls = $this->create_controls(
			self::$_control_schema
		);
	}

	public function prepare_form($context, $validation)
	{
		// Assign the validation rules for the controls used on the
		// form.
		$this->prepare_controls($this->_controls, $context, 
			$validation);

		// Assign the validation rules for the fields on the form
		// that are not covered by the controls and are specific to
		// this report.
		$validation->add_rules(
			array(
				'custom_status_include' => array(
					'type' => 'bool',
					'default' => false
				),
				'custom_activities_include' => array(
					'type' => 'bool',
					'default' => false
				),
				'custom_progress_include' => array(
					'type' => 'bool',
					'default' => false
				),
				'custom_tests_include' => array(
					'type' => 'bool',
					'default' => false
				)
			)
		);

		if (request::is_post())
		{
			return;
		}

		// We assign the default values for the form depending on the
		// event. For 'add', we use the default values of this plugin.
		// For 'edit/rerun', we use the previously saved values of
		// the report/report job to initialize the form. Please note
		// that we prefix all fields in the form with 'custom_' and
		// that the storage format omits this prefix (validate_form).

		if ($context['event'] == 'add')
		{
			$defaults = array(
				'status_include' => true,
				'activities_include' => true,
				'progress_include' => true,
				'tests_include' => true
			);
		}
		else
		{
			$defaults = $context['custom_options'];
		}

		foreach ($defaults as $field => $value)
		{
			$validation->set_default('custom_' . $field, $value);
		}
	}

	public function validate_form($context, $input, $validation)
	{
		// We begin with validating the controls used on the form.
		$values = $this->validate_controls(
			$this->_controls,
			$context,
			$input,
			$validation);

		if (!$values)
		{
			return false;
		}

		static $fields = array(
			'status_include',
			'activities_include',
			'progress_include',
			'tests_include'
		);

		// And then add our fields from the form input that are not
		// covered by the controls and return the data as it should be
		// stored in the report options.
		foreach ($fields as $field)
		{
			$key = 'custom_' . $field;
			$values[$field] = arr::get($input, $key);
		}

		return $values;
	}

	public function render_form($context)
	{
		$project = $context['project'];

		$params = array(
			'controls' => $this->_controls,
			'project' => $project,
			'test_columns' => $context['test_columns']
		);

		// Note that we return separate HTML snippets for the form/
		// options and the used dialogs (which must be included after
		// the actual form as they include their own <form> tags).
		return array(
			'form' => $this->render_view(
				'form',
				$params,
				true
			),
			'after_form' => $this->render_view(
				'form_dialogs',
				$params,
				true
			)
		);
	}

	public function run($context, $options)
	{
		$project = $context['project'];

		// We start by getting the milestone and the associated test
		// runs/plans from the database which also includes the full
		// statistics (status counts).
		$milestone = $this->_helper->get_milestone(
			$options['milestones_id']
		);

		if ($milestone)
		{
			$runs = $this->_helper->get_runs_by_milestone(
				$milestone->id
			);
		}
		else 
		{
			$runs = array();
		}

		$runs_noplan = array();
		foreach ($runs as $run)
		{
			if ($run->is_plan)
			{
				foreach ($run->runs as $r)
				{
					$runs_noplan[] = $r;					
				}
			}
			else
			{
				$runs_noplan[] = $run;
			}
		}

		// We then read the activity (data for the activity chart) and
		// the activities (test results and comments added over time).
		$activities_include = $options['activities_include'];
		$activities_limit = $options['activities_limit'];

		if ($activities_include && $milestone)
		{
			$this->_helper->get_daterange_tofrom(
				$options['activities_daterange'],
				$options['activities_daterange_from'],
				$options['activities_daterange_to'],
				$activities_from,
				$activities_to
			);

			$activity = $this->_helper->get_activity_by_milestone(
				$milestone,				
				$activities_from,
				$activities_to
			);

			// Get the statuses for the status filter, if any. If we
			// include all statuses, we can ignore this.
			if ($options['activities_statuses_include'] == 
				TP_REPORT_PLUGINS_STATUSES_ALL)
			{
				$activities_status_ids = null;	
			}
			else
			{
				$activities_status_ids = obj::get_ids(
					$this->_helper->get_statuses(
						$options['activities_statuses_ids']
					)
				);
			}

			$activities = $this->_helper->get_activities_by_milestone(
				$milestone,
				$activities_from,
				$activities_to,
				$activities_status_ids,
				$activities_limit,
				$activities_rels
			);
		}
		else
		{
			$activity = null;
			$activities = array();
			$activities_rels = array();
			$activities_from = null;
			$activities_to = null;
		}

		// We then read the progress and forecast information (for the
		// burndown charts and forecast details).
		$progress_include = $options['progress_include'];

		$progress = null;
		$burndown = null;

		if ($progress_include && $milestone && $runs)
		{
			$progress = $this->_helper->get_progress_by_milestone(
				$milestone,
				$runs
			);

			if ($progress)
			{
				$burndown = $this->_helper->get_burndown_by_milestone(
					$milestone,
					$runs_noplan, // sic! Actual runs only
					$progress
				);
			}
		}

		$status_include = $options['status_include'];

		// Render the report to a temporary file and return the path
		// to TestRail (including additional resources that need to be
		// copied). We use a different view if the milestone no longer
		// exists.
		if ($milestone)
		{
			$view_name = 'index';
		}
		else 
		{
			$view_name = 'index_na';
		}

		return array(
			'resources' => self::$_resources,
			'html_file' => $this->render_page(
				$view_name,
				array(
					'report' => $context['report'],
					'project' => $project,
					'milestone' => $milestone,
					'runs' => $runs,
					'runs_noplan' => $runs_noplan,
					'status_include' => $status_include,
					'activities_include' => $activities_include,
					'activity' => $activity,
					'activities' => $activities,
					'activities_rels' => $activities_rels,
					'activities_from' => $activities_from,
					'activities_to' => $activities_to,
					'activities_limit' => $activities_limit,
					'progress_include' => $progress_include,
					'progress' => $progress,
					'burndown' => $burndown,
					'tests_include' => $options['tests_include'],
					'test_filters' => $options['tests_filters'],
					'test_limit' => $options['tests_limit'],
					'test_columns' => $context['test_columns'],
					'test_columns_for_user' => 
						$options['tests_columns'],
					'fields' => $context['fields'],
					'case_fields' => $context['case_fields'],
					'test_fields' => $context['test_fields'],
					'show_links' => !$options['content_hide_links']
				)
			)
		);
	}
}
