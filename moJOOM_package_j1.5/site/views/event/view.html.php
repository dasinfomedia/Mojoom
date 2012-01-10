<?php
/**
 * Event View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
ini_set("display_errors","0");
 
class MojoomViewEvent extends JView
{
	function display($tpl = null)
	{
		$user =& JFactory::getUser();
		$user_id = $user->get('id');
		$config		= CFactory::getConfig();
		$model = & JModel::getInstance('profile_edit','MojoomModel');
		
		$username = $user->get('username'); 
		$timezone = $this->get( 'Timezone' );
		$category = $this->get( 'Category' );
		$event = $this->get( 'Event' );
		$event_count = $this->get('EventsCreationCount');
		
		$s_date = @explode(' ',$event->startdate);
		$s_date1 = @explode('-',$s_date[0]);
		
		$e_date = @explode(' ',$event->enddate);
		$e_date1 = @explode('-',$e_date[0]);
		
		$createYears1 = $model->createYears(date('Y'),2015, 's_year',$s_date1[0]);
		$createMonths1 = $model->createMonths('s_month',$s_date1[1]);
		$createDays1 = $model->createDays('s_day',$s_date1[2]);
		
		$createYears2 = $model->createYears(date('Y'),2015, 'e_year',$e_date1[0]);
		$createMonths2 = $model->createMonths('e_month',$e_date1[1]);
		$createDays2 = $model->createDays('e_day',$e_date1[2]);
		
		$this->assignRef( 'Timezone',	$timezone );
		$this->assignRef( 'Category',	$category );
		$this->assignRef( 'username',	$username );
		$this->assignRef( 'Event',	$event );
		$this->assignRef( 'Event_Count',	$event_count );
		$this->assignRef( 'eventcreatelimit'	, $config->get('eventcreatelimit') );
		
		$this->assignRef( 'createYears1',	$createYears1 );
		$this->assignRef( 'createMonths1',	$createMonths1 );
		$this->assignRef( 'createDays1',	$createDays1 );
		
		$this->assignRef( 'createYears2',	$createYears2 );
		$this->assignRef( 'createMonths2',	$createMonths2 );
		$this->assignRef( 'createDays2',	$createDays2 );
		
		parent::display($tpl);
	}
}

