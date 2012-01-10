<?php
/**
 * Friend Model for Mojoom Component
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
class MojoomModelFriend extends JModel
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

			$query	= 'SELECT DISTINCT(a.connect_to) AS id ';
			if($filter == 'suggestion')
			{
				$query	= 'SELECT DISTINCT(b.connect_to) AS id ';
			}
			$query	.= ', CASE WHEN c.userid IS NULL THEN 0 ELSE 1 END AS online';
			
			switch( $filter )
			{
                case 'all':
                	$query	.= ', b.name , e.thumb';
					$query	.= ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS a '
							. 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b '
							. 'ON a.connect_from=' . $db->Quote( $id ) . ' '
							. 'AND a.connect_to=b.id '
							. 'AND a.status=' . $db->Quote( '1' ) . ' '
							. ' LEFT JOIN '. $db->nameQuote('#__community_users') .' as e ON b.id = e.userid '
					        . 'LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.connect_to = c.userid '
					        . 'WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) ';
					
		            // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		                                                        
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
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
					
                	$query	.= ', b.name , e.thumb';
					$query	.= ' FROM ' . $db->nameQuote( '#__community_connection' ) . ' AS a '
							. 'INNER JOIN ' . $db->nameQuote( '#__users' ) . ' AS b '
							. 'ON a.connect_from=' . $db->Quote( $id ) . ' '
							. 'AND a.connect_to=b.id '
							. 'AND a.status=' . $db->Quote( '1' );
					$query	.= $filterQuery;
					$query	.= ' LEFT JOIN '. $db->nameQuote('#__community_users') .' as e ON b.id = e.userid ';
					$query  .= ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.connect_to = c.userid';
					$query  .= ' WHERE NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) ';

		            // Search those we send connection
		            $pagingQuery = "SELECT count(*) "
		            .' FROM #__community_connection as a, #__users as b'
		            .' WHERE a.`connect_from`='.$db->Quote($id)
		            .' AND a.`status`=1 '
		            .' AND a.`connect_to`=b.`id` '
		            . $filterQuery
		            .' AND NOT EXISTS ( SELECT d.`blocked_userid` FROM ' . $db->nameQuote( '#__community_blocklist' ) . ' AS d WHERE d.`userid` = ' . $db->Quote( $id ) . ' AND d.`blocked_userid` = a.`connect_to`) '
		            .' ORDER BY a.`connection_id` DESC ';
		
		            $db->setQuery($pagingQuery);
		            $total = $db->loadResult();
		
		            // Appy pagination
		            if ( empty($this->_pagination))
		            {
		                jimport('joomla.html.pagination');
		                $this->_pagination = new JPagination($total, $limitstart, $limit);
		            }
                	break;
			}
			
            switch($sorted)
            {
                // We only want the id since we use CFactory::getUser later to get their full details.
                case 'online':                		
						$query	.= ' ORDER BY online DESC';
                    break;
                case 'suggestion':
						$query	.=	' GROUP BY (b.`connect_to`)'
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
						$query	.= ' ORDER BY a.connection_id DESC';
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
			//echo $query;
            $db->setQuery($query);

            $result = $db->loadObjectList();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }
			
			return $result;
			
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
		 /**
         *get Friend Connection
         *
         *@param connect_from int owner's id
         *@param connect_to stranger's id
         *return db object
         */

        function getFriendConnection($connect_from, $connect_to)
        {

            $db = & $this->getDBO();

            $query = "SELECT * FROM #__community_connection
		        WHERE (`connect_from` = ".$db->Quote($connect_from)." AND `connect_to` =".$db->Quote($connect_to).")
				OR ( `connect_from` = ".$db->Quote($connect_to)." AND `connect_to` =".$db->Quote($connect_from).")";

            $db->setQuery($query);
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());

            }

            $result = $db->loadObjectList();

            return $result;
        }
		
		/**
         * Lets caller know if the request really belongs to the UserId
         **/
        function isMyRequest($requestId, $userId)
        {
            $db = & $this->getDBO();

            $query = 'SELECT * FROM '
            .$db->nameQuote('#__community_connection')
            .'WHERE '.$db->nameQuote('connect_from').'='.$db->Quote($requestId).' '
            .'AND '.$db->nameQuote('connect_to').'='.$db->Quote($userId);

            $db->setQuery($query);
			$res = $db->loadObject(); 
            $status = (count($res) > 0)?$res->connection_id:0;

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            return $status;
        }
		 /**
         * approve the requested friend connection
         * @param	id 	int		the connection request id
         * @return	true if everything is ok
         */
        function approveRequest($id)
        {
            $connection = array ();
            $db = & $this->getDBO();
            //get connect_from and connect_to
            $query = "SELECT `connect_from`,`connect_to`"
            ." FROM #__community_connection "
            ." WHERE `connection_id` =".$db->Quote($id);

            $db->setQuery($query);
            $conn = $db->loadObject();

            if (! empty($conn))
            {
                $connect_from = $conn->connect_from;
                $connect_to = $conn->connect_to;

                $connection[] = $connect_from;
                $connection[] = $connect_to;

                //delete connection id
                $query = "DELETE FROM #__community_connection"
                ." WHERE `connection_id`=".$db->Quote($id);

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }


				$date	=& JFactory::getDate(); //get the time without any offset!
                //do double entry                
                //@todo escape code
                $query = "INSERT INTO #__community_connection SET"
                	. ' `connect_from`='.$db->Quote($connect_from)
                	. ', `connect_to`='.$db->Quote($connect_to)
                	. ', `status`=1'
                	. ', `created` = ' . $db->Quote($date->toMySQL());

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }


                //@todo escape code
                $query = "INSERT INTO #__community_connection SET"
                	. ' `connect_from`='.$db->Quote($connect_to)
                	. ', `connect_to`='.$db->Quote($connect_from)
                	. ', `status`=1'
                	. ', `created` = ' . $db->Quote($date->toMySQL());

                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum())
                {
                    JError::raiseError(500, $db->stderr());
                }

              	// update the friends countUPDATE `jos_community_users` SET `friendcount`=`friendcount`+1 WHERE `userid`=
				$qry = "UPDATE #__community_users SET `friendcount`=`friendcount` + 1 WHERE `userid`=".$connect_from;
				//print_r($qry);
				$db->setQuery( $qry );
				$res1	=   $db->query();
										
				$qry1= "UPDATE #__community_users SET `friendcount`=`friendcount` + 1 WHERE `userid`=".$connect_to;
				
				$db->setQuery( $qry1 );
				$res2	=   $db->query();
				return $connection;
            }
            else
            {
                // Return null is null
                return null;
            }
        }
		 /**
         * Delete sent request
         */
        function deleteSentRequest($from, $to)
        {
            $db = & $this->getDBO();

            $query = "DELETE FROM #__community_connection
				  WHERE `connect_from` = ".$db->Quote($from)."
				  AND `connect_to` = ".$db->Quote($to)." AND `status` = '0' ";

            $db->setQuery($query);
            $db->query();

            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
            }

            return true;
        }
		 /**
         * Delete sent request
         */
        function deleteFriend($from, $to)
        {
            $db = & $this->getDBO();

            $query = "DELETE FROM #__community_connection
				  WHERE `connect_from` = ".$db->Quote($from)."
				  AND `connect_to` = ".$db->Quote($to)." AND `status` = '1' ";

            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
    	    }
			$query = "DELETE FROM #__community_connection
				  WHERE `connect_from` = ".$db->Quote($to)."
				  AND `connect_to` = ".$db->Quote($from)." AND `status` = '1' ";

            $db->setQuery($query);
            $db->query();
			if ($db->getErrorNum())
            {
                JError::raiseError(500, $db->stderr());
    	    }

            return true;
        }



}
