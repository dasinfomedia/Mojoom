<?php
/**
 * Profile Edit Model for Mojoom Component
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelProfile_edit extends JModel 
{

	/**
	 * Constructor that retrieves the ID from the request
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the training identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function getProfile()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$db =& JFactory::getDBO();
		$query = "SELECT c.* FROM #__community_fields_values as c where c.user_id = '".$userid."' order by c.field_id asc";		
		$db->setQuery( $query );
		$profile = $db->loadObjectlist();
		return $profile;
	}
	
	function store()
	{	
		$row =& $this->getTable();
		$data = JRequest::get( 'post' );
			
		// Bind the form fields to the training table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the training record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}
	
	function profile_edit()
	{
		$data = JRequest::get( 'post' );
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		$browser = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		if($browser == false)
		{ 
			if($_FILES['profile_avatar'] != "")
			{
				$image = $this->uploadAvatar();
				$this->_n1;
				$this->_n2;
				if($image == true)
				{
				$query = "update #__community_users set avatar = '".$this->_n1."' , thumb = '".$this->_n2."' where userid = '".$userid."'";
				$db->setQuery( $query );
				$db->query();
				}
			}
		}
		else
		{
			$query = "update #__community_users set avatar = '".$data['profile_avatar']."' , thumb = '".$data['profile_avatar']."' where userid = '".$userid."'";
			$db->setQuery( $query );
			$db->query();
		}
		
		$id = array(1=>2,2=>3,3=>4,4=>6,5=>7,6=>8,7=>9,8=>10,9=>11,10=>12,11=>14,12=>15); 
		$data['b_date'] = $data['b_year'].'-'.$data['b_month'].'-'.$data['b_day'];
		$value = array(1=>$data['gender'],2=>$data['b_date'],3=>$data['aboutme'],4=>$data['mobile'],5=>$data['phone'],6=>$data['address'],7=>$data['state'],8=>$data['city'],9=>$data['country'],10=>$data['website'],11=>$data['university'],12=>$data['year']);
		$i=1;
			
		$qry = "select id from #__community_fields_values where user_id = '".$userid."'";
		//echo $qry;
		//exit;
		$db->setQuery( $qry );
		$uid = $db->loadResult();
		if($uid) {				
			while($id[$i] != "")
			{	
				$query = "update #__community_fields_values set value =  ". $db->Quote( $value[$i] )." where user_id = '".$data['id']."' and field_id = '".$id[$i]."'";			
				$db->setQuery( $query );
				$db->query();
				$i++;
			}
		}
		else {
			while($id[$i] != "")
			{
			$query = "insert into #__community_fields_values (`user_id`,`field_id`,`value`) values ('".$userid."','".$id[$i]."',".$db->Quote( $value[$i] ).")";			
			echo $query;
			
			$db->setQuery( $query );
			$db->query();
			$i++;
			}
		}
		//exit;
		return true;	
	}
	/**
	 * Upload a new user avatar
	 */	 	
	function uploadAvatar()
	{

	//make sure this directory is writable!
	$path_thumbs = "./images/avatar";		
	//the new width of the resized image, in pixels.
	$img_thumb_width = 64; // 
	$img_width = 160; 
	$extlimit = "yes"; 

	//List of allowed extensions if extlimit = yes
	$limitedext = array(".gif",".jpg",".png",".jpeg",".bmp");		
	//the image -> variables
	$file_type = $_FILES['profile_avatar']['type'];
	$file_name = $_FILES['profile_avatar']['name'];
	$file_size = $_FILES['profile_avatar']['size'];
	$file_tmp = $_FILES['profile_avatar']['tmp_name'];
		
	//check the file's extension
	$ext = strrchr($file_name,'.');
	$ext = strtolower($ext);
	//if file extension is not allowed!
	if (($extlimit == "yes") && (!in_array($ext,$limitedext))) {
	return false;
	}
	//so, whats the file's extension?
	$getExt = explode ('.', $file_name);
	$file_ext = $getExt[count($getExt)-1];
	//create a random file name
	$rand_name = md5(time());
	$rand_name1 = 'thumb_'.$rand_name;

	//the new width variable
	$ThumbWidth = $img_thumb_width;
	/////////////////////////////////
	// CREATE THE MAIN IMAGE //
	////////////////////////////////
	//keep image type
	if($file_size){
		if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
			$new_img = imagecreatefromjpeg($file_tmp);
		}elseif($file_type == "image/x-png" || $file_type == "image/png"){
			$new_img = imagecreatefrompng($file_tmp);
		}elseif($file_type == "image/gif"){
			$new_img = imagecreatefromgif($file_tmp);
		}
		//list the width and height and keep the height ratio.
		list($width, $height) = getimagesize($file_tmp);
		//calculate the image ratio
		$imgratio=$width/$height;
		if ($imgratio>1){
			$newwidth = $img_width;
			$newheight = $img_width/$imgratio;
		}else{
			$newheight = $img_width;
			$newwidth = $img_width*$imgratio;
		}
		//function for resize image.
		if (function_exists(imagecreatetruecolor)){
			$resized_img = imagecreatetruecolor($newwidth,$newheight);
		}else{
			return false;
		}
		//the resizing is going on here!
		imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		//finally, save the image
		imagejpeg ($resized_img,"$path_thumbs/$rand_name.$file_ext");
		
	}
		
	/////////////////////////////////
	// CREATE THE THUMBNAIL //
	////////////////////////////////
	//keep image type
	if($file_size){
		if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
			$new_img = imagecreatefromjpeg($file_tmp);
		}elseif($file_type == "image/x-png" || $file_type == "image/png"){
			$new_img = imagecreatefrompng($file_tmp);
		}elseif($file_type == "image/gif"){
			$new_img = imagecreatefromgif($file_tmp);
		}
		//list the width and height and keep the height ratio.
		list($width, $height) = getimagesize($file_tmp);
		//calculate the image ratio
		$imgratio=$width/$height;
		if ($imgratio>1){
			$newwidth = $ThumbWidth;
			$newheight = $ThumbWidth/$imgratio;
		}else{
			$newheight = $ThumbWidth;
			$newwidth = $ThumbWidth*$imgratio;
		}
		//function for resize image.
		if (function_exists(imagecreatetruecolor)){
			$resized_img = imagecreatetruecolor($newwidth,$newheight);
		}else{
			return false;
		}
		//the resizing is going on here!
		imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		//finally, save the image
		imagejpeg ($resized_img,"$path_thumbs/$rand_name1.$file_ext");
		imagedestroy ($resized_img);
		imagedestroy ($new_img);
	}
	$this->_n1 = "$path_thumbs/$rand_name.$file_ext";
	$this->_n2 = "$path_thumbs/$rand_name1.$file_ext";
	return true;
	}
	
	function createYears($start_year, $end_year, $id='year_select', $selected=null)
    {

        /*** the current year ***/
        $selected = is_null($selected) ? date('Y') : $selected;

        /*** range of years ***/
        $r = range($start_year, $end_year);

        /*** create the select ***/
        $select = '<select name="'.$id.'" id="'.$id.'">';
        foreach( $r as $year )
        {
            $select .= "<option value=\"$year\"";
            $select .= ($year==$selected) ? ' selected="selected"' : '';
            $select .= ">$year</option>\n";
        }
        $select .= '</select>';
        return $select;
    }
	/////////////
	//code for months
	/////////////
	function createMonths($id='month_select', $selected= null)
    {
        /*** array of months ***/
        $months = array(
                '01'=>'Jan',
                '02'=>'Feb',
                '03'=>'Mar',
                '04'=>'Apr',
                '05'=>'May',
                '06'=>'Jun',
                '07'=>'Jul',
                '08'=>'Aug',
                '09'=>'Sep',
                '10'=>'Oct',
                '11'=>'Nov',
                '12'=>'Dec');

        /*** current month ***/
	
        $selected = is_null($selected) ? date('m') : $selected;

        $select = '<select name="'.$id.'" id="'.$id.'">'."\n";
        foreach($months as $key=>$mon)
        {
            $select .= "<option value=\"$key\"";
            $select .= ($key==$selected) ? ' selected="selected"' : '';
            $select .= ">$mon</option>\n";
        }
        $select .= '</select>';
        return $select;
    }
	///////////////
	/////code for days..
	////////////////
	function createDays($id='day_select', $selected=null)
    {
        /*** range of days ***/
        $r = range(1, 31);

        /*** current day ***/
        $selected = is_null($selected) ? date('d') : $selected;

        $select = "<select name=\"$id\" id=\"$id\">\n";
        foreach ($r as $day)
        {
            $select .= "<option value=\"$day\"";
            $select .= ($day==$selected) ? ' selected="selected"' : '';
            $select .= ">$day</option>\n";
        }
        $select .= '</select>';
        return $select;
    }
	
}
