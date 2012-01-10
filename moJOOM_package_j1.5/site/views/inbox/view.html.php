<?php
/**
 * Inbox View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewInbox extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel();
		$my	=& JFactory::getUser();	
		$msg =& $this->get( 'inbox' );
		// Add small avatar to each image
		if (! empty ( $msg ))
		{
			foreach ( $msg as $key => $val )
			{
				// based on the grouped message parent. check the unread message
				// count for this user.
				$filter ['parent'] = $val->parent;
				$filter [' user_id'] = $my->id;
				
				$unRead = $model->countUnRead( $filter );

				$msg [$key]->unRead = $unRead;
			}
		}
		
		
		if(empty($msg))
		{
		?>
        	<div id="header_text">
				<div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
                <div id="header_title">Inbox</div>
                <div id="forward"></div>
	        </div>
			<div class="column body">
				<div class="community-empty-list"><?php echo JText::_('NOT HAVE ANY MSG'); ?></div>
			</div>		   
		<?php 
		}
		else  
		{
			$this->assignRef( 'messages',	$msg );
			parent::display($tpl);
		}
		
	}
}

