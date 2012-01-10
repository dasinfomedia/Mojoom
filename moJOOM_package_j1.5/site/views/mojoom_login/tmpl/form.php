<?php 
/**
 * Mojoom Login View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');
ini_set("display_errors","0"); ?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<div id="header_text">
	<div id="back"><a href="index.php?option=com_mojoom&view=register"><input type="button" class="edit" value="Sign Up"></a></div>
    <div id="header_title"><?php echo JText::_('LOGIN TITLE'); ?></div>
</div>

<form action="index.php" method="post" name="com-login" id="com-form-login">
<fieldset class="inputfldst">
	<p id="com-form-login-username">
		<label for="username"><?php echo JText::_('USERNAME') ?></label><br />
		<input name="username" id="username" type="text" class="inputbox" alt="username"  />
	</p>
	<p id="com-form-login-password">
		<label for="passwd"><?php echo JText::_('PASSWORD') ?></label><br />
		<input type="password" id="passwd" name="passwd" class="inputbox"  alt="password" />
	</p>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<p id="com-form-login-remember">
		<label for="remember"><?php echo JText::_('REMEMBER') ?></label>
		<input type="checkbox" id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" />
	</p>
	<?php endif; ?>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN TITLE') ?>" />
</fieldset>
<ul class="loginactions">
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
		<?php echo JText::_('FORGOT USERNAME'); ?></a>
	</li>
	<li>
		<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
		<?php echo JText::_('FORGOT PASSWORD'); ?></a>
	</li>
	
	<li>
		<a href="index.php?option=com_mojoom&view=register">
			<?php echo JText::_('REGISTER'); ?></a>
	</li>
	
</ul>
	<input type="hidden" name="option" value="com_mojoom" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="controller" value="mojoom" />
	<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form> 
