<?php
/**
 * Mojooms Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

/**
 * Mojooms Model
 *
 * @package  Mojoom
 * @subpackage Components
 */
class MojoomsModelMojooms extends JModel
{
	/**
	 * Mojoom data array
	 *
	 * @var array
	 */
	
	var $_data;
	
	//$_data = array();
	var $_data1;
	
	// xml module positions
	var $_modulepositions;
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = 'SELECT * FROM #__mojoom_config where id=1';
		
		return $query;
	}

	function getData()
	{
		$query = $this->_buildQuery();
		$this->_data = $this->_getList( $query );
		return $this->_data;
	}
	/**
	 * Retrieves the  the names of the templates and also the positions defined
	 * @return array Array of objects containing the data from the database
	 */
	function getTemplates()
	{
		$templateBaseDir = JPATH_SITE.DS.'templates'.DS;
		$templates = array ();
		$templates[] = array ('value' => '');
	
		jimport('joomla.filesystem.folder');
		$templateDirs = JFolder::folders($templateBaseDir);
		foreach($templateDirs as $templateDir)
		{
			$templateFile = $templateBaseDir.$templateDir.DS.'templateDetails.xml';
			if(!is_file($templateFile))
				continue;
			$xml = JApplicationHelper::parseXMLInstallFile($templateFile);
			if($xml['type'] != 'template')
				continue;
			$templates[] = array ('value' => $templateDir);
	
			$xml =& JFactory::getXMLParser('Simple');
			if($xml->loadFile($templateFile))
			{
				$p =& $xml->document->getElementByPath('positions');
				if(is_a($p, 'JSimpleXMLElement') && count($p->children()))
					foreach($p->children() as $child)
						$positions[] = $child->data();
			}
		}
		$positions[] = '';
		$positions = array_unique($positions);
		sort($positions);
		
		$this->_xmlmodulepositions = array ();
		foreach($positions as $position)
		$this->_xmlmodulepositions[] = $position;
		
		sort($this->_xmlmodulepositions);
	  //return $this->_data;
	  return $templates;
	}
	
	function getModulePositions()
	{
		/** @var JDatabase $db */
		$db =& JFactory::getDBO();
		$query = 'SELECT DISTINCT(position) FROM #__modules WHERE client_id = 0';
		$db->setQuery($query);
		$positions = $db->loadResultArray();
		$positions = (is_array($positions)) ? $positions : array ();
		$positions[] = '';
		$positions = array_unique($positions);
		sort($positions);
	
		$assignedmodulepositions = array ();
		foreach($positions as $position)
			$assignedmodulepositions[] = $position;
				
		//TOTAL POSITIONS: (with redundant value)
		$totalpos = array_merge($this->_xmlmodulepositions,$assignedmodulepositions);
		$modulepositions = array_unique($totalpos);
		sort($modulepositions);
		return $modulepositions;
	}
	
	function store()
	{	
		$row =& $this->getTable('mojoom_config');
		
		$data = JRequest::get( 'post' );
		$user = JFactory::getUser();
		$data['user_id'] = $user->id;
		$data['date'] = date('Y-m-d H-i-s');
		$data['published'] = 1;
		//////////////////////////////////////////
		///////code for file uploading
		//////////////////////////////////////////
		
		$myabsoluteurl = JURI::base();
		$name1 = $CF_PATH.'components/com_mojoom/images';
		$filename = $_FILES['iphonelogo']['name'];
                print_r($filename);    
		$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1);
		if($filename != "")
		{
			
			$part = explode(".",$filename);
			$part[0] = $part[0].time();
			$newname = $part[0].$ext;
			$name1.= '/'.basename( $newname );
					
			if(move_uploaded_file($_FILES['iphonelogo']['tmp_name'] , $name1))
			{
						echo "The file ". basename( $_FILES['iphonelogo']['name']). " has been uploaded";
			}
			else {
						echo "Sorry, there was a problem uploading your file.";
			}
			$data['iphonelogo']=$newname;
			
		}
		////////////////////// upload facebook icon /////////////////////////////
		$filename1 = $_FILES['facebookicon']['name'];
                print_r($filename1);    
		$ext1 = substr($filename1, strrpos($filename1,'.'), strlen($filename1)-1);
		if($filename1 != "")
		{
			
			$part1 = explode(".",$filename1);
			$part1[0] = $part1[0].time();
			$newname1 = $part1[0].$ext1;
			$name1.= '/'.basename( $newname1 );
					
			if(move_uploaded_file($_FILES['facebookicon']['tmp_name'] , $name1))
			{
						echo "The file ". basename( $_FILES['facebookicon']['name']). " has been uploaded";
			}
			else {
						echo "Sorry, there was a problem uploading your file.";
			}
			$data['facebookicon']=$newname1;
			
		}
		
		////////////////////// upload facebook icon /////////////////////////////
		
		////////////////////// upload twitter icon /////////////////////////////
		$filename2 = $_FILES['twittericon']['name'];
                print_r($filename2);    
		$ext2 = substr($filename2, strrpos($filename2,'.'), strlen($filename2)-1);
		if($filename2 != "")
		{
			
			$part2 = explode(".",$filename2);
			$part2[0] = $part2[0].time();
			$newname2 = $part2[0].$ext2;
			$name1.= '/'.basename( $newname2 );
					
			if(move_uploaded_file($_FILES['twittericon']['tmp_name'] , $name1))
			{
						echo "The file ". basename( $_FILES['twittericon']['name']). " has been uploaded";
			}
			else {
						echo "Sorry, there was a problem uploading your file.";
			}
			$data['twittericon']=$newname2;
			
		}
		
		////////////////////// upload twitter icon /////////////////////////////
		
		////////////////////// upload linkedin icon /////////////////////////////
		$filename3 = $_FILES['linkedinicon']['name'];
                print_r($filename3);    
		$ext3 = substr($filename3, strrpos($filename3,'.'), strlen($filename3)-1);
		if($filename3 != "")
		{
			
			$part3 = explode(".",$filename3);
			$part3[0] = $part3[0].time();
			$newname3 = $part3[0].$ext3;
			$name1.= '/'.basename( $newname3 );
					
			if(move_uploaded_file($_FILES['linkedinicon']['tmp_name'] , $name1))
			{
						echo "The file ". basename( $_FILES['linkedinicon']['name']). " has been uploaded";
			}
			else {
						echo "Sorry, there was a problem uploading your file.";
			}
			$data['linkedinicon']=$newname3;
			
		}
		
		////////////////////// upload linkedin icon /////////////////////////////
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
	
}