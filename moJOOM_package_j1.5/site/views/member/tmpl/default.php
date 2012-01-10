<?php
/**
 * Member View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

 ?>
<?php
ini_set("display_errors","0");
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
$document->setTitle(JText::_('MEMBER'));
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
    <div id="header_title_friends" style="width:61% !important;">
			<ul id="sub_menu">
            	<li class="leftrndcrnr active"></li>
				<li id="left"><a href="index.php?option=com_mojoom&view=friend&layout=default" ><?php echo JText::_('FRIEND'); ?></a></li>
                <li class="saperator"></li>
				<li id="mid"><a href="index.php?option=com_mojoom&view=member&layout=default" class="active"><?php echo JText::_('MEMBER'); ?></a></li>
                <li class="saperator"></li>
				<li id="right"><a href="index.php?option=com_mojoom&view=search&layout=default"><?php echo JText::_('SEARCH'); ?></a></li>
                <li class="rightrndcrnr"></li>  
			</ul>
	</div>
    <div id="forward"></div>
</div>
<div class="friendscontainer">
<div class="col100">
  <div id="friend_main">	
    
    <div id="result">
   		<?php 
			if($this->friend) {
			 $i	= 0;
			foreach($this->friend as $friend)
			{ 
			?>
            <div class="result_data">
            	<div class="profile_img">
                            
                	<a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $friend->id; ?>">
                    <?php if(!strstr($friend->getThumbAvatar($friend->id),"user_thumb")){ ?>
                    <img src="<?php echo $friend->getThumbAvatar($friend->id);?>" width="25px" height="25px" alt="Img"  />
                    <?php } else { ?>
                    <img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" alt="" width="25px" height="25px" />
                    <?php } ?>
                    </a> 
                </div>
                <div class="profile_name"><a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $friend->id; ?>"><?php echo $friend->name; ?></a></div>
                <div class="profile_status">
					<?php if($friend->isOnline()){ ?>
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
            <?php } 
			}else
			{
			echo JText::_('NO MEM FOUND');
			}
			?>  
			         
   </div>    
   </div>     
</div> 
</div>	  
<?php 
} 
?>
