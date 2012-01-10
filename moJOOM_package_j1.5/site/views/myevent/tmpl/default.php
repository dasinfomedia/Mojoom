<?php 
/**
 * My Event View for Mojoom Component
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
if($user->guest) 
{ 
?>

	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('EVENT'));
	?>
		<script language="javascript" type="text/javascript">
		function filterevents()
		{		
			var selobj = document.getElementById('Event_Type');
			var index = selobj.selectedIndex;
			t_id = selobj.options[index].value;
			window.location.href = "index.php?option=com_mojoom&controller=event&task=myevents&event_type="+ t_id;
		}
		</script>
        
		<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
		<div id="header_text">
			<div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
			 <div id="header_title_events">
					<ul id="sub_menu">
                    	<li class="leftrndcrnr active"><span>&nbsp;</span></li>
						<li id="left"><a href="index.php?option=com_mojoom&controller=event&task=myevents" class="active"><?php echo JText::_('EVENT GROUP EVENT'); ?></a></li>
                        <li class="saperator"></li>
						<!--<li id="mid"><a href="index.php?option=com_mojoom&controller=event&task=invite">Category</a></li>
                        <li class="saperator"></li>-->
						<li id="right"><a href="index.php?option=com_mojoom&controller=event&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
                        <li class="rightrndcrnr"></li>
					</ul>
			</div>
		</div>
       <div class="componentcontent">
			<div class="groupscontainer">
			<select name="Event_Type" id="Event_Type" onchange="filterevents();">
                <option value="1" <?php if($data['event_type'] == 1) { ?> selected="selected" <?php } ?> ><?php echo JText::_('MYEVENT');?></option>
                <option value="2" <?php if($data['event_type'] == 2) { ?> selected="selected" <?php } ?> ><?php echo JText::_('ALLEVENT');?></option>
                <option value="3" <?php if($data['event_type'] == 3) { ?> selected="selected" <?php } ?> ><?php echo JText::_('PASTEVENT');?></option>				
			</select>
			<br /><br />
			<?php
			if( $this->Myevent )
			{				
				foreach($this->Myevent as $event)
				{
					?>
					<div class="community-groups-results-item">	
						<div class="community-groups-results-left event_thumb" >
							<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id=' . $event->id );?>">
							<?php
								$avtarimg = '';
								if($group->thumb == '')
									$avatarimg = 'components/com_community/assets/event_thumb.png';
								else
									$avatarimg = $event->thumb;
							?>
							
							<img class="avatar" src="<?php echo $avatarimg;?>" alt="<?php echo JText::_($event->name); ?>"/></a>
                            <div class="eventDate"><?php echo JHTML::_('date', $event->startdate, JText::_('%b %d'));?></div>
						</div>
						<div class="community-groups-results-right">
							<h3 class="groupName">
								<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=event&task=viewevent&event_id=' . $event->id );?>">
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
							<span class="icon-group" style="margin-right: 5px; float:right ;">&nbsp;(
								<!--<a href="<?php //echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewmembers&group_id=' . $group->id ); ?>">--><?php
								if(($event->confirmedcount ) == 1)
									echo JText::sprintf('GUEST EVENT', $event->confirmedcount);
								else
									echo JText::sprintf('GUESTS EVENT', $event->confirmedcount);
									?><!--</a>--> )
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
						<div class="groupCreated small">
						<?php //echo JText::sprintf('From: %1$s' , JHTML::_('date', $event->startdate, JText::_('DATE_FORMAT_LC3')) );?>
                        <?php echo JText::sprintf('From: %1$s' , JHTML::_('date', $event->startdate, JText::_('%d %b %Y %I:%M %P')) );?>
                        </div>
                        <div class="groupCreated small">
						<?php //echo JText::sprintf('Until: %1$s' , JHTML::_('date', $event->enddate, JText::_('DATE_FORMAT_LC3')) );?>
                        <?php echo JText::sprintf('Until: %1$s' , JHTML::_('date', $event->enddate, JText::_('%d %b %Y %I:%M %P')) );?>
                        </div>    
						<div class="groupActions11">
	
							<span class="icon-discuss" style="margin-right: 5px; float:left;">
								<a href="index.php?option=com_mojoom&controller=event&task=invite&event_id=<?php echo $event->id; ?>"><input type="button" class="invite" value="Invite" /></a>				</span>	
							<span class="icon-wall" style="margin-right: 5px; float:left;">
							<?php if($event->creator == $this->user_id) { ?><a href="index.php?option=com_mojoom&controller=event&task=edit&event_id=<?php echo $event->id; ?>"><input type="button" class="invite" value="Edit" /></a> <?php } ?>
							</span>
						</div>
					</div>
				</div>
			<?php
			}
	}			
	else
	{
		echo JText::_('NO EVENT FOUND');	
	}
	?>
    	</div>
	</div>
<?php	
} 
?>