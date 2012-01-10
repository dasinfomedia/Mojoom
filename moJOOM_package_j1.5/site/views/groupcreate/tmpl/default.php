 <?php 
/** 
 * Group Create View for Mojoom Component
 * 
 * @package   Mojoom
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
	$document->setTitle(JText::_('GROUP TITLE'));
	

?> 
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<form method="post" action="index.php" id="createGroup" name="jsform-groups-create" class="community-form-validate">

<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
     <div id="header_title_groups">
			<ul id="sub_menu">
				<li class="leftrndcrnr"></li>
				<li id="left"><a href="index.php?option=com_mojoom&controller=groups&task=mygroups"><?php echo JText::_('GROUP BUL'); ?></a></li>
				<li class="saperator"></li>
				<li id="mid"><a href="index.php?option=com_mojoom&controller=groups&task=myinvite"><?php echo JText::_('MY INVITES'); ?></a></li>
				<li class="saperator"></li>
				<li id="right"><a class="active" href="index.php?option=com_mojoom&controller=groups&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
				<li class="rightrndcrnr ractive"><span>&nbsp;</span></li>
			</ul>
	</div>
	 <div id="forward"><input type="submit" value="<?php if(!$this->groupid){ ?>Save<?php }else { ?>Edit<?php } ?>" class="edit" id="submit" name="submit" /></div>  
</div>
<div class="componentcontent">
<div id="community-groups-wrap">
<?php if(!$this->groupid){ ?>
<?php echo JText::_('GROUP TEXT'); ?>
<?php } 
// set the var that indicates that we are in edit mode
$editflag = 0;
if($this->groupinfo)
{	
	//print_r($this->params);
	//echo '</pre>';
	$gp = $this->groupinfo;
	$editflag = 1;
}
?>
<div class="hints">
		<?php echo JText::sprintf('YOU HAVE CREATED OUT OFGROUP', $this->groupCreated, 300 );?>
	</div>
	<br />
	<table class="creategroupformtable" cellspacing="1" cellpadding="0" width="100%" >
	<!-- group name -->
	<tr>
		<td class="key">
			<label for="name" class="label title jomTips" title="<?php echo JText::_('GROUP NAME');?>::<?php echo JText::_('GROUP NAME TIPS'); ?>">
				*<?php echo JText::_('GROUP NAME'); ?>
			</label>
		</td>
		<td class="value">
			<input name="name" id="name1" type="text" class="required inputbox" value="<?php if($editflag){ echo $gp->name; }?>" size="20" />
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<!-- group description -->
	<tr>
		<td class="key">
			<label for="description" class="label title jomTips" title="<?php echo JText::_('GROUP DESCRIPTION');?>::<?php echo JText::_('GROUP DESCRIPTION TIPS');?>">
				*<?php echo JText::_('GROUP DESCRIPTION');?>
			</label>
		</td>
		<td class="value">
			<textarea name="description" id="description" class="required inputbox" cols="20"><?php if($editflag){ echo $gp->description; }?></textarea>
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<!-- group category -->
	<tr>
		<td class="key">
			<label for="categoryid" class="label title jomTips" title="<?php echo JText::_('GROUP CATEGORY');?>::<?php echo JText::_('GROUP CATEGORY TIPS');?>">
				*<?php echo JText::_('GROUP CATEGORY');?>
			</label>
		</td>
		<td class="value">
			<select name="categoryid" id="categoryid">
			<?php foreach($this->cat as $ct)
				{?>
					<option value="<?php echo $ct->id; ?>" <?php if($editflag){ if($ct->id == $gp->categoryid) { ?> selected="selected" <?php } }?>><?php echo $ct->name; ?></option>
					<?php
				}
				?>
			</select>
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<!-- group type -->
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('GROUP TYPE');?>::<?php echo JText::_('GROUP REQUIRE APPROVAL TIPS');?>">
				<?php echo JText::_('GROUP TYPE'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="approvals" id="approve-open" value="0" <?php if($editflag){ echo ($gp->approvals == 0 ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';} ?>/>
				<label for="approve-open" class="label lblradio"><?php echo JText::_('Open');?></label>
			</div>
			<!--<div style="margin-bottom: 10px;" class="small">
				<?php //echo JText::_('Anyone can join and view this group.');?>
			</div> -->
			
			<div>
				<input type="radio" name="approvals" id="approve-private" value="1" <?php if($editflag){ echo ($gp->approvals == 1 ) ? ' checked="checked"' : ''; }?> />
				<label for="approve-private" class="label lblradio"><?php echo JText::_('Private');?></label>
			</div>
			<!--<div class="small">
				<?php //echo JText::_("This group requires approval for new members to join. Anyone can view the group's description. Only group members are allowed to see the group's content.");?>
			</div> -->
		</td>
	</tr>
	<tr class="detail_border"></tr>
		<!-- group ordering -->
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('DISCUSS ORDERING');?>::<?php echo JText::_('DISCUSS ORDERING TIPS');?>">
				<?php echo JText::_('DISCUSS ORDERING'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="discussordering" id="discussordering-lastreplied" value="0" <?php if($editflag){echo ($this->params->get('discussordering') == 0 ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?>/>
				<label for="discussordering-lastreplied" class="label lblradio" ><?php echo JText::_('Order by last replied');?></label>
			</div>
			<div>
				<input type="radio" name="discussordering" id="discussordering-creation" value="1" <?php if($editflag){echo ($this->params->get('discussordering') == 1 ) ? ' checked="checked"' : ''; }?>/>
				<label for="discussordering-creation" class="label lblradio"><?php echo JText::_('ORDER BY CREATION DATE');?></label>
			</div>
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('PHOTOS');?>::<?php echo JText::_('GROUP PHOTOS PERMISSION TIPS');?>">
				<?php echo JText::_('PHOTOS'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="photopermission" id="photopermission-disabled" value="-1" <?php if($editflag){ echo ($this->params->get('photopermission') == -1 ) ? ' checked="checked"' : ''; }?>/>
				<label for="photopermission-disabled" class="label lblradio"><?php echo JText::_('GROUP PHOTO DISABLED.');?></label>
			</div>
			<div>
				<input type="radio" name="photopermission" id="photopermission-members" value="0" <?php if($editflag){ echo ($this->params->get('photopermission') == 0 ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?> />
				<label for="photopermission-members" class="label lblradio"><?php echo JText::_('GROUP PHOTO ALLOW MEMBERS UPLOAD');?></label>
			</div>
			<div>
				<input type="radio" name="photopermission" id="photopermission-admin" value="1" <?php if($editflag){ echo ($this->params->get('photopermission') == 1 ) ? ' checked="checked"' : ''; }?> />
				<label for="photopermission-admin" class="label lblradio"><?php echo JText::_('GROUP PHOTO ALLOW ONLY ADMINS UPLOAD');?></label>
			</div>		
		</td>
	</tr>
	<tr class="detail_border"></tr>	
	<tr>
		<td class="key">
			<label for="grouprecentphotos-admin" class="label title jomTips" title="<?php echo JText::_('GROUP RECENT PHOTOS LIMIT');?>::<?php echo JText::_('GROUP RECENT PHOTOS LIMIT TIPS');?>">
				<?php echo JText::_('GROUP RECENT PHOTOS LIMIT');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="grouprecentphotos" id="grouprecentphotos-admin" value="<?php if($editflag){ echo $this->params->get('grouprecentphotos', 6); }else { echo '6'; }?>" size="5"  />
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<!-- group videos -->
	
	<tr>
		<td class="key">
			<label for="discussordering" class="label title jomTips" title="<?php echo JText::_('VIDEOS');?>::<?php echo JText::_('GROUP VIDEOS PERMISSION TIPS');?>">
				<?php echo JText::_('VIDEOS'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="videopermission" id="videopermission-disabled" value="-1" <?php if($editflag){ echo ($this->params->get('videopermission') == -1 ) ? ' checked="checked"' : ''; }?>/>
				<label for="videopermission-disabled" class="label lblradio"><?php echo JText::_('GROUP VIDEO DISABLED');?></label>
			</div>
			<div>
				<input type="radio" name="videopermission" id="videopermission-members" value="0" <?php if($editflag){ echo ($this->params->get('videopermission') == 0 ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?>/>
				<label for="videopermission-members" class="label lblradio"><?php echo JText::_('GROUP VIDEO ALLOW MEMBERS UPLOAD');?></label>
			</div>
			<div>
				<input type="radio" name="videopermission" id="videopermission-admin" value="1" <?php if($editflag){ echo ($this->params->get('videopermission') == 1 ) ? ' checked="checked"' : ''; }?>/>
				<label for="videopermission-admin" class="label lblradio"><?php echo JText::_('GROUP VIDEO ALLOW ONLY ADMINS UPLOAD');?> 
			</div>
			
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label for="grouprecentvideos-admin" class="label title jomTips" title="<?php echo JText::_('GROUP RECENT VIDEO LIMIT');?>::<?php echo JText::_('GROUP RECENT VIDEO LIMIT TIPS');?>">
				<?php echo JText::_('GROUP RECENT VIDEO LIMIT');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="grouprecentvideos" id="grouprecentvideos-admin" value="<?php if($editflag){ echo $this->params->get('grouprecentvideos', 6); }else { echo '6'; }?>" size="5" />
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('Events');?>::<?php echo JText::_('SET THE PERMISSION FOR GROUP');?>"><?php echo JText::_('EVENT GROUP EVENT');?></label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="eventpermission" id="eventpermission-disabled" value="-1" <?php  if($editflag){ echo ($this->params->get('eventpermission') == -1 ) ? ' checked="checked"' : ''; }?> />
				<label for="eventpermission-disabled" class="label lblradio"><?php echo JText::_('DISABLE GROUP EVENTS');?></label>
			</div>
			<div>
				<input type="radio" name="eventpermission" id="eventpermission-members" value="0" <?php  if($editflag){ echo ($this->params->get('eventpermission') == 0 ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?> />
				<label for="eventpermission-members" class="label lblradio"><?php echo JText::_('ALLOW MEMBER TO CREATE GROUP');?></label>
			</div>
			<div>
				<input type="radio" name="eventpermission" id="eventpermission-admin" value="1" <?php  if($editflag){ echo ($this->params->get('eventpermission') == 1 ) ? ' checked="checked"' : ''; }?> />
				<label for="eventpermission-admin" class="label lblradio"><?php echo JText::_('ALLOW ONLY GROUP ADMINS TO CREATE GROUP');?></label>
			</div>
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label for="grouprecentevents-admin" class="label title jomTips" title="<?php echo JText::_('GROUP EVENTS');?>::<?php echo JText::_('GROUP EVENTS TIPS');?>">
				<?php echo JText::_('GROUP EVENTS');?>
			</label>
		</td>
		<td class="value">
			<input type="text" name="grouprecentevents" id="grouprecentevents-admin" value="<?php if($editflag){ echo $this->params->get('grouprecentevents', 6); }else { echo '6'; }?>"  size="5"/>
		</td>
	</tr>
	<tr class="detail_border"></tr>
		<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('GROUP NEW MEMBER NOTIFICATION');?>::<?php echo JText::_('GROUP NEW MEMBER NOTIFICATION TIPS');?>">
				<?php echo JText::_('GROUP NEW MEMBER NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="newmembernotification" id="newmembernotification-enable" value="1" <?php if($editflag){  echo ($this->params->get('newmembernotification', '1') == true ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?> />
				<label for="newmembernotification-enable" class="label lblradio"><?php echo JText::_('Enable');?></label>
			</div>
			<div>
				<input type="radio" name="newmembernotification" id="newmembernotification-disable" value="0" <?php if($editflag){  echo ($this->params->get('newmembernotification', '1') == false ) ? ' checked="checked"' : ''; }?>/>
				<label for="newmembernotification-disable" class="label lblradio"><?php echo JText::_('Disable');?></label>
			</div>			
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('GROUP JOIN REQUEST NOTIFICATION');?>::<?php echo JText::_('GROUP JOIN REQUEST NOTIFICATION TIPS');?>">
				<?php echo JText::_('GROUP JOIN REQUEST NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="joinrequestnotification" id="joinrequestnotification-enable" value="1" <?php if($editflag){ echo ($this->params->get('joinrequestnotification', '1') == true ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?> />
				<label for="joinrequestnotification-enable" class="label lblradio"><?php echo JText::_('Enable');?></label>
			</div>
			<div>
				<input type="radio" name="joinrequestnotification" id="joinrequestnotification-disable" value="0" <?php if($editflag){ echo ($this->params->get('joinrequestnotification', '1') == false ) ? ' checked="checked"' : ''; }?>/>
				<label for="joinrequestnotification-disable" class="label lblradio"><?php echo JText::_('Disable');?></label>
			</div>			
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<tr>
		<td class="key">
			<label class="label title jomTips" title="<?php echo JText::_('GROUP WALL POST NOTIFICATION');?>::<?php echo JText::_('GROUP WALL POST NOTIFICATION TIPS');?>">
				<?php echo JText::_('GROUP WALL POST NOTIFICATION'); ?>
			</label>
		</td>
		<td class="value">
			<div>
				<input type="radio" name="wallnotification" id="wallnotification-enable" value="1" <?php if($editflag){ echo ($this->params->get('wallnotification', '1') == true ) ? ' checked="checked"' : ''; }else { echo ' checked="checked"';}?>/>
				<label for="wallnotification-enable" class="label lblradio"><?php echo JText::_('Enable');?></label>
			</div>
			<div>
				<input type="radio" name="wallnotification" id="wallnotification-disable" value="0" <?php if($editflag){ echo ($this->params->get('wallnotification', '1') == false ) ? ' checked="checked"' : '';}?>/>
				<label for="wallnotification-disable" class="label lblradio"><?php echo JText::_('Disable');?></label>
			</div>			
		</td>
	</tr>
	<tr class="detail_border"></tr>
	<?php if($this->groupid) { ?>
	<tr>
		<td class="key">
			<label for="removeactivities" class="label title jomTips" title="<?php echo JText::_('REMOVE GROUP ACTIVITIES');?>::<?php echo JText::_('REMOVE GROUP ACTIVITIES TIPS');?>">
				<?php echo JText::_('REMOVE GROUP ACTIVITIES');?>
			</label>
		</td>
		<td class="value">
			<input type="checkbox" name="removeactivities" id="removeactivities" value="1" />
			<div class="small"><?php echo JText::_('BY CHECKING THIS OPTION EXISTING ACTIVITIES');?></div>
		</td>
	</tr>
	<?php } ?>
	
	</table>
</div>
<input type="hidden" name="groupid" value="<?php if($editflag) { echo $this->groupid; } else { echo 0; }?>" />
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="task" value="creategp" />
<input type="hidden" name="controller" value="groups" />
<!--<input type="submit" value="Create" class="edit" id="submit" name="submit" />
 --></form>
</div>
<?php 
} 
?>