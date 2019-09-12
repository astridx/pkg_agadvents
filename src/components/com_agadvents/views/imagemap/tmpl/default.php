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

JHtml::stylesheet('com_agadvents/style.css', array(), true);

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$params = $this->params;
$category = $this->get('category');
$extension = $category->extension;
$canEdit = $params->get('access-edit');
$className = substr($extension, 4);

$uniqid = uniqid();

$script[] = "";

if ($params->get('jqueryversion') === 'simple')
{
	JHtml::script('com_agadvents/jquery.jmap.min.js', false, true);
	$script[] = "jQuery(function ($) {";
	$script[] = "	$(window).load(function (e) {";
	$script[] = "		$('img[usemap]').jMap();";
	$script[] = "	});";
	$script[] = "});";
}
else
{
	$script[] = "jQuery(function ($) {";
	$script[] = "	$(window).load(function (e) {";
	$script[] = "		$('img[usemap]').rwdImageMaps_" . $uniqid . "();";
	$script[] = "		$('area').on('click', function () {";
	$script[] = "		});";
	$script[] = "	});";
	$script[] = "});";
	$script[] = "var hdc" . $uniqid . ";";
	$script[] = "(function ($) {";
	$script[] = "	$.fn.rwdImageMaps_" . $uniqid . " = function () {";
	$script[] = "		var \$img = $('#img_" . $uniqid . "');";
	$script[] = "		var rwdImageMap = function () {";
	$script[] = "			\$img.each(function () {";
	$script[] = "				if (typeof ($(this).attr('usemap')) == 'undefined')";
	$script[] = "					return;";
	$script[] = "				var that = this,";
	$script[] = "						\$that = $(that);";
	$script[] = "				$('#img_" . $uniqid . "').on('load', function () {";
	$script[] = "					var attrW = 'width',";
	$script[] = "							attrH = 'height',";
	$script[] = "							w = \$that.attr(attrW),";
	$script[] = "							h = \$that.attr(attrH);";
	$script[] = "					if (!w || !h) {";
	$script[] = "						var temp = new Image();";
	$script[] = "						temp.src = \$that.attr('src');";
	$script[] = "						if (!w)";
	$script[] = "							w = temp.width;";
	$script[] = "						if (!h)";
	$script[] = "							h = temp.height;";
	$script[] = "					}";
	$script[] = "					var wPercent = \$that.width() / 100,";
	$script[] = "							hPercent = \$that.height() / 100,";
	$script[] = "							map = \$that.attr('usemap').replace('#', ''),";
	$script[] = "							c = 'coords';";
	$script[] = "							n = 'name';";
	$script[] = "					$('map[name=\"' + map + '\"]').find('area').each(function () {";
	$script[] = "						var \$this = $(this);";
	$script[] = "						if (!\$this.data(c))";
	$script[] = "							\$this.data(c, \$this.attr(c));";
	$script[] = "						if (!\$this.data(n))";
	$script[] = "							\$this.data(n, \$this.attr(n));";
	$script[] = "						var number = \$this.data(n);";
	$script[] = "						var coords = \$this.data(c).split(','),";
	$script[] = "								coordsPercent = new Array(coords.length);";
	$script[] = "						for (var i = 0; i < coordsPercent.length; ++i) {";
	$script[] = "							if (i % 2 === 0)";
	$script[] = "								coordsPercent[i] = parseInt(((coords[i] / w) * 100) * wPercent);";
	$script[] = "							else";
	$script[] = "								coordsPercent[i] = parseInt(((coords[i] / h) * 100) * hPercent);";
	$script[] = "						}";
	$script[] = "						\$this.attr(c, coordsPercent.toString());";
	if ($params->get('clickableareas') === 'fix' || $params->get('clickableareas') === 'fixwithoutnumbers')
	{
		$script[] = "drawRect" . $uniqid . "(coordsPercent.toString(), number);";
	}
	$script[] = "					});";
	$script[] = "				}).attr('src', \$that.attr('src'));";
	$script[] = "			});";
	$script[] = "			var img = document.getElementById('img_" . $uniqid . "');";
	$script[] = "			var x, y, w, h;";
	$script[] = "			// get its position and width+height;";
	$script[] = "			x = img.offsetLeft;";
	$script[] = "			y = img.offsetTop;";
	$script[] = "			w = img.clientWidth;";
	$script[] = "			h = img.clientHeight;";
	$script[] = "			// move the canvas, so it's contained by the same parent as the image";
	$script[] = "			var imgParent = img.parentNode;";
	$script[] = "			var can = document.getElementById('" . $uniqid . "');";
	$script[] = "			imgParent.appendChild(can);";
	$script[] = "			// place the canvas in front of the image";
	$script[] = "			can.style.zIndex = 1;";
	$script[] = "			// position it over the image";
	$script[] = "			can.style.left = x + 'px';";
	$script[] = "			can.style.top = y + 'px';";
	$script[] = "			// make same size as the image";
	$script[] = "			can.setAttribute('width', w + 'px');";
	$script[] = "			can.setAttribute('height', h + 'px');";
	$script[] = "			hdc" . $uniqid . " = can.getContext('2d');";
	$script[] = "			};";
	$script[] = "			$(window).resize(rwdImageMap).trigger('resize');";
	$script[] = "			return this;";
	$script[] = "	};";
	$script[] = "})(jQuery);";

	if ($params->get('clickableareas') === 'fix')
	{
		$script[] = "function drawRect" . $uniqid . "(coOrdStr, number)";
		$script[] = "{";
		$script[] = "	var mCoords = coOrdStr.split(',');";
		$script[] = "	var top, left, bot, right;";
		$script[] = "	left = mCoords[0];";
		$script[] = "	top = mCoords[1];";
		$script[] = "	right = mCoords[2];";
		$script[] = "	bot = mCoords[3];";
		$script[] = "	hdc" . $uniqid . ".fillStyle = 'rgba(225,225,225,0.5)';";
		$script[] = "	hdc" . $uniqid . ".strokeStyle = 'rgba(225,225,225,0.5)';";
		$script[] = "	hdc" . $uniqid . ".lineWidth = 2;";
		$script[] = "	hdc" . $uniqid . ".fillRect(left, top, right - left, bot - top);";
		$script[] = "	hdc" . $uniqid . ".fillStyle = 'rgba(0,0,0,0.5)';";
		$script[] = "	hdc" . $uniqid . ".font = 'bold 20px sans-serif';";
		$script[] = "	hdc" . $uniqid . ".fillText(number, parseInt(left), parseInt(top)+20 );";
		$script[] = "}";
	}

	if ($params->get('clickableareas') === 'hover'|| $params->get('clickableareas') === 'fixwithoutnumbers')
	{
		$script[] = "function drawRect" . $uniqid . "(coOrdStr)";
		$script[] = "{";
		$script[] = "	var mCoords = coOrdStr.split(',');";
		$script[] = "	var top, left, bot, right;";
		$script[] = "	left = mCoords[0];";
		$script[] = "	top = mCoords[1];";
		$script[] = "	right = mCoords[2];";
		$script[] = "	bot = mCoords[3];";
		$script[] = "	hdc" . $uniqid . ".fillStyle = 'rgba(225,225,225,0.5)';";
		$script[] = "	hdc" . $uniqid . ".strokeStyle = 'rgba(225,225,225,0.5)';";
		$script[] = "	hdc" . $uniqid . ".lineWidth = 2;";
		$script[] = "	hdc" . $uniqid . ".fillRect(left, top, right - left, bot - top);";
		$script[] = "	hdc" . $uniqid . ".fillStyle = 'rgba(0,0,0,0.5)';";
		$script[] = "	hdc" . $uniqid . ".font = 'bold 20px sans-serif';";
		$script[] = "}";
	}

	if ($params->get('clickableareas') === 'hover')
	{
		$script[] = "function myLeave" . $uniqid . "()";
		$script[] = "{";
		$script[] = "	var canvas = document.getElementById('" . $uniqid . "');";
		$script[] = "	hdc" . $uniqid . ".clearRect(0, 0, canvas.width, canvas.height);";
		$script[] = "}";

		$script[] = "function myHover" . $uniqid . "(element)";
		$script[] = "{";
		$script[] = "	var coordStr = element.getAttribute('coords');";
		$script[] = "	drawRect" . $uniqid . "(coordStr);";
		$script[] = "}";
	}
}
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

