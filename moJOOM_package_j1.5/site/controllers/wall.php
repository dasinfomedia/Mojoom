<?php
/**
 * Event Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Event Controller
 *
 * @package    Mojoom
 * @subpackage Components 
 */
/* com_community component's core.php file included to be able to use the core classes of the component */ 
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class MojoomControllerWall extends MojoomController 
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}
	function save()
	{
		$model = $this->getModel('wall1');

		if ($model->wall($post)) {
			$msg = JText::_( 'WALL SAVED' );
		} else {
			$msg = JText::_( 'ERROR SAVING WALL' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&view=wall1';
		$this->setRedirect($link, $msg);
	}
	
}