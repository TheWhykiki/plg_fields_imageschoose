<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Text
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;


defined('_JEXEC') or die;

if (!$field->value
	|| $field->value == '-1'
	|| !in_array($context, array('com_content.article', 'com_content.category')))
{
	return;
}

$params = $this->params;

$templateFramework   = (string) $params->get('templateFramework', 'raw');
$captionType =	(string) $params->get('caption_type', 'none');

$value = $field->value;

if ($value == '')
{
    return;
}

if (is_array($value))
{
    $value = implode(', ', $value);
}

if($templateFramework == 'raw') {
    echo htmlentities($value);
    return;
}

if (in_array($templateFramework, array('bs2', 'bs3', 'bs4','uikit2', 'uikit3'), true))
{
    $imagesArray = explode(',', $value);
	$imagesArray = array_map('trim', $imagesArray);
	
    $images = [];
 
	foreach ($imagesArray as $picture) {
		$image = new stdClass();
		$caption = PlgFieldsImageschooseHelper::getCaption($picture, $captionType);
		$image->picture = $picture;
		$image->picture_alt = Text::_('PLG_FIELDS_IMAGESCHOOSE_TEXT_BEFORE_ALT') . $picture;
		$image->picture_caption_overlay = $caption;
        $images[] = $image;
	}
 
}

//TODO: Mit Guido checken --> anders abfangen?
/*
if (!$params->get('activate', false))
{
	return;
}
*/

$theme             = Factory::getApplication()->getTemplate();
$themeOverridePath = JPATH_THEMES . '/' . $theme . '/html/plg_' . $this->_type . '_' . $this->_name;
$layoutBasePath    = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/tmpl/layouts';
$thumbCachePath    = JPATH_SITE . '/cache/plg_' . $this->_type . '_' . $this->_name;
$imagesPath        = false;

if (!Folder::exists($thumbCachePath))
{
    Folder::create($thumbCachePath);
}

$renderer = new FileLayout($templateFramework, $layoutBasePath, array('component' => 'none'));
$renderer->addIncludePath($themeOverridePath);

$debug = $this->params->get('debug') !== '0';
$renderer->setOptions(array('debug' => $debug));

$itemsXline    = (object) $params->get('items_x_line');
$itemsXlineBs2 = (int) $params->get('items_x_line_m', 3);

if ($templateFramework == 'bs2')
{
	$itemsXline = (int) round(12 / $itemsXlineBs2);
}

$gutter = $params->get('gutter', 'medium');

if (in_array($templateFramework, array('bs2', 'bs3', 'bs4'), true))
{
	$gutter = $params->get('gutter_bs', 'medium');
}

if ($templateFramework == 'uikit3')
{
	$itemsXline = (object) $params->get('items_x_line_uikit3');
}

$captionOverlay = $params->get('caption_overlay', 'none');
$imageLayout    = $params->get('image_layout', 'none');

if ($templateFramework == 'uikit3')
{
	$imageLayout = $params->get('image_layout_uikit3', 'none');
}

$thumbWidth  = $params->get('thumb_width', '320px');
$thumbHeight = $params->get('thumb_height', '240px');

$displayData = array(
	'frwk'           => $templateFramework,
	'images'         => $images,
	'imagesPath'     => $imagesPath,
	'captionOverlay' => $captionOverlay == 'none' ? false : $captionOverlay,
	'imageLayout'    => $imageLayout == 'none' ? false : $imageLayout,
	'thumbnails'     => array(
		'active'    => $params->get('thumbnails', 0),
		'cachePath' => $thumbCachePath,
		'width'     => $thumbWidth,
		'height'    => $imageLayout == 'circle' ? $thumbWidth : $thumbHeight,
	),
	'itemsXline'     => $itemsXline,
	'itemsXlineBs2'  => $itemsXlineBs2,
	'gutter'         => $gutter,
); ?>

<div class="imageschoose_container">
<?php echo $renderer->render($displayData); ?>
</div>



