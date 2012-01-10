<?php 
/**
 * Friend View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

 ini_set("display_errors","0"); ?>
<?php
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
$document->setTitle(JText::_('FRIENDS'));
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
    <div id="header_title_friends" style="width:61% !important;">
			<ul id="sub_menu">
            	<li class="leftrndcrnr active"><span>&nbsp;</span></li>
				<li id="left"><a href="index.php?option=com_mojoom&view=friend&layout=default" class="active"><?php echo JText::_('FRIENDS'); ?></a></li>
                <li class="saperator"></li>
				<li id="mid"><a href="index.php?option=com_mojoom&view=member&layout=default"><?php echo JText::_('MEMBER'); ?></a></li>
                <li class="saperator"></li>
				<li id="right"><a href="index.php?option=com_mojoom&view=search&layout=default"><?php echo JText::_('SEARCH'); ?></a></li>
                <li class="rightrndcrnr"></li>  
			</ul>
	</div>
    <div id="forward"><!--<input type="submit" name="submit" id="submit" value="Save" class="edit" />--></div>
</div>
<div class="friendscontainer">
<div class="col100">
  <div id="friend_main">
  <?php if ($this->friend) { ?>	
    <div id="result">
   		<?php 
			 $i	= 0;
			foreach($this->friend as $friend)
			{
			?>
            <div class="result_data">
            	<div class="profile_img">
                	<a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $friend->id; ?>"><img src="<?php if($friend->thumb){ echo $friend->thumb; } else { echo "./components/com_community/templates/blackout/images/default_thumb.png"; } ?>" width="25px" height="25px" alt="Img"  /></a> 
                </div>
                <div class="profile_name"><a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $friend->id; ?>"><?php echo $friend->name; ?></a></div>
                <div class="profile_status">
					<?php if($friend->online == 1){ ?>
                    	<img src="./components/com_mojoom/images/green.png" width="12px" height="12px" alt="On" />
					<?php } else { ?> 
                    	<img src="./components/com_mojoom/images/red.png" width="12px" height="12px" alt="Off" /> 
					<?php } ?> 
                </div>
            </div>
            <?php 
			if($i < (count( $this->friend)-1))
			{ ?> <div class="albumsaperator">&nbsp;</div> <?php
			}
			$i++;
			?> 
            <?php } ?>           
   </div>   
<?php } else { 
	echo JText::_('NO FRIEND FOUND');
} ?>

   </div>     
</div> 	  
</div>
<?php 
} 
?>
