<?php 
/** 
 * Group Detail View for Mojoom Component
 * 
 * @package    Mojoom
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
	$document->setTitle(JText::_('GROUP DETAIL TITLE'));
	JHTML::_('behavior.mootools');
	JHTML::_('behavior.modal', 'a.join');
	JHTML::_('behavior.modal', 'a.deletegrp');
	JHTML::_('behavior.modal', 'a.leavegrp');
	

?>
<script type="text/javascript">

window.addEvent('domready', function() {
// Decorate the login windows to use a modal.
$ES('a.join').each(function(a){
a.setProperty('rel', '{size: {x: 245, y: 100}, ajaxOptions: {method: "get"}}');
if (a.getProperty('href').contains('?')) {
	a.setProperty('href', a.getProperty('href')+'&tmpl=component');
} else {
	a.setProperty('href', a.getProperty('href')+'?tmpl=component');
}
});
});

window.addEvent('domready', function() {
// Decorate the login windows to use a modal.
$ES('a.deletegrp').each(function(a){
a.setProperty('rel', '{size: {x: 245, y: 150}, ajaxOptions: {method: "get"}}');
if (a.getProperty('href').contains('?')) {
	a.setProperty('href', a.getProperty('href')+'&tmpl=component');
} else {
	a.setProperty('href', a.getProperty('href')+'?tmpl=component');
}
});
});

window.addEvent('domready', function() {
// Decorate the login windows to use a modal.
$ES('a.leavegrp').each(function(a){
a.setProperty('rel', '{size: {x: 245, y: 150}, ajaxOptions: {method: "get"}}');
if (a.getProperty('href').contains('?')) {
	a.setProperty('href', a.getProperty('href')+'&tmpl=component');
} else {
	a.setProperty('href', a.getProperty('href')+'?tmpl=component');
}
});
});

</script>
<script type="text/javascript">
function disp() {
window.scrollTo(0,0);
}
</script>
<script language="javascript" type="text/javascript">
function getXMLHTTP() 
{ //fuction to return the xml http object
	var ajax_request;   
    if (  window.ActiveXObject  )  
	{
        var mSoftVersions = [
            'MSXML2.DOMDocument.5.0',
            'MSXML2.DOMDocument.4.0',
            'MSXML2.DOMDocument.3.0',
            'MSXML2.DOMDocument.2.0',
            'MSXML2.DOMDocument',
            'Microsoft.XmlDom',
            'Msxml2.XMLHTTP',
            'Microsoft.XMLHTTP'
        ];       
        for (  i=0; i<mSoftVersions.length; i++  )  
		{
            try 
			{
                ajax_request = new ActiveXObject (  mSoftVersions[i]  );
            }  
			catch (  e  )
			  {    }
        }
    }  
	else if (  !ajax_request && typeof XMLHttpRequest != 'undefined'  )  
	{
        try
		{
            ajax_request = new XMLHttpRequest;
        }
		catch (  e  )  {    }
    }  
	else if (  !ajax_request && window.createRequest  )  
	{
        try {
            ajax_request = window.createRequest;
        }  
		catch (  e  )  {    }
    }  
	else  
	{
        ajax_request = false;
    }   
    return ajax_request;
}
	
	function like(type,id,uid)
	{			
		var strURL1="index2.php?option=com_mojoom&view=like&id="+id+"&uid="+uid+"&type="+type+"&item=2";
			var req = getXMLHTTP();
			if (req)
			{					
				req.onreadystatechange = function()
				 {						
					if (req.readyState == 4) 
					{							
						if(req.status == 200)
						{														   
						   document.getElementById('jsApLike').innerHTML = req.responseText;								
						}
						else
						{
							alert("There was a problem loading the page. Please refresh.\n" + req.statusText);
						}
					}				
				}							
				req.open("GET", strURL1,true);
				req.send(null);				
			}									 
	}
</script> 

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
	<?php if($this->ismine) { ?>
	<div id="forward"  class="android"><a href="index.php?option=com_mojoom&controller=groups&task=editgroup&group_id=<?php echo $this->group->id;?>"><input type="button" class="edit" value="Edit"></a></div> 
	<?php } ?>
</div>
<div class="componentcontent">
	<div class="groupscontainer">
		<div class="group">
			<!-- begin: .cLayout -->
			<div class="cLayout clrfix">
				<!-- begin: .cMain -->
			    <div class="cMain clrfix">
					<?php if($this->isinvited)
					{ ?>
						<div id="groups-invite-<?php echo $this->group->id; ?>" class="com-invitation-msg">
							<div class="com-invite-info">
								<?php echo JText::sprintf( 'INVITE TO JOIN GROUP', $this->joinHTML); // joinHTML = $join in view.html(remaining)?><br />
								<?php 
								if($this->friendscount == 0 || $this->friendscount == 1)
									echo JText::sprintf( 'FRND IS MEM OF GROUP' , $this->friendscount ); 
								else
								echo JText::sprintf( 'FRND ARE MEM' , $this->friendscount );
								?>
							</div>
							<div class="com-invite-action">
							    <a href="" onclick="">
					    			<?php echo JText::_('ACCEPT GP'); ?>
				   				</a>
				    			<?php echo JText::_('OR GP'); ?>
				    			<a href="" onclick="">
					    			<?php echo JText::_('REJECT GP'); ?>
							    </a>
							</div>
						</div>
					<?php } ?>
					<div class="group-top">
						<!-- Group Top: Group Left -->
						<div class="group-left">
							<!-- Group Avatar -->
							<div id="community-group-avatar" class="group-avatar">
							<?php
								$avatar = '';
								if($this->group->avatar == '')
									$avatar = 'components/com_community/templates/blackout/images/group_thumb.png';
								else
									$avatar = $this->group->avatar;
							?>
								<img src="<?php echo $avatar; ?>" border="0" alt="<?php echo $this->group->name;?>" />

								<!-- Group Buddy -->
								<?php if( $this->isadmin && !$this->ismine ) { ?>
										<div class="cadmin tag-this" title="<?php echo JText::_('GROUP ADMIN GP'); ?>">
												<?php echo JText::_('GROUP OWNER GP'); ?>
										</div>

								<?php } else if( $this->ismine ) { ?>
										
								<?php } ?>
								 <!-- Group Buddy -->
							</div>
							<!-- Group Avatar -->
							<!-- Event Top: App Like -->
							<div class="jsApLike" id="jsApLike">
								<span id="like-container">
									<span class="like-snippet" id="like-events-1">                    
                                		<a onclick="like('1','<?php echo $this->group->id; ?>','<?php echo JFactory::getUser()->id; ?>')" href="javascript:void(0);" <?php if($this->like === false) { ?> class="like_button" <?php } else { ?> class="like_button_active" <?php } ?> >Like</a>                        
                                		<a onclick="like('2','<?php echo $this->group->id; ?>','<?php echo JFactory::getUser()->id; ?>');" href="javascript:void(0);" <?php if($this->dislike === false) { ?> class="dislike_button" <?php } else { ?>class="dislike_button_active" <?php } ?> >0</a>                    
                            		</span>
                            	</span>
								<div class="clr"></div>
							</div>
							<!-- end: App Like -->   
						</div>
						<!-- Group Top: Group Left -->
						<!-- Group Top: Group Main -->
						<div class="group-main">
							<!-- Group Approval -->
							<div class="group-approval">
								
								<?php if( $this->waitingapproval ) { ?>
								<div class="info">
									<span class="icon-waitingapproval"><?php echo JText::_('YOUR APP IS AWAITING APPROVAL'); ?> </span>
								</div>
								<?php }?>
							</div>
							<!-- Group Approval -->
							<!-- Group Information -->
							<div id="community-group-info" class="group-info">
							
								<div class="ctitle">
									<span id="title">
									<?php echo JText::_('GROUP INFO GP');?>
									</span>
									<!-- Group Owner & Admin -->
									<?php if( $this->isadmin && !$this->ismine ) { ?>
											<span class="cadmin"> <?php echo JText::_('GROUP ADMIN GP'); ?> </span>
									<?php } else if( $this->ismine ) { ?>
											<span class="cowner"> <?php echo JText::_('GROUP OWNER GP'); ?> </span>
									<?php } ?>
									<!-- Group Owner & Admin -->
								</div>
								
								<div class="cparam group-category">
									<div class="clabel"><?php echo JText::_('CATEGORY GP'); ?>:</div>
									<div class="cdata" id="community-group-data-category">
										<a href="<?php echo JRoute::_('index.php?option=com_mojoom&controller=groups&task=mygroups&category_id=' . $this->group->categoryid);?>"><?php echo JText::_( $this->category[0]->name ); ?></a>
									</div>
								</div>

								<div class="cparam group-name">
									<div class="clabel"><?php echo JText::_('NAME GP');?>:</div>
										<div class="cdata" id="community-group-data-name">
											<?php echo $this->group->name; ?>
											<?php
											if($this->group->approvals == 1)
											{
												if( $this->ismine )
												{
													echo '<a href=""> ' . '('. JText::_('PRIVATE GP') . ')' . '</a>';										}
												else
												{
													echo '('. JText::_('PRIVATE GP') . ')';
												}							
											}
											?>									
										</div>
									</div>
									<div class="cparam group-created">
										<div class="clabel"><?php echo JText::_('CREATED GP');?>:</div>
										<div class="cdata"><?php echo JHTML::_('date', $this->group->created, JText::_('DATE_FORMAT_LC3')); ?></div>
									</div>            

								<div class="cparam group-owner">
									<div class="clabel">
										<?php echo JText::_('CREATOR GP');?>:
									</div>
									<div class="cdata">
										<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $this->group->ownerid );?>"><?php echo JFactory::getUser($this->group->ownerid)->username; ?>
										</a>
									</div>
								</div>
						</div>
						<!-- Group Information -->
						<div style="clear: left;"></div>
						
						</div>
						<!-- Group Top: Group Main -->
					
						<!-- Event Top: Event Description -->
						<div class="group-desc">
							<div class="ctitle"><span id="title"><?php echo JText::_('DESC GP');?></span></div>
							<?php echo $this->group->description; ?>
						</div>
						<!-- Event Top: Event Description -->
					
						<!-- detail page action container -->
						<div class="groupactions">
							<ul class="group-menus cResetList clrfix">
								<?php if( (!$this->ismember) && !($this->waitingapproval) ) { ?>
						    	<!-- Join Group -->
							   		<li id="grpjoin">
															
										<a href="index.php?option=com_mojoom&controller=groups&task=joingroup&group_id=<?php echo $this->group->id; ?>" class="join" ><input type="button" value="Join" class="action"  ></a>
									</li>
								<?php } ?>
								
								<?php if( ($this->isadmin) || ($this->ismine) || ($this->ismember) ) { ?>
									<!-- Invite Friend -->
									<li id="grpinvite">
										<a href="index.php?option=com_mojoom&controller=groups&task=invitefriends&group_id=<?php echo $this->group->id; ?>&user_id=<?php echo JFactory::getUser()->id; ?>" ><input type="button" value="Invite" class="action"  ></a>
									</li>
								<?php } ?>
								
					   		 		<li id="grpmembers">
						    			<a class="group-discussion" href="index.php?option=com_mojoom&controller=groups&task=viewmembers&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Members" class="action big"  ></a>
									</li>

								
								<?php if( ($this->ismember) && !($this->waitingapproval)): ?>

					   			 <!-- Discussions links-->

					   		 		<li id="grpdiscuss">

						    			<a class="group-discussion" href="index.php?option=com_mojoom&controller=groups&task=groupdiscussion&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Discussions" class="action bigger"  ></a>
									</li>

								<?php endif; ?>
								
									<!-- Invite Friend -->
									<li id="grpevent">
											<a href="index.php?option=com_mojoom&controller=event&task=groupevent&group_id=<?php echo $this->group->id; ?>" ><input type="button" value="Group Events" class="action bigger"  ></a>
									</li>
								<?php //} ?>
								
								<?php if( $this->isadmin ): ?>

									<!-- Add Bulletin -->
			
									<li id="grpnews">
			
										<a class="group-add-bulletin" href="index.php?option=com_mojoom&controller=groups&task=groupbulletin&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Bulletins" class="action biggest"  ></a>
			
									</li>
			
							<?php endif; ?>
                            
                            <?php if( $this->ismember ): ?>
                            	<li id="grpphotos">
									<a class="group-albums" href="index.php?option=com_mojoom&controller=groups&task=groupalbums&user_id=<?php echo $user->id; ?>&group_id=<?php echo $this->group->id; ?>">
                                        <input type="button" value="Albums" class="action album"  ></a>
								</li>
							<?php endif; ?>
                            
                            <?php if( $this->ismember ): ?>
                            	<li id="grpvideos">
									<a class="group-video" href="index.php?option=com_mojoom&controller=groups&task=groupvideo&user_id=<?php echo $user->id; ?>&group_id=<?php echo $this->group->id; ?>">
                                        <input type="button" value="Videos" class="action video"  ></a>
								</li>
							<?php endif; ?>
                            
                            	<li id="grpwall">
									<a class="group-wall" href="index.php?option=com_mojoom&controller=groups&task=groupwall&user_id=<?php echo $user->id; ?>&group_id=<?php echo $this->group->id; ?>">
                                        <input type="button" value="Wall" class="action wall"  ></a>
								</li>
                                
                                <?php if( ($this->isadmin) || ($this->ismine) || ($this->isCommunityAdmin) ) { ?>
                                 <li id="grpdelete">
                                <a class="deletegrp" href="index.php?option=com_mojoom&controller=groups&task=groupdelete&user_id=<?php echo $user->id; ?>&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Delete Group" class="action delete"  ></a>
                                </li>
								<?php } else if(  $this->ismember) { ?>
                                 <li id="grpleave">
											<a class="leavegrp" href="index.php?option=com_mojoom&controller=groups&task=groupleave&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Leave Group" class="action leave"  ></a>
                                            </li>
								<?php } ?>
                               <?php if( ($this->isCommunityAdmin) && ( $this->ismember)) { ?>
											<li id="grpleave">
                                            <a class="leavegrp" href="index.php?option=com_mojoom&controller=groups&task=groupleave&group_id=<?php echo $this->group->id; ?>"><input type="button" value="Leave Group" class="action leave"  ></a>
                                            </li>
                                 <?php } ?>                            
							</ul>
						</div>
						<!-- detail page action container -->
					</div> <!-- group top -->
				</div>
				<!-- end: .cMain -->
			</div>
			<!-- end: .cLayout -->
		</div>
	</div>
</div>
<?php
}
?>