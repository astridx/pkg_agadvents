<?php
/**
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_agadvent
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use AgadventNamespace\Module\Agadvent\Site\Helper\AgadventHelper;

$testtext  = AgadventHelper::getText();

$mode = $params->get('mode');
$test = $params->get('test');
$category = $params->get('category');

require ModuleHelper::getLayoutPath('mod_agadvent', $params->get('layout', 'default'));
