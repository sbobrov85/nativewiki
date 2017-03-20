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

		 return $url;
	 }

	/**
	 * Defines the cell's background color and sets the overrides.
	 * Copy paste from mantis source code and renamed.
	 * @param int     $g_project_id   Current project id.
	 * @param string  $threshold    Configuration option.
	 * @param string  $p_file         System default value.
	 * @param string  $p_global       All projects value.
	 * @param string  $p_project      Current project value.
	 * @param boolean $p_set_override If true, will define an override if needed.
	 * @return string HTML tag attribute for background color override
	 */
	public static function setColor(
		$g_project_id,
		$threshold,
		$p_file,
		$p_global,
		$p_project,
		$p_set_override
	) {
		$t_color = '';

		# all projects override
		if( $p_global != $p_file ) {
			$t_color = 'color-global';
			// if( $p_set_override && ALL_PROJECTS == $g_project_id ) {
			// 	set_overrides( $threshold );
			// }
		}

		# project overrides
		if( $p_project != $p_global ) {
			$t_color = 'color-project';
			// if( $p_set_override && ALL_PROJECTS != $g_project_id ) {
			// 	set_overrides( $threshold );
			// }
		}

		return $t_color;
	}

	/**
	 * Return thresholds list.
	 * @return array thresholds list.
	 */
	public static function getThresholdList()
	{
		return array(
			'view_wiki',
			'edit_wiki_pages',
			'delete_wiki_pages',
			'view_wiki_pages_history',
		);
	}

	/**
	 * Get access level expression based on global access levels.
	 * @param mixed $accessRaw raw access level.
	 * @param array global access levels.
	 * @return array access level expression.
	 */
	public static function getAccessExpression($accessRaw, $globalAccessLevels)
	{
		if (!is_array($accessRaw)) {
			$accessExpression = array();
			foreach ($globalAccessLevels as $accessLevel => $accessLabel) {
				if ($accessLevel >= $accessRaw) {
					$accessExpression[] = $accessLevel;
				}
			}
		} else {
			$accessExpression = $accessRaw;
		}

		return $accessExpression;
	}

	/**
	 * Set plugin thresholds access.
	 * @param array $globalAccess global access.
	 * @param $projectId current project id.
	 * @param string $threshold threshold label.
	 * @param bool $p_all_projects_only true, if all projects only, false another.
	 */
	public static function setThresholdAccess(
		$globalAccess,
		$projectId,
		$threshold,
		$p_all_projects_only = false
	) {
		if (($globalAccess >= config_get_access($threshold)) &&
			((ALL_PROJECTS == $projectId) || !$p_all_projects_only)
		) {
			$f_threshold = gpc_get_int_array('flag_thres_' . $threshold, array());
			$t_access_levels = MantisEnum::getAssocArrayIndexedByValues(
				config_get('access_levels_enum_string')
			);
			ksort($t_access_levels);
			reset($t_access_levels);

			$t_lower_threshold = NOBODY;
			$t_array_threshold = array();

			foreach ($t_access_levels as $t_access_level => $t_level_name) {
				if( in_array( $t_access_level, $f_threshold ) ) {
					if( NOBODY == $t_lower_threshold ) {
						$t_lower_threshold = $t_access_level;
					}
					$t_array_threshold[] = $t_access_level;
				} else {
					if( NOBODY <> $t_lower_threshold ) {
						$t_lower_threshold = -1;
					}
				}
			}

			$t_existing_threshold = config_get($threshold);
			$t_existing_access = config_get_access($threshold);
			if (($t_existing_threshold != $t_array_threshold)) {
				$newThreshold = -1 == $t_lower_threshold ?
					$t_array_threshold : $t_lower_threshold;
				plugin_config_set(
					$threshold,
					$newThreshold,
					NO_USER,
					$projectId,
					$f_access
				);
			}
		}
	}
}
