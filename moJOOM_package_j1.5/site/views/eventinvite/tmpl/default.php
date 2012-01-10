<?php
/**
 * Event invite View for Mojoom Component
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<?php
$data = JRequest::get( 'get' );
$user =& JFactory::getUser();
if($user->guest) 
{ 
?>
	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('EVENTINVITE TITLE'));
?> 

		<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<form id="community-invitation-form" name="invitation-form" method="post" action="index.php">
		<div id="header_text">
			 <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
			 <div id="header_title_events">
					<ul id="sub_menu">
                    	<li class="leftrndcrnr"></li>
						<li id="left"><a href="index.php?option=com_mojoom&controller=event&task=myevents" ><?php echo JText::_('EVENT GROUP EVENT'); ?></a></li>
                        <li class="saperator"></li>
						<li id="mid"><a href="index.php?option=com_mojoom&controller=event&task=invite" class="active"><?php echo JText::_('INVITE'); ?></a></li>
                        <li class="saperator"></li>
						<li id="right"><a href="index.php?option=com_mojoom&controller=event&task=create" ><?php echo JText::_('CREATE BUL'); ?></a></li>
                        <li class="rightrndcrnr"></li>
					</ul>
			</div>
            <div id="forward" class="android"><input type="submit" class="edit" value="Send" /></div>            
		</div>        	
       <div class="componentcontent">
			<div class="groupscontainer">			
			<?php if($this->Member){	?>	
					<div class="invitation-bg">
                    
                    <div id="invitation-error"></div>
                        <div class="head-note"><?php echo JText::_('SELECT FRND INVITE'); ?></div>
                        <div id="community-invitation">                    
                            <select name="friends[]" id="friends" >
                            	<option value=""><?php echo JText::_('SELECT FRND');?></option>
                                <?php foreach($this->Member as $member) { ?>
								<option value="<?php echo $member->id; ?>"><?php echo $member->name; ?></option>
                                <?php } ?>                                
                            </select>
                        </div>                        
                        <div class="invitation-option">
                            <div class="option invitation-message-container">
                                <div class="textarea-label">
                                   <?PHP echo JText::_('PERSONAL MSG'); ?>			</div>
                                <div class="textarea-wrap">
                                    <textarea id="message" name="message"></textarea>
                                </div>
                            </div>
                        </div>
                      
                    </div>
			
			<?php 
			}
			else
			{ 
				echo JText::_('NO FRIEND FOUND OR FULL');	
			} ?>
    	</div>
	</div>
<?php		
}
?>
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="task" value="invited" />
<input type="hidden" name="controller" value="event" />
<input type="hidden" name="event_id" value="<?php echo $data['event_id']; ?>" />
</form>