<?php
/**
 * Videos View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php
ini_set("display_errors","0");
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
	$document->setTitle('GALLERY::VIDEO');
	jimport( 'joomla.utilities.date' );

?> 
<script language="javascript" type="text/javascript">
function showvideo(vid)
{
	
	document.getElementById('videoitems').style.display = 'none';
	document.getElementById('video').innerHTML = setvideo(vid);
	document.getElementById('video').style.display = 'block';
}
function setvideo(vid)
{
	vidtg = "<div style='margin:0 auto;width:320px;'><iframe width='320' height='249' src=http://www.youtube.com/embed/"+ vid + " frameborder='0' allowfullscreen align='middle'></iframe></div>";
	return vidtg;
}

function decideback()
{
	if(document.getElementById('video').style.display == 'none')
	{
		history.back();
	}
	else
	{
		document.getElementById('videoitems').style.display = 'block';
		document.getElementById('video').style.display = 'none';
	}
}
</script>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="decideback()" class="back" value="Back"></div>
    <div id="header_title">Videos</div>
</div>
<div class="componentcontent">
<?php 
//print_r($this->videos); 
?>
	<?php if ($this->videos) { $flag=1;?>
   	 <script language="javascript" type="text/javascript">
		function filteralbum()
		{
		
			var selobj = document.getElementById('user_id');
			var index = selobj.selectedIndex;
			userid = selobj.options[index].value;
			window.location.href = "index.php?option=com_mojoom&view=videos&user_id=" + userid;
			
		}
		</script>
	<div class="video-items" id="videoitems">
    <?php $uid = JFactory::getUser()->id; if($uid == $this->user_id || $this->user_id == 0 ) { ?>
    	<?php echo JText::_('FILTER VIDEO BY ');$uid = JFactory::getUser()->id;?>&nbsp; 
			<select name="user_id" id="user_id" onchange="filteralbum();">
						<option value="0" <?php if($this->user_id == 0) { ?> selected="selected" <?php } ?> ><?php echo JText::_('ALL VIDEOS'); ?></option>
						<option value="<?php echo $uid;?>" <?php if($uid == $this->user_id) { ?> selected="selected" <?php } ?>><?php echo JText::_('MY VIDEOS'); ?></option>
			</select>
            
			<br /><br />
            <?php } ?>
		<?php foreach($this->videos as $video) { ?>
			<div class="video-item jomTips tipFullWidth" id="<?php echo "video-" . $video->id ?>" >
				<div class="video-item <?php if($flag == 1){ echo 'odd';$flag=0;}else{ echo 'even';$flag=1;}?>">
				<!---VIDEO THUMB-->
					<div class="video-thumb">      
						<a class="video-thumb-url" href="<?php // echo $video->getURL(); ?>#"style="width: <?php echo $this->videoThumbWidth; ?>px; height:<?php echo $this->videoThumbHeight; ?>px;" onclick="showvideo('<?php echo trim($video->video_id);?>')"><img src="<?php echo $video->thumb; ?>" width="<?php echo $this->videoThumbWidth; ?>" height="<?php echo $this->videoThumbHeight; ?>" alt="" /></a>
						<?php if ($video->status == 'ready'){?>   
						<span class="video-durationHMS">
							<?php 
								if($video->duration != 0)
								{
									$duration = $this->formatDuration( (int)($video->duration), 'HH:MM:SS' );
									$duration = $this->toNiceHMS( $duration );
								}
								else
								{
									$duration = JText::_('N/A');
								}
								
								echo $duration; ?>
						</span>
						<?php } ?>                
					</div>
					<!---end: VIDEO THUMB-->
					<!---VIDEO SUMMARY-->
					<div class="video-summary">
						<div class="video-title">
							<?php
							if (!$video->status == 'ready') { // pending
								echo $video->title;
							} else {
							?>
							<a href="<?php //echo $video->getURL(); ?>#" onclick="showvideo('<?php echo trim($video->video_id);?>')"><?php echo $video->title; ?></a>
							<?php } ?>
						</div>	
						<div class="video-details small">
							<div class="video-hits"><?php echo $video->hits." views";?></div>                    
							<div class="video-lastupdated">
							<?php
							if( $video->lastupdated == '0000-00-00 00:00:00' || $video->lastupdated == '')
							{
								$video->lastupdated	= $video->created;
				
								if( $video->lastupdated == '' || $video->lastupdated == '0000-00-00 00:00:00')
								{
									$video->lastupdated	= JText::_( 'N/A' );
								}
								else
								{
									$lastUpdated	= new JDate( $video->lastupdated );
									$video->lastupdated	= $this->createdLapse( $lastUpdated );
								}
							}
							else
							{
								$lastUpdated	= new JDate( $video->lastupdated );
								$video->lastupdated	= $this->createdLapse( $lastUpdated );
							}

						?>
							
							<?php echo "Last updated:".$video->lastupdated;?>
							</div>
							<div class="video-creatorName"><a href="<?php //echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id='.$video->creator); ?>"><?php //echo $this->getusername($video->creator)->username; ?></a></div> 
						</div> <!-- end: video-details small -->
					</div>
					<!---end: VIDEO SUMMARY-->	
				</div>
				<!---end: VIDEO ITEM-->
			</div>
			<!---end: VIDEO ITEM-->
	<?php } ?>
	
	</div>
	<!---end: VIDEO ITEM(S)-->
	
	<?php 
	
	}
	else
	{
		?>
        <div style="padding-left:3%;float:left;padding-bottom: 3%;padding-top: 3%;width: 97%;">
        <?php echo JText::_('NO VIDEO FOUND'); ?>
        </div>
        <?php
	}
	
	?>
<div id="video" style="display:none;float:left;width:100%;padding-top:2%;padding-bottom:2%;"></div>
</div>
<?php 
} 
?>