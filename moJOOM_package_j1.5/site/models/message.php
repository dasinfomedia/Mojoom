<?php
/**
 * Message Model for Mojoom Component
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

class MojoomModelMessage extends JModel
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
	
	function getUser($id)
	{
		$db =& $this->getDBO();
				
		$sql = "select name,id from #__users where id = '".$id."'";
		$db->setQuery($sql);
		$result = $db->loadRow();
		return $result;
	}
	
	function send($vars)
	{	    
		$db =& $this->getDBO();
		$my	=& JFactory::getUser();   
		
		// @todo: user db table later on				
		//$cDate =& JFactory::getDate(gmdate('Y-m-d H:i:s'), $mainframe->getCfg('offset'));//get the current date from system.
		//$date	= cGetDate();
		$date	=& JFactory::getDate(); //get the time without any offset!
		$cDate	=$date->toMySQL();
		 
		$obj = new stdClass();
		$obj->id = null;
		$obj->from = $my->id;
		$obj->posted_on = $date->toMySQL();
		$obj->from_name	= $my->name;
		$obj->subject	= $vars['subject'];
		$obj->body		= $vars['body'];
		
		// Don't add message if user is sending message to themselve	
		if( $vars['to']!=$my->id ){
		
			$db->insertObject('#__community_msg', $obj, 'id');     
			
			// Update the parent
			$obj->parent = $obj->id;
			$db->updateObject('#__community_msg', $obj, 'id');
		}	
		
		if(is_array($vars['to'])){
		
		    //multiple recepint
		    foreach($vars['to'] as $sToId){
		    	if( $vars['to']!=$my->id )
		        	$this->addReceipient($obj, $sToId); 
		    }		    
		} else {
		
		    //single recepient
		    if( $vars['to']!=$my->id )
		    	$this->addReceipient($obj, $vars['to']);
		}    
		
		return $obj->id;
	}
	
	/**
	 * Add receipient
	 */	 	
	function addReceipient($msgObj, $recepientId){   
		$db =& $this->getDBO();
		$my	=& JFactory::getUser(); 
	        
		$recepient = new stdClass();
		$recepient->msg_id = $msgObj->id;
		$recepient->msg_parent = $msgObj->parent;
		$recepient->msg_from = $msgObj->from;
		$recepient->to	= $recepientId;
		
		if( $my->id != $recepientId )		
			$db->insertObject('#__community_msg_recepient', $recepient);
		
		if($db->getErrorNum()) {
		     JError::raiseError( 500, $db->stderr());
	    }
	}
	
	/**
	 * Return list of sent items
	 */	 	
	function &getSent()
	{
	    $my =& JFactory::getUser();
		$from = $my->id;
		
		if (empty($this->_data))
		{		    		
			$this->_data = array();

			$db =& $this->getDBO();
				
			$sql = "SELECT b.*, a.`to`, c.`name` as `to_name` "
				." FROM #__community_msg_recepient as a, "
				." #__community_msg as b, #__users c "
				." WHERE "
				." b.`from` = {$from} AND "
				." b.`deleted`=0 AND"
				." b.`id` = a.`msg_id` AND"
				." a.`to` = c.`id`"				
				." ORDER BY b.`posted_on` DESC";

			$db->setQuery($sql);
			$result = $db->loadObjectList();

			if($db->getErrorNum())
			{
				JError::raiseError( 500, $db->stderr());
		    }
			
			// For each message, find the parent+from, group them together
			$inboxResult	=  array();
			$inToName 		=  array();
			$inToId   		=  array();

			if(!empty($result))
			{
				foreach($result as $row)
				{
					if( !isset( $inboxResult[ $row->parent ] ) )
					{
						$inToName[$row->parent][$row->to_name] = $row->to_name;
						$inToId[$row->parent][$row->to]		= $row->to;
						$inboxResult[$row->parent]				= $row;
					} 
				}
			}
			
			//now rewrite back the to / to_name
			foreach($inboxResult as $row)
			{
			   $inboxResult[$row->parent]->to = $inToId[$row->parent];
			   $inboxResult[$row->parent]->to_name = $inToName[$row->parent];
			}

		    $limit 		= $this->getState('limit');
		    $limitstart	= $this->getState('limitstart');
		    
			if(empty($this->_pagination))
			{
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit );
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}			
			
			return $inboxResult;
		}				
		
		return null;
	}

	// get the detail message
	
	function &getMessages($filter = array())
	{
	
	    $my =& JFactory::getUser();
	    $db =& $this->getDBO();	
	    
		if (empty($this->_data))
		{
			$this->_data = array();			
				
		    $sql = "SELECT a.*, b.`to`, b.`deleted` as `to_deleted`, b.`is_read` , c.name as to_name"
				." FROM #__community_msg a, #__community_msg_recepient b , #__users as c "
				." where a.`parent` = " . $db->Quote($filter['msgId'])
				." and  b.`msg_parent` = " . $db->Quote($filter['msgId'])
				." and  a.`id` = b.`msg_id`"
				." and  b.`to` = c.`id`"
				." order by a.`id` desc, a.`deleted` desc, b.`deleted` desc";			

			$db->setQuery($sql);
			if($db->getErrorNum()) {
				JError::raiseError( 500, $db->stderr());
		    }
		    
			// Now, we get all the conversation within this discussion
		    $allMsgFromMe = $db->loadObjectList();
		    
		    // perform further filtering
		    $prev_id = 0;
			foreach($allMsgFromMe as $row){
			    $showMsg = true;			    			    
			    
			    if($row->to == $my->id){ //message for me.                
                    $showMsg = ($row->to_deleted == 0);
				} else if($row->from == $my->id){ // message from me
				    $showMsg = ($row->deleted == 0);
				}
				
				// check whether this message id is the same as previous one or not.
				// if yes...mean the message send to multiple users. We need to show
				// only one time.
				if($showMsg){
				    $showMsg = ($row->id != $prev_id);				    
				}
				
				//update the flag for next checking.
				$prev_id = $row->id;
				
				if($showMsg){
				    //append message into array object
				    $this->_data[] = $row;
				}
			}
			
			//reverse the array so that it show the old to latest.
			$this->_data = array_reverse($this->_data);
			
		}
		
		return $this->_data;
	}
	
	/**
	 * Mark a message as "read" (opened) from Inbox page
	 * @param	object 		message id
	 * @param	object 		current user id	 
	 */	 	
	function markAsRead($filter){
		$db =& $this->getDBO();
		$my =& JFactory::getUser();				
		
		// update all the messages that belong to current user.
 		$sql = "UPDATE #__community_msg_recepient "
 			." SET `is_read`= 1"
 			." WHERE `msg_id`=" . $db->Quote($filter['parent']) . " AND `to`=" . $db->Quote($filter['user_id'])
 			." AND `is_read`= 0";
		
        //executing update query
 		$db->setQuery($sql);
 		$db->query();
		 		
		return true;
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
