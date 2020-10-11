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
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  1.0
 */
class Com_AgadventsInstallerScript
{
	/**
	 * Function to perform changes during install
	 *
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_agadvents');

		return true;
	}

	/**
	 * This method is called after a component is uninstalled.
	 *
	 * @param   \stdClass  $parent  Parent object calling this method.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function uninstall($parent)
	{
		echo '<p>' . JText::_('COM_AGADVENT_UNINSTALL_TEXT') . '</p>';

		return true;
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   string                      $type    The action being performed
	 * @param   JInstallerAdapterComponent  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function postflight($type, $parent)
	{
		$category = JTable::getInstance('Category');

		// Check if the Uncategorised category exists before adding it
		if (!$category->load(array('extension' => 'com_agadvents', 'title' => 'Uncategorised')))
		{
			$catid = $this->createCategorie('Uncategorised', '*');

			for ($i = 1; $i <= 24; $i++)
			{
				$title = $i . '. Dezember';
				$alias = $i . '-dezember' . uniqid();
				$this->createAdvents($title, $alias, $catid, $i);
			}
		}

		if (!$category->load(array('extension' => 'com_agadvents', 'title' => '2020')))
		{
			$catid = $this->createCategorie('2020', '*');

			for ($i = 1; $i <= 24; $i++)
			{
				$title = $i . '. Dezember 2020';
				$alias = $i . '-dezember2020' . uniqid();
				$this->createAdvents($title, $alias, $catid, $i);
			}
		}

		if (!$category->load(array('extension' => 'com_agadvents', 'title' => '2019')))
		{
			$catid = $this->createCategorie('2019', '*');

			for ($i = 1; $i <= 24; $i++)
			{
				$title = $i . '. Dezember 2019';
				$alias = $i . '-dezember2019' . uniqid();
				$this->createAdvents($title, $alias, $catid, $i);
			}
		}

		$this->copyFiles();
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   String  $name  The name
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 */
	public function createCategorie($name, $lang)
	{
		$category = JTable::getInstance('Category');

		$category->extension = 'com_agadvents';
		$category->title = $name;
		$category->description = $name;
		$category->published = 1;
		$category->access = 1;
		$category->params = '{"category_layout":"","image":"images\/com_agadvents\/sample_images\/advent_grau.png","image_alt":""}';
		$category->metadata = '{"author":"","robots":""}';
		$category->metadesc = '';
		$category->metakey = '';
		$category->language = $lang;
		$category->checked_out_time = JFactory::getDbo()->getNullDate();
		$category->version = 1;
		$category->hits = 0;
		$category->modified_user_id = 0;
		$category->checked_out = 0;

		// Set the location in the tree
		$category->setLocation(1, 'last-child');

		// Check to make sure our data is valid
		if (!$category->check())
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_AGADVENTS_ERROR_INSTALL_CATEGORY', $category->getError()));

			return;
		}

		// Now store the category
		if (!$category->store(true))
		{
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_AGADVENTS_ERROR_INSTALL_CATEGORY', $category->getError()));

			return;
		}

		// Build the path for our category
		$category->rebuildPath($category->id);

