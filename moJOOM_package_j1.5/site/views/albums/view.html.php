<?php
/**
 * Alubms View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewAlbums extends JView
{
	function display($tpl = null)
	{
		$model	= $this->getModel();
		// 2nd argument for the PHOTOS_USER_TYPE and PHOTOS_GROUP_TYPE but here for individual so it is user
		$userid = JRequest::getVar('user_id',0);
 		$albums	= $model->getAlbums( $userid ,'user');
 		//$albums	= $model->getAlbums( $user->id ,'user');
		$this->assignRef( 'albums',	$albums );
		$this->assignRef( 'user_id',	$userid );
		parent::display($tpl);
	}
	
	function createdLapse(&$date)
	{
		

		$now	=& JFactory::getDate();

		$html	= '';

		$diff	= $this->_timeDifference($date->toUnix(), $now->toUnix());

		if( !empty($diff['days']) )

		{

			$days		= $diff['days'];

			$months		= ceil( $days / 30 );

			if( $days == 1 )

			{

				

			}

			

			switch( $days )

			{

				case ($days == 1):

				

					// @rule: Something that happened yesterday

					$html	.= JText::_( 'YESTERDAY' );



				break;

				case ( $days > 1 && $days < 7 && $days < 30 ):

				

					// @rule: Something that happened within the past 7 days

					$html	.= JText::sprintf( 'DAYS' , $days ) . ' ';



				break;

				case ( $days > 1 && $days > 7 && $days < 30 ):

				

					// @rule: Something that happened within the month but after a week

					$weeks	= round( $days / 7 );

					$html	.= JText::sprintf( CStringHelper::isPlural( $weeks ) ? 'WEEKS' : 'WEEK' , $weeks ) . ' ';	



				break;

				case ( $days > 30 && $days < 365 ):

				

					// @rule: Something that happened months ago

					$months	= round( $days / 30 );

					$html	.= JText::sprintf( CStringHelper::isPlural( $months ) ? 'MONTHS' : 'MONTH' , $months ) . ' ';



				break;

				case ( $days > 365 ):

				

					// @rule: Something that happened years ago

					$years	= round( $days / 365 );

					$html	.= JText::sprintf( CStringHelper::isPlural( $years ) ? 'YEARS' : 'YEAR' , $years ) . ' ';



				break;

			}

		}

		else

		{

			// We only show he hours if it is less than 1 day

			if(!empty($diff['hours']))				

				$html .= JText::sprintf('HOURS', $diff['hours']) . ' ';

			

			if(!empty($diff['minutes']))

				$html .= JText::sprintf('MINUTES', $diff['minutes']) . ' ';

		}

		

		if(empty($html)){

			$html .= JText::_('LESS THEN MINUTES');

		}

		

		if($html != JText::_('YESTERDAY'))

			$html .= JText::_('AGO');

		return $html;

	}

	function getusername($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT name,username FROM #__users WHERE id='.$id;
		$db->setQuery( $query );
		$result	= $db->loadObject();
		return $result;
	}
	
	function _timeDifference($start , $end )
	{
		jimport('joomla.utilities.date');
	
			if(is_string($start) && ($start != intval($start))){
	
				$start = new JDate($start);
	
				$start = $start->toUnix();
	
			}
		
			if(is_string($end) && ($end != intval($end) )){
	
				$end = new JDate($end);
	
				$end = $end->toUnix();
	
			}
	
			$uts = array();
	
			$uts['start']      =    $start ;
	
			$uts['end']        =    $end ;
	
			if( $uts['start']!==-1 && $uts['end']!==-1 )
	
			{
	
				if( $uts['end'] >= $uts['start'] )
	
				{
	
					$diff    =    $uts['end'] - $uts['start'];
	
					if( $days=intval((floor($diff/86400))) )
	
						$diff = $diff % 86400;
	
					if( $hours=intval((floor($diff/3600))) )
	
						$diff = $diff % 3600;
	
					if( $minutes=intval((floor($diff/60))) )
	
						$diff = $diff % 60;
	
					$diff    =    intval( $diff );            
	
					return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	
				}
	
				else
	
				{
	
					trigger_error( JText::_("DATE TIME INFO"), E_USER_WARNING );
	
				}
	
			}
	
			else
	
			{
	
				trigger_error( JText::_("INVALID DATE TIME"), E_USER_WARNING );
	
			}
	
			return( false );
	
		
		}
}

