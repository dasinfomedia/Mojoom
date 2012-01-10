<?php  
/**
 * Profile Wall/Recent Activities Model for Mojooom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');

class MojoomModelWall1 extends JModel
{

	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
	}  
	 
	function getHTML( $actor, $target, $date = null, $maxEntry=0 , $type = '', $idprefix = '', $showActivityContent = true , $showMoreActivity = false , $exclusions = null , $displayArchived = false )
	{	
		$html		= '';
		$numLines 	= 0;
		$maxList		= ($maxEntry == 0) ? 20 : $maxEntry;
		$htmlData	= array();
		$data			= $this->_getData($actor, $target, $date, $maxList, $type , $exclusions , $displayArchived );
		return $data;
		
	}
	
	function _getData($actor, $target, $date = null, $maxEntry=20 , $type = '' , $exclusions = null , $displayArchived = false )
	{
	
		$html 		= '';
		$numLines 	= 0;
		$htmlData 	= array();
		$rows = $this->_getActivities( $actor, $target, $date, $maxEntry , 1 , $exclusions , $displayArchived );
		
		$day = -1;
		// Initialize exclusions variables.
		$exclusions		= is_array( $exclusions ) ? $exclusions : array();
		
		// Inject additional properties for processing

		for($i = 0; $i < count($rows); $i++) 
		{
			$row			=& $rows[$i];
			// A 'used' activities = activities that has been aggregated
			$row->used 		= false;

		}
		$dayinterval 	= 1;
		$lastTitle 		= '';
		
		for($i = 0; $i < count($rows) && (count($htmlData) <= $maxEntry ); $i++) 
		{

			$row		= $rows[$i];
			$oRow		=& $rows[$i];

			// store aggregated activities
			$oRow->activities = array();

			if(!$row->used && count($htmlData) <= $maxEntry )
			{
				$oRow	=& $rows[$i];
				if(!isset($row->used))
				{
					$row->used = false;
				}
				if($day != $row->daydiff)
				{
					$act		= new stdClass();
					$act->type	= 'content';
					$day		= $row->daydiff;
					if($day == 0)
					{
						$act->title = JText::_('TODAY');
					}
					else if($day == 1)	
					{
						$act->title = JText::_('yesterday');
					}
					else if($day < 7)
					{
						$act->title = JText::sprintf('DAYS AGO', $day);
					}
					else if(($day >= 7) && ($day < 30))
					{
						$dayinterval = 7;						
						$act->title = (intval($day/$dayinterval) == 1 ? JText::_('1 week ago') : JText::sprintf('WEEKS AGO', intval($day/$dayinterval)));
					}	
					else if(($day >= 30))
					{
						$dayinterval = 30;

						$act->title = (intval($day/$dayinterval) == 1 ? JText::_('1 month ago') : JText::sprintf('MONTHS AGO', intval($day/$dayinterval)));

					}
					// set to a new 'title' type if this new one has a new title
					// only add if this is a new title
					if($act->title != $lastTitle)
					{
						$lastTitle 	= $act->title;
						$act->type 	= 'title'; 
						$htmlData[] = $act;
					}
				}
				$act = new stdClass();
				$act->type = 'content';
				$title = $row->title;
				$app = $row->app;
				$cid = $row->cid;
				$actor = $row->actor;
				
				for($j = $i; ($j < count($rows)) && ($row->daydiff == $day); $j++)
				{
					$row = $rows[$j];			
					// we aggregate stream that has the same content on the same day.
					// we should not however aggregate content that does not support
					// multiple content. How do we detect? easy, they don't have
					// {multiple} in the title string
					// However, if the activity is from the same user, we only want 
					// to show the laste acitivity

					if( ($row->daydiff == $day) && ($row->title  == $title) && ($app == $row->app) && ($cid == $row->cid ) && ((JString::strpos($row->title, '{/multiple}') !== FALSE ) || ($row->actor == $actor )))
					{

						// @rule: If an exclusion is added, we need to fetch activities without these items.
						// Aggregated activities should also be excluded.
						$exclusions[]	= $row->id;
						$row->used 		= true;
						$oRow->activities[] = $row;
					}

				}

				$app	= !empty($oRow->app) ? $this->_appLink($oRow->app, $oRow->actor, $oRow->target) : ''; 

				$oRow->title	= JString::str_ireplace('{app}', $app, $oRow->title);    
				$favicon = '';
				// this should not really be empty
				if(!empty($oRow->app))
				{
				    // check if the image icon exist in template folder
				    if ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'templates' . DS . 'blackout' . DS . 'images' . DS . 'favicon' . DS . $oRow->app.'.png') )
				    {
				        $favicon = JURI::root(). 'components/com_community/templates/blackout/images/favicon/'.$oRow->app.'.png';
					}
					else
					{
					    // check if the image icon exist in asset folder
						if ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'assets' . DS . 'favicon' . DS . $oRow->app.'.png') )
						{
							$favicon = JURI::root(). 'components/com_community/assets/favicon/'.$oRow->app.'.png';
						}
						elseif ( JFile::exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'plugins' . DS . 'community' . DS . $oRow->app . DS . 'favicon.png') )
						{
							$favicon = JURI::root(). 'plugins/community/'.$oRow->app.'/favicon.png';
						}
						else
						{
                            $favicon = JURI::root(). 'components/com_community/assets/favicon/default.png';
						}
					}
				}

				else
				{
				    $favicon = JURI::root(). 'components/com_community/assets/favicon/default.png';
				}

				$act->favicon = $favicon;

				$target = $this->_targetLink($oRow->target, true );

				$oRow->title	= JString::str_ireplace('{target}', $target, $oRow->title);

				if(count($oRow->activities) > 0)
				{
					
					// multiple
					$actorsLink = '';					
					foreach( $oRow->activities as $actor )
					{
						if(empty($actorsLink))
							$actorsLink = $this->_actorLink(intval($actor->actor));
						else {
							// only add if this actor is NOT already linked
							$alink = $this->_actorLink(intval($actor->actor));
							$pos = strpos($actorsLink, $alink);
							if ($pos === false) {
								$actorsLink .= ', '.$alink;
							}
						}

					}
					$actorLink = $this->_actorLink(intval($oRow->actor));
					$count = count($oRow->activities);
					$oRow->title 	= preg_replace('/\{single\}(.*?)\{\/single\}/i', '', $oRow->title);
					$search  		= array('{multiple}','{/multiple}');
					$oRow->title	= JString::str_ireplace($search, '', $oRow->title);
					$oRow->title	= JString::str_ireplace('{actors}'	, $actorsLink, $oRow->title);
					$oRow->title	= JString::str_ireplace('{actor}'	, $actorLink, $oRow->title);
					$oRow->title	= JString::str_ireplace('{count}'	, $count, $oRow->title);
					
				}
				else
				{
					// single
					$actorLink = $this->_actorLink(intval($oRow->actor));
					$oRow->title = preg_replace('/\{multiple\}(.*)\{\/multiple\}/i', '', $oRow->title);
					$search  = array('{single}','{/single}');
					$oRow->title	= JString::str_ireplace($search, '', $oRow->title);
					$oRow->title	= JString::str_ireplace('{actor}', $actorLink, $oRow->title);
					
				}

				// @rule: If an exclusion is added, we need to fetch activities without these items.

				// Compile exclusion lists.

				/******************************************$exclusions[]	= $oRow->id; */

