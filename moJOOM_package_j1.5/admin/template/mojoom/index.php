<!DOCTYPE HTML PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width" />
	<meta name="apple-mobile-web-app-capable" content="yes" />

    <!-- for iphone only -->
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
    <jdoc:include type="head" />     
	<?php 
		ini_set("display_errors","0");
		require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
		require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
		$mainframe = JFactory::getApplication('site');
		$db =& JFactory::getDBO();
		$query = "SELECT iphonelogo, iphonetemplatetheme, iphonejoomlasearch, socialneticons, facebookicon, facebooklink, twittericon, twitterlink, linkedinicon, linkedinlink, tmpl_iphone_module1, tmpl_iphone_module2, tmpl_iphone_module3, iphonefooter, iphonebacktotop FROM #__mojoom_config WHERE id=1";
		$db->setQuery($query);
		$rs1 = $db->loadObject();
	?>
    <?php 
	////////////////////////////////////////////////////
	///// ipad detact and add css for ipad/////////////
	$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
	if ($browser == true)
	{ 
		if($rs1->iphonetemplatetheme == 'blue')
		{ 
		?>	
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/ipadblue.css" rel="stylesheet" type="text/css" /> 
		<?php }else if($rs1->iphonetemplatetheme == 'green'){?>
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/ipadgreen.css" rel="stylesheet" type="text/css" />      
		<?php }else if($rs1->iphonetemplatetheme == 'red'){
		?>
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/ipadred.css" rel="stylesheet" type="text/css" /> 
		<?php 
		}
	}
	////////////// end ipad///////////////////
	/////////////////////////////////////////
	///// android detact and add css for android/////////////
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(stripos($ua,'android') !== false) { // &amp;&amp; stripos($ua,'mobile') !== false) {
		if($rs1->iphonetemplatetheme == 'blue')
		{ 
		?>	
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/androidblue.css" rel="stylesheet" type="text/css" /> 
		<?php }else if($rs1->iphonetemplatetheme == 'green'){?>
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/androidgreen.css" rel="stylesheet" type="text/css" />      
		<?php }else if($rs1->iphonetemplatetheme == 'red'){
		?>
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/androidred.css" rel="stylesheet" type="text/css" /> 
		<?php 
		}
	}
	////////////// end android///////////////////
	?>
	<?php 
		if($rs1->iphonetemplatetheme == 'blue'){ 
	?>		
		<link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/blue.css" rel="stylesheet" type="text/css" /> 
	<?php }else if($rs1->iphonetemplatetheme == 'green'){?>
	    <link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/green.css" rel="stylesheet" type="text/css" />      
	<?php }else if($rs1->iphonetemplatetheme == 'red'){?>
	    <link href="<?php echo $this->baseurl; ?>/templates/mojoom/css/red.css" rel="stylesheet" type="text/css" /> 
	<?php } ?> 
	<?php
	JHTML::_('behavior.mootools');
	JHTML::_('behavior.modal');
	?>
