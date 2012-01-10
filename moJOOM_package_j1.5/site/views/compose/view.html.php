<?php
/**
 * Compose View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewCompose extends JView
{
	function display($tpl = null)
	{
		$Id = JRequest::getVar ( 'id', '', 'POST' );
		$Id1 = JRequest::getVar ( 'id', '', 'GET' );
		if($Id || $Id1)
		{
			$model = & JModel::getInstance('message','MojoomModel');
			if($Id) { $to = $model->getUser($Id);  }
			else { $to = $model->getUser($Id1);  }
			
			$flage = 1; 
			$this->assignRef( 'to',	$to );
			$this->assignRef( 'flage',	$flage );
		}
		else
		{
			$model = & JModel::getInstance('friend','MojoomModel');
			$mainframe =& JFactory::getApplication();
			$my	= JFactory::getUser();
			$id = JRequest::getCmd('userid', 0 );		
			if( $id == 0 )
			{
				$id	= $my->id;
			}		
			$sorted		= JRequest::getVar( 'sort' , 'latest' , 'GET' );
			$filter		= JRequest::getWord( 'filter' , 'all' , 'GET' );
			$friend = $model->getFriends($id , $sorted , true , $filter );
			$flage = 0;
			$this->assignRef( 'friends',	$friend );
			//$this->assignRef( 'flage',	$flage );
		}
		parent::display($tpl);
	}
}

