<?php if (!defined('ROOTPATH')) exit('No direct script access allowed'); ?>
<table class="grid">
	<colgroup>
		<col></col>
		<col class="comment"></col>
	</colgroup>
	<tr class="odd">
		<td><?php echo  lang('reports_ms_attr_completed') ?></td>
		<?php if ($milestone->is_completed): ?>
		<td class="right"><?php echo  lang('layout_yes') ?></td>
		<?php else: ?>
		<td class="right"><?php echo  lang('layout_no') ?></td>
		<?php endif ?>
	</tr>
	<?php if ($milestone->is_completed): ?>
	<tr class="even">
		<td><?php echo  lang('reports_ms_attr_completedon') ?></td>
		<td class="right">
			<?php echo  date::format_short_date($milestone->completed_on) ?>
		</td>
	</tr>
	<?php endif ?>
	<?php if ($milestone->due_on): ?>
	<tr class="odd">
		<td><?php echo  lang('reports_ms_attr_dueon') ?></td>
		<td class="right">
			<?php echo  date::format_short_date_utc($milestone->due_on) ?>
		</td>
	</tr>
	<?php endif ?>
</table>