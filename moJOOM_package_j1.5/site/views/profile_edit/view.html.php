<?php
/**
 * Profile Edit View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
ini_set("display_errors","0");

class MojoomViewProfile_edit extends JView
{
	function display($tpl = null)
	{
		$model	= $this->getModel();
		$profile = $this->get( 'Profile' );
		$b_date = @explode('-',$profile[1]->value);
		$createYears = $model->createYears(1959, date('Y'), 'b_year',$b_date[0]);
		$createMonths = $model->createMonths('b_month',$b_date[1]);
		$createDays = $model->createDays('b_day',$b_date[2]);
		
		$user =& JFactory::getUser();
		$username = $user->get('username');
		$this->assignRef( 'profile',	$profile );
		$this->assignRef( 'username',	$username );
		$this->assignRef( 'createYears',	$createYears );
		$this->assignRef( 'createMonths',	$createMonths );
		$this->assignRef( 'createDays',	$createDays );
		parent::display($tpl);
	}
}

