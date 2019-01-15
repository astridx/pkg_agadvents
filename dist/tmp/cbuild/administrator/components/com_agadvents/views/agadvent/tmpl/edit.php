<?php
/**
 * @package     Joomla.Site
 * @subpackage  pkg_agadvents
 *
 * @copyright   Copyright (C) 2005 - 2018 Astrid Günther, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @link        astrid-guenther.de
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::script('media/jquery.Jcrop.min.js', false, true);
JHtml::stylesheet('media/jquery.Jcrop.min.css', array(), true);
JHtml::script('com_agadvents/script.js', false, true);
JHtml::stylesheet('com_agadvents/style.css', array(), true);

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0 ));

$app = JFactory::getApplication();
$input = $app->input;

$assoc = JLanguageAssociations::isEnabled();

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = array('details', 'images', 'item_associations', 'jmetadata');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (task == 'agadvent.cancel' || document.formvalidator.isValid(document.getElementById('agadvent-form'))) {
			" . $this->form->getField('description')->save() . "
			Joomla.submitform(task, document.getElementById('agadvent-form'));
		}
	};
");

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo JRoute::_('index.php?option=com_agadvents&layout=' . $layout . $tmpl . '&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="agadvent-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_AGADVENTS_NEW_AGADVENT', true) : JText::_('COM_AGADVENTS_EDIT_AGADVENT', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="form-vertical">
					<?php echo $this->form->getControlGroup('number'); ?>
					<?php echo $this->form->getControlGroup('description'); ?>
					<?php echo $this->form->getControlGroup('begin'); ?>
					<?php echo $this->form->getControlGroup('ende'); ?>
					<?php echo $this->form->getControlGroup('show_url'); ?>
					<?php echo $this->form->getControlGroup('url'); ?>
					<?php echo $this->form->getControlGroup('content'); ?>
					<?php echo $this->form->getControlGroup('content_no'); ?>
					<?php echo $this->form->getControlGroup('cords'); ?>

					<?php echo $this->form->getControlGroup('cords_hidden'); ?>
					<!-- Set in the image -->
					<?php
						$category  = $this->item->catid;
						$c = JCategories::getInstance('Agadvents')->get($category);
					?>
					<?php if ($c->getParams()->get('image')) : ?>
						<?php
							$imagedata = getimagesize(JPATH_SITE . '/' . $c->getParams()->get('image'));
							echo JText::_('COM_AGADVENTS_ADMIN_VIEW_IMAGE_WIDTH') . ' ' . $imagedata[0] . 'px ';
							echo ' ' . JText::_('COM_AGADVENTS_ADMIN_VIEW_IMAGE_HEIGHT') . ' ' . $imagedata[1] . 'px ';
						?>
						<img
							src="<?php echo JURI::root() . '/' . $c->getParams()->get('image'); ?>"
							id="target"
						>
					<?php else : ?>
						<?php echo JText::_('COM_AGADVENTS_ADMIN_VIEW_CORDS_MISSING_IMAGE'); ?>
					<?php endif;?>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('JGLOBAL_FIELDSET_IMAGE_OPTIONS', true)); ?>
			<div class="row-fluid">
				<div class="span6">
					<?php echo $this->form->getControlGroup('images'); ?>
					<?php foreach ($this->form->getGroup('images') as $field) : ?>
						<?php echo $field->getControlGroup(); ?>
					<?php endforeach; ?>
				</div>
			</div>

		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<?php if ( ! $isModal && $assoc) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
			<?php echo $this->loadTemplate('associations'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php elseif ($isModal && $assoc) : ?>
			<div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	</div>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
