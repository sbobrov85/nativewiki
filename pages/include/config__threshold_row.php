<?php
/**
 * MantisBT - A PHP based bugtracking system
 *
 * MantisBT is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * MantisBT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright 2017  JeraIT Team - support@jerait.com
 */

// get access levels.
$t_file_exp = NativeWiki::getAccessExpression(
	plugin_config_get($threshold, null, true),
	$g_access_levels
);
$t_global_exp = NativeWiki::getAccessExpression(
	plugin_config_get($threshold, null, true, ALL_USERS, ALL_PROJECTS),
	$g_access_levels
);
$t_project_exp = NativeWiki::getAccessExpression(
	plugin_config_get($threshold),
	$g_access_levels
);

$t_can_change = access_has_project_level(
	config_get_access($threshold),
	$g_project_id,
	$g_user
) && ((ALL_PROJECTS == $g_project_id) || !$p_all_projects_only);

?>

<tr>
	<td>
		<?= string_display(lang_get("access_$threshold")) ?>
	</td>

	<?php
	foreach ($g_access_levels as $t_access_level => $t_access_label):
		$t_file = in_array($t_access_level, $t_file_exp);
		$t_global = in_array($t_access_level, $t_global_exp);
		$t_project = in_array($t_access_level, $t_project_exp);

		$t_color = NativeWiki::setColor(
			$g_project_id,
			$threshold,
			$t_file,
			$t_global,
			$t_project,
			$t_can_change
		);

		if ($t_can_change) {
			$t_checked = $t_project ? 'checked="checked"' : '';
			$t_value = '<label><input type="checkbox" class="ace" name="flag_thres_'
				. $threshold . '[]" value="'
				. $t_access_level . '" ' . $t_checked
				. ' /><span class="lbl"></span></label>';
			$t_show_submit = true;
		} else {
			if ($t_project) {
				$t_value = '<i class="fa fa-check fa-lg blue"></i>';
			} else {
				$t_value = '&#160;';
			}
		} ?>

		<td class="center <?= $t_color ?>"><?= $t_value ?></td>

	<?php endforeach ?>
</tr>
