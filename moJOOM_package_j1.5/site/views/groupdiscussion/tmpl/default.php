<?php 
/** 
 * Group Discussion View for Mojoom Component
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
	$pagetitle = JText::_('DISCUS FORM TITLE'. $this->group->name);
	$document->setTitle($pagetitle);

?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
	<div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back">
    </div>
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
	
</div>
<div class="componentcontent">
	<div class="groupdiscussioncontainer">
	
	<?php
	if( $this->discussions )
	{
		foreach($this->discussions as $row)
		{
		?>
		<div id="discuss_<?php echo $row->id; ?>" class="group-discussion">
			<div class="group-discussion-title">
            	<a href="<?php echo JRoute::_('index.php?option=com_mojoom&controller=groups&task=viewdiscussion&group_id=' . $this->group->id. '&topicid=' . $row->id ); ?>">
                <?php echo $row->title; ?>
            </a>

            <div class="group-discussion-replies">
                <a href="<?php echo JRoute::_('index.php?option=com_mojoom&controller=groups&task=viewdiscussion&group_id=' . $this->group->id. '&topicid=' . $row->id ); ?>">
                    <?php 
					if($row->count == 1)
						echo JText::sprintf('REPLAY GD', $row->count);
					else
						echo JText::sprintf('REPLIES GD', $row->count);
					?>
                </a>

            </div>

        </div>

        <?php if( $row->lastmessage ){ ?>

        <div class="clr"></div>

        <div class="group-discussion-introtext small"><?php echo $this->escape( $row->lastmessage );?></div>

        <?php } ?>

        <div class="clr"></div>

        <div class="group-discussion-author small">

            <?php if( isset( $row->lastreplier ) && !empty( $row->lastreplier ) ) { ?>

            <span class="groups-news-author">

            </span>

            <?php } else { ?>

            <span class="groups-news-author">

				<?php echo JText::sprintf('Started by %1$s.' , '<a href="' . 'index.php?option=com_mojoom&view=mojoom&user_id='.$row->creator. '">' . JFactory::getUser($row->creator)->name . '</a>'); ?>

			</span>

            <?php } ?>

        </div>

	</div>

	<?php

	}

	?>

<?php

}

else

{

?>

	<div class="empty"><?php echo JText::_('NO DIS ADDED'); ?></div>

<?php

}

?>

	<a style="float:left;width:100%;text-align:right;padding-top:10px;" href="index.php?option=com_mojoom&controller=groups&task=creatediscussion&group_id=<?php echo $this->group->id;?>"><?php echo JText::_('CREATE DISCUSSION'); ?></a>
	</div>
</div>
<?php
}
?>