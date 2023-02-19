<?php
/**
 * @package      Joomla.Plugin
 * @subpackage   Fields.Imageschoose
 *
 * @author       Whykiki <info@whykiki.de>
 * @copyright    2023 wir-lieben-webdesign.de - All rights reserved.
 * @license      GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

/**
 * Layout variables
 * ---------------------
 * @var   string  $thumb            Url to the thumbnail of the image.
 * @var   string  $alt              Alternate text of image.
 * @var   array   $attribs          Attributes of image or empty.
 * @var   string  $caption_overlay  Html for caption/overlay.
 * @var   array   $containerClass   CSS classes for container.
 */

$containerClass = !empty($containerClass) ? ' class="' . $containerClass . '"' : '';
?>
<div<?php echo $containerClass ;?>>
	<?php echo HTMLHelper::_('image', $thumb, $alt, $attribs, false, -1);
	if (!empty($caption_overlay)) : ?>
		<div class="caption text-center">
			<?php echo $caption_overlay; ?>
		</div>
	<?php endif; ?>
</div>
