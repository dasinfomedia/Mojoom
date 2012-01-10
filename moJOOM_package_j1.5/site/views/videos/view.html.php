<?php
/**
 * Videos View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewVideos extends JView
{
	function display($tpl = null)
	{
		$model	= $this->getModel();
		$sorted 	=   JRequest::getVar('sort', 'latest');
		//get the category id
		$filters	  = array
		(

			'status'		=> 'ready',

			'category_id'	=> 1,

			'sorting'		=> $sorted

		);
		$userid = JRequest::getVar('user_id',0);
		$videos			= $model->getVideos($userid,$filters);
		$width = 112;
		$height = 84;
		$this->assignRef( 'videos',	$videos );
		$this->assignRef( 'videoThumbWidth', $width );
		$this->assignRef( 'videoThumbHeight', $height );
		$this->assignRef( 'user_id',	$userid );
		parent::display($tpl);
	}
	
	function formatDuration($duration = 0, $format = 'HH:MM:SS')

	{

		if ($format == 'seconds' || $format == 'sec') {

			$arg = explode(":", $duration);

	

			$hour	= isset($arg[0]) ? intval($arg[0]) : 0;

			$minute	= isset($arg[1]) ? intval($arg[1]) : 0;

			$second	= isset($arg[2]) ? intval($arg[2]) : 0;

	

			$sec = ($hour*3600) + ($minute*60) + ($second);

			return (int) $sec;

		}

	

		if ($format == 'HH:MM:SS' || $format == 'hms') {

			$timeUnits = array

			(

				'HH' => $duration / 3600 % 24,

				'MM' => $duration / 60 % 60,

				'SS' => $duration % 60

			);

	

			$arg = array();

			foreach ($timeUnits as $timeUnit => $value) {

				$arg[$timeUnit] = ($value > 0) ? $value : 0;

			}

	

			$hms = '%02s:%02s:%02s';

			$hms = sprintf($hms, $arg['HH'], $arg['MM'], $arg['SS']);

			return $hms;

		}

	}
	function toNiceHMS($hms)

	{

		$arr	= array();

		$arr	= explode(':', $hms);

	

		if ($arr[0] == '00') {

			array_shift($arr);

		}

	

		return implode(':', $arr);

	}
	
	function getusername($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT name,username FROM #__users WHERE id='.$id;
		$db->setQuery( $query );
		$result	= $db->loadObject();
		return $result;
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

					$html	.= JText::_( 'yesterday' );



				break;

				case ( $days > 1 && $days < 7 && $days < 30 ):

				

					// @rule: Something that happened within the past 7 days

					$html	.= JText::sprintf( '%1$s days' , $days ) . ' ';



				break;

				case ( $days > 1 && $days > 7 && $days < 30 ):

				

					// @rule: Something that happened within the month but after a week

					$weeks	= round( $days / 7 );

					$html	.= JText::sprintf( $this->_isPlural( $weeks ) ? '%1$s weeks' : '%1$s week' , $weeks ) . ' ';	



				break;

				case ( $days > 30 && $days < 365 ):

				

					// @rule: Something that happened months ago

					$months	= round( $days / 30 );

					$html	.= JText::sprintf( $this->_isPlural( $months ) ? '%1$s months' : '%1$s month' , $months ) . ' ';



				break;

				case ( $days > 365 ):

				

					// @rule: Something that happened years ago

					$years	= round( $days / 365 );

					$html	.= JText::sprintf( $this->_isPlural( $years ) ? 'YEARS' : 'YEAR' , $years ) . ' ';



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
	
					trigger_error( JText::_('DATE TIME INFO'), E_USER_WARNING );
	
				}
	
			}
	
			else
	
			{
	
				trigger_error( JText::_("INVALID DATE TIME"), E_USER_WARNING );
	
			}
	
			return( false );
	
		
		}
		function _isPlural($num)
		{
			return !$this->_isSingular($num);
		}
		
		function _isSingular($num)
		{
	
			$singularnumbers = 1;
			$singularnumbers = explode(',', $singularnumbers);
			return in_array($num, $singularnumbers);
		}

}

