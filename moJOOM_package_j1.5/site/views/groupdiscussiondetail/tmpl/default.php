<?php
/** 
 * Group Discussion create View for mojoom Component
 * 
 * @package    mojoom
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
	$pagetitle = $this->discussion->title;
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
</div>
<div class="componentcontent">
	<div class="grpdissdetailcontainer">
<!--========================================= -->
	<div id="group-discussion-topic">

	<!--Discussion : Avatar-->

	<div class="author-avatar">

		<a href="<?php echo 'index.php?option=com_mojoom&view=mojoom&user_id='.$this->creator->id; ?>"><img class="avatar" src="<?php echo $this->creator->getThumbAvatar(); ?>" border="0" alt="" /></a>

	</div>

    <!--Discussion : Avatar-->

    

    <!--Discussion : Detail-->

	<div class="discussion-detail">

		<!--Discussion : Author & Date-->

        <div class="discussion-created">

			<?php 
			$creatorLink = 'index.php?option=com_mojoom&view=mojoom&user_id='.$this->creator->id; 
			echo JText::sprintf('Started by <a href="%1$s">%2$s</a> ,<br> on %3$s' , $creatorLink , $this->creator->getDisplayName() , JHTML::_('date', $this->discussion->created, JText::_('%d %b %Y %I:%M %p'))); ?>
            <?php //echo JText::sprintf('Until: <br> %1$s' , JHTML::_('date', $event->enddate, JText::_('%a, %d %B %Y %I:%M %P')) );?>

        </div>

        <!--Discussion : Author & Date-->

        	

        <!--Discussion : Entry-->

        <div class="discussion-entry">

			<?php echo $this->discussion->message; ?>

        </div>

        <!--Discussion : Entry-->

	</div>

    <!--Discussion : Detail-->

        

	<div style="clear: both;"></div>

</div>


<form action="index.php" name="commentadd" method="post">
<div class="app-box">

	<div class="wall-tittle"><?php echo JText::_('REPLIES'); ?></div>

	<?php if($this->config->get('group_discuss_order') == 'DESC'){ ?>

	<div id="wallForm">
	
		<div class="cavatar">

			<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $this->my->id );?>"><img class="avatar" alt="<" src="<?php echo $this->my->getThumbAvatar()?>"></a>

		</div>



		<div class="ccontent-avatar">

			<textarea id="wall-message" name="message" class="inputbox" rows="" cols=""></textarea>



			<div class="wall-respond-area">

			<input type="submit" name="submit" value="Add Comment" />
			</div>

		</div>

<div style="clear:both;"></div>


	
	</div>

	<div id="wallContent">
	
	<?php
		foreach($this->wallContent as $wall)
		{
			$user1	= CFactory::getUser( $wall->post_by );
		?>
			<!--========================== -->
			<div id="wall_<?php echo $wall->id; ?>" class="wallComments">

    			<div class="cavatar">
					<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $user1->id );?>"><img class="avatar" alt="<" src="<?php echo $user1->getThumbAvatar()?>"></a>

				</div>

    			<div class="ccontent-avatar">

    				<div class="createby">

        				<a href="<?php echo 'index.php?option=com_mojoom&view=mojoom&user_id=' .  $wall->post_by ?>"><?php echo $user1->getDisplayName(); ?></a>,

    				</div>

   					<div class="content">

        				<span id="wall-message-<?php echo $id;?>"><?php echo $wall->comment; ?></span>

    				</div>

    

    				<div class="date">

      					<span class="createdate">
						<?php //echo JHTML::_('date', $wall->date, JText::_('DATE_FORMAT_LC2')); ?>
                        <?php echo JHTML::_('date', $wall->date, JText::_('%a, %d %b %Y %I:%M %P')) ;?>
                        </span>

    	
  					</div>

				</div>

				<div class="clr">&nbsp;</div>

			</div>

			<!--========================== -->
		<?php
		}
	?>
	
	</div>



	<?php } else { ?>

	<div id="wallContent"><?php //echo $wallContent; ?></div>

	<div id="wallForm">
	<div class="cavatar">

			<a href="<?php echo JRoute::_('index.php?option=com_mojoom&view=mojoom&user_id=' . $this->my->id );?>"><img class="avatar" alt="<" src="<?php echo $this->my->getThumbAvatar()?>"></a>

		</div>



		<div class="ccontent-avatar">

			<textarea id="wall-message" name="message" class="inputbox" rows="" cols=""></textarea>



			<div class="wall-respond-area">

			<input type="submit" name="submit" value="Add Comment" />
			</div>

		</div>

<div style="clear:both;"></div>
	</div>



	<?php } ?>

	

</div>
<input type="hidden" name="group_id" value="<?php echo $this->group->id; ?>" />
<input type="hidden" name="topic_id" value="<?php echo $this->topicId; ?>" />
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="task" value="commentadd" />
<input type="hidden" name="controller" value="groups" />

</form>
<!--========================================= -->
	</div>
</div>
<?php
}
?>