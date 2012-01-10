<?php
/**
 * Group Event Create View for mojoom Component
 * 
 * @package    mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
 
class MojoomViewGroupevent_create extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('event','MojoomModel');		
		$my = JFactory::getUser();
		$config		= CFactory::getConfig();
		$group_id = JRequest::getVar( 'group_id' );
		$timezone = $model->getTimezone();
		$category = $model->getCategory();
		$event = $model->getEvent();
		$total = $model->getTotalGroupEvents($data['group_id']);
		$event_count = $model->getEventsCreationCount();
		
		$profile_edit = & JModel::getInstance('profile_edit','MojoomModel');
		
		$s_date = @explode(' ',$event->startdate);
		$s_date1 = @explode('-',$s_date[0]);
		
		$e_date = @explode(' ',$event->enddate);
		$e_date1 = @explode('-',$e_date[0]);
		
		$createYears1 = $profile_edit->createYears(date('Y'),2015, 's_year',$s_date1[0]);
		$createMonths1 = $profile_edit->createMonths('s_month',$s_date1[1]);
		$createDays1 = $profile_edit->createDays('s_day',$s_date1[2]);
		
		$createYears2 = $profile_edit->createYears(date('Y'),2015, 'e_year',$e_date1[0]);
		$createMonths2 = $profile_edit->createMonths('e_month',$e_date1[1]);
		$createDays2 = $profile_edit->createDays('e_day',$e_date1[2]);
		
		$this->assignRef( 'Timezone',	$timezone );
		$this->assignRef( 'Category',	$category );
		$this->assignRef( 'username',	$username );
		$this->assignRef( 'Event',	$event );
		$this->assignRef( 'Event_Count',	$event_count );
		$this->assignRef( 'eventcreatelimit'	, $config->get('eventcreatelimit') );
		$this->assignRef( 'group_id'	, $group_id );
		
		$this->assignRef( 'createYears1',	$createYears1 );
		$this->assignRef( 'createMonths1',	$createMonths1 );
		$this->assignRef( 'createDays1',	$createDays1 );
		
		$this->assignRef( 'createYears2',	$createYears2 );
		$this->assignRef( 'createMonths2',	$createMonths2 );
		$this->assignRef( 'createDays2',	$createDays2 );
		
		parent::display($tpl);
	}
}

