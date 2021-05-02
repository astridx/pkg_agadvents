<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_agadvent
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$info = array();
$info[0] = 0;
$info[1] = 0;
$info = getimagesize($catimage);
?>

<figure style="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_STYLE_FIGURE'); ?>" id="imagemap">
<svg viewBox="0 0 <?php echo $info[0]; ?> <?php echo $info[1]; ?>" >
  <defs>
    <style>
      polygon:hover {
	    fill: white;
	    opacity:0.5;
	  }
    polygon {
	    fill: white;
	    opacity:0.2;
	  }
    </style>
  </defs> 
 
  <image xlink:href="<?php echo $catimage; ?>" alt="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_ALT'); ?>" />
    
  <?php foreach ($list as $i => $item) : ?>
  <?php if (true) : ?>  
  <a xlink:href="<?php echo $item->cordsimagemap; ?>">
    <polygon points="<?php echo $item->cordsimagemap; ?>" style="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_STYLE_POLYGONE'); ?>" />
  </a>
  <?php endif; ?>
<?php endforeach; ?>

</svg>
  <figcaption><?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_FIGCAPTION'); ?></figcaption>
</figure>

