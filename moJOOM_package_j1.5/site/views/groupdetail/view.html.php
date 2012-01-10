<?php
/** 
 * Group Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');


class MojoomViewGroupdetail extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$my = JFactory::getUser();

		$groupid = JRequest::getVar('group_id',0);
		
		$group = $model->getGroup($groupid);
		// If user are invited
		$isInvited  =	$model->isInvited( $my->id, $groupid );
		$groupmembers = $model->getMembers($groupid);
		
		// Is there any my friend is the member of this group?
		$join		=   '';
		$friendsCount	=   0;
		if( $isInvited )
		{
		    // Get the invitors

		    // Get users friends in this group
		    $friendsCount   =	$model->getFriendsCount( $my->id, $groupid );
		}

		// Test if the current user is admin
		$isAdmin	    =	$model->isAdministrator( $my->id , $groupid );
		$isCommunityAdmin = $model->isCommunityAdmin( $my->id );
	
		// Test if the current browser is a member of the group
		$isMember	    =	$model->isMember( $my->id , $groupid );
		$waitingApproval    =	false;
		
		// If I have tried to join this group, but not yet approved, display a notice
		if( $model->isWaitingAuthorization( $my->id , $groupid ) )
		{
			$waitingApproval	= true;
		}
		// Get like
		$modelevent = & JModel::getInstance('event','MojoomModel');
		$Info	 = $modelevent->getInfo('groups',$groupid);
		
		$like = false;
		$dislike = false;
		$like = strpos($Info->like,$my->id.',');
		$dislike = strpos($Info->dislike,$my->id.',');
		
		$category		= $model->getGroupCategory($group->categoryid);
		$isMine		= ($my->id == $group->ownerid);
		
		// assign data to the template
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'isinvited',	$isInvited );
		$this->assignRef( 'groupmembers' , $groupmembers );
		$this->assignRef( 'friendscount' , $friendsCount );
		$this->assignRef( 'isadmin', $isAdmin );
		$this->assignRef( 'ismember' , $isMember );
		$this->assignRef( 'isCommunityAdmin' , $isCommunityAdmin );
		$this->assignRef( 'waitingapproval' , $waitingApproval );
		$this->assignRef( 'like',	$like );
		$this->assignRef( 'dislike',	$dislike );			
		$this->assignRef( 'category' ,$category);
		$this->assignRef( 'ismine' ,$isMine);
		
		parent::display($tpl);
	}
	
}