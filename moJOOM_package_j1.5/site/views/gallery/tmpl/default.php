<?php 
/**
 * Gallary View for Mojoom Component
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
	$document->setTitle(JText::_('PRO GAL'));
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
    <div id="back"><!--<input type="button" onclick="javascript:history.back();" class="back" value="Back">-->
    <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" /><input type="button" value="Back" class="back"  /></a></div>
    <div id="header_title"><?php echo JText::_('GALLERY'); ?></div>
</div>
<div class="componentcontent">
	<div id="photos"><span><a href="index.php?option=com_mojoom&view=albums&user_id=<?php echo $user->id; ?>"><?php echo JText::_('PHOTO'); ?></a></span></div>
	<div id="videos"><span><a href="index.php?option=com_mojoom&view=videos"><?php echo JText::_('VIDEO'); ?></a></span></div>
</div>
<?php 
} 
?>
