<?php
/**
 * Profile Page View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewProfile_page extends JView
{
	function display($tpl = null)
	{
		$profile = $this->get( 'Profile' );
		$notification = $this->get( 'Notification' );
		
		$this->assignRef( 'profile',	$profile );
		$this->assignRef( 'Notification',	$notification );
		
		parent::display($tpl);
	}
}

