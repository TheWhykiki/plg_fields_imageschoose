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

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Image\Image;
use Joomla\CMS\Filesystem\File;

/**
 * Helper for plg_fields_imageschoose
 *
 * @since   1.0.0
 */
class PlgFieldsImageschooseHelper
{
	/**
	 * @var   bool
	 *
	 * @since   1.0.0
	 */
	public static $runJs = false;
	
	protected static $captionType;
	protected static $captionData;
	
	/**
	 * @param   object  $imgObject
	 *
	 * @return   void
	 *
	 * @since    1.0.0
	 */
	public static function createThumbnail(&$imgObject)
	{
		$image       = new Image($imgObject->imgAbsPath);
		$newImage    = $image->resize($imgObject->thumbnails['width'], $imgObject->thumbnails['height'], true, Image::SCALE_FIT);
		$thumbWidth  = $newImage->getWidth();
		$thumbHeight = $newImage->getHeight();
		
		$newImage->destroy();
		
		$thumbAbsPath = $imgObject->thumbnails['cachePath']
			. '/' . $imgObject->fileName
			. '_' . $thumbWidth . 'x' . $thumbHeight
			. '.' . $imgObject->fileExt;
		
		if (!file_exists($thumbAbsPath))
		{
			$thumbAbsPath = $image->createThumbs($thumbWidth . 'x' . $thumbHeight, Image::CROP_RESIZE, $imgObject->thumbnails['cachePath'])[0]
				->getPath();
		}
		
		$image->destroy();
		$imgObject->thumbnails['thumbAbsPath'] = $thumbAbsPath;
	}
	
	/**
	 * @param   string          $imagesPath
	 * @param   string|object   $image
	 *
	 * @return   object
	 *
	 * @since    1.0.0
	 */
	public static function getImgObject($imagesPath, $image)
	{
		$imgObject = new stdClass;
		$captionType = self::$captionType;
		$captionData = self::$captionData;
		
		$caption = '';
		
		if($captionType !== 'none')
		{
			$caption = self::getCaption($image->picture);
		}
		
		if ($imagesPath === false)
		{
			$imgObject->file            = pathinfo($image->picture, PATHINFO_BASENAME);
			$imgObject->fileName        = pathinfo($image->picture, PATHINFO_FILENAME);
			$imgObject->fileExt         = pathinfo($image->picture, PATHINFO_EXTENSION);
			$imgObject->url             = Uri::base(true) . '/' . $image->picture;
			$imgObject->imgAbsPath      = JPATH_SITE . '/' . $image->picture;
			$imgObject->alt             = str_replace(array('-', '_'), " ", $imgObject->fileName);
			$imgObject->caption_overlay = $caption;
			
			if (!empty($image->picture_alt))
			{
				$imgObject->alt = trim(strip_tags($image->picture_alt));
			}
			
			if (!empty($image->picture_caption_overlay))
			{
				$imgObject->caption_overlay = $image->picture_caption_overlay;
			}
			
			return $imgObject;
		}
		
		$imgObject->file            = $image;
		$imgObject->fileName        = pathinfo($image, PATHINFO_FILENAME);
		$imgObject->fileExt         = pathinfo($image, PATHINFO_EXTENSION);
		$imgObject->url             = Uri::base(true) . '/' . $imagesPath . '/' . $image;
		$imgObject->imgAbsPath      = JPATH_SITE . '/' . $imagesPath . '/' . $image;
		$imgObject->alt             = str_replace(array('-', '_'), " ", $imgObject->fileName);
		$imgObject->alt             = str_replace(array('-', '_'), " ", $imgObject->fileName);
		$imgObject->caption_overlay = $caption;
		
		return $imgObject;
	}
	
	public static function initJs()
	{
		if (self::$runJs === false)
		{
			$js = "\nwindow.onload = function() {\n";
			$js .= "\tbaguetteBox.run('.imagechooseContainer', {\n";
			$js .= "\t\tloop: true,\n";
			$js .= "\t\tanimation: 'fadeIn',\n";
			$js .= "\t\tnoScrollbars: true\n";
			$js .= "\t});\n";
			$js .= "};\n";
			
			$app = Factory::getApplication();
			$doc = $app->getDocument();
			$doc->addScriptDeclaration($js);
			
			JHtml::_('stylesheet', 'plg_fields_imageschoose/baguetteBox.min.css', array('version' => 'auto', 'relative' => true));
			JHtml::_('script', 'plg_fields_imageschoose/baguetteBox.min.js', array('version' => 'auto', 'relative' => true));
			
			self::$runJs = true;
		}
	}
	
	/**
	 * Get File Data from EXIF
	 *
	 * @param   string  $image
	 *
	 * @return   array  $fileData
	 *
	 * @since    1.0.0
	 */
	
