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
 * @class NativeWiki
 */
class NativeWiki {
	 /**
	  * Get full wiki url with project and other params.
	  * @return string full wiki url.
	  */
	 public static function getWikiUrl()
	 {
		 $url = plugin_page('content.php');
		 $project = helper_get_current_project();

		 if (!empty($project) && !is_int($project)) {
		 	$url .= '&amp;=' . $project;
		 }

		 return $url;
	 }
}
