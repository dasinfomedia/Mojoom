<?php
/**
 * Like View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewLike extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('event','MojoomModel');		
		$my = JFactory::getUser();
		$user_id = $my->id;		
		$data = JRequest::get( 'get' ); 		
		if($data['item'] == 1)
		{		
			if($data['type'] == 1)
			{
				$Like	 = $model->setLike('events',$data['id'],$data['uid']);		 	
			}
			else
			{
				$Dislike	 = $model->setDislike('events',$data['id'],$data['uid']);
			}
			
			$Info	 = $model->getInfo('events',$data['id']);		 
					
			$like = false;
			$dislike = false;
			$like = strpos($Info->like,$user_id.',');
			$dislike = strpos($Info->dislike,$user_id.',');
			
			$this->assignRef( 'like',	$like );
			$this->assignRef( 'dislike',	$dislike );								
		}
		if($data['item'] == 2)
		{
			
			if($data['type'] == 1)
			{
				$Like	 = $model->setLike('groups',$data['id'],$data['uid']);		 	
			}
			else
			{
				$Dislike	 = $model->setDislike('groups',$data['id'],$data['uid']);
			}
			
			$Info	 = $model->getInfo('groups',$data['id']);		 
					
			$like = false;
			$dislike = false;
			$like = strpos($Info->like,$user_id.',');
			$dislike = strpos($Info->dislike,$user_id.',');
			
			$this->assignRef( 'like',	$like );
			$this->assignRef( 'dislike',	$dislike );				
		}
		parent::display($tpl);
	}
}

