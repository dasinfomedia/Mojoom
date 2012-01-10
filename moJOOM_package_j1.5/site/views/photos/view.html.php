<?php
/**
 * Photos View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.date');
class MojoomViewPhotos extends JView
{
	function display($tpl = null)
	{
		$albumId	= JRequest::getVar('album_id' , '');
		$model	= $this->getModel();
		// 2nd argument for the PHOTOS_USER_TYPE and PHOTOS_GROUP_TYPE but here for individual so it is user
 		$photos	= $model->getAllPhotos( $albumId ,'user',null,'DESC');
		$this->assignRef( 'photos',	$photos );
		parent::display($tpl);
	}
}

