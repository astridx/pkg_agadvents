<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_agadvent
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Module\Agadvent\Site\Helper;

\defined('_JEXEC') or die;

/**
 * Helper for mod_agadvent
 *
 * @since  __BUMP_VERSION__
 */
class AgadventHelper
{
	/**
	 * Retrieve agadvent test
	 *
	 * @param   Registry        $params  The module parameters
	 * @param   CMSApplication  $app     The application
	 *
	 * @return  array
	 */
	public static function getText()
	{
		return 'AgadventHelpertest';
	}
}
