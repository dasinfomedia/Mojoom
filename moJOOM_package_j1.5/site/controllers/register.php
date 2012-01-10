<?php
/**
 * Register Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller'); 
class MojoomControllerRegister extends MojoomController 
{
	
	/**
	 * Prepares the registration form
	 * @return void
	 */ 
	function register()
	{
		$usersConfig = &JComponentHelper::getParams( 'com_users' );  
		if (!$usersConfig->get( 'allowUserRegistration' )) { 
			JError::raiseError( 403, JText::_( 'ACCESS FORBIDDEN' ));
			return;
		}

		$user 	=& JFactory::getUser();

		if ( $user->get('guest')) {
			JRequest::setVar('view', 'register');
		} else {
			$this->setredirect('index.php?option=com_user&task=edit',JText::_('You are already registered.'));
		}

		parent::display();
	}
	function save()
	{
		// Check for request forgeries
		
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$user	 =& JFactory::getUser();
		$userid = JRequest::getVar( 'id', 0, 'post', 'int' );
		// preform security checks
		if ($user->get('id') == 0 || $userid == 0 || $userid <> $user->get('id')) {
			JError::raiseError( 403, JText::_('ACCESS FORBIDDEN') );
			return;
		}

		//clean request
		$post = JRequest::get( 'post' );
		$post['username']	= JRequest::getVar('username', '', 'post', 'username');
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
	
		// get the redirect
		$return = JURI::base();
		
		// do a password safety check
		if(strlen($post['password']) || strlen($post['password2'])) { // so that "0" can be used as password e.g.
			if($post['password'] != $post['password2']) {
				$msg	= JText::_('PASSWORDS_DO_NOT_MATCH');
				// something is wrong. we are redirecting back to edit form.
				// TODO: HTTP_REFERER should be replaced with a base64 encoded form field in a later release
				$return = str_replace(array('"', '<', '>', "'"), '', @$_SERVER['HTTP_REFERER']);
				if (empty($return) || !JURI::isInternal($return)) {
					$return = JURI::base();
				}
				$this->setRedirect($return, $msg, 'error');
				return false;
			}
		}

		// we don't want users to edit certain fields so we will unset them
		unset($post['gid']);
		unset($post['block']);
		unset($post['usertype']);
		unset($post['registerDate']);
		unset($post['activation']);

		// store data
		$model = $this->getModel('user');

		if ($model->store($post)) {
			$msg	= JText::_( 'SETTING SAVED' );
		} else {
			$msg	= $model->getError();
		}

		
		$this->setRedirect( $return, $msg );
	}
	
	
	/**
	 * Save user registration and notify users and admins if required
	 * @return void
	 */
	function register_save()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		//clean request
		$post = JRequest::get( 'post' );
		$post['username']	= JRequest::getVar('username', '', 'post', 'username');
		$post['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['password2']	= JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
		
		// get the redirect
		$return = JURI::base();
		
		// do a password safety check
		if(strlen($post['password']) || strlen($post['password2'])) { // so that "0" can be used as password e.g.
			if($post['password'] != $post['password2']) {
				$msg	= JText::_('PASSWORD NOT MATCH');
				// something is wrong. we are redirecting back to edit form.
				// TODO: HTTP_REFERER should be replaced with a base64 encoded form field in a later release
				$return = str_replace(array('"', '<', '>', "'"), '', @$_SERVER['HTTP_REFERER']);
				if (empty($return) || !JURI::isInternal($return)) {
					$return = JURI::base();
				}
				$this->setRedirect($return, $msg, 'error');
				return false;
			}
		}

		// Get required system objects
		$user 		= clone(JFactory::getUser());
		$pathway 	=& $mainframe->getPathway();
		$config		=& JFactory::getConfig();
		$authorize	=& JFactory::getACL();
		$newUsertype = 'Registered';

		// Bind the post array to the user object
		if (!$user->bind( JRequest::get('post'), 'usertype' )) {
			JError::raiseError( 500, $user->getError());
		}

		// Set some initial user values
		$user->set('id', 0);
		$user->set('usertype', $newUsertype);
		$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

		// If user activation is turned on, we need to set the activation information
			
			jimport('joomla.user.helper');
			$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		
		// If there was an error with registration, set the message and display form
		
		if ( !$user->save() )
		{
			JError::raiseWarning('', JText::_( $user->getError()));
			$this->register();
			return false;
		}
		
			$obj1 = new stdClass();
			$obj1->userid = $user->id;
			$obj1->points = 0;
			$obj1->posted_on = $date->toMySQL();
			$obj1->avatar	= '';								
			$obj1->thumb	= '';
			$obj1->params	= 'notifyEmailSystem=1
								privacyProfileView=0
								privacyPhotoView=0
								privacyFriendsView=0
								privacyVideoView=1
								notifyEmailMessage=1
								notifyEmailApps=1
								notifyWallComment=0';
			$db->insertObject('#__community_users', $obj1, 'userid');
			
			$extra_field = array(1=>2,2=>3,3=>4,4=>6,5=>7,6=>8,7=>9,8=>10,9=>11,10=>12,11=>14,12=>15,13=>16);
			$i = 1;
			$obj2 = new stdClass();
			while($extra_field[$i] != "")
			{
				$obj2->id = '';
				$obj2->user_id 	= $user->id;
				$obj2->field_id  = $extra_field[$i];
				$obj2->value = '';
				$db->insertObject('#__community_fields_values', $obj2, 'id');
				$i++;
			}					
         ////////// end of joomsocial customisation///////////////////////////	
		// Send registration confirmation mail
		$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
		MojoomControllerRegister::_sendMail($user, $password);

		// Everything went fine, set relevant message depending upon user activation state and display message
		
			$message  = JText::_( 'ACCOUNT CREATED' );
		
		$this->setRedirect('index.php', $message);
	}

	
	
	function _sendMail(&$user, $password)
	{
		global $mainframe;

		$db		=& JFactory::getDBO();

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= &JComponentHelper::getParams( 'com_users' );
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();

		$subject 	= sprintf ( JText::_( 'ACCOUNT DETAIL FOR'), $name, $sitename);
		$subject 	= html_entity_decode($subject, ENT_QUOTES);

		if ( $useractivation == 1 ){
			$message = sprintf ( JText::_( "REGISTER THANKYOU MSG" ), $name, $sitename, $siteURL."index.php?option=com_user&task=activate&activation=".$user->get('activation'), $siteURL, $username, $password);
		} else {
			$message = sprintf ( JText::_( 'THANKYOU NOW LOGIN' ), $name, $sitename, $siteURL);
		}

		$message = html_entity_decode($message, ENT_QUOTES);

		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		// Send email to user
		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}

		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);

		// Send notification to all administrators
		$subject2 = sprintf ( JText::_( 'ACCOUNT DETAIL FOR' ), $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		foreach ( $rows as $row )
		{
			if ($row->sendEmail)
			{
				$message2 = sprintf ( JText::_( "NEW USER REG SEND REG" ), $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			}
		}
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
		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_mojoom&view=mojoom&Itemid=2';//?option=com_training';
		$this->setRedirect($link, $msg);
	}
	
}