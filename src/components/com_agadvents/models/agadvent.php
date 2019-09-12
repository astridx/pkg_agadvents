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

use Joomla\Utilities\ArrayHelper;

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * Agadvents Component Model for a Agadvent record
 *
 * @since  1.5
 */
class AgadventsModelAgadvent extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var  string
	 */
	protected $_context = 'com_agadvents.agadvent';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		// Load the object state.
		$id = $app->input->getInt('id');
		$this->setState('agadvent.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('agadvent.id');
			}

			// Get a level row instance.
			$table = JTable::getInstance('Agadvent', 'AgadventsTable');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
			elseif ($error = $table->getError())
			{
				$this->setError($error);
			}
		}

		$date = JFactory::getDate();
		$up = new JDate($this->_item->publish_up);
		$down = new JDate($this->_item->publish_down);

		if (($date > $up || $this->_item->publish_up == '0000-00-00 00:00:00') && ($date < $down || $this->_item->publish_down == '0000-00-00 00:00:00') && $this->_item->state == 1)
		{
			return $this->_item;
		}
		else
		{
			return JError::raiseError(404, 'Item not found.');
		}

	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Agadvent', $prefix = 'AgadventsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to increment the hit counter for the agadvent
	 *
	 * @param   integer  $id  Optional ID of the agadvent.
	 *
	 * @return  boolean  True on success
	 */
	public function hit($id = null)
	{
		if (empty($id))
		{
			$id = $this->getState('agadvent.id');
		}

		return $this->getTable('Agadvent', 'AgadventsTable')->hit($id);
	}
}