// Load parameters for veno box
$popup_width = $params->get('popup_width', 0) . 'px';
$popup_height = $params->get('popup_height', 0) . 'px';

if ($popup_width == 0)
{
	$popup_width = '';
}

if ($popup_height == 0)
{
	$popup_height = '';
}
$venojs[] = "jQuery(function ($) {";
$venojs[] = "$('.venobox').venobox({";
$venojs[] = "     framewidth: '" . $popup_width . "',";
$venojs[] = "     frameheight: '" . $popup_height . "',";
$venojs[] = "     bgcolor: '" . $params->get('popup_background_color', '#fff') . "',";
//$venojs[] = "     border: '100px',";
$venojs[] = " });";
$venojs[] = "});";

//load parameters for selecting the view
$test = $params->get('test', 0);
//$popup = $params->get('popup', 0);
$shownotaktiv = $params->get('shownotaktiv', 0);

// Aktuelles Datum für Echtbetrieb speichern
$current_date = JFactory::getDate();

$dispatcher = JEventDispatcher::getInstance();

$category->text = $category->description;
$dispatcher->trigger('onContentPrepare', array($extension . '.categories', &$category, &$params, 0));
$category->description = $category->text;

$results = $dispatcher->trigger('onContentAfterTitle', array($extension . '.categories', &$category, &$params, 0));
$afterDisplayTitle = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentBeforeDisplay', array($extension . '.categories', &$category, &$params, 0));
$beforeDisplayContent = trim(implode("\n", $results));

$results = $dispatcher->trigger('onContentAfterDisplay', array($extension . '.categories', &$category, &$params, 0));
$afterDisplayContent = trim(implode("\n", $results));
/**
 * This will work for the core components but not necessarily for other components
 * that may have different pluralisation rules.
 */
if (substr($className, -1) === 's')
{
	$className = rtrim($className, 's');
}
$tagsData = $category->tags->itemTags;
?>
<div>
	<div class="<?php echo $className . '-category' . $this->pageclass_sfx; ?>">
<?php if ($params->get('show_page_heading')) : ?>
			<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
			</h1>
			<?php endif; ?>

		<?php if ($params->get('show_category_title', 1)) : ?>
			<h2>
			<?php echo JHtml::_('content.prepare', $category->title, '', $extension . '.category.title'); ?>
			</h2>
			<?php endif; ?>
			<?php echo $afterDisplayTitle; ?>

		<?php if ($params->get('show_cat_tags', 1)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
		<?php endif; ?>

		<?php if ($beforeDisplayContent || $afterDisplayContent || $params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
			<div class="category-desc">
			<?php if ($params->get('show_description_image') && $category->getParams()->get('image')) : ?>

					<map class="mapComponent" name="usemap_<?php echo $uniqid; ?>" id="usemap_<?php echo $uniqid; ?>">
					<?php foreach ($this->items as $i => $item) : ?>

							<?php
							$item->cords;
							$begin = JFactory::getDate($item->begin);
							$ende = JFactory::getDate($item->ende);
							// Is the current date between begin and end?
							if ($current_date >= $begin && $current_date <= $ende)
							{
								$datum = true;
							} else
							{
								$datum = false;
							}
							?>

							<?php
							// Link to external url and popup is not possible
							$popup = $params->get('popup', 0);
							if ($item->show_url && ($datum && $popup || $test && $popup))
							{
								$popup = false;
							}
							// Set the correct view
							if (!$datum && !$test && !$shownotaktiv)
							{
								$view = 'anzeige_nein';
							} elseif (($datum && $test && $popup && $shownotaktiv) ||
									($datum && !$test && $popup && $shownotaktiv) ||
									(!$datum && $test && $popup && $shownotaktiv) ||
									(!$datum && $test && $popup && !$shownotaktiv) ||
									($datum && $test && $popup && !$shownotaktiv) ||
									($datum && !$test && $popup && !$shownotaktiv) ||
									($datum && !$test && $popup && $shownotaktiv))
							{
								$view = 'anzeige_aktiv__popup_ja';
							} elseif (!$datum && !$test && !$popup && $shownotaktiv)
							{
								$view = 'anzeige_inaktiv__popup_nein';
							} elseif (!$datum && !$test && $popup && $shownotaktiv)
							{
								$view = 'anzeige_inaktiv__popup_ja';
							} else
							{
								$view = 'anzeige_aktiv__popup_nein';
							}
							?>

<?php switch ($view): ?>
<?php case 'anzeige_aktiv__popup_nein': ?>
									<area
										href="<?php echo $item->link; //JURI::root() . 'index.php?option=com_agadvents&view=agadvent&id=' . $item->id ?>"
										shape="rect"
										name="<?php echo $item->number ?>"
										onmouseover="myHover<?php echo $uniqid; ?>(this);"
										onmouseout="myLeave<?php echo $uniqid; ?>();"
										coords="<?php echo $item->cords ?>"
										title="<?php echo $item->title ?>"
										alt="<?php echo $item->description ?>"
										/>
<?php break; ?>
<?php case 'anzeige_nein': ?>
					<?php // do nothing  ?>
<?php break; ?>
<?php case 'anzeige_aktiv__popup_ja': ?>
					<?php
					JHtml::script('com_agadvents/venobox.min.js', false, true);
					JHtml::stylesheet('com_agadvents/venobox.css', array(), true);
					JFactory::getDocument()->addScriptDeclaration(implode("\n", $venojs));
					?>
									<area
										href="<?php echo $item->link_popup; // JURI::root() . 'index.php?option=com_agadvents&tmpl=component&view=agadvent&id=' . $item->id ?>"
										shape="rect"
										name="<?php echo $item->number ?>"
										class="venobox"
										data-vbtype="iframe"
										onmouseover="myHover<?php echo $uniqid; ?>(this);"
										onmouseout="myLeave<?php echo $uniqid; ?>();"
										coords="<?php echo $item->cords ?>"
										title="<?php echo $item->title ?>"
										alt="<?php echo $item->description ?>"
										/>
<?php break; ?>
<?php case 'anzeige_inaktiv__popup_nein': ?>
									<area
										href="<?php echo $item->link_notactive; // JURI::root() . 'index.php?option=com_agadvents&view=agadvent_no&id=' . $item->id  ?>"
										shape="rect"
										name="<?php echo $item->number ?>"
										onmouseover="myHover<?php echo $uniqid; ?>(this);"
										onmouseout="myLeave<?php echo $uniqid; ?>();"
										coords="<?php echo $item->cords ?>"
										title="<?php echo $item->title ?>"
										alt="<?php echo $item->description ?>"
										/>
<?php break; ?>
<?php case 'anzeige_inaktiv__popup_ja': ?>
					<?php
					JHtml::script('com_agadvents/venobox.min.js', false, true);
					JHtml::stylesheet('com_agadvents/venobox.css', array(), true);
					JFactory::getDocument()->addScriptDeclaration(implode("\n", $venojs));
					?>
									<area
										href="<?php echo $item->link_notactive_popup; // JURI::root() . 'index.php?option=com_agadvents&tmpl=component&view=agadvent_no&id=' . $item->id ?>"
										shape="rect"
										name="<?php echo $item->number ?>"
										class="venobox"
										data-vbtype="iframe"
										onmouseover="myHover<?php echo $uniqid; ?>(this);"
										onmouseout="myLeave<?php echo $uniqid; ?>();"
										coords="<?php echo $item->cords ?>"
										title="<?php echo $item->title ?>"
										alt="<?php echo $item->description ?>"
										/>
<?php break; ?>
<?php default: ?>
					<?php // default = anzeige_aktiv__popup_nein  ?>
									<area
										href="<?php echo $item->link; // JURI::root() . 'index.php?option=com_agadvents&view=agadvent&id=' . $item->id  ?>"
										shape="rect"
										onmouseover="myHover<?php echo $uniqid; ?>(this);"
										onmouseout="myLeave<?php echo $uniqid; ?>();"
										coords="<?php echo $item->cords ?>"
										title="<?php echo $item->title ?>"
										alt="<?php echo $item->description ?>"
										/>
<?php endswitch ?>

							<?php endforeach; ?>
					</map>
					<canvas
						id="<?php echo $uniqid; ?>">
					</canvas>
					<img
						id="img_<?php echo $uniqid; ?>"
						class=""
						src="<?php echo $category->getParams()->get('image'); ?>"
						style="max-width:100%;"
						border="0"
						title="<?php echo htmlspecialchars($category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"
						usemap="#usemap_<?php echo $uniqid; ?>"
						alt="<?php echo htmlspecialchars($category->getParams()->get('image_alt'), ENT_COMPAT, 'UTF-8'); ?>"
						/>

	<?php endif; ?>
	<?php echo $beforeDisplayContent; ?>
	<?php if ($params->get('show_description') && $category->description) : ?>
		<?php echo JHtml::_('content.prepare', $category->description, '', $extension . '.category.description'); ?>
	<?php endif; ?>
	<?php echo $afterDisplayContent; ?>
				<div class="clr"></div>
			</div>
			<?php endif; ?>

	</div>
</div>



