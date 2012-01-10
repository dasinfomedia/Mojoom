<?php
/** 
 * Group Join View for mojoom Component
 * 
 * @package    mojoom
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
			<div id="cwin_logo"><?php echo JText::_('JOIN GROUP'); ?></div>           
			<div class="clr"></div>
		</div>
		<div class="windowcontent">
			<p>
				<?php 
				echo JText::sprintf('SURE WANT TO JOIN', $this->group->name );?>
			</p>
			<form action="index.php" method="post" name="jsform-groups-ajaxshowjoingroup">
				<input type="submit" name="Submit" class="button" value="Yes">
				<a href="#"><input type="button" name="Submit" class="button" value="No" onClick="window.parent.document.getElementById( 'sbox-window' ).close();" ></a>
				
				<input type="hidden" name="group_id" value="<?php echo $this->group->id; ?>">
				<input type="hidden" name="option" value="com_mojoom" />
				<input type="hidden" name="task" value="savejoingroup" />
				<input type="hidden" name="controller" value="groups" />
	
			</form>
		</div>
	</div>
</div>
<?php 
} 
?>