// 26-12-2012 M change.
// comment display in wall 
$rowselect=$oRow->app;
$flagiswall = 0;
if($rowselect == 'walls'){
	$flagiswall = 1;
	$rowid=$oRow->id;
	$query = "SELECT cw.comment  FROM jos_community_activities as ca join  jos_community_wall as cw WHERE ca.cid=cw.id and ca.id=".$rowid;
	$this->_db->setQuery( $query );
	$result	= $this->_db->loadObject();
	$comments= $result->comment;
	
}

				// If the param contains any data, replace it with the content
				preg_match_all("/{(.*?)}/", $oRow->title, $matches, PREG_SET_ORDER);
				
				if(!empty( $matches )) 
				{
					$params = new JParameter( $oRow->params );
					//print_r($oRow->title);
					//print_r($params);
					$ctr=1;
					
					foreach ($matches as $val) 
					{	
						//print_r($val);
						$replaceWith = $params->get($val[1], null);
						
						//print_r($replaceWith);
						$newurl1 = 'index.php?option=com_mojoom&';
						
						// now first change the url for our mojoom component :: GROUP
						switch($oRow->app)
						{
							case 'groups':
										$act1 = $params->get('action',null);
										switch ($act1)
										{
											case 'group.wall.create':
												$str1rr = explode('?',$replaceWith);
												$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
												$qrystrngarr = explode('&',$qrystrng);
												$arr = explode('=',$qrystrngarr[2]);
												$newurl1 = $newurl1 . '&controller=groups&task=groupwall&user_id='.JFactory::getUser()->id . '&group_id='.$arr[1];
												break;
											
											case 'group.create':
												$str1rr = explode('?',$replaceWith);
												$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
												$qrystrngarr = explode('&',$qrystrng);
												$arr = explode('=',$qrystrngarr[2]);
												$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
												break;
												
											case 'group.discussion.create':
									
												if($ctr == 1)
												{
													$str1rr = explode('?',$replaceWith);
													$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
													$qrystrngarr = explode('&',$qrystrng);
													$arr = explode('=',$qrystrngarr[2]);
													$arr1=explode('=',$qrystrngarr[3]);
													$newurl1 = $newurl1 . '&controller=groups&task=viewdiscussion&group_id='.$arr[1].'&topicid='.$arr1[1];
												}
												else if($ctr == 2)
												{
													$newurl1 = $replaceWith;
												}
												else if($ctr == 3)
												{
													$str1rr = explode('?',$replaceWith);
													$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
													$qrystrngarr = explode('&',$qrystrng);
													$arr = explode('=',$qrystrngarr[2]);
													$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
												}
												$ctr=$ctr+1;
												
												break;
											
											case 'group.discussion.reply':
												
												if($ctr == 1)
												{
													$str1rr = explode('?',$replaceWith);
													$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
													$qrystrngarr = explode('&',$qrystrng);
													$arr = explode('=',$qrystrngarr[2]);
													$arr1=explode('=',$qrystrngarr[3]);
													$newurl1 = $newurl1 . '&controller=groups&task=viewdiscussion&group_id='.$arr[1].'&topicid='.$arr1[1];
												}
												else if($ctr == 2)
												{
													$str1rr = explode('?',$replaceWith);
													$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
													$qrystrngarr = explode('&',$qrystrng);
													$arr = explode('=',$qrystrngarr[2]);
													$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
												}
												else if($ctr == 3)
												{
													$newurl1 = $replaceWith;
												}
												$ctr=$ctr+1;
												
												break;
												
											default:
												//now chk for individual
													// for joining the group
													if(	stristr($oRow->title,'JOINED THE GROUP') )
													{
														$str1rr = explode('?',$replaceWith);
														$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
														$qrystrngarr = explode('&',$qrystrng);
														$arr = explode('=',$qrystrngarr[2]);
														$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
													}
													// for uploading a new avatar for the group
													if(	stristr($oRow->title,'UPLODED NO AVTAR FOR GROUP') )
													{
														$str1rr = explode('?',$replaceWith);
														$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
														$qrystrngarr = explode('&',$qrystrng);
														$arr = explode('=',$qrystrngarr[2]);
														$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
													}
													// for added a new bulletin
													if(	stristr($oRow->title,'ADDED NEW BULLETIN') )
													{
														$str1rr = explode('?',$replaceWith);
														$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
														$qrystrngarr = explode('&',$qrystrng);
														$arr = explode('=',$qrystrngarr[2]);
														$newurl1 = $newurl1 . '&controller=groups&task=groupbulletin&group_id='.$arr[1];
													}
													// for updating the group detail
													if(	stristr($oRow->title,'UPDATED GROUP') )
													{
														$str1rr = explode('?',$replaceWith);
														$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
														$qrystrngarr = explode('&',$qrystrng);
														$arr = explode('=',$qrystrngarr[2]);
														$newurl1 = $newurl1 . '&controller=groups&task=viewgroup&group_id='.$arr[1];
													}
													
										}
										break;
								case 'walls':
										
										break;
								case 'photos':
										$str1rr = explode('?',$replaceWith);
										$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
										$qrystrngarr = explode('&',$qrystrng);
										//get the actor
										$uid = $oRow->actor; 
										$albumidarr = explode('=',$qrystrngarr[2]);
										// group photo
										if(	stristr($oRow->title,'NEW PHOTOS IN GROUP ALBUM') )
										{
											$newurl1 = $newurl1 . '&view=group_photos&album_id='.$albumidarr[1].'&user_id='.$uid;
										}
										else
										{
											$newurl1 = $newurl1 . '&view=photos&album_id='.$albumidarr[1].'&user_id='.$uid;
										}
										break;
								case 'videos':
										$str1rr = explode('?',$replaceWith);
										$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
										$qrystrngarr = explode('&',$qrystrng);
										//get the actor
										$uid = $oRow->actor;
										$grpidarr = explode('=',$qrystrngarr[2]); 
										// here there is no discrimination for grp vid and profile vid so we need to find it by the url
										if( stristr($replaceWith,'groupid') )
										{
											$newurl1 = $newurl1 . '&controller=groups&task=groupvideo&user_id='.$uid.'&group_id='.$grpidarr[1];
										}
										else
										{
											$newurl1 = $newurl1 . '&view=videos';
										}
										break;
								case 'events':
									$act1 = $params->get('action',null);
									switch ($act1)
									{
										case 'event.join':
											$str1rr = explode('?',$replaceWith);
											$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
											$qrystrngarr = explode('&',$qrystrng);
											$arr = explode('=',$qrystrngarr[2]);
											$newurl1 = $newurl1 . '&controller=event&task=viewevent&event_id='.$arr[1];
											break;
										case 'events.create':
											$str1rr = explode('?',$replaceWith);
											$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
											$qrystrngarr = explode('&',$qrystrng);
											$arr = explode('=',$qrystrngarr[2]);
											$newurl1 = $newurl1 . '&controller=event&task=viewevent&event_id='.$arr[1];
											break;
										case 'events.wall.create':
											$str1rr = explode('?',$replaceWith);
											$qrystrng = substr($str1rr[1],21,strlen($str1rr[1]));
											$qrystrngarr = explode('&',$qrystrng);
											$arr = explode('=',$qrystrngarr[2]);
											$newurl1 = $newurl1 . '&controller=event&task=viewevent&event_id='.$arr[1];
											
											
											
									}
									break;
											
						}
							
						
						//if the replacement start with 'index.php', we can CRoute it
						/*if( strpos($replaceWith, 'index.php') === 0){

							$replaceWith = CRoute::_($replaceWith);

						}

						*/

						if( !is_null( $replaceWith ) ) 

						{

							$oRow->title	= JString::str_ireplace($val[0], $newurl1, $oRow->title);

						}
						

					}

				}/************************************/

				
				$act->id 		= $oRow->id;
				//26-12-2012 M Change
				// display comment in wall pages. 
				if($flagiswall ==1){
					$act->title 	= $oRow->title . "<br>" . $comments;
				}
				else
				{
					$act->title 	= $oRow->title;
				}
				$act->actor 	= $oRow->actor;
				//$act->content	= $this->_getActivityContent( $oRow );
				

				$timeFormat		= '%I:%M %p';
				$dayFormat		= '%b %d';
				$date			= $this->_getDate($oRow->created);
				$createdTime = '';
				$createdTime	= $this->_timeLapse($date);

				$act->created 	= $createdTime;
				$act->createdDate = $date->toFormat(JText::_('DATE_FORMAT_LC2'));
				$act->app 		= $oRow->app;

				$htmlData[] = $act;

			}

		}

		$objActivity				= new stdClass();
		$objActivity->data			= $htmlData;
		$objActivity->exclusions	= empty( $htmlData ) ? false : implode( ',' , $exclusions );
		return $objActivity;
	}
	
	function _getActivities($userid='', $friends='', $afterDate = null, $maxEntries=20 , $respectPrivacy = true , $exclusions = null , $displayArchived = false )
	{

		$my = JFactory::getUser();
		$todayDate	= new JDate();

		// Oversampling, to cater for aggregated activities

		$maxEntries = ($maxEntries < 0) ? 0 : $maxEntries;

		$maxEntries = $maxEntries*8;



		$orWhere = array();

		$andWhere = array();

		$onActor = '';

		//default the 1st condition here so that if the date is null, it wont give sql error.

		if( !$displayArchived )
		{
			$andWhere[] = "`archived`=0";
		}

		if( $respectPrivacy )
		{

			// Add friends limits, but admin should be able to see all

			// @todo: should use global admin code check instead

			if($my->id == 0)
			{
				// for guest, it is enough to just test access <= 0

				$andWhere[] = "(a.`access` <= 10)";
			}
			elseif( !( $my->usertype == 'Super Administrator' || $my->usertype == 'Administrator' || $my->usertype == 'Manager' ))
			{

				$orWhere[] = "((a.`access` = 0) {$onActor})";
				$orWhere[] = "((a.`access` = 10) {$onActor})";
				$orWhere[] = "( (a.`access` = 20) AND ({$my->id} != 0)  {$onActor})";

				if($my->id != 0)
				{

					$orWhere[] = "( (a.access = 30) AND (a.actor = {$my->id}) {$onActor})";
					$orWhere[] = "( (a.access = 30) AND (a.actor IN (SELECT c.`connect_to`
							FROM `#__community_connection` as c
							WHERE
								c.`connect_from` = {$my->id}
							AND
								c.`status` = 1) ) {$onActor} )";
				}

			} 

		}

		if(!empty($userid))

		{

			//get the list of acitivity id in archieve table 1st.

			$subQuery	= 'SELECT b.`activity_id` FROM #__community_activities_hide as b WHERE b.`user_id` = '. $this->_db->Quote($userid);

			$this->_db->setQuery($subQuery);

			$subResult	= $this->_db->loadResultArray();

			$subString	= implode(',', $subResult);

		

			if( ! empty($subString))

				$andWhere[] = "a.`id` NOT IN ($subString) ";

	    }			

		

		$whereOr = implode(' OR ', $orWhere);

		$whereAnd = implode(' AND ', $andWhere);

		

		// Actors can also be your friends

		// We load 100 activities to cater for aggregated content

		$date	= $this->_getDate(); //we need to compare where both date with offset so that the day diff correctly.

		

		$sql = "SELECT a.*, TO_DAYS(".$this->_db->Quote($date->toMySQL(true)).") -  TO_DAYS( DATE_ADD(a.`created`, INTERVAL ".$date->getOffset()." HOUR ) ) as 'daydiff' "

			." FROM #__community_activities as a "

			." WHERE "

			." ( $whereOr ) AND "

			." $whereAnd ORDER BY a.`created` DESC LIMIT " . $maxEntries;				  

	

		// Remove the bracket if it is not needed

		$sql = JString::str_ireplace("WHERE  (  ) AND", ' WHERE ', $sql);

