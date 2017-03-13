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

form_security_validate('plugin_nativewiki_config_submit');

auth_reauthenticate( );
access_ensure_global_level(config_get('manage_plugin_threshold'));

plugin_require_api('lib/config.php');

$f_process_wiki_engine = gpc_get_string('wiki_engine', 'markdown');
plugin_config_set('wiki_engine', $f_process_wiki_engine);

form_security_purge('plugin_nativewiki_config_submit');

print_successful_redirect(plugin_page('config', true ));
