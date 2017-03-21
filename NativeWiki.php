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

/**
 * Native Wiki plugin
 */

plugin_require_api('helper/NativeWikiCommon.php', 'NativeWiki');

class NativeWikiPlugin extends MantisPlugin  {
	/**
	* {@inheriteDoc}
	* @see MantisPlugin::register()
	*/
	public function register()
	{
		$this->name = lang_get('plugin_NativeWiki_name');
		$this->description = lang_get('plugin_NativeWiki_description');
		$this->page = 'config';

		$this->version = '1.0.0';
		$this->requires = array(
			'MantisCore' => '2.0.0',
		);

		$this->author = 'JeraIT Team';
		$this->contact = 'support@jerait.com';
		$this->url = 'http://jerait.com';
	}

	/**
	 * {@inheriteDoc}
	 * @see MantisPlugin::register()
	 */
	public function hooks()
	{
		return array(
			'EVENT_MENU_MAIN' => 'main_menu'
		);
	}

	/**
	 * Creating wiki menu item on sidebar.
	 * @return array
	 */
	public function main_menu()
	{
		$mainMenuItems = array();

		if (access_has_project_level(plugin_config_get('view_wiki'))) {
			$mainMenuItems[] = array(
				'title' => lang_get('plugin_NativeWiki_wiki'),
				'access_level' => ANYBODY,
				'url' => NativeWikiCommonHelper::getWikiUrl(),
				'icon' => 'fa-book'
			);
		}

		return $mainMenuItems;
	}

	/**
	 * {@inheriteDoc}
	 * @see MantisPlugin::config()
	 */
	public function config()
	{
		$access = array();

		foreach (NativeWiki::getThresholdList() as $threshold) {
			$access[$threshold] = ADMINISTRATOR;
		}

		return $access;
	}
}
