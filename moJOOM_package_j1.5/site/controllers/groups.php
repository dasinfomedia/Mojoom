<?php
/**
 * Group Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class MojoomControllerGroups extends MojoomController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
	}
	function mygroups()
	{
		JRequest::setVar('view', 'groups');
		parent::display();
	}
	function myinvite()
	{
		JRequest::setVar('view', 'groupmyinvite');
		parent::display();
	}
	function create()
	{
		//JRequest::setVar('model', 'groups');
		JRequest::setVar('view', 'groupcreate');
		//$model		= $this->getModel('groups');
		parent::display();
	}
	function editgroup()
	{
		
		JRequest::setVar('view', 'groupcreate');
		parent::display();
	}
	function creategp()
	{
		$model = & $this->getModel ( 'pgroup' );

		$msgData		= JRequest::get( 'POST' );
		
		if ($gid = $model->store($msgData)) {
		
			CFactory::load( 'helpers' , 'url' );
			CFactory::load( 'libraries', 'activities' );
			$my				= CFactory::getUser();
			
			if($msgData['groupid'] == 0) {
				$group = $model->getGroup($gid);
				$msg = JText::_('GROUP CREATED NOTICE');
				//$msg = JText::sprintf('GROUP CREATED NOTICE', $group->name );
				$act = new stdClass();

				$act->cmd 		= 'group.create';

				$act->actor   	= $my->id;

				$act->target  	= 0;

				$act->title	  	= JText::sprintf('{actor} created a new group, <a href="%1$s">%2$s</a>' , '{group_url}' , $group->name );

				$act->content	= ( $group->approvals == 0) ? $group->description : '';

				$act->app		= 'groups';

				$act->cid		= $group->id;
				
				$params = new JParameter('');

				$params->set( 'action', 'group.create' );

				$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $gid ) , false )  );
				
				CActivityStream::add( $act, $params->toString() );

			}else{
				$msg = JText::_('GROUP UPDATED NOTICE');
				//$msg = JText::sprintf('GROUP UPDATED NOTICE', $group->name );
				// add this in to the activity
				// get the group name
				$group = $model->getGroup($msgData['groupid']);
				$act = new stdClass();

				$act->cmd 		= 'group.updated';

				$act->actor   	= $my->id;

				$act->target  	= 0;

				$act->title	  	= JText::sprintf('{actor} updated group, <a href="%1$s">%2$s</a>' , '{group_url}' , $group->name );

				$act->content	= '';

				$act->app		= 'groups';

				$act->cid		= $group->id;

				

				$params = new JParameter('');

				$params->set('group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $msgData['groupid'] ) , false ) );

				// Add activity logging

				CActivityStream::add( $act, $params->toString() );

				}
				
		} else {
			$msg = JText::_('ERROR IN GROUP');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=mygroups',$msg);	 
	}
	
	function viewmembers()
	{
		JRequest::setVar('view', 'groupmembers');
		parent::display();
	}
	
	function viewgroup()
	{
		JRequest::setVar('view', 'groupdetail');
		parent::display();
		
	}
	
	function joingroup()
	{
		JRequest::setVar('view', 'groupjoin');
		parent::display();
	}
	
	function savejoingroup()
	{
		$model = & $this->getModel ( 'pgroup' );
		$msgData = JRequest::get( 'POST' );
		
		$member	= $model->saveMember($msgData['group_id']);
		
		if( $member->approved )
		{
			$msg = JText::_('JOIN GROUP');
			
			// get the group name
			$group = $model->getGroup($msgData['group_id']);
			
			CFactory::load( 'helpers' , 'url' );
			CFactory::load( 'libraries', 'activities' );
			
			$act = new stdClass();

			$act->cmd 		= 'group.join';

			$act->actor   	= $member->memberid;

			$act->target  	= 0;

			$act->title	  	= JText::sprintf('{multiple}{actors}{/multiple}{single}{actor}{/single} joined the group <a href="%1$s">%2$s</a>' , '{group_url}' , $group->name );

			$act->content	= '';

			$act->app		= 'groups';

			$act->cid		= $group->id;
			
			$params = new JParameter('');

			$params->set( 'action' , 'groups.join');

			$params->set( 'group_url', CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
			
			CActivityStream::add( $act, $params->toString() );

		}
		else
		{
			$msg = JText::_( 'GROUP APPROVAL SHORTLY' ); 
		}
			
		$this->setredirect( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id='.$msgData['group_id'] ,$msg );
		
	}
	
	function invitefriends()
	{
		JRequest::setVar('view', 'groupinvitefrnds');
		parent::display();
	}
	
	function saveinvitefrnds()
	{
		$model = & $this->getModel ( 'pgroup' );
		$msgData = JRequest::get( 'POST' );
		$flag  = $model->storeInvitedFriends();
		if($flag)
		{
			$msg = JText::_('GROUP INVITATION SENT');
		}
		else
		{
			$msg = JText::_('ERROR IN GROUP INVITATION');
		}
		$this->setredirect( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id='.$msgData['group_id'] ,$msg );
		
	}
	
	function acceptinvite()
	{
		$model = & $this->getModel ( 'pgroup' );
		$msgData = JRequest::get( 'GET' );
		$gname = $model->storeAcceptedInvite($msgData['group_id']);
		if($gname)
		{
			$msg = JText::sprintf('GROUP INVITATION ACCEPT',$gname);
			//$msg = JText::sprintf('GROUP INVITATION ACCEPT', $gname );
			
		}
		else
		{
			$msg = JText::_('ERROR IN ACCEPT INVITATION');
		}
		$this->setredirect( 'index.php?option=com_mojoom&controller=groups&task=myinvite' ,$msg );
		
	}
	
	function rejecttinvite()
	{
		$model = & $this->getModel ( 'pgroup' );
		$msgData = JRequest::get( 'GET' );
		$gname = $model->storeRejectedInvite($msgData['group_id']);
		if($gname)
		{
			$msg = JText::sprintf('DECLINED GROUP INVITATION',$gname);
			//$msg = JText::sprintf('DECLINED GROUP INVITATION', $gname );
		}
		else
		{
			$msg = JText::_('ERROR IN DECLINED INVITATION');
		}
		$this->setredirect( 'index.php?option=com_mojoom&controller=groups&task=myinvite' ,$msg );
	}
	
	function groupdiscussion()
	{
		JRequest::setVar('view', 'groupdiscussion');
		parent::display();
	}
	function creatediscussion()
	{
		JRequest::setVar('view', 'groupdiscussioncreate');
		parent::display();
	}
	function savedisscussion()
	{
		$model = & $this->getModel ( 'groupdiscussion' );

		$msgData		= JRequest::get( 'POST' );
		$discussion = $model->store($msgData);
		if ($discussion) {
				
			CFactory::load( 'helpers' , 'url' );
			
			CFactory::load( 'libraries', 'activities' );
			
			$my				= CFactory::getUser();
			
			$model1 = & $this->getModel ( 'pgroup' );
			$group = $model1->getGroup($msgData['group_id']);
			$message = $msgData['message'];
			
			$act = new stdClass();

			$act->cmd 		= 'group.discussion.create';

			$act->actor 	= $my->id;

			$act->target 	= 0;

			$act->title		= JText::sprintf('{actor} started a new discussion, <a href="{topic_url}">{topic}</a> in <a href="%1$s">%2$s</a> group' , '{group_url}' , $group->name );

			$act->content	= $message;

			$act->app		= 'groups';

			$act->cid		= $group->id;

			

			$params				= new JParameter('');

			$params->set( 'action', 'group.discussion.create' );

			$params->set( 'topic_id', $discussion->id );

			$params->set( 'topic', $discussion->title );

			$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );

			$params->set( 'topic_url',  CUrl::build( 'groups' , 'viewdiscussion', array('groupid' =>$group->id, 'topicid' => $discussion->id), false) );

			

			CActivityStream::add( $act, $params->toString() );
		
			$msg = JText::_('DISCUSSION ADD');
			
		} else {
			$msg = JText::_('ERROR IN DISCUSSION');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=groupdiscussion&group_id='.$msgData['group_id'],$msg);	 
	}
	/**
	 * View method to display specific discussion from a group
	 *
	 * @access	public
	 * @param	Object	Data object passed from controller
	 */
	function viewdiscussion( )
	{
		JRequest::setVar('view', 'groupdiscussiondetail');
		parent::display();
	}
	
	function commentadd()
	{
	
		$model = & $this->getModel ( 'groupdiscussion' );

		$msgData		= JRequest::get( 'POST' );
		
		$wallid = $model->SaveDiscussionWall($msgData);
		
		if ($wallid) {
			
			CFactory::load( 'helpers' , 'url' );
			CFactory::load( 'libraries', 'activities' );
			$my	= CFactory::getUser();
			
			// get the discussion name
			$discussion = $model->getDiscussion($msgData['topic_id']);
			$message = $msgData['message'];
			//get group name
			$model1 = & $this->getModel ( 'pgroup' );
			$group = $model1->getGroup($msgData['group_id']);
			
			$act = new stdClass();

			$act->cmd 		= 'group.discussion.reply';

			$act->actor 	= $my->id;

			$act->target 	= 0;

			$act->title		= JText::sprintf('{actor} replied a discussion <a href="%1$s">%2$s</a> in <a href="{group_url}">{group_name}</a> group' , '{discuss_url}', $discussion->title );

			$act->content	= $message;

			$act->app		= 'groups';

			$act->cid		= $msgData['group_id'];

			

			$params = new JParameter('');

			$params->set( 'action', 'group.discussion.reply' );

			$params->set( 'wallid', $wallid);

			$params->set( 'group_url', 'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$msgData['group_id']);

			$params->set( 'group_name', $group->name);

			$params->set( 'discuss_url' , CUrl::build( 'groups' , 'viewdiscussion', array( 'groupid' => $discussion->groupid , 'topicid' => $discussion->id) , false ) );

		

			// Add activity log

			CActivityStream::add( $act, $params->toString() );

			
			$msg = JText::_('COMMENT ADD');
			
		} else {
			$msg = JText::_('ERROR IN COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=viewdiscussion&group_id='.$msgData['group_id'].'&topicid='.$msgData['topic_id'],$msg);	 
		
		
	}
	
	function groupcommentadd()
	{
	
		$model = & $this->getModel ( 'group_wall' );

		$msgData	= JRequest::get( 'POST' );
		
		$wallid = $model->SaveGroupWall($msgData);
		
		if ($wallid) {
		
			CFactory::load( 'helpers' , 'url' );

			CFactory::load ( 'libraries', 'activities' );
			
			$my				= CFactory::getUser();
			
			//get group name
			$model1 = & $this->getModel ( 'pgroup' );
			$group = $model1->getGroup($msgData['group_id']);
			$message = $msgData['message'];
			
			$act = new stdClass();

			$act->cmd 		= 'group.wall.create';

			$act->actor 	= $my->id;

			$act->target 	= 0;

			$act->title		= JText::sprintf('{actor} added a new wall post in the group, <a href="%1$s">%2$s</a>' , '{group_url}' , $group->name );

			$act->content	= $message;

			$act->app		= 'groups';

			$act->cid		= $msgData['group_id'];
			
			$params = new JParameter('');

			$params->set('action', 'group.wall.create');

			$params->set('wallid', $wallid);

			$params->set('group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $msgData['group_id'] ) , false ) );
			
			CActivityStream::add( $act, $params->toString() );
		
			$msg = JText::_('COMMENT ADD');
			
		} else {
			$msg = JText::_('ERROR IN COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=groupwall&user_id='.$msgData['user_id'].'&group_id='.$msgData['group_id'],$msg);	 
		
		
	}
	
	
	function groupcommentadd_inner()
	{
		
		$model = & $this->getModel ( 'group_wall' );

		$msgData	= JRequest::get( 'POST' );
		
		if ($model->SaveGroupWallInner($msgData)) {
			
				$msg = JText::_('COMMENT ADD');
			
		} else {
			$msg = JText::_('ERROR IN COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=groupwall&user_id='.$msgData['user_id'].'&group_id='.$msgData['group_id'],$msg);	 
		
		
	}
	
	function removegroupwallcomment()
	{
	
		$model = & $this->getModel ( 'group_wall' );

		$Data	= JRequest::get( 'GET' );

		if ($model->RemoveGroupWallComment($Data)) {
			
				$msg = JText::_('DELETE COMMENT');
			
		} else {
			$msg = JText::_('ERROR IN DELETE COMMENT');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=groupwall&user_id='.$Data['uid'].'&group_id='.$Data['gid'],$msg);	 
		
		
	}
	function groupdelete()
	{
		JRequest::setVar('view', 'groupdeleteconfirm');
		parent::display();
	
	}
	
	function groupdeletefinal()
	{
	
		$model = & $this->getModel ( 'group_delete' );

		$Data	= JRequest::get( 'POST' );
		$groupId = $Data['group_id'];
		$step = $Data['step'];
		
		if ($model->deleteGroup($groupId,$step)) {
				$msg = JText::_('DELETE GROUP');
			
		} else {
			$msg = JText::_('ERROR IN DELETE GROUP');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=mygroups',$msg);	 
		
	}
	
	function groupbulletin()
	{
		JRequest::setVar('view', 'groupbulletin');
		parent::display();	
	}
	function groupalbums()
	{
		JRequest::setVar('view', 'group_albums');
		parent::display();	
	}
	function groupvideo()
	{
		JRequest::setVar('view', 'group_video');
		parent::display();	
	}
	
	function groupwall()
	{
		JRequest::setVar('view', 'group_wall');
		parent::display();	
	}
		
	function createbulletin()
	{
		JRequest::setVar('view', 'groupbulletincreate');
		parent::display();
	}
	function savebulletin()
	{
		$model = & $this->getModel ( 'groupbulletin' );

		$msgData		= JRequest::get( 'POST' );
		
		$bulletin = $model->store($msgData);
		
		if ($bulletin) {
			
			CFactory::load( 'helpers' , 'url' );

			CFactory::load ( 'libraries', 'activities' );
			
			$my				= CFactory::getUser();
			
			//get group name
			$model1 = & $this->getModel ( 'pgroup' );
			$group = $model1->getGroup($msgData['group_id']);
			$message = $msgData['message'];
		
			$act = new stdClass();

			$act->cmd 		= 'group.news.create';

			$act->actor 	= $my->id;

			$act->target 	= 0;

			$act->title		= JText::sprintf('{actor} added a new bulletin, <a href="%1$s">%2$s</a>' , '{group_url}' , $bulletin->title );

			$act->content	= ( $group->approvals == 0 ) ? JString::substr( strip_tags( $bulletin->message ) , 0 , 100 ) : '';

			$act->app		= 'groups';

			$act->cid		= $bulletin->groupid;

			
			$params = new JParameter('');

			$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );
			

			CActivityStream::add( $act, $params->toString() );
		
			$msg = JText::_('BULLETIN ADD');
			
		} else {
			$msg = JText::_('ERROR IN BULLETIN');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=groupbulletin&group_id='.$msgData['group_id'],$msg);	 
	}
	function groupbulletindetail( )
	{
		JRequest::setVar('view', 'groupbulletindetail');
		parent::display();
	}
	function groupleave()
	{
		JRequest::setVar('view', 'groupleaveconfirm');
		parent::display();
	
	}
	function groupleavefinal()
	{
	
		$model = & $this->getModel ( 'group_delete' );

		$Data	= JRequest::get( 'POST' );
		$groupId = $Data['group_id'];
		$step = $Data['step'];
		
		if ($model->leaveGroup($groupId,$step)) {
				$msg = JText::_('LEAVE GROUP');
			
		} else {
			$msg = JText::_('ERROR IN LEAVE GROUP');
		}
		$this->setredirect('index.php?option=com_mojoom&controller=groups&task=mygroups',$msg);	 
		
	}
}