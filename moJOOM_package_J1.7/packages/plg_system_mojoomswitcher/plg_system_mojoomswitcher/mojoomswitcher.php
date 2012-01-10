<?php
defined('_JEXEC') or die('Restricted access');
ini_set('display_errors','0');
jimport('joomla.plugin.plugin');
class plgSystemMojoomSwitcher extends JPlugin
{
	function plgSystemMojoomSwitcher(& $subject)
	{
		parent::__construct($subject);
	}
	function onAfterInitialise()
	{
		$mainframe = JFactory::getApplication();
		if($mainframe->isAdmin()) // don't use MobileJoomla in backend
			return;
	}
	function onAfterRoute()
	{
		$mainframe = JFactory::getApplication();
		if($mainframe->isAdmin()) // don't use this in backend
			return;
		require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
		require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');
		$db =& JFactory::getDBO();
		$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		if ($browser == true)  { 
			$query = "select iphone_template,iphonehomepage,iphoneprofilepage,iphoneaboutuspage,iphonemorepage from #__mojoom_config where id=1";
			$db->setQuery($query);
			$rs = $db->loadObject();
			$template = $rs->iphone_template;
			//Set template
				if($template)
				{
					$mainframe->setUserState('setTemplate', $template);
					$mainframe->setTemplate($template);
				}
		}
		
		// do the menu manipulation only if tehe current template is set to mytemplate for that get the template name
		
		$currenttemplate = $mainframe->getTemplate();
		if($currenttemplate == 'mojoom') {
			/** @var JMenuSite $menu */
			$menu =& JSite::getMenu();
			//print_r($menu);
			/*foreach($menu->_items as $mitem)
			{
				if($mitem->name == 'Profile')
				{
					$idprofile = $mitem->id;
				}
			}*/
			
			// get the Home page parameter
			$default = $menu->getDefault();
			$home = $default->query;
			$home['Itemid'] = $default->id;
			
			
			/*print_r ($default);
			echo "<br><br>";
			print_r ($home);
			echo "<br><br>";*/
			
			//now get the currently active menu item but first check the joomla version coz getActive is not supported in joomla1.6
			
			$currentmenuitem = $menu->getActive();
			/*print_r($currentmenuitem);*/
			$currentmenuitemname = $currentmenuitem->name;
			// check if the current item is profile?
			if($currentmenuitemname == 'Profile')
			{
				$profile = $currentmenuitem->query;
				$profile['Itemid'] = $currentmenuitem->id;
				//print_r($profile);
			}
			// check if the current item is About Us?
			if($currentmenuitemname == 'About us')
			{
				$aboutus = $currentmenuitem->query;
				$aboutus['Itemid'] = $currentmenuitem->id;
				//print_r($profile);
			}
			// check if the current item is More?
			if($currentmenuitemname == 'More')
			{
				$more = $currentmenuitem->query;
				$more['Itemid'] = $currentmenuitem->id;
				//print_r($profile);
			}
				
			// get the link chosen by the user for the homepage and the profilepage
			if($rs->iphonehomepage != ''){$homepage = $rs->iphonehomepage;}
			if($rs->iphoneprofilepage != ''){$profilepage = $rs->iphoneprofilepage;}
			if($rs->iphoneaboutuspage != ''){$aboutuspage = $rs->iphoneaboutuspage;}
			if($rs->iphonemorepage != ''){$morepage = $rs->iphonemorepage;}
				
			
			if(substr($homepage, 0, 10) == 'index.php?')
			{
				
				parse_str(substr($homepage, 10), $mj_home);
				if(isset($mj_home['Itemid']))
				{
					$mj_home_Itemid = (int)$mj_home['Itemid'];
					$menu->setDefault($mj_home_Itemid);
				}
					
			}
			if(substr($profilepage, 0, 10) == 'index.php?')
			{
				
				parse_str(substr($profilepage, 10), $mj_profile);
				if(isset($mj_profile['Itemid']))
				{
					$mj_profile_Itemid = (int)$mj_profile['Itemid'];
					$menu->setActive($mj_profile_Itemid);
				}
					
			}
			if(substr($aboutuspage, 0, 10) == 'index.php?')
			{
				
				parse_str(substr($aboutuspage, 10), $mj_aboutus);
				if(isset($mj_aboutus['Itemid']))
				{
					$mj_aboutus_Itemid = (int)$mj_aboutus['Itemid'];
					$menu->setActive($mj_aboutus_Itemid);
				}
					
			}
			if(substr($morepage, 0, 10) == 'index.php?')
			{
				
				parse_str(substr($morepage, 10), $mj_more);
				if(isset($mj_more['Itemid']))
				{
					$mj_more_Itemid = (int)$mj_more['Itemid'];
					$menu->setActive($mj_more_Itemid);
				}
					
			}
			$current = $_GET;
			if($current == $home)
			{
				
				if($homepage)
				{
					if(isset($mj_home_Itemid))
					{
						global $Itemid;
						$Itemid = $mj_home_Itemid;
						$menu->setActive($Itemid);
						$mainframe->authorize($Itemid);
					}
	
					$_SERVER['REQUEST_URI'] = JURI::base(true).'/'.$homepage;
					if(isset($mj_home))
					{
						$_SERVER['QUERY_STRING'] = substr($homepage, 10);
						foreach($current as $key => $val) //clear old variables
						{
							unset($_REQUEST[$key]);
							unset($_GET[$key]);
						}
						JRequest::set($mj_home, 'get');
					}
					else
					{
						$url = 'http';
						$url .= (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])
								 && (strtolower($_SERVER['HTTPS'])!='off'))
								? 's' : '';
						$url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
						$uri = new JURI($url);
						$router =& $mainframe->getRouter();
						$result = $router->parse($uri);
						JRequest::set($result, 'get');
					}
				}//end of if($homepage)
			}//end of if current=homepage
			else if($current == $profile)
			{
				
				if($profilepage)
				{
					if(isset($mj_profile_Itemid))
					{
						global $Itemid;
						$Itemid = $mj_profile_Itemid;
						$menu->setActive($Itemid);
						$mainframe->authorize($Itemid);
					}
	
					$_SERVER['REQUEST_URI'] = JURI::base(true).'/'.$profilepage;
					if(isset($mj_profile))
					{
						$_SERVER['QUERY_STRING'] = substr($profilepage, 10);
						foreach($current as $key => $val) //clear old variables
						{
							unset($_REQUEST[$key]);
							unset($_GET[$key]);
						}
						JRequest::set($mj_profile, 'get');
					}
					else
					{
						$url = 'http';
						$url .= (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])
								 && (strtolower($_SERVER['HTTPS'])!='off'))
								? 's' : '';
						$url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
						$uri = new JURI($url);
						$router =& $mainframe->getRouter();
						$result = $router->parse($uri);
						JRequest::set($result, 'get');
					}
				}//end of if($profilepage)
			}//end of else if($current == $profile)
			else if($current == $aboutus)
			{
				
				if($aboutuspage)
				{
					if(isset($mj_aboutus_Itemid))
					{
						global $Itemid;
						$Itemid = $mj_aboutus_Itemid;
						$menu->setActive($Itemid);
						$mainframe->authorize($Itemid);
					}
	
					$_SERVER['REQUEST_URI'] = JURI::base(true).'/'.$aboutuspage;
					if(isset($mj_aboutus))
					{
						$_SERVER['QUERY_STRING'] = substr($aboutuspage, 10);
						foreach($current as $key => $val) //clear old variables
						{
							unset($_REQUEST[$key]);
							unset($_GET[$key]);
						}
						JRequest::set($mj_aboutus, 'get');
					}
					else
					{
						$url = 'http';
						$url .= (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])
								 && (strtolower($_SERVER['HTTPS'])!='off'))
								? 's' : '';
						$url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
						$uri = new JURI($url);
						$router =& $mainframe->getRouter();
						$result = $router->parse($uri);
						JRequest::set($result, 'get');
					}
				}//end of if($aboutuspage)
			}//end of else if($current == $aboutus)
			else if($current == $more)
			{
				
				if($morepage)
				{
					if(isset($mj_more_Itemid))
					{
						global $Itemid;
						$Itemid = $mj_more_Itemid;
						$menu->setActive($Itemid);
						$mainframe->authorize($Itemid);
					}
	
					$_SERVER['REQUEST_URI'] = JURI::base(true).'/'.$morepage;
					if(isset($mj_more))
					{
						$_SERVER['QUERY_STRING'] = substr($morepage, 10);
						foreach($current as $key => $val) //clear old variables
						{
							unset($_REQUEST[$key]);
							unset($_GET[$key]);
						}
						JRequest::set($mj_more, 'get');
					}
					else
					{
						$url = 'http';
						$url .= (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])
								 && (strtolower($_SERVER['HTTPS'])!='off'))
								? 's' : '';
						$url .= '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
						$uri = new JURI($url);
						$router =& $mainframe->getRouter();
						$result = $router->parse($uri);
						JRequest::set($result, 'get');
					}
				}//end of if($morepage)
			}//end of else if($current == $more)
			else
			{
				$menu->setActive($idprofile);
				$mainframe->authorize($idprofile);
			}
		}// main template checking condition finish
	}
}