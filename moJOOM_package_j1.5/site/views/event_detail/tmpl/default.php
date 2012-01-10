<?php 
/**
 * Event Detail View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<script type="text/javascript">
function inner_comment(id)
{
	if(document.getElementById('inner_comment_'+id).style.display == 'none')
	{
		document.getElementById('inner_comment_'+id).style.display = 'block';
	}
	else
	{
		document.getElementById('inner_comment_'+id).style.display = 'none';
	}
}
function inner_comment_none()
{
	document.getElementById('inner_comment_<?php echo $act->id;?>').style.display = 'none';
}
</script>

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
	$document->setTitle(JText::_('EVENT DETAIL TITLE'));
?>
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
		var strURL1="index2.php?option=com_mojoom&view=like&id="+id+"&uid="+uid+"&type="+type+"&item=1";
			var req = getXMLHTTP();
			if (req)
			{					
				req.onreadystatechange = function()
				 {						
					if (req.readyState == 4) 
					{	// only if "OK"								
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
			 <div id="header_title_events">
					<ul id="sub_menu">
                    		
                    	<li class="leftrndcrnr active"><span>&nbsp;</span></li>
						<li id="left"><a href="index.php?option=com_mojoom&controller=event&task=myevents" class="active"><?php echo JText::_('EVENT GROUP EVENT'); ?></a></li>
                        <?php if($data['group_id'] == "") { ?>
                        <li class="saperator"></li>
						<li id="mid"><a href="index.php?option=com_mojoom&controller=event&task=invite&event_id=<?php echo $this->Event->id; ?>"><?php echo JText::_('INVITE'); ?></a></li>
                        <?php } ?>
                        <li class="saperator"></li>
						<li id="right"><a href="index.php?option=com_mojoom&controller=event&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
                        <li class="rightrndcrnr"></li>
					</ul>
			</div>
            <?php if($this->Event->creator == $this->user_id) { ?><div id="forward">
            		
                    <a href="index.php?option=com_mojoom&controller=event&task=<?php if($data['group_id'] != "") { echo 'creategroupevent&group_id='.$data[group_id].''; } else { echo 'edit'; } ?>&event_id=<?php echo $this->Event->id; ?>"><input type="button" name="submit" id="submit" value="Edit" class="edit" /></a>
                    </div> 
			<?php } ?>            
		</div>        	
       <div class="componentcontent">
			<div class="groupscontainer">			
			<?php			
			if($this->Event){		?>
					<div class="event-top">
                    <!-- Event Top: Event Left -->
                    <div class="event-left">
                        <!-- Event Avatar -->
                        <div class="event-avatar" id="community-event-avatar">
                            <img border="0" alt="<?php echo $this->Event->title; ?>" width="69" height="69" src="<?php if($this->Event->thumb == "") { echo "./components/com_community/templates/blackout/images/event.png"; } else { echo "./components/com_community/templates/blackout/images/".$this->Event->thumb; } ?>" >                
                        </div>
                        <!-- Event Avatar -->
                        <!-- Event Top: App Like -->
                        <div class="jsApLike" id="jsApLike">
                            <span id="like-container">
                                <span class="like-snippet" id="like-events-1">                    
                                <a onclick="like('1','<?php echo $this->Event->id; ?>','<?php echo $this->user_id; ?>')" href="javascript:void(0);" <?php if($this->like === false) { ?> class="like_button" <?php } else { ?> class="like_button_active" <?php } ?> >Like</a>                        
                                <a onclick="like('2','<?php echo $this->Event->id; ?>','<?php echo $this->user_id; ?>');" href="javascript:void(0);" <?php if($this->dislike === false) { ?> class="dislike_button" <?php } else { ?>class="dislike_button_active" <?php } ?> >0</a>                    
                            </span>
                            </span>
                            <div class="clr"></div>
                        </div>
                        <!-- end: App Like -->	  
                    </div>
                      
                    <!-- Event Top: Event Left -->
                    <!-- Event Top: Event Main -->
                    <div class="event-main">
                        <!-- Event Approval -->
                        <div class="event-approval"></div>            
                        <!-- Event Information -->
                        <div class="event-info" id="community-event-info">
                            <div class="ctitle">
	                            <span id="title"><?php echo JText::_('EVENT INFO'); ?></span>
                                <?php if($this->Event->creator ==  $this->user_id) {  ?>
                                <span class="cowner"> ( You are the event owner ) </span>
                                <?php } ?>
                            </div>            
                            <div class="cparam event-category">
                                <div class="clabel"><?php echo JText::_('CATEGORY'); ?></div>
                                <div id="community-event-data-category" class="cdata">
                                    <?php echo $this->Event->cat; ?>
                                </div>
                            </div>             
                            <!-- Location info -->
                            <div class="cparam event-location">
                                <div class="clabel"><?php echo JText::_('LOCATION'); ?></div>
                                <div id="community-event-data-location" class="cdata"><?php echo $this->Event->location; ?></div>
                            </div>
                                                     
                        </div>
                        <!-- Event Information -->
                        <div style="clear: left;"></div>
                    </div>
                    <!-- start: Event Main -->
                    <div id="event_time">
                    	<div class="cparam event-created">
                            <div class="ctitle"><span id="title"><?php echo JText::_('TIME'); ?></span></div>
                            <div class="cdata small">
                            <span>From</span> : <?php echo JText::sprintf('FROM EVENT DETAIL' , JHTML::_('date', $this->Event->startdate, JText::_('%a, %d %b %Y %I:%M %P')) );?><br>
                            <span>Until</span> : <?php echo JText::sprintf('UNTIL EVENT DETAIL' , JHTML::_('date', $this->Event->enddate, JText::_('%a, %d %b %Y %I:%M %P')) );?>    									
                            </div>
                        </div>
                        <div class="cparam event-owner">
                            <div class="clabel"><?php echo JText::_('CREATOR'); ?></div>
                            <div class="cdata">
                                <a href="index.php?option=com_mojoom&view=mojoom&user_id=<?php echo $this->Event->creator; ?>"><?php echo $this->Event->name; ?></a>
                            </div>
                        </div>            
                        <!-- Number of tickets -->
                        <div class="cparam event-tickets">
                            <div class="clabel"><?php echo JText::_('SEAT AVAIL'); ?></div>
                            <div class="cdata"><?php echo ($this->Event->ticket - $this->Event->confirmedcount);  ?> ( <?php echo $this->Event->ticket; ?> total )  </div>
                        </div>
                    </div>     
                    <div class="group-desc">
						<div class="ctitle"><span id="title"><?php echo JText::_('DESC'); ?></span></div>
						<?php echo $this->Event->description; ?>
                    </div>
                    <?php if($data['group_id'] == "") { ?>
                    <div class="groupActions1">
                        <span class="icon-discuss" style="margin-right: 5px; float:left;">
                            <a href="index.php?option=com_mojoom&controller=event&task=invite&event_id=<?php echo $this->Event->id; ?>"><input type="button" class="invite" value="Invite" /></a>				
                        </span>	                       
                    </div>
                    <?php } ?>
                    <?php if($this->Event->creator ==  $this->user_id) { ?>
                    <div class="groupActions1">
                        <span class="icon-discuss" style="margin-right: 5px; float:left;">
                            <a href="index.php?option=com_mojoom&controller=event&task=remove&event_id=<?php echo $this->Event->id; ?>" onclick="return confirm('Are you sure you want to delete this event?')"><input type="button" class="invite" value="Delete" /></a>				
                        </span>	                       
                    </div>
                    <?php } ?>
                    
                    <?php if($data['group_id'] != "") { 
							if($this->Event->startdate > date('Y-m-d')) {
					
					?>
                    <div class="groupActions">
                        <div class="ctitle"><span id="title"><?php echo JText::_('YOUR RESPONSE'); ?></span></div>
                        <p><?php echo JText::_('ATTENDING EVENT'); ?></p>
                        <form name="event-invite" id="event-status" action="index.php?option=com_mojoom&amp;controller=event&amp;task=updatestatus" method="post">
                        <div>
                            <input type="radio" name="status" id="status1" value="1" <?php if($this->Response == 1) { ?> checked="checked" <?php } ?> >
                            <label for="status1"><?php echo JText::_('YES'); ?></label>
                            <input type="radio" name="status" id="status2" value="2" <?php if($this->Response == 2) { ?> checked="checked" <?php } ?> >
                            <label for="status2"><?php echo JText::_('NO'); ?></label>
                            <input type="radio" name="status" id="status3" value="3" <?php if($this->Response == 3) { ?> checked="checked" <?php } ?> >
                            <label for="status3"><?php echo JText::_('MAY BE'); ?></label>
                        </div>  
                        <input type="hidden" name="groupid" value="<?php echo $data['group_id']; ?>"  />
                        <input type="hidden" name="eventid" value="<?php echo $data['event_id']; ?>"  />
                        <input type="hidden" name="memberid" value="<?php echo $userid; ?>"  />
                        <input type="hidden" name="old_status" value="<?php echo $this->Response; ?>"  />
	                    <div style="margin:10px;" >
                        <input type="submit" value="Send Response" class="button" >
                        </div>
                        </form>
                    </div>
                    <?php } 
					
					}
					?>                             
    		
            </div>
            
            
            <!---------------------- event wall ------------------------->
            <div class="event_wall_heading"><?php echo JText::_('WALLHEAD'); ?></div>
            <div class="componentcontent">
                <div class="event_wall">
                    <div class="app-box-content_event_wall">
                     <?php  
				 
					 if($this->guestmember == $this->user_id) { ?>
                        <form action="index.php" name="commentadd" method="post">
                        <div class="app-box">
                            <div id="wallForm">
                                <div class="cavatar">
                                    <a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $this->user_id);?>">
                                    <?php
                                    $thumb = $this->getAvatar($this->user_id);
                                    if($thumb != '') 
                                    {
                                    ?>
                                    <img class="avatar" src="<?php echo $thumb; ?>" width="40" border="0" alt=""/>
                                    <?php } else { ?>
                                    <img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="40" border="0" alt=""/>
                                    <?php 
                                    } 
                                    ?>
                                    </a>
                                </div>
                                <div class="ccontent-avatar">
                                    <textarea id="wall-message" name="message" class="inputbox" rows="" cols=""></textarea>
                                    <div class="wall-respond-area"><input type="submit" name="submit" value="Add Comment" /></div>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="eventid" value="<?php echo $data['event_id']; ?>"  />
                        <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" />
                        <input type="hidden" name="option" value="com_mojoom" />
                        <input type="hidden" name="task" value="eventcommentadd" />
                        <input type="hidden" name="controller" value="event" />
                        </form>
                	<?php 
					} 
				 ?>
                 
                <?php foreach($this->activities as $act): ?>
                <?php if($act->type !='events'): ?>
                <?php else: 
                    $actor = JFactory::getUser($this->user_id);
                     ?>
                     <div id="wallContent">
                        <div id="wall_<?php echo $act->post_by; ?>" class="wallComments">
                        <div class="main_comment">
                            <div class="cavatar">
                                <?php if(!empty($act->post_by)) { 
                                    $url	= 'index.php?option=com_mojomm&view=mojoom&user_id='.$act->post_by;	?>
                                    <a href="<?php echo $url; ?>">
                                    <?php
                                     $thumb = $this->getAvatar($act->post_by);
                                     if($thumb != '') {
                                    ?>
                                    <img class="avatar" src="<?php echo $thumb; ?>" width="40" border="0" alt=""/>
                                <?php } else { ?>
                                    <img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="40" border="0" alt=""/>
                                <?php } 
                                }
                                ?>
                                </a>
                            </div>
                                <div class="createby">
                                    <?php $url = 'index.php?option=com_mojoom&view=mojoom&user_id='.$act->post_by; ?>
                                    <a href="<?php echo $url;?>"><?php echo $act->name;?></a>,
                                </div>
                                <div class="maincontent">
                                        <span id="wall-message-<?php echo $act->post_by;?>"><?php $strip_comm = $this->stripCommentData($act->comment); echo $strip_comm;?></span>
                                </div>
                                </div>
                            <div class="ccontent-avatar">
                                 <div class="show_inner_Comment1">
                                 <?php
                                 //print_r($act->comment);
                                 $i = 0;
                                 $comm = $this->getCommentsData($act->comment);
                                // print_r($comm);
                                 foreach($comm as $com_comment)
                                 {
                                 ?>
                                   <div class="show_inner_Comment <?php echo $i;?>">
                                    <div class="cavatar_inner">
                                        <?php if(!empty($com_comment->creator)) { 
                                            $url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$com_comment->creator;	?>
                                            <a href="<?php echo $url; ?>">
                                            <?php
                                             $thumb = $this->getAvatar($com_comment->creator);
                                             if($thumb != '') {
                                            ?>
                                            <img class="avatar" src="<?php echo $thumb; ?>" width="40" border="0" alt=""/>
                                        <?php } else { ?>
                                            <img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="40" border="0" alt=""/>
                                        <?php } 
                                        }
                                        ?>
                                        </a>
                                    </div>
                                    <div class="createby_inner">
                                    <?php
                                     $actor_name = JFactory::getUser($com_comment->creator);
                                     $url = 'index.php?option=com_mojoom&view=mojoom&user_id='.$com_comment->creator; ?>
                                    <a href="<?php echo $url;?>"><?php echo $actor_name->name;?></a>, on  
                                    <span class="createdate_inner">
                                    <?php //echo JHTML::_('date', $com_comment->date, JText::_('DATE_FORMAT_LC2')); ?>
                                     <?php echo JHTML::_('date', $com_comment->date, JText::_('%a, %d %b %Y %I:%M %P')) ;?>
                                    </span>
                                    </div>
                                    <div class="content_inner">
                                            <span id="wall-message-<?php echo $com_comment->creator; ?>"><?php  echo $com_comment->text; ?></span>
                                    </div>
                                 </div>                           
                                 <?php 
                                 $i++;}
                                 ?>
                                 </div>
                                 
                                 <div id="inner_comment_<?php echo $act->id;?>" class="inner_comment_<?php echo $act->id; ?>" style="display:none;">
                                    <form action="index.php" name="adminForm" method="post">
                                    <div class="app-box">
                                      
                                        <div id="wallForm">
                                            <div class="ccontent-avatar">
                                                <textarea id="wall-message" name="message" class="inputbox" rows="" cols=""></textarea>
                                                <div class="wall-respond-area"><input type="submit" name="submit" value="Add" /><input type="button" name="cancel" value="cancel" onclick="inner_comment(<?php echo $act->id; ?>)" /></div>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="eventid" value="<?php echo $data['event_id']; ?>"  />
                                    <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" />
                                    <input type="hidden" name="a_id" value="<?php echo $act->id; ?>" />
                                    <input type="hidden" name="option" value="com_mojoom" />
                                    <input type="hidden" name="task" value="eventcommentadd_inner" />
                                    <input type="hidden" name="controller" value="event" />
                                    </form>
                                </div>
            
                                <?php  if($this->guestmember == $this->user_id)
                                { ?> 
                                <div class="inner_comment">
                                    <a href="JavaScript:void(0);"  onclick="inner_comment(<?php echo $act->id; ?>);" name="comment" > <?php echo JText::_('COMMENT'); ?></a>
                                </div>
                                <?php } ?>
                                <div class="date">
                                    <span class="createdate" >
                                    <?php echo JHTML::_('date', $act->date, JText::_('%a, %d %b %Y %I:%M %P')) ;?>
                                    <?php //echo JHTML::_('date', $act->date, JText::_('DATE_FORMAT_LC2')); ?></span>
                                    <span class="remove_wall">
                                    <?php if($this->guestmember == $this->user_id)
                                    { ?> 
                                    <?php
                                    $url1 ="index.php?option=com_mojoom&controller=event&task=removeeventwallcomment&id=".$act->id."&uid=".$act->post_by."&eid=".$act->contentid."";
                                    ?>
                                    <a href="<?php echo $url1;?>" ><img src="components/com_mojoom/images/cancel.png" alt="cancel" align="middle" style="padding-bottom:4px" ></a>
                                    <?php } ?>
                                    </span>
                                </div>
                                                    
                            </div>
                            <div class="clr">&nbsp;</div>
                            
                        </div>
                </div>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php if( $exclusions !== false && $showMoreActivity) { ?>
                <div class="joms-newsfeed-more" id="activity-more">
                    <a class="more-activity-text" href="javascript:void(0);" onclick="joms.activities.more();"><?php echo JText::_('CC MORE');?></a>
                    <div class="loading"></div>
                </div>
                <input type="hidden" id="activity-exclusions" value="<?php echo $exclusions;?>" />
            <?php } ?>
                </div>
                </div>
            </div>
            
            <!----------------------------------------------------------->
            
		<?php		
	}
	else
	{
		?>
		<div class="componentcontent">
			<div class="groupscontainer">
				<?php echo JText::_('NO EVENT DETAIL FOUND'); ?>
			</div>
		</div>
		<?php
	}
}
?>