<?php
/** 
 * Wall1 View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://?www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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
	$document->setTitle('Wall');
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
    <div id="header_title"><?php echo JFactory::getUser($this->user_id)->name;?>'s&nbsp;Wall</div>
	<div id="refresh"><input type="button" value="" class="refresh" onclick="javascript:location.reload();" /></div>
</div>
<div class="componentcontent">
	<div class="app-box-content">
<?php ///////////////////////////////////////// Form /////////////////////////////////// 
	 // 26-12-2012 M change.
	// create form for wall posting.
?>
<form action="index.php" method="post" name="Form" id="Form">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'WALL POSTING' ); ?></legend>

		<table class="table">
		<tr>
			<td width="100" align="right" class="key" valign="top" >
				<label for="greeting">
					<?php echo JText::_( 'POSTING HERE' ); ?>:
				</label>
			</td>
			<td><textarea name="title" id="title"  width="100%" cols="40" rows="3"></textarea>
            	
				<!--<input class="text_area" type="text" name="title" id="title" size="100" rows="5" maxlength="250" />-->
                <input type="submit" name="submit" value="Add Comment" />
			</td>
		</tr>
       
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_mojoom" />
<!--<input type="hidden" name="id" value="<?php echo $this->hello->id; ?>" />-->
<input type="hidden" name="user_id" value="<?php echo $this->user_id;?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="wall" />
</form>
<?php //////////////////////////////////////////////// End Form ///////////////////////////////////////////////////// ?>
    <?php if($this->activities->data) { ?>
	<?php foreach($this->activities->data as $act): ?>
	<?php if($act->type =='title'): ?>
		<?php /*if($config->get('activitydateformat') == COMMUNITY_DATE_FIXED ){ ?>
			<div class="ctitle"><?php echo $act->title; ?></div>
		<?php }*/ ?>
	<?php else: 
		$actor = JFactory::getUser($act->actor);
		 ?>
         
		<div id="profile-newsfeed-item<?php echo $act->id; ?>" class="joms-newsfeed-item">
	    	<!--NEWS FEED AVATAR-->
			<div class="newsfeed-avatar">
			
				<?php if(!empty($actor->id)) { 
					$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$actor->id;	?>
					<a href="<?php echo $url; ?>">
					<?php
					 $thumb = $this->getAvatar($actor->id);
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
	    	<!--NEWS FEED AVATAR-->
	
			<!--NEWS FEED FAVICON-->
			<!--<div class="newsfeed-favicon"><img src="<?php //echo $act->favicon; ?>" class="icon" alt="<?php //echo $act->app;?>" /></div> -->
			<!--NEWS FEED FAVICON-->
			
			<!--NEWS FEED CONTENT-->
       
	    	<div class="newsfeed-content">
				<div class="newsfeed-content-top"><?php echo $act->title; ?></div>
				<?php if(!empty($act->content) && $showMore ){ ?>
					<?php if( $config->getBool('showactivitycontent')) { ?>
						<div id="<?php echo $idprefix; ?>profile-newsfeed-item-content-<?php echo $act->id;?>" class="newsfeed-content-hidden" style="display:block"><?php echo $act->content; ?></div>
					<?php } else { ?>
						<div id="<?php echo $idprefix; ?>profile-newsfeed-item-content-<?php echo $act->id;?>" class="small profile-newsfeed-item-action" style="display:block">
							<a href="javascript:void(0);" id="newsfeed-content-<?php echo $act->id;?>" onclick="joms.activities.getContent('<?php echo $act->id;?>');"><?php echo JText::_('CC MORE');?></a>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
			<!--NEWS FEED CONTENT-->
			<!--NEWS FEED DATE-->
			<div class="newsfeed-date small"><?php echo $act->created; ?></div>
			<!--NEWS FEED DATE-->
			
		</div>
	<?php endif; ?>
<?php endforeach; ?>
<?php }
	  else
	  {
	  	echo JText::_('NO RECENT ACTIVITY FOUND'); 
	  }
 ?>
<?php if( $exclusions !== false && $showMoreActivity) { ?>
	<div class="joms-newsfeed-more" id="activity-more">
		<a class="more-activity-text" href="javascript:void(0);" onclick="joms.activities.more();"><?php echo JText::_('CC MORE');?></a>
		<div class="loading"></div>
	</div>
	<input type="hidden" id="activity-exclusions" value="<?php echo $exclusions;?>" />
<?php } ?>
	</div>
</div>
<?php 
} 
?>