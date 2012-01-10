<?php
/**
 * Mojoom Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
jimport('joomla.application.component.controller');

class MojoomControllerMojoom extends MojoomController
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
		JRequest::setVar( 'view', 'mojoom' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	 
	 // save function not in use.
	function save()
	{
		$model = $this->getModel('mojoom');
		//print_r ($model);
		//exit;
		if ($model->store($post)) {
			$msg = JText::_( 'MOJOOM LOG VREATED' );
		} else {
			$msg = JText::_( 'ERROR IN MOJOOM LOG CREATED' );
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&view=mojoom&Itemid=2';//?option=com_training';
		$this->setRedirect($link, $msg);
	}

	function login()
	{
		// Check for request forgeries
		JRequest::checkToken('request') or jexit( 'Invalid Token' );

		global $mainframe;

		if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$return = base64_decode($return);
			if (!JURI::isInternal($return)) {
				$return = '';
			}
		}

		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $return;

		$credentials = array();
		$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
		$credentials['password'] = JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);

		//preform the login action
		$error = $mainframe->login($credentials, $options);

		if(!JError::isError($error))
		{
			// Redirect if the return url is not registration or login
			if ( ! $return ) {
				$return	= 'index.php?option=com_mojoom';
			}

			$mainframe->redirect( $return );
		}
		else
		{
			// Facilitate third party login forms
			if ( ! $return ) {
				$return	= 'index.php?option=com_mojoom&view=mojoom_login&layout=form';
			}

			// Redirect to a login form
			$mainframe->redirect( $return );
		}
	}

	function logout()
	{
		global $mainframe;

		//preform the logout action
		$error = $mainframe->logout();

		if(!JError::isError($error))
		{
			if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
				$return = base64_decode($return);
				if (!JURI::isInternal($return)) {
					$return = '';
				}
			}

			// Redirect if the return url is not registration or login
				$mainframe->redirect( 'index.php?option=com_mojoom' );
		} else {
			parent::display();
		}
	}
	
	function profile_edit()
	{
		$model = $this->getModel('profile_edit');
		if ($model->profile_edit($post)) {
			$msg = JText::_( 'PROFILE EDIT' ); 
		} else {
			$msg = JText::_( 'PROFILE EDIT ERROR' );
		}
		
		$link = 'index.php?option=com_mojoom&view=mojoom&Itemid=2';
		$this->setRedirect($link, $msg);
		
	}
	
	function status()
	{
		$data = JRequest::get( 'post' );
		$message = $data['status'];
		$model = $this->getModel('mojoom');
		if ($model->status($post)) {
			//add this to the current activities
			$my			= CFactory::getUser();
			$act = new stdClass();
			$act->cmd 		= 'profile.status.update';
			$act->actor 	= $my->id;
			$act->target 	= $my->id;

			CFactory::load( 'helpers' , 'linkgenerator' );

			// @rule: Autolink hyperlinks
			$message		= CLinkGeneratorHelper::replaceURL( $message );
			
			// @rule: Autolink to users profile when message contains @username
			$message		= CLinkGeneratorHelper::replaceAliasURL( $message );
			
		
			$privacyParams	= $my->getParams();			
			
			$act->title		= '{actor} '.$message;
			$act->content	= '';
			$act->app		= 'profile';
			$act->cid		= $my->id;
			$act->access	= $privacyParams->get('privacyProfileView');
			
			CFactory::load('libraries', 'activities');
			CActivityStream::add($act);
			$msg = JText::_( 'STATUS UPDATE' );
		} else {
			$msg = JText::_( 'ERROR IN STATUS' );
		}
		
		$link = 'index.php?option=com_mojoom&view=mojoom&Itemid=2';
		$this->setRedirect($link, $msg);	
	}
	
}