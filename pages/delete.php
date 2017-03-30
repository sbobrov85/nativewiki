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

plugin_require_api('helper/NativeWikiContent.php', 'NativeWiki');

access_ensure_project_level(plugin_config_get('delete_wiki_pages'));

$path = gpc_get_string('path', '');

helper_ensure_confirmed(
	lang_get('plugin_NativeWiki_delete_sure_msg'),
	lang_get('plugin_NativeWiki_delete')
);

NativeWikiContentHelper::deleteWikiPage($path, helper_get_current_project());

print_successful_redirect(plugin_page('content.php'));
