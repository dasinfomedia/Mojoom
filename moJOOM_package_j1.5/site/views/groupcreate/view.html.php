<?php
/** 
 * Group Create View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupcreate extends JView
{
	function display($tpl = null)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config		= CFactory::getConfig();
		$user = JFactory::getUser();
		$model = & JModel::getInstance('pgroup','MojoomModel');
		
		$groupid = JRequest::getVar('group_id',0);
		if($groupid != 0)
		{
			$group = $model->getGroup($groupid);
			$params = $model->getParams($groupid);
			$this->assignRef( 'groupinfo',	$group );
			$this->assignRef( 'params',	$params );
			$this->assignRef( 'groupid',	$groupid );
		}
		$totalGroup	= $model->getGroupsCreationCount($user->id);
		
		$gpcat = $model->getGroupCategories();
		
		//$posts = $model->getPost('user',$user->id);
		
		$this->assignRef( 'groupCreated',	$totalGroup );
		$this->assignRef( 'cat',	$gpcat );
		$this->assignRef( 'config'	, $config );
		parent::display($tpl);
	}
	
}