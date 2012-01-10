<?php  
/**
 * Group Discussion Model for Mojoom Component
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

class MojoomModelGroupdiscussion extends JModel
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

	 * Get list of discussion topics

	 *

	 * @param	$id	The group id

	 * @param	$limit Limit

	 **/ 

	function getDiscussionTopics( $groupId , $limit = 0 , $order = '' )

	{

		$orderByQuery	= '';

		switch( $order )

		{

			default:

				$orderByQuery = 'ORDER BY a.lastreplied DESC ';

				break;

		}

		$query		= 'SELECT a.*, COUNT( b.id ) AS count, b.comment AS lastmessage '

					. 'FROM ' . $this->_db->nameQuote( '#__community_groups_discuss' ) . ' AS a '

					. 'LEFT JOIN ' . $this->_db->nameQuote( '#__community_wall' ) . ' AS b ON b.contentid=a.id '

					. 'AND b.`date`=( SELECT max( date ) FROM #__community_wall WHERE `contentid`=a.id) '

					. 'AND b.type=' . $this->_db->Quote( 'discussions' ) . ' '

					. 'LEFT JOIN ' . $this->_db->nameQuote( '#__community_wall' ) . ' AS c ON c.`contentid`=a.`id` '

					. 'AND c.`type`=' . $this->_db->Quote( 'discussions') . ' '

					. 'WHERE a.groupid=' . $this->_db->Quote( $groupId ) . ' '

					. 'AND a.parentid=' . $this->_db->Quote( '0' ) . ' '

					. 'GROUP BY a.id '

					. $orderByQuery;

		$this->_db->setQuery( $query );

		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())

		{

			JError::raiseError( 500, $this->_db->stderr());

		}

		return $result;

	}
	
	function store( $data )
	{
		$my = JFactory::getUser();
		
		$query	= 'SELECT discusscount from #__community_groups where id='.$data['group_id'];
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		$ctr = $result->discusscount + 1;
		
		
		$obj = new stdClass();
		$obj->id = '';
		$obj->parentid = 0;
		$obj->groupid = $data['group_id'];
		$obj->creator 	= $my->id;
		$obj->created = gmdate('Y-m-d H:i:s');
		$obj->title	= strip_tags($data['title']);
		$obj->message		= '<p>'.$data['message'].'</p>';
		$obj->lastreplied = $obj->created;
		$obj->lock = 0;
		$this->_db->insertObject('#__community_groups_discuss', $obj, 'id');
		
		$query	= 'SELECT discusscount from #__community_groups where id='.$data['group_id'];
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		
		$obj1 = new stdClass();
		$obj1->id = $data['group_id'];
		$obj1->discusscount = $ctr;
		$this->_db->updateObject('#__community_groups', $obj1, 'id');
		return $obj;
	}
	
	function getDiscussion( $discussionId )
	{
		$query	= 'SELECT * from #__community_groups_discuss where id='.$discussionId;
		
		$this->_db->setQuery( $query );
		
		$result	= $this->_db->loadObject();
		
		return $result;
	
	}
	
	/**
	 * Method to get the last replier information from specific discussion
	 * 
	 * @params $discussionId	The specific discussion row id
	 **/

	function getLastReplier( $discussionId )

	{

		$query	= 'SELECT * FROM ' . $this->_db->nameQuote( '#__community_wall' ) . ' '

				. 'WHERE ' . $this->_db->nameQuote( 'contentid' ) . '=' . $this->_db->Quote( $discussionId ) . ' '

				. 'AND ' . $this->_db->nameQuote( 'type' ) . '=' . $this->_db->Quote( 'discussions' )

				. 'ORDER BY date DESC LIMIT 1';

		$this->_db->setQuery( $query );

		$result	= $this->_db->loadObject();

		if($this->_db->getErrorNum())
		{
			JError::raiseError( 500, $this->_db->stderr());
		}
		return $result;
	}

	function getRepliers( $discussionId , $groupId )
	{

		$query	= 'SELECT DISTINCT(a.post_by) FROM ' . $this->_db->nameQuote( '#__community_wall' ) . ' AS a '

				. 'INNER JOIN #__community_groups_members AS b '

				. 'ON b.groupid=' . $this->_db->Quote( $groupId ) . ' '

				. 'WHERE ' . $this->_db->nameQuote( 'a.contentid' ) . '=' . $this->_db->Quote( $discussionId ) . ' '

				. 'AND ' . $this->_db->nameQuote( 'a.type' ) . '=' . $this->_db->Quote( 'discussions' ) . ' '

				. 'AND a.post_by=b.memberid';

		$this->_db->setQuery( $query );

		return $this->_db->loadResultArray();

	}
	
	/**
	 *Return a list of discussion replies.
	 * 
	 * @param	int		$topicId	The replies for specific topic id.
	 * @return	Array	An array of database objects.
	 **/	 	 	 	 	

	public function getReplies( $topicId )

	{

		$query	= 'SELECT a.* , b.name FROM #__community_wall AS a '

				. 'INNER JOIN #__users AS b '

				. 'WHERE b.id=a.post_by '

				. 'AND a.type=' . $this->_db->Quote( 'discussions' ) . ' '

				. 'AND a.contentid=' . $this->_db->Quote( $topicId )

				. ' ORDER BY a.date DESC ';

		$this->_db->setQuery( $query );

		if($this->_db->getErrorNum())
		{
			JError::raiseError(500, $this->_db->stderr());
		}
		$result	= $this->_db->loadObjectList();
		return $result;

	}
	/**
	 * Fetches the wall content template and returns the wall data in HTML format
	 *
	 * @param	appType			The application type to load the walls from
	 * @param	uniqueId		The unique id for the specific application	 
	 * @param	isOwner			Boolean value if the current browser is owner of the specific app or profile
	 * @param	limit			The limit to display the walls
	 * @param	templateFile	The template file to use.
	 **/	 	
	function getWallContents( $appType , $uniqueId , $isOwner , $limit = 0 , $limitstart = 0, $templateFile = 'wall.content' , $processFunc = '', $param = null )
	{
		$config   = CFactory::getConfig();			
		$model	= CFactory::getModel( 'wall' );
		
		// Special 'discussions'
		$order = 'DESC';
		
		$walls	= $model->getPost( $appType , $uniqueId , 300, $limitstart, $order);
		
		// Special 'discussions'
		$discussionsTrigger = false;
		$order = $config->get('group_discuss_order');
		if(($appType == 'discussions') && ($order == 'ASC'))
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
	
	function processWallContent($comment)
	{
		// Convert video link to embedded video
		CFactory::load('helpers' , 'videos');
		$comment = CVideosHelper::getVideoLink($comment);
		return $comment;
	}
	function getWallCount($appType , $uniqueId)
	{
		$model	= CFactory::getModel( 'wall' );
		$count	= $model->getCount($uniqueId , $appType);
		return $count;
	}
	
	function SaveDiscussionWall($data)
	{
		$discussion = $this->getDiscussion($data['topic_id']);
		$my = CFactory::getUser();
		$wallid = $this->saveWall( $data['topic_id'] , $data['message'] , 'discussions' , $my , ($my->id == $discussion->creator) , 'groups,discussion');
		// now updating the discussion table's last reply
		$discuss = new stdClass();
		$discuss->id = $data['topic_id'];
		$discuss->lastreplied	= gmdate('Y-m-d H:i:s');
		$this->_db->updateObject('#__community_groups_discuss', $discuss, 'id');
		return $wallid;
		
	}
	
	function saveWall( $uniqueId , $message , $appType , &$creator , $isOwner , $processFunc = '' )
	{
			$my = CFactory::getUser();
			$wall = new stdClass();
			$wall->id 			= '';
			$wall->contentid	= $uniqueId;
			$wall->post_by		= $creator->id;
			$wall->ip			= $_SERVER['REMOTE_ADDR'];
			$wall->comment		= $message;
			$wall->date			= gmdate('Y-m-d H:i:s');
			$wall->published	= 1;
			$wall->type			= $appType;
			$this->_db->insertObject('#__community_wall', $wall, 'id');
			return $wall->id;

	}
	

}