<?php
/**
 * Profile Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0");
$data = JRequest::get( 'get' );
$my = JFactory::getUser();

?>
<link rel="stylesheet" href="./components/com_mojoom/css/mojoom.css" type="text/css" />
<div id="header_text">
	<div id="header_text">
        <div id="back"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>" /><input type="button" value="Back" class="back"  /></a>
        <!--<input type="button" value="Back" class="back" onclick="javascript:history.back();" />--></div>
         <div id="header_title"><?php if($data['user_id'] == $my->id) { ?>	<?php echo $my->username; ?><?php } else { ?><?php $my1 = JFactory::getUser($data['user_id']);echo $my1->username; ?><?php } ?></div>
        <div id="forward">
        <?php if($data['user_id'] == $my->id) { ?>	
            <a href="index.php?option=com_mojoom&view=profile_edit&layout=default"><input type="button" value="Edit" class="edit" /></a>
        <?php } ?>
        </div>    
	</div>
</div>    	
<div class="componentcontent">

	<div class="detail">	 
		<span><?php echo JText::_('BASIC INFO'); ?></span>
		<div class="detail_1">
			<label for="gender"><?php echo JText::_('GENDER'); ?></label>		
		 	<span><?php echo $this->profile[0]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">			
			<label for="b_date" id="b_dateLabel" name="b_dateLabel"><?php echo JText::_('BIRTHDAY'); ?></label>		
			<span><?php echo $this->profile[1]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="aboutme" id="aboutmeLabel" name="aboutmeLabel"><?php echo JText::_('ABOUT ME'); ?></label>		
			<span><?php echo $this->profile[2]->value; ?></span>
		 </div>
       </div>  
       <div class="detail">	     
         <span><?php echo JText::_( 'Contact Information' ); ?></span> 
		 <div class="detail_1">	
			<label for="phone" id="phoneLabel" name="phoneLabel"><?php echo JText::_('PHONE'); ?></label>		
			<span><?php echo $this->profile[3]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">	
			<label for="address" id="addressLabel" name="addressLabel"><?php echo JText::_('ADDRESS'); ?></label>		
			<span><?php echo $this->profile[5]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
		 <div class="detail_1">	
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('STATE'); ?></label>		
			<span><?php echo $this->profile[6]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">	
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('CITY'); ?></label>		
			<span><?php echo $this->profile[7]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">	
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('COUNTRY'); ?></label>		
			<span><?php echo $this->profile[8]->value; ?></span>
		 </div>
         <div class="detail_border"></div>
         <div class="detail_1">	
			<label for="state" id="stateLabel" name="stateLabel"><?php echo JText::_('WEBSITE'); ?></label>		
			<span><?php echo $this->profile[9]->value; ?></span>
		 </div>
       </div>  
       <div class="detail">	 
         <span><?php echo JText::_( 'EDUCATION INFO' ); ?></span>   
		 <!--<div class="detail_1">	
			<label for="university" id="universityLabel" name="universityLabel">Course Name</label>		
			<span><?php //echo $this->profile[10]->value; ?></span>
		 </div>
         <div class="detail_border"></div>-->
		 <div class="detail_1">
			<label for="year" id="yearLabel" name="stateLabel"><?php echo JText::_('COLLEGE'); ?><br /><?php echo JText::_('UNIVERSITY'); ?></label>		
			<span><?php echo $this->profile[11]->value; ?></span>
		 </div>	
         <div class="detail_border"></div>
		 <div class="detail_1">
			<label for="year" id="yearLabel" name="stateLabel"><?php echo JText::_('GRADUTE YEAR'); ?></label>		
			<span><?php echo $this->profile[11]->value; ?></span>
		 </div>	
	   </div>
       <div class="detail_border_bottom"></div>			
</div>

