<?php
	/**
	 * @package     Joomla.Plugin
	 * @subpackage  Fields.Osmap
	 *
	 * @copyright   Copyright (C) 2017 NAME. All rights reserved.
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 */
	
	use Joomla\CMS\HTML\HTMLHelper;
	use Joomla\CMS\Language\Text;
	use Joomla\CMS\Session\Session;
	
	
	defined('_JEXEC') or die;
	
	JFormHelper::loadFieldClass('text');
	
	class JFormFieldImageschoose extends JFormFieldText
	{
		public $type = 'Whylinks';
		
		
		/**
		 * Method to get the field input markup.
		 *
		 * @return  string  The field input markup.
		 *
		 * @since   3.7.0
		 */
		
		public function getInput()
		{
			
			$field = '<div class="linkFieldContainer">';
			
			// Modal Button
			$field .= '<button
            type="button"
            class="button-save btn btn-primary imageChooseModalButton"
            data-bs-id="' . $this->id . '"
            data-bs-toggle="modal"
            data-bs-target="#imageChooseModalTarget-' . $this->id . '">' . $this->title . ' auswählen</button>';
			
			$field .= '<span class="hinweis">' . Text::_('PLG_FIELDS_IMAGESCHOOSE_REPLACE_NOTE') . '</span>';
			
			// Image Preview
			$field .= '<ul class="imagesPreviewList grid-container" id="previewList-' . $this->id . '"></ul>';
			
			// Modal
			$field .= $this->_setModalMedia();
			$field .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" />';
			$field .= '</div>';
			
			return $field;
		}
		
		
		/**
		 * Set Modal Media
		 *
		 * @return string
		 * @since version
		 */
		
		protected function _setModalMedia()
		{
			$token = Session::getFormToken();
			
			$html = '<iframe id="mediaFrame_' . $this->id . '" src="/administrator/index.php?option=com_jce&task=plugin.display&plugin=browser&standalone=1&' . $token . '=1&path=local-images:/"></iframe>';
			$footer = '<button class="btn btn-success addImages">' . Text::_('ADD') . '</button>';
			
			return HTMLHelper::_(
				'bootstrap.renderModal',
				'imageChooseModalTarget-' . $this->id, // selector
				array( // options
					'modal-dialog-scrollable' => true,
					'title'                   => 'Bilder auswählen',
					'footer'                  => $footer,
				),
				$html
			);
		}
	}
