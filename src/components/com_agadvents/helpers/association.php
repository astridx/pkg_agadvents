<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

JLoader::register('AgadventsHelper', JPATH_ADMINISTRATOR . '/components/com_agadvents/helpers/agadvents.php');
JLoader::register('AgadventsHelperRoute', JPATH_SITE . '/components/com_agadvents/helpers/route.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * Agadvents Component Association Helper
 *
 * @since  3.0
 */
abstract class AgadventsHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since   3.0
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		$jinput = JFactory::getApplication()->input;
		$view   = is_null($view) ? $jinput->get('view') : $view;
		$id     = empty($id) ? $jinput->getInt('id') : $id;

		if ($view === 'agadvent')
		{
			if ($id)
			{
				$associations = JLanguageAssociations::getAssociations('com_agadvents', '#__agadvents', 'com_agadvents.item', $id);

				$return = array();

				foreach ($associations as $tag => $item)
				{
					$return[$tag] = AgadventsHelperRoute::getAgadventRoute($item->id, (int) $item->catid, $item->language);
				}

				return $return;
			}
		}

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_agadvents');
		}

		return array();
	}
}
