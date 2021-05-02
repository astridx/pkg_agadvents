<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$input = $app->input;

$assoc = Associations::isEnabled();

$this->ignore_fieldsets = ['item_associations'];
$this->useCoreUI = true;

$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_agadvents.leaflet.css')
	->useStyle('com_agadvents.leaflet.draw.css')
	->useScript('keepalive')
	->useScript('form.validate')	
	->useScript('com_agadvents.leaflet')
	->useScript('com_agadvents.leaflet.draw')
	->useScript('com_agadvents.cords')
	->useScript('com_agadvents.validatecords');

$tmpl = $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>
<form action="<?php echo Route::_('index.php?option=com_agadvents&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="agadvent-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div>
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'details']); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', empty($this->item->id) ? Text::_('COM_AGADVENTS_NEW_AGADVENT') : Text::_('COM_AGADVENTS_EDIT_AGADVENT')); ?>
		<div class="row">
			<div class="col-lg-9">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<?php echo Text::_('COM_AGADVENTS_TMPL_EDIT_FULLTEXT'); ?>
								<?php echo $this->getForm()->renderField('fulltext'); ?>
							</div>
							<hr>
							<div class="col-lg-12">
								<small><?php echo Text::_('COM_AGADVENTS_TMPL_EDIT_CORDS'); ?></small>
								<?php echo $this->getForm()->renderField('cords'); ?>
								<?php echo $this->getForm()->renderField('cordsimagemap'); ?>
								<div>
									<div 
										id="cordsmap" 
										style="height: 400px;"
										data-catimage=<?php echo $this->catimage; ?>
									>
									</div>
								</div>
							</div>
							<hr>
							<hr>
							<div class="col-lg-12">
								<small><?php echo Text::_('COM_AGADVENTS_TMPL_EDIT_SHOWINACTIVE'); ?></small>
								<?php echo $this->getForm()->renderField('showinactive'); ?>
								<?php echo $this->getForm()->renderField('fulltext_no'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="card">
					<div class="card-body">
						<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		
		<?php if ($assoc) : ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php endif; ?>
		
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
		<div class="row">
			<div class="col-md-6">
				<fieldset id="fieldset-publishingdata" class="options-form">
					<legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
					<div>
					<?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
				</fieldset>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
