<?php
	
	/**
	 * Joomla! Content Management System
	 *
	 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
	 * @license    GNU General Public License version 2 or later; see LICENSE.txt
	 */
	
	

// phpcs:disable PSR1.Files.SideEffects
	use Joomla\CMS\Factory;
	
	\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects
	
	/**
	 * Form Field class for the Joomla Platform.
	 * Provides a hidden field
	 *
	 * @link   https://html.spec.whatwg.org/multipage/input.html#hidden-state-(type=hidden)
	 * @since  1.7.0
	 */
	class JFormFieldStyling extends \JFormFieldHidden
	{
		/**
		 * The form field type.
		 *
		 * @var    string
		 * @since  1.7.0
		 */
		protected $type = 'Styling';
		
		/**
		 * Name of the layout being used to render the field
		 *
		 * @var    string
		 * @since  3.7
		 */
		protected $layout = 'joomla.form.field.hidden';
		
		/**
		 * Method to get the field input markup.
		 *
		 * @return  string  The field input markup.
		 *
		 * @since   1.7.0
		 */
		protected function getInput()
		{
			$app = Factory::getApplication();
			$wa = $app->getDocument()->getWebAssetManager();
			$wa->registerAndUseStyle('plg_fields_imageschoose_styling', 'plg_fields_imageschoose/styling.css');
			return '';
		}
		
	}
