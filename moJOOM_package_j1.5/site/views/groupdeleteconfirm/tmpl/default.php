<?php 
/** 
 * Group Delete Confirm View for Mojoom Component
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
?> 
<style>
strong {
    font-weight: bold;
}
</style>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div class="componentcontent">
	<div class="groupswindowcontainer">
		<div id="cWindowContentTop">			
			<div id="cwin_logo"><?php echo JText::_('GROUP DELETE'); ?></div>           
			<div class="clr"></div>
		</div>
		<div class="windowcontent">
			<p style="float:left;padding-left:2%;width:96%; text-align:justify;">
				<?php 
				echo JText::sprintf('CONFIRM DELETE', $this->group->name );?>
			</p>
			<form action="index.php" method="post" name="jsform-groups-ajaxshowjoingroup" style="float:left; width:98%; padding-top:2%; padding-left:2%">
				<input type="submit" name="Submit" class="button" value="Delete">
				<a href="#"><input type="button" name="Submit" class="button" value="Cancel" onClick="window.parent.document.getElementById( 'sbox-window' ).close();" ></a>
				
				<input type="hidden" name="group_id" value="<?php echo $this->group->id; ?>">
				<input type="hidden" name="step" value="<?php echo 0; ?>">
				<input type="hidden" name="option" value="com_mojoom" />
				<input type="hidden" name="task" value="groupdeletefinal" />
				<input type="hidden" name="controller" value="groups" />
	
			</form>
		</div>
	</div>
</div>
<?php 
} 
?>