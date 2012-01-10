<?php 
/**
 * Mojoom View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); 
JHTML::_('behavior.mootools');
JHtml::_('behavior.modal', 'a.notification');
JHTML::_('behavior.modal', 'a.addfrnd');
?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<script type="text/javascript">
function showstdiv() {
	document.getElementById('st_msg').style.display = 'none';
	document.getElementById('st_section').style.display = 'block';
}
</script>
<script type="text/javascript">
window.addEvent('domready', function() {
// Decorate the login windows to use a modal.
$ES('a.notification').each(function(a){
a.setProperty('rel', '{size: {x: 250, y: 145}, ajaxOptions: {method: "get"}}');
if (a.getProperty('href').contains('?')) {
a.setProperty('href', a.getProperty('href')+'&tmpl=component');
} else {
a.setProperty('href', a.getProperty('href')+'?tmpl=component');
}
});
});

window.addEvent('domready', function() {
// Decorate the login windows to use a modal.
$ES('a.addfrnd').each(function(a){
a.setProperty('rel', '{size: {x: 250, y: 165}, ajaxOptions: {method: "get"}}');
if (a.getProperty('href').contains('?')) {
	a.setProperty('href', a.getProperty('href')+'&tmpl=component');
} else {
	a.setProperty('href', a.getProperty('href')+'?tmpl=component');
}
});
});

</script>


<?php
$user =& JFactory::getUser();
$userid = $user->get('id');
$data = JRequest::get( 'get' );
if($user->guest) 
{
?>
	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
		<div id="back">
        <!--<a href="<?php //echo $_SERVER['HTTP_REFERER'];?>" /><input type="button" value="Back" class="back"/></a>-->
			<input type="button" value="Back" class="back" onclick="javascript:history.back();" />        
        </div>
        <div id="header_title">&nbsp;<?php echo JText::_('FROM'); ?>&nbsp;</div>
        <?php if($data['user_id'] == "") { ?>	
        	<div id="forward"><a href="index.php?option=com_mojoom&view=profile_edit&layout=default"><input type="button" value="Edit" class="edit" /></a></div>
        <?php }	
			 else 
			 { 
				if($this->profile->addfriend)
				{			
		?>      
                <?php if($userid != $data['user_id']) 
					  { 
			    ?><div id="forward">
                	   <a href="index.php?option=com_mojoom&view=addfriend&user_id=<?php echo $data['user_id']; ?>" class="addfrnd"><input type="button" value="Add Friend" class="add_friend" /></a></div>
                <?php }
                }
                else 
				{ 
                 ?>
                 <div id="forward">
                 <a href="index.php?option=com_mojoom&controller=friend&task=remove_friend&user_id=<?php echo $data['user_id']; ?>" ><input type="button" value="Remove Friend" class="remove_friend" /></a>    </div>                
            <?php 	
				}			
			}	
		 ?>
         
</div>

 	<div id="profile_head">
		
		<div id="profile_detail">
			<div id="profile_image">
				<img src="<?php if($this->profile->avatar){ echo $this->profile->avatar; } else { echo "./components/com_community/templates/blackout/images/default_thumb.png"; } ?>"   />
			</div>
			<div id="profile_overview">
				<span><?php echo  $this->profile->username; ?></span>			
			</div>
            <div id="profile_view">
				<span><?php echo JText::_('PROFILE VIEW'); ?> <?php echo  $this->profile->view; ?></span>			
			</div>
            <div id="profile_status">
			
			<?php 
			if($this->profile->status == ""  && $userid == $this->profile->id )
			{
			?>   
               		<form name="status" action="index.php" method="post">
						<input type="text" name="status" id="status" onfocus="if(this.value=='Status Here...') this.value='';" onblur="if(this.value=='') this.value='Status Here...';" value="Status Here..." style="font-style:italic" />
						<input type="hidden" name="controller" value="mojoom" />
						<input type="hidden" name="option" value="com_mojoom" />
						<input type="hidden" name="task" value="status" />	
					</form>		
            <?php 
			} else
			{ ?>
				<div id="st_section" style="display:none;">        
               		<form name="status" action="index.php" method="post" name="statusform"> 
						<input type="text" name="status" id="status" value="<?php echo $this->profile->status;?>" /> 
						<input type="hidden" name="controller" value="mojoom" />
						<input type="hidden" name="option" value="com_mojoom" />
						<input type="hidden" name="task" value="status" />	
					</form>
				</div>
				
				<p id="st_msg" onclick="<?php if($userid == $this->profile->id )
					{?>showstdiv()<?php } ?>"><?php echo $this->profile->status; ?></p> 
				<?php
			}
			?>
            </div>
             <div id="profile_notification">
            <?php if($this->Notification  > 0 && $userid == $this->profile->id ) {   ?>
            	<!-- class="notification" title="notification" rel="{handler: 'ajax'}" -->
                <a href="index.php?option=com_mojoom&view=notification&layout=default" ><?php echo  $this->Notification;  ?></a>
             <?php } ?>
            </div>    
            
		</div>	
		<div id="profile_menu">
			<?php if($data['user_id'] != "")
			{ 
				$userid = $data['user_id'];
			}
			?>
            <span id="detail"><a href="index.php?option=com_mojoom&view=profile_detail&user_id=<?php echo $userid; ?>&layout=default" rel="external" ><?php echo JText::_('VIEW DETAIL'); ?></a></span>
     		<?php if($data['user_id'] != "") { 
				$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
				if(stripos($ua,'android') !== false) {?>  
	            <span id="message" > <a href="index.php?option=com_mojoom&view=compose&layout=default&id=<?php echo $userid; ?>"style="margin-left:1px;" class="android"><?php echo JText::_('SEND MSG'); ?></a></span> <?php }
				else
				{ ?>
                <span id="message" > <a href="index.php?option=com_mojoom&view=compose&layout=default&id=<?php echo $userid; ?>"style="margin-left:4px;"><?php echo JText::_('SEND MSG'); ?></a></span>
				<?php } 
				}else { ?>
            	<span id="message"> <a href="index.php?option=com_mojoom&view=message&layout=default"><?php echo JText::_('MESSAGE MOJOOM'); ?></a></span>           
            <?php } ?>
            
            
			<?php if($data['user_id'] != "") { ?>
                <span id="pphotos"><a href="index.php?option=com_mojoom&view=albums&user_id=<?php echo $userid; ?>"><?php echo JText::_('PHOTO'); ?></a></span><br />            
                <span id="pvideos"><a href="index.php?option=com_mojoom&view=videos&user_id=<?php echo $userid; ?>"><?php echo JText::_('VIDEO'); ?></a></span>	  
                <span id="friend"><a href="index.php?option=com_mojoom&view=friend&layout=default&user_id=<?php echo $userid; ?>"><?php echo JText::_('FRIEND'); ?></a></span>
                <span id="pgroups"><a href="index.php?option=com_mojoom&controller=groups&task=mygroups&user_id=<?php echo $userid; ?>"><?php echo JText::_('GROUP TITLE'); ?></a></span>
                <!-- 26-12-2012 M Change-->
                 <span id="wall"><a href="index.php?option=com_mojoom&view=wall1&user_id=<?php echo $userid;?>"><?php echo JText::_('WALLHEAD'); ?></a></span>     
            <?php }else { ?>
            	<span id="gallery"><a href="index.php?option=com_mojoom&view=gallery"><?php echo JText::_('GALLERY'); ?></a></span><br />            
                <span id="wall"><a href="index.php?option=com_mojoom&view=wall1"><?php echo JText::_('WALLHEAD'); ?></a></span>
                <span id="friend"><a href="index.php?option=com_mojoom&view=friend&layout=default&user_id=<?php echo $userid; ?>">Friend</a></span>
            	<span id="chat"><a href="index.php?option=com_mojoom&view=more"><?php echo JText::_('More'); ?></a></span>	     
                     
            <?php } ?>
            
            
			
		</div>
	</div>
	
	
<?php 
} 
?>
