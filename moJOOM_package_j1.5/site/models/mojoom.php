<?php
/**
 * Mojoom Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

ini_set("display_errors","0");
jimport( 'joomla.application.component.model' );

class MojoomModelMojoom extends JModel
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
	 * Gets the greeting
	 * @return string The greeting to be displayed to the user
	 */
	
	function getProfile() 
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$data = JRequest::get( 'get' );
		$id =$userid;
		if($data['user_id'] != "")
		{
			$userid = $data['user_id']; 
		}
		
		if($userid != 0)
		{
			$db =& JFactory::getDBO();
			$query = "SELECT a.*,b.*,CASE WHEN c.connection_id IS NULL THEN 1 ELSE 0 END AS addfriend FROM #__users as a inner join #__community_users as b LEFT JOIN #__community_connection AS c ON c.connect_from= '".$id."' AND c.connect_to = a.id  AND c.status=1 where a.id = b.userid and a.id = '".$userid."'";
			
			$db->setQuery( $query );
			$profile = $db->loadObject();
			//print_r($query);
			return $profile;
		}
		else
		{
			return false;
		}

	}
	
	function store()
	{	
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );
		
		//print_r ($data);
		//exit;
		
		// Bind the form fields to the training table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the training record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}
	
	function status()
	{
		$data = JRequest::get( 'post' );
		$user =& JFactory::getUser();
		$db =& JFactory::getDBO();
		$userid = $user->get('id');
		$query = "Update #__community_users set status = '".$data['status']."' where userid = '".$userid."'";
		$db->setQuery( $query );
		$status = $db->Query();
		return $status;
		
	}
	/////// email notification ////////////
	function getNotification_msg()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();
		$query = "SELECT a.from_name , a.subject, a.posted_on , b.msg_from , b.to , b.msg_id , c.thumb FROM #__community_msg as a inner join #__community_msg_recepient as b on a.id = b.msg_id and b.is_read = 0 and b.deleted = 0 and b.to = '".$userid."' inner join #__community_users as c on b.msg_from = c.userid ";
		$db->setQuery( $query );
		$msg = $db->loadObjectlist();
		return $msg;		
	}
	/////// friend request notification ////////////
	function getNotification_frd()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();
		$query = "SELECT c.name , c.id , b.thumb FROM #__community_connection as a inner join #__community_users as b on a.connect_from = b.userid and a.status = 0 and a.connect_to = '".$userid."' inner join #__users as c on c.id = a.connect_from ";
		$db->setQuery( $query );
		$friends = $db->loadObjectlist();
		return $friends;		
	}
	/////// event notification ////////////
	function getNotification_event()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();
		$query = "SELECT b.id, b.title , b.thumb,c.name FROM #__community_events_members as a inner join #__community_events as b on a.eventid = b.id and a.status = 0 and a.memberid = '".$userid."' inner join #__users as c on b.creator = c.id";
		$db->setQuery( $query );
		$event = $db->loadObjectlist();
		return $event;		
	}
	//////// end of notification ///////////
	
	
}
