<?php
/**
 * Profile Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewProfile_detail extends JView
{
	function display($tpl = null)
	{
		$profile = $this->get( 'Profile' );
		//$isNew		= ($swimming->id < 1);	
		$this->assignRef( 'profile',	$profile );
		
		parent::display($tpl);
	}
}

