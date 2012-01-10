<?php
/** 
 * Group Invite Friend View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupinvitefrnds extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$my = JFactory::getUser();

		$groupid = JRequest::getVar('group_id',0);
		
		//get the list of friends for that we have used the friends model
		$friendsmodel = & JModel::getInstance('friend','MojoomModel');
		$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$filter		= JRequest::getWord( 'filter' , 'all' , 'GET' );
		$tmpFriends 	= $friendsmodel->getFriends($my->id , $sorted , false , $filter );
		//echo 'total friends:<pre>';
		//print_r($tmpFriends);
		
		$friends		= array();
		
		for( $i = 0; $i < count( $tmpFriends ); $i++ )
		{
			$friend			=& $tmpFriends[ $i ];
			if( !$model->isMember( $friend->id , $groupid ) && !$model->isInvited( $friend->id , $groupid ) )
			{
				$friends[]	= $friend;
			}
		}

		//echo 'friends that are not invited or member of the group';
		//print_r($friends);
		// get invitation table data
		$invitedFriendsdata = $model->getInvitation('groups,inviteUsers',$groupid);
		//print_r($invitedFriendsdata);
		$selected = $model->getInvitedUsers($invitedFriendsdata[0]);
		$i = 0;
		//print_r($selected);
		$showFriends = true;
		$showEmail = true;
		$callback = 'groups,inviteUsers';
		// assign data to the template
		$this->assignRef( 'group_id',	$groupid );
		$this->assignRef( 'friends',	$friends );
		$this->assignRef( 'selected',	$selected );
		$this->assignRef( 'showFriends' , $showFriends );
		$this->assignRef( 'showEmail' ,  $showEmail);
		$this->assignRef( 'callback',	$callback);
		
		parent::display($tpl);
	}
	
}