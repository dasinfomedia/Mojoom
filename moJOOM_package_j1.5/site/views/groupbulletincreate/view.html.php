<?php
/** 
 * Group Bulletin Create View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');


class MojoomViewGroupbulletincreate extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$dismodel = & JModel::getInstance('groupbulletin','MojoomModel');
		
		$my = JFactory::getUser();
		$groupid = JRequest::getVar('group_id',0);
		$editor =& JFactory::getEditor();	
		
		$group = $model->getGroup($groupid);
		
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config			= CFactory::getConfig();
		
		$this->assignRef('editor',	$editor);
		$this->assignRef('group',	$group);
		$this->assignRef('config',	$config);
		
		parent::display($tpl);
	}
	
}