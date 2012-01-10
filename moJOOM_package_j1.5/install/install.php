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


$status = new JObject();
$status->templates = array();
$status->plugins = array();

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* PLUGIN INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/

$plugins = $this->manifest->getElementByPath('plugins');
if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children())) {

	foreach ($plugins->children() as $plugin)
	{
		$pname		= $plugin->attributes('plugin');
		$pgroup		= $plugin->attributes('group');
		$porder		= $plugin->attributes('order');

		// Set the installation path
		if (!empty($pname) && !empty($pgroup)) {
			$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$pgroup);
		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		* If we created the plugin directory and will want to remove it if we
		* have to roll back the installation, lets add it to the installation
		* step stack
		*/
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		$element = $plugin->getElementByPath('files');
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy all necessary files
		$element = $plugin->getElementByPath('languages');
		if ($this->parent->parseLanguages($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Copy media files
		$element = $plugin->getElementByPath('media');
		if ($this->parent->parseMedia($element, 1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$db = JFactory::getDBO();

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
		' FROM `#__plugins`' .
		' WHERE folder = '.$db->Quote($pgroup) .
		' AND element = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a plugin already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
			$row = JTable::getInstance('plugin');
			$row->name = JText::_(ucfirst($pgroup)).' - '.JText::_(ucfirst($pname));
			$row->ordering = $porder;
			$row->folder = $pgroup;
			$row->iscore = 0;
			$row->access = 0;
			$row->client_id = 0;
			$row->element = $pname;
			$row->published = 1;
			$row->params = '';

			if (!$row->store()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}
		}

		$status->plugins[] = array('name'=>$pname,'group'=>$pgroup);
	}
}

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* SETUP DEFAULTS
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
///// Enter default article////////////
$obj12 = new stdClass();
$obj12->id = '';
$obj12->title = 'Home';
$obj12->alias = 'mojoomhome';
$obj12->title_alias = '';
$obj12->introtext = '<p>moJOOM is easily mobilized your social-networking site which is   developed  using  JomSocial , displaying perfect in iphone , Android and   blackberry and all other Phones.</p>';
$obj12->fulltext = '';
$obj12->state = '1';
$obj12->sectionid = '0';
$obj12->mask = '0';
$obj12->catid = '0';
$obj12->created = date('Y-m-d h:i:s');
$obj12->created_by 	 = '62';
$obj12->created_by_alias = '';
$obj12->modified = '0000-00-00 00:00:00';
$obj12->modified_by = '0';
$obj12->checked_out = '0';
$obj12->checked_out_time = '0000-00-00 00:00:00';
$obj12->publish_up  = date('Y-m-d h:i:s');
$obj12->publish_down = '0000-00-00 00:00:00';
$obj12->images	 = '';
$obj12->urls	 = '';
$obj12->attribs	 = 'show_title=\link_titles=\show_intro=\show_section=\link_section=\show_category=\link_category=\show_vote=0\show_author=0\show_create_date=0\show_modify_date=0\show_pdf_icon=0\show_print_icon=0\show_email_icon=0\language=\keyref=\readmore=';
$obj12->version = '1';
$obj12->parentid = '0';
$obj12->ordering = '1';
$obj12->metakey = '';
$obj12->metadesc  = '';
$obj12->access = '0';
$obj12->hits	 = '0';
$obj12->metadata	 = 'robots=\author=';
$db->insertObject('#__content', $obj12, 'id');
$homeaticle_id = $obj12->id;


$obj13 = new stdClass();
$obj13->id = '';
$obj13->title = 'About us';
$obj13->alias = 'mojoomaboutus';
$obj13->title_alias = '';
$obj13->introtext = '<p> </p>
<p>We are providing features like :</p>
<p> </p>
<ul class="sec_menu" style="padding-left:10px;">
<li class="first"><img class="optimized" src="components/com_mojoom/images/optimized_icon.png" border="0" /> Profile Page</li>
<li><img class="optimized" src="components/com_mojoom/images/os_icon.png" border="0" />Messages</li>
<li><img class="optimized" src="components/com_mojoom/images/easy_icon.png" border="0" />Gallery</li>
<li><img class="optimized" src="components/com_mojoom/images/premium_icon.png" border="0" />moJOOM templates</li>
<li><img class="optimized" src="components/com_mojoom/images/activity_feature.png" border="0" />Activity Stream</li>
<li><img class="optimized" src="components/com_mojoom/images/languages_icon.png" border="0" />Friends</li>
<li class="last"><img class="optimized" src="components/com_mojoom/images/more.png" border="0" />More</li>
</ul>';
$obj13->fulltext = '';
$obj13->state = '1';
$obj13->sectionid = '0';
$obj13->mask = '0';
$obj13->catid = '0';
$obj13->created = date('Y-m-d h:i:s');
$obj13->created_by 	 = '62';
$obj13->created_by_alias = '';
$obj13->modified = '0000-00-00 00:00:00';
$obj13->modified_by = '0';
$obj13->checked_out = '0';
$obj13->checked_out_time = '0000-00-00 00:00:00';
$obj13->publish_up  = date('Y-m-d h:i:s');
$obj13->publish_down = '0000-00-00 00:00:00';
$obj13->images	 = '';
$obj13->urls	 = '';
$obj13->attribs	 = 'show_title=\link_titles=\show_intro=\show_section=\link_section=\show_category=\link_category=\show_vote=0\show_author=0\show_create_date=0\show_modify_date=0\show_pdf_icon=0\show_print_icon=0\show_email_icon=0\language=\keyref=\readmore=';
$obj13->version = '1';
$obj13->parentid = '0';
$obj13->ordering = '1';
$obj13->metakey = '';
$obj13->metadesc  = '';
$obj13->access = '0';
$obj13->hits	 = '0';
$obj13->metadata	 = 'robots=\author=';
$db->insertObject('#__content', $obj13, 'id');
$aboutusaticle_id = $obj13->id;

$obj14 = new stdClass();
$obj14->id = '';
$obj14->title = 'More';
$obj14->alias = 'mojoommore';
$obj14->title_alias = '';
$obj14->introtext = '<p>More contains Groups and Events which makes easier to interact with everyone and will have tremendous growth in membership.</p>';
$obj14->fulltext = '';
$obj14->state = '1';
$obj14->sectionid = '0';
$obj14->mask = '0';
$obj14->catid = '0';
$obj14->created = date('Y-m-d h:i:s');
$obj14->created_by 	 = '62';
$obj14->created_by_alias = '';
$obj14->modified = '0000-00-00 00:00:00';
$obj14->modified_by = '0';
$obj14->checked_out = '0';
$obj14->checked_out_time = '0000-00-00 00:00:00';
$obj14->publish_up  = date('Y-m-d h:i:s');
$obj14->publish_down = '0000-00-00 00:00:00';
$obj14->images	 = '';
$obj14->urls	 = '';
$obj14->attribs	 = 'show_title=\link_titles=\show_intro=\show_section=\link_section=\show_category=\link_category=\show_vote=0\show_author=0\show_create_date=0\show_modify_date=0\show_pdf_icon=0\show_print_icon=0\show_email_icon=0\language=\keyref=\readmore=';
$obj14->version = '1';
$obj14->parentid = '0';
$obj14->ordering = '1';
$obj14->metakey = '';
$obj14->metadesc  = '';
$obj14->access = '0';
$obj14->hits	 = '0';
$obj14->metadata	 = 'robots=\author=';
$db->insertObject('#__content', $obj14, 'id');
$moreaticle_id = $obj14->id;

//////////////////////////////////////


// Check to see if a plugin by the same name is already installed
// get the mojoom component's id
$query = 'SELECT `id`' .
' FROM `#__components`' .
' WHERE parent = 0 and name=' .$db->Quote('mojoom').
' AND parent = 0';
$db->setQuery($query);
$componentID = $db->loadResult();

// get the jomsocial component's id
$query1 = 'SELECT `id`' .
' FROM `#__components`' .
' WHERE parent = 0 and name=' .$db->Quote('JomSocial').
' AND parent = 0';
$db->setQuery($query1);
$componentID1 = $db->loadResult();

////////



///////
/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* OUTPUT TO SCREEN
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$rows = 0;
// installation of the menu
// step1 : insert it in the jos_menu_types
$obj = new stdClass();
$obj->id = '';
$obj->menutype = 'mojoommenu';
$obj->title = 'moJoomMenu';
$obj->description = 'Menu creted by Mojoom';
$db->insertObject('#__menu_types', $obj, 'id');
$menuid = $obj->id;
//step2 : insert into the jos_modules
$obj1 = new stdClass();
$obj1->id = '';
$obj1->title = 'moJoomMenu';
$obj1->content = '';
$obj1->ordering = 1;
$obj1->position = 'mojoom';
$obj1->checked_out = 0;
$obj1->checked_out_time = '0000-00-00 00:00:00';
$obj1->published = 1;
$obj1->module = 'mod_mainmenu';
$obj1->numnews = 0;
$obj1->access = 0;
$obj1->showtitle = 1;
$obj1->params = "menutype=mojoommenu\nmenu_style=list\nstartLevel=0\nendLevel=0\nshowAllChildren=0\nwindow_open=\nshow_whitespace=0\ncache=1\ntag_id=\nclass_sfx=\nmoduleclass_sfx=\nmaxdepth=10\nmenu_images=0\nmenu_images_align=0\nmenu_images_link=0\nexpand_menu=0\nactivate_parent=0\nfull_active_id=0\nindent_image=0\nindent_image1=\nindent_image2=\nindent_image3=\nindent_image4=\nindent_image5=\nindent_image6=\nspacer=\nend_spacer=";
$obj1->iscore = 0;
$obj1->client_id = 0;
$obj1->control = '';
$db->insertObject('#__modules', $obj1, 'id');
$moduleid = $obj1->id;
//step3 : insert into the jos_modules_menu 
$obj2 = new stdClass();
$obj2->moduleid = $moduleid;
$obj2->menuid = 0;
$db->insertObject('#__modules_menu', $obj2, 'id');
//step 4 : create 1st menu item
$obj3 = new stdClass();
$obj3->id = '';
$obj3->menutype = 'mojoommenu';
$obj3->name = 'Home';
$obj3->alias = 'mojoomhome';
$obj3->link = "index.php?option=com_content&view=article&id=$homeaticle_id";
$obj3->type = 'component';
$obj3->published = 1;
$obj3->parent = 0;
$obj3->componentid = 20;
$obj3->sublevel = 0;
$obj3->ordering = 1; 
$obj3->checked_out = 0;
$obj3->checked_out_time = '0000-00-00 00:00:00';
$obj3->pollid = 0;
$obj3->browserNav = 0;
$obj3->access = 0;
$obj3->utaccess = 0;
$obj3->params = "num_leading_articles=1\nnum_intro_articles=1\nnum_columns=0\nnum_links=0\norderby_pri=\norderby_sec=front\nmulti_column_order=1\nshow_pagination=2\nshow_pagination_results=1\nshow_feed_link=1\nshow_noauth=\nshow_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_item_navigation=\nshow_readmore=\nshow_vote=\nshow_icons=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nshow_hits=\nfeed_summary=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0";
$obj3->lft = 0;
$obj3->rgt = 0;
$obj3->home = 0;
$db->insertObject('#__menu', $obj3, 'id');
///// M  //////
$hid = $obj3->id; 
/////
//step 5 : create 2nd menu item
$obj4 = new stdClass();
$obj4->id = '';
$obj4->menutype = 'mojoommenu';
$obj4->name = 'Profile';
$obj4->alias = 'mojoomprofile';
$obj4->link = 'index.php?option=com_mojoom&view=mojoom';
$obj4->type = 'component';
$obj4->published = 1;
$obj4->parent = 0;
$obj4->componentid = $componentID;
$obj4->sublevel = 0;
$obj4->ordering = 2; 
$obj4->checked_out = 0;
$obj4->checked_out_time = '0000-00-00 00:00:00';
$obj4->pollid = 0;
$obj4->browserNav = 0;
$obj4->access = 0;
$obj4->utaccess = 0;
$obj4->params = "page_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0";
$obj4->lft = 0;
$obj4->rgt = 0;
$obj4->home = 0;
$db->insertObject('#__menu', $obj4, 'id');
///// M  //////
$pid = $obj4->id; 
/////
//step 6 : create 3rd menu item
$obj5 = new stdClass();
$obj5->id = '';
$obj5->menutype = 'mojoommenu';
$obj5->name = 'About us';
$obj5->alias = 'mojoomaboutus';
$obj5->link = "index.php?option=com_content&view=article&id=$aboutusaticle_id";
$obj5->type = 'component';
$obj5->published = 1;
$obj5->parent = 0;
$obj5->componentid = $componentID1;
$obj5->sublevel = 0;
$obj5->ordering = 3; 
$obj5->checked_out = 0;
$obj5->checked_out_time = '0000-00-00 00:00:00';
$obj5->pollid = 0;
$obj5->browserNav = 0;
$obj5->access = 0;
$obj5->utaccess = 0;
$obj5->params = "page_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0";
$obj5->lft = 0;
$obj5->rgt = 0;
$obj5->home = 0;
$db->insertObject('#__menu', $obj5, 'id');
///// M  //////
$aid = $obj5->id; 
/////
//step 7 : create 4th menu item
$obj6 = new stdClass();
$obj6->id = '';
$obj6->menutype = 'mojoommenu';
$obj6->name = 'More';
$obj6->alias = 'mojoommore';
$obj6->link = "index.php?option=com_content&view=article&id=$moreaticle_id";
$obj6->type = 'component';
$obj6->published = 1;
$obj6->parent = 0;
$obj6->componentid = 20;
$obj6->sublevel = 0;
$obj6->ordering = 4; 
$obj6->checked_out = 0;
$obj6->checked_out_time = '0000-00-00 00:00:00';
$obj6->pollid = 0;
$obj6->browserNav = 0;
$obj6->access = 0;
$obj6->utaccess = 0;
$obj6->params = "num_leading_articles=1\nnum_intro_articles=1\nnum_columns=0\nnum_links=0\norderby_pri=\norderby_sec=front\nmulti_column_order=1\nshow_pagination=2\nshow_pagination_results=1\nshow_feed_link=1\nshow_noauth=\nshow_title=\nlink_titles=\nshow_intro=\nshow_section=\nlink_section=\nshow_category=\nlink_category=\nshow_author=\nshow_create_date=\nshow_modify_date=\nshow_item_navigation=\nshow_readmore=\nshow_vote=\nshow_icons=\nshow_pdf_icon=\nshow_print_icon=\nshow_email_icon=\nshow_hits=\nfeed_summary=\npage_title=\nshow_page_title=1\npageclass_sfx=\nmenu_image=-1\nsecure=0";
$obj6->lft = 0;
$obj6->rgt = 0;
$obj6->home = 0;
$db->insertObject('#__menu', $obj6, 'id');
///// M  //////
$mid = $obj6->id; 
/// Inser in mojoom config table 
$obj7 = new stdClass();
$obj7->id = '';
$obj7->iphone_template='mojoom';
$obj7->iphonetemplatetheme ='red';
$obj7->iphonehomepage="index.php?option=com_content&view=article&id=$homeaticle_id&Itemid=$hid";
$obj7->iphoneprofilepage="index.php?option=com_mojoom&view=mojoom&Itemid=$pid";
$obj7->iphoneaboutuspage="index.php?option=com_content&view=article&id=$aboutusaticle_id&Itemid=$aid";
$obj7->iphonemorepage="index.php?option=com_content&view=article&id=$moreaticle_id&Itemid=$mid";
$obj7->date = date('Y-m-d H:i:s');
$obj7->published = 1;
$db->insertObject('#__mojoom_config', $obj7, 'id');

 ?>
<?php 
	// install templates
	$TemplateSource = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_mojoom'.DS.'template';
	$templates = array ('mojoom');
	$status1 = true;
	foreach($templates as $template)
	{
		if(!InstallTemplate($TemplateSource.DS.$template, $template,$hid,$pid,$aid,$mid))
		{
			$status1 = false;
			$ERRORS[] = "<b>".JText::_('Cannot install:')." MoJoom '$template' template.</b>";
		}
	}
	if($status1)
		JFolder::delete($TemplateSource);

function InstallTemplate($sourcedir, $name,$hid,$pid,$aid,$mid)
{
	global $ERRORS;
	$TemplateDir = JPATH_ROOT.DS.'templates'.DS.$name;
	if(!is_dir($sourcedir))
	{
		$ERRORS[] = JText::_('Cannot find directory:')." $sourcedir.";
		return false;
	}
	if(is_dir($TemplateDir) && !JFolder::delete($TemplateDir))
	{
		$ERRORS[] = JText::_('Cannot remove directory:').' '.$TemplateDir;
		return false;
	}
	$status1 = true===JFolder::move($sourcedir, $TemplateDir);
	if(is_file($TemplateDir.DS.'templateDetails.xm_') &&
			!JFile::move($TemplateDir.DS.'templateDetails.xm_', $TemplateDir.DS.'templateDetails.xml'))
	{
		$ERRORS[] = str_replace(array ('%1', '%2'),
		                        array ($TemplateDir.DS.'templateDetails.xm_', $TemplateDir.DS.'templateDetails.xml'),
		                        JText::_("Cannot rename '%1' into '%2'."));
		$status1 = false;
	}
	else
	{
		/** @var JDatabase $db */
		$db =& JFactory::getDBO();
		$query = 'SELECT COUNT(*) FROM #__templates_menu WHERE template = '.$db->Quote($name);
		$db->setQuery($query);
		if($db->loadResult()==0)
		{
			$query = 'INSERT INTO #__templates_menu (template, menuid) VALUES ('.$db->Quote($name).', '.$hid.');';
			$query1 = 'INSERT INTO #__templates_menu (template, menuid) VALUES ('.$db->Quote($name).', '.$pid.');';
			$query2 = 'INSERT INTO #__templates_menu (template, menuid) VALUES ('.$db->Quote($name).', '.$aid.');';
			$query3 = 'INSERT INTO #__templates_menu (template, menuid) VALUES ('.$db->Quote($name).', '.$mid.');';
			$db->setQuery($query);
			$db->query();
			$db->setQuery($query1);
			$db->query();
			$db->setQuery($query2);
			$db->query();
			$db->setQuery($query3);
			$db->query();
		}
	}
	return $status1;
}
?>
<img src="../components/com_mojoom/images/logo.png" width="88" height="33" alt="Mojoom ! Mobile based social networking " align="right" />

<h2>Mojoom Installation</h2>
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
			<td class="key" colspan="2"><?php echo 'Mojoom '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
<?php
if (count($status->templates)) : ?>
		<tr>
			<th colspan="2"><?php echo JText::_('Template'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->templates as $template) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($template['name']); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
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
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>
