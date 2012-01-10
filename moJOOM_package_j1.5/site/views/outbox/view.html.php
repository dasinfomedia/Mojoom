<?php
/**
 * Outbox View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MojoomViewOutbox extends JView
{
	function display($tpl = null)
	{
		$model = & JModel::getInstance('message','MojoomModel');
		$mainframe =& JFactory::getApplication();
		
		$sent = $model->getSent();
		//echo '<pre>';
//		print_r($sent);
//		echo '</pre>';
		if(empty($sent))
		{
		?>
        	<div id="header_text">
				<div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
                <div id="header_title"><?php echo JText::_('OUTBOX'); ?></div>
                <div id="forward"></div>
	        </div>
			<div class="column body">
				<div class="community-empty-list"><?php echo JText::_('NOT HAVE ANY MSG'); ?></div>
			</div>		    
		<?php 
		}
		else 
		{
			$this->assignRef( 'sent',	$sent );
			parent::display($tpl);
		}
		
	}
}

