<?php
/**
 * Member Model for Mojoom Component
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

class MojoomModelMember extends JModel
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
	 * Search for people
	 * @param query	string	people's name to seach for	
	 */	 
	 /**
         * return the list of friend from approved connections
         * controller need to set the id
         *
         * @param	id	int		user id of the person we want to searhc their friend
         * @param	bool do we need to randomize the result
         * @param	sorted	boolean	do we need sorting?
         * @return	CUser objects
         */
        function & getFriends($id, $sorted = 'latest', $useLimit = true , $filter = 'all' , $maxLimit = 0 )
        {
            $cusers = array ();
			
			// Deprecated since 1.8 .
			// Earlier versions the default $filter is empty but since we will now need to handle character filter,
			// we need to set the default to 'all'
			if( empty($filter) )
			{
				$filter	= 'all';
			}
		
            // For visitor with id=0, obviously he won't have any friend!
            if ( empty($id))
            {
                return $cusers;
            }

            $db = & $this->getDBO();

            $wheres = array ();
            $wheres[] = 'block = 0';
            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');

			$query	= 'SELECT b.* ';
			$query	.= ', CASE WHEN c.userid IS NULL THEN 0 ELSE 1 END AS online';
			
			switch( $filter )
			{
                case 'all':
                	$query	.= ', e.thumb ';
					$query	.= ' FROM '  . $db->nameQuote( '#__users' ) . ' AS b '
							. ' LEFT JOIN '. $db->nameQuote('#__community_users') .' as e ON b.id = e.userid '
					        . ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON b.id = c.userid '
					        . ' WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = b.`id`) ';
					
		           
                    break;
                default:
					$filterCount	= JString::strlen( $filter );
					
					$filterQuery	= '';
					
					if( $filter == 'others' )
					{
						$filterQuery	= ' AND b.name REGEXP "^[^a-zA-Z]."';
					}
					else
					{
					    $config         = CFactory::getConfig();
					    
						$filterQuery	= ' AND(';
						for( $i = 0; $i < $filterCount; $i++ )
						{
							$char			= $filter{$i};
							$filterQuery	.= $i != 0 ? ' OR ' : ' ';
							$nameField      = 'b.' . $db->nameQuote( $config->get('displayname') );
							$filterQuery	.= $nameField .' LIKE "' . JString::strtoupper($char) . '%" OR ' . $nameField . ' LIKE "' . JString::strtolower($char) . '%"';
						}
						$filterQuery	.= ')';
					}
					
                	$query	.= ', e.thumb ';
					$query	.= ' FROM ' . $db->nameQuote( '#__users' ) . ' AS b ';
					$query	.= $filterQuery;
					$query	.= ' LEFT JOIN '. $db->nameQuote('#__community_users') .' as e ON b.id = e.userid ';
					$query  .= ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON b.id = c.userid';
					$query  .= ' WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = b.`id`) ';

		          
                	break;
			}
			
            switch($sorted)
            {
                // We only want the id since we use CFactory::getUser later to get their full details.
                case 'online':                		
						$query	.= ' ORDER BY online DESC';
                    break;
                case 'suggestion':
						$query	.=	' GROUP BY (b.`id`)'
								. ' HAVING (totalFriends >= ' . FRIEND_SUGGESTION_LEVEL . ')';
								
                    break;
                case 'name':
            		//sort by name only applicable to filter is not mutual and suggestion
            		if($filter != 'mutual' && $filter != 'suggestion')
            		{
            			$config	= CFactory::getConfig();
            			$query	.= ' ORDER BY b.' . $db->nameQuote( $config->get( 'displayname' ) ) . ' ASC';
					}
					break;	
                default:
						
                    break;
            }

            if ($useLimit)
            {
                $query .= " LIMIT {$limitstart}, {$limit} ";
            }
            else if ($maxLimit > 0)
            {
            	// we override the limit by specifying how many return need to be return.
            	$query .= " LIMIT 0, {$maxLimit} ";
            }
			
			$db->setQuery($query);

            $result = $db->loadObjectList();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
			
			return $result;
			 //preload all users
			$uids = array();
			foreach($result as $m)
			{
				$uids[] = $m->id;
			}
			CFactory::loadUsers($uids);
			
            for ($i = 0; $i < count($result); $i++)
            {

                $usr = CFactory::getUser($result[$i]->id);
                $cusers[] = $usr;
            }

            return $cusers;
        }
		
		/**
         * Save a friend request to stranger. Stranger will have to approve
         * @param	$id		int		stranger user id
         * @param   $fromid int     owner's id
         */
        function addFriend($id, $fromid, $msg='', $status = 0)
        {
            $my = JFactory::getUser();
            $db = & $this->getDBO();
            $wheres[] = 'block = 0';

            if ($my->id == $id)
            {
                JError::raiseError(500, JText::_('NOT ADD FRIEND YOURSELF'));
            }

            $date	=& JFactory::getDate(); //get the time without any offset!
            $query	= "INSERT INTO #__community_connection SET"
				. ' `connect_from` = '.$db->Quote($fromid)
            	. ', `connect_to` = '.$db->Quote($id)
            	. ', `status` = '. $db->Quote($status)
            	. ', `created` = ' . $db->Quote($date->toMySQL())
				. ', `msg` = ' . $db->Quote($msg);
		
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
				return false;
            }
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
