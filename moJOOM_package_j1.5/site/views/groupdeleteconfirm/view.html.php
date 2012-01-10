<?php
/** 
 * Group Delete Confirm View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');


class MojoomViewGroupdeleteconfirm extends JView
{
	function display($tpl = null)
	{
		//$my = JFactory::getUser();
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$groupid = JRequest::getVar('group_id',0);
		$group = $model->getGroup($groupid);

		$this->assignRef( 'group',	$group );
		$this->assignRef( 'params',	$params); 
		parent::display($tpl);
	}
	
}