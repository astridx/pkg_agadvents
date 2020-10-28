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
?>

<?php
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
	JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
} else
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
	if ($params->get('clickableareas') === 'fix')
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

	if ($params->get('clickableareas') === 'hover')
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
	} else {
		$script[] = "function myLeave" . $uniqid . "()";
		$script[] = "{";
		$script[] = "	console.log('');";
		$script[] = "}";

		$script[] = "function myHover" . $uniqid . "(element)";
		$script[] = "{";
		$script[] = "	console.log('');";
		$script[] = "}";		
	}
}
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

//load parameters for veno box
$component_params = JComponentHelper::getParams('com_agadvents');
$popup_background_color = $component_params->get('popup_background_color', '#fff');
$popup_width = $component_params->get('popup_width', 0) . 'px';
$popup_height = $component_params->get('popup_height', 0) . 'px';
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
$venojs[] = "     framewidth: '" . $popup_width . "',	";
$venojs[] = "     frameheight: '" . $popup_height . "',";
$venojs[] = "     bgcolor: '" . $popup_background_color . "',";
$venojs[] = " });";
$venojs[] = "});";

//load parameters for selecting the view
$test = $params->get('test', 0);
//$popup = $params->get('popup', 0);
$shownotaktiv = $params->get('shownotaktiv', 0);

// Aktuelles Datum für Echtbetrieb speichern
$current_date = JFactory::getDate();
?>








<?php if ($params->get('display') == 'image' || $params->get('display') == 'image_and_list') : ?>


	<?php
	JHtml::_('jquery.framework');
	JHtml::stylesheet('com_agadvents/style.css', array(), true);
	?>


	<map class="mapModule" name="usemap_<?php echo $uniqid; ?>" id="usemap_<?php echo $uniqid; ?>">
	<?php foreach ($list as $item) : ?>

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
					(!$datum && $test && $popup && !$shownotaktiv) ||
					(!$datum && $test && $popup && $shownotaktiv) ||
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
						href="<?php echo $item->link ?>"
						rel="' . $params->get('follow', 'nofollow') . '"
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
						href="<?php echo $item->link_popup ?>"
						rel="' . $params->get('follow', 'nofollow') . '"
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
						href="<?php echo $item->link_notactive ?>"
						rel="' . $params->get('follow', 'nofollow') . '"
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
						href="<?php echo $item->link_notactive_popup ?>"
						rel="' . $params->get('follow', 'nofollow') . '"
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
						href="<?php echo $item->link ?>"
						rel="' . $params->get('follow', 'nofollow') . '"
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
<?php endif; // diplay = image ?>





<?php if ($params->get('display') == 'list' || $params->get('display') == 'image_and_list') : ?>


	<ul class="agadvents<?php echo $moduleclass_sfx; ?>">
	<?php foreach ($list as $item) : ?>
			<li>
		<?php $link = $item->link; ?>
		<?php
		switch ($item->params->get('target', 3))
		{
			case 1:
				// Open in a new window
				echo '<a href="' . $link . '" target="_blank" rel="' . $params->get('follow', 'nofollow') . '">' .
				htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
				break;

			case 2:
				// Open in a popup window
				echo "<a href=\"#\" onclick=\"window.open('" . $link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\">" .
				htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
				break;

			default:
				// Open in parent window
				echo '<a href="' . $link . '" rel="' . $params->get('follow', 'nofollow') . '">' .
				htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8') . '</a>';
				break;
		}
		?>

				<?php if ($params->get('description', 0)) : ?>
					<?php echo nl2br($item->description); ?>
				<?php endif; ?>

				<?php if ($params->get('hits', 0)) : ?>
					<?php echo '(' . $item->hits . ' ' . JText::_('MOD_AGADVENTS_HITS') . ')'; ?>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
	</ul>





	<?php endif; // display = list  ?>

