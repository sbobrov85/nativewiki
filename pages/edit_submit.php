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

access_ensure_project_level(plugin_config_get('edit_wiki_pages'));

plugin_require_api('helper/NativeWikiContent.php', 'NativeWiki');

form_security_validate('wiki_page');

// collect wiki page form data
$wikiPage = array(
	'page_id' => gpc_get_int('page_id', 0),
	'parent_id' => gpc_get_int('parent_id', 0),
	'project_id' => gpc_get_int('project_id', ALL_PROJECTS),
	'alias' => gpc_get_string('alias', null),
	'header' => gpc_get_string('header', ''),
	'wiki_text' => gpc_get_string('wiki_text')
);
$path = gpc_get_string('path', '');

if ($wikiPage['page_id']) { // update wiki page (edit action)
	NativeWikiContentHelper::updateWikiPage($wikiPage['page_id'], $wikiPage);
} else { // create new wiki page (new action)
	NativeWikiContentHelper::addWikiPage($wikiPage, $path);
}

// purge & exit
form_security_purge('wiki_page');

print_successful_redirect(
	plugin_page('content', true) . (!empty($path) ? '&path=' . $path : '')
);
