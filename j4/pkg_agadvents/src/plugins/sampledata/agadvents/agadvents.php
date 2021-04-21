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
	
		$this->year   = date('Y');
		$this->month  = date('m');
		$this->day    = date('d');
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
	 * First step to enter the sampledata. Content.
	 *
	 * @return  array or void  Will be converted into the JSON response to the module.
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function onAjaxSampledataApplyStep1()
	{
		
		$currentData = $this->stepsData->{1}->data;
		//https://github.com/dgrammatiko/sloth-pkg/blob/main/plg_sampledata/sloth.php
		$currentData = $this->stepsData->{1}->data;
		$teststring = "";
		foreach ($currentData as $num => $data) {
			$teststring = $teststring . " - " . $data->path;
		}
		$response = new stdClass;
		$response->success = true;
		$response->message = $teststring . Text::_('PLG_SAMPLEDATA_AGADVENTS_STEP1_SUCCESS');

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
