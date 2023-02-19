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

use Joomla\CMS\HTML\HTMLHelper;

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
 * @var   int     $itemsXline      Items x line selected for responive views.
 * @var   int     $itemsXlineBs2   Items x line selected for view.
 */

$sublayout = 'default';
$imgData   = array();
$imgWidth  = array();
$linkAttr  = array();

$imgData['attribs'] = array();

if ($captionOverlay == 'overlay')
{
	$captionOverlay = 'caption';
}

$imgData['containerClass'] = '';

if ($imageLayout == 'thumbnail')
{
	$imgData['containerClass'] = 'thumbnail';
}

if ($imageLayout == 'rounded')
{
	$imgData['attribs']['class'] = 'thumbnail';
}

if ($imageLayout == 'circle')
{
	$imgData['attribs']['class'] = 'img-circle';
}

$imgWidth = 'span' . $itemsXline;

$imgContainer = 'div';
$gutter = $gutter == 'collapse' ? ' no-gutters': ' show-grid';

HTMLHelper::_('stylesheet', 'plg_fields_imageschoose/bs.min.css', array('version' => 'auto', 'relative' => true));
PlgFieldsImageschooseHelper::initJs(); ?>

<div class="imagechooseContainer icBs2">
	<div class="row thumbnails<?php echo $gutter; ?>">
		<?php include __DIR__ . '/_tmpl_base.php'; ?>
	</div>
</div>
