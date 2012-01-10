<?php
/**
 * Mojoomconfig Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Mojooomconfig Controller
 *
 * @package    Mojoom
 * @subpackage Components 
 */
class MojoomsControllerMojoomconfig extends MojoomsController
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

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'pmss_edit' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('mojooms');

		if ($model->store($post)) {
			$msg = JText::_( 'CONFIG SETTING' );
		} else {
			$msg = JText::_( 'Error in save' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('pms');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Project Could not be Deleted' );
		} else {
			$msg = JText::_( 'Project Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_pms', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_mojoom', $msg );
	}
}