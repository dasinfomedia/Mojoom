<?php 
/**
 * Profile Edit View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

 ini_set("display_errors","0"); ?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<script type="text/javascript">
function validatethis()
{
	var numericExpression = /^[0-9]+$/;
	
	if(!document.getElementById('mobile').value.match(numericExpression)){
		alert('Please enter svalid mobile number.');
		document.getElementById('mobile').focus();
		return false;
	}
	if(!document.getElementById('phone').value.match(numericExpression)){
		alert('Please enter valid phone number.');
		document.getElementById('phone').focus();
		return false;
	}
	var x=document.getElementById('website').value;
	regex=/http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/;
	var res = regex.test(x);
	if (res == false)
	{
	  	alert("Please Enter Valid Website URL.");
		return false;
	}
	return true;
}
</script>
<form id="form1" method="post" action="index.php" enctype="multipart/form-data" >
<div id="header_text">
    <div id="back"><!--<input type="button" value="Back" class="back" onclick="javascript:history.back();" />--><a href="<?php echo $_SERVER['HTTP_REFERER'];?>" /><input type="button" value="Back" class="back"  /></a></div>
    <div id="header_title"><?php echo $this->username; ?></div>
    <div id="forward"><input type="submit" name="submit" id="submit" value="Save" class="edit" onclick="return validatethis()"  /></div>
</div>
<div class="componentcontent">

	<div class="detail">	 
		<span><?php echo JText::_( 'BASIC INFO' ); ?></span>
			<div class="detail_1">
			<label for="gender"><?php echo JText::_('PROFILE IMG'); ?></label>				 	
            	<?php
				$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
				$browser1 = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
				if($browser == false)
				{ ?>
                <input type="file" name="profile_avatar" id="profile_avatar"  /> 
                <?php 
				}
				elseif($browser1 == false)
				{ ?>
				<input type="text" name="profile_avatar" id="profile_avatar" value="" />
                <span style="width:80%; float:left;"><?php echo JText::_('ENTER IMG FULL URL'); ?></span>  
				<?php }
				else
				{?>
                <input type="text" name="profile_avatar" id="profile_avatar" value="" />
                <span style="width:80%; float:left;"><?php echo JText::_('ENTER IMG FULL URL'); ?></span>  
                <?php } ?>
		 	</div>
            <div class="detail_border"></div>
            <div class="detail_1">
            <label for="gender"><?php echo JText::_('GENDER'); ?></label>		
             <select name="gender" id="gender">
                <option value="Male" <?php if($this->profile[0]->value == "Male") { ?> selected="selected" <?php } ?>><?php echo JText::_('MALE'); ?></option>
                <option value="Female" <?php if($this->profile[0]->value == "Female") { ?> selected="selected" <?php } ?>><?php echo JText::_('FEMALE'); ?></option>
             </select>
             </div>
             <div class="detail_border"></div>
             <div class="detail_1">
                <label for="b_date" id="b_dateLabel" name="b_dateLabel"><?php echo JText::_('BIRTHDAY'); ?></label>	
                <?php echo $this->createMonths; ?> 
				<?php echo $this->createDays; ?>
				<?php echo $this->createYears; ?>	               
             </div>
             <div class="detail_border"></div>
             <div class="detail_1">
                <label for="aboutme" id="aboutmeLabel" name="aboutmeLabel"><?php echo JText::_('ABOUT ME'); ?></label>		
                <textarea name="aboutme" id="aboutme" ><?php echo $this->profile[2]->value; ?></textarea>
             </div>
     </div>
     <div class="detail">	 
        <span><?php echo JText::_( 'CONTACT INFORMATION' ); ?></span>         
		 <div class="detail_1">
			<label for="phone" id="phoneLabel" name="phoneLabel"><?php echo JText::_('MOBILE'); ?></label>		
			<input type="text" name="mobile" id="mobile" value="<?php echo $this->profile[3]->value; ?>"  />
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">
			<label for="phone" id="phoneLabel" name="phoneLabel"><?php echo JText::_('PHONE'); ?></label>		
			<input type="text" name="phone" id="phone" value="<?php echo $this->profile[4]->value; ?>"  />
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="address" id="addressLabel" name="addressLabel"><?php echo JText::_('ADDRESS'); ?></label>		
			<textarea name="address" id="address"   ><?php echo $this->profile[5]->value; ?></textarea>
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('STATE'); ?></label>		
			<input type="text" name="state" id="state" value="<?php echo $this->profile[6]->value; ?>" />
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('CITY'); ?></label>		
			<input type="text" name="city" id="city" value="<?php echo $this->profile[7]->value; ?>" />
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('COUNTRY'); ?></label>		
			<input type="text" name="country" id="country" value="<?php echo $this->profile[8]->value; ?>" />
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('YOUR WEBSITE'); ?></label>		
			<input type="text" name="website" id="website" value="<?php echo $this->profile[9]->value; ?>" />
		 </div>	
      </div>
      <div class="detail">	
         <span><?php echo JText::_( 'EDUCATION' ); ?></span>     
		 <div class="detail_1">
			<label for="university" id="universityLabel" name="universityLabel"><?php echo JText::_('UNIVERSITY'); ?></label>		
			<input type="text" name="university" id="university" value="<?php echo $this->profile[10]->value; ?>"  />
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="year" id="yearLabel" name="stateLabel"><?php echo JText::_('YEAR EDIT'); ?></label>		
			<input type="text" name="year" id="year" value="<?php echo $this->profile[11]->value; ?>" />
		 </div>			 
	</div>
    <div class="detail_border_bottom"></div>				
	<input type="hidden" name="option" value="com_mojoom" />
	<input type="hidden" name="task" value="profile_edit" />
	<input type="hidden" name="controller" value="mojoom" />
	<input type="hidden" name="id" value="<?php echo $this->profile[0]->user_id; ?>" />
	</form>		
</div> 

