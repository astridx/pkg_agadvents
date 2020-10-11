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
 * HTML View class for the WebLinks component
 *
 * @since  1.5
 */
class AgadventsViewImagemap extends JViewCategory
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{

		parent::commonCategoryDisplay();

		// Prepare the data.
		// Compute the agadvent slug & link url.
		foreach ($this->items as $item)
		{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

			if ($item->params->get('count_clicks', $this->params->get('count_clicks', 1)) == 1)
			{
				$item->link	= JRoute::_('index.php?option=com_agadvents&task=agadvent.go&view=agadvent&id=' . $item->slug);
				$item->link_popup	= JRoute::_('index.php?tmpl=component&option=com_agadvents&task=agadvent.go&view=agadvent&id=' . $item->slug);
			}
			else
			{
				$item->link	= JRoute::_('index.php?option=com_agadvents&view=agadvent&id=' . $item->slug);
				$item->link_popup	= JRoute::_('index.php?option=com_agadvents&tmpl=component&view=agadvent&id=' . $item->slug);
			}

			$item->link_notactive	= JURI::root() . 'index.php?option=com_agadvents&view=agadvent_no&id=' . $item->id;
			$item->link_notactive_popup	= JURI::root() . 'index.php?tmpl=component&option=com_agadvents&view=agadvent_no&id=' . $item->id;

			$temp = new JRegistry;
			$temp->loadString($item->params);
			$item->params = clone $this->params;
			$item->params->merge($temp);
		}

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_AGADVENTS_DEFAULT_PAGE_TITLE'));
		}

		$id = (int) @$menu->query['id'];

		if ($menu && ($menu->query['option'] != 'com_agadvents' || $id != $this->category->id))
		{
			$this->params->set('page_subheading', $this->category->title);
			$path = array(array('title' => $this->category->title, 'link' => ''));
			$category = $this->category->getParent();

			while (($menu->query['option'] != 'com_agadvents' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => AgadventsHelperRoute::getCategoryRoute($category->id));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		parent::addFeed();
	}
}
