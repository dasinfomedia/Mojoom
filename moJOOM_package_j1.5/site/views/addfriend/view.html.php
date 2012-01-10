<?php
/** 
 * Add friend View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

 
class MojoomViewAddfriend extends JView
{
	function display($tpl = null)
	{
		$user_id = JRequest::getVar('user_id',0);
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		
		 $my 			= CFactory::getUser();
		 $user  			= CFactory::getUser($user_id);
		 
		 $model = & JModel::getInstance('friend','MojoomModel');
		 $connection		= $model->getFriendConnection( $my->id , $user_id );
		 
		$errflag = '';
		
		if(count( $connection ) > 0 )
		{
			if( $connection[0]->connect_from == $my->id )
			{
				$errflag = JText::sprintf('ALREADY REQUESTED TO ADD',$user->getDisplayName());
			}
			else
			{
				$errflag = JText::sprintf('ALREADY REQUESTED PLEASE CHECK',$user->getDisplayName());
			}
		}
		$this->assignRef( 'user_id',	$user_id);
		$this->assignRef( 'user',	$user);
		$this->assignRef( 'errflag',	$errflag);
		
		parent::display($tpl);
	}
	
}