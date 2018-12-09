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
 * HTML Agadvent View class for the Agadvents component
 *
 * @since  1.0.0
 */
class AgadventsViewAgadvent extends JViewLegacy
{
	protected $item;

	protected $params;

	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$dispatcher = JEventDispatcher::getInstance();

		$this->item   = $this->get('Item');
		$this->state  = $this->get('State');
		$this->params = $this->state->get('params');

		// Create a shortcut for $item.
		$item = $this->item;

		$item->tagLayout = new JLayoutFile('joomla.content.tags');
		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_agadvents.agadvent', $this->item->id);

		$offset = $this->state->get('list.offset');

		JPluginHelper::importPlugin('content');

		$item->text = $item->content;
		$dispatcher->trigger('onContentPrepare', array ('com_agadvents.agadvent', &$item, &$item->params, $offset));
		$item->content = $item->text;

		$item->event = new stdClass;

		$results = $dispatcher->trigger('onContentAfterTitle', array('com_agadvents.agadvent', &$item, &$item->params, $offset));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_agadvents.agadvent', &$item, &$item->params, $offset));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->trigger('onContentAfterDisplay', array('com_agadvents.agadvent', &$item, &$item->params, $offset));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		$externeUrl = $item->show_url;

		if ($externeUrl)
		{
			header('Location: ' . $item->url, true, 307);

			exit();
		}

		$document = JFactory::getDocument();
		$style = 'body {'
			. 'background-color:' . $this->params->get('popup_background_color', '#fff') . ';'
			. '}';
		$document->addStyleDeclaration($style);

		parent::display($tpl);
	}
}
