<?php
/**
 * Photos View for Mojoom Component
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
	$document->setTitle(JText::_('GAL PHOTO TITLE'));
?> 
<script language="javascript" type="text/javascript">
function showimage(img,curWidth1)
{	
	document.getElementById('community-photo-items').style.display = 'none';
	document.getElementById('photo').innerHTML = setimage(img,curWidth1);
	document.getElementById('photo').style.display = 'block';
}
function setimage(img,curWidth)
{
	if(curWidth < window.outerWidth )
	{
		imgtg = "<img class='smallimg' src='" + img + "' align='absmiddle'>";
	}
	else
	{
		imgtg = "<img class='bigimg' src='" + img + "' align='absmiddle'>";
	}
	return imgtg;
}

function decideback()
{
	if(document.getElementById('photo').style.display == 'none')
	{
		history.back();
	}
	else
	{
		document.getElementById('community-photo-items').style.display = 'block';
		document.getElementById('photo').style.display = 'none';
	}
}
</script>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="decideback();" class="back" value="Back"></div>
    <div id="header_title"><?php echo JText::_('IMG GAL HEAD'); ?></div>
</div>
<div class="componentcontent">
	<div id="community-photo-items" class="photo-list-item">
		<?php
		if($this->photos)
		{	$ctr=1;
			for( $i=0, $j=1; $i<count($this->photos); $i++,$j++ )
			{
				$row =& $this->photos[$i];
				if($ctr == 1)
				{ ?>
				<div class="photorow">
				<?php
				}
				?>
					<div class="photo-item jomTips" id="photo-<?php echo $i;?>" title="<?php echo $this->escape($row->caption);?>">
						<?php 
							// get the image size
							$size = getimagesize($row->image);
							$width = $size[0];
						?>
						<a href="#" onclick="showimage('<?php echo $row->image;?>',<?php echo $width; ?>);"><img class="jomTips" src="<?php echo $row->thumbnail;?>" alt="<?php echo $this->escape($row->caption);?>" id="photoid-<?php echo $row->id;?>"  /></a>
					</div>
					
				<?php if($ctr == 4 || $j == count($this->photos) ) 
					{ 	?>
						</div>
						<?php 
						$ctr = 1;
					}
					else
					{
						$ctr= $ctr + 1;
					}
				}
			}
			else
			{
			 echo JText::_('NO PHOTO UPLOADED');
			}
			?>
		
	</div>
	
	<div id="photo" style="display:none;margin-left:0;float:left;width:100%;text-align:center;"></div>

</div>
<?php 
} 
?>
</div>