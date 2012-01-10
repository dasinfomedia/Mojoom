<?php
/** 
 * Group My Invite View for mojoom Component
 * 
 * @package    mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewGroupmyinvite extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('pgroup','MojoomModel');
		$sorted			= JRequest::getVar( 'sort' , 'latest' );

		$my				= JFactory::getUser();
		$rows		= $model->getGroupInvites( $my->id );
		
		$groups		= array();
		$ids		= '';

		if( $rows )
		{
			foreach( $rows as $row )
			{
				$gdata = $model->getGroup($row->groupid);
				$groups[]	= $gdata;
				$ids		= (empty($ids)) ? $gdata->id : $ids . ',' . $gdata->id;
			}
		}
	//	print_r($groups);
		
		$this->assignRef( 'my',	$my );
		$this->assignRef( 'groups',	$groups );
		
		parent::display($tpl);
	}
	
}