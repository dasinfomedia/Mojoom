<?php
/**
 * Inbox Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');
jimport('joomla.html.pagination');

class MojoomModelInbox extends JModel
{
	var $_pagination;
	var $_total;

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
		$mainframe =& JFactory::getApplication();
 	 	
 	 	// Get pagination request variables
 	 	$limit		= ($mainframe->
		getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
 	 	
 	 	// In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		 	 	
		$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);		
	}
	
	/**
	 * get pagination data
	 */	 	
	function getPagination()
	{
		return $this->_pagination;
	}
	
	/**
	 * get total data
	 */	 	
	function getTotal()
	{
		return $this->_total;
	}
	
	/**
	 * Return the conversation list
	 */	 	
	function &getInbox()
	{
	   	$my =& JFactory::getUser();
		$to = $my->id;
		
		if (empty($this->_data))
		{		    		
			$this->_data = array();

			$db =& $this->getDBO();
			
			$sql = "SELECT MAX(b.`id`) AS `bid`";
			$sql .= " FROM #__community_msg_recepient as a, #__community_msg as b"; 
			$sql .= " WHERE a.`to` = {$to}"; 
			$sql .= " AND b.`id` = a.`msg_id`"; 
			$sql .= " AND a.`deleted`=0"; 
			$sql .= " GROUP BY b.`parent`";
			$db->setQuery($sql);
			$tmpResult = $db->loadObjectList();			
			
			$strId = '';
			foreach ($tmpResult as $tmp)
			{
				if (empty($strId)) $strId = $tmp->bid;
				else $strId = $strId . ',' . $tmp->bid;
			}
			
			$result	= null;
			if( ! empty($strId) )
			{	
				$sql = "SELECT b.`id`, b.`from`, b.`parent`, b.`from_name`, b.`posted_on`, b.`subject`";
				$sql .= " FROM #__community_msg as b"; 
				$sql .= " WHERE b.`id` in (".$strId.")"; 			
				$sql .= " ORDER BY b.`posted_on` DESC";
				
				$db->setQuery($sql);
				$result = $db->loadObjectList();
				if($db->getErrorNum()) {
					JError::raiseError( 500, $db->stderr());
			    }
		    }
			
			// For each message, find the parent+from, group them together 
			$inboxResult =  array();
			if(!empty($result)){
				foreach($result as $row) {
					$inboxResult[$row->parent] = $row;
				}
			}
			
		    $limit 		= $this->getState('limit');
		    $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit );
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}
			
			return $inboxResult;
		}				
		
		return null;
	}
	/**
	 * Get unread message count for current user
	 * @param	int		parent message id
	 * @param	int		current user id
	 * @return  int     unread message count	 	 
	 */	 	
	function countUnRead($filter){
		 $db =& $this->getDBO();
		 $unRead = 0;
		 
		 // Skip the whole db query if no user specified
		 if(empty($filter['user_id']))
		 	return 0;
		 
		 $sql = "select count('1') as `unread_count`";
		 $sql .= " from #__community_msg_recepient";
		 $sql .= " where `is_read` = 0";
		 if(! empty($filter['parent']))
		     $sql .= " and `msg_parent` =" . $db->Quote($filter['parent']);		 
		 if(! empty($filter['user_id']))
		     $sql .= " and `to` =" . $db->Quote($filter['user_id']);		 		 
		 
		 $sql .= " and `deleted` = 0";

		 $db->setQuery($sql);		 
		 $result = $db->loadObject();
		 
		 if(! empty($result)){
		     $unRead = $result->unread_count;
		 }
		 
		 return $unRead;
	}
	
	
	function getDisplayName($id)
	{
		 $db = & $this->getDBO();
		 $query ="select name from #__users where id ='".$id."'";
		 $db->setQuery($query);
		 $name = $db->LoadResult();
		 return $name; 
	}
}
