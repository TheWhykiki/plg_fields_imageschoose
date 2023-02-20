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

if (version_compare(JVERSION, 4, 'ge'))
{
	\JLoader::registerAlias('FieldsHelper', '\\Joomla\\Component\\Fields\\Administrator\\Helper\\FieldsHelper');
}
	
use Joomla\CMS\Factory;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);
JLoader::register('PlgFieldsImageschooseHelper', __DIR__ . '/helper.php');

/**
 * Fields Text Plugin
 *
 * @since  3.7.0
 */
class PlgFieldsImageschoose extends FieldsPlugin
{
	
	/**
	 * @var   string
	 *
	 * @since   1.0.0
	 */
	private $context;
	
	
    public function onBeforeCompileHead()
    {
        $app = Factory::getApplication();
		
        if ($app->isClient('administrator'))
        {
	        $wa = $app->getDocument()->getWebAssetManager();
	        $wa->registerAndUseStyle('plg_fields_imageschoose_galleryfield', 'plg_fields_imageschoose/imageschoose.css');
	        $wa->registerAndUseScript('plg_fields_imageschoose_galleryfield_script', 'plg_fields_imageschoose/imageschoose.js');
        }

    }
	
	/**
	 * Show field only in allowed sections
	 *
	 * @return   array|\string[][]
	 *
	 * @since   1.0.0
	 */
	public function onCustomFieldsGetTypes()
	{
		if (in_array($this->context, array(
			'com_fields.field.com_content.article',
			'com_fields.field.com_content.categories',
			null,
		)))
		{
			return parent::onCustomFieldsGetTypes();
		}
		
		return array();
	}
	
	/**
	 * Set context for validation of allowed sections
	 *
	 * @param   \JForm     $form
	 * @param   \stdClass  $data
	 *
	 * @return   void
	 *
	 * @since   1.0.0
	 */
	public function onContentPrepareForm(JForm $form, $data)
	{
		$this->context = $form->getName();
		
		return parent::onContentPrepareForm($form, $data);
	}
	
	
}
