<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$canDo   = ContentHelper::getActions('com_agadvents', 'category', $this->item->catid);
$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == Factory::getUser()->id);
$tparams = $this->item->params;
?>

<h2>
<?php //echo Text::_('COM_AGADVENTS_NAME') . $this->item->name; ?>
</h2>

<?php
/*echo $this->item->published == 1;
echo 'kk';
echo   strtotime($this->item->publish_up) > strtotime(Factory::getDate()); echo 'kk';
echo   !is_null($this->item->publish_down) ;echo 'kk';
echo   strtotime($this->item->publish_down) < strtotime(Factory::getDate());
echo '--today: ';
echo strtotime(Factory::getDate());echo '-- down: ';
echo strtotime($this->item->publish_down);
echo 'kk';
echo Factory::getDate();echo '-- sdkjfdlsjfldsk: ';
echo $this->item->publish_down;echo '-- sdkjfdlsjfldsk: ';*/
?>

<?php echo $this->item->fulltext; ?>

<?php if ($canEdit) : ?>
	<div class="icons">
		<div class="float-end">
			<div>
				<?php echo HTMLHelper::_('agadventicon.edit', $this->item, $tparams); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;
