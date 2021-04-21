<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Component\Agadvents\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Agadvents Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_agadvents
 * @since       __DEPLOY_VERSION__
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a agadvents from a agadvent ID, agadvents category ID and language
	 *
	 * @param   integer  $id        The id of the agadvents
	 * @param   integer  $catid     The id of the agadvents's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agadvents
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgadventsRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agadvents&view=agadvents&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agadvent from a agadvent ID, agadvents category ID and language
	 *
	 * @param   integer  $id        The id of the agadvents
	 * @param   integer  $catid     The id of the agadvents's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the agadvents
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getAgadventRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_agadvents&view=agadvent&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a agadvents category from a agadvents category ID and language
	 *
	 * @param   mixed  $catid     The id of the agadvents's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the agadvents
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode) {
			$id = $catid->id;
		} else {
			$id = (int) $catid;
		}

		if ($id < 1) {
			$link = '';
		} else {
			// Create the link
			$link = 'index.php?option=com_agadvents&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled()) {
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}
