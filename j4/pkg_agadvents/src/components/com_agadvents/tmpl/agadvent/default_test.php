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

<?php echo Text::_('COM_AGADVENTS_TEST') . $this->item->name; ?>
<h2>
<?php echo $this->item->name; ?>
</h2>

<h3>
<?php echo Text::_('COM_AGADVENTS_AKTIV') ?>
</h3>
<?php echo $this->item->fulltext; ?>
<h3>
<?php echo Text::_('COM_AGADVENTS_NOT_AKTIV') ?>
</h3>
<?php echo $this->item->fulltext_no; ?>

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
