<?php
/**
 * My Event View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
ini_set("display_errors","0");

class MojoomViewMyevent extends JView
{
	function display($tpl = null)
	{ 
		$model = & JModel::getInstance('event','MojoomModel');		
		$user = JFactory::getUser();  
		$data = JRequest::get( 'get' );
		
		
		if($data['event_type'] == 2) // 2 = all event 
		{
			$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );			
			$myevent		= $model->getMyevents( null, null , $sorted , null , true , false , null , null , 'ALL_TYPES');
		}
		elseif($data['event_type'] == 3) // 3 = past event
		{
			$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
			$myevent		= $model->getMyevents( null, $user->id , $sorted , null, false, true);	
		}
		else
		{
			$sorted		= JRequest::getVar( 'sort' , 'startdate' , 'GET' );
			$myevent		= $model->getMyevents( null, $user->id , $sorted );
		}
		$this->assignRef( 'user_id',	$user->id );
		$this->assignRef( 'Myevent',	$myevent );
		parent::display($tpl);
	}
}

