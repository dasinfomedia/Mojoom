<?php
/** 
 * Groups View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroups extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$sorted	    =	JRequest::getVar( 'sort' , 'latest' , 'GET' );
		$categiryid = JRequest::getVar('category_id',0);
		$userid = JRequest::getVar('user_id',0);
		
		
		$gpcat = $model->getGroupCategories();
		if($userid == 0)
		{
			$allgroups = $model->getAllGroups($categiryid,$sorted);
		}
		else
		{
			$allgroups = $model->getMyGroups($userid,$sorted);
		}
		
		$this->assignRef( 'groups',	$allgroups );
		$this->assignRef( 'cat',	$gpcat );
		$this->assignRef( 'category_id',	$categiryid );
		$this->assignRef( 'user_id',	$userid );

		parent::display($tpl);
	}
	
}