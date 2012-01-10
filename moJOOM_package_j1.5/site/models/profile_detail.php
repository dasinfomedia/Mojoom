<?php

/**
 * Profile Detail Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelProfile_detail extends JModel
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

		$array = JRequest::getVar('user_id',  0, '', 'array');
		$this->setId((int)$array[0]);		
	}

	/**
	 * Method to set the training identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
	/**
	 * Gets the greeting
	 * @return string The greeting to be displayed to the user
	 */
	
	function getProfile()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		if($this->_id != "")
		{
			$userid = $this->_id; 
		}
		$db =& JFactory::getDBO();
		$query = "SELECT c.* FROM #__community_fields_values as c where c.user_id = '".$userid."' order by c.field_id asc";
		$db->setQuery( $query );
		$profile = $db->loadObjectlist();
		return $profile;
	}
	
	
}
