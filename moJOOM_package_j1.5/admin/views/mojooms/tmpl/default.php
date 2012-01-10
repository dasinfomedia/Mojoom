<?php
/**
 * Mojooms View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
ini_set("display_errors","0"); 
?>
<script language="javascript" type="text/javascript">
function showthemeoption()
{
	var selectobj = document.getElementById('iphone_template');
	var val = selectobj.options[selectobj.selectedIndex].value;
	if(val == 'mojoom') {
		document.getElementById('themeoptions').style.display = 'table-row';
	}else{
		document.getElementById('themeoptions').style.display = 'none';
	}
}
function showsocialmedia()
{
	var selectobj = document.getElementById('socialneticons');
	
	if(document.adminForm.socialneticons[0].checked == '1' )
	{
		document.getElementById('socialmedia').style.display = 'table-row';
	}
	else
	{
		document.getElementById('socialmedia').style.display = 'none';
	}
}
</script>
<?php
JHTML::_('behavior.tooltip');
JHTML::_('behavior.switcher');
?>
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
		<?php
		jimport('joomla.html.pane');
		$config = $this->data[0]; // view variable
		
		/** @var JPane $tabs */
		$tabs =& JPane::getInstance();
		echo $tabs->startPane('configPane');
		echo $tabs->startPanel('iPhone', 'iphone-page');
		?>
		<br/>
		<fieldset class="adminform">
			<legend><?php echo JText::_('iPhone/iPad Settings'); ?></legend>
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_('TEMPLATE_NAME'); ?>::<?php echo JText::_('IPHONE_TEMP_NAME'); ?>"><?php echo JText::_('TEMPLATE_NAME'); ?></span>
					</td>
					<td>
					
					<select name="iphone_template" id="iphone_template" onchange="showthemeoption();" >
					<?php
						if(empty($config->id))
						{
							$ctr = 0;
							foreach($this->templates as $template)
							{	
								if($ctr == 1)
								{
								?>
								<option value="<?php echo $template['value']; ?>" selected="selected"><?php echo $template['value']; ?></option>
								<?php
								}
								else
								{
								?>
								<option value="<?php echo $template['value']; ?>"><?php echo $template['value']; ?></option>
								<?php
								}
								$ctr = $ctr + 1;
							}
						}
						else
						{
							foreach($this->templates as $template)
							{	
								
								?>
				<option value="<?php echo $template['value']; ?>" <?php if($config->iphone_template == $template['value']){ ?>selected="selected" <?php } ?>><?php echo $template['value']; ?></option>
								<?php
								
							}
						}
							
						?>
						</select>
					 </td>
				</tr>
				<!-- only visisble if the select is mytemplate and if already selected the  mytemplate then by default show theme options -->
				
				<tr id="themeoptions" <?php if($config->iphone_template == 'mojoom'){?> style="display:table-row;"<?php }else{?> style="display:none;"<?php } ?>>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_('TEMP_THEME'); ?>::<?php echo JText::_('IPHONE_TEMP_COLOR'); ?>"><?php echo JText::_('TEMP_THEME'); ?></span>
					</td>
					<td>
						<select name="iphonetemplatetheme">
							<option value="red" <?php if($config->iphonetemplatetheme == 'blue') { ?> selected="selected" <?php } ?>>red</option>
							<option value="green" <?php if($config->iphonetemplatetheme == 'green') { ?> selected="selected" <?php } ?>>green</option>
							<option value="blue" <?php if($config->iphonetemplatetheme == 'blue') { ?> selected="selected" <?php } ?>>blue</option>							
						</select>
					</td>
				</tr>
				<!-- only visisble if the select is mytemplate -->
				</tbody>
			</table>
		</fieldset>
		<fieldset class="adminform">
		<legend><?php echo JText::_('iPhone/iPad Default Template Header Settings'); ?></legend>
		<table class="admintable" cellspacing="1">
			<tbody>
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_('SELECT_LOGO'); ?>::<?php echo JText::_('IPHONE_TEMP_LOGO'); ?>"><?php echo JText::_('SELECT_LOGO'); ?></span>
					</td>
					<td>
						<input type="file" name="iphonelogo" id="iphonelogo" />
					</td>
				</tr>
					<?php if($config->iphonelogo){
						?>
					<tr>
						<td></td>
						<td>
						<img src="<?php echo $this->baseurl; ?>/components/com_mojoom/images/<?php echo $config->iphonelogo; ?>" alt="logo" class="editlinktip hasTip" title="<?php echo JText::_('Selected Logo'); ?>::<?php echo JText::_('Currently selected iPhone template logo.'); ?>"/>
						</td>
					</tr>	
					 <?php } ?>
					 <tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('SHOW_JOOMLA_SEARCH'); ?>::<?php echo JText::_('ENABLE_JOOMLA_SEARCH'); ?>"><?php echo JText::_('SHOW_JOOMLA_SEARCH'); ?></span>
					</td>
					<td>
						<input type="radio" name="iphonejoomlasearch" id="iphonejoomlasearch" value="1"  <?php if($config->iphonejoomlasearch == 1){ ?> checked="checked" <?php } ?>/>Yes
						<input type="radio" name="iphonejoomlasearch" id="iphonejoomlasearch" value="0" <?php if($config->iphonejoomlasearch == 0){ ?> checked="checked" <?php } ?>/>No
					</td>
				</tr>
				
				<tr>
					<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_('SOCIAL_NETWORKING_ICONS_AT_TOP_RIGHT'); ?>::<?php echo JText::_('ENABLE_SOCIAL_NETWORK_ICONS'); ?>"><?php echo JText::_('SOCIAL_NETWORKING_ICONS_AT_TOP_RIGHT'); ?></span>
					</td>
					<td>
					<input type="radio" name="socialneticons" id="socialneticons" value="1" <?php if($config->socialneticons == 1){ ?> checked="checked" <?php } ?> onchange="showsocialmedia()"/>Yes
					<input type="radio" name="socialneticons" id="socialneticons" value="0" <?php if($config->socialneticons == 0){ ?> checked="checked" <?php } ?> onchange="showsocialmedia()"/>No						
					</td>
				</tr>
				
			<tr id="socialmedia" <?php if($config->socialneticons == 1){ echo 'in';?> style="display:table-row;"<?php }else{?> style="display:none;"<?php }  ?>>
				<td colspan="2">
					<table class="admintable" cellspacing="1">
						<tbody>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Facebook Icon'); ?>::<?php echo JText::_('Upload Facbook Icon.'); ?>"><?php echo JText::_('Facebook Icon'); ?></span>
								</td>
								<td>
									<input type="file" name="facebookicon" id="facebookicon" />
								</td>
							</tr>
							<?php if($config->facebookicon){
								?>
								<tr>
									<td></td>
									<td>
									<img src="<?php echo $this->baseurl; ?>/components/com_mojoom/images/<?php echo $config->facebookicon; ?>" alt="logo" class="editlinktip hasTip" title="<?php echo JText::_('Selected Facebook icon'); ?>::<?php echo JText::_('Currently selected Facebook icon.'); ?>"/>
									</td>
								</tr>	
							 <?php } ?>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Facebook Link'); ?>::<?php echo JText::_('Upload Facbook Link.'); ?>"><?php echo JText::_('Facebook Link'); ?></span>
								</td>
								<td>
									<input class="text_area" type="text" size="80" name="facebooklink" id="facebooklink" 
					           value="<?php if($config->facebooklink){ echo $config->facebooklink; } ?>"/>
								</td>
							</tr>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Twitter Icon'); ?>::<?php echo JText::_('Upload Twitter Icon.'); ?>"><?php echo JText::_('Twitter Icon'); ?></span>
								</td>
								<td>
									<input type="file" name="twittericon" id="twittericon" />
								</td>
							</tr>
							<?php if($config->twittericon){
								?>
								<tr>
									<td></td>
									<td>
									<img src="<?php echo $this->baseurl; ?>/components/com_mojoom/images/<?php echo $config->twittericon; ?>" alt="logo" class="editlinktip hasTip" title="<?php echo JText::_('Selected Twitter icon'); ?>::<?php echo JText::_('Currently selected Twitter icon.'); ?>"/>
									</td>
								</tr>	
							 <?php } ?>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Twitter Link'); ?>::<?php echo JText::_('Upload Twitter Link.'); ?>"><?php echo JText::_('Twitter Link'); ?></span>
								</td>
								<td>
									<input class="text_area" type="text" size="80" name="twitterlink" id="twitterlink" 
					           value="<?php if($config->twitterlink){ echo $config->twitterlink; } ?>"/>
								</td>
							</tr>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Linkedin Icon'); ?>::<?php echo JText::_('Upload Linkedin Icon.'); ?>"><?php echo JText::_('Linkedin Icon'); ?></span>
								</td>
								<td>
									<input type="file" name="linkedinicon" id="linkedinicon" />
								</td>
							</tr>
							<?php if($config->linkedinicon){
								?>
								<tr>
									<td></td>
									<td>
									<img src="<?php echo $this->baseurl; ?>/components/com_mojoom/images/<?php echo $config->linkedinicon; ?>" alt="logo" class="editlinktip hasTip" title="<?php echo JText::_('Selected Linkedin icon'); ?>::<?php echo JText::_('Currently selected Linkedin icon.'); ?>"/>
									</td>
								</tr>	
							 <?php } ?>
							<tr>
								<td class="key">
									<span class="editlinktip hasTip" title="<?php echo JText::_('Linkedin Link'); ?>::<?php echo JText::_('Upload Linkedin Link.'); ?>"><?php echo JText::_('Linkedin Link'); ?></span>
								</td>
								<td>
									<input class="text_area" type="text" size="80" name="linkedinlink" id="linkedinlink" 
					           value="<?php if($config->linkedinlink){ echo $config->linkedinlink; } ?>"/>
								</td>
							</tr>

						</tbody>
					</table>
				</td>
			</tr>
				<!-- only visisble if the select is mytemplate -->
				</tbody>
			</table>
		</fieldset>
		<fieldset class="adminform">
		<legend><?php echo JText::_('iPhone/iPad Default Template Menu Settings'); ?></legend>
		<table class="admintable" cellspacing="1">
			<tbody>
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('HOMEPAGE'); ?>::<?php echo JText::_('SET_HOME_PAGE'); ?>"><?php echo JText::_('HOMEPAGE'); ?></span>
					</td>
					<td>
					<input class="text_area" type="text" size="80" name="iphonehomepage" id="iphonehomepage" readonly="readonly"
					           value="<?php if($config->iphonehomepage){ echo $config->iphonehomepage; } ?>"/>
							   </td>
				</tr>
				<tr>
				<td></td>
				<td>
				
				<?php // setting up the homepage....
					$menuoptions = $this->menuoptions();
				 	echo JHTML::_('select.genericlist', $menuoptions, 'iphone_tmphomepage', 'size="7" onchange="document.getElementById(\'iphonehomepage\').value=this.value" ', 'value', 'text',$config->iphonehomepage); ?>
				</td>
				</tr>
				<!-- setting up the profile page -->
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('PROFILE_PAGE'); ?>::<?php echo JText::_('LINK_FOR_2ND_MENU'); ?>"><?php echo JText::_('PROFILE_PAGE'); ?></span>
					</td>
					<td>
					<input class="text_area" type="text" size="80" name="iphoneprofilepage" id="iphoneprofilepage" readonly="readonly"
					           value="<?php if($config->iphoneprofilepage){ echo $config->iphoneprofilepage; } ?>"/>
							   </td>
				</tr>
				<tr>
				<td></td>
				<td>
				
				<?php // setting up the profilepage....
				 	echo JHTML::_('select.genericlist', $menuoptions, 'iphone_tmpprofilepage', 'size="7" onchange="document.getElementById(\'iphoneprofilepage\').value=this.value" ', 'value', 'text',$config->iphoneprofilepage); ?>
				</td>
				</tr>
				<!-- setting up the profile page -->
				<!-- setting up the About us page -->
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('ABOUT_US_PAGE'); ?>::<?php echo JText::_('LINK_FOR_3RD_MENU'); ?>"><?php echo JText::_('ABOUT_US_PAGE'); ?></span>
					</td>
					<td>
					<input class="text_area" type="text" size="80" name="iphoneaboutuspage" id="iphoneaboutuspage" readonly="readonly"
					           value="<?php if($config->iphoneaboutuspage){ echo $config->iphoneaboutuspage; } ?>"/>
							   </td>
				</tr>
				<tr>
				<td></td>
				<td>
				<?php // setting up the iphoneaboutuspage....
				 	echo JHTML::_('select.genericlist', $menuoptions, 'iphone_tmpaboutuspage', 'size="7" onchange="document.getElementById(\'iphoneaboutuspage\').value=this.value" ', 'value', 'text',$config->iphoneaboutuspage); ?>
				</td>
				</tr>
				<!-- setting up the About us page -->
				<!-- setting up the More page -->
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('MORE'); ?>::<?php echo JText::_('LINK_FOR_4TH_MENU'); ?>"><?php echo JText::_('MORE'); ?></span>
					</td>
					<td>
					<input class="text_area" type="text" size="80" name="iphonemorepage" id="iphonemorepage" readonly="readonly"
					           value="<?php if($config->iphonemorepage){ echo $config->iphonemorepage; } ?>"/>
							   </td>
				</tr>
				<tr>
				<td></td>
				<td>
				<?php // setting up the iphoneaboutuspage....
				 	echo JHTML::_('select.genericlist', $menuoptions, 'iphone_tmpmorepage', 'size="7" onchange="document.getElementById(\'iphonemorepage\').value=this.value" ', 'value', 'text',$config->iphonemorepage); ?>
				</td>
				</tr>
				<!-- setting up the More page -->
			</tbody>
			</table>
		</fieldset>
		<fieldset class="adminform">
		<legend><?php echo JText::_('iPhone/iPad Default Template Modules Settings'); ?></legend>
		<table class="admintable" cellspacing="1">
			<tbody>
				<!-- now the three module positions -->
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('1ST_MODULE_POSITION_COMPONENT'); ?>::<?php echo JText::_('NAME_1ST_MODULE_POSITION_COMPONENT'); ?>"><?php echo JText::_('1ST_MODULE_POSITION_COMPONENT'); ?></span>
					</td>
					<td>
					<select name="tmpl_iphone_module1" id="tmpl_iphone_module1">
						<?php 
							foreach($this->positions as $position)
							{
								?>
								<option value="<?php echo $position; ?>" <?php if($config->tmpl_iphone_module1 == $position){ ?> selected="selected" <?php } ?>><?php echo $position; ?></option>
								<?php
							}
						?>
					</select>					
					</td>
				</tr>
				<tr><?php echo $return; ?>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('2ND_MODULE_POSITION_COMPONENT'); ?>::<?php echo JText::_('NAME_2ND_MODULE_POSITION_COMPONENT'); ?>"><?php echo JText::_('2ND_MODULE_POSITION_COMPONENT'); ?></span>
					</td>
					<td>
					<select name="tmpl_iphone_module2" id="tmpl_iphone_module2">
						<?php 
							foreach($this->positions as $position)
							{ 
								?>
								<option value="<?php echo $position; ?>" <?php if($config->tmpl_iphone_module2 == $position){ ?> selected="selected" <?php } ?>><?php echo $position; ?></option>
								<?php
							}
						?>
					</select>
					</td>
				</tr>
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('3RD_MODULE_POSITION_COMPONENT'); ?>::<?php echo JText::_('NAME_3RD_MODULE_POSITION_COMPONENT'); ?>"><?php echo JText::_('3RD_MODULE_POSITION_COMPONENT'); ?></span>
					</td>
					<td>
					<select name="tmpl_iphone_module3" id="tmpl_iphone_module3">
						<?php 
							foreach($this->positions as $position)
							{
								?>
								<option value="<?php echo $position; ?>" <?php if($config->tmpl_iphone_module3 == $position){ ?> selected="selected" <?php } ?>><?php echo $position; ?></option>
								<?php
							}
						?>
					</select>
					</td>
				</tr>
				<!-- now the three module positions -->
			</tbody>
			</table>
		</fieldset>
		<fieldset class="adminform">
		<legend><?php echo JText::_('iPhone/iPad Default Template Footer Settings'); ?></legend>
		<table class="admintable" cellspacing="1">
			<tbody>
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('FOOTER_TEXT'); ?>::<?php echo JText::_('WRITE_COPYRIGHT_FOOTER_TEXT'); ?>"><?php echo JText::_('FOOTER_TEXT'); ?></span>
					</td>
					<td>
					<textarea class="text_area" rows="3" cols="45" name="iphonefooter" id="iphonefooter"><?php if($config->iphonefooter){ echo $config->iphonefooter; } ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key"><span class="editlinktip hasTip"
					                      title="<?php echo JText::_('BACK_TOP_LINK'); ?>::<?php echo JText::_('ENABLE_BACK_TOP_LINK'); ?>"><?php echo JText::_('BACK_TOP_LINK'); ?></span>
					</td>
					<td>
						<input type="radio" name="iphonebacktotop" id="iphonebacktotop" value="1"  <?php if($config->iphonebacktotop == 1){ ?> checked="checked" <?php } ?>/>Yes
						<input type="radio" name="iphonebacktotop" id="iphonebacktotop" value="0" <?php if($config->iphonebacktotop == 0){ ?> checked="checked" <?php } ?>/>No
					</td>
				</tr>
				
				</tbody>
			</table>
		</fieldset>

		<?php
		echo $tabs->endPanel();
		echo $tabs->endPane();
		?>

		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="id" id="id" value="<?php if(empty($config->id)){ echo 0; }else{ echo $config->id; }?>" />
		<input type="hidden" name="controller" value="mojoomconfig" />
		

		</form>
		<script type="text/javascript" src="<?php echo JURI::root(true);?>/includes/js/overlib_mini.js"></script>