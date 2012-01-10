<?php
/** 
 * Group Bulletin Detail View for Mojoom Component
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
	$pagetitle = $this->bulletin->title;
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
	<div class="grpbulldetailcontainer">
<!--========================================= -->
	<div id="group-buletin-topic">


	<!--Discussion : Avatar-->

	<div class="author-avatar">

		<a href="<?php echo 'index.php?option=com_mojoom&view=mojoom&user_id='.$this->creator->id; ?>"><img class="avatar" src="<?php echo $this->creator->getThumbAvatar(); ?>" border="0" alt="" /></a>

	</div>

    <!--Discussion : Avatar-->

    

    <!--Discussion : Detail-->

	   <div class="buletin-detail">

		<!--Discussion : Author & Date-->

        <div class="buletin-created">

			<?php 
			echo JHTML::_('date', $this->bulletin->date, JText::_('DATE_FORMAT_LC')); ?>

        </div>

        <!--Discussion : Author & Date-->

        	

        <!--Discussion : Entry-->

      <div class="buletin-entry">

			<?php echo $this->bulletin->message; ?>

        </div>

        <!--Discussion : Entry-->

	</div>

    <!--Discussion : Detail-->

        

	<div style="clear: both;"></div>

</div>



<!--========================================= -->
	</div>
</div>
<?php
}
?>