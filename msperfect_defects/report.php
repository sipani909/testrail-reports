<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php

/**
 * Defects Summary report for TestRail
 *
 * Copyright Gurock Software GmbH. All rights reserved.
 *
 * This is the TestRail report for displaying a defects summary for
 * a specific milestones/plan or selected test runs.
 *
 * http://www.gurock.com/testrail/
 */

class Msperfect_defects_report_plugin extends Report_plugin
{
	private $_controls;

	// The controls and options for those controls that are used on
	// the form of this report.
	private static $_control_schema = array(
		'defects_select' => array(
			'namespace' => 'custom_defects'
		),
		'runs_select' => array(
			'namespace' => 'custom_runs',
			'multiple_suites' => true
		),
		'runs_limit' => array(
			'type' => 'limits_select',
			'namespace' => 'custom_runs',
			'min' => 0,
			'max' => 100,
			'default' => 25
		),
		'tests_columns' => array(
			'type' => 'columns_select',
			'namespace' => 'custom_tests',
			'default' => array(
				'tests:id' => 75,
				'cases:title' => 0
			)
		),
		'tests_filter' => array(
			'namespace' => 'custom_tests'
		),
		'tests_limit' => array(
			'type' => 'limits_select',
			'namespace' => 'custom_tests',
			'min' => 0,
			'max' => 5000,
			'default' => 1000
		),
		'content_hide_links' => array(
			'namespace' => 'custom_content',
		)
	);

	// The resources to copy to the output directory when generating a
	// report.
	private static $_resources = array(
		'images/report-assets/run16.svg',
		'images/report-assets/help.svg',
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
			$defaults = array();
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
		// All fields on the form are covered by controls, so we just
		// need to validate the controls.
		return $this->validate_controls(
			$this->_controls,
			$context,
			$input,
			$validation);
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

		// Read the test suites first.
		$suites = $this->_helper->get_suites_by_include(
			$project->id,
			$options['runs_suites_ids'],
			$options['runs_suites_include']
		);

		$suite_ids = obj::get_ids($suites);

		// Limit this report to specific test cases, if requested.
		// This is only relevant for single-suite projects and with a
		// section filter.
		$case_ids = $this->_helper->get_case_scope_by_include(
			$suite_ids,
			arr::get($options, 'runs_sections_ids'),
			arr::get($options, 'runs_sections_include'),
			$has_cases
		);

		// We then get the actual list of test runs used, depending on
		// the report options.
		if ($suite_ids)
		{
			$runs = $this->_helper->get_runs_by_include(
				$project->id,
				$suite_ids,
				$options['runs_include'],
				$options['runs_ids'],
				$options['runs_filters'],
				null, // Active and completed
				$options['runs_limit'],
				$run_rels,
				$run_count
			);
		}
		else
		{
			$runs = array();
			$run_rels = array();
			$run_count = 0;
		}

		$run_ids = obj::get_ids($runs);

		// Convert the defect ID text to an array of defect IDs.
		$defect_ids_text = $options['defects_ids'];
		if ($defect_ids_text)
		{
			$defect_ids = str::split_lines($defect_ids_text);
		}
		else
		{
			$defect_ids = array();
		}

		$results = array();
		if ($run_ids && $has_cases)
		{
			// And then finally get the list of defects from the DB,
			// together with their test IDs.
			$defects = $this->_helper->get_defects_ex(
				$run_ids,
				$case_ids,
				$options['defects_include'],
				$defect_ids,
				$options['tests_limit']
			);

			$test_ids = $defects->test_ids;
			if ($test_ids)
			{
				$results = $this->_helper->get_results_for_tests_many(
					$run_ids,
					$test_ids,
					$runs,
					$runs_with_defects,
					$runs_ignored_count
				);

				$run_count -= $runs_ignored_count; // Adjust run count
				$runs = $runs_with_defects;
			}
			else
			{
				$run_count = 0;
				$runs = array();
			}
		}
		else
		{
			$defects = null;
			$test_ids = array();
		}

		if ($test_ids)
		{
			$runs_for_tests = $this->_helper->get_runs_for_tests(
				$test_ids);			
		}
		else 
		{
			$runs_for_tests = array();
		}

		// Render the report to a temporary file and return the path
		// to TestRail (including additional resources that need to be
		// copied).
		return array(
			'resources' => self::$_resources,
			'html_file' => $this->render_page(
				'index',
				array(
					'report' => $context['report'],
					'project' => $project,
					'defects' => $defects,
					'results' => $results,
					'runs' => $runs,
					'run_rels' => $run_rels,
					'run_count' => $run_count,
					'runs_for_tests' => $runs_for_tests,
					'test_columns' => $context['test_columns'],
					'test_columns_for_user' => 
						$options['tests_columns'],
					'test_fields' => $context['test_fields'],
					'test_limit' => $options['tests_limit'],
					'case_fields' => $context['case_fields'],
					'show_links' => !$options['content_hide_links']
				)
			)
		);
	}
}
