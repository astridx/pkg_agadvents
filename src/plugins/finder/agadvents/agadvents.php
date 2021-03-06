<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2020 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;

// Load the base adapter.
require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php';

/**
 * Smart Search adapter for Joomla Agadvents.
 *
 * @since  1.0
 */
class PlgFinderAgadvents extends FinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $context = 'Agadvents';

	/**
	 * The extension name.
	 *
	 * @var    string
	 * @since   1.0
	 */
	protected $extension = 'com_agadvents';

	/**
	 * The sublayout to use when rendering the results.
	 *
	 * @var    string
	 * @since   1.0
	 */
	protected $layout = 'agadvent';

	/**
	 * The type of content that the adapter indexes.
	 *
	 * @var    string
	 * @since   1.0
	 */
	protected $type_title = 'Agadvent';

	/**
	 * The table name.
	 *
	 * @var    string
	 * @since   1.0
	 */
	protected $table = '#__agadvents';

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method to update the item link information when the item category is
	 * changed. This is fired when the item category is published or unpublished
	 * from the list view.
	 *
	 * @param   string   $extension  The extension whose category has been updated.
	 * @param   array    $pks        An array of primary key ids of the content that has changed state.
	 * @param   integer  $value      The value of the state that the content has been changed to.
	 *
	 * @return  void
	 *
	 * @since    1.0
	 */
	public function onFinderCategoryChangeState($extension, $pks, $value)
	{
		// Make sure we're handling com_agadvents categories.
		if ($extension == 'com_agadvents')
		{
			$this->categoryStateChange($pks, $value);
		}
	}

	/**
	 * Method to remove the link information for items that have been deleted.
	 *
	 * @param   string  $context  The context of the action being performed.
	 * @param   JTable  $table    A JTable object containing the record to be deleted.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since    1.0
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context == 'com_agadvents.agadvent')
		{
			$id = $table->id;
		}
		elseif ($context == 'com_finder.index')
		{
			$id = $table->link_id;
		}
		else
		{
			return true;
		}

		// Remove the item from the index.
		return $this->remove($id);
	}

	/**
	 * Smart Search after content save method.
	 * Reindexes the link information for a agadvent that has been saved.
	 * It also makes adjustments if the access level of a agadvent item or
	 * the category to which it belongs has been changed.
	 *
	 * @param   string   $context  The context of the content passed to the plugin.
	 * @param   JTable   $row      A JTable object.
	 * @param   boolean  $isNew    True if the content has just been created.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since    1.0
	 * @throws  Exception on database error.
	 */
	public function onFinderAfterSave($context, $row, $isNew)
	{
		// We only want to handle agadvents here. We need to handle front end and back end editing.
		if ($context == 'com_agadvents.agadvent' || $context == 'com_agadvents.form')
		{
			// Check if the access levels are different.
			if (!$isNew && $this->old_access != $row->access)
			{
				// Process the change.
				$this->itemAccessChange($row);
			}

			// Reindex the item.
			$this->reindex($row->id);
		}

		// Check for access changes in the category.
		if ($context == 'com_categories.category')
		{
			// Check if the access levels are different.
			if (!$isNew && $this->old_cataccess != $row->access)
			{
				$this->categoryAccessChange($row);
			}
		}

		return true;
	}

	/**
	 * Smart Search before content save method.
	 * This event is fired before the data is actually saved.
	 *
	 * @param   string   $context  The context of the content passed to the plugin.
	 * @param   JTable   $row      A JTable object.
	 * @param   boolean  $isNew    True if the content is just about to be created.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since    1.0
	 * @throws  Exception on database error.
	 */
	public function onFinderBeforeSave($context, $row, $isNew)
	{
		// We only want to handle agadvents here.
		if ($context == 'com_agadvents.agadvent' || $context == 'com_agadvents.form')
		{
			// Query the database for the old access level if the item isn't new.
			if (!$isNew)
			{
				$this->checkItemAccess($row);
			}
		}

		// Check for access levels from the category.
		if ($context == 'com_categories.category')
		{
			// Query the database for the old access level if the item isn't new.
			if (!$isNew)
			{
				$this->checkCategoryAccess($row);
			}
		}

		return true;
	}

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * unpublished, archived, or unarchived from the list view.
	 *
	 * @param   string   $context  The context for the content passed to the plugin.
	 * @param   array    $pks      An array of primary key ids of the content that has changed state.
	 * @param   integer  $value    The value of the state that the content has been changed to.
	 *
	 * @return  void
	 *
	 * @since    1.0
	 */
	public function onFinderChangeState($context, $pks, $value)
	{
		// We only want to handle agadvent here.
		if ($context == 'com_agadvents.agadvent' || $context == 'com_agadvents.form')
		{
			$this->itemStateChange($pks, $value);
		}

		// Handle when the plugin is disabled.
		if ($context == 'com_plugins.plugin' && $value === 0)
		{
			$this->pluginDisable($pks);
		}
	}

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @param   FinderIndexerResult  $item    The item to index as an FinderIndexerResult object.
	 * @param   string               $format  The item format.  Not used.
	 *
	 * @return  void
	 *
	 * @since    1.0
	 * @throws  Exception on database error.
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		$item->setLanguage();

		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) == false)
		{
			return;
		}

		// Initialise the item parameters.
		$registry = new Registry;
		$registry->loadString($item->params);
		$item->params = $registry;

		$registry = new Registry;
		$registry->loadString($item->metadata);
		$item->metadata = $registry;

		// Build the necessary route and path information.
		$item->url = $this->getURL($item->id, $this->extension, $this->layout);
		$item->route = AgadventsHelperRoute::getAgadventRoute($item->slug, $item->catslug, $item->language);
		$item->path = FinderIndexerHelper::getContentPath($item->route);

		/*
		 * Add the meta-data processing instructions based on the newsfeeds
		 * configuration parameters.
		 */
		// Add the meta-author.
		$item->metaauthor = $item->metadata->get('author');

		// Handle the link to the meta-data.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'link');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metakey');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metadesc');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'metaauthor');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'author');
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'created_by_alias');

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'Agadvent');

		// Add the category taxonomy data.
		$item->addTaxonomy('Category', $item->category, $item->cat_state, $item->cat_access);

		// Add the language taxonomy data.
		$item->addTaxonomy('Language', $item->language);

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);

		// Index the item.
		$this->indexer->index($item);
	}

	/**
	 * Method to setup the indexer to be run.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since    1.0
	 */
	protected function setup()
	{
		// Load dependent classes.
		require_once JPATH_SITE . '/components/com_agadvents/helpers/route.php';

		return true;
	}

	/**
	 * Method to get the SQL query used to retrieve the list of content items.
	 *
	 * @param   mixed  $query  A JDatabaseQuery object or null.
	 *
	 * @return  JDatabaseQuery  A database object.
	 *
	 * @since    1.0
	 */
	protected function getListQuery($query = null)
	{
		$db = JFactory::getDbo();

		// Check if we can use the supplied SQL query.
		$query = $query instanceof JDatabaseQuery ? $query : $db->getQuery(true)
			->select('a.id, a.catid, a.title, a.alias, a.url AS link, a.description, a.content AS body')
			->select('a.metakey, a.metadesc, a.metadata, a.language, a.access, a.ordering')
			->select('a.created_by_alias, a.modified, a.modified_by')
			->select('a.publish_up AS publish_start_date, a.publish_down AS publish_end_date')
			->select('a.state AS state, a.created AS start_date, a.params')
			->select('c.title AS category, c.published AS cat_state, c.access AS cat_access');

		// Handle the alias CASE WHEN portion of the query.
		$case_when_item_alias = ' CASE WHEN ';
		$case_when_item_alias .= $query->charLength('a.alias', '!=', '0');
		$case_when_item_alias .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when_item_alias .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when_item_alias .= ' ELSE ';
		$case_when_item_alias .= $a_id . ' END as slug';
		$query->select($case_when_item_alias);

		$case_when_category_alias = ' CASE WHEN ';
		$case_when_category_alias .= $query->charLength('c.alias', '!=', '0');
		$case_when_category_alias .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when_category_alias .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when_category_alias .= ' ELSE ';
		$case_when_category_alias .= $c_id . ' END as catslug';
		$query->select($case_when_category_alias)

			->from('#__agadvents AS a')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		return $query;
	}
}
