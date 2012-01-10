<?php 
/**
 * Group Photos Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelGroup_photos extends JModel
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

	function getAllPhotos( $albumId = null , $photoType ='group', $orderType = 'DESC' )
	{

		$where	= ' WHERE b.`type` = ' . $this->_db->Quote($photoType);

		if( !is_null($albumId) )
		{

			$where	.=	' AND b.`id`'
					.	'=' . $this->_db->Quote( $albumId )
					.	' AND a.`albumid`'
					.	'=' . $this->_db->Quote( $albumId );												
		}

		

		$where	.= ' AND a.`published`=' . $this->_db->Quote( 1 );

		

		$query	= 'SELECT a.* FROM ' . $this->_db->nameQuote( '#__community_photos') . ' AS a';

		$query	.= ' INNER JOIN ' . $this->_db->nameQuote( '#__community_photos_albums') . ' AS b';

		$query	.= ' ON a.`albumid` = b.`id`';

		$query	.= $where;

		$query	.= ' ORDER BY  a.`ordering` , a.`created` ' . $orderType;

		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();

		if($this->_db->getErrorNum())
		{

			JError::raiseError( 500, $this->_db->stderr());

		}

		return $result;

	}

	
	
}
