<?php  
/**
 * Group Bulletin Model for Mojoom Component
 * 
 * @package    Mojooom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');

 /* com_community component's core.php file included to be able to use the core classes of the component */
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');
/* com_community component's template.php file included */
require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'template.php');

class MojoomModelGroupbulletin extends JModel
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
	
	/**

	 * Method to retrieve a list of bulletins

	 *

	 * @param	$id	The id of the group if necessary

	 *

	 * @return	$result	An array of bulletins	 

	 **/	 

	function getBulletins( $groupId = null , $limit = 0 )
	{

		$where 		= ( !is_null($groupId) ) ? 'WHERE a.groupid=' . $this->_db->Quote( $groupId ) : '';




		$query	= 'SELECT * '

				. 'FROM ' . $this->_db->nameQuote('#__community_groups_bulletins') . ' AS a '

				. $where . ' '

				. 'ORDER BY a.`date` DESC';


		$this->_db->setQuery( $query );

		$result	= $this->_db->loadObjectList();



		if($this->_db->getErrorNum())

		{

			JError::raiseError( 500, $this->_db->stderr());

	    }

	    

		return $result;

	}
	
	function store($data)
	{
		$my = JFactory::getUser();
		$obj = new stdClass();
		$obj->id = '';
		$obj->groupid = $data['group_id'];
		$obj->created_by = $my->id;
		$obj->published = 1;
		$obj->title	= strip_tags($data['title']);
		$obj->message		= '<p>'.$data['message'].'</p>';
		$obj->date = gmdate('Y-m-d H:i:s');
		$this->_db->insertObject('#__community_groups_bulletins', $obj, 'id');
		return $obj;
	}
	
	function getBulletin( $bulletinId )
	{
		$query	= 'SELECT * from #__community_groups_bulletins where id='.$bulletinId;
		
		$this->_db->setQuery( $query );
		
		$result	= $this->_db->loadObject();
		
		return $result;
	
	}
	

}