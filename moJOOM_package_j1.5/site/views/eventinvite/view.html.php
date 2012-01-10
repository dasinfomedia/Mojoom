<?php
/**
 * Event invite View for Mojoom Component
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewEventinvite extends JView
{
	function display($tpl = null)
	{ 
		$model = & JModel::getInstance('event','MojoomModel');		
		$my = JFactory::getUser();
		
		$Member		= $model->getMember($my->id);
		
		$this->assignRef( 'user_id',	$my->id );
		$this->assignRef( 'Member',	$Member );
		parent::display($tpl);
	}
}

