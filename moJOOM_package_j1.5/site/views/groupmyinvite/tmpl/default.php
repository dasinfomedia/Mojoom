<?php
/** 
 * Group My Invite View for mojoom Component
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
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('PENDING INVIT TITLE'));
	

?> 
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
     <div id="header_title_groups">
			<ul id="sub_menu">
				<li class="leftrndcrnr"></li>
				<li id="left"><a href="index.php?option=com_mojoom&controller=groups&task=mygroups"><?php echo JText::_('GROUP BUL'); ?></a></li>
				<li class="saperator"></li>
				<li id="mid"><a class="active" href="index.php?option=com_mojoom&controller=groups&task=myinvite"><?php echo JText::_('MY INVITES'); ?></a></li>
				<li class="saperator"></li>
				<li id="right"><a href="index.php?option=com_mojoom&controller=groups&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
				<li class="rightrndcrnr"></li>
			</ul>
	</div>
</div>
<div class="componentcontent">
	<div class="groupscontainer">
<!--======================= -->
	<div class="cLayout clrfix">

		<!-- ALL MY GROUP LIST -->

		<div class="clrfix">

		<?php

		if( $this->groups )

		{
			//print_r($this->groups);
		?>

			<div>

			<?php 
				$count = count($this->groups);
				if($count == 1)
					echo JText::sprintf('GROUP INVIT', $count ); 
				else
					echo JText::sprintf('GROUPS INVIT', $count ); 
			?>

			</div><br />

			<?php

			foreach($this->groups as $group)
			{


				?>

				<div class="community-groups-results-item" id="groups-invite-<?php echo $group->id;?>">

					<div class="community-groups-results-left">

						<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id=' . $group->id );?>">
						<?php
								$avtarimg = '';
								if($group->thumb == '')
									$avatarimg = 'components/com_community/assets/group_thumb.jpg';
								else
									$avatarimg = $group->thumb;
						?>
						<img class="avatar" src="<?php echo $avatarimg;?>" alt="<?php echo $this->escape($group->name); ?>"/></a>

					</div>

					<div class="community-groups-results-right">

						<h3 class="groupName">

							<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id=' . $group->id );?>"><?php echo $group->name; ?></a>

						</h3>
						
						<span class="icon-group" style="margin-right: 5px;">&nbsp;(
								<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewmembers&group_id=' . $group->id ); ?>"><?php
								if($group->membercount == 1)
									echo JText::sprintf('MEM INV', $group->membercount);
								else
									echo JText::sprintf('MEMS INV', $group->membercount);
									?></a> )
						</span>

						<div class="groupDescription">
						<?php
								$totwords = str_word_count($group->description);
								$numwords = 5; 
								if($totwords > $numwords)
								{
							 		preg_match("/(\S+\s*){0,$numwords}/", $group->description, $regs); 
							 		$shortdesc = trim($regs[0]); 
						 			echo JText::_($shortdesc)." ..."; 
								}
								else
								{
									echo JText::_($group->description);
								}
								?>
						</div>

						<div class="groupCreated small"><?php echo JText::sprintf('CREATE ON INVIT' , JHTML::_('date', $group->created, JText::_('DATE_FORMAT_LC3')) );?>
						</div>            

            

						<div class="groupActions">

							<span class="icon-discuss" style="margin-right: 5px;">

								<?php
								if($group->discusscount == 0 || $group->discusscount == 1 )
									 echo JText::sprintf('DISCUSSION INVIT', $group->discusscount);
								else
									echo JText::sprintf('DISCUSSIONS INVIT', $group->discusscount);
								?>

							</span>

							<span class="icon-wall" style="margin-right: 5px;">

								<?php
									if($group->wallcount == 0  || $group->wallcount == 1 )
										 echo JText::sprintf('WALL POST INVIT', $group->wallcount);
									else
										 echo JText::sprintf('WALL POSTS INVIT', $group->wallcount);
								?>

							</span>

						</div>

						<div class="community-groups-pending-actions">

							<a class="icon-add-friend" href="index.php?option=com_mojoom&controller=groups&task=acceptinvite&group_id=<?php echo $group->id; ?>" ><?php echo JText::_('Accept');?></a>

							<a class="icon-remove" href="index.php?option=com_mojoom&controller=groups&task=rejecttinvite&group_id=<?php echo $group->id; ?>" ><?php echo JText::_('Reject');?></a>

						</div>

					</div>

		<div style="clear: both;"></div>

	</div>

	<?php

		}

	}

	else

	{

	?>

		<div class="group-not-found"><?php echo JText::_('HAVE NO PENDING INVIT'); ?></div>

	<?php

	}

	?>


	</div>

	<div class="clr"></div>

</div>
<!--======================= -->
	</div>
</div>
<?php 
} 
?>