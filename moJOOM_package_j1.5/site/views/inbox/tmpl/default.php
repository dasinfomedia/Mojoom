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

ini_set("display_errors","0"); ?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" />
    <!--<a href="<?php //echo $_SERVER['HTTP_REFERER'];?>index.php?option=com_mojoom&view=message&layout=default&Itemid=55" /><input type="button" value="Back" class="back"  /></a>--></div>
    <div id="header_title"><?php echo JText::_('INBOX TITLE'); ?></div>
    <div id="refresh"><input type="button" value="" class="refresh" onclick="javascript:location.reload();" /></div>
</div>
<div class="componentcontent">
	<div class="outbox-list" id="inbox-listing">
  <?php  //print_r($this->messages); ?>
  <?php 
   $i	= 0;
   $document = JFactory::getDocument();
	$document->setTitle(JText::_('MSG INBOX'));
    foreach ( $this->messages as $message ) : ?>
	<div class="<?php if($message->unRead != 1 ) { echo JText::_('INBOX READ'); } else { if($i==0){echo JText::_('INBOX UNREAD FIRST'); }else{echo JText::_('INBOX UNREAD');} }?>" id="message-<?php echo $message->id; ?>">
    	<div style="width:10%; float:left; margin:5px;">
        <?php if($message->unRead != 1 ) { ?>
			<img src="./components/com_mojoom/css/read.png" alt="msg" width="15px" height="15px" />
        <?php } else { ?>
		     <img src="./components/com_mojoom/css/unread.png" alt="msg" width="15px" height="15px" />
        <?php } ?>     
        </div>
    	<div class="msgarrow" style="width:80%; float:left;">
        	<span><a href="index.php?option=com_mojoom&view=message_detail&task=inbox&id=<?php echo $message->id; ?>" ><?php echo $message->from_name; ?></a></span>&nbsp;&nbsp;<span style=" color: #ffffff;font-size: 0.9em;font-weight: normal;"><?php echo $message->posted_on; ?></span><br/>
        	<span style=" color: #ffffff;font-size: 0.9em;font-weight: normal;"><?php echo $message->subject; ?></span>
        </div>        
    </div>
    <?php 
			if($i < (count( $this->messages)-1))
			{ ?> <div class="msgsaperator">&nbsp;</div> <?php
			}
			$i++;
	?>		
    <?php endforeach; ?>
  	</div>
</div>	
