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

auth_reauthenticate( );
access_ensure_global_level(config_get('manage_plugin_threshold'));

layout_page_header();

layout_page_begin( 'manage_overview_page.php' );

print_manage_menu( 'manage_plugin_page.php' );

$wiki_engines = array(
	'markdown' => 'Markdown',
	'creole' => 'Creole'
);

$g_project_id = helper_get_current_project();

// prepare project title.
if( ALL_PROJECTS == $g_project_id ) {
	$t_project_title = lang_get('config_all_projects');
} else {
	$t_project_title = sprintf(
		lang_get('config_project'),
		string_display(project_get_name($g_project_id))
	);
}

$g_access_levels = MantisEnum::getAssocArrayIndexedByValues(
	config_get('access_levels_enum_string')
);

$wiki_engine_global = plugin_config_get('wiki_engine', null, true);
$wiki_engine_global_project = plugin_config_get(
	'wiki_engine',
	null,
	true,
	ALL_USERS,
	$g_project_id
);
$wiki_engine_project = plugin_config_get('wiki_engine');

$wiki_engine = reset(array_reverse(array_filter(array(
	$wiki_engine_global,
	$wiki_engine_global_project,
	$wiki_engine_project
))));

$color = NativeWikiCommonHelper::setColor(
	$g_project_id,
	'wiki_engine',
	$wiki_engine_global,
	$wiki_engine_global_project,
	$wiki_engine_project,
	false
);

?>

<!-- main wrap -->
<div class="col-md-12 col-xs-12">
	<div class="space-10"></div>
	<!-- well -->
	<div class="well">
		<p class="bold">
			<i class="fa fa-info-circle"></i>
			<?= $t_project_title ?>
		</p>
		<p>
			<?= lang_get('colour_coding') ?>
			<?php if (ALL_PROJECTS <> $g_project_id): ?>
				<span class="color-project">
					<?= lang_get( 'colour_project' ) ?>
				</span><br />
			<?php endif ?>
			<span class="color-global">
				<?= lang_get( 'colour_global' ) ?>
			</span>
		</p>
	</div>
	<!-- /well -->

	<!-- form-container -->
	<div id="common-options-div" class="form-container">
		<form
			id="common-config-form"
			method="post"
			action="<?= plugin_page('config_submit') ?>"
		>
			<?= form_security_field('plugin_nativewiki_config_submit') ?>
			<fieldset>
				<!-- widget-box -->
				<div class="widget-box widget-color-blue2">

					<!-- widget-header -->
					<div class="widget-header widget-header-small">
						<h4 class="widget-title lighter">
							<i class="ace-icon fa fa-cog"></i>
							<?= lang_get('plugin_NativeWiki_config_common') ?>
						</h4>
					</div>
					<!-- /widget-header -->

					<!-- widget-body -->
					<div class="widget-body">
						<!-- widget-main -->
						<div class="widget-main no-padding">
							<!-- table-responsive -->
							<div class="table-responsive">
								<table class="table table-bordered table-condensed table-striped">
									<tr>
										<td class="category">
											<?= lang_get('plugin_NativeWiki_config_engine') ?>
										</td>
										<td>
											<label>
												<select
													name="wiki_engine"
													class="<?= $color ?>">
													<?php foreach ($wiki_engines as $wiki_engine_value => $wiki_engine_label): ?>
													<option
														value="<?= $wiki_engine_value ?>"<?= $wiki_engine_value == $wiki_engine ? ' selected="selected"' : '' ?>>
														<?= $wiki_engine_label ?>
													</option>
													<?php endforeach ?>
												</select>
												<span class="lbl"></span>
											</label>
										</td>
									</tr>
								</table>
							</div>
							<!-- /table-responsive -->
						</div>
						<!-- /widget-main -->
					</div>
					<!-- /widget-body -->
				</div>
				<!-- /widget-box -->
			</fieldset>

			<?php include __DIR__
				. DIRECTORY_SEPARATOR . 'include'
				. DIRECTORY_SEPARATOR . 'config__access.php'
			?>

			<!-- submit -->
			<div class="space-10"></div>
			<input
				type="submit"
				class="btn btn-primary btn-white btn-round"
				value="<?= lang_get('change_configuration') ?>"
			/>
			<!-- /submit -->

		</form>
	</div>
	<!-- /form-container -->

</div>
<!-- /main wrap -->

<?php
layout_page_end();
