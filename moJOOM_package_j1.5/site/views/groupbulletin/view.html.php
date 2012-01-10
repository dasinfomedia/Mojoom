<?php
/** 
 * Group Bulletin View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupbulletin extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$bulmodel = & JModel::getInstance('groupbulletin','MojoomModel');
		$my = JFactory::getUser();

		$groupid = JRequest::getVar('group_id',0);
		$group = $model->getGroup($groupid);
		$bulletins		= $bulmodel->getBulletins( $group->id );
		
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		
		// Get the creator of the bulletins
		for( $i = 0; $i < count( $bulletins ); $i++ )
		{
			$row			=& $bulletins[ $i ];
			$row->creator	= CFactory::getUser( $row->created_by );
		}
		
	
		// assign data to the template
		$this->assignRef( 'group',	$group );
		$this->assignRef( 'bulletins'	, $bulletins );
		
		parent::display($tpl);
	}
	
}