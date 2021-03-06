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

// prepare common variables.
$thresholdsList = NativeWikiCommonHelper::getThresholdList();
$g_user = auth_get_current_user_id();
$t_show_submit = false;

?>

<fieldset>
	<div class="space-10"></div>
	<!-- widget-box -->
	<div class="widget-box widget-color-blue2">

		<!-- widget-header -->
		<div class="widget-header widget-header-small">
			<h4 class="widget-title lighter">
				<i class="ace-icon fa fa-sliders"></i>
				<?= lang_get('plugin_NativeWiki_config_access') ?>
			</h4>
		</div>
		<!-- /widget-header -->

		<!-- widget-body -->
		<div class="widget-body">
			<!-- widget-main -->
			<div class="widget-main no-padding">
				<!-- table-responsible -->
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-condensed">
						<thead>
							<tr>
								<th class="bold" width="40%" rowspan="2">
									<?= lang_get( 'perm_rpt_capability' ) ?>
								</th>
								<th class="bold" style="text-align:center"  width="40%" colspan="<?= count( $g_access_levels ) ?>">
									<?= lang_get( 'access_levels' ) ?>
								</th>
							</tr>
							<tr>
							<?php foreach($g_access_levels as $t_access_level => $t_access_label): ?>
								<th class="bold" style="text-align:center">
									&#160;<?= MantisEnum::getLabel(lang_get('access_levels_enum_string'), $t_access_level) ?>&#160;
								</th>
							<?php endforeach ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($thresholdsList as $threshold) {
								include __DIR__  . DIRECTORY_SEPARATOR
									. 'config__threshold_row.php';
							} ?>
						</tbody>
					</table>
				</div>
				<!-- /table-responsible -->
			</div>
			<!-- /widget-main -->
		</div>
		<!-- /widget-body -->
	</div>
	<!-- /widget-box -->
</fieldset>
