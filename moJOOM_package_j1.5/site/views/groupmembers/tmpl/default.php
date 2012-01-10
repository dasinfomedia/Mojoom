<?php
/** 
 * Group Members View for mojoom Component
 * 
 * @package   mojoom
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
	$document->setTitle(JText::_('GROUP MEM TITLE'));
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
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
</div>
<div class="componentcontent">
	<div class="groupscontainer">
		<div class="GroupName"><h3><?php echo $this->group->name.JText::_('\'s Members'); ?></h3></div>
		<?php if( $this->members ) {
					foreach($this->members as $member)
					{
					?>
						<div class="mini-profile" id="member_<?php echo $member->id;?>">
							<div class="mini-profile-avatar">			
								<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $member->id); ?>">
								<?php if($member->thumb != '')
								{?>
								<img class="avatar" src="<?php echo $member->thumb; ?>" alt="<?php echo $member->username; ?>" />							<?php } else { ?>
								<img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" alt=""/>
								<?php } ?>
								</a> 
							</div>
							<div class="mini-profile-details">
								<h3 class="name">
									<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $member->id); ?>"><strong><?php echo $member->username; ?></strong></a>
								</h3>
								<div id="onlinestatus">
									<?php if($member->isOnline == 1){ ?>
										<img src="./components/com_mojoom/images/green.png" width="12px" height="12px" alt="On" />
									<?php } else { ?> 
										<img src="./components/com_mojoom/images/red.png" width="12px" height="12px" alt="Off" /> 
									<?php } ?> 
								</div>
								<div class="mini-profile-details-status"><?php echo $member->status ;?></div>
								<span class="icon-group">
									<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=friend&user_id=' . $member->id );?>"><?php 
									if($member->friendsCount == 1 || $member->friendsCount == 0 )
										echo JText::sprintf( '1FRIEND' , $member->friendsCount);
									else
										echo JText::sprintf( '1FRIEND' , $member->friendsCount);
									?>
									</a>
								</span>
								<?php $my = JFactory::getUser();
								if($my->id != $member->id)
								{
								?>
								<span class="icon-write">
									<a href="index.php?option=com_mojoom&view=compose&id=<?php echo $member->id; ?> "><?php echo JText::_('Write message'); ?></a>
					        	</span>
								<?php
								}
								?>
							</div>
							
						</div>
					<?php
					}
				}
		?>
	</div>
</div>
<?php
}
?>