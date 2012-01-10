<?php
/** 
 * Group Discussion create View for mojoom Component
 * 
 * @package    mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupdiscussiondetail extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$dismodel = & JModel::getInstance('groupdiscussion','MojoomModel');
		

		$groupId = JRequest::getVar('group_id',0);
		$topicId		= JRequest::getVar( 'topicid' , 0 );
		
		$group = $model->getGroup($groupId);
		$discussion = $dismodel->getDiscussion( $topicId );
		//print_r($discussion);
		
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config			= CFactory::getConfig();
		$my = CFactory::getUser();
		
		// Get the discussion creator info
		$creator		= CFactory::getUser( $discussion->creator );
		$isGroupAdmin	=   $model->isAdministrator( $my->id , $group->id );
		
		
		$wallContent	= $dismodel->getWallContents( 'discussions' , $discussion->id , $isGroupAdmin , '',0, 'wall.content','groups,discussion');
		
	
		//$params = $model->getParams($groupId);
		//$discussions	= $dismodel->getDiscussionTopics( $group->id , 0 ,  $params->get('discussordering' , 0) );
	
		// assign data to the template
		
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'discussion',	$discussion );
		$this->assignRef ( 'creator', $creator );
		$this->assignRef( 'config',	$config );
		$this->assignRef( 'wallContent',	$wallContent );
		$this->assignRef( 'my',	$my );
		$this->assignRef( 'topicId',	$topicId );
		
		
		parent::display($tpl);
	}
	
}