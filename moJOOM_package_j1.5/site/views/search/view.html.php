<?php
/**
 * Search View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class MojoomViewSearch extends JView
{
	function display($tpl = null)
	{
		$friend = $this->get( 'Searchpeople' );

		$this->assignRef( 'Result',	$friend );
		
		parent::display($tpl);
	}
}

