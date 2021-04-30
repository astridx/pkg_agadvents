<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agadvents
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Component\Agadvents\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Agadvents\Administrator\Extension\AgadventsComponent;
use Joomla\Component\Agadvents\Site\Helper\AssociationHelper;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

/**
 * This models supports retrieving lists of agadvents.
 *
 * @since  1.6
 */
class AgadventsModel extends ListModel
{
	/**
	 * Get the master query for retrieving a list of agadvents subject to the model state.
	 *
	 * @return  \JDatabaseQuery
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$user = Factory::getUser();

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$nowDate = Factory::getDate()->toSql();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				[
					$db->quoteName('a.id'),
					$db->quoteName('a.name'),
					$db->quoteName('a.alias'),
					$db->quoteName('a.cords'),
					$db->quoteName('a.fulltext_no'),
					$db->quoteName('a.fulltext'),
					$db->quoteName('a.checked_out'),
					$db->quoteName('a.checked_out_time'),
					$db->quoteName('a.catid'),
					$db->quoteName('a.publish_up'),
					$db->quoteName('a.publish_down'),
					$db->quoteName('a.access'),
					$db->quoteName('a.featured'),
					$db->quoteName('a.language'),
					$query->length($db->quoteName('a.fulltext')) . ' AS ' . $db->quoteName('readmore'),
					$db->quoteName('a.ordering'),
				]
			)
		)
			->select(
				[
					$db->quoteName('c.title', 'category_title'),
					$db->quoteName('c.path', 'category_route'),
					$db->quoteName('c.access', 'category_access'),
					$db->quoteName('c.alias', 'category_alias'),
					$db->quoteName('c.language', 'category_language'),
					$db->quoteName('c.published'),
					$db->quoteName('c.published', 'parents_published'),
				]
			)
			->from($db->quoteName('#__agadvents_details', 'a'))
			->join('LEFT', $db->quoteName('#__categories', 'c'), $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'));

		// Filter by a single or group of agadvents.
		$agadventId = $this->getState('filter.agadvent_id');

		if (is_numeric($agadventId))
		{
			$agadventId = (int) $agadventId;
			$type = $this->getState('filter.agadvent_id.include', true) ? ' = ' : ' <> ';
			$query->where($db->quoteName('a.id') . $type . ':agadventId')
				->bind(':agadventId', $agadventId, ParameterType::INTEGER);
		}
		elseif (is_array($agadventId))
		{
			$agadventId = ArrayHelper::toInteger($agadventId);

			if ($this->getState('filter.agadvent_id.include', true))
			{
				$query->whereIn($db->quoteName('a.id'), $agadventId);
			}
			else
			{
				$query->whereNotIn($db->quoteName('a.id'), $agadventId);
			}
		}

		// Filter by a single or group of categories
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$type = $this->getState('filter.category_id.include', true) ? ' = ' : ' <> ';

			// Add subcategory check
			$includeSubcategories = $this->getState('filter.subcategories', false);

			if ($includeSubcategories)
			{
				$categoryId = (int) $categoryId;
				$levels     = (int) $this->getState('filter.max_category_levels', 1);

				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true)
					->select($db->quoteName('sub.id'))
					->from($db->quoteName('#__categories', 'sub'))
					->join(
						'INNER',
						$db->quoteName('#__categories', 'this'),
						$db->quoteName('sub.lft') . ' > ' . $db->quoteName('this.lft')
							. ' AND ' . $db->quoteName('sub.rgt') . ' < ' . $db->quoteName('this.rgt')
					)
					->where($db->quoteName('this.id') . ' = :subCategoryId');

				$query->bind(':subCategoryId', $categoryId, ParameterType::INTEGER);

				if ($levels >= 0)
				{
					$subQuery->where($db->quoteName('sub.level') . ' <= ' . $db->quoteName('this.level') . ' + :levels');
					$query->bind(':levels', $levels, ParameterType::INTEGER);
				}

				// Add the subquery to the main query
				$query->where(
					'(' . $db->quoteName('a.catid') . $type . ':categoryId OR ' . $db->quoteName('a.catid') . ' IN (' . (string) $subQuery . '))'
				);
				$query->bind(':categoryId', $categoryId, ParameterType::INTEGER);
			}
			else
			{
				$query->where($db->quoteName('a.catid') . $type . ':categoryId');
				$query->bind(':categoryId', $categoryId, ParameterType::INTEGER);
			}
		}
		elseif (is_array($categoryId) && (count($categoryId) > 0))
		{
			$categoryId = ArrayHelper::toInteger($categoryId);

			if (!empty($categoryId))
			{
				if ($this->getState('filter.category_id.include', true))
				{
					$query->whereIn($db->quoteName('a.catid'), $categoryId);
				}
				else
				{
					$query->whereNotIn($db->quoteName('a.catid'), $categoryId);
				}
			}
		}

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->whereIn($db->quoteName('a.language'), [Factory::getLanguage()->getTag(), '*'], ParameterType::STRING);
		}

		// Filter by a single or group of tags.
		$tagId = $this->getState('filter.tag');

		if (is_array($tagId) && count($tagId) === 1)
		{
			$tagId = current($tagId);
		}

		if (is_array($tagId))
		{
			$tagId = ArrayHelper::toInteger($tagId);

			if ($tagId)
			{
				$subQuery = $db->getQuery(true)
					->select('DISTINCT ' . $db->quoteName('agadvents_item_id'))
					->from($db->quoteName('#__agadvents_detailsitem_tag_map'))
					->where(
						[
							$db->quoteName('tag_id') . ' IN (' . implode(',', $query->bindArray($tagId)) . ')',
							$db->quoteName('type_alias') . ' = ' . $db->quote('com_agadvents.agadvent'),
						]
					);

				$query->join(
					'INNER',
					'(' . (string) $subQuery . ') AS ' . $db->quoteName('tagmap'),
					$db->quoteName('tagmap.agadvents_item_id') . ' = ' . $db->quoteName('a.id')
				);
			}
		}
		elseif ($tagId = (int) $tagId)
		{
			$query->join(
				'INNER',
				$db->quoteName('#__agadvents_detailsitem_tag_map', 'tagmap'),
				$db->quoteName('tagmap.agadvents_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_agadvents.agadvent')
			)
				->where($db->quoteName('tagmap.tag_id') . ' = :tagId')
				->bind(':tagId', $tagId, ParameterType::INTEGER);
		}

		// Add the list ordering clause.
		$query->order(
			$db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC'))
		);

		return $query;
	}

	/**
	 * Method to get a list of agadvents.
	 *
	 * Overridden to inject convert the attribs field into a Registry object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItems()
	{
		$items  = parent::getItems();

		return $items;
	}
}
