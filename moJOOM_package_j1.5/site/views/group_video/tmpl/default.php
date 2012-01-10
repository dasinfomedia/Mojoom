<?php 
/**
 * Group Video View for Mojoom Component
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
	$document->setTitle(JText::_('GAL GROUP VIDEO TITLE'));
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
	vidtg = "<iframe width='320' height='249' src=http://www.youtube.com/embed/"+ vid + " frameborder='0' allowfullscreen></iframe>";
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
    <div id="header_title"><?php echo JText::_('GROUP VIDEO HEAD'); ?></div>
</div>
<div class="componentcontent">
<?php 
//print_r($this->videos); 
?>
	<?php if ($this->videos) { ?>
	<div class="video-items" id="videoitems">
		<?php foreach($this->videos as $video) { ?>
			<div class="video-item jomTips tipFullWidth" id="<?php echo "video-" . $video->id ?>" >
				<div class="video-item">
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
							<a href="<?php //echo $video->getURL(); ?>#"><?php echo $video->title; ?></a>
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
							
							<?php echo JText::_("LAST UP GROUP".$video->lastupdated);?>
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
	
	?>
<div id="video" style="display:none;"></div>
</div>
<?php 
} 
?>