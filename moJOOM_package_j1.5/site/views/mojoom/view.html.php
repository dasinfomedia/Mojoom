<?php
/**
 * Mojoom View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewMojoom extends JView
{
	function display($tpl = null) 
	{
		$profile = $this->get( 'Profile' );
		$msg = $this->get( 'Notification_msg' );
		$frd = $this->get( 'Notification_frd' );
		$event = $this->get( 'Notification_event' );
		
		$notification = count($msg) + count($frd) + count($event);
		
		$this->assignRef( 'profile',	$profile );
		$this->assignRef( 'Notification',	$notification );
				
		parent::display($tpl);
	}
}