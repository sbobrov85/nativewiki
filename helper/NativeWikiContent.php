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
     * @param int $projectId wiki project id.
	 * @return array header and wiki text pair or empty array.
	 */
	public static function getContent($path, $projectId = ALL_PROJECTS)
	{
		//base query parts arrays.
		$query = array(
			'SELECT p.header, p.wiki_text FROM ' . plugin_table('page') . ' p'
		);
		$queryWhere = array();

		// try explode multilevel path by colon.
		$paths = array_filter(explode(':', $path));

		// get only two last aliases (parent->child).
		if (count($paths) > 2) {
			$paths = array_slice($paths, 0, -2);
		}

		if (count($paths)) {
			$queryWhere []= 'p.alias = ' . db_param();
		} else { // default way...
			$queryWhere []= 'p.alias IS NULL';
		}

		// for top pages lock parent_id
		if (count($paths) < 2) {
			$queryWhere []= 'p.parent_id = 0';
		}

		// check parent_id relation.
		if (count($paths) > 1) {
			$paths = array_reverse($paths);
			$query []= 'JOIN ' . plugin_table('page')
				. ' pp ON pp.page_id = p.parent_id';
			$queryWhere []= 'pp.alias = ' . db_param();
		}

		// lock current project
        $queryWhere []= 'p.project_id = ' . db_param();

		// build query params.
		$dbParams = array();
		if (!empty($paths)) {
			$dbParams = array_merge($dbParams, $paths);
		}
		$dbParams []= $projectId;

		// get ado result instance.
		$ado = db_query(
			implode(' ', $query) . ' WHERE ' . implode(' AND ', $queryWhere),
			$dbParams
		);

		// fetch result.
		$page = $ado->getRowAssoc();

		return $page ?: array();
	}
}
