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

	public function __construct(&$subject, $config = array()) {
		// https://github.com/dgrammatiko/sloth-pkg/blob/main/plg_sampledata/sloth.php
		if (file_exists(__DIR__ . '/data.json')) {
			try {
				$json = file_get_contents(__DIR__ . '/data.json');
				$data = json_decode($json);
				$this->pluginData = $data->plugin;
				$this->stepsData = $data->steps;
		  	} catch (\Exception $e) {
				new \Exception('Plugin doesn\'t have valid data');
		  	}
		}
	
		parent::__construct($subject, $config);
	
		$this->user   = Factory::getUser();
	}

	/**
	 * Get an overview of the proposed sampledata.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onSampledataGetOverview()
	{
		if (!Factory::getUser()->authorise('core.create', 'com_agadvents'))
		{
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
		$categoryModel = $this->app->bootComponent('com_categories')
			->getMVCFactory()->createModel('Category', 'Administrator');

		$category = [
			'title'           => 'Weihnachten ' . date('Y'),
			'parent_id'       => 1,
			'id'              => 0,
			'published'       => 1,
			'access'          => 1,
			'created_user_id' => $this->user->id,
			'extension'       => 'com_agadvents',
			'level'           => 1,
			'alias'           => 'weihnachten'. date('Y'),
			'associations'    => array(),
			'description'     => '',
			'language'        => '*',
			'params'          => '{}'
		];

		try
		{
			if (!$categoryModel->save($category))
			{
				throw new Exception($categoryModel->getError());
			}
		}
		catch (Exception $e)
		{
			$response = new stdClass;
			$response->success = false;
			$response->message = Text::sprintf('PLG_SAMPLEDATA_AGADVENTS_STEP_FAILED', 1, $e->getMessage());

			return $response;
		}

		// Get ID from category we just added
		$catId = $categoryModel->getItem()->id;


		$mvcFactory = $this->app->bootComponent('com_agadvents')->getMVCFactory();
		$adventsModel = $mvcFactory->createModel('Agadvent', 'Administrator', ['ignore_request' => true]);

		for ($i = 1; $i <= 24; $i++)
		{
			$adventsModel = $mvcFactory->createModel('Agadvent', 'Administrator', ['ignore_request' => true]);

			$item = [
				'name'  => 'Tag '. $i,
				'alias'    => 'taga'. $i,
				'catid'    => $catId,
				'fulltext' => Text::_('PLG_SAMPLEDATA_AGADVENTS_FULL_TEXT') . $i,
				'fulltext_no' => Text::_('PLG_SAMPLEDATA_AGADVENTS_FULL_TEXT_NO') . $i,
				'number' => $i,
				'published' => 1,
				'cords' => '100,100,100',
				'start_date' => '2021-12-' . sprintf("%02d", $i) . ' 00:00:01',
				'end_date' => '2021-12-' . sprintf("%02d", $i) . ' 23:59:59',
				'params'  => '{}'
			];

			try
			{
				if (!$adventsModel->save($item))
				{
					throw new Exception($adventsModel->getError());
				}
			}
			catch (Exception $e)
			{
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




	/**
	 * Final step to show completion of sampledata.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	private function copyFiles() {
		// https://github.com/dgrammatiko/sloth-pkg/blob/main/plg_sampledata/sloth.php
		$zip = new \ZipArchive;
		if (is_file(__DIR__ . '/zips/images.zip')) {
		  if ($zip->open(__DIR__ . '/zips/images.zip') === TRUE) {
			$zip->extractTo(JPATH_ROOT . '/images');
			$zip->close();
		  } else {
			$msg[] = 'images.zip';
		  }
		}
		if (is_file(__DIR__ . '/zips/cached-resp-images.zip')) {
		  if ($zip->open(__DIR__ . '/zips/cached-resp-images.zip') === TRUE) {
			$zip->extractTo(JPATH_ROOT . '/media');
			$zip->close();
		  } else {
			$msg[] = $msg[] = 'cached-resp-images.zip';
		  }
		}
	
		if (count($msg)) {
		  self::message(false, Text::_('JERROR'), 0, implode(', ', $msg));
		}
		return self::message(true,  Text::_($this->pluginData->strings->FILES_COPIED, 1, 'x'));
	  }	
}
