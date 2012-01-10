<?php
/**
 * MoJoom - moJOOM is easily mobilized your social-networking
 * site which is developed using JomSocial , displaying perfect 
 * in iphone , Android and blackberry and all other Phones.
 * Copyright (C) 2003 - 2011, Dasinfomedia Pvt Ltd
 *
 * All rights reserved.  The MoJoom is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * This program is paid software; 
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * @version		1.0.0
 * @package		Mojoom
 * @copyright	2003 - 2011, Dasinfomedia Pvt Ltd
 * @license		Open Source License, GPL v2 based
 * 
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.helper');
$status = new JObject();
$status->plugins = array();


/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * PLUGIN REMOVAL SECTION
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$plugins = $this->manifest->getElementByPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin)
	{
		$pname		= $plugin->attributes('plugin');
		$pgroup		= $plugin->attributes('group');

		// Set the installation path
		if (!empty($pname) && !empty($pgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = JFactory::getDBO();

		// Delete the plugins in the #__plugins table
		$query = 'DELETE FROM #__plugins WHERE element = '.$db->Quote($pname).' AND folder = '.$db->Quote($pgroup);
		$db->setQuery($query);
		if (!$db->query()) {
			JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.$db->stderr(true));
			$retval = false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Remove all necessary files
		$element = $plugin->getElementByPath('files'); 
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			$this->parent->removeFiles($element, -1);
		}

		$element = $plugin->getElementByPath('languages');
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			$this->parent->removeFiles($element, 1);
		}

		// If the folder is empty, let's delete it
		$files = JFolder::files($this->parent->getPath('extension_root'));
		if (!count($files)) {
			JFolder::delete($this->parent->getPath('extension_root'));
		}

		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
	}
}


/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
$rows = 0;

/********************************************************************************
Delete a Default article
*********************************************************************************/ 
 
$db = JFactory::getDBO();
$query = 'DELETE FROM #__content WHERE alias = "mojoommore" ';
$db->setQuery($query);
$db->query();

$query1 = 'DELETE FROM #__content WHERE alias = "mojoomaboutus" ';
$db->setQuery($query1);
$db->query();

$query2 = 'DELETE FROM #__content WHERE alias = "mojoomhome" ';
$db->setQuery($query2);
$db->query();

///// delete menutype/////////////
$query3 = 'DELETE FROM #__menu_types WHERE menutype = "mojoommenu" ';
$db->setQuery($query3);
$db->query();
///////////////////////////////////////
/////////////module/////////////////////

$query4 = 'SELECT id FROM #__modules WHERE position = "mojoom" ';
$db->setQuery($query4);
$moduleidID = $db->loadResult();

$query5 = 'DELETE FROM #__modules WHERE position = "mojoom" ';
$db->setQuery($query5);
$db->query();

$query6 = 'DELETE FROM #__modules_menu WHERE moduleid = "'.$moduleidID.'" ';
$db->setQuery($query6);
$db->query();

$query7 = 'DELETE FROM #__menu WHERE menutype = "mojoommenu" ';
$db->setQuery($query7);
$db->query();
 
 ///////////////////////////////////////
/////////////template delete code/////////////////////
//uninstall templates
$templateslist = array ('mojoom');
foreach($templateslist as $t)
{
	if(!UninstallTemplate($t))
		$ERRORS[] = "<b>".JText::_('Cannot uninstall:')." Mobile Joomla '$t' template.</b>";
}
function UninstallTemplate($name)
{
	global $ERRORS;
	$TemplateDir = JPATH_ROOT.DS.'templates'.DS.$name;
	/** @var JDatabase $db */
	$db =& JFactory::getDBO();
	$db->setQuery('DELETE FROM #__templates_menu WHERE client_id = 0 AND template = '.$db->Quote($name));
	$db->query();
	if(!JFolder::delete($TemplateDir))
	{
		$ERRORS[] = JText::_('Cannot remove directory:').' '.$TemplateDir;
		return false;
	}
	return true;
}


?>

<h2>Mojoom Uninstallation</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Mojoom'.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
<?php
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	
	</tbody>
</table>
<table class="adminlist">
</table>