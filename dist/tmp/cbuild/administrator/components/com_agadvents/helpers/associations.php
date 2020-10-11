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

use Joomla\Utilities\ArrayHelper;

JTable::addIncludePath(__DIR__ . '/../tables');

/**
 * Content associations helper.
 *
 * @since  2.0.5
 */
class AgadventsAssociationsHelper extends JAssociationExtensionHelper
{
	/**
	 * The extension name
	 *
	 * @var     array   $extension
	 *
	 * @since   2.0.5
	 */
	protected $extension = 'com_agadvents';

	/**
	 * Array of item types
	 *
	 * @var     array   $itemTypes
	 *
	 * @since   2.0.5
	 */
	protected $itemTypes = array('agadvent', 'category');

	/**
	 * Has the extension association support
	 *
	 * @var     boolean   $associationsSupport
	 *
	 * @since   2.0.5
	 */
	protected $associationsSupport = true;

	/**
	 * Get the associated items for an item
	 *
	 * @param   string  $typeName  The item type
	 * @param   int     $id        The id of item for which we need the associated items
	 *
	 * @return  array
	 *
	 * @since   2.0.5
	 */
	public function getAssociations($typeName, $id)
	{
		$type = $this->getType($typeName);

		$context    = $this->extension . '.item';
		$catidField = 'catid';

		if ($typeName === 'category')
		{
			$context    = 'com_categories.item';
			$catidField = '';
		}

		// Get the associations.
		$associations = JLanguageAssociations::getAssociations(
			$this->extension,
			$type['tables']['a'],
			$context,
			$id,
			'id',
			'alias',
			$catidField
		);

		return $associations;
	}

	/**
	 * Get item information
	 *
	 * @param   string  $typeName  The item type
	 * @param   int     $id        The id of item for which we need the associated items
	 *
	 * @return  JTable|null
	 *
	 * @since   2.0.5
	 */
	public function getItem($typeName, $id)
	{
		if (empty($id))
		{
			return null;
		}

		$table = null;

		switch ($typeName)
		{
			case 'agadvent':
				$table = JTable::getInstance('Agadvent', 'AgadventsTable');
				break;

			case 'category':
				$table = JTable::getInstance('Category');
				break;
		}

		if (empty($table))
		{
			return null;
		}

		$table->load($id);

		return $table;
	}

	/**
	 * Get information about the type
	 *
	 * @param   string  $typeName  The item type
	 *
	 * @return  array  Array of item types
	 *
	 * @since   2.0.5
	 */
	public function getType($typeName = '')
	{
		$fields  = $this->getFieldsTemplate();
		$tables  = array();
		$joins   = array();
		$support = $this->getSupportTemplate();
		$title   = '';

		if (in_array($typeName, $this->itemTypes))
		{
			switch ($typeName)
			{
				case 'agadvent':

					$support['state'] = true;
					$support['acl'] = true;
					$support['category'] = true;
					$support['save2copy'] = true;

					$tables = array(
						'a' => '#__agadvents'
					);

					$title = 'agadvent';
					break;

				case 'category':
					$fields['created_user_id'] = 'a.created_user_id';
					$fields['ordering']        = 'a.lft';
					$fields['level']           = 'a.level';
					$fields['catid']           = '';
					$fields['state']           = 'a.published';

					$support['state']    = true;
					$support['acl']      = true;
					$support['checkout'] = true;
					$support['level'] = true;

					$tables = array(
						'a' => '#__categories'
					);

					$title = 'category';
					break;
			}
		}

		return array(
			'fields'  => $fields,
			'support' => $support,
			'tables'  => $tables,
			'joins'   => $joins,
			'title'   => $title
		);
	}
}
