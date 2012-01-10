<?php 
/** 
 * Group Wall View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroup_wall extends JView
{
	function display($tpl = null)
	{
		$model	= $this->getModel();
		$model1 = & JModel::getInstance('pgroup','MojoomModel');
		
		$user = JFactory::getUser();
		$groupid = JRequest::getVar('group_id',0);
		//$group = $dmodel->getGroup($groupid);
		$limit		= JRequest::getVar( 'limit' , 5 , 'REQUEST' );
		$limitstart = JRequest::getVar( 'limitstart', 0, 'REQUEST' );
		$userActivities	= $model->getPost('groups',$groupid,$limit, $limitstart );
		$this->assignRef( 'activities',	$userActivities );

		$this->assignRef( 'my',	$my );
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config			= CFactory::getConfig();
		$my = CFactory::getUser();
		
		
		$isGroupAdmin	=   $model->isAdministrator( $my->id , $groupid );
		$this->assignRef( 'isGroupAdmin' , $isGroupAdmin );
		$isMember	    =	$model1->isMember( $my->id , $groupid );
		$this->assignRef( 'ismember' , $isMember );
		//echo $isGroupAdmin;
		$wallContent	= $model->getWallContents( 'groups' ,$groupid , $isGroupAdmin , 10 ,0 , 'wall.content' , 'groups,group');
		$this->assignRef( 'wallContent',	$wallContent );
		
		parent::display($tpl);
	}
	
	function getAvatar($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT thumb FROM #__community_users WHERE userid='.$id;
		$db->setQuery( $query );
		$result	= $db->loadObject();
		return $result->thumb;
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
	
}

