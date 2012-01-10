<?php
/**
 * Group Photos View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
jimport( 'joomla.utilities.date');

class MojoomViewGroup_photos extends JView
{
	function display($tpl = null)
	{
		$albumId	= JRequest::getVar('album_id' , '');
		$model	= $this->getModel();
		// 2nd argument for the PHOTOS_USER_TYPE and PHOTOS_GROUP_TYPE but here for individual so it is user
 		$group_photos	= $model->getAllPhotos( $albumId ,'group',null,'DESC');
		$this->assignRef( 'group_photos',	$group_photos );
		parent::display($tpl);
	}
}
