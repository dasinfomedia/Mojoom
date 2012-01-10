<?php 
/**
 * Message Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
ini_set("display_errors","0"); ?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<form id="form1" method="post" action="index.php?option=com_mojoom&controller=message&task=compose" > 	
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
    <div id="header_title"></div>
    <div id="forward"><input type="submit" value="Reply" name="submit" id="submit" class="edit" /></div>
</div>
<?php 
foreach($this->Detail as $detail ) ?>
<div class="componentcontent">
<div class="col1">
    <div class="compose_item">
        <label><?php echo JText::_('TO'); ?> </label>
        <span id="to"><?php echo $detail->to_name; ?></span>
    </div>
	<div class="compose_item">
        <label><?php echo JText::_('FROM'); ?> </label>
        <span id="to"><?php echo $detail->from_name; ?></span>
    </div>
    <div class="compose_item">			
        <label><?php echo JText::_('SUBJECT'); ?> </label>		
        <span id="subject"><?php echo $detail->subject; ?></span>
     </div>
     <div class="compose_item">
        <label><?php echo JText::_('MESSAGE DETAIL'); ?> </label>		
        <span id="message"><?php echo $detail->body; ?></span>
     </div>     

<?php
$task = $_GET['task'];
if($task == 'inbox')
{ ?>
<input type="hidden" name="id" value="<?php echo $detail->from; ?>" />
<?php 
}else
{ ?>
<input type="hidden" name="id" value="<?php echo $detail->to; ?>" />
<?php } ?>
</div> 
</div>
</form>
	