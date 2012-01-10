<?php
/**
 * Event Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Event Controller
 *
 * @package    Mojoom
 * @subpackage Components 
 */
/* com_community component's core.php file included to be able to use the core classes of the component */ 
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class MojoomControllerEvent extends MojoomController 
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}
	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'event' );
		JRequest::setVar( 'layout', 'default'  );
		parent::display();
	}
	function myevents()
	{
		JRequest::setVar('view', 'myevent');
		parent::display();
	}
	function invite()
	{
		JRequest::setVar('view', 'eventinvite');
		parent::display();
	}
	function create()
	{
		JRequest::setVar('view', 'event');
		parent::display();
	}
	function viewevent()
	{
		JRequest::setVar('view', 'event_detail');
		parent::display();
	}
	function groupevent()
	{
		JRequest::setVar('view', 'groupevent');
		parent::display();
	}
	function creategroupevent()
	{
		JRequest::setVar('view', 'groupevent_create');
		parent::display();
	}
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('event');
		$data = JRequest::get( 'post' );
		if ($model->store($post)) {
			if($data['id'] == ""){
				//activity stream
			
				$event	= $model->getEvent();
				CFactory::load( 'libraries', 'activities' );
				$my				=	CFactory::getUser();
				// Activity stream purpose if the event is a public event
			
				$act = new stdClass();
				$act->cmd 		= 'events.create';
				$act->actor   	= $my->id;
				$act->target	= 0;
				$act->title	  	= JText::sprintf( '{actor} added a new event <a href="{event_url}">%1$s</a>' , $event->title );
				$act->content	= '';
				$act->cid		= $event->id;
				$act->app		= 'events';
	
				$params 		= new JParameter('');
				$action_str  	= 'events.create';
				
				$params->set( 'action', $action_str );
				$params->set( 'event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
	
				// Add activity logging
				CActivityStream::add( $act, $params->toString() );
				$msg = JText::_( 'EVENT CREATED' );
			}else {
			$msg = JText::_( 'EVENT UPDATED' );
			}
		} else {
			$msg = JText::_( 'ERROR IN EVENT' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&controller=event&task=myevents';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save_groupevent()
	{
		$model = $this->getModel('event');
		$data = JRequest::get( 'post' );
		if ($model->store_groupevent($post)) {
			if($data['id'] == ""){
				$event	= $model->getEvent();
				CFactory::load( 'libraries', 'activities' );
				$my				=	CFactory::getUser();
				// Activity stream purpose if the event is a public event
			
				$act = new stdClass();
				$act->cmd 		= 'events.create';
				$act->actor   	= $my->id;
				$act->target	= 0;
				$act->title	  	= JText::sprintf( '{actor} added a new group event <a href="{event_url}">%1$s</a>' , $event->title );
				$act->content	= '';
				$act->cid		= $event->id;
				$act->app		= 'events';
	
				$params 		= new JParameter('');
				$action_str  	= 'events.create';
				
				$params->set( 'action', $action_str );
				$params->set( 'event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id . '&groupid='.$data['contentid']);
	
				// Add activity logging
				CActivityStream::add( $act, $params->toString() );
			$msg = JText::_( 'GROUP EVENT CREATED' );
			}else {
			$msg = JText::_( 'GROUP EVENT UPDATED' );
			}
		} else {
			$msg = JText::_( 'ERROR IN GROUP EVENT' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&controller=event&task=groupevent&group_id='.$data['contentid'].'';
		$this->setRedirect($link, $msg);
	}
	function invited()
	{		
		$data = JRequest::get( 'post' );
		$db =& JFactory::getDBO();
		$my = JFactory::getUser();
		$date	=& JFactory::getDate();	
		$i=0;
		$flash = true;
		while($data['friends'][$i] != "")
		{
			$obj = new stdClass();
			$obj->id  = null;
			$obj->eventid = $data['event_id'];
			$obj->memberid 	 = $data['friends'][$i];
			$obj->status = 0;
			$obj->permission	= 3;
			$obj->invited_by	= $my->id;
			$obj->approval = 0;
			$obj->created	= $date->toMySQL();				
			$db->insertObject('#__community_events_members', $obj, 'id');     
			$i++;
			
			if($obj->id == "")
			{
				$flash = false;
			}			
		}
			
		// insert into invitation table also			
		$qry = "select id from #__community_invitations where callback = 'events,inviteUsers' and cid = '".$data['event_id']."'";
		$db->setQuery( $qry );
		$invitation = $db->loadResult();
		$users = implode(',',$data['friends']);
		if($invitation == "")
		{
			$qry = "INSERT into #__community_invitations(`callback`,`cid`,`users`) values('events,inviteUsers','".$data['event_id']."','".$users."')";
		}
		else
		{
			$qry = "UPDATE #__community_invitations set users =  CONCAT(`users`, '".','.$users."') where id = '".$invitation."'";
		}			
		$db->setQuery( $qry );
		$db->query();
		// update event table for invitedcount count..
		$friends = count($data['friends']);
		$qry = "UPDATE #__community_events set invitedcount =  (`invitedcount` + '".$friends."' ) where id = '".$data['event_id']."'";
		$db->setQuery( $qry );
		$db->query();			
				
		if ($flash == true) {			
		$msg = JText::_( 'INVITAION SENDED' );			
		} else {
			$msg = JText::_( 'ERROR IN INVITATION' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&controller=event&task=myevents';
		$this->setRedirect($link, $msg);
	}
	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('event');
		if(!$model->delete()) {
			$msg = JText::_( 'ERROR IN DELETE EVENT' );
		} else {
			$msg = JText::_( 'EVENT DELETE' );
		}
		$this->setRedirect( 'index.php?option=com_mojoom&controller=event&task=myevents', $msg );
	}
	
	function accept()
	{
		$data = JRequest::get( 'get' );
		$model = $this->getModel('event');
		if($model->accept()) {
			//activity stream
			
			$event	= $model->getEvent();
			CFactory::load( 'helpers' , 'url' );
			CFactory::load( 'libraries', 'activities' );
			$my				=	CFactory::getUser();
			// Activity stream purpose if the event is a public event
		
			$act = new stdClass();
			$act->cmd 		= 'event.join';
			$act->actor   	= $my->id;
			$act->target	= 0;
			$act->title	  	= JText::sprintf( '{actor} is attending <a href="{event_url}">%1$s</a>.' , $event->title );
			$act->content	= '';
			$act->cid		= $event->id;
			$act->app		= 'events';

			$params 		= new JParameter('');
			$action_str  	= 'event.join';
			$params->set( 'eventid' , $event->id);
			$params->set( 'action', $action_str );
			$params->set( 'event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);

			// Add activity logging
			CActivityStream::add( $act, $params->toString() );
		
			
			$msg = JText::_( 'ACCEPT INVITAION' );
		} else {
			$msg = JText::_( 'ERROR IN EVENT INVITATION' );
		}
		$this->setRedirect( 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id='.$data['event_id'].'', $msg );
	}
	function reject()
	{
		$data = JRequest::get( 'get' );
		$model = $this->getModel('event');
		if($model->reject()) {
			$msg = JText::_( 'REJECT INVITAION' );
		} else {
			$msg = JText::_( 'ERROR IN REJECT INVITAION' );
		}
		$this->setRedirect( 'index.php?option=com_mojoom&view=mojoom', $msg );
	}	
	
	function updatestatus()
	{
		$data = JRequest::get( 'post' );
		$flage = true;
		$db =& JFactory::getDBO();
		$my = JFactory::getUser();
		$date	=& JFactory::getDate();	
		// start of action part
		if($data['old_status'] != $data['status'])
		{
				if($data['old_status'] == "")
				{													
					$obj = new stdClass();
					$obj->id  = null;
					$obj->eventid = $data['eventid'];
					$obj->memberid 	 = $data['memberid'];
					$obj->status = $data['status'];
					$obj->permission	= 3;
					$obj->invited_by	= 0;
					$obj->approval = 0;
					$obj->created	= $date->toMySQL();				
					$db->insertObject('#__community_events_members', $obj, 'id');     			
					if($obj->id == "")
					{
						$flage = false;
					}
					else
					{
						// update the friends count
						if($data['status'] == 1) {	
						$qry = "UPDATE #__community_events set confirmedcount =  (`confirmedcount` + 1 ) where id = '".$data['eventid']."'";
						}elseif($data['status'] == 2){
						$qry = "UPDATE #__community_events set declinedcount  =  (`declinedcount` + 1 ) where id = '".$data['eventid']."'";
						}else{
						$qry = "UPDATE #__community_events set maybecount  =  (`maybecount` + 1 ) where id = '".$data['eventid']."'";
						}
						$db->setQuery( $qry );
						$db->query();	
					}						
				}
				// if user already enter his/her status and going to change status again than....
				else
				{
						$qry = "UPDATE #__community_events_members set status =  '".$data['status']."' where eventid = '".$data['eventid']."' and memberid 	='".$data['memberid']."'";					$db->setQuery( $qry );
						$status = $db->query();	
						if($status)
						{
							// update the friends count
							if($data['status'] == 1) {	
							$qry = "UPDATE #__community_events set confirmedcount =  (`confirmedcount` + 1 ) where id = '".$data['eventid']."'";
							}elseif($data['status'] == 2){
							$qry = "UPDATE #__community_events set declinedcount  =  (`declinedcount` + 1 ) where id = '".$data['eventid']."'";
							}else{
							$qry = "UPDATE #__community_events set maybecount  =  (`maybecount` + 1 ) where id = '".$data['eventid']."'";
							}
							$db->setQuery( $qry );
							$db->query();
							// update the friends old count
							if($data['old_status'] == 1) {	
							$qry = "UPDATE #__community_events set confirmedcount =  (`confirmedcount` - 1 ) where id = '".$data['eventid']."'";
							}elseif($data['old_status'] == 2){
							$qry = "UPDATE #__community_events set declinedcount  =  (`declinedcount` - 1 ) where id = '".$data['eventid']."'";
							}else{
							$qry = "UPDATE #__community_events set maybecount  =  (`maybecount` - 1 ) where id = '".$data['eventid']."'";
							}
							$db->setQuery( $qry );
							$db->query();
						}
						else
						{
							$flage = false;
						}
				}
		}
		// end of action part
		
		if ($flage == true) {			
			$msg = JText::_( 'RESPONSE SAVED' );			
		} else {
			$msg = JText::_( 'ERROR IN RESPONSE' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id='.$data['eventid'].'&group_id='.$data['groupid'].'';
		$this->setRedirect($link, $msg);
	}
	function eventcommentadd()
	{
	
		$model = & $this->getModel ( 'event_detail' );

		$msgData	= JRequest::get( 'POST' );
		$wallid = $model->SaveEventWall($msgData);
		if ($wallid) {
			//activity stream
			$event	= $model->getEvent($msgData['eventid']);
			CFactory::load( 'helpers' , 'url' );
			CFactory::load( 'libraries', 'activities' );
			$my				=	CFactory::getUser();
			// Activity stream purpose if the event is a public event
		
			$act = new stdClass();
			$act->cmd 		= 'events.wall.create';
			$act->actor   	= $my->id;
			$act->target	= 0;
			$act->title	  	= JText::sprintf( '{actor} added a new wall post in the event, <a href="{event_url}">%1$s</a>' , $event->title );
			$act->content	= $msgData['message'];
			$act->cid		= $msgData['eventid'];
			$act->app		= 'events';

			$params 		= new JParameter('');
			$action_str  	= 'events.wall.create';
			$params->set( 'event_url', 'index.php?option=com_community&view=events&task=viewevent&eventid=' . $event->id);
			$params->set( 'action', $action_str );
			$params->set('wallid', $wallid);
			// Add activity logging
			CActivityStream::add( $act, $params->toString() );
		
				$msg = JText::_('COMMENT ADD');
			
		} else {
			$msg = JText::_('ERROR IN COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=event&task=viewevent&event_id='.$msgData['eventid'].'',$msg);	 
	}
	function eventcommentadd_inner()
	{
		
		$model = & $this->getModel ( 'event_detail' );

		$msgData	= JRequest::get( 'POST' );
		
		if ($model->SaveEventWallInner($msgData)) {
			
				$msg = JText::_('COMMENT ADD');
			
		} else {
			$msg = JText::_('ERROR IN COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=event&task=viewevent&event_id='.$msgData['eventid'].'',$msg);	 
	}
	
	function removeeventwallcomment()
	{
	
		$model = & $this->getModel ( 'event_detail' );

		$Data	= JRequest::get( 'GET' );
		print_r($Data);
		if ($model->RemoveEventWallComment($Data)) {
			
				$msg = JText::_('DELETE COMMENT');
			
		} else {
			$msg = JText::_('ERROR IN DELETE COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=event&task=viewevent&event_id='.$Data['eid'].'',$msg);	 
		
		
	}
}