<?php 
/**
 * Compose View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
ini_set("display_errors","0");
 ?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<form id="form1" method="post" action="index.php" > 	  
<div id="header_text">
    <div id="back"><!--<input type="button" value="Back" class="back" onclick="javascript:history.back();" />-->
    <a href="<?php //echo $_SERVER['HTTP_REFERER'];?>index.php?option=com_mojoom&view=message&layout=default&Itemid=55" /><input type="button" value="Back" class="back"  /></a></div>
    <div id="header_title"><?php echo JText::_('COMPOSE'); ?></div>
	<?php if ($this->friends || $this->flage) { ?>
    <div id="forward"><input type="submit" value="Send" class="edit" id="submit" name="submit" /></div>
	<?php } ?>
</div>
<?php if ($this->friends || $this->flage ) { ?>
<div class="composeform">

    <div class="compose_item">
        <label for="gender">To:</label>
        <?php if($this->flage == 1) { ?>
        <select name="to" name="to">
        	<option value="<?php echo $this->to[1]; ?>"><?php echo $this->to[0]; ?></option>
        </select>
        <?php } else { ?>		
        <select name="to" name="to">
        <?php foreach ( $this->friends as $friend ) : ?>
    	    <option value="<?php echo $friend->id; ?>" id="<?php echo $friend->id; ?>"><?php echo $friend->name; ?></option>
        <?php endforeach; 
			}
		?>		
        </select> 
    </div>
    <div class="compose_item">			
        <label for="b_date" id="b_dateLabel" name="b_dateLabel"><?php echo JText::_('SUBJECT'); ?></label>		
        <input type="text" id="subject" name="subject" value="" />
     </div>
     <div class="compose_item">
        <label for="aboutme" id="aboutmeLabel" name="aboutmeLabel"><?php echo JText::_('MESSAGE DETAIL'); ?></label>		
        <textarea name="body" id="body" rows="7" ></textarea>
     </div>     
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="task" value="write" />
<input type="hidden" name="controller" value="message" />
</div> 
</form>
<?php } else { ?>
<div class="composeform">
	<?php echo JText::_('NO FRIEND FOR SEND MSG'); ?>
</div>
<?php } ?>