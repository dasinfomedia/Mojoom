<?php
/**
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );
// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );
// Require specific controller if requested

if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}



// Create the controller

$classname	= 'MojoomsController'.$controller;

$controller	= new $classname( );



// Perform the Request task

$controller->execute( JRequest::getVar( 'task' ) );



// Redirect if set by the controller

$controller->redirect();