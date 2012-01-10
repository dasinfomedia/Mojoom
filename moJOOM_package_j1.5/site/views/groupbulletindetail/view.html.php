<?php
/** 
 * Group Bulletin Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupbulletindetail extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$bullmodel = & JModel::getInstance('groupbulletin','MojoomModel');
		

		$groupId = JRequest::getVar('group_id',0);
		$bulletinId		= JRequest::getVar( 'bulletinid' , 0 );
		
		$group = $model->getGroup($groupId);
		$bulletin = $bullmodel->getBulletin( $bulletinId );
		
		
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config			= CFactory::getConfig();
		$my = CFactory::getUser();
		
		// Get the discussion creator info
		$creator		= CFactory::getUser( $discussion->creator );
		$isGroupAdmin	=   $model->isAdministrator( $my->id , $group->id );
		
		
		//$params = $model->getParams($groupId);
		//$discussions	= $dismodel->getDiscussionTopics( $group->id , 0 ,  $params->get('discussordering' , 0) );
	
		// assign data to the template
		
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'bulletin',	$bulletin );
		$this->assignRef( 'my',	$my );
		$this->assignRef( 'creator',	$creator );
		
		
		
		parent::display($tpl);
	}
	
}