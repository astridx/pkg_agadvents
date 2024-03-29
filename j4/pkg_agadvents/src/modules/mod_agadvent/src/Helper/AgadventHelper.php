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

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\String\StringHelper;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Uri\Uri;

/**
 * Helper for mod_agadvent
 *
 * @since  __BUMP_VERSION__
 */
abstract class AgadventHelper
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

	/**
	 * Method to get the image of the categorie.
	 *
	 * @return  string  The image of the categorie.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public static function getCatimage($catid)
	{
		$categories = Categories::getInstance('com_agadvents', []);
		$category = $categories->get($catid);
		
		$catimage = $category->getParams()->get('image');

		if ($catimage !== null) {
			$catimageURL = Uri::root() . "/" . $catimage;
		} else {
			$catimageURL = Uri::root() . "/media/com_agadvents/images/noimage.png";
		}

		return $catimageURL;
	}

	/**
	 * Get a list of articles from a specific category
	 *
	 * @param   \Joomla\Registry\Registry  &$params  object holding the models parameters
	 *
	 * @return  mixed
	 *
	 * @since  __BUMP_VERSION__
	 */
	public static function getList(&$params)
	{
		$app = Factory::getApplication();
		$factory = $app->bootComponent('com_agadvents')->getMVCFactory();

		$agadvents = $factory->createModel('Agadvents', 'Site', ['ignore_request' => true]);

		$agadvents->setState('filter.category_id', $params->get('catid')[0]);

		$items = $agadvents->getItems();

		// Prepare data for display using display options
		foreach ($items as &$item) {
			$item->slug = $item->id; // . ':' . $item->alias;
			if ($params->get('test')) {
				$item->layout = 'default_test';
			} else {
				$item->layout = 'default';
			}
		}

		return $items;
	}

	/**
	 * Strips unnecessary tags from the introtext
	 *
	 * @param   string  $introtext  introtext to sanitize
	 *
	 * @return mixed|string
	 *
	 * @since  __BUMP_VERSION__
	 */
	public static function _cleanIntrotext($introtext)
	{
		$introtext = str_replace(['<p>', '</p>'], ' ', $introtext);
		$introtext = strip_tags($introtext, '<a><em><strong>');
		$introtext = trim($introtext);

		return $introtext;
	}
}
