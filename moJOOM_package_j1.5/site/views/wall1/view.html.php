<?php 
/** 
 * Wall1 View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');

class MojoomViewWall1 extends JView
{
	function display($tpl = null)
	{
		$user_id = JRequest::getVar('user_id',JFactory::getUser()->id);
		$model	= $this->getModel();
		$user = JFactory::getUser();
		$userActivities	= $model->getHTML('', '', null, 0 , '' , '', true , false );
		$this->assignRef( 'activities',	$userActivities );
		$this->assignRef( 'user_id',	$user_id );
		parent::display($tpl);
	}
	
	function getAvatar($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT thumb FROM #__community_users WHERE userid='.$id;
		$db->setQuery( $query );
		$result	= $db->loadObject();
		return $result->thumb;
	}
	
}