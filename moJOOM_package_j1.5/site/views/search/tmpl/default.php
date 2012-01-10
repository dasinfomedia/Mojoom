<?php 
/**
 * Search View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<?php
$data = JRequest::get( 'post' );
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
$document->setTitle(JText::_('SEARCH')); 
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" value="Back" class="back" onclick="javascript:history.back();" /></div>
    <div id="header_title_friends" style="width:61% !important;">
			<ul id="sub_menu">
            	<li class="leftrndcrnr active"></li>
				<li id="left"><a href="index.php?option=com_mojoom&view=friend&layout=default"><?php echo JText::_('FRIEND'); ?></a></li>
                <li class="saperator"></li>
				<li id="mid"><a href="index.php?option=com_mojoom&view=member&layout=default"><?php echo JText::_('MEMBER'); ?></a></li>
                <li class="saperator"></li>
				<li id="right"><a href="index.php?option=com_mojoom&view=search&layout=default" class="active"><?php echo JText::_('SEARCH'); ?></a></li>
                <li class="rightrndcrnr ractive"><span>&nbsp;</span></li>  
			</ul>
	</div>
    <div id="forward"><!--<input type="submit" name="submit" id="submit" value="Save" class="edit" />--></div>
</div>
<div class="componentcontent">
<div class="searchcontainer">
	<div class="col100">
 	 <div id="friend_main">	
   <form name="search" method="post" action="index.php">
   		<input type="text" name="q" id="q" style="background: url(./components/com_mojoom/images/searchbox.png) no-repeat scroll 0 0 transparent; height: 31px; width: 270px; border:none; padding-left:25px;" value="<?php echo $data['q']; ?>"  />
        <input type="hidden" name="option" value="com_mojoom" />
        <input type="hidden" name="view" value="search" />        
   </form>
   </div>
   </div>
   <?php 
   if($data['q'] != "")
   { 
		if($this->Result)
		{
   ?>   
   <div id="result">
   			
   		<?php
			
			 $i	= 0;
			foreach($this->Result as $result)
			{
			?>
            <div class="result_data">
            	<div class="profile_img">
                	<a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $result->id; ?>"><img src="<?php if($result->thumb){ echo $result->thumb; } else { echo "components/com_community/templates/blackout/images/default_thumb.png"; } ?>" width="25px" height="25px" alt="Img"  /></a> 
                </div>
                <div class="profile_name">
				<a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $result->id; ?>"><?php echo $result->name; ?></a>
				</div>
                <div class="profile_status">
					<?php if($result->online == 1){ ?>
                    	<img src="./components/com_mojoom/images/green.png" width="12px" height="12px" alt="On" />
					<?php } else { ?> 
                    	<img src="./components/com_mojoom/images/red.png" width="12px" height="12px" alt="Off" /> 
					<?php } ?> 
                </div>
            </div>
            <?php 
			if($i < (count($this->Result)-1))
			{ ?> <div class="albumsaperator">&nbsp;</div> <?php
			}
			$i++;
			?>  
            <?php } ?>           
   </div>
   <?php } else { ?> <div id="friend_main">	 <?php echo JText::_('NO RESULT FOUND'); ?> </div><?php } 
   }  ?>    
</div> 	
</div>  
<?php 
} 
?>
