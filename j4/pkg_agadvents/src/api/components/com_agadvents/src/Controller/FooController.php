<?php
/**
 * @package     Joomla.API
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AgadventNamespace\Component\Agadvents\Api\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/**
 * The agadvents controller
 *
 * @since  __BUMP_VERSION__
 */
class AgadventController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	protected $contentType = 'agadvents';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	protected $default_view = 'agadvents';

	/**
	 * Method to save a record.
	 *
	 * @param   integer  $recordKey  The primary key of the item (if exists)
	 *
	 * @return  integer  The record ID on success, false on failure
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function save($recordKey = null)
	{
		$data = (array) json_decode($this->input->json->getRaw(), true);

		foreach (FieldsHelper::getFields('com_agadvents.agadvent') as $field) {
			if (isset($data[$field->name])) {
				!isset($data['com_fields']) && $data['com_fields'] = [];

				$data['com_fields'][$field->name] = $data[$field->name];
				unset($data[$field->name]);
			}
		}

		$this->input->set('data', $data);

		return parent::save($recordKey);
	}
}
