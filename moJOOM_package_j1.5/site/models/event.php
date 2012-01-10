<?php
/**
 * Event Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
/* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');   
class MojoomModelEvent extends JModel 
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
		global $mainframe, $option;
		
		// Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 		
		$this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
		
		$array = JRequest::getVar('event_id',  0, '', 'get');
		$this->setId((int)$array);
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
	
	function getEvent()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__community_events where id = '".$this->_id."'";		
		$db->setQuery( $query );
		$event = $db->loadObject();
		return $event;
	}
	
	function getGroupevent()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__community_events where id = '".$this->_id."'";		
		$db->setQuery( $query );
		$event = $db->loadObject();
		return $event;
	}

	function getCategory()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__community_events_category";		
		$db->setQuery( $query );
		$cat = $db->loadObjectlist();
		return $cat;
	}
	
	function store()
	{	
		JTable::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'tables');
		$row =& JTable::getInstance('event', 'CTable');
		$my = JFactory::getUser();
		$db =& $this->getDBO();
		$data = JRequest::get( 'post' );
		$date	=& JFactory::getDate();	
		if( JString::strtoupper(JRequest::getMethod()) != 'POST')
		{
			$view->addWarning( JText::_('PERMISSION DENIED'));
			return false;
		}	
		//format startdate and eendate with time before we bind into event object
		if( isset( $data['starttime-ampm'] ) && $data['starttime-ampm'] == 'PM' && $data['starttime-hour'] != 12 )
		{
			$data['starttime-hour'] = $data['starttime-hour']+12;
		}		
		if( isset( $data['endtime-ampm'] ) && $data['endtime-ampm'] == 'PM' && $data['endtime-hour'] != 12 )
		{
			$data['endtime-hour'] = $data['endtime-hour'] + 12;
		}		
		$data['startdate']  = $data['s_year'] .'-' .$data['s_month'] .'-'.$data['s_day'] .' ' . $data['starttime-hour'].':'.$data['starttime-min'].':00';
		$data['enddate']  	= $data['e_year'] .'-' .$data['e_month'] .'-'.$data['e_day'] .' ' . $data['endtime-hour'].':'.$data['endtime-min'] . ':00';
		if($data['id'] <= 0)
		{
			$data['created'] = $date->toMySQL();
			$data['invitedcount'] = 0;
			$data['confirmedcount'] = 1;
			$data['creator'] = $my->id; 
		}
		if($data['id'] <= "")
		{
			$config				= CFactory::getConfig();			
			//@rule: If event moderation is enabled, event should be unpublished by default
			$data['published']	= $config->get('event_moderation') ? 0 : 1;			
		}
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
		$evid = $row->id;
		$this->_id = $evid;
		// Insert event member if new event created
		if($data['id'] <= 0)
		{
			$obj = new stdClass();
			$obj->id  = null;
			$obj->eventid = $row->id;
			$obj->memberid 	 = $my->id;
			$obj->status = 1;
			$obj->permission	= 1;
			$obj->invited_by	= 0;
			$obj->approval = 0;
			$obj->created	= $date->toMySQL();				
			$db->insertObject('#__community_events_members', $obj, 'id');     
		}
		// Insert event member on invitation table
		if($data['id'] <= 0)
		{
			$obj1 = new stdClass();
			$obj1->id  = null;
			$obj1->callback  = 'events,inviteUsers';
			$obj1->cid = $row->id;
			$obj1->users = $my->id;
			$db->insertObject('#__community_invitations', $obj1, 'id');     
		}
		return true;
	}
	
	function store_groupevent()
	{	
		JTable::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'tables');
		$row =& JTable::getInstance('event', 'CTable');
		$my = JFactory::getUser();
		$db =& $this->getDBO();
		$data = JRequest::get( 'post' );
		$date	=& JFactory::getDate();	
		if( JString::strtoupper(JRequest::getMethod()) != 'POST')
		{
			$view->addWarning( JText::_('PERMISSION DENIED'));
			return false;
		}	
		//format startdate and eendate with time before we bind into event object
		if( isset( $data['starttime-ampm'] ) && $data['starttime-ampm'] == 'PM' && $data['starttime-hour'] != 12 )
		{
			$data['starttime-hour'] = $data['starttime-hour']+12;
		}		
		if( isset( $data['endtime-ampm'] ) && $data['endtime-ampm'] == 'PM' && $data['endtime-hour'] != 12 )
		{
			$data['endtime-hour'] = $data['endtime-hour'] + 12;
		}		
		//$data['startdate']  = $data['startdate'] . ' ' . $data['starttime-hour'].':'.$data['starttime-min'].':00';
		//$data['enddate']  	= $data['enddate'] . ' ' . $data['endtime-hour'].':'.$data['endtime-min'] . ':00';
		$data['startdate']  = $data['s_year'] .'-' .$data['s_month'] .'-'.$data['s_day'] .' ' . $data['starttime-hour'].':'.$data['starttime-min'].':00';
		$data['enddate']  	= $data['e_year'] .'-' .$data['e_month'] .'-'.$data['e_day'] .' ' . $data['endtime-hour'].':'.$data['endtime-min'] . ':00';
		if($data['id'] <= 0)
		{
			$data['created'] = $date->toMySQL();
			$data['invitedcount'] = 0;
			$data['confirmedcount'] = 1;
			$data['creator'] = $my->id; 
			$data['type'] = 'group'; 
		}
		if($data['id'] <= "")
		{
			$config				= CFactory::getConfig();			
			//@rule: If event moderation is enabled, event should be unpublished by default
			$data['published']	= $config->get('event_moderation') ? 0 : 1;			
		}
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
		$evid = $row->id;
		$this->_id = $evid;
		
		// Insert event member if new event created
		if($data['id'] <= 0)
		{
			$obj = new stdClass();
			$obj->id  = null;
			$obj->eventid = $row->id;
			$obj->memberid 	 = $my->id;
			$obj->status = 1;
			$obj->permission	= 1;
			$obj->invited_by	= 0;
			$obj->approval = 0;
			$obj->created	= $date->toMySQL();				
			$db->insertObject('#__community_events_members', $obj, 'id');     
		}
		
		return true;
	}
	
	static public function getTimezone( )
	{
		$timezone= array();
		$timezone['-11'] = JText::_('MIDWAY');
		$timezone['-10'] = JText::_('HAWAII');
		$timezone['-9.5'] = JText::_('TAIOHAE');
		$timezone['-9'] = JText::_('ALASKA');
		$timezone['-8'] = JText::_('PACIFIC');
		$timezone['-7'] = JText::_('MOUNTAIN');
		$timezone['-6'] = JText::_('CENTRAL');
		$timezone['-5'] = JText::_('EASTERN');
		$timezone['-4'] = JText::_('ATLANTIC');
		$timezone['-4.5'] = JText::_('VENEZUELA');
		$timezone['-3.5'] = JText::_('STJOHN');
		$timezone['-3'] = JText::_('BRAZIL');
		$timezone['-2'] = JText::_('MIDATLANTIC');
		$timezone['-1'] = JText::_('AZORES');
		$timezone['0'] = JText::_('EUROPETIME');
		$timezone['1'] = JText::_('AMSTERDAM');
		$timezone['2'] = JText::_('ISTANBUL');
		$timezone['3'] = JText::_('BAGHDAD');
		$timezone['3.5'] = JText::_('TEHRAN');
		$timezone['4'] = JText::_('ABUDHABI');
		$timezone['4.5'] = JText::_('KABUL');
		$timezone['5'] = JText::_('EKATERINBURG');
		$timezone['5.5'] = JText::_('BOMBAY');
		$timezone['5.75'] = JText::_('KATHMANDU');
		$timezone['6'] = JText::_('ALMATY');
		$timezone['6.30'] = JText::_('YAGOON');
		$timezone['7'] = JText::_('NAGKOK');
		$timezone['8'] = JText::_('BEIJING');
		$timezone['8.75'] = JText::_('ULAANBAATAR');
		$timezone['9'] = JText::_('TOKYO');
		$timezone['9.5'] = JText::_('ADELAIDE');
		$timezone['10'] = JText::_('EASTERN');
		$timezone['10.5'] = JText::_('LORDHOWE');
		$timezone['11'] = JText::_('MAGADAN');
		$timezone['11.30'] = JText::_('NORFOLK');
		$timezone['12'] = JText::_('AUCKLAND');
		$timezone['12.75'] = JText::_('CHATHAM');
		$timezone['13'] = JText::_('TONGA');
		$timezone['14'] = JText::_('KIRIBATI');
		
		return $timezone;
	}
	
	/**
	 * Returns an object of events which the user has registered.
	 *
	 * @access	public
	 * @param	string 	User's id.
	 * @param	string 	sorting criteria.
	 * @returns array  An objects of event fields.
	 */
	function getMyevents( $categoryId = null, $userId = null , $sorting = null, $search = null, $hideOldEvent = true, $showOnlyOldEvent = false, $pending = null, $advance = null  ,$type = null , $contentid = 0 , $limit = null )
	{
		$db	    =&	$this->getDBO();
		$join	    =	'';
		$extraSQL   =	'';
		$date	=& JFactory::getDate(); 
		if( !empty($userId) )
		{
			$join	    =	'LEFT JOIN ' . $db->nameQuote('#__community_events_members') . ' AS b ON a.id=b.eventid ';
			$extraSQL   .= ' AND b.memberid=' . $db->Quote($userId);
		}
		
		if( !empty($search) )
		{
			$extraSQL   .= ' AND a.title LIKE ' . $db->Quote( '%' . $search . '%' );
		}
		
		if( !empty($categoryId) && $categoryId != 0 )
		{
			$extraSQL   .= ' AND a.catid=' . $db->Quote($categoryId);
		}

		if( !empty( $pending ) && !empty($userId) )
		{
			$extraSQL   .= ' AND b.status=' . $db->Quote($pending);
		}

		/* Begin : ADVANCE SEARCH */
		if( !empty($advance) )
		{
			if( !empty($advance['startdate']) )
			{
				$startDate	=   CTimeHelper::getDate( strtotime($advance['startdate']) );

				$extraSQL	.=  ' AND a.startdate >= ' . $db->Quote( $startDate->toMySQL() );

			}
			else // If empty, don't select the past event
			{
				$extraSQL	.=  ' AND a.startdate >= ' . $db->Quote( $date->toMySQL() );
			}

			if( !empty($advance['enddate']) )
			{
				$endDate	=   CTimeHelper::getDate( strtotime($advance['enddate']) );

				$extraSQL	.=  ' AND a.startdate <= ' . $db->Quote( $endDate->toMySQL() );
			}

			/* Begin : SEARCH WITHIN */
			if( !empty($advance['radius']) && !empty($advance['fromlocation']) ){

				$longitude  =	null;
				$latitude   =	null;

				CFactory::load('libraries', 'mapping');
				$data = CMapping::getAddressData( $advance['fromlocation'] );

				if($data){
					if($data->status == 'OK')
					{
						$latitude  = (float) $data->results[0]->geometry->location->lat;
						$longitude = (float) $data->results[0]->geometry->location->lng;
					}
				}
			

				$lng_min = $longitude - $advance['radius'] / abs(cos(deg2rad($latitude)) * 69);
				$lng_max = $longitude + $advance['radius'] / abs(cos(deg2rad($latitude)) * 69);
				$lat_min = $latitude - ($advance['radius'] / 69);
				$lat_max = $latitude + ($advance['radius'] / 69);

				$extraSQL   .=	' AND a.longitude > ' . $db->quote($lng_min)
						. ' AND a.longitude < ' . $db->quote($lng_max)
						. ' AND a.latitude > ' . $db->quote($lat_min)
						. ' AND a.latitude < ' . $db->quote($lat_max);

			}
			/* End : SEARCH WITHIN */
		}
		/* End : ADVANCE SEARCH */

		$limitstart =   $this->getState('limitstart');
		$limit	    =   $limit === null ? $this->getState('limit') : $limit;

		//if( $type != 'ALL_TYPES' )
//		{
//			$extraSQL   .=  ' AND a.type=' . $db->Quote( $type );
//			$extraSQL   .=  ' AND a.contentid=' . $contentid;
//		}

		if( $type == 'ALL_TYPES' )
		{
			// @rule: Respect group privacy
			$join		.=  ' LEFT JOIN ' . $db->nameQuote('#__community_groups') . ' AS g';
			$join 		.= ' ON g.id = a.contentid ';
			$extraSQL	.= ' AND (g.approvals = 0 OR g.approvals IS NULL';
			
			if( !empty($userId ) )
			{
				$extraSQL	.= ' OR b.memberid=' . $db->Quote( $userId );
			}
			$extraSQL	.= ')';
		}

		$orderBy    =	'';
		$total	    =	0;

		switch($sorting)
		{			
			case 'latest':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.created DESC';
				break;
			case 'alphabetical':
				if( empty($orderBy) )
					$orderBy	= ' ORDER BY a.title ASC';
				break;
			case 'startdate':
			default:
				$orderBy	= ' ORDER BY a.startdate ASC';
				break;
		}
		
		if($hideOldEvent)
		{
			$extraSQL .= ' AND a.enddate > ' . $db->Quote( $date->toMySQL() );
		}

		if($showOnlyOldEvent)
		{
			$extraSQL .= ' AND a.enddate < ' . $db->Quote( $date->toMySQL() );
		}
		
		$limit	= empty($limit) ? 0 : $limit;
				
		$query	= 'SELECT DISTINCT a.* FROM '
				. $db->nameQuote('#__community_events') . ' AS a '
				. $join
				. 'WHERE a.published=' . $db->Quote( '1' )
				. $extraSQL
				. $orderBy
				. ' LIMIT ' . $limitstart . ', ' . $limit;
		
		//echo $query;
		//exit;
		$db->setQuery( $query );		
		$result	= $db->loadObjectList();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$query	= 'SELECT COUNT(DISTINCT(a.`id`)) FROM ' . $db->nameQuote('#__community_events') . ' AS a '
				. $join
				. 'WHERE a.published=' . $db->Quote( '1' ) . ' '
				. $extraSQL;

		$db->setQuery( $query );
		$total	= $db->loadResult();

		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		if( empty($this->_pagination) )
		{
			jimport('joomla.html.pagination');

			$this->_pagination	= new JPagination( $total, $limitstart, $limit );
		}

		return $result;
	}
	
	/**
	 * Responsible for displaying the event page.
	 **/
	 
	 function getViewevent()
	 {
	 	$data = JRequest::get( 'get' );
		$qry = "select a.* , count(b.id) as total_booking , c.name  ,d.name as cat from #__community_events as a inner join #__community_events_members as b on a.id = b.eventid inner join #__community_events_category as d on a.catid = d.id inner join #__users as c on a.creator = c.id and c.block = 0 where a.id = '".$data['event_id']."'";

		$db =& JFactory::getDBO();
		$db->setQuery( $qry );
		$event = $db->loadObject();
		return $event;
	 }
	 
	 function getMember($id)	 
	 {
	 	$db =& JFactory::getDBO();
		$data = JRequest::get( 'get' );
		$qry = "select memberid from #__community_events_members where eventid = '".$data['event_id']."'  and memberid != '".$id."'";
		$db->setQuery( $qry );		
		$member = $db->loadResultArray();
		$member = implode(',' , $member);
		if($member != "")
		{
		$qry = "SELECT b.id ,b.name FROM #__community_connection AS a inner join #__users as b where a.connect_from= '".$id."' AND a.status='1' and a.connect_to = b.id and b.id not in ($member)";
		}
		else
		{
			$qry = "SELECT b.id ,b.name FROM #__community_connection AS a inner join #__users as b where a.connect_from= '".$id."' AND a.status='1' and a.connect_to = b.id and b.id ";
		}

		$db->setQuery( $qry );
		$memberlist = $db->loadObjectlist();

		return $memberlist; 
	 }
	 /**
	 * Return the number of groups cretion count for specific user
	 **/
	function getEventsCreationCount()
	{
		$user =& JFactory::getUser();
		$userId = $user->get('id');  
		// guest obviously has no events
		if($userId == 0)
			return 0;
		$db		=& $this->getDBO();
		$query	= 'SELECT COUNT(*) FROM '
				. $db->nameQuote( '#__community_events' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'creator' ) . '=' . $db->Quote( $userId );		
		$db->setQuery( $query );
		$count	= $db->loadResult();
		return $count;
	}	 	
	// Check if the vote for the item is exist
	function getInfo( $element, $itemId )
	{
		$db	=&  JFactory::getDBO();

		$query	=   'SELECT * FROM ' . $db->nameQuote('#__community_likes') . ' '
			    . 'WHERE ' . $db->nameQuote('element') . '=' . $db->Quote( $element ) . ' '
			    . 'AND ' . $db->nameQuote('uid') . '=' . $db->Quote( $itemId );

		$db->setQuery( $query );

		$result	=   $db->loadObject();

		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}
	
	function setLike( $element, $itemId ,$userId)
	{
		$db	=&  JFactory::getDBO();		
		$query = "select id from #__community_likes where element = '".$element."' and uid = '".$itemId."' ";
		$db->setQuery( $query );
		$result	=   $db->loadResult();		
		if($result == "")
		{
			$query = "insert into #__community_likes(`element`, `uid`, `like`) values('".$element."','".$itemId."','".$userId.','."')";			
		}
		else
		{
			$qry = "select id from #__community_likes where `like` LIKE '%$userId%' and `uid` = '$itemId' AND element = '".$element."' ";
			$db->setQuery( $qry );
			$id_l	=   $db->loadResult();
			$qry = "SELECT `id`,`dislike` FROM `#__community_likes` WHERE `uid` = '$itemId' AND `dislike` LIKE '%$userId%' AND element = '".$element."' "; 			
			$db->setQuery( $qry );
			$id_d	=   $db->loadRow();			
			if($id_l != "")
			{
				return true;
			}
			else
			{
				$dislike = str_replace($userId.',' , '' , $id_d[1]);
				$qry = "UPDATE #__community_likes SET `dislike` = '".$dislike."' WHERE id='".$id_d[0]."'";
				$db->setQuery( $qry );
				$dislike	=   $db->query();						
				$query = "UPDATE #__community_likes SET `like` = CONCAT(`like`, '".$userId.','."') WHERE id='".$result."'";			
			}
		}			
		$db->setQuery( $query );
		$like	=   $db->query();		
		return $like;
			
	}
	function setDislike( $element, $itemId ,$userId)
	{
		$db	=&  JFactory::getDBO();		
		$query = "select id from #__community_likes where element = '".$element."' and uid = '".$itemId."' ";
		$db->setQuery( $query );
		$result	=   $db->loadResult();		
		if($result == "")
		{
			$query = "insert into #__community_likes(`element`, `uid`, `dislike`) values('".$element."','".$itemId."','".$userId.','."')";			
		}
		else
		{
			$qry = "SELECT `id`,`like` FROM `#__community_likes` WHERE `uid` = '$itemId' AND `like` LIKE '%$userId%' AND element = '".$element."' "; 
			$db->setQuery( $qry );
			$id_l	=   $db->loadRow();
			$qry = "select id from #__community_likes where `dislike` LIKE '%$userId%' and `uid` = '$itemId' AND element = '".$element."' ";
			$db->setQuery( $qry );
			$id_d	=   $db->loadResult();
			if($id_d == "")
			{
				$like = str_replace($userId.',' , '' , $id_l[1]);
				$qry = "UPDATE #__community_likes SET `like` = '".$like."' WHERE id='".$id_l[0]."'";								
				$db->setQuery( $qry );
				$like	=   $db->query();						
				$query = "UPDATE #__community_likes SET `dislike` = CONCAT(`dislike`, '".$userId.','."') WHERE id='".$result."'";			
			}
			else
			{
				return true;
			}
		}	
		$db->setQuery( $query );
		$dislike	=   $db->query();		
		return $dislike;			
	}
	
	function accept()
	{
		$db =& JFactory::getDBO();
		$data = JRequest::get( 'get' );
		$user =& JFactory::getUser();
		$userId = $user->get('id');  
		// update member status	
		$qry = "UPDATE #__community_events_members SET status = 1 where eventid = '".$data['event_id']."'  and memberid = '".$userId."'";
		$db->setQuery( $qry );		
		$accept = $db->query();		
		// update nenber counts for event		
		$count = $this->getConfirmcount($data['event_id']);
		$qry = "UPDATE #__community_events SET confirmedcount = '".$count."' where id = '".$data['event_id']."'";		
		$db->setQuery( $qry );		
		$accept1 = $db->query();
		return $accept1;		
	
	}
	
	function reject()
	{
		$db =& JFactory::getDBO();
		$data = JRequest::get( 'get' );
		$user =& JFactory::getUser();
		$userId = $user->get('id');  
		// update member status	
		$qry = "UPDATE #__community_events_members SET status = 2 where eventid = '".$data['event_id']."'  and memberid = '".$userId."'";
		$db->setQuery( $qry );		
		$accept = $db->query();		
		// update nenber counts for event		
		$count = $this->getConfirmcount($data['event_id']);
		$qry = "UPDATE #__community_events SET confirmedcount = '".$count."' , invitedcount = (`invitedcount` - 1) where id = '".$data['event_id']."'";		
		$db->setQuery( $qry );		
		$accept1 = $db->query();
		return $accept1;		
	
	}
	
	function getConfirmcount($id)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT count(id) as total from #__community_events_members where eventid = '".$id."' and status = 1";		
		$db->setQuery( $qry );
		$total	=   $db->loadResult();
		return $total;
	}
	
	/**
	 * Method to retrieve total events for a specific group
	 * 
	 * @param	int		$groupId	The unique group id.
	 * @return	array	$result		An array of result.
	 **/	 	 	 	 	
	public function getTotalGroupEvents( $groupId )
	{
		//CFactory::load( 'helpers' , 'event' );
		$db		=& $this->getDBO();
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_events' ) . ' WHERE '
				. $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' AND '
				. $db->nameQuote( 'contentid' ) . '=' . $db->Quote( $groupId );
		$db->setQuery( $query );
		$result	= $db->loadResult();

		return $result;
	}
	
	/**
	 * Method to retrieve events for a specific group
	 * 
	 * @param	int		$groupId	The unique group id.
	 * @return	array	$result		An array of result.
	 **/	 	 	 	 	
	public function getGroupEvents( $groupId , $limit = 0 )
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__community_events' ) . ' WHERE '
				. $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' AND '
				. $db->nameQuote( 'contentid' ) . '=' . $db->Quote( $groupId );
		if( $limit != 0 )
		{
			$query	.= 'LIMIT 0,' . $limit;
		}		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}		
		$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_events' ) . ' WHERE '
				. $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' AND '
				. $db->nameQuote( 'contentid' ) . '=' . $db->Quote( $groupId );
		$db->setQuery( $query );		
		$total	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		if( empty($this->_pagination) )
		{
			$limit		= $this->getState('limit');
			$limitstart = $this->getState('limitstart');
			jimport('joomla.html.pagination');
			$this->_pagination	= new JPagination( $total, $limitstart, $limit );
		}
		
		return $result;
	}
	function getGroupOwner( $groupId )
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT ownerid as owner FROM ' . $db->nameQuote( '#__community_groups' ) . ' WHERE '
				. $db->nameQuote( 'id' ) . '=' . $db->Quote( $groupId );
		$db->setQuery( $query );		
		$owner	= $db->loadResult();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $owner;
	}
	function getGroupEventResponse( $eventId , $userId )
	{
		$db		=& $this->getDBO();
		$query	= 'SELECT status FROM ' . $db->nameQuote( '#__community_events_members' ) . ' WHERE '
				. $db->nameQuote( 'eventid' ) . '=' . $db->Quote( $eventId ). ' AND '
				. $db->nameQuote( 'memberid' ) . '=' . $db->Quote( $userId );
		$db->setQuery( $query );		
		$status	= $db->loadResult();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		return $status;
	}
	function Delete()
	{
		$db =& JFactory::getDBO();
		$data = JRequest::get( 'get' );
		$user =& JFactory::getUser();
		$userId = $user->get('id');  
		// delete member 	
		$qry = "DELETE from #__community_events_members where eventid = '".$data['event_id']."'";
		$db->setQuery( $qry );		
		$delete = $db->query();		
		// delete invitations		
		$qry = "DELETE from #__community_invitations where cid = '".$data['event_id']."' and callback = 'events,inviteUsers'";		
		$db->setQuery( $qry );		
		$delete1 = $db->query();		
		// delete event		
		$qry = "DELETE from #__community_events where id = '".$data['event_id']."'";		
		$db->setQuery( $qry );		
		$delete2 = $db->query();
		return $delete2;		
	}
	
}
