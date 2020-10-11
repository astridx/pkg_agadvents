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
 * System plugin for Joomla Agadvents.
 *
 * @since  3.7
 */
class PlgSystemAgadvents extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Supported Extensions
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $supportedExtensions = array(
		'mod_stats',
		'mod_stats_admin',
	);

	/**
	 * Method to add statistics information to Administrator control panel.
	 *
	 * @param   string  $extension  The extension requesting information.
	 *
	 * @return  array  containing statistical information.
	 *
	 * @since   3.7
	 */
	public function onGetStats($extension)
	{
		if (!in_array($extension, $this->supportedExtensions))
		{
			return array();
		}

		if (!JComponentHelper::isEnabled('com_agadvents'))
		{
			return array();
		}

		$db = JFactory::getDbo();
		$query_count = $db->getQuery(true)
			->select('COUNT(id) AS count_links')
			->from('#__agadvents')
			->where('state = 1');
		$agAdvents_counts = $db->setQuery($query_count)->loadResult();

		$query_hits = $db->getQuery(true)
			->select('SUM(hits) AS sum_hits')
			->from('#__agadvents')
			->where('state = 1');
		$agAdvents_hits = $db->setQuery($query_hits)->loadResult();

		if (!$agAdvents_counts)
		{
			return array();
		}

		return array(array(
			'title' => JText::_('PLG_SYSTEM_AGADVENTS_STATISTICS'),
			'icon'  => 'out-2',
			'data'  => $agAdvents_counts
		),
		array(
			'title' => JText::_('PLG_SYSTEM_AGADVENTS_STATISTICS_HITS'),
			'icon'  => 'eye',
			'data'  => $agAdvents_hits
		)
		);
	}
}
