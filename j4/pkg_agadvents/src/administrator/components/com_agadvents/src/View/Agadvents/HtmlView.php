<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Component\Agadvents\Administrator\View\Agadvents;

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use AgadventNamespace\Component\Agadvents\Administrator\Helper\AgadventHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;

/**
 * View class for a list of agadvents.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  \JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  \JObject
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function display($tpl = null): void
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');

		if (!count($this->items) && $this->get('IsEmptyState')) {
			$this->setLayout('emptystate');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
			$this->sidebar = \JHtmlSidebar::render();
		} else {
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', '', 'CMD')) {
				// If the language is forced we can't allow to select the language, so transform the language selector filter into a hidden field.
				$languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

				// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
				$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
			}
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function addToolbar()
	{
		AgadventHelper::addSubmenu('agadvents');
		$this->sidebar = \JHtmlSidebar::render();

		$canDo = ContentHelper::getActions('com_agadvents', 'category', $this->state->get('filter.category_id'));
		$user  = Factory::getUser();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(Text::_('COM_AGADVENTS_MANAGER_AGADVENTS'), 'address agadvent');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_agadvents', 'core.create')) > 0) {
			$toolbar->addNew('agadvent.add');
		}

		if ($canDo->get('core.edit.state')) {
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fa fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);
			$childBar = $dropdown->getChildToolbar();
			$childBar->publish('agadvents.publish')->listCheck(true);
			$childBar->unpublish('agadvents.unpublish')->listCheck(true);

			$childBar->standardButton('featured')
				->text('JFEATURE')
				->task('agadvents.featured')
				->listCheck(true);
			$childBar->standardButton('unfeatured')
				->text('JUNFEATURE')
				->task('agadvents.unfeatured')
				->listCheck(true);

			$childBar->archive('agadvents.archive')->listCheck(true);

			if ($user->authorise('core.admin')) {
				$childBar->checkin('agadvents.checkin')->listCheck(true);
			}

			if ($this->state->get('filter.published') != -2) {
				$childBar->trash('agadvents.trash')->listCheck(true);
			}

			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
				$childBar->delete('agadvents.delete')
					->text('JTOOLBAR_EMPTY_TRASH')
					->message('JGLOBAL_CONFIRM_DELETE')
					->listCheck(true);
			}

			// Add a batch button
			if ($user->authorise('core.create', 'com_agadvents')
				&& $user->authorise('core.edit', 'com_agadvents')
				&& $user->authorise('core.edit.state', 'com_agadvents')) {
				$childBar->popupButton('batch')
					->text('JTOOLBAR_BATCH')
					->selector('collapseModal')
					->listCheck(true);
			}
		}

		if ($user->authorise('core.admin', 'com_agadvents') || $user->authorise('core.options', 'com_agadvents')) {
			$toolbar->preferences('com_agadvents');
		}

		ToolbarHelper::divider();
		ToolbarHelper::help('', false, 'http://joomla.org');

		HTMLHelper::_('sidebar.setAction', 'index.php?option=com_agadvents');
	}
}
