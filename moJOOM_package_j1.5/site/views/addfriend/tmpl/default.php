<?php
/** 
 * Add friend View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
ini_set("display_errors","0");
 ?>
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
?> 
<style>
strong {
    font-weight: bold;
}
</style>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div class="componentcontent">
	<div class="addFriendContainer">
		<div id="cWindowContentTop">			
			<div id="cwin_logo">Add new friend</div>           
			<div class="clr"></div>
		</div>
		<div class="windowcontent">

			<?php if(empty($this->errflag)) { ?>
			<p><?php echo JText::sprintf('ADD AS FRIEND' , $this->user->getDisplayName() );?></p>
			<form name="addfriend" id="addfriend" method="post" action="index.php">	
            	<?php 
                if($this->user->thumb != '') {
					?>
					<img class="avatar" src="<?php echo $this->user->thumb; ?>" alt=""/>
				<?php } else { ?>
					<img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" alt=""/>
				<?php } ?>
		        <!--<img class="avatar" src="<?php //echo $this->user->getThumbAvatar(); ?>" alt="<?php //echo $this->user->getDisplayName();?>" alt=""/> -->
				<textarea class="inputbox" name="msg"></textarea>
				<input type="hidden" class="button" name="userid" value="<?php echo $this->user_id; ?>"/>
				<div style="float:left;width:100%">
				<input type="submit" name="Submit" class="button" value="Add Friend">
				<a href="#"><input type="button" name="Submit" class="button" value="Cancel" onClick="window.parent.document.getElementById( 'sbox-window' ).close();" ></a>
				</div>
				<input type="hidden" name="option" value="com_mojoom" />
				<input type="hidden" name="task" value="addfriend" />
				<input type="hidden" name="controller" value="friend" />
			</form>
			<?php } else { echo $this->errflag; }?>
		
		</div>
	</div>
</div>
<?php 
} 
?>