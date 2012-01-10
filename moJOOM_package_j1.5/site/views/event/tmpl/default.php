<?php
/**
 * Event View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0");
 ?>
<?php
$document = JFactory::getDocument();
if($this->Event->id == ""){
$document->setTitle(JText::_('CREATE NEW EVENT'));
}else {
$document->setTitle(JText::_('EVENT TITLE'));
}
?>
<script type="text/javascript">
function validate()
{
	if(document.getElementById('title').value == "")
	{
		alert("Please Enter the Title");
		document.getElementById('title').focus();
		return false;
	}
	if(document.getElementById('location').value == "")
	{
		alert("Please Enter the Location");
		document.getElementById('location').focus();
		return false;
	}
	if(document.getElementById('ticket').value == "")
	{
		alert("Please Enter the Ticket");
		document.getElementById('ticket').focus();
		return false;
	}

	return true;
}
</script>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<form id="form1" method="post" action="index.php" enctype="multipart/form-data" >
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
    <div id="header_title_events">
            <ul id="sub_menu">
            	<li class="leftrndcrnr"></li>
                <li id="left"><a href="index.php?option=com_mojoom&controller=event&task=myevents" ><?php echo JText::_('EVENT GROUP EVENT'); ?></a></li>
                <li class="saperator"></li>
                <li id="right"><a href="index.php?option=com_mojoom&controller=event&task=create" class="active"><?php echo JText::_('CREATE BUL'); ?></a></li>    
                <li class="rightrndcrnr ractive"><span>&nbsp;</span></li>            
            </ul>
    </div>
    <div id="forward" class="android"><input type="submit" name="submit" id="submit" value="Save" class="edit" onclick="return validate();" /></div>
</div>
<div class="componentcontent"> 
	<?php if($this->Event_Count < $this->eventcreatelimit) { ?>
    <div class="detail">
	    <?php if($this->eventcreatelimit != 0 ) { ?>
    		<span><?php echo JText::sprintf('YOU CREATE EVENT', $this->Event_Count, $this->eventcreatelimit );?></span>
        <?php } ?>    	 
		    <div class="detail_1">
			<label for="gender">*<?php echo JText::_('TITLE'); ?></label>				 	
            	<input type="text" name="title" id="title" value="<?php echo $this->Event->title; ?>" />                
		 	</div>
            <div class="detail_border"></div>
            
            <div class="detail_1">
			<label for="gender"><?php echo JText::_('DESCRIPTION'); ?></label>				 	
            	<textarea name="description" id="description" ><?php echo strip_tags($this->Event->description); ?></textarea>
		 	</div>
            <div class="detail_border"></div>           
            
            <div class="detail_1">
			<label for="gender">*<?php echo JText::_('CATEGORY'); ?></label>				 	
            	 <select name="catid" id="catid" >
				<?php foreach( $this->Category as $category ){ ?>
					<option value="<?php echo $category->id;?>" <?php if($this->Event->catid == $category->id) { ?> selected="selected" <?php } ?> ><?php echo $category->name; ?></option>
				<?php } ?>
				</select>                   
		 	</div>
            <div class="detail_border"></div>
            
            <div class="detail_1">
			<label for="gender">*<?php echo JText::_('LOCATION'); ?></label>				 	
            	<input type="text" name="location" id="location" value="<?php echo $this->Event->location; ?>" />                
		 	</div>
            <div class="detail_border"></div>
            
            <div class="detail_1">
                <label for="b_date" id="b_dateLabel" name="b_dateLabel">*<?php echo JText::_('START DATE/TIME'); ?></label>		
                <?php
				$date_time = explode(' ',$this->Event->startdate); 				
				$time = explode(':',$date_time[1]);
				if($time[0] > 12 )				
				{
					$s_hour	= $time[0] - 12;
					$s_ampm = 'PM'; 	 
				}
				else
				{
					$s_hour = $time[0];
					$s_ampm = 'AM'; 	 
				}				
				$s_min = $time[1];
				
				?>
                <?php echo $this->createMonths1; ?> 
				<?php echo $this->createDays1; ?>
				<?php echo $this->createYears1; ?><br />	    
                <select class="required inputbox" id="starttime-hour" name="starttime-hour">
                	<?php for($i=1;$i<=12;$i++) { ?>
                    <option value="<?php echo $i; ?>" <?php if($i == $s_hour) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
                    <?php } ?>                   
                 </select>
                 :
                 <select class="required inputbox" id="starttime-min" name="starttime-min">
                 	<?php for($i=0;$i<45;$i=$i+15) { ?>
                    <option value="<?php echo $i; ?>" <?php  if($i == $s_min) { ?> selected="selected" <?php }?> > 
						<?php echo $i; ?> 
                    </option> 
                    <?php } ?>                    
                 </select> 
                 <select class="required inputbox" id="starttime-ampm" name="starttime-ampm">
                 	<option value="AM" <?php if($s_ampm == 'AM'){ ?> selected="selected" <?php } ?>  >am</option>
                    <option value="PM" <?php if($s_ampm == 'PM'){ ?> selected="selected" <?php } ?>>pm</option>
                 </select>
             </div>
             <div class="detail_border"></div>
            
            <div class="detail_1">
                <label for="b_date" id="b_dateLabel" name="b_dateLabel">*<?php echo JText::_('END DATE/TIME'); ?></label>
                <?php 
				$date_time = explode(' ',$this->Event->enddate); 				
				$time = explode(':',$date_time[1]);
				if($time[0] > 12 )				
				{
					$e_hour	= $time[0] - 12;
					$e_ampm = 'PM'; 	 
				}
				else
				{
					$e_hour = $time[0];
					$e_ampm = 'AM'; 	 
				}				
				$e_min = $time[1];
				?>		
              	<?php echo $this->createMonths2; ?> 
				<?php echo $this->createDays2; ?>
				<?php echo $this->createYears2; ?><br />	 
                <select class="required inputbox" id="endtime-hour" name="endtime-hour">
                	<?php for($i=1;$i<=12;$i++) { ?>
                    <option value="<?php echo $i; ?>"  <?php if($i == $e_hour) { ?> selected="selected" <?php } elseif($i==4) { ?> selected="selected" <?php }else{} ?> ><?php echo $i; ?> </option>
                    <?php } ?>                   
                </select>:<select class="required inputbox" id="endtime-min" name="endtime-min">
                	<?php for($i=0;$i<45;$i=$i+15) { ?>
                    <option value="<?php echo $i; ?>"  <?php if($i == $e_min) { ?> selected="selected" <?php } ?> > <?php echo $i; ?> </option>
                    <?php } ?>   
                </select><select class="required inputbox" id="endtime-ampm" name="endtime-ampm">
                   <option value="AM" <?php if($e_ampm == 'AM'){ ?> selected="selected" <?php } ?>  >am</option>
                    <option value="PM" <?php if($e_ampm == 'PM'){ ?> selected="selected" <?php } ?> >pm</option>
                </select>			
            </div>
            <div class="detail_border"></div>                         
           	<div class="detail_1">
			<label for="gender">*<?php echo JText::_('TIMEZONE'); ?></label>				 	            	
               <select name="offset" style="width:125px;">
				<?php
				foreach( $this->Timezone as $offset => $value ){
				?>
					<option value="<?php echo $offset;?>" <?php if($offset == $this->Event->offset){ ?>  selected="selected" <?php }elseif($offset == 0){?> selected="selected" <?php }else {} ?> > <?php echo $value;?> </option>
				<?php
				}
				?>
				</select>              
		 	</div>
            <div class="detail_border"></div>
            
            <div class="detail_1">
			<label for="gender"><?php echo JText::_('TYPE'); ?></label>				 	
            	<div style="float:left; width:50%;">
                <input type="radio" name="permission" id="permission-open" value="0" <?php if($this->Event->permission == 0 ) { ?> checked="checked" <?php } ?> />Open
                </div>
                <div style="float:left; width:50%;">
                <input type="radio" name="permission" id="permission-private" value="1" <?php if($this->Event->permission == 1 ) { ?> checked="checked" <?php } ?>  />Private
              	</div>                
		 	</div>            
            <div class="detail_border"></div>
            
            <div class="detail_1">
			<label for="gender">*<?php echo JText::_('NUMBER OF SEATS'); ?></label>				 	
            	<input type="text" name="ticket" id="ticket" value="<?php echo $this->Event->ticket; ?>" />                
		 	</div>
            <div class="detail_border"></div>
            
            <div class="detail_1">
			<label for="gender"><?php echo JText::_('ALLOW GUEST TO INVITE'); ?></label>
            	<div style="float:left; width:50%;">				 	
            	<input type="radio" name="allowinvite" id="allowinvite0" value="1" <?php if($this->Event->allowinvite == 1 ) { ?> checked="checked" <?php } ?>  /><?php echo JText::_('YES EV'); ?>
               
                </div>
                <div style="float:left; width:50%;">
                <input type="radio" name="allowinvite" id="allowinvite1" value="0" <?php if($this->Event->allowinvite == 0 ) { ?> checked="checked" <?php } ?>  /><?php echo JText::_('NO EV'); ?>
                
                </div>                
		 	</div>            
     </div>     	
	<?php } else { ?>
	<div class="detail">
    <span><?php echo JText::_('YOU ARE ALREADY CREATED MAXIMUM EVENT');?></span>
	<?php } ?> 
     
</div>
	<input type="hidden" name="option" value="com_mojoom" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="controller" value="event" />
	<input type="hidden" name="id" value="<?php echo $this->Event->id; ?>" />
	</form>		


