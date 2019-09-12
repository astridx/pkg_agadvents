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

/**
 * Agadvents helper.
 *
 * @since  1.6
 */
class AgadventsHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName = 'agadvents')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_AGADVENTS_SUBMENU_AGADVENTS'), 'index.php?option=com_agadvents&view=agadvents', $vName == 'agadvents'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_AGADVENTS_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&extension=com_agadvents', $vName == 'categories'
		);

		if (JComponentHelper::isEnabled('com_fields'))
		{
			JHtmlSidebar::addEntry(
				JText::_('JGLOBAL_FIELDS'), 'index.php?option=com_fields&context=com_agadvents.agadvent', $vName == 'fields.fields'
			);

			JHtmlSidebar::addEntry(
				JText::_('JGLOBAL_FIELD_GROUPS'), 'index.php?option=com_fields&view=groups&context=com_agadvents.agadvent', $vName == 'fields.groups'
			);
		}
	}

	/**
	 * Adds Count Items for Advent Category Manager.
	 *
	 * @param   stdClass[]  &$items  The agadvents category objects.
	 *
	 * @return  stdClass[]  The agadvents category objects.
	 *
	 * @since   3.6.0
	 */
	public static function countItems(&$items)
	{
		$db = JFactory::getDbo();

		foreach ($items as $item)
		{
			$item->count_trashed = 0;
			$item->count_archived = 0;
			$item->count_unpublished = 0;
			$item->count_published = 0;

			$query = $db->getQuery(true)
				->select('state, COUNT(*) AS count')
				->from($db->qn('#__agadvents'))
				->where($db->qn('catid') . ' = ' . (int) $item->id)
				->group('state');

			$db->setQuery($query);
			$agadvents = $db->loadObjectList();

			foreach ($agadvents as $agadvent)
			{
				if ($agadvent->state == 1)
				{
					$item->count_published = $agadvent->count;
				}
				elseif ($agadvent->state == 0)
				{
					$item->count_unpublished = $agadvent->count;
				}
				elseif ($agadvent->state == 2)
				{
					$item->count_archived = $agadvent->count;
				}
				elseif ($agadvent->state == -2)
				{
					$item->count_trashed = $agadvent->count;
				}
			}
		}

		return $items;
	}
}