// 		echo $sql;exit;	

		$this->_db->setQuery( $sql );

		$result = $this->_db->loadObjectList();

		if($this->_db->getErrorNum()) {

			JError::raiseError( 500, $this->_db->stderr());

		}

		// @todo: write a plugin that return the html part of the whole system

		return $result;

	}
	
	function _getDate( $str = '' )
	{

		$mainframe	=& JFactory::getApplication();

		$extraOffset	= 0;

		$date	= new JDate($str);

		$my		=& JFactory::getUser();

		if(!$my->id){

			$date->setOffset($mainframe->getCfg('offset') + $extraOffset);

		} else{

			if(!empty($my->params)){

				$pos = JString::strpos($my->params, 'timezone');
			
				$offset = $mainframe->getCfg('offset') + $extraOffset;

				if ($pos === false) {

				   $offset = $mainframe->getCfg('offset') + $extraOffset;

				} 

				$date->setOffset($offset);

			} else

				$date->setOffset($mainframe->getCfg('offset') + $extraOffset);
		}
		return $date;
	}
	// 26-12-2012 M Change if friens wall post want to see then link.
	function _appLink($name, $actor = 0, $userid = 0)
	{
		
		//static $instances = array();

		//$my =& JFactory::getUser();
		if(empty($name))
			return '';
		//if( empty($instances[$id.$actor.$userid]) )
		//{
		$url = '';
		// @todo: check if this app exist
		if(true) {
			// if no target specified, we use actor
			if($userid == 0) 
				$userid= $actor;
				
			if( $userid != 0 && $name != 'profile' && $name != 'news_feed' && $name != 'photos' && $name != 'friends')
			{
				$url	= 'index.php?option=com_mojoom&view=wall1&user_id='.$userid;
				$url = '<a href="' . $url .'" >'. $this->_getAppTitle($name) . '</a>';
			}
			else
			{
				$url = $this->_getAppTitle($name);
			}
		}
		return $url;
	}
	function _getAppTitle($appname)
	{

		static $instances = array();
		if(empty($instances[$appname]))
		{

			$sql = "SELECT name FROM #__plugins WHERE `element`=". $this->_db->Quote($appname);
			$this->_db->setQuery($sql);
			$instances[$appname] = $this->_db->loadResult();

		}
		
		return $instances[$appname];
	}
	
	function _targetLink( $id, $onApp=false )
	{

		static $instances1 = array();
		if( empty($instances1[$id]) )
		{
		$my			=& JFactory::getUser();
		$linkName	= ($id==0)? false : true;
		$name = $this->_getUserName($id);
		// Wrap the name with link to his/her profile

		$html = $name;

		if($linkName)
		{
			$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$id;	
			$html = '<a href="'.$url.'">'.$name.'</a>';
		}
		$instances1[$id] = $html;
		}

		return $instances1[$id];

	}
	
	function _actorLink($id)
	{
		static $instances1 = array();
		if( empty($instances1[$id]))
		{
			$my			=& JFactory::getUser();
			$linkName	= ($id==0)? false : true;
			$name = $this->_getUserName($id);

			// Wrap the name with link to his/her profile
			$html		= $name;
			if($linkName)
			{
				$url	= 'index.php?option=com_mojoom&view=mojoom&user_id='.$id;
				$html = '<a href="'.$url.'" class="actor-link">'.$name.'</a>';
			}
			$instances1[$id] = $html;
		}
		return $instances1[$id];

	}
	function _getUserName($id)
	{
		$query = 'SELECT name,username FROM #__users WHERE id='.$id;
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObject();
		return $result->username;
	}
	function _timeLapse($date)
	{
		
		$now = new JDate();

		$dateDiff = $this->_timeDifference($date->toUnix(), $now->toUnix());
		if( $dateDiff['days'] > 0){
			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['days'])) ? 'DAYS AGO':'DAY AGO', $dateDiff['days']);

		}elseif( $dateDiff['hours'] > 0){

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['hours'])) ? 'HOURS AGO':'HOUR AGO', $dateDiff['hours']);

		}elseif( $dateDiff['minutes'] > 0){

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['minutes'])) ? 'MINUTES AGO':'MINUTE AGO', $dateDiff['minutes']);

		}else {

			$lapse = JText::sprintf( ($this->_isPlural($dateDiff['seconds'])) ? 'SECONDS AGO':'SECOND AGO', $dateDiff['seconds']);

		}

		return $lapse;
	}



	function _timeDifference( $start , $end )
	{
		if(is_string($start) && ($start != intval($start))){
			$start = new JDate($start);
			$start = $start->toUnix();
		}
		if(is_string($end) && ($end != intval($end) )){
			$end = new JDate($end);
			$end = $end->toUnix();
		}

		$uts = array();
	    $uts['start']      =    $start ;
	    $uts['end']        =    $end ;
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        else
	        {
	            trigger_error( JText::_("DATE TIME INFO"), E_USER_WARNING );
	        }

	    }
	    else
	    {
	        trigger_error( JText::_("INVALID DATE TIME"), E_USER_WARNING );
	    }
	    return( false );
	}
	
	function _isPlural($num)
	{
		return !$this->_isSingular($num);
	}
	
	function _isSingular($num)
	{

		$singularnumbers = 1;
		$singularnumbers = explode(',', $singularnumbers);
		return in_array($num, $singularnumbers);
	}
