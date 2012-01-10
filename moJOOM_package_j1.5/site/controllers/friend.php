<?php
/**
 * friend Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * friend Controller
 *
 * @package    Mojoom
 * @subpackage Components
 */
 /* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
class MojoomControllerFriend extends MojoomController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		
	}
	
	function addfriend() 
	{
		//@todo filter paramater
		//$objResponse   = new JAXResponse();
		$model =& $this->getModel('friend');
		$my = JFactory::getUser();
		
		if($my->id == 0)
		{
		  // return $this->ajaxBlockUnregister();
		}		

		$postVars =  JRequest::get( 'post' );
		$id = $postVars['userid']; //get it from post
		$msg = strip_tags($postVars['msg']);
		$data  = JFactory::getUser($id);

		if($model->addFriend($id, $my->id, $msg))
			{
				// 27-12-2011 M Change.
				$msg = JText::sprintf( "'".$model->getDisplayName($id)."' WILL RECEIVE YOUR REQUEST '".$model->getDisplayName($id)."' WILL HAVE TO CONFIRM FRIEND" );
				//$msg = JText::sprintf('CONFIRM FRIENDS', $model->getDisplayName($id), $model->getDisplayName($id) )
			}
			else
			{
				$msg = JText::_( 'ERROR IN INVITATION' );
			}
			//echo $msg;
			$link = 'index.php?option=com_mojoom&view=mojoom&layout=default&user_id='.$id;
			$this->setRedirect($link, $msg);
				
	}
	
	function accept() 
	{
		$my = JFactory::getUser();
		$model =& $this->getModel('friend');
		$my = JFactory::getUser();
		$postVars =  JRequest::get( 'get' );
		//print_r($postVars);
		$requestId = $model->isMyRequest( $postVars['user_id'] , $my->id);
		if( $requestId != 0)		
		{
			//echo $requestId; 
			$connected		= $model->approveRequest( $requestId );
			if($connected)
			{
				$act			= new stdClass();
				$act->cmd 		= 'friends.request.approve';
				$act->actor   	= $connected[0];
				$act->target  	= $connected[1];
				$act->title	  	= JText::_('ACTOR AND TARGET ARE NOW FRIEND');
				$act->content	= '';
				$act->app		= 'friends';
				$act->cid		= 0;

				CFactory::load ( 'libraries', 'activities' );
				CActivityStream::add($act);
				$msg = JFactory::getUser($connected[0])->name . " AND YOU ARE FRIEN NOW";
			}
			else
			{   // 27-12-2011 M Change.
				$msg =JText::_("ERROR IN ACCEPT");
			}
			$link = 'index.php?option=com_mojoom&view=mojoom&layout=default';
			$this->setRedirect($link, $msg);
		}
	}
	
	/**
	 * Method to cancel a friend request
	 */
	public function reject()
	{
		$my		= JFactory::getUser();
		$model =& $this->getModel('friend');		
		$getVars =  JRequest::get( 'get' );	

		if($model->deleteSentRequest($getVars['user_id'],$my->id))
		{
			$msg	= JText::_('CC FRIEND REQUEST CANCELED');
			
			//add user points - friends.request.cancel removed @ 20090313
		}
		else
		{
			$msg	= JText::_('CC FRIEND REQUEST CANCELLED ERROR');
		}

		$link = 'index.php?option=com_mojoom&view=mojoom&layout=default&user_id='.$my->id;
		$this->setRedirect($link, $msg);
	}
	/**
	 * Method to cancel a friend request
	 */
	public function remove_friend()
	{
		$my		= JFactory::getUser();
		$model =& $this->getModel('friend');		
		$getVars =  JRequest::get( 'get' );	

		if($model->deleteFriend($getVars['user_id'],$my->id))
		{
			$msg	= JText::_('FRIEND REMOVED');			
		}
		else
		{
			$msg	= JText::_('FRIEND REMOVED ERROR');
		}

		$link = 'index.php?option=com_mojoom&view=friend&layout=default';
		$this->setRedirect($link, $msg);
	}

}