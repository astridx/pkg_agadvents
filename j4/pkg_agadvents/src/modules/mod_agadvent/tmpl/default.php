<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_agadvent
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;
?>


<ul>
<?php foreach ($list as $i => $item) : ?>
  <?php if (true) : ?>
    <li class="" >

    <div class="">
      <?php echo $item->name; ?>
      <?php echo $item->cords; ?>
    </div>

  </li>
  <?php endif; ?>
<?php endforeach; ?>
</ul>

?>
<figure id="imagemap">
<svg viewBox="0 0 1536 1024" >
  <defs>
    <style>
      rect:hover {
	    fill: white;
	    opacity:0.5;
	  }
    </style>
  </defs> 
 
  <image width="1536" height="1024" xlink:href="https://picsum.photos/1536/1024">
    <title>Mount Rushmore National Memorial</title>
  </image>	
  
  <a xlink:href="https://de.wikipedia.org/wiki/George_Washington">
    <rect x="300" y="125" opacity="0" width="250" height="300" />
  </a>
  <a xlink:href="https://de.wikipedia.org/wiki/Thomas_Jefferson">
    <rect x="550" y="225" opacity="0" width="200" height="300" />
  </a>
  <a xlink:href="https://de.wikipedia.org/wiki/Theodore_Roosevelt">
    <rect x="750" y="375" opacity="0" width="200" height="300" />
  </a>
  <a xlink:href="https://de.wikipedia.org/wiki/Abraham_Lincoln">
    <rect x="999" y="375" opacity="0" width="200" height="300" />
  </a>
</svg>
  <figcaption>Mount Rushmore National Memorial - Klicken Sie auf die Köpfe.</figcaption>
</figure>

hhh
