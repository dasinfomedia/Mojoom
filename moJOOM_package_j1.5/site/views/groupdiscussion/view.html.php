<?php
/** 
 * Group Discussion View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupdiscussion extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$dismodel = & JModel::getInstance('groupdiscussion','MojoomModel');
		$my = JFactory::getUser();

		$groupid = JRequest::getVar('group_id',0);
		$group = $model->getGroup($groupid);
		$params = $model->getParams($groupid);
		$discussions	= $dismodel->getDiscussionTopics( $group->id , 0 ,  $params->get('discussordering' , 0) );
	
		// assign data to the template
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'discussions',	$discussions );
		
		parent::display($tpl);
	}
	
}