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

ini_set("display_errors","0"); ?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" />
    <!--<a href="<?php //echo $_SERVER['HTTP_REFERER'];?>" /><input type="button" value="Back" class="back"  /></a>--></div>
        <div id="header_title"><?php echo JText::_('OUTBOX'); ?></div>
    <div id="refresh"><input type="button" value="" class="refresh" onclick="javascript:location.reload();" /></div>
</div>
<div class="componentcontent">
  <div id="inbox-listing" class="outbox-list">
  <?php //print_r($this->sent); ?>
   <?php 
   $i	= 0;
   foreach ( $this->sent as $message ) : ?>
	<div id="message-<?php echo $message->id; ?>" style="padding-bottom: 5px;padding-top: 5px; " class="inbox-read">
    	<div style="width:10%; float:left; margin:5px;"><img src="./components/com_mojoom/css/read.png" alt="msg" width="15px" height="15px" /></div>
    	<div style="width:80%; float:left;" class="msgarrow">
        	<span><a href="index.php?option=com_mojoom&view=message_detail&task=outbox&id=<?php echo $message->id; ?>" ><?php foreach($message->to_name as $key=>$value) { echo $value; } ?></a></span>&nbsp;&nbsp;<span><?php echo $message->posted_on; ?></span><br/>
        	<span><?php echo $message->subject; ?></span>
        </div>        
    </div>
    <?php 
			if($i < (count($this->sent)-1))
			{ ?> <div class="msgsaperator">&nbsp;</div> <?php
			}
			$i++;
	?>		
    <?php endforeach; ?>
    </div>
</div>
	