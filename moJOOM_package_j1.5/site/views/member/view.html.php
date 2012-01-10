<?php
/**
 * Member View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewMember extends JView
{
	function display($tpl = null)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
		$config 	= CFactory::getConfig();
		$limit = $config->get('frontpageusers');
		$model1 = CFactory::getModel('user');
		
		$latestMembers = $model1->getLatestMember( $limit );
			

		$this->assignRef( 'friend',	$latestMembers );
		
		parent::display($tpl);
	}
}

