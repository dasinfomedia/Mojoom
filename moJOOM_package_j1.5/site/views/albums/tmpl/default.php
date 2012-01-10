<?php 
/**
 * Alubms View for Mojoom Component
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
	$document->setTitle(JText::_('ALBUM TITLE'));
	jimport( 'joomla.utilities.date' );
?> 
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back">
    </div>
    <div id="header_title">Albums</div>
</div>
<div class="componentcontent">
	<div class="albums">

<?php

echo $result;

	if( $this->albums )
	{   
		$i	= 0;
		?>
        <script language="javascript" type="text/javascript">
		function filteralbum()
		{
		
			var selobj = document.getElementById('user_id');
			var index = selobj.selectedIndex;
			userid = selobj.options[index].value;
			window.location.href = "index.php?option=com_mojoom&view=albums&user_id=" + userid;
			
		}
		</script>
        <?php $uid = JFactory::getUser()->id; if($uid == $this->user_id || $this->user_id == 0 ) { ?>
        
		<?php echo JText::_('ALBUM BY');$uid = JFactory::getUser()->id;?>&nbsp; 
			<select name="user_id" id="user_id" onchange="filteralbum();">
						<option value="0" <?php if($this->user_id == 0) { ?> selected="selected" <?php } ?> >All Albums</option>
						<option value="<?php echo $uid;?>" <?php if($uid == $this->user_id) { ?> selected="selected" <?php } ?>>My Albums</option>
			</select>
			<br /><br /> 
       <?php } ?>
		<?php
        foreach($this->albums as $album)
		{
		?>
			<div class="album">
				<div class="album jomTips tipFullWidth" title="<?php echo $album->name;?>::<?php echo $album->description;?>">
			    	<div class="album-cover">
						<?php if($album->thumbnail != ''){ ?>
						<a class="album-cover-link" href="index.php?option=com_mojoom&view=photos&album_id=<?php echo $album->id;?>&user_id=<?php echo $album->creator; ?>"><img src="<?php echo $album->thumbnail; ?>" alt="<?php echo $album->name;?>" class="avatar"/></a>
						<?php } else {?>
						<a class="album-cover-link" href="<?php  ?>#">
							<img src="./components/com_community/assets/photo_thumb.png" class="avatar" alt="img"/>
						</a>
						<?php } ?>
						<div class="album-name"><a href="<?php ?>#"><?php if(strlen($album->name) >= 10){echo substr($album->name,0,7).'...';}else{echo $album->name;}?></a><?php echo "(".$album->count; ?>)
						</div>
        			</div>
			        
				</div> 
			  </div>
			  
	<?php 
		
		}
	}
	else
	{
		echo JText::_('NO ALBUM');
	}
?>
</div>
<?php 
} 
?>
</div>