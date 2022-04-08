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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use AgadventNamespace\Component\Agadvents\Site\Helper\RouteHelper;

$info = [];
$info[0] = 1;
$info[1] = 1;
$info = getimagesize($catimage);
?>

<?php if ($test) : ?>
	<?php // echo Text::_('COM_AGADVENTS_MODULE_TMPL_TEST'); ?>
<?php endif; ?>

<?php if ($mode === 'image' || $mode === 'imageandlist') : ?>  
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
		<?php if ($item->showurl === "0") : ?>  
			<a xlink:href="<?php echo Route::_(RouteHelper::getAgadventRoute($item->slug, $item->catid, $item->language)) . '?layout=' . $item->layout; ?>">
				<polygon points="<?php echo $item->cordsimagemap; ?>" style="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_STYLE_POLYGONE'); ?>" />
			</a>
		<?php endif; ?>
		<?php if ($item->showurl === "1") : ?>  
			<a xlink:href="<?php echo $item->url; ?>">
				<polygon points="<?php echo $item->cordsimagemap; ?>" style="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_STYLE_POLYGONE'); ?>" />
			</a>
		<?php endif; ?>
		<?php if ($item->showurl === "2") : ?>  
			<?php $itemurl =  Uri::root() . $item->url; ?>
			<a xlink:href="<?php echo $itemurl; ?>">
				<polygon points="<?php echo $item->cordsimagemap; ?>" style="<?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_STYLE_POLYGONE'); ?>" />
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
	</svg>
	<figcaption><?php echo Text::_('COM_AGADVENTS_MODULE_TMPL_DEFAULT_FIGCAPTION'); ?></figcaption>
	</figure>
<?php endif; ?>

<?php if ($mode === 'list' || $mode === 'imageandlist') : ?> 
	<ul>
		<?php foreach ($list as $i => $item) : ?>
			<?php if ($showurl === "0") : ?> 
				<li> 
				<a href="<?php echo Route::_(RouteHelper::getAgadventRoute($item->slug, $item->catid, $item->language)) . '?layout=' . $item->layout; ?>">
					<?php echo $item->name; ?>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
