<?php 
/** 
 * Group Bulletin Create View for Mojoom Component
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
	$document->setTitle(JText::_('GP ADD NEWS'));
	
?>

<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<form method="post" action="index.php" id="creatediscussion" name="jsform-groups-discussion" class="community-form-validate">
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
	<div id="forward" class="android"><input type="submit" class="edit" value="Add" /></div>   
</div>
<div class="componentcontent">
	<div class="groupsdisscussioncontainer">
<!--==========================================	 -->
<table class="formtable">
	<?php echo $beforeFormDisplay;?>
	<?php if ( $this->config->get( 'htmleditor' ) == 'jce' ) : ?>
	<tr class="fromrow">
		<td class="key" >
			<label for="title" class="label" style="text-align: left;">*<?php echo JText::_('NEWS TITLE'); ?></label>
		</td>
	</tr>
	<tr class="fromrow">
		<td class="value" >
			<input type="text" name="title" id="title" class="inputbox" value="" style="width:100%;float:left;" />
		</td>
	</tr>
	
	<tr class="fromrow">
		<td class="key" >
			<label for="message" class="label" style="text-align: left;">*<?php echo JText::_('NEWS DESCRIPTION'); ?></label>
		</td>
	</tr>
	<tr class="fromrow">
		<td class="value" >
			<?php if( $this->config->get( 'htmleditor' ) && $this->config->getBool( 'allowhtml' ) ) : ?>
				<?php echo $this->editor->display( 'message',  '' , '95%', '200', '10', '20' , false ); ?>
			<?php else : ?>
				<textarea rows="3" cols="40" name="message" id="message" class="inputbox" style="width:100%;float:left;height:120px"></textarea>
			<?php endif; ?>
		</td>
	</tr>
	
	<?php else : ?>
	
	<tr class="fromrow">
		<td class="key">
			<label for="title" class="label">*<?php echo JText::_('NEWS TITLE'); ?></label>
		</td>
	</tr>
	<tr class="fromrow">
		<td class="value">
			<input type="text" name="title" id="title"  class="inputbox" value="" style="width:100%;float:left;" />
		</td>
	</tr>
	
	<tr class="fromrow">
		<td class="key">
			<label for="message" class="label">*<?php echo JText::_('NEWS DESCRIPTION'); ?></label>
		</td>
	</tr>
	<tr class="fromrow">
		<td class="value"> 
			<textarea rows="3" cols="40" name="message" id="message" class="inputbox" style="width:100%;float:left;height:120px"></textarea>
			

		</td>
	</tr>
	
	<?php endif; ?>
	
	<tr class="fromrow">
		<td class="key"></td>
		<td class="value">
			<span class="hints"><?php echo JText::_( 'VALIDATION FORM' ); ?></span>
		</td>
	</tr>
	<tr class="fromrow">
		<td class="key"></td>
		<td class="value">
			
			
		</td>
	</tr>
</table>
<input type="hidden" value="<?php echo $this->group->id; ?>" name="group_id" />
<input type="hidden" name="option" value="com_mojoom" />
<input type="hidden" name="task" value="savebulletin" />
<input type="hidden" name="controller" value="groups" />

</form>
<!--========================================== -->
</div>
</div>
<?php
}
?>