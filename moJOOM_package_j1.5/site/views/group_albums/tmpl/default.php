<?php 
/**
 * Group Alumb View for Mojoom Component
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
	$document->setTitle(JText::_('GAL GA TITLE'));
	jimport( 'joomla.utilities.date' );
?> 
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
    <div id="header_title"><?php echo JText::_('GROUP ALBUM'); ?></div>
</div>
<div class="componentcontent">
	<div class="albums">

<?php

	if( $this->group_albums )
	{   
		$i	= 0;
        foreach($this->group_albums as $album)
		{
?>
			<div class="album">
				<div class="album jomTips tipFullWidth" title="<?php echo $album->name;?>::<?php echo $album->description;?>">
			    	<div class="album-cover">
						<?php if($album->thumbnail != ''){ ?>
						<a class="album-cover-link" href="index.php?option=com_mojoom&view=group_photos&album_id=<?php echo $album->id;?>&user_id=<?php echo $album->creator; ?>"><img src="<?php echo $album->thumbnail; ?>" alt="<?php echo $album->name;?>" class="avatar"/></a>
						<?php } else {?>
						<a class="album-cover-link" href="<?php //echo $album->link; ?>#">
							<img src="./components/com_community/assets/photo_thumb.png" class="avatar" alt="img"/>
						</a>
						<?php } ?>
						<div class="album-name"><a href="<?php //echo $album->link; ?>#"><?php echo $album->name; ?></a>
						&nbsp;<?php echo "( ".$album->count; ?>&nbsp;)
						</div>
        			</div>
   
				</div> 
			  </div>
	<?php 
		}
}
else{
echo JText::_('NO ALBUM CREATED');}
?>
</div>
<?php 
} 
?>
</div>