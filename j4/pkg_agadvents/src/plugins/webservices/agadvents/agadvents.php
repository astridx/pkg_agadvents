<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Webservices.agadvents
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;

/**
 * Web Services adapter for com_agadvents.
 *
 * @since  __BUMP_VERSION__
 */
class PlgWebservicesAgadvents extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  __BUMP_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Registers com_agadvents's API's routes in the application
	 *
	 * @param   ApiRouter  &$router  The API Routing object
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function onBeforeApiRoute(&$router)
	{
		$router->createCRUDRoutes(
			'v1/agadvents',
			'agadvent',
			['component' => 'com_agadvents']
		);

		$router->createCRUDRoutes(
			'v1/agadvents/categories',
			'categories',
			['component' => 'com_categories', 'extension' => 'com_agadvents']
		);
	}
}
