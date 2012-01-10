<?php
/** 
 * Group Members View for mojoom Component
 * 
 * @package   mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupmembers extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');

		$groupid = JRequest::getVar('group_id',0);
		
		$groupmembers = $model->getMembers($groupid);
		$group = $model->getGroup($groupid);
		
		$this->assignRef( 'members',	$groupmembers );
		$this->assignRef( 'group',	$group );

		parent::display($tpl);
	}
	
}