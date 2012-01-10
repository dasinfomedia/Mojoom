<?php 
/**
 * Group Albums Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelGroup_albums extends JModel
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

	function getGroup_albums($userid, $type,$g_id)
	{
		
		$extraSQL	= ' WHERE a.type = ' . $this->_db->Quote($type);
		if( !empty($g_id) && $type == 'group' )
		{
			$extraSQL	.= ' AND a.groupid=' . $this->_db->Quote( $g_id ) . ' ';
		}
		else if( !empty( $id ) && $type == 'user' )
		{
			$extraSQL	.= ' AND a.creator=' . $this->_db->Quote( $id ) . ' ';
		}
		// Get total albums
		$query	= 'SELECT COUNT(*) '
				. 'FROM ' . $this->_db->nameQuote( '#__community_photos_albums' ) . ' AS a'
				. $extraSQL;
		
		$this->_db->setQuery( $query );
		$total = $this->_db->loadResult();
		$this->total = $total;
		
		$query	= 'SELECT a.*, '
				. 'COUNT( DISTINCT(b.id) ) AS count, '
				. 'MAX(b.created) AS lastupdated, '
				. 'c.thumbnail as thumbnail, '
				. 'c.storage AS storage, '
				. 'c.id as photoid '
				. 'FROM ' . $this->_db->nameQuote( '#__community_photos_albums' ) . ' AS a '
				. 'LEFT JOIN ' . $this->_db->nameQuote( '#__community_photos' ) . ' AS b '
				. 'ON a.id=b.albumid '
				. 'LEFT JOIN ' . $this->_db->nameQuote( '#__community_photos' ) . ' AS c '
				. 'ON a.photoid=c.id '
				. $extraSQL
				. 'GROUP BY a.id '
				. ' ORDER BY a.`created` DESC';
				
		$this->_db->setQuery( $query );
		$result	= $this->_db->loadObjectList();
		
		// Update their correct Thumbnails

		return $result;
				
	}	
	
	
}
