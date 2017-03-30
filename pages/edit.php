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

access_ensure_project_level(plugin_config_get('edit_wiki_pages'));

layout_page_header();

layout_page_begin(NativeWikiCommonHelper::getWikiUrl());

$path = gpc_get_string('path', '');
$projectId = helper_get_current_project();
$content = NativeWikiContentHelper::getContent($path, $projectId);
if (empty($content) && !empty($path)) {
	$paths = explode(':', $path);
	$content['alias'] = array_pop($paths);
}
?>

<div class="col-md-12 col-xs-12">
	<form id="edit_wiki_page_form"
		method="post"
		action="<?= plugin_page('edit_submit.php') ?>"
	>
		<?= form_security_field('wiki_page') ?>

		<input
			type="hidden"
			name="page_id"
			value="<?= isset($content['page_id']) ? $content['page_id'] : '' ?>"
		/>
		<input
			type="hidden"
			name="parent_id"
			value="<?= isset($content['parent_id']) ? $content['parent_id'] : '' ?>"
		/>
		<input type="hidden" name ="project_id" value="<?= $projectId ?>"/>
		<input
			type="hidden"
			name="path"
			value="<?= $path ?>"
		/>

		<!-- widget-box -->
		<div class="widget-box widget-color-blue2">
			<!-- widget-header -->
			<div class="widget-header widget-header-small">
				<h4 class="widget-title lighter">
					<i class="ace-icon fa fa-edit"></i>
					<?= lang_get('plugin_NativeWiki_edit_wiki_page_title') ?>
				</h4>
			</div>
			<!-- /widget-header -->

			<!-- widget-main -->
			<div class="widget-main no-padding">
				<!-- table-responsive -->
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<tr>
							<td class="category" width="30%">
								<label for="alias">
									<?= lang_get('plugin_NativeWiki_alias') ?>
								</label>
							</td>
							<td width="70%">
								<input
									type="text"
									name="alias"
									id="alias"
									size="105"
									value="<?= isset($content['alias']) ? $content['alias'] : '' ?>"
								/>
							</td>
						</tr>
						<tr>
							<td class="category" width="30%">
								<label for="header">
									<?= lang_get('plugin_NativeWiki_header') ?>
								</label>
							</td>
							<td width="70%">
								<input
									type="text"
									name="header"
									id="header"
									size="105"
									value="<?= isset($content['header']) ? $content['header'] : '' ?>"
								/>
							</td>
						</tr>
						<tr>
							<td class="category" width="30%">
								<label for="wiki_text">
									<?= lang_get('plugin_NativeWiki_wiki_text') ?>
								</label>
							</td>
							<td>
								<textarea
									name="wiki_text"
									id="wiki_text"
									class="form-control"
									cols="80"
									rows="10"
								><?=
									isset($content['wiki_text']) ?
										$content['wiki_text'] : ''
								?></textarea>
							</td>
						</tr>
					</table>
				</div>
				<!-- /table-responsive -->
			</div>
			<!-- /widget-main -->

			<!-- widget-toolbox -->
			<div class="widget-toolbox padding-8 clearfix">
				<span class="required pull-right">
					* <?= lang_get('plugin_NativeWiki_all_required') ?>
				</span>
				<input
					type="submit"
					class="btn btn-primary btn-white btn-round"
					value="<?= lang_get('plugin_NativeWiki_edit_submit') ?>"
				/>
			</div>
			<!-- /widget-toolbox -->
		</div>
		<!-- /widget-box -->

	</form>

</div>

<?php
layout_page_end();
