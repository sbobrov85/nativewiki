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

plugin_require_api('helper/NativeWikiCommon.php', 'NativeWiki');
plugin_require_api('helper/NativeWikiContent.php', 'NativeWiki');

access_ensure_project_level(plugin_config_get('view_wiki'));

layout_page_header();

layout_page_begin(NativeWikiCommonHelper::getWikiUrl());

// controll access.

$canEdit = access_has_project_level(plugin_config_get('edit_wiki_pages'));
$canViewHistory = access_has_project_level(plugin_config_get('view_wiki_pages_history'));

$content = NativeWikiContentHelper::getContent(
	gpc_get_string('path', ''),
	helper_get_current_project()
);

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
						if (!empty($content['header'])) {
							echo $content['header'];
						} else {
							echo lang_get('plugin_NativeWiki_empty_header');
						}
					?>
				</h4>
			</div>
			<!-- /widget-header -->

			<?php if ($canEdit || $canViewHistory): ?>
			<!-- widger-toolbox -->
			<div class="widget-toolbox padding-8 clearfix">
				<!-- btn-toolbar -->
				<div class="btn-toolbar">
					<!-- btn-group -->
					<div class="btn-group pull-left">
					<?php
						if ($canEdit) {
							print_small_button(
								plugin_page('edit.php'),
								lang_get('plugin_NativeWiki_edit')
							);
						}
						if ($canViewHistory) {
							print_small_button(
								plugin_page('history.php'),
								lang_get('plugin_NativeWiki_history')
							);
						}
					?>
					</div>
					<!-- /btn-group -->
				</div>
				<!-- /btn-toolbar -->

				<div class="space-10"></div>

				<!-- content -->
				<div class="content">
					<?php if (!empty($content['wiki_text'])) {
						echo $content['wiki_text'];
					} else {
						echo lang_get('plugin_NativeWiki_nopage');
					} ?>
				</div>
				<!-- /content -->

				<div class="space-10"></div>
			</div>
			<!-- /widger-toolbox -->
			<?php endif ?>

		</div>
		<!-- /widget-box -->
	</div>
	<!-- /widget-body -->

</div>

<?php
layout_page_end();