// 26-12-2012 M create.
// create function for wall posting 	
	function wall()
	{	
		
		$my = JFactory::getUser();
		// get data of post.
		$data = JRequest::get( 'post' );
		$da=$data['title'];
		$target=$data['user_id'];
		
		$ip=@$REMOTE_ADDR; 
		$wall = new stdClass();
			// insert data into jos_community_wall table.
			$wall->id 			= '';
			$wall->contentid	= $target;
			$wall->post_by		= $my->id;
			$wall->ip			= $ip;
			$wall->comment		= $da;
			$wall->date		    = gmdate('Y-m-d H:i:s');
			$wall->published	= 1;
			$wall->type			='user';
			$this->_db->insertObject('#__community_wall', $wall, 'id');
			$wall_id = $wall->id;
		
			
			/*$query = "SELECT usertype FROM #__users WHERE id=".$my->id;
			$this->_db->setQuery( $query );
			$result	= $this->_db->loadObject();
			$mytype= $result->usertype;	*/
			//echo $mytype;
			
			
			// Set the wall properties
			// insert data in jos_community_activities table.
			$wall1 = new stdClass();
			
			$wall1->id 			= '';
			$wall1->actor		= $my->id;
			$wall1->target		= $target;
			$wall1->title		= "{actor} wrote on {target}'s {app}";
			$wall1->content		= "{plugins,walls,getWallActivityContent}";
			$wall1->app			= 'walls';
			$wall1->cid			= $wall_id;
			$wall1->created		= gmdate('Y-m-d H:i:s');
			$wall1->points		= 1;
			$this->_db->insertObject('#__community_activities', $wall1, 'id');
			// @todo: set the ip address
	
		return $wall1->id;
		

	}

}
