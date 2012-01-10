<?php
/**
 * Message Controller for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * Message Controller
 *
 * @package    Mojoom
 * @subpackage Components
 */
class MojoomControllerMessage extends MojoomController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		
	}
	
	function write()
	{
		
		$mainframe =& JFactory::getApplication();
		$my		= JFactory::getUser ();
		
		$data	= new stdClass ( );
		
		if($my->id == 0)
		{
			return $this->blockUnregister();
		}

		$data->to			= JRequest::getVar ( 'to', '', 'POST' );
		$data->subject		= JRequest::getVar ( 'subject', '', 'POST' );		
		$data->body			= JRequest::getVar ( 'body', '', 'POST' );
		$data->sent			= 0;
		$model				= & $this->getModel ( 'user' );
		$actualTo			= array ();
		
		// are we saving ??
		$validated = true;
		
			
				//exit;
				if (empty ( $data->subject ))
				{
					$msg =  JText::_('SUBJECT MISSSING');
					$validated = false;
				}
	
				if (empty ( $data->body ))
				{
					$msg = JText::_('NO MESSAGES');
					$validated = false;
				}
	
				if (empty ( $data->to ))
				{
					$msg = JText::_('RECIEVER MISSSING');
					$validated = false;
				}
				

				// restrict user to send message to themselve
				if( $my->id == $validUser )
				{
					$msg = JText::_('MESSAGE SENT YOURSELF.');
					$link = 'index.php?option=com_mojoom&view=inbox&layout=default';
					$this->setRedirect($link, $msg);										
				}
										
				// store message
				if ($validated)
				{
					$model = & $this->getModel ( 'message' );
	
					$msgData		= JRequest::get( 'POST' );
					//$msgData ['to'] = $actualTo;

					//$msgid = $model->send (  );
					//$data->sent = 1;
					$msg ="";
					if ($model->send($msgData)) {
						$msg = JText::_('MESSAGE SENT');
					} else {
						$msg = JText::_('MESSAGE NOT SENT');
					}
					// Check the table in so it can be edited.... we are done with it anyway
					$link = 'index.php?option=com_mojoom&view=inbox&layout=default';
					$this->setRedirect($link, $msg);					
				}
				else
				{
					$link = 'index.php?option=com_mojoom&view=compose&layout=default';
					$this->setRedirect($link, $msg);					
				}
		
	}
	function compose()
	{
		JRequest::setVar('view', 'compose');
		parent::display();
	}
}