<?php
/**
 * Notification View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?><link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
     <div id="header_title"><?php echo JText::_('NOTIFICATION'); ?></div>
</div>
<div class="componentcontent">
  <div class="notificationcontainer">	
    <!-- friend notification start -->
    <?php foreach($this->Notification_frd as $frd) { ?>
  	 <div class="community-groups-results-item">	
		<div class="subject"><?php echo JText::_('NEW FRND REQUEST'); ?></div>
        <div class="community-groups-results-left event_thumb" >		
            <a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $frd->id; ?>">
				<?php if($frd->thumb != '') {
					?>
					<img class="avatar" src="<?php echo $frd->thumb; ?>" width="32" border="0" alt="<?php echo $frd->name; ?>"/>
				<?php } else { ?>
					<img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="32" border="0" alt=""/>
				<?php } ?>
            </a>
		</div>
        <div class="community-groups-results-right">
			<p>		
				<?php echo $frd->name; ?> <?php echo JText::_('WANT TO BE FRND'); ?><br>
            </p>
        </div>  
        <div class="groupDescription">        
			<div class="small">      
               <a href="index.php?option=com_mojoom&controller=friend&task=accept&user_id=<?php echo $frd->id; ?>" style="text-indent: 0pt; padding-left: 20px;" class="icon-add-friend">
                    <?php echo JText::_('APPROVE'); ?></a>
               <a href="index.php?option=com_mojoom&controller=friend&task=reject&user_id=<?php echo $frd->id; ?>" style="text-indent: 0pt;" class="icon-remove"><?php echo JText::_('REMOVE'); ?></a>
			</div>
		</div>
    </div>	
    <?php } ?>
    <!-- friend notification end -->
    <!-- email notification start -->
	<?php foreach($this->Notification_msg as $notification) { 
	?>
	 <div class="community-groups-results-item">	
			<div class="subject"><?php echo JText::_('NEW MSG'); ?></div>
			<div class="community-groups-results-left event_thumb" >		
		        	<a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $notification->msg_from; ?>">
					<?php if($notification->thumb != '') {
					?>
					<img class="avatar" src="<?php echo $notification->thumb; ?>" width="32" border="0" alt="<?php echo $notification->from_name; ?>"/>
				<?php } else { ?>
					<img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="32" border="0" alt=""/>
				<?php } ?>
		            </a>
			</div>
            <div class="community-groups-results-right">
				<h3 class="groupName">		
                    <a href="index.php?option=com_mojoom&view=message_detail&task=inbox&id=<?php echo $notification->msg_id; ?>" class="subject">
						<img style="vertical-align: middle;" src="./components/com_community/templates/default/images/new.gif">						
						<?php echo $notification->subject; ?>				</a>
                </h3>
            </div>
            <div class="msgDescription">        
					<div class="small">
					    <?php echo $notification->from_name; ?>, 
						<?php echo JHTML::_('date', $notification->posted_on, JText::_('DATE_FORMAT_LC3')); ?>
                    </div>
            </div>			
	</div><br>
    <?php } ?>
    <!-- email notification end -->    
    <!-- event notification sttart -->    
    <?php foreach($this->Notification_event as $event) { ?>
    <div class="community-groups-results-item">	
	<div class="subject"><?php echo JText::_('NEW EVENT INVIT'); ?></div>		
		<div class="community-groups-results-left event_thumb" >
		            <a href="index.php?option=com_mojoom&controller=event&task=viewevent&event_id=<?php echo $event->id; ?>">
						<img width="32" alt="<?php echo $event->title; ?>" class="avatar" src="<?php if($event->thumb == ""){ ?>./components/com_community/assets/event_thumb.png<?php } else { echo $this->tjumb; }?>">
					</a>
		</div>
		<div class="community-groups-results-right">
			<h3 class="groupName">	
			   	<?php echo $event->name; ?><?php echo JText::_('INVIT TO JOIN'); ?>  <strong><?php echo $event->title; ?></strong> <?php echo JText::_('EVENT').'.'; ?>
			</h3>
        </div>		    	
		 <div class="groupDescription">        
            <div class="small">
                    <a href="index.php?option=com_mojoom&controller=event&task=accept&event_id=<?php echo $event->id ?>" style="text-indent: 0pt; padding-left: 20px;" class="icon-add-friend"><?php echo JText::_('ACCEPT GP'); ?></a>
                    <a href="index.php?option=com_mojoom&controller=event&task=reject&event_id=<?php echo $event->id ?>" style="text-indent: 0pt;" class="icon-remove"><?php echo JText::_('REJECT GP'); ?></a>               
            </div>
		</div>	
	</div>
    <?php } ?>
    <!-- event notification end -->    
    
</div>	
</div>