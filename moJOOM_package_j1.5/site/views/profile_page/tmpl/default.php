<?php
/**
 * Profile Page View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

 ini_set("display_errors","0"); ?>
<script language="javascript" type="text/javascript">
</script>
<?php
$user =& JFactory::getUser();
if ($user->guest) 
{
	?>
<script type="text/javascript" language="javascript"></script>
<?php echo JText::_('PLEASE LOGIN'); ?><a href="#/mojoom/index.php?option=com_mojoom&view=mojoom_login&layout=form"> <?php echo JText::_('HERE'); ?> </a> 

<?php 
} 
else
{
?><?php echo JText::_('PROFILE DETAIL PAGE'); ?>
 	<div id="profile_head" style="width:100%; float:left;">
		<div id="profile_action" style="width:100%; float:left;"><a href="index.php?option=com_mojoom&view=profile_edit&layout=form">Edit</a></div>
		<div id="profile_detail" style="width:100%; float:left;">
			<div id="profile_image" style="width:50%; float:left">
				<img src="<?php echo $this->profile->avatar; ?>" width="50px" height="50px" alt="Image Not Available" />
			</div>
			<div id="profile_overview" style="width:50%; float:left;">
				<?php echo  $this->profile->username; ?>			
			</div>
            <div id="profile_notification" style="width:50%; float:left;">
				<?php echo  count($this->Notification); ?>			
			</div>
			<div id="profile_menu" style="width:100%; float:left;">
				<ul style="list-style:square;">
					<li><a href="#/mojoom/index.php?option=com_mojoom&view=profile_detail"><?php echo JText::_('VIEW DETAIL'); ?></a></li>
					<li><?php echo JText::_('MESSAGE'); ?></li>
					<li><?php echo JText::_('GALLERY'); ?></li>
				</ul>
			</div>
		</div>
	</div>
	
<?php 
} 
?>
	