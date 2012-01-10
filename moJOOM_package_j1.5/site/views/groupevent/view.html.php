<?php
/**
 * Group Event View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupevent extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('event','MojoomModel');		
		$my = JFactory::getUser();
		$config		= CFactory::getConfig();
		$data = JRequest::get( 'get' );
		$timezone = $model->getTimezone();
		$category = $model->getCategory();
		$event = $model->getGroupEvents($data['group_id']);
		$total = $model->getTotalGroupEvents($data['group_id']);
		$event_count = $model->getEventsCreationCount();
		$GroupOwner = $model->getGroupOwner($data['group_id']);
		
		
		$this->assignRef( 'Myevent',	$myevent );
		$this->assignRef( 'Timezone',	$timezone );
		$this->assignRef( 'Category',	$category );
		$this->assignRef( 'username',	$username );
		$this->assignRef( 'Event',	$event );
		$this->assignRef( 'Event_Count',	$event_count );
		$this->assignRef( 'GroupOwner',	$GroupOwner );
		$this->assignRef( 'eventcreatelimit'	, $config->get('eventcreatelimit') );
		parent::display($tpl);
	}
}

