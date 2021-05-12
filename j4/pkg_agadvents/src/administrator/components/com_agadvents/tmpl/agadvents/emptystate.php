<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_advents
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_AGADVENTS',
	'formURL' => 'index.php?option=com_agadvents',
	'helpURL' => 'https://github.com/astridx/pkg_agadvents/blob/master/j4/pkg_agadvents/README.md',
	'icon' => 'icon-copy',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_agadvents') || count($user->getAuthorisedCategories('com_agadvents', 'core.create')) > 0) {
	$displayData['createURL'] = 'index.php?option=com_agadvents&task=agadvent.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
