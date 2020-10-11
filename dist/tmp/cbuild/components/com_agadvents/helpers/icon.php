<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2020 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

/**
 * Agadvent Component HTML Helper.
 *
 * @since  1.5
 */
class JHtmlIcon
{
	/**
	 * Create a link to create a new agadvent
	 *
	 * @param   mixed  $agadvent  Unused
	 * @param   mixed  $params    Unused
	 *
	 * @return  string
	 */
	public static function create($agadvent, $params)
	{
		JHtml::_('bootstrap.tooltip');

		$uri = JUri::getInstance();
		$url = JRoute::_(AgadventsHelperRoute::getFormRoute(0, base64_encode($uri)));
		$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
		$button = JHtml::_('link', $url, $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_AGADVENTS_FORM_CREATE_AGADVENT') . '">' . $button . '</span>';
	}

	/**
	 * Create a link to edit an existing agadvent
	 *
	 * @param   object                     $agadvent  Agadvent data
	 * @param   \Joomla\Registry\Registry  $params    Item params
	 * @param   array                      $attribs   Unused
	 *
	 * @return  string
	 */
	public static function edit($agadvent, $params, $attribs = array())
	{
		$uri = JUri::getInstance();

		if ($params && $params->get('popup'))
		{
			return;
		}

		if ($agadvent->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		$url	= AgadventsHelperRoute::getFormRoute($agadvent->id, base64_encode($uri));
		$icon	= $agadvent->state ? 'edit.png' : 'edit_unpublished.png';
		$text	= JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);

		if ($agadvent->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date = JHtml::_('date', $agadvent->created);
		$author = $agadvent->created_by_alias ? $agadvent->created_by_alias : $agadvent->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');

		$button = JHtml::_('link', JRoute::_($url), $text);

		return '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_AGADVENTS_EDIT') . ' :: ' . $overlib . '">' . $button . '</span>';
	}
}
