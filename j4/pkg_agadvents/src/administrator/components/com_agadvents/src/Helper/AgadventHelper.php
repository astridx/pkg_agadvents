<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Component\Agadvents\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

/**
 * Agadvent component helper.
 *
 * @since  __BUMP_VERSION__
 */
class AgadventHelper extends ContentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public static function addSubmenu($vName)
	{
		if (ComponentHelper::isEnabled('com_fields') && ComponentHelper::getParams('com_agadvents')->get('custom_fields_enable', '1')) {
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_agadvents.agadvent',
				$vName == 'fields.fields'
			);
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_agadvents.agadvent',
				$vName == 'fields.groups'
			);
		}
	}
}