<?php
if ($this->getBuffer('message')) : ?>
<script type="text/javascript">
window.addEvent('domready', function() {
var myel = new Element('a',{'href':'#error_info'});
SqueezeBox.fromElement(myel,{
handler:'adopt',
adopt:'error_info',
size: {x:245,y:'auto'},
});
});
</script>
<?php endif; ?>
</head>
<body id="bid">
<div id="main">
	<div id="header">
		<div id="logo">
		<?php 
			if($rs1->iphonelogo != ''){
				 // get the image size
				$size = getimagesize("administrator/components/com_mojoom/images/".$rs1->iphonelogo);
				$width = $size[0];
				$height = $size[1];
		?>
			<a href="<?php echo $this->baseurl; ?>"><img src="<?php echo $this->baseurl; ?>/administrator/components/com_mojoom/images/<?php echo $rs1->iphonelogo; ?>" height="<?php echo $height; ?>" width="<?php echo $width; ?>" alt="logo" style="float:left;" /></a>
		<?php 
			}	
			else
			{
			?>
			<a href="<?php echo $this->baseurl; ?>"><img src="<?php echo $this->baseurl; ?>/administrator/components/com_mojoom/images/logoimg.png" height="24px" width="58px" alt="logo" style="float:left;"/></a>
			<?php } ?>
		</div>
		<?php $user =& JFactory::getUser();
			if(!$user->guest)
			{ ?>
				
                <div id="welcomemsg">
					<p>Hi, <?php echo $user->username; ?>&nbsp;</p>
					<form action="index.php" name="profile" method="post" >
						<input type="image" src="<?php echo $this->baseurl; ?>/templates/mojoom/images/logout1.png" style="float:left;width:18px;height:20px;" />
						<input type="hidden" name="option" value="com_mojoom" />
						<input type="hidden" name="task" value="logout" />
						<input type="hidden" name="controller" value="mojoom" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</div>
			<?php
			}

		?>
		
		<?php if($rs1->iphonejoomlasearch == 1){
		?>
		<div id="search">
			<jdoc:include type="modules" name="user4" style="xhtml" />
		</div>	
		<?php } ?>
		<?php if($rs1->socialneticons == 1){
		// get the image size
				$fbsize = getimagesize("administrator/components/com_mojoom/images/".$rs1->facebookicon);
				$fbwidth = $fbsize[0];
				$fbheight = $fbsize[1];
				$twsize = getimagesize("administrator/components/com_mojoom/images/".$rs1->facebookicon);
				$twwidth = $twsize[0];
				$twheight = $twsize[1];
				$insize = getimagesize("administrator/components/com_mojoom/images/".$rs1->facebookicon);
				$inwidth = $insize[0];
				$inheight = $insize[1];
		?>
		<div id="socialnet">
			<a href="<?php echo $rs1->facebooklink; ?>" ><img src="<?php echo $this->baseurl; ?>/administrator/components/com_mojoom/images/<?php echo $rs1->facebookicon; ?>" height="<?php echo $fbheight; ?>" width="<?php echo $fbwidth; ?>"/></a>
			<a href="<?php echo $rs1->twitterlink; ?>" ><img src="<?php echo $this->baseurl; ?>/administrator/components/com_mojoom/images/<?php echo $rs1->twittericon; ?>" height="<?php echo $twheight; ?>" width="<?php echo $twwidth; ?>"/></a>
			<a href="<?php echo $rs1->linkedinlink; ?>" ><img src="<?php echo $this->baseurl; ?>/administrator/components/com_mojoom/images/<?php echo $rs1->linkedinicon; ?>" height="<?php echo $inheight; ?>" width="<?php echo $inwidth; ?>"/></a>
		</div>	
		<?php } ?>
		
	</div>
	<div id="main_menu">
		<jdoc:include type="modules" name="mojoom" />
	</div>
	
	<div id="content">
		<div style="display:none;">
			<div id="error_info"><jdoc:include type="message" /></div>
		</div>
		<?php $menu = &JSite::getMenu(); 
				  $currentMenuName = $menu->getActive()->name; 
				  $option = JRequest::getVar('option','');
		?>
		<?php if ($option == 'com_content' || $option == 'com_user' ) { ?>
			<div class="homepagecontainer">	
				<jdoc:include type="component" />
			</div>
		<?php } else { ?>
				<jdoc:include type="component" />
		<?php } ?>
	</div>
	<div id="modulesections">
		<?php 
		$menu = &JSite::getMenu(); 
		$currentMenuName = $menu->getActive()->name;
		if($menu->getActive() == $menu->getDefault()) {
			if(isset($rs1->tmpl_iphone_module1))
			{	// check the condition for multiple modules for same position
				?>
				<jdoc:include type="modules" name="<?php echo $rs1->tmpl_iphone_module1; ?>" style="xhtml" />
				<?php
			}
		
			if(isset($rs1->tmpl_iphone_module2))
			{	// check the condition for multiple modules for same position
				?>
				<jdoc:include type="modules" name="<?php echo $rs1->tmpl_iphone_module2; ?>" style="xhtml" />
				<?php
			}
		
			if(isset($rs1->tmpl_iphone_module3))
			{	// check the condition for multiple modules for same position
				?>
				<jdoc:include type="modules" name="<?php echo $rs1->tmpl_iphone_module3; ?>" style="xhtml" />
				<?php
			}
		}// home page 
		?>
	</div>
	<?php if($rs1->iphonefooter != '') {
	?>
	<div id="footer">
		<?php echo $rs1->iphonefooter; ?>
		<?php if($rs1->iphonebacktotop == 1) {
			?>
			<a class="backtop" href="#bid"><img alt="&raquo; Back To Top." src="components/com_mojoom/images/back to top.png"  /></a>
			<?php
		}
		?>
	</div>
	<?php 
	}
	?>
</div>
</body>
</html>
