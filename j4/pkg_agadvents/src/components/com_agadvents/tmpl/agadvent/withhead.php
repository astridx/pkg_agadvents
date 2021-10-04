<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

echo "<hr>With head.<hr>";

echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;
