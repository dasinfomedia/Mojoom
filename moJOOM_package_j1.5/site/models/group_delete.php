<?php  
/**
 * Group Delete Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');
/* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
/* com_community component's template.php file included */
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'template.php');
class MojoomModelGroup_delete extends JModel
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
	/**
	 * Returns the count of the members of a specific group
	 *
	 * @access	public
	 * @param	string 	Group's id.
	 * @return	int	Count of members
	 */	 
	function getMembersCount( $id )
	{
		$db	=& $this->getDBO();

		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__community_groups_members') . ' '
					. 'WHERE groupid=' . $db->Quote( $id ) . ' '
					. 'AND ' . $db->nameQuote( 'approved' ) . '=' . $db->Quote( '1' );
			
		$db->setQuery( $query );	
		$count = $db->loadResult();
	
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
	
		return $count;
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
		$db		=& $this->getDBO();

		$query	= 'SELECT a.*, b.name AS ownername , c.name AS category FROM ' 
				. $db->nameQuote('#__community_groups') . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote('#__users') . ' AS b '
				. 'INNER JOIN ' . $db->nameQuote('#__community_groups_category') . ' AS c '
				. 'WHERE a.id=' . $db->Quote( $id ) . ' '
				. 'AND a.ownerid=b.id '
				. 'AND a.categoryid=c.id ';

		$db->setQuery( $query );
		$result	= $db->loadObject();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}

	/**
	 * Delete group's bulletin 
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupBulletins($gid)
	{
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_bulletins")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$db->quote($gid);
						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
	}
	/**
	 * Delete group's member
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupMembers($gid)
	{
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_members")." 
				WHERE 
						".$db->nameQuote("groupid")."=".$db->quote($gid);						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
	}
	/**
	 * Delete group's wall
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupWall($gid)
	{
		$db =& JFactory::getDBO();
				
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_wall")." 
				WHERE 
						".$db->nameQuote("contentid")." = ".$db->quote($gid)." AND
						".$db->nameQuote("type")." = ".$db->quote('groups');						
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}
		
		return true;
	}
	/**
	 * Delete group's discussion
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/
	function deleteGroupDiscussions($gid)
	{
		$db =& JFactory::getDBO();
	
		$sql = "SELECT 
						".$db->nameQuote("id")." 						
				FROM 
						".$db->nameQuote("#__community_groups_discuss")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$gid;						
		$db->setQuery($sql);
		$row = $db->loadobjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($row))
		{
			$ids_array = array();	
			foreach($row as $tempid)
			{
				array_push($ids_array, $tempid->id);
			}
			$ids = implode(',', $ids_array);
		}			
					
		$sql = "DELETE 
				
				FROM 
						".$db->nameQuote("#__community_groups_discuss")." 
				WHERE 
						".$db->nameQuote("groupid")." = ".$gid;				
		$db->setQuery($sql);
		$db->Query();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		if(!empty($ids))
		{				
			$sql = "DELETE 
					
					FROM 
							".$db->nameQuote("#__community_wall")." 
					WHERE 
							".$db->nameQuote("contentid")." IN (".$ids.") AND 
							".$db->nameQuote("type")." = ".$db->quote('discussions');				
			$db->setQuery($sql);
			$db->Query();
			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
			}
		}
		
		return true;
	}
	/**
	 * Delete group's media
	 * 
	 * param	string	id The id of the group.
	 * 	 
	 **/	
	function deleteGroupMedia($gid)
	{		
		$db 			=& JFactory::getDBO();
		$photosModel	= CFactory::getModel( 'photos' );
		$videoModel		= CFactory::getModel( 'videos' );
		
		// group's photos removal.
		$albums			=& $photosModel->getGroupAlbums($gid , false, false, 0);		
		foreach ($albums as $item)
		{
			$photos			= $photosModel->getAllPhotos($item->id, PHOTOS_GROUP_TYPE);
			
			foreach ($photos as $row)
			{
				$photo	=& JTable::getInstance( 'Photo' , 'CTable' );
				$photo->load($row->id);
				$photo->delete();
			}
			
			//now we delete group photo album folder
			$album	=& JTable::getInstance( 'Album' , 'CTable' );
			$album->load($item->id);
			$album->delete();
		}
		
		//group's videos
		CFactory::load('libraries', 'storage');
		CFactory::load('libraries','featured');
		$featuredVideo	= new CFeatured(FEATURED_VIDEOS);
		$videos			= $videoModel->getGroupVideos($gid);
		
		foreach($videos as $vitem)
		{
			if (!$vitem) continue;
				
			$video		= JTable::getInstance( 'Video' , 'CTable' );
			$videaId	= (int) $vitem->id;
						
			$video->load($videaId);
									
			if($video->delete())
			{
				// Delete all videos related data											
				$videoModel->deleteVideoWalls($videaId);				
				$videoModel->deleteVideoActivities($videaId);
								
				//remove featured video								
				$featuredVideo->delete($videaId);				
																
				//remove the physical file				
				$storage = CStorage::getStorage($video->storage);
				if ($storage->exists($video->thumb))
				{
					$storage->delete($video->thumb);
				}
								
				if ($storage->exists($video->path))
				{
					$storage->delete($video->path);
				}
			}
			
		}
		
		return true;
	}
	
	function deleteGroup( $groupId, $step=1 )
	{
		// step = 0 for all deletion
			
		//get the group data
		$group = $this->getGroup($groupId);
		$membersCount	= $this->getMembersCount($groupId);	
		

		CFactory::load( 'libraries' , 'activities' );

		$my				= CFactory::getUser();

		$isMine			= ($my->id == $group->ownerid);		

		// Delete all group bulletins
		if($this->deleteGroupBulletins($groupId)){echo 'done';}else{echo 'not done';}
		
		// Delete all group members
		if($this->deleteGroupMembers($groupId)){echo 'done';}else{echo 'not done';}
	
		// Delete all group wall
		if($this->deleteGroupWall($groupId)){echo 'done';}else{echo 'not done';}
		
		// Delete all group discussions
		if($this->deleteGroupDiscussions($groupId)){echo 'done';}else{echo 'not done';}
		
		// Delete all group's media files
		if($this->deleteGroupMedia($groupId)){echo 'done';}else{echo 'not done';}
		
		// Delete group
		$group	=& JTable::getInstance( 'Group' , 'CTable' );
		$group->load( $groupId );
		$groupData = $group;
					
		if( $group->delete( $groupId ) )
		{

			CFactory::load( 'libraries' , 'featured' );
			$featured	= new CFeatured('groups');
			$featured->delete($groupId);
			jimport( 'joomla.filesystem.file' );
			//@rule: Delete only thumbnail and avatars that exists for the specific group

			if($groupData->avatar != "components/com_community/assets/group.jpg" && !empty($groupData->avatar))
			{

				$path = explode('/', $groupData->avatar);

				$file = JPATH_ROOT . DS . $path[0] . DS . $path[1] . DS . $path[2] .DS . $path[3];

				if(JFile::exists($file))

				{

					JFile::delete($file);

				}

			}
			if($groupData->thumb != "components/com_community/assets/group_thumb.jpg" && !empty($groupData->thumb))

			{

				$path = explode('/', $groupData->thumb);

				$file = JPATH_ROOT . DS . $path[0] . DS . $path[1] . DS . $path[2] .DS . $path[3];

				if(JFile::exists($file))

				{

					JFile::delete($file);

				}

			}						

			// Remove from activity stream

			CActivityStream::remove('groups', $groupId);

		}

		else

		{

			echo JText::_('CC ERROR WHILE DELETING GROUP');

		}
	

		return true;
	
	}
	
	function leaveGroup($groupId,$step)
	{
		$my			= CFactory::getUser();
		
		$data		= new stdClass();

		$data->groupid	= $groupId;

		$data->memberid	= $my->id;
		
		$db	=& $this->getDBO();
		
		$strSQL	= 'DELETE FROM ' . $db->nameQuote('#__community_groups_members') . ' '
				. 'WHERE ' . $db->nameQuote('groupid') . '=' . $db->Quote( $data->groupid ) . ' '
				. 'AND ' . $db->nameQuote('memberid') . '=' . $db->Quote( $data->memberid );
		
		$db->setQuery( $strSQL );
		$db->query();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		$this->updateStats($groupId);
		return true;

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
}