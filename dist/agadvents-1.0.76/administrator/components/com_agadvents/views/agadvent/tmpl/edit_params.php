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

$fieldSets = $this->form->getFieldsets('params'); ?>
<?php foreach ($fieldSets as $name => $fieldSet) : ?>
	<div class="tab-pane" id="params-<?php echo $name; ?>">
	<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
		<?php echo '<p class="alert alert-info">' . $this->escape(JText::_($fieldSet->description)) . '</p>'; ?>
	<?php endif; ?>
	<?php foreach ($this->form->getFieldset($name) as $field) : ?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?></div>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
	<?php endforeach; ?>
	</div>
<?php endforeach; ?>
