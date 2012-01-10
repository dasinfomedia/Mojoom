<?php
/** 
 * Group Join View for mojoom Component
 * 
 * @package    mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupjoin extends JView
{
	function display($tpl = null)
	{
		//$model	= $this->getModel();
		
		//$posts = $model->getPost('user',$user->id);
		
		//$this->assignRef( 'posts',	$posts );
		
		
		//$my = JFactory::getUser();
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$groupid = JRequest::getVar('group_id',0);
		$group = $model->getGroup($groupid);
		$params = $model->getParams($groupid);
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'params',	$params); 
		parent::display($tpl);
	}
	
}