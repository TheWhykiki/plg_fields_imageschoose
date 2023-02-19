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
 * @var   string  $thumb            Url to the thumbnail of the image.
 * @var   string  $alt              Alternate text of image.
 * @var   array   $attribs          Attributes of image or empty.
 * @var   string  $caption_overlay  Tag for title container.
 * @var   string  $containerClass   Classes for container.
 */

$attribs['class'] = !empty($attribs['class']) ? $attribs['class'] . ' uk-overlay-spin' : 'uk-overlay-spin';
$containerClass .= ' uk-overlay uk-overlay-hover';
?>
<figure class="<?php echo trim($containerClass); ?>">
	<?php echo HTMLHelper::_('image', $thumb, $alt, $attribs, false, -1); ?>
	<figcaption class="uk-overlay-panel uk-overlay-fade uk-overlay-background uk-flex uk-flex-center uk-flex-middle uk-text-center">
		<div><?php echo $caption_overlay; ?></div>
	</figcaption>
</figure>
