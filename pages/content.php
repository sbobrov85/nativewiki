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

plugin_require_api('helper.php', 'NativeWiki');

layout_page_header();

layout_page_begin(NativeWiki::getWikiUrl());

?>

<div class="col-md-12 col-xs-12">
	<div class="space-10"></div>

	<!-- widget-body -->
	<div class="widget-body">
		<!-- widget-box -->
		<div class="widget-box widget-color-blue2">
			<!-- widget-header -->
			<div class="widget-header widget-header-small">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-file-o"></i>
					<?= lang_get('plugin_NativeWiki_wiki_for') ?>
					<?php
						$currentProject = helper_get_current_project();
						if (!empty($currentProject) && !is_int($currentProject)) {
							echo $currentProject;
						} else {
							echo lang_get('all_projects');
						}
					?>
				</h4>
			</div>
			<!-- /widget-header -->

			<!-- widger-toolbox -->
			<div class="widget-toolbox padding-8 clearfix">
				<!-- btn-toolbar -->
				<div class="btn-toolbar">
					<!-- btn-group -->
					<div class="btn-group pull-left">
					<?php
						print_small_button(
							plugin_page('edit.php'),
							lang_get('plugin_NativeWiki_edit')
						);
						print_small_button(
							plugin_page('history.php'),
							lang_get('plugin_NativeWiki_history')
						);
					?>
					</div>
					<!-- /btn-group -->
				</div>
				<!-- /btn-toolbar -->
			</div>
			<!-- /widger-toolbox -->
		</div>
		<!-- /widget-box -->
	</div>
	<!-- /widget-body -->

</div>

<?php
layout_page_end();