	public static function getFileData($image)
	{
		$exif = exif_read_data($image);
		
		if ($exif === false)
		{
			return Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_EXIFERROR');
		}
		
		$path_parts = pathinfo($image);
		
		$fileExt =  $path_parts['extension'];
		$fileName =  $path_parts['filename'];
		$fileAuthor     = '';
		
		if(isset($exif['Artist']))
		{
			$fileAuthor      = $exif['Artist'];
		}
		$fileTitle = '';
		if(isset($exif['Title']))
		{
			$fileTitle       = $exif['Title'];
		}
		$fileDescription = '';
		if(isset($exif['ImageDescription']))
		{
			$fileDescription = $exif['ImageDescription'];
		}
		
		$fileSize 	  = '';
		if(isset($exif['FileSize']))
		{
			$fileSize        = self::_formatFilesize($exif['FileSize']);
		}
		
		$copyrightAuthor = '';
		if(isset($exif['Copyright']))
		{
			$copyrightAuthor = $exif['Copyright'];
		}
		
		$fileDate        = '';
		$fileYear        = '';
		if(isset($exif['DateTimeOriginal']))
		{
			
			$createdDate = new Date($exif['DateTimeOriginal']);
			$createDateString = $createdDate->toUnix();
			$fileDate        = PlgFieldsImageschooseHelper::formatFileDate($createDateString, false);
			$fileYear        = PlgFieldsImageschooseHelper::formatFileDate($createDateString, 'Y');
		}
		else{
			if(isset($exif['FileDateTime']))
			{
				$fileDate        = PlgFieldsImageschooseHelper::formatFileDate($exif['FileDateTime'], false);
				$fileYear        = PlgFieldsImageschooseHelper::formatFileDate($exif['FileDateTime'], 'Y');
			}
		}
		
		
		$variableNames = array(
			'fileName'        => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILENAME'),
			'fileExt'         => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILEEXT'),
			'fileAuthor'      => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILEAUTHOR'),
			'fileTitle'       => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILETITLE'),
			'fileDescription' => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILEDESCRIPTION'),
			'fileDate'        => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILEDATE'),
			'fileYear'        => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILEYEAR'),
			'fileSize'        => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_FILESIZE'),
			'copyrightAuthor' => Text::_('PLG_FIELDS_IMAGESCHOOSE_CAPTION_LABEL_COPYRIGHTAUTHOR'),
		);
		
		$fileArray = array();
		
		foreach ($variableNames as $name => $label)
		{
			if (isset($$name) && !empty($$name) && $$name != '')
			{
				$fileArray[$name] = [
					'label' => $label,
					'value' => $$name,
				];
			}
		}
		
		return $fileArray;
	}
	
	/**
	 * Format File Date to the current language
	 *
	 * @param   string  $date
	 * @param   string  $format
	 *
	 * @return   string
	 *
	 * @since    1.0.0
	 */
	
	public static function formatFileDate($date, $format)
	{
		$tester = $date;
		if(!$format)
		{
			$language    = Factory::getApplication()->getLanguage();
			$languageTag = $language->getTag();
			$locale = str_replace('-', '_', $languageTag);
			
			$formatter = new IntlDateFormatter(
				$locale,  // the locale to use, e.g. 'en_GB'
				IntlDateFormatter::SHORT,  // how the date should be formatted, e.g. IntlDateFormatter::FULL
				IntlDateFormatter::NONE  // how the time should be formatted, e.g. IntlDateFormatter::FULL
			);
			
			$pattern = $formatter->getPattern();
			$pattern = preg_replace(
				'/(?<!y)yy(?!y)/',
				'yyyy',
				$pattern);
			$formatter->setPattern($pattern);
			
			return $formatter->format($date);
		}
		
		$datum = new Joomla\CMS\Date\Date($date);
		
		return $datum->format($format);
	}
	
	
	/**
	 * Get Caption
	 *
	 * @param   string  $image
	 * @param   string  $captionType
	 * @param   array   $captionData
	 *
	 * @return   string
	 *
	 * @since    1.0.0
	 */
	public static function getCaption($image)
	{
		$captionType = self::$captionType;
		$captionData = self::$captionData;
		
		$caption = '';
		$fileData = self::getFileData($image);
		
		if($captionType === 'full')
		{
			$caption .= '<ul class="captionList">';
			foreach ($fileData as $key => $value)
			{
				if(in_array($key, $captionData))
				{
					$caption .= '<li><label>' . $value['label'] . ':</label> ' . $value['value'] . '</li>';
				}
			}
			$caption .= '</ul>';
		}
		else if($captionType === 'copyright')
		{
			$caption .= '<span class="captionCopyright">&copy; ' . $fileData['fileYear']['value'] . ' ' . $fileData['copyrightAuthor']['value'] . '</span>';
		}
		
		return $caption;
	}
	
	/**
	 * Get Filesize
	 *
	 * @param   string  $image
	 *
	 * @return   string
	 *
	 * @since    1.0.0
	 */
	protected static function _formatFilesize($size)
	{
		$language    = Factory::getApplication()->getLanguage();
		$languageTag = $language->getTag();
		
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		
		for ($i = 0; $size >= 1024 && $i < 4; $i++)
		{
			$size /= 1024;
		}
		
		$formatter = new NumberFormatter($languageTag, NumberFormatter::DECIMAL);
		
		$formattedSize = $formatter->format(round($size, 2)) . ' ' . $units[$i];
		
		return $formattedSize;
	}
	
	
	
	public static function helperInit($captionType, $captionData)
	{
		self::$captionType = $captionType;
		self::$captionData = $captionData;
		
	}

}
