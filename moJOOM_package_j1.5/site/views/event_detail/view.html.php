<?php
/**
 * Event Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class MojoomViewEvent_detail extends JView
{
	function display($tpl = null) 
	{ 
		$model1	= $this->getModel();
		$model = & JModel::getInstance('event','MojoomModel');		
		$my = JFactory::getUser();
		$data = JRequest::get( 'get' );
		$user_id = $my->id;				
		$event		= $model->getViewevent();
		if($data['group_id'] != "")
		{
			$EventResponse = $model->getGroupEventResponse($data['event_id'],$my->id);
			$this->assignRef( 'Response',	$EventResponse );
		}	
				
		$Info	 = $model->getInfo('events',$data['event_id']);
		$like = false;
		$dislike = false;
		$like = strpos($Info->like,$user_id.',');
		$dislike = strpos($Info->dislike,$user_id.',');
		$this->assignRef( 'like',	$like );
		$this->assignRef( 'dislike',	$dislike );								
		$this->assignRef( 'user_id',	$user_id );
		$this->assignRef( 'Event',	$event );
		
		$limit		= JRequest::getVar( 'limit' , 5 , 'REQUEST' );
		$limitstart = JRequest::getVar( 'limitstart', 0, 'REQUEST' );
		
		$eventid = JRequest::getVar('event_id',0);
		//print_r($eventid);
		$userActivities	= $model1->getPost('events',$eventid,$limit, $limitstart );
		$this->assignRef( 'activities',	$userActivities );
		$guestmember	= $model1->getGuestmember($eventid,$user_id);
		$this->assignRef( 'guestmember',	$guestmember );
		parent::display($tpl);
	}
	
	
	
	function getAvatar($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT thumb FROM #__community_users WHERE userid='.$id;
		$db->setQuery( $query );
		$result	= $db->loadObject();
		return $result->thumb;
	}
	
	function stripCommentData($comment)
	{
	
	// Once we retrive the comment, we can remove them
	
	$content = preg_replace('/\<comment\>(.*?)\<\/comment\>/i', '', $comment);
	
	return $content;
	
	}
	
	function getCommentsData($comment)
	{
		$json = new Services_JSON();
		$comments = array();	
		// See if the content already has commment.	
		// If not, create it and add to it	
		$regex = '/\<comment\>(.*?)\<\/comment\>/i';
		if (preg_match($regex, $comment, $matches)) 
		{
			$comments = $json->decode($matches[1]);
			
		}
		
		return $comments;
	}
}

