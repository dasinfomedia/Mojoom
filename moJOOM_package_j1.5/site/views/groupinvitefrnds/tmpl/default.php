<?php
/** 
 * Group Invite Friend View for Mojoom Component
 * 
 * @package    Mojoom
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
	$document->setTitle(JText::_('GP INVITE FRND TITLE'));
	
?>

<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<form method="post" action="index.php" id="inviteFriendGroup" name="jsform-groups-invite" class="community-form-validate">
<div id="header_text">
	<div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
	  <div id="header_title_groups">
			<ul id="sub_menu">
				<li class="leftrndcrnr active"><span>&nbsp;</span></li>
				<li id="left"><a href="index.php?option=com_mojoom&controller=groups&task=mygroups" class="active"><?php echo JText::_('GROUP BUL'); ?></a></li><li class="saperator"></li>
				<li id="mid"><a href="index.php?option=com_mojoom&controller=groups&task=myinvite"><?php echo JText::_('MY INVITES'); ?></a></li>
				<li class="saperator"></li>
				<li id="right"><a href="index.php?option=com_mojoom&controller=groups&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
				<li class="rightrndcrnr"></li>
			</ul>
	</div>
	<div id="forward" class="android"><input type="submit" class="edit" value="Send" /></div>   
</div>
<div class="componentcontent">
	<div class="groupsinvitecontainer">
	<div class="invitation-bg">
			<?php
			if( $this->showFriends )
			{
			?>
				
				<?php 

				if( !empty( $this->friends ) )
				{

				?>
					<div class="head-note"><?php echo JText::_('SELECT FRND TO SEND INVIT');?></div>
				<div id="community-invitation">

				<select name="friends[]" id="friends" multiple="multiple" >
					<option value="">Select Friends</option>
				<?php
				foreach( $this->friends as $friend )
				{
				?> 
					<option value="<?php echo $friend->id; ?>"><?php echo $friend->name; ?></option>
				<?php
                }
                ?>
                </select>
				<!--</ul> -->
	</div>

	<?php

	} 

	else 

	{

?>

	<div><?php echo JText::_('HAVE NO FRND OR SEND INVITE');?></div>

<?php

	}

?>		

	<div class="invitation-option">

<?php

}
	if( !empty( $this->friends ) )
				{
?>

		<div class="option invitation-message-container">

			<div class="textarea-label">

				<?php echo JText::_('PERSONAL MSG');?>

			</div>

			<div class="textarea-wrap">

				<textarea name="message" id="message"></textarea>

			</div>

		</div>

	
<?php 

}
?>
</div>
</div>


</div>
</div>
<input type="hidden" name="callback" value="<?php echo $this->callback; ?>" />
<input type="hidden" name="group_id" value="<?php echo $this->group_id; ?>" />
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="controller" value="groups" />
<input type="hidden" name="task" value="saveinvitefrnds" />
</form>


<?php
}
?>