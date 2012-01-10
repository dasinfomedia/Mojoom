<?php
/**
 * More View for Mojoom Component
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
	$document->setTitle(Jtext::_('MORE'));
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
    <div id="header_title"><?php echo JText::_('MORE'); ?></div>
</div>
<div class="componentcontent">
	<div id="groups"><span><a href="index.php?option=com_mojoom&controller=groups&task=mygroups"><?php echo JText::_('GROUP BUL'); ?></a></span></div>
	<div id="events"><span><a href="index.php?option=com_mojoom&controller=event&task=myevents"><?php echo JText::_('EVENT GROUP EVENT'); ?></a></span></div>
	<!--<div id="chats"><span><a href="#">Chat</a></span></div> -->
</div>
<?php 
} 
?>
