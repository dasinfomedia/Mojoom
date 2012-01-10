<?php
/**
 * Group Event View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<?php
$data = JRequest::get( 'get' );
$user =& JFactory::getUser();
$userid = $user->get('id');
if($user->guest) 
{ 
?>
	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('GROUP EVENT TITLE'));
	?>		
		<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
		<div id="header_text">
			<div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
			 <div id="header_title_events">
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
			<?php
			if( $this->Event )
			{				
				foreach($this->Event as $event)
				{
					?>
					<div class="community-groups-results-item">	
						<div class="community-groups-results-left event_thumb" >
							<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id=' . $event->id .'&group_id='. $data['group_id']);?>">
							<?php
								$avtarimg = '';
								if($group->thumb == '')
									$avatarimg = 'components/com_community/assets/event_thumb.png';
								else
									$avatarimg = $event->thumb;
							?>
							
							<img class="avatar" src="<?php echo $avatarimg;?>" alt="<?php echo JText::_($event->name); ?>"/></a>
						</div>
						<div class="community-groups-results-right">
							<h3 class="groupName">
								<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id=' . $event->id .'&group_id='. $data['group_id'] );?>">
								<?php 
								$e_name = str_word_count($event->title);
								$numwords = 7; 
								if($e_name > $numwords)
								{
							 		preg_match("/(\S+\s*){0,$numwords}/", $event->title, $regs); 
							 		$shortdesc = trim($regs[0]); 
						 			echo JText::_($shortdesc)." ..."; 
								}
								else
								{
									echo JText::_($event->title); 
								} ?></a>
							</h3>
							<span class="icon-group" style="margin-right: 5px;">&nbsp;(
								<a href="<?php //echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewmembers&group_id=' . $group->id ); ?>"><?php
								if($event->confirmedcount == 1)
									echo JText::sprintf('GUEST EVENT', $event->confirmedcount);
								else
									echo JText::sprintf('GUESTS EVENT', $event->confirmedcount);
									?></a> )
							</span>
						<div class="groupDescription">
							<?php
								$totwords = str_word_count($event->description);
								$numwords = 10; 
								if($totwords > $numwords)
								{
							 		preg_match("/(\S+\s*){0,$numwords}/", $event->description, $regs); 
							 		$shortdesc = trim($regs[0]); 
						 			echo JText::_($shortdesc)." ..."; 
								}
								else
								{
									echo JText::_($event->description);
								}
								?></div>
						<div class="groupCreated small"><?php echo JText::sprintf('From: <br> %1$s' , JHTML::_('date', $event->startdate, JText::_('%a, %d %B %Y %I:%M %P')) );?></div>
                        <div class="groupCreated small"><?php echo JText::sprintf('Until: <br> %1$s' , JHTML::_('date', $event->enddate, JText::_('%a, %d %B %Y %I:%M %P')) );?></div>    
						
					</div>
				</div>
			<?php
			}
	}			
	else
	{
		echo JText::_('No event found');	
	}
	?>
    <!--	===================================== -->
	<!-- create event link -->
	<?php 
	if ($this->GroupOwner == $userid ) { ?>
    <a style="float:left;width:100%;text-align:right;padding-top:10px;" href="index.php?option=com_mojoom&controller=event&task=creategroupevent&group_id=<?php echo $data['group_id'];?>"><?php echo JText::_('CREATE EVENT'); ?></a>
    <?php } ?>
	</div>
</div>
    
<?php	
} 
?>