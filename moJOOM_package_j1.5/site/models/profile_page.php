<?php
/**
 * Profile Page Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelProfile_page extends JModel
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

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}
	
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function getProfile()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		
		if($userid != 0)
		{
			$db =& JFactory::getDBO();
			$query = "SELECT a.*,b.* FROM #__users as a inner join #__community_users as b where a.id = b.userid and a.id = '".$userid."'";
			$db->setQuery( $query );
			$profile = $db->loadObject();
			return $profile;
		}
		else
		{
			return false;
		}

	}
	
	function getNotification()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();
		$query = "SELECT a.from_name , a.subject, a.posted_on , b.msg_from , b.to , c.thumb FROM #__community_msg as a inner join #__community_msg_recepient as b on a.id = b.msg_id and b.is_read = 0 and b.deleted = 0 and b.to = '".$userid."' inner join #__community_users as c on b.to = c.userid ";
		echo $query;
		$db->setQuery( $query );
		$notification = $db->loadObjectlist();
		return $notification;
		
	}
	
	
}
