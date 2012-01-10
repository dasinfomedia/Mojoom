<?php  
/**
 * Group Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');

class MojoomModelPgroup extends JModel
{

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct() 
	{
		parent::__construct(); 
	} 
	function getGroupsCreationCount( $userId )
	{
		// guest obviously has no group
		if($userId == 0)
			return 0;
		
		$query	= 'SELECT COUNT(*) FROM ' 
				. $this->_db->nameQuote( '#__community_groups' ) . ' '
				. 'WHERE ' . $this->_db->nameQuote( 'ownerid' ) . '=' . $this->_db->Quote( $userId );				
		$this->_db->setQuery( $query );
		
		$count	= $this->_db->loadResult();
		
		return $count;
	}	
	
	function getGroupCategories()
	{
		$query	= 'SELECT * FROM ' 
				. $this->_db->nameQuote( '#__community_groups_category' );		
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();
		
		return $result;
	}
	function getGroupCategory($catid)
	{
		$query	= 'SELECT * FROM ' 
				. $this->_db->nameQuote( '#__community_groups_category' ) . 'WHERE id = ' . $catid;		
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();
		return $result;
	
	}
	
	function store()
	{
		$data = JRequest::get( 'post' );
		//print_r($data);
		
		$my	=& JFactory::getUser(); 
		$date	=& JFactory::getDate(); //get the time without any offset!
		$cDate	=$date->toMySQL();
		
		$obj = new stdClass();
		if( $data['groupid'] == 0)
		{
			$obj->id = '';
		}
		else
		{
			$obj->id = $data['groupid'];
		}			
		$obj->published = 1;
		$obj->ownerid = $my->id;
		$obj->categoryid 	= $data['categoryid'];
		$obj->name	= $data['name'];
		$obj->description		= $data['description'];
		$obj->approvals = $data['approvals'];
		$obj->created = $cDate;
		$obj->discusscount = 0;
		$obj->wallcount = 0;
		$obj->membercount = 1;
		$obj->params = "discussordering=".$data['discussordering']."\nphotopermission=".$data['photopermission']."\nvideopermission=".$data['videopermission']."\neventpermission=".$data['eventpermission']."\ngrouprecentphotos=".$data['grouprecentphotos']."\ngrouprecentvideos=".$data['grouprecentvideos']."\ngrouprecentevents=".$data['grouprecentevents']."\nnewmembernotification=".$data['newmembernotification']."\njoinrequestnotification=".$data['joinrequestnotification']."\nwallnotification=".$data['wallnotification'];
		if($data['groupid'] == 0 )
		{
			$this->_db->insertObject('#__community_groups', $obj, 'id');
		}
		else
		{
			//in update there is one more additional option for removing the activities for the group
			$removeActivity		= JRequest::getVar( 'removeactivities' , false , 'POST' );
			echo $removeActivity;
			if( $removeActivity )
			{
				$this->removeActivity( 'groups' ,$data['groupid'] );
			}
			$this->_db->updateObject('#__community_groups', $obj, 'id');
		}
		// aso do the entry in the community_groups_members as well
		if($data['groupid'] == 0 ) {
		$obj1 = new stdClass();
		$obj1->groupid = $obj->id;
		$obj1->memberid = $my->id;
		$obj1->approved = 1;
		$obj1->permissions = 1;
		$this->_db->insertObject('#__community_groups_members', $obj1,'');
		}
		return $obj->id;
	}
	
	/**
	 * Returns All the groups
	 *
	 * @access	public
	 * @param	string 	Category id
	 * @param	string	The sort type
	 * @param	string	Search value
	 * @return	Array	An array of group objects
	 */	 
	function getAllGroups( $categoryId = null , $sorting = null , $search = null , $skipDefaultAvatar = false )
	{
		$extraSQL	= '';
		$pextra		= '';
		
		
		// Test if search is parsed
		if( !is_null( $search ) )
		{
			$extraSQL	.= " AND a.name LIKE " . $this->_db->Quote( '%' . $search . '%' ) . " ";
		}

		if( $skipDefaultAvatar )
		{
			$extraSQL	.= ' AND ( a.thumb != ' . $this->_db->Quote( 'components/com_community/assets/group_thumb.jpg' ) . ' AND a.avatar != ' . $this->_db->Quote('components/com_community/assets/group.jpg') . ' )';
		}
		$order	=''; 
		switch ( $sorting )
		{
			case 'alphabetical':
				$order		= ' ORDER BY a.name ASC ';
				break;
			case 'mostdiscussed':
				$order	= ' ORDER BY discusscount DESC ';
				break;
			case 'mostwall':
				$order	= ' ORDER BY wallcount DESC ';
				break;
			case 'mostmembers':
				$order	= ' ORDER BY membercount DESC ';
				break;
			default:
				$order	= 'ORDER BY a.created DESC ';
				break;
		}

		if( !is_null($categoryId) && $categoryId != 0 )
		{
			$extraSQL	.= ' AND a.categoryid=' . $this->_db->Quote($categoryId) . ' ';
		}
		
		if ($sorting == 'mostactive')
		{
			$query = " SELECT *, 
							".$this->_db->nameQuote('cid').", 
							COUNT(".$this->_db->nameQuote('cid').") AS ".$this->_db->nameQuote('count')." 
					   FROM 
							".$this->_db->nameQuote('#__community_activities')." AS a
				 INNER JOIN	".$this->_db->nameQuote('#__community_groups')." AS b ON a.".$this->_db->nameQuote('cid')." = b.".$this->_db->nameQuote('id')."
					  WHERE 
							a.".$this->_db->nameQuote('app')." = ".$this->_db->quote('groups')." AND
							b.".$this->_db->nameQuote('published')." = ".$this->_db->quote('1')." AND
							a.".$this->_db->nameQuote('archived')." = ".$this->_db->quote('0')." AND
							a.".$this->_db->nameQuote('cid')." != ".$this->_db->quote('0')." 
				   GROUP BY a.".$this->_db->nameQuote('cid')."
				   ORDER BY ".$this->_db->nameQuote('count')." DESC";
		}
		else
		{
			$query = "SELECT * FROM #__community_groups as a WHERE a.`published`='1' "
					. $extraSQL 
					. $order;
		}
		
		$this->_db->setQuery( $query );
		$rows	= $this->_db->loadObjectList();


		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		
		$query	= 'SELECT COUNT(*) FROM #__community_groups AS a '
				. 'WHERE a.published=' . $this->_db->Quote( '1' )
				. $extraSQL;
		
		$this->_db->setQuery( $query );
		$total	= ($sorting == 'mostactive') ? count($rows) : $this->_db->loadResult();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}		
		return $rows;
	}
	/**
	 * Returns an object of groups which the user has registered.
	 * 	 	
	 * @access	public
	 * @param	string 	User's id.
	 * @returns array  An objects of custom fields.	 
	 * @todo: re-order with most active group stays on top	 
	 */	 
	function getMyGroups( $userId = null , $sorting = null )
	{
		$extraSQL	= '';
		
		
		if( !is_null($userId) )
		{
			$extraSQL	= ' AND b.memberid=' . $this->_db->Quote($userId);
		}

		$orderBy	= '';

		switch($sorting)
		{
			case 'mostmembers':
				// Get the groups that this user is assigned to
				$query		= 'SELECT a.id FROM ' . $this->_db->nameQuote('#__community_groups') . ' AS a '
							. 'LEFT JOIN ' . $this->_db->nameQuote('#__community_groups_members') . ' AS b '
							. 'ON a.id=b.groupid '
							. 'WHERE b.approved=' . $this->_db->Quote( '1' )
							. $extraSQL; 

				$this->_db->setQuery( $query );
				$groupsid		= $this->_db->loadResultArray();
				
				if($this->_db->getErrorNum())
				{
					JError::raiseError( 500, $this->_db->stderr());
				}
				
				if( $groupsid )
				{
					$groupsid		= implode( ',' , $groupsid );
	
					$query			= 'SELECT a.* '
									. 'FROM ' . $this->_db->nameQuote('#__community_groups') . ' AS a '
									. 'WHERE a.published=' . $this->_db->Quote( '1' ) . ' '
									. 'AND a.id IN (' . $groupsid . ') '
									. 'ORDER BY a.membercount DESC ';
										
				}
				break;
			case 'mostdiscussed':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.discusscount DESC ';
			case 'mostwall':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.wallcount DESC ';
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= 'ORDER BY a.name ASC ';
			case 'mostactive': 
				//@todo: Add sql queries for most active group
			default:
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.created DESC ';

				$query	= 'SELECT a.* FROM '
						. $this->_db->nameQuote('#__community_groups') . ' AS a '
						. 'INNER JOIN ' . $this->_db->nameQuote('#__community_groups_members') . ' AS b ON a.id=b.groupid '
						. 'AND b.approved=' . $this->_db->Quote( '1' ) . ' '
						. 'AND a.published=' . $this->_db->Quote( '1' ) . ' '
						. $extraSQL
						. $orderBy;
						
				break;
		}  
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		
		return $result;
	}
	/**
	 * Returns All the group members list
	 *
	 * @access	public
	 * @param	string 	group id
	 * @return	Array	An array of group objects
	 */	 
	function getMembers( $groupid , $limit = 0 , $onlyApproved = true , $randomize = false , $loadAdmin = false )
	{
		$my = JFactory::getUser();
		$query	= 'SELECT a.memberid AS id, a.approved , b.name as name FROM '
				. $this->_db->nameQuote('#__community_groups_members') . ' AS a '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__users') . ' AS b '
				. 'WHERE b.id=a.memberid '
				. 'AND a.groupid=' . $this->_db->Quote( $groupid ) . ' '
				. 'AND b.block=' . $this->_db->Quote( '0' ) . ' '
				. 'AND a.permissions !=' . $this->_db->quote( '-1' );
		
		if( $onlyApproved )
		{
			$query	.= ' AND a.approved=' . $this->_db->Quote( '1' );
		}
		else
		{
			$query	.= ' AND a.approved=' . $this->_db->Quote( '0' );
		}		
		if( $randomize )
		{
			$query	.= ' ORDER BY RAND() ';
		}
		else
		{
			$query	.= ' ORDER BY b.`' . 'name' . '`';
		}
	
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();
		

		if($this->_db->getErrorNum()) {
			JError::raiseError( 500, $this->_db->stderr());
		}
		
		$membersList = array();
		foreach($result as $member)
		{
			$user				= JFactory::getUser( $member->id );

			$user->thumb 		= $this->_getUserInfo( $member->id, 'thumb');
			$user->friendsCount	= $this->_getUserInfo( $member->id, 'friendcount' );
			$user->status 		= $this->_getUserInfo( $member->id, 'status' );
			$user->approved		= $member->approved;
			$user->isMe			= ( $my->id == $member->id ) ? 1 : 0;
			$user->isAdmin		= $this->isAdministrator( $user->id , $groupid ); 
			$user->isOwner      = $user->isAdmin;
			$user->isOnline		= $this->_isOnline($member->id);
	
			$membersList[] 		= $user; 
		}
		return $membersList;
	}
	
	function _getUserInfo( $userid , $field )
	{
		 $query ="select $field from #__community_users where userid ='".$userid."'";
		 $this->_db->setQuery($query);
		 $value = $this->_db->LoadResult();
		 return $value; 
	}
	
	/**
	 * Check if the user is a group admin 
	 */	 	
	function isAdministrator($userid, $groupid)
	{
		if($userid == 0)
			return false;

		$query	= 'SELECT ownerid FROM ' . $this->_db->nameQuote( '#__community_groups' ) . ' '
					. 'WHERE ' . $this->_db->nameQuote( 'id' ) . '=' . $this->_db->Quote( $groupid ) . ' '
					. 'AND ' . $this->_db->nameQuote( 'ownerid' ) . '=' . $this->_db->Quote( $userid );
		$this->_db->setQuery( $query );
			
		$isAdmin	= ( $this->_db->loadResult() >= 1 ) ? 1 : 0;

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}		
		return $isAdmin;
	}
	/**
	 * Returns an object of group
	 * 	 	
	 * @access	public
	 * @param	string 	Group Id
	 * @returns object  An object of the specific group	 
	 */
	function getGroup( $id )
	{
		$query	= 'SELECT a.*, b.name AS ownername , c.name AS category FROM ' 
				. $this->_db->nameQuote('#__community_groups') . ' AS a '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__users') . ' AS b '
				. 'INNER JOIN ' . $this->_db->nameQuote('#__community_groups_category') . ' AS c '
				. 'WHERE a.id=' . $this->_db->Quote( $id ) . ' '
				. 'AND a.ownerid=b.id '
				. 'AND a.categoryid=c.id ';

		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}

		return $result;
	}
	
	function getParams($id)
	{
		$query	= 'SELECT params FROM ' . $this->_db->nameQuote('#__community_groups') . 'WHERE id=' . $id; 
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		$params	= new JParameter( $result->params );

		return $params;
	}
	
	function _isOnline($userid)
	{	
		$query = "select userid from #__session where userid = ".$userid;
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		if($result->userid != '')
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	/**
	 * Check if the user is invited in the group
	 */
	function isInvited($userid, $groupid)
	{
		if($userid == 0)
		{
		    return 0;
		}
		
		$query	=   'SELECT * FROM ' . $this->_db->nameQuote('#__community_groups_invite') . ' '
			    . 'WHERE ' . $this->_db->nameQuote( 'groupid' ) . '=' . $this->_db->Quote( $groupid ) . ' '
			    . 'AND ' . $this->_db->nameQuote( 'userid' ) . '=' . $this->_db->Quote( $userid );

		$this->_db->setQuery( $query );

		$isInvited	= ( $this->_db->loadResult() >= 1 ) ? 1 : 0;

		return $isInvited;
	}
	/**
	 * Return an object of group's invitors
	 */
	function getInvitors( $userid, $groupid )
	{
		if($userid == 0)
		{
		    return false;
		}

		$query	=   'SELECT DISTINCT(' . $this->_db->nameQuote( 'creator' ) . ') FROM ' . $this->_db->nameQuote('#__community_groups_invite') . ' '
			    . 'WHERE ' . $this->_db->nameQuote( 'groupid' ) . '=' . $this->_db->Quote( $groupid ) . ' '
			    . 'AND ' . $this->_db->nameQuote( 'userid' ) . '=' . $this->_db->Quote( $userid );

		$this->_db->setQuery( $query );

		$results  =	$this->_db->loadObjectList();

		return $results;
	}
	/**
	 * Return the count of the user's friend of a specific group
	 */
	function getFriendsCount( $userid, $groupid )
	{
		$query	=   'SELECT COUNT(DISTINCT(a.connect_to)) AS id  FROM ' . $this->_db->nameQuote('#__community_connection') . ' AS a '
			    . 'INNER JOIN ' . $this->_db->nameQuote( '#__users' ) . ' AS b '
			    . 'INNER JOIN ' . $this->_db->nameQuote( '#__community_groups_members' ) . ' AS c '
			    . 'ON a.connect_from=' . $this->_db->Quote( $userid ) . ' '
			    . 'AND a.connect_to=b.id '
			    . 'AND c.groupid=' . $this->_db->Quote( $groupid ) . ' '
			    . 'AND a.connect_to=c.memberid '
			    . 'AND a.status=' . $this->_db->Quote( '1' ) . ' '
			    . 'AND c.approved=' . $this->_db->Quote( '1' );

		$this->_db->setQuery( $query );

		$total = $this->_db->loadResult();

		return $total;
	}
	/**
	 * Check if the given user is a member of the group
	 * @param	string	userid	
	 * @param	string	groupid	 	 
	 */	 	
	function isMember($userid, $groupid) {
		
		// guest is not a member of any group
		if($userid == 0)
			return false;
		$strSQL	= 'SELECT COUNT(*) FROM ' . $this->_db->nameQuote('#__community_groups_members') . ' '
				. 'WHERE ' . $this->_db->nameQuote('groupid') . '=' . $this->_db->Quote($groupid) . ' '
				. 'AND ' . $this->_db->nameQuote('memberid') . '=' . $this->_db->Quote($userid)
				. 'AND ' . $this->_db->nameQuote( 'approved' ) .'=' . $this->_db->Quote( '1' );
				
		$this->_db->setQuery( $strSQL );
		$count	= $this->_db->loadResult();
		return $count;
	}
	/**
	 * See if the given user is waiting authorization for the group
	 * @param	string	userid	
	 * @param	string	groupid	 	 
	 */	 	
	function isWaitingAuthorization($userid, $groupid) {
		// guest is not a member of any group
		if($userid == 0)
			return false;
		
		$strSQL	= 'SELECT COUNT(*) FROM `#__community_groups_members` '
				. 'WHERE ' . $this->_db->nameQuote('groupid') . '=' . $this->_db->Quote($groupid) . ' '
				. 'AND ' . $this->_db->nameQuote('memberid') . '=' . $this->_db->Quote($userid)
				. 'AND ' . $this->_db->nameQuote('approved') . '=' . $this->_db->Quote(0);
				
		$this->_db->setQuery( $strSQL );
		$count	= $this->_db->loadResult();
		return $count;
	}
	
	function removeActivity( $app , $uniqueId )
	{

		$query	= 'DELETE FROM ' . $this->_db->nameQuote( '#__community_activities' ) . ' '
				. 'WHERE ' . $this->_db->nameQuote( 'app' ) . '=' . $this->_db->Quote( $app ) . ' '
				. 'AND ' . $this->_db->nameQuote( 'cid' ) . '=' . $this->_db->Quote( $uniqueId ) ;
				
		$this->_db->setQuery( $query );
		$status	= $this->_db->query();
		
		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		return $status;

	}
	
	function saveMember( $groupId )
	{
	
				
		$group		= $this->getGroup( $groupId );
		
		$params		= $this->getParams($groupId);

		$my			= JFactory::getUser();

		

		// Set the properties for the members table
		$member = new stdClass();
		$member->groupid	= $group->id;
		$member->memberid	= $my->id;
		$member->approved	= ( $group->approvals == 1 ) ? 0 : 1;
		if($this->isCommunityAdmin($my->id))
		{
 			$member->permissions= '1';
		}
		else
		{
			$member->permissions= '0';
		}

		$this->_db->insertObject('#__community_groups_members', $member,'');

		/*$owner	= CFactory::getUser( $group->ownerid );

		

		//trigger for onGroupJoin

		$this->triggerGroupEvents( 'onGroupJoin' , $group , $my->id);



		// Test if member is approved, then we add logging to the activities.

		if( $member->approved )

		{

			$act = new stdClass();

			$act->cmd 		= 'group.join';

			$act->actor   	= $my->id;

			$act->target  	= 0;

			$act->title	  	= JText::sprintf('CC ACTIVITIES GROUP JOIN' , '{group_url}' , $group->name );

			$act->content	= '';

			$act->app		= 'groups';

			$act->cid		= $group->id;

			

			$params = new JParameter('');

			$params->set( 'group_url' , CUrl::build( 'groups' , 'viewgroup' , array( 'groupid' => $group->id ) , false ) );

			

			// Add logging

			CFactory::load ( 'libraries', 'activities' );

			CActivityStream::add($act, $params->toString() );

			

			//add user points

			CFactory::load( 'libraries' , 'userpoints' );		

			CUserPoints::assignPoint('group.join');	

			

			// Store the group and update stats

			$group->updateStats();

			$group->store();

		}*/
		$this->updateStats($groupId);
		return $member;

	}
	
	function updateStats( $groupId )
	{
		
			
		// @rule: Update the members count each time stored is executed.
		$query	= 'SELECT COUNT(1) FROM ' . $this->_db->nameQuote( '#__community_groups_members' ) . ' '
				. 'WHERE groupid=' . $this->_db->Quote( $groupId ) . ' '
				. 'AND approved=' . $this->_db->Quote( '1' );

		$this->_db->setQuery( $query );
		$obj2 = new stdClass();
		$obj2->id = $groupId;
		$obj2->membercount	= $this->_db->loadResult();
		$this->_db->updateObject('#__community_groups', $obj2, 'id');
		// @rule: Update the discussion count each time stored is executed.
	}
	/**
	 * Override parent's method as the loading method will be based on the
	 * unique callback and cid
	 **/	 	 
	public function getInvitation( $callback , $groupid )
	{
		
		$query	= 'SELECT * FROM ' . $this->_db->nameQuote( '#__community_invitations' ) . ' WHERE '
				. $this->_db->nameQuote( 'callback' ) . '=' . $this->_db->Quote( $callback ) . ' '
				. 'AND ' . $this->_db->nameQuote( 'cid' ) . '=' . $this->_db->Quote( $groupid );
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		return $result;
	}
	
	/**
	 * Retrieves invited members from this table
	 * 
	 * @return	Array	$users	An array containing user id's	 	 
	 **/
	public function getInvitedUsers($results)
	{
		$pos = '';
		$users = '';
		$pos = strpos($results->users,',');
		if($pos == '')
			$users[0] = $results->users;
		else
			$users	= explode( ',' , $results->users );
		return $users;
	}
	/*** function to save the invite friends to join the group */
	function storeInvitedFriends()
	{
		$data = JRequest::get( 'post' );
		/*print_r($data);
		echo $data['group_id'];
		exit;*/
		
		$my = JFactory::getUser();
		$date	=& JFactory::getDate();	
		$i=0;
		$flash = true;
		while($data['friends'][$i] != "")
		{
			$obj = new stdClass();
			$obj->groupid  = $data['group_id'];
			$obj->userid = $data['friends'][$i];
			$obj->creator = $my->id;
			$this->_db->insertObject('#__community_groups_invite', $obj, '');     
			$i++;
			
			if($obj->groupid == "")
			{
				$flash = false;
			}			
		}
			
		// insert into invitation table also			
		$qry = "select id from #__community_invitations where callback = '".$data['callback']."' and cid = '".$data['group_id']."'";
		$this->_db->setQuery( $qry );
		$invitation = $this->_db->loadResult();
		$users = implode(',',$data['friends']);
		if($invitation == "")
		{
			$qry = "INSERT into #__community_invitations(`callback`,`cid`,`users`) values('".$data['callback']."','".$data['group_id']."','".$users."');";
		}
		else
		{
			$qry = "UPDATE #__community_invitations set users =  CONCAT(`users`, '".','.$users."') where id = '".$invitation."'";
		}			
		$this->_db->setQuery( $qry );
		$this->_db->query();
	
		return flash; 
	}

	function getGroupInvites( $userId , $sorting = null )
	{
		$extraSQL	= ' AND a.userid=' . $this->_db->Quote($userId);
		$orderBy	= '';		
		switch($sorting)
		{
			
			case 'mostmembers':
				// Get the groups that this user is assigned to
				$query		= 'SELECT a.groupid FROM ' . $this->_db->nameQuote('#__community_groups_invite') . ' AS a '
							. 'LEFT JOIN ' . $this->_db->nameQuote('#__community_groups_members') . ' AS b '
							. 'ON a.groupid=b.groupid '
							. 'WHERE b.approved=' . $this->_db->Quote( '1' )
							. $extraSQL; 

				$this->_db->setQuery( $query );
				$groupsid		= $this->_db->loadResultArray();
				
				if($this->_db->getErrorNum())
				{
					JError::raiseError( 500, $this->_db->stderr());
				}
				
				if( $groupsid )
				{
					$groupsid		= implode( ',' , $groupsid );
	
					$query			= 'SELECT a.* '
									. 'FROM ' . $this->_db->nameQuote('#__community_groups_invite') . ' AS a '
									. 'INNER JOIN #__community_groups AS b '
									. 'ON a.groupid=b.id '
									. 'WHERE a.groupid IN (' . $groupsid . ') '
									. 'ORDER BY b.membercount DESC '
									. 'LIMIT ' . $limitstart . ',' . $limit;	
				}
				break;
			case 'mostdiscussed':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.discusscount DESC ';
			case 'mostwall':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.wallcount DESC ';
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= 'ORDER BY b.name ASC ';
			case 'mostactive':
				//@todo: Add sql queries for most active group
			
			default:
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY b.created DESC ';

				$query	= 'SELECT distinct a.* FROM '
						. $this->_db->nameQuote('#__community_groups_invite') . ' AS a '
						. 'INNER JOIN ' . $this->_db->nameQuote( '#__community_groups' ) . ' AS b ON a.groupid=b.id '
						. 'INNER JOIN ' . $this->_db->nameQuote('#__community_groups_members') . ' AS c ON a.groupid=c.groupid '
						. 'AND c.approved=' . $this->_db->Quote( '1' ) . ' '
						. 'AND b.published=' . $this->_db->Quote( '1' ) . ' '
						. $extraSQL
						. $orderBy;
				break;
		}

		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		return $result;
	}
	
	function storeAcceptedInvite($groupid)
	{
		$my	= JFactory::getUser();
		
		$member = new stdClass();
		$member->groupid	= $groupid;
		$member->memberid	= $my->id;
		$member->approved	=  1;
 		$member->permissions= '0';
		$this->_db->insertObject('#__community_groups_members', $member,'');
		$this->updateStats($groupid);
		$delflag = $this->deleteInviteEntry($groupid,$my->id);
		$grp = $this->getGroup( $groupid );
		return $grp->name;
		
	}
	function storeRejectedInvite($groupid)
	{
		$my	= JFactory::getUser();
		$delflag = $this->deleteInviteEntry($groupid,$my->id);
		$grp = $this->getGroup( $groupid );
		return $grp->name;
	}
	
	/**
	 * Deletes the entry from the community_groups_invite table  after accepting or rejecting the invite 	 
	 **/
	 function deleteInviteEntry($groupid,$userid)
	{
		
		$query	= 'DELETE FROM ' . $this->_db->nameQuote( '#__community_groups_invite' ) . ' WHERE '
				. '`groupid`=' . $this->_db->Quote( $groupid ) . ' AND '
				. '`userid`=' . $this->_db->Quote( $userid );
		$this->_db->setQuery( $query );
		return $this->_db->Query();
	}
	
	function isCommunityAdmin($userid = null)

	{

		$my	= JFactory::getUser($userid);		

		return ( $my->usertype == 'Super Administrator' || $my->usertype == 'Administrator' || $my->usertype == 'Manager' );

	}

}