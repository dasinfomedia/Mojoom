<?php 
/**
 * Message View for Mojoom Component
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
        <div id="header_title"><?php echo JText::_('MESSAGE');?></div>
        <div id="forward"></div>
</div>
<div class="componentcontent">
    <div id="inbox">
    	<span><a href="index.php?option=com_mojoom&view=inbox&layout=default"><?php echo JText::_('INBOX TITLE');?></a></span>
    </div>
    <div id="outbox">
    	<span><a href="index.php?option=com_mojoom&view=outbox&layout=default"><?php echo JText::_('OUTBOX');?></a></span>
    </div>
    <div id="compose">
    	<span><a href="index.php?option=com_mojoom&view=compose&layout=default"><?php echo JText::_('COMPOSE');?></a></span>
    </div>
</div>
	