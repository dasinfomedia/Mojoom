<?php
/**
 * Friend View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewFriend extends JView
{
	function display($tpl = null)
	{
		
		$mainframe =& JFactory::getApplication();
		
		$my	= JFactory::getUser();
		//$id = JRequest::getCmd('userid', 0 );	
		$id = 	JRequest::getVar( 'user_id' , 0 );
		//echo $id;	
		if( $id == 0 )
		{
			$id	= $my->id;
			
		}
		$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$filter		= JRequest::getWord( 'filter' , 'all' , 'GET' );
		$friends 	= $this->getModel( 'friend' );
		$friend = $friends->getFriends($id , $sorted , true , $filter );
		
		$this->assignRef( 'friend',	$friend );
		parent::display($tpl);
	}
}

