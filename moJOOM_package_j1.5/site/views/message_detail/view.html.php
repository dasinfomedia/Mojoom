<?php
/**
 * Message Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewMessage_detail extends JView
{
	function display($tpl = null)
	{
		$mainframe =& JFactory::getApplication();
		$model = & JModel::getInstance('message','MojoomModel');
		$msgId = JRequest::getVar ( 'id', '', 'REQUEST' );
		$task = JRequest::getVar ( 'task', '', 'REQUEST' );
		$my = & JFactory::getUser ();
		
		$filter = array ();
		
		$filter ['msgId'] = $msgId;
		$filter ['to'] = $my->id;
		if($task == 'inbox')
		{
			$filter ['parent'] = $msgId;
			$filter ['user_id'] = $my->id;
			$read = $model->markAsRead($filter);	
		}
		$detail = $model->getMessages($filter);
		$this->assignRef( 'Detail',	$detail );
		parent::display($tpl);
	}
}

