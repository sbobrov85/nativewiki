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

class NativeWikiContentHelper {
	/**
	 * Get wiki content from database.
	 * @param string $path colon-separated string.
	 * @return array header and wiki text pair or empty array.
	 */
	public static function getContent($path)
	{
		$paths = array_filter(implode(':', $path));
		if (count($paths) > 2) {
			$paths = array_slice($paths, 0, -2);
		}

		$query = array(
			'SELECT p.header, p.wiki_text FROM ' . plugin_table('page') . ' p'
		);
		$queryWhere = array('WHERE');

		if (count($paths)) {
			$queryWhere []= 'p.alias = ' . $db_param();
		} else {
			$queryWhere []= 'p.page_id = 0';
		}

		if (count($paths) > 1) {
			$paths = array_reverse($paths);
			$query []= 'JOIN wiki_text as pp ON pp.page_id = p.page_id';
			$queryWhere []= 'AND pp.alias_id = ' . $db_param();
		}

		$ado = db_query(
			implode(' ', $query) . ' ' . implode(' ', $queryWhere),
			!empty($paths) ? $paths : null
		);

		$page = $ado->getRowAssoc();

		return $page ?: array();
	}
}
