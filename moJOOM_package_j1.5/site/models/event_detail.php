<?php  
/**
 * Event Detail Model for Mojoom Component
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
class MojoomModelEvent_detail extends JModel
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
	
	function getGuestmember($gid,$uid)
	{
		$query = 'SELECT memberid FROM #__community_events_members WHERE status=1 and eventid = '.$gid.' and memberid = '.$uid.'';
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadResult();
		return $result;
	}
	
	function getPost($type, $cid, $limit, $limitstart, $order = 'DESC'){

		$db=& JFactory::getDBO();
		$strSQL	= 'SELECT a.* , b.name FROM #__community_wall AS a '
				. 'INNER JOIN #__users AS b '
				. 'WHERE b.id=a.post_by '
				. 'AND a.type=' . $db->Quote( $type ) . ' '

				. 'AND a.contentid=' . $db->Quote( $cid )

				. ' ORDER BY a.date '. $order;

				{
		
					$strSQL.= " LIMIT $limitstart , $limit ";
		
				}
		$db->setQuery( $strSQL );
		//echo '<pre>'.$db->getQuery().'</pre>';
		if($db->getErrorNum()){

			JError::raiseError(500, $db->stderr());

		}
		$result=$db->loadObjectList();
		//print_r($result);
		return $result;
	}
	
	
	
	function _getDate( $str = '' )
	{

		$mainframe	=& JFactory::getApplication();

		$extraOffset	= 0;

		$date	= new JDate($str);

		$my		=& JFactory::getUser();

		if(!$my->id){

			$date->setOffset($mainframe->getCfg('offset') + $extraOffset);

		} else{

			if(!empty($my->params)){

				$pos = JString::strpos($my->params, 'timezone');
			
				$offset = $mainframe->getCfg('offset') + $extraOffset;

				if ($pos === false) {

				   $offset = $mainframe->getCfg('offset') + $extraOffset;

				} 

				$date->setOffset($offset);

			} else

				$date->setOffset($mainframe->getCfg('offset') + $extraOffset);

		}

		return $date;

	}
	
	function _appLink($name, $actor = 0, $userid = 0)
	{
		//static $instances = array();

		//$my =& JFactory::getUser();
		if(empty($name))
			return '';
		//if( empty($instances[$id.$actor.$userid]) )
		//{
		$url = '';
		// @todo: check if this app exist
		if(true) {
			// if no target specified, we use actor
			if($userid == 0) 
				$userid= $actor;

			if( $userid != 0 && $name != 'profile' && $name != 'news_feed' && $name != 'photos' && $name != 'friends')
			{
				
				$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$id;
				$url = '<a href="' . $url .'" >'. $this->_getAppTitle($name) . '</a>';

			}
			else
			{
				$url = $this->_getAppTitle($name);
			}

		}

		return $url;

	}
	
	function _getAppTitle($appname)
	{

		static $instances = array();
		if(empty($instances[$appname]))
		{

			$sql = "SELECT name FROM #__plugins WHERE `element`=". $this->_db->Quote($appname);
			$this->_db->setQuery($sql);
			$instances[$appname] = $this->_db->loadResult();

		}
		
		return $instances[$appname];
	}
	
	function _targetLink( $id, $onApp=false )
	{

		static $instances1 = array();
		if( empty($instances1[$id]) )
		{
		$my			=& JFactory::getUser();
		$linkName	= ($id==0)? false : true;
		$name = $this->_getUserName($id);
		// Wrap the name with link to his/her profile

		$html = $name;

		if($linkName)
		{
			$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$id;	
			$html = '<a href="'.$url.'">'.$name.'</a>';
		}
		$instances1[$id] = $html;
		}
		return $instances1[$id];
	}
	
	function _actorLink($id)
	{
		static $instances1 = array();
		if( empty($instances1[$id]))
		{
			$my			=& JFactory::getUser();
			$linkName	= ($id==0)? false : true;
			$name = $this->_getUserName($id);

			// Wrap the name with link to his/her profile
			$html		= $name;
			if($linkName)
			{
				$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$id;
				$html = '<a href="'.$url.'" class="actor-link">'.$name.'</a>';
			}
			$instances1[$id] = $html;
		}
		return $instances1[$id];

	}
	function _getUserName($id)
	{
		$query = 'SELECT name,username FROM #__users WHERE id='.$id;
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		return $result->username;
	}
	function _timeLapse($date)
	{
		
		$now = new JDate();

		$dateDiff = $this->_timeDifference($date->toUnix(), $now->toUnix());
		if( $dateDiff['days'] > 0){
			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['days'])) ? 'DAYS AGO':'DAY AGO', $dateDiff['days']);

		}elseif( $dateDiff['hours'] > 0){

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['hours'])) ? 'HOURS AGO':'HOUR AGO', $dateDiff['hours']);

		}elseif( $dateDiff['minutes'] > 0){

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['minutes'])) ? 'MINUTES AGO':'MINUTE AGO', $dateDiff['minutes']);

		}else {

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['seconds'])) ? 'SECONDS AGO':'SECOND AGO', $dateDiff['seconds']);

		}

		return $lapse;
	}



	function _timeDifference( $start , $end )
	{
		if(is_string($start) && ($start != intval($start))){
			$start = new JDate($start);
			$start = $start->toUnix();
		}
		if(is_string($end) && ($end != intval($end) )){
			$end = new JDate($end);
			$end = $end->toUnix();
		}

		$uts = array();
	    $uts['start']      =    $start ;
	    $uts['end']        =    $end ;
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
	            trigger_error( JText::_("DATE TIME INFO"), E_USER_WARNING );
	        }

	    }
	    else
	    {
	        trigger_error( JText::_("INVALID DATE TIME"), E_USER_WARNING );
	    }
	    return( false );
	}
	
	function _isPlural($num)
	{
		return !$this->_isSingular($num);
	}
	
	function _isSingular($num)
	{

		$singularnumbers = 1;
		$singularnumbers = explode(',', $singularnumbers);
		return in_array($num, $singularnumbers);
	}
	
	function getWallContents( $appType , $uniqueId , $isOwner , $limit = 0 , $limitstart = 0, $templateFile = 'wall.content' , $processFunc = '', $param = null )
	{
		
		
		$config   = CFactory::getConfig();
				
		$model	= CFactory::getModel( 'wall' );
		
		// Special 'discussions'
		$order = 'DESC';
		//if($appType == 'discussions'){
			//$order = $config->get('group_discuss_order');
			//$discussionsTrigger = true;
		//}
		
		$walls	= $model->getPost( $appType , $uniqueId , 300, $limitstart, $order);

		// Special 'discussions'
		$discussionsTrigger = false;
		$order = $config->get('group_discuss_order');
		if(($appType == 'groups') && ($order == 'ASC'))
		{
			$walls	= array_reverse($walls);
			$discussionsTrigger = true;
		}

		if( $walls )
		{
			//Process wall comments
			CFactory::load('libraries', 'comment');
			$wallComments	= array();
			$comment		= new CComment();
			
			for( $i = 0 ; $i < count( $walls ); $i++ )
			{
				$wall			= $walls[ $i ];
				$wallComments[]	= $wall->comment;
				$wall->comment  = $comment->stripCommentData($wall->comment);
			}
			
		}
			
		return $walls;
	}
	

	function stripCommentData($comment)
	{
	
	// Once we retrive the comment, we can remove them
	
	$content = preg_replace('/\<comment\>(.*?)\<\/comment\>/i', '', $comment);
	
	return $content;
	
	}
	
	function getCommentsData($comment)
	{
		$json = new Services_JSON();
		$comments = array();	
		// See if the content already has commment.	
		// If not, create it and add to it	
		$regex = '/\<comment\>(.*?)\<\/comment\>/i';
		if (preg_match($regex, $comment, $matches)) 
		{
			$comments = $json->decode($matches[1]);
			
		}
		
		return $comments;
	}
	
	function SaveEventWallInner($data)
	{
		$db =& JFactory::getDBO();
		
		$query = "select * FROM #__community_wall WHERE id=".$data['a_id']."";
		$db->setQuery( $query );
		$json_comment = $db->loadObject();
		$a = $this->stripCommentData($json_comment->comment);
		//echo $a;
		
		$d = $this->getCommentsData($json_comment->comment);
		$e   = json_encode($d);
		//echo $e;
		
		$b = trim($e,']');
		$g = trim($b,'[');
		//echo $g;
		
		$date	= new JDate();
	
		$arr = array("creator" => $data['user_id'], "text" =>  $data['message'],"date"=>$date->toUnix());
		
		$json  = json_encode($arr);
		if($g != "")
		{
			$json_data = $g.",".$json;
		}
		else
		{
			$json_data = $json;
		}
		//echo $json_data;
	
		$wall_json = new stdClass();
		$wall_json->id 			= $data['a_id'];
	//	$wall_json->date			= gmdate('Y-m-d H:i:s');
		$wall_json->comment	= $a."<comment>[".$json_data."]</comment>";
		//echo $wall_json->comment;
		//exit;
		
		$this->_db->updateObject('#__community_wall', $wall_json, 'id');

		return true;
		
	}
	
	function SaveEventWall($data)
	{
		$my = CFactory::getUser();
		$wallid = $this->saveWall( $data['eventid'] ,$data['user_id'], $data['message'] , 'events' );
		return $wallid;
	}
	
	function saveWall( $uniqueId ,$creatorid, $message , $appType  )
	{
			$my = CFactory::getUser();
				
			// Set the wall properties
			$e_wall = new stdClass();
			$e_wall->id 			= '';
			$e_wall->contentid	= $uniqueId;
			$e_wall->post_by		= $creatorid;
			$e_wall->ip			= $_SERVER['REMOTE_ADDR'];
			$e_wall->comment		= $message;
			$e_wall->date			= gmdate('Y-m-d H:i:s');
			$e_wall->published	= 1;
			$e_wall->type			= $appType;
			$this->_db->insertObject('#__community_wall', $e_wall, 'id');
			return $e_wall->id;

	}
	
	function RemoveEventWallComment($data)
	{
		$db =& JFactory::getDBO();
		$query = "DELETE FROM #__community_wall WHERE id=".$data['id']."";
		$db->setQuery( $query );
		$db->query();
		return true;
		
	}
	function getEvent($id)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__community_events where id = '".$id."'";		
		$db->setQuery( $query );
		$event = $db->loadObject();
		return $event;
	}
	
}
