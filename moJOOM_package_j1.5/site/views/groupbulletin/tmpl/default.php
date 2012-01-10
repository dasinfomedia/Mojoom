<?php 
/** 
 * Group Bulletin View for Mojoom Component
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
	$pagetitle = JText::_('BULLENTIN TITLE '.$this->group->name);
	$document->setTitle($pagetitle);
	

?>
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
	<?php // if($this->ismine) { ?>
	<!--<div id="forward"><a href="/mojoom/index.php?option=com_mojoom&controller=groups&task=editgroup&group_id=<?php echo $this->group->id;?>"><input type="button" class="edit" value="Edit"></a></div>  -->
	<?php //} ?>
</div>
<div class="componentcontent">
	<div class="groupbulletincontainer">
<!--	====================================== -->
	<?php

	
	
	
	
	if( $this->bulletins )
	
	{
	
		foreach( $this->bulletins as $row )
	
		{
	
	
	?>
	
		<div id="bulletin_<?php echo $row->id; ?>" class="groups-news-row">
	
			<div class="groups-news-title">
	
				<a href="<?php echo JRoute::_('index.php?option=com_mojoom&controller=groups&task=groupbulletindetail&group_id=' . $this->group->id . '&bulletinid=' . $row->id);?>">
	
					<?php echo $row->title; ?>
	
				</a>
	
			</div>
	
			<div class="groups-news-meta small">
	
				<span class="group-news-date">
	
					<?php echo JHTML::_('date' , $row->date, JText::_('DATE_FORMAT_LC')); ?>
	
				</span>
	
				<span class="group-news-author">
	
					<?php echo JText::sprintf( 'by <a href="%2$s">%1$s</a>' , $row->creator->getDisplayName() , JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $row->creator->id ) ); ?>
	
				</span>
	
			</div>
	
			<?php
	
				// Only display news item for first item
	
				if( $i == 0 )
	
				{
	
			?>
	
			<div class="groups-news-text">
	
				<?php echo $row->message;?>
	
			</div>
	
			<?php
	
				}
	
			?>
	
		</div>
	
	
	
	
	
	<?php
	
		} //end foreaach
	
	} // end if
	
	else
	
	{
	
	?>
	
		<div class="empty"><?php echo JText::_('NO BUL ADDED'); ?></div>
	
	<?php
	
	}	
	
	?>
<!--	====================================== -->
<!-- create bulletin/news link -->
	<a style="float:left;width:100%;text-align:right;padding-top:10px;" href="index.php?option=com_mojoom&controller=groups&task=createbulletin&group_id=<?php echo $this->group->id;?>"><?php echo JText::_('CREATE BUL'); ?></a>

	</div>
</div>
<?php
}
?>