<?php 
/**
 * Group Video Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class MojoomModelGroup_video extends JModel
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

	function getGroup_video($filters = array(),$g_id)
	{
		$where	= array();
		foreach ($filters as $field => $value)
		{
			if ($value || $value === 0)
			{
				switch (strtolower($field))
				{
					case 'category_id':
						if (is_array($value)) {
							JArrayHelper::toInteger($value);
							$value	= implode( ',', $value );
						}
						$where[]	= 'v.`category_id` IN (' . $value . ')';
						break;
					case 'status':
						$where[]	= 'v.`status` = ' . $this->_db->quote($value);
						break;
				}
			}
		}
		$where[] = ' v.groupid=' . $this->_db->Quote( $g_id ) . ' ';

		$where		= count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
		
		// Joint with group table
		$join	= '';
		if (isset($filters['or_group_privacy']))
		{
			$approvals	= (int) $filters['or_group_privacy'];
			$join		=  ' LEFT JOIN ' . $db->nameQuote('#__community_groups') . ' AS g';
			$join 		.= ' ON g.id = v.groupid';
			$where		.= ' AND (g.approvals = 0 OR g.approvals IS NULL)';
		}

		$order		= '';
		$sorting	= isset($filters['sorting']) ? $filters['sorting'] : 'latest';

		switch ($sorting)
		{
			case 'mostwalls':
				// mostwalls is sorted below using JArrayHelper::sortObjects
				// since in db vidoes doesn't has wallcount field
			case 'mostviews':
				$order	= ' ORDER BY v.`hits` DESC';
				break;
			case 'title':
				$order	= ' ORDER BY v.`title` ASC';
				break;
			case 'latest':
			default :
				$order	= ' ORDER BY v.`created` DESC';
				break;
		}

		
		$query		= ' SELECT v.*, v.created AS lastupdated'
					. ' FROM ' . $this->_db->nameQuote('#__community_videos') . ' AS v'
					. $join
					. $where
					. $order;
					
		$this->_db->setQuery($query);
		$result		= $this->_db->loadObjectList();

		if ($this->_db->getErrorNum())
			JError::raiseError(500, $this->_db->stderr());

		return $result;
				
	}	
	
}