<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<?php $limit_reached = false ?>

<?php $colspan_no_test = count($test_columns_for_user) + 1 ?>

<table class="grid">
	<colgroup>
		<col style="width: 125px"></col>
		<?php
		$temp = array();
		$temp['test_columns'] = $test_columns;
		$temp['test_columns_for_user'] = $test_columns_for_user;
		$GI->load->view('report_plugins/tests/grid/colgroups', $temp);
		?>
	</colgroup>
	<tr class="header">
		<th><?php echo  lang('reports_ds_defects_defects') ?></th>
		<?php
		$temp = array();
		$temp['test_columns'] = $test_columns;
		$temp['test_columns_for_user'] = $test_columns_for_user;
		$GI->load->view('report_plugins/tests/grid/headers', $temp);
		?>
	</tr>
	<?php $test_chunk_size = 250 ?>
	<?php $test_chunk_ix = 0 ?>
	<?php arr::alternator() ?>
	<?php foreach ($defects->content as $defect_id => $test_ids): ?>
		<?php $i = 0 ?>
		<?php $alt = arr::alternator('odd', 'even') ?>
		<?php if ($test_ids): ?>
		<?php foreach ($test_ids as $test_id): ?>
			<?php if (!isset($test_chunk)): ?>
				<?php $test_chunk_ids = array_slice(
					$defects->test_ids,
					$test_chunk_ix,
					$test_chunk_size
				) ?>
				<?php if (!$test_chunk_ids): ?>
					<?php break ?>
				<?php endif ?>
				<?php $test_chunk = $report_helper->get_tests(
					$test_chunk_ids,
					$case_fields,
					$test_fields,
					$case_assocs,
					$case_rels,
					$test_assocs,
					$result_assocs
				) ?>
				<?php $test_lookup = obj::get_lookup($test_chunk) ?>
			<?php endif ?>
			<tr class="<?php echo  $alt ?>">
				<?php $test = arr::get($test_lookup, $test_id) ?>
				<td>
				<?php if ($i == 0): ?>
					<?php if ($show_links): ?>
						<?php $defs = defects::format(
							h($defect_id), 
							$project) ?>
					<?php else: ?>
						<?php $defs = defects::format_nolinks(
							h($defect_id)
						) ?>
					<?php endif ?>
					<?php if ($defs): ?>
						<?php echo  $defs ?>
					<?php endif ?>
				<?php endif ?>
				</td>
				<?php if ($test): ?>
					<?php
					$temp = array();
					$temp['project'] = $project;
					$temp['test'] = $test;
					$temp['test_assocs'] = $test_assocs;
					$temp['test_fields'] = $test_fields;
					$temp['case_assocs'] = $case_assocs;
					$temp['case_fields'] = $case_fields;
					$temp['case_rels'] = $case_rels;
					$temp['result_assocs'] = $result_assocs;
					$temp['test_columns'] = $test_columns;
					$temp['test_columns_for_user'] = $test_columns_for_user;
					$temp['show_links'] = $show_links;
					$GI->load->view('report_plugins/tests/grid/columns', $temp);
					?>
				<?php else: ?>
					<td colspan="<?php echo  $colspan_no_test ?>"></td>
				<?php endif ?>
			</tr>
			<?php $i++ ?>
			<?php $test_chunk_ix++ ?>
			<?php if ($limit): ?>
				<?php if ($test_chunk_ix >= $limit): ?>
					<?php $limit_reached = true ?>
					<?php break ?>
				<?php endif ?>
			<?php endif ?>
			<?php if (($test_chunk_ix % $test_chunk_size) == 0): ?>
				<?php unset($test_chunk) ?>
				<?php unset($test_assocs) ?>
				<?php unset($test_rels) ?>
				<?php unset($test_lookup) ?>
			<?php endif ?>
		<?php endforeach ?>
		<?php else: ?>
			<tr class="<?php echo  $alt ?>">
				<td>
					<?php if ($show_links): ?>
						<?php $defs = defects::format(
							h($defect_id),
							$project) ?>
					<?php else: ?>
						<?php $defs = defects::format_nolinks(
							h($defect_id)
						) ?>
					<?php endif ?>
					<?php if ($defs): ?>
						<?php echo  $defs ?>
					<?php endif ?>				
				</td>
				<td colspan="<?php echo  $colspan_no_test ?>"><em><?php echo  lang('reports_ds_defects_no_tests') ?></em></td>
			</tr>
		<?php endif ?>
	<?php endforeach ?>
</table>

<?php if ($limit_reached): ?>
	<?php if ($defects->defect_count == $defects->defect_count_partial): ?>
		<?php if ($defects->test_count > $defects->test_count_partial): ?>
			<p class="partial">
				<?php echo  langf('reports_ds_defects_more_tests',
				$defects->test_count - 
				$defects->test_count_partial) ?>
			</p>
		<?php endif ?>
	<?php else: ?>
		<p class="partial">
			<?php echo  langf('reports_ds_defects_more_defects',
				$defects->defect_count - 
				$defects->defect_count_partial) ?>
		</p>
	<?php endif ?>
<?php endif ?>