		return $category->id;
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   string   $title   The type being performed
	 * @param   string   $alias   The alias being performed
	 * @param   integer  $catid   The catid being performed
	 * @param   integer  $number  The number being performed
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function createAdvents($title, $alias, $catid, $number)
	{
		$agadvent = new stdClass;

		$agadvent->catid = $catid;
		$agadvent->title = $title;
		$agadvent->alias = $title;
		$agadvent->content = $title . ' here comes your activ content';
		$agadvent->content_no = $title . 'here comes your content if not activ';

		$agadvent->number = $number;

		switch ($number)
		{
			case 1:
				$agadvent->cords = '299,399,392,493';
				$agadvent->cords_hidden = '299,399,93,94';
				break;
			case 2:
				$agadvent->cords = '736,135,806,214';
				$agadvent->cords_hidden = '736,135,70,79';
				break;
			case 3:
				$agadvent->cords = '914,899,987,998';
				$agadvent->cords_hidden = '914,899,73,99';
				break;
			case 4:
				$agadvent->cords = '626,417,711,524';
				$agadvent->cords_hidden = '626,417,85,107';
				break;
			case 5:
				$agadvent->cords = '77,263,153,329';
				$agadvent->cords_hidden = '77,263,76,66';
				break;
			case 6:
				$agadvent->cords = '952,418,1053,530';
				$agadvent->cords_hidden = '952,418,101,112';
				break;
			case 7:
				$agadvent->cords = '499,748,579,832';
				$agadvent->cords_hidden = '499,748,80,84';
				break;
			case 8:
				$agadvent->cords = '442,334,544,432';
				$agadvent->cords_hidden = '442,334,102,98';
				break;
			case 9:
				$agadvent->cords = '62,786,190,894';
				$agadvent->cords_hidden = '62,786,128,108';
				break;
			case 10:
				$agadvent->cords = '129,444,226,521';
				$agadvent->cords_hidden = '129,444,97,77';
				break;
			case 11:
				$agadvent->cords = '807,306,903,386';
				$agadvent->cords_hidden = '807,306,96,80';
				break;
			case 12:
				$agadvent->cords = '524,916,641,1030';
				$agadvent->cords_hidden = '524,916,117,114';
				break;
			case 13:
				$agadvent->cords = '414,139,516,214';
				$agadvent->cords_hidden = '414,139,102,75';
				break;
			case 14:
				$agadvent->cords = '835,645,933,720';
				$agadvent->cords_hidden = '835,645,98,75';
				break;
			case 15:
				$agadvent->cords = '177,645,277,716';
				$agadvent->cords_hidden = '177,645,100,71';
				break;
			case 16:
				$agadvent->cords = '569,210,662,277';
				$agadvent->cords_hidden = '569,210,93,67';
				break;
			case 17:
				$agadvent->cords = '648,663,767,769';
				$agadvent->cords_hidden = '648,663,119,106';
				break;
			case 18:
				$agadvent->cords = '327,786,452,905';
				$agadvent->cords_hidden = '327,786,125,119';
				break;
			case 19:
				$agadvent->cords = '225,168,359,302';
				$agadvent->cords_hidden = '225,168,134,134';
				break;
			case 20:
				$agadvent->cords = '969,743,1079,809';
				$agadvent->cords_hidden = '969,743,110,66';
				break;
			case 21:
				$agadvent->cords = '207,949,302,1025';
				$agadvent->cords_hidden = '207,949,95,76';
				break;
			case 22:
				$agadvent->cords = '769,512,875,575';
				$agadvent->cords_hidden = '769,512,106,63';
				break;
			case 23:
				$agadvent->cords = '693,869,785,935';
				$agadvent->cords_hidden = '693,869,92,66';
				break;
			case 24:
				$agadvent->cords = '361,549,492,699';
				$agadvent->cords_hidden = '361,549,131,150';
				break;
		}

		if ($number < 10)
		{
			$number = '0' . $number;
			$agadvent->begin = '2020-12-' . $number . ' 00:01:01';
			$agadvent->ende = '2020-12-' . $number . ' 23:59:59';
		}
		else
		{
			$agadvent->begin = '2020-12-' . $number . ' 00:01:01';
			$agadvent->ende = '2020-12-' . $number . ' 23:59:59';
		}

		$agadvent->asset_id = 0;
		$agadvent->url = '';
		$agadvent->description = $title;
		$agadvent->hits = 0;
		$agadvent->state = 1;
		$agadvent->checked_out = 0;
		$agadvent->checked_out_time = '2020-04-11 00:00:00';
		$agadvent->ordering = 0;
		$agadvent->access = 1;
		$agadvent->params = '';
		$agadvent->language = '*';
		$agadvent->created = '0000-00-00 00:00:00';
		$agadvent->created_by = 0;
		$agadvent->created_by_alias = '';
		$agadvent->modified = '0000-00-00 00:00:00';
		$agadvent->modified_by = 0;
		$agadvent->metakey = '';
		$agadvent->metadesc = '';
		$agadvent->metadata = '';
		$agadvent->featured = 0;
		$agadvent->xreference = '';
		$agadvent->version = 0;
		$agadvent->images = '';

		// Insert the object into the user agadvent table.
		$result = JFactory::getDbo()->insertObject('#__agadvents', $agadvent);

		return true;
	}

	/**
	 * Method to rcopy files.
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function copyFiles()
	{
		// Creating a folder
		$mode = 0755;
		$path = JPATH_SITE . "/images/com_agadvents/";
		JFolder::create($path, $mode);

		$path = JPATH_SITE . "/images/com_agadvents/sample_images/";
		JFolder::create($path, $mode);

		$pathsearch = JPATH_SITE . "/media/com_agadvents/images/";

		// Now we read all png files and put them in an array.
		$png_files = JFolder::files($pathsearch, '.png');

		foreach ($png_files as $file)
		{
			if (!file_exists($path . $file))
			{
				JFile::copy($pathsearch . $file, $path . $file);
			}
		}

		return true;
	}
}
