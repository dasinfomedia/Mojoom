<?php
/**
 * Ragister View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
});
// -->
function validatethis()
{
	if(document.getElementById('name').value == '')
	{
		alert('Please Enter Name.');
		return false;
	}
	if(document.getElementById('username').value == '')
	{
		alert('Please Enter Username.');
		return false;
	}
	if(document.getElementById('email').value == '')
	{
		alert('Please Enter Email.');
		return false;
	}
	var x=document.forms["josForm"]["email"].value;
	regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
	var res = regex.test(x);
	
	if (res == false)
	{
	  	alert("Please Enter Valid Email.");
		return false;
	}
	if(document.getElementById('password').value == '')
	{
		alert('Please Enter Password.');
		return false;
	}
	if(document.getElementById('password').value.length < 6)
	{
		alert('Password should be of atleast 6 characters.');
		return false;
	}
	if(document.getElementById('password2').value == '')
	{
		alert('Please Enter Verify Password.');
		return false;
	}
	if(document.getElementById('password').value.toString() != document.getElementById('password2').value.toString() )
	{
		alert('Verify Password should be same as Password.');
		return false;
	}
	

	return true;
}
</script>

<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate registration-form" >
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40">
		<label id="namemsg" for="name">
			<?php echo JText::_('NAME GP'); ?>:
		</label>
	</td>
  	<td>
  		<input type="text" name="name" id="name" value="" class="inputbox required" maxlength="50" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="usernamemsg" for="username">
			<?php echo JText::_( 'REGISTER USERNAME' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="username" name="username" value="" class="inputbox required validate-username" maxlength="25" /> *
	</td>
</tr>
<tr>
	<td height="40">
		<label id="emailmsg" for="email">
			<?php echo JText::_( 'EMAIL' ); ?>:
		</label>
	</td>
	<td>
		<input type="text" id="email" name="email" value="" class="inputbox required validate-email" maxlength="100" /> *
	</td>
</tr>
<tr>
	<td height="40">
		<label id="pwmsg" for="password">
			<?php echo JText::_( 'REGISTER PASSWORD' ); ?>:
		</label>
	</td>
  	<td>
  		<input class="inputbox required validate-password" type="password" id="password" name="password" value="" /> *
  	</td>
</tr>
<tr>
	<td height="40">
		<label id="pw2msg" for="password2">
			<?php echo JText::_( 'VERIFY PASSWORD' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required validate-passverify" type="password" id="password2" name="password2" value="" /> *
	</td>
</tr>
<tr>
	<td colspan="2" height="40">
		<?php echo JText::_( 'VALIDATION FORM' ); ?>
	</td>
</tr>
</table>
	<button class="button validate" type="submit" onclick="return validatethis()"><?php echo JText::_('REGISTER'); ?></button>
	<input type="hidden" name="option" value="com_mojoom" />
    <input type="hidden" name="task" value="register_save" />
	<input type="hidden" name="id" value="0" />
	<input type="hidden" name="gid" value="0" />
    <input type="hidden" name="controller" value="register" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>