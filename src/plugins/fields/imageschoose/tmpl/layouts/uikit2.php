<?php
/**
 * @package      Joomla.Plugin
 * @subpackage   Fields.Jtgallery
 *
 * @author       Barbara Assmann, Guido De Gobbis
 * @copyright    (c) JoomTools.de - All rights reserved.
 * @license      GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

extract($displayData);

/**
 * Layout variables
 * ---------------------
 * @var   string  $frwk            Selected framework.
 * @var   array   $images          All selected images or all images inside of selected folder.
 * @var   string  $imagesPath      Absolute path of images if folder is selected.
 * @var   string  $captionOverlay  Output of Caption/Overlay if Framework support it.
 * @var   string  $imageLayout     Layout for image output.
 * @var   string  $thumbCachePath  Absolute path for thumbnails or responsive images.
 * @var   object  $itemsXline      Items x line selected for responive views.
 * @var   string  $gutter          Grid gutter between images.
 */

$sublayout = 'default';
$imgData   = array();
$imgWidth  = array();
$linkAttr  = array();

$imgData['attribs'] = array();

if ($imageLayout == 'thumbnail' && $captionOverlay)
{
	$linkAttr['class'] = 'uk-thumbnail uk-border-rounded';
	$imgData['attribs']['class'] = 'uk-border-rounded';
}

$imgData['containerClass'] = '';

if ($imageLayout && in_array($imageLayout,array('rounded', 'thumbnail'), true) && ($captionOverlay == 'overlay' || !$captionOverlay))
{
	$imgData['containerClass'] = 'uk-thumbnail uk-border-rounded';
	$imgData['attribs']['class'] = 'uk-border-rounded';
}

if ($imageLayout == 'rounded' && $captionOverlay == 'caption')
{
	$imgData['containerClass'] = 'uk-border-rounded';
	$imgData['attribs']['class'] = 'uk-thumbnail uk-border-rounded';
}

if ($imageLayout == 'circle' && $captionOverlay != 'caption')
{
	$imgData['containerClass'] = 'uk-thumbnail uk-border-circle';
	$imgData['attribs']['class'] = 'uk-border-circle';
}

if ($imageLayout == 'circle' && $captionOverlay == 'caption')
{
	$imgData['containerClass'] = 'uk-border-circle';
	$imgData['attribs']['class'] = 'uk-thumbnail uk-border-circle';
}

$responsiveGrids = array(
	'xl' => '-xlarge',
	'l'  => '-large',
	'm'  => '-medium',
	's'  => '-small',
);

foreach ($responsiveGrids as $key => $grid)
{
	if ($itemsXline->$key != '0')
	{
		$imgWidth[] = 'uk-width' . $grid . '-1-' . $itemsXline->$key;
	}
}

$imgContainer = 'div';
$imgWidth     = implode(' ', $imgWidth);
$gridMatch = $captionOverlay == 'caption' ? ' data-uk-grid-match="{target: \'figure\'}"' : '';
PlgFieldsImageschooseHelper::initJs(); ?>

<div class="uk-grid uk-grid-<?php echo $gutter; ?> imagechooseContainer icUikit2" data-uk-grid-margin<?php echo $gridMatch; ?>>
	<?php include __DIR__ . '/_tmpl_base.php'; ?>
</div>
