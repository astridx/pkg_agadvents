<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Sampledata.Agadvents
 *
 * @copyright   (C) astrid-guenther.de>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Extension\ExtensionHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\Database\ParameterType;

/**
 * Sampledata - Agadvents Plugin
 *
 * @since  __BUMP_VERSION__
 */
class PlgSampledataAgadvents extends CMSPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $db;

	/**
	 * Application object
	 *
	 * @var    JApplicationCms
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $app;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Holds the data
	 *
	 * @var    string
	 *
	 * @since  __BUMP_VERSION__
	 */
	protected $stepsData;

	/**
	 * Get an overview of the proposed sampledata.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onSampledataGetOverview()
	{
		if (!Factory::getUser()->authorise('core.create', 'com_agadvents')) {
			return;
		}

		$data = new stdClass;
		$data->name = $this->_name;
		$data->title = Text::_('PLG_SAMPLEDATA_AGADVENTS_OVERVIEW_TITLE');
		$data->description = Text::_('PLG_SAMPLEDATA_AGADVENTS_OVERVIEW_DESC');
		$data->icon = 'star';
		$data->steps = 4;

		return $data;
	}

	/**
	 * First step to enter the sampledata. Category and Items.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep1()
	{
		$user = Factory::getUser();
		$categoryModel = $this->app->bootComponent('com_categories')
			->getMVCFactory()->createModel('Category', 'Administrator');

		$category = [
			'title' => 'Weihnachten ' . date('Y'),
			'parent_id' => 1,
			'id' => 0,
			'published' => 1,
			'access' => 1,
			'created_user_id' => $user->id,
			'extension' => 'com_agadvents',
			'level' => 1,
			'alias' => 'weihnachten'. date('Y'),
			'associations' => [],
			'description' => '',
			'language' => '*',
			'params'       => [
				'image'=> 'plugins/sampledata/agadvents/images/calendar.jpg',
				'image_alt' => Text::_('PLG_SAMPLEDATA_AGADVENTS_CATEGORY_INTROIMAGE_ALT')
			],
		];

		try {
			if (!$categoryModel->save($category)) {
				throw new Exception($categoryModel->getError());
			}
		} catch (Exception $e) {
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGADVENTS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId = $categoryModel->getItem()->id;


		$mvcFactory = $this->app->bootComponent('com_agadvents')->getMVCFactory();
		$adventsModel = $mvcFactory->createModel('Agadvent', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 24; $i++) {
			$adventsModel = $mvcFactory->createModel('Agadvent', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => 'Tag '. $i,
				'alias'    => 'tag'. $i,
				'catid'    => $catId,
				'fulltext' => Text::_('PLG_SAMPLEDATA_AGADVENTS_FULL_TEXT') . $i,
				'fulltext_no' => Text::_('PLG_SAMPLEDATA_AGADVENTS_FULL_TEXT_NO') . $i,
				'published' => 1,
				'cords' => '',
				'cordsimagemap' => '',
				'publish_up' => '2021-12-' . sprintf("%02d", $i) . ' 00:00:01',
				'publish_down' => '2021-12-' . sprintf("%02d", $i) . ' 23:59:59',
				'params'  => '{}'
			];

			switch ($i) {
				case 1:
					$item['cords'] = ' 1236,22  1036,30  1052,234  1236,234 ';
					$item['cordsimagemap'] = ' 22,44  30,244  234,228  234,44 ';
					break;
				case 2:
					$item['cords'] = ' 1076,258  1068,434  1208,438 ';
					$item['cordsimagemap'] = ' 258,204  434,212  438,72 ';
					break;
				case 3:
					$item['cords'] = ' 1196,474  1084,486  1064,658  1216,638 ';
					$item['cordsimagemap'] = ' 474,84  486,196  658,216  638,64 ';
					break;
				case 4:
					$item['cords'] = ' 1068,690  1064,854  1212,854 ';
					$item['cordsimagemap'] = ' 690,212  854,216  854,68 ';
					break;
				case 5:
					$item['cords'] = ' 992,54  876,66  852,226  1024,206 ';
					$item['cordsimagemap'] = ' 54,288  66,404  226,428  206,256 ';
					break;
				case 6:
					$item['cords'] = ' 1008,274  856,434  1020,434 ';
					$item['cordsimagemap'] = ' 274,272  434,424  434,260 ';
					break;
				case 7:
					$item['cords'] = ' 1020,482  856,630  1008,646 ';
					$item['cordsimagemap'] = ' 482,260  630,424  646,272 ';
					break;
				case 8:
					$item['cords'] = ' 1000,690  864,846  1024,858 ';
					$item['cordsimagemap'] = ' 690,280  846,416  858,256 ';
					break;
				case 9:
					$item['cords'] = ' 812,46  668,46  660,210  812,210 ';
					$item['cordsimagemap'] = ' 46,468  46,612  210,620  210,468 ';
					break;
				case 10:
					$item['cords'] = ' 808,250  656,274  664,426  812,426 ';
					$item['cordsimagemap'] = ' 250,472  274,624  426,616  426,468 ';
					break;
				case 11:
					$item['cords'] = ' 804,466  668,478  640,658  796,630 ';
					$item['cordsimagemap'] = ' 466,476  478,612  658,640  630,484 ';
					break;
				case 12:
					$item['cords'] = ' 816,678  664,694  652,858  816,842 ';
					$item['cordsimagemap'] = ' 678,464  694,616  858,628  842,464 ';
					break;
				case 13:
					$item['cords'] = ' 624,54  444,58  448,214  628,222 ';
					$item['cordsimagemap'] = ' 54,656  58,836  214,832  222,652 ';
					break;
				case 14:
					$item['cords'] = ' 604,258  456,262  448,434  592,418 ';
					$item['cordsimagemap'] = ' 258,676  262,824  434,832  418,688 ';
					break;
				case 15:
					$item['cords'] = ' 440,478  452,638  616,658 ';
					$item['cordsimagemap'] = ' 478,840  638,828  658,664 ';
					break;
				case 16:
					$item['cords'] = ' 604,698  464,854  616,854 ';
					$item['cordsimagemap'] = ' 698,676  854,816  854,664 ';
					break;
				case 17:
					$item['cords'] = ' 404,46  268,58  268,214  420,214 ';
					$item['cordsimagemap'] = ' 46,876  58,1012  214,1012  214,860 ';
					break;
				case 18:
					$item['cords'] = ' 424,262  272,262  260,422  432,418 ';
					$item['cordsimagemap'] = ' 262,856  262,1008  422,1020  418,848 ';
					break;
				case 19:
					$item['cords'] = ' 252,474  256,638  404,634 ';
					$item['cordsimagemap'] = ' 474,1028  638,1024  634,876 ';
					break;
				case 20:
					$item['cords'] = ' 416,682  256,690  256,854  404,846 ';
					$item['cordsimagemap'] = ' 682,864  690,1024  854,1024  846,876 ';
					break;
				case 21:
					$item['cords'] = ' 212,42  52,50  40,222  212,214 ';
					$item['cordsimagemap'] = ' 42,1068  50,1228  222,1240  214,1068 ';
					break;
				case 22:
					$item['cords'] = ' 208,258  64,258  52,438  220,426 ';
					$item['cordsimagemap'] = ' 258,1072  258,1216  438,1228  426,1060 ';
					break;
				case 23:
					$item['cords'] = ' 224,470  64,470  44,630  216,630 ';
					$item['cordsimagemap'] = ' 470,1056  470,1216  630,1236  630,1064 ';
					break;
				case 24:
					$item['cords'] = ' 212,678  72,686  60,854  208,846 ';
					$item['cordsimagemap'] = ' 678,1068  686,1208  854,1220  846,1072 ';
					break;
			}

		
			try {
				if (!$adventsModel->save($item)) {
					throw new Exception($adventsModel->getError());
				}
			} catch (Exception $e) {
				$response = new stdClass;
				$response->success = false;
				$response->message = Text::sprintf('PLG_SAMPLEDATA_AGADVENTS_STEP_FAILED', 1, $e->getMessage());
			
				return $response;
			}
		}

		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGADVENTS_STEP1_SUCCESS');

		return $response;
	}

	/**
	 * Second step to enter the sampledata. Menus.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep2()
	{
		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGADVENTS_STEP2_SUCCESS');

		return $response;
	}

	/**
	 * Third step to enter the sampledata. Modules.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep3()
	{
		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGADVENTS_STEP3_SUCCESS');

		return $response;
	}

	/**
	 * Final step to show completion of sampledata.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep4()
	{
		$response = new stdClass;
		$response->success = true;
		$response->message = Text::_('PLG_SAMPLEDATA_AGADVENTS_STEP4_SUCCESS');

		return $response;
	}
}
