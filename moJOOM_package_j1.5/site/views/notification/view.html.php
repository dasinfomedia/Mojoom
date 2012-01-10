<?php
/**
 * Notification View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewNotification extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('Mojoom','MojoomModel');
		$mainframe =& JFactory::getApplication();
		
		$msg = $model->getNotification_msg();
		$frd = $model->getNotification_frd();
		$event = $model->getNotification_event();
		
		$this->assignRef( 'Notification_msg',	$msg );
		$this->assignRef( 'Notification_frd',	$frd );
		$this->assignRef( 'Notification_event',	$event );
		
		parent::display($tpl);
	}
}

