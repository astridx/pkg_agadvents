<?php
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

$input = $app->input;

// Prep for Normal or Dynamic Modes
$mode = $params->get('mode', 'image');
$test = $params->get('test', 1);
$idbase = $params->get('catid');

$cacheid = md5(serialize(array ($idbase, $module->module, $module->id)));

$cacheparams = new \stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = AgadventHelper::class;
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;

$list = ModuleHelper::moduleCache($module, $params, $cacheparams);

require ModuleHelper::getLayoutPath('mod_agadvent', $params->get('layout', 'default'));
