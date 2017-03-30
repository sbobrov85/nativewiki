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
	 * Explode string path to array.
	 * @param string $path raw path from url.
	 * @return array exploded path (chunks).
	 */
	public static function explodePath($path)
	{
		return array_filter(explode(':', $path));
	}

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
			'SELECT p.* FROM ' . plugin_table('page') . ' p'
		);
		$queryWhere = array();

		// try explode multilevel path by colon.
		$paths = self::explodePath($path);

		// get only two last aliases (parent->child).
		if (count($paths) > 2) {
			$paths = array_slice($paths, -2, 2);
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

	/**
	 * Update existing wiki page.
	 * @param int $pageId database wiki page id.
	 * @param array $bind wiki page data.
	 */
	public static function updateWikiPage($pageId, $bind)
	{
		$query = 'UPDATE ' . plugin_table('page') . ' SET '
			. 'alias = ' . db_param() . ', '
			. 'header = ' . db_param() . ', '
			. 'wiki_text = ' . db_param()
			. ' WHERE page_id = ' . $pageId;
		$bind = array_intersect_key(
			$bind,
			array_flip(array('alias', 'header', 'wiki_text'))
		);

		db_query($query, $bind);
	}

	/**
	 * Check and add all path pages and new wiki page.
	 * @param array $bind wiki page form data.
	 * @param string $path current page path.
	 */
	public static function addWikiPage($bind, $path)
	{
		$paths = self::explodePath($path);

		if (count($paths)) { // remove last chunk
			unset($paths[count($paths) - 1]);
		}

		$bind['parent_id'] = self::generatePathPages($paths);
		
		self::insertWikiPage($bind);
	}

	/**
	 * Insert wiki page data into db.
	 * @param array $bind wiki page data.
	 * @return int last inserted page id.
	 */
	protected static function insertWikiPage($bind)
	{

		$query = 'INSERT INTO ' . plugin_table('page') . ' VALUES ('
			. db_param() . ', '
			. db_param() . ', '
			. db_param() . ', '
			. db_param() . ', '
			. db_param() . ', '
			. db_param() . ')';

		db_query($query, $bind);

		return db_insert_id();
	}

	/**
	 * Generate path pages (recursive).
	 * @param array $paths paths chunks.
	 * @param int $parentId parent page id.
	 * @return int last parent id.
	 */
	public static function generatePathPages(array $paths, $parentId = 0)
	{
		if (count($paths)) {
			$pathChunk = array_shift($paths);
			$query = 'SELECT page_id FROM ' . plugin_table('page')
				. ' WHERE alias = ' . db_param()
				. ' AND parent_id = ' . db_param();
			$adoResult = db_query($query, array($pathChunk, $parentId));
			$result = $adoResult->getRowAssoc();

			if (empty($result['page_id'])) {
				$parentId = self::insertWikiPage(array(
					'page_id' => 0,
					'parent_id' => $parentId,
					'project_id' => helper_get_current_project(),
					'alias' => $pathChunk,
					'header' => '',
					'wiki_text' => ''
				));
			} else {
				$parentId = $result['page_id'];
			}

			$parentId = self::generatePathPages($paths, $parentId);
		}

		return $parentId;
	}

	/**
	 * Check if exists pages for current path (recursive).
	 * @param array $paths paths chunks.
	 * @return bool true, if all pages is exists, false otherwise.
	 */
	public static function validatePath(array $paths)
	{
		$result = true;

		if (count($paths > 2)) {
			$parentAlias = array_shift($paths);
			$childrenAlias = array_shift($paths);

			$query = 'SELECT cp.alias FROM ' . plugin_table('page') . ' pp'
				. ' JOIN ' . plugin_table('page')
				. ' cp ON cp.parent_id = pp.page_id'
				. ' WHERE pp.alias = ' . db_param();
			
			$adoResult = db_query($query, array($parentAlias));

			$page = $adoResult->getRowAssoc();

			if (!isset($page['alias']) || $page['alias'] != $childrenAlias) {
				$result = false;
			} else {
				$result = self::validatePath($paths);
			}
		}

		return $result;
	}

	/**
	 * Remove wiki page text and history.
	 * @param string $path wiki page path.
	 * @param int $projectId current project id.
	 */
	public static function deleteWikiPage($path, $projectId)
	{
		$page = self::getContent($path, $projectId);

		if (!empty($page['page_id'])) {
			$page['header'] = '';
			$page['wiki_text'] = '';
			self::updateWikiPage($page['page_id'], $page);
		}
	}
}
