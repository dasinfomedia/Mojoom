<?php
/** 
 * Group Wall View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0");  ?>
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
$data = JRequest::get( 'post' );
$user =& JFactory::getUser();
require_once (JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'pc_includes'.DS.'JSON.php');
if($user->guest) 
{
?>
	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('GP WALL TITLE'));
	

?> 
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
    <div id="header_title"><?php echo JText::_('GP WALL HEAD'); ?></div>
	<div id="refresh"><input type="button" value="" class="refresh" onclick="javascript:location.reload();" /></div>
</div>

			

<div class="componentcontent">
	<div class="group_wall">
		<div class="app-box-content">
         <?php if($this->ismember != 0)
					 { ?>
    		<form action="index.php" name="commentadd" method="post">
            <div class="app-box">
              
                <div id="wallForm">
                    <div class="cavatar">
                      
                        
                        <a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $this->my->id );?>">
                        <?php
						$thumb = $this->getAvatar($this->my->id);
						if($thumb != '') 
						{
						?>
						<img class="avatar" src="<?php echo $thumb; ?>" width="40" border="0" alt=""/>
						<?php } else { ?>
						<img class="avatar" src="components/com_community/templates/blackout/images/default_thumb.png" width="40" border="0" alt=""/>
						<?php 
						} 
						?>
                       <!-- <img class="avatar" alt="<" src="<?php //echo $this->my->getThumbAvatar()?>">-->
                        </a>
                        
                    </div>
                    <div class="ccontent-avatar">
                        <textarea id="wall-message" name="message" class="inputbox" rows="" cols=""></textarea>
                        <div class="wall-respond-area"><input type="submit" name="submit" value="Add Comment" /></div>
                    </div>
            		<div style="clear:both;"></div>
                </div>
            </div>
            <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>" />
            <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>" />
            <input type="hidden" name="option" value="com_mojoom" />
            <input type="hidden" name="task" value="groupcommentadd" />
            <input type="hidden" name="controller" value="groups" />
            </form>
    <?php } ?>
     
	<?php foreach($this->activities as $act): ?>
	<?php if($act->type !='groups'): ?>
	<?php else: 
		$actor = JFactory::getUser($act->post_by);
		 ?>
         <div id="wallContent">
			<!--========================== -->
			<div id="wall_<?php echo $act->post_by; ?>" class="wallComments">
            <div class="main_comment">
    			<div class="cavatar">
					<?php if(!empty($act->post_by)) { 
						$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$act->post_by;	?>
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
                        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>" />
                        <input type="hidden" name="a_id" value="<?php echo $act->id; ?>" />
                        <input type="hidden" name="option" value="com_mojoom" />
                        <input type="hidden" name="task" value="groupcommentadd_inner" />
                        <input type="hidden" name="controller" value="groups" />
                        </form>
                    </div>

                     <?php if($this->ismember != 0)
					 { ?> 
                    <div class="inner_comment">
                		<a href="JavaScript:void(0);"  onclick="inner_comment(<?php echo $act->id; ?>);" name="comment" > <?php echo JText::_('Comment'); ?></a>
                	</div>
                    <?php } ?>
                    <div class="date">
      					<span class="createdate" >
						<?php echo JHTML::_('date', $act->date, JText::_('%a, %d %b %Y %I:%M %P')) ;?>
                        <?php //echo JHTML::_('date', $act->date, JText::_('DATE_FORMAT_LC2')); ?></span>
                        <span class="remove_wall">
                        <?php if($this->isGroupAdmin != 0)
					 	{ ?> 
                        
                        <?php
						$url1 ="index.php?option=com_mojoom&controller=groups&task=removegroupwallcomment&id=".$act->id."&uid=".$act->post_by."&gid=".$act->contentid."";
						?>
                		<a href="<?php echo $url1;?>" ><img src="components/com_mojoom/images/cancel.png" alt="cancel" align="middle" style="padding-bottom:4px" ></a>
                        <?php } ?>
                		</span>
  					</div>
                                        
				</div>
				<div class="clr">&nbsp;</div>
                
			</div>
			<!--========================== -->
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
<?php 
} 
?>
