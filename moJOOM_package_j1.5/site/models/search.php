<?php
/**
 * Search Model for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.utilities.date');
jimport('joomla.html.pagination');

class MojoomModelSearch extends JModel
{
	var $_pagination;
	var $_total;
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();
		$mainframe =& JFactory::getApplication();
 	 	
 	 	// Get pagination request variables
 	 	$limit		= ($mainframe->
		getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
	    $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');
 	 	
 	 	// In case limit has been changed, adjust it
	    $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		 	 	
		$this->setState('limit',$limit);
 	 	$this->setState('limitstart',$limitstart);		
	}
	
	/**
	 * get pagination data
	 */	 	
	function getPagination()
	{
		return $this->_pagination;
	}
	
	/**
	 * get total data
	 */	 	
	function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Search for people
	 * @param query	string	people's name to seach for	
	 */	 
	function getSearchpeople()
	{
		$db	= &$this->getDBO();
		//$data			= new stdClass();
		$query			= JRequest::get('REQUEST');
		//$data->query	= JRequest::getVar( 'q', '', 'REQUEST' );
		$avatarOnly		= JRequest::getVar( 'avatar' , '' );

		$filter = array();
		$strict = true;
		$regex = $strict? 
		      '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' : 
		       '/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' ;
			
		$data = array();
		//select only non empty field
		foreach($query as $key => $value)
		{
			if(!empty($query[$key]))
			{
				$data[$key]=$value;
			}
		}
		
		// build where condition
		$filterField	= array();						
		if(isset($data['q']))
		{ 		
			$value			= $data['q'];
			if (preg_match($regex, JString::trim($value), $matches))
			{ 
				$query = array($matches[1], $matches[2]);
				$cond = $matches[1]."@".$matches[2];
				$filter[] = "`email`=" . $db->Quote($cond);
		    }
			else
			{
				//$config		= CFactory::getConfig();
				//$nameType	= $db->nameQuote( $config->get( 'displayname' ) );
				$nameType = 'name';
				$filter[]	= 'UCASE(' . $nameType . ') LIKE UCASE(' . $db->Quote( '%' . $value . '%' ) . ')';
		    }
	    }
		
		$limit			= $this->getState('limit');
		$limitstart		= $this->getState('limitstart');		
		$finalResult	= array();
		$total			= 0;
		if(count($filter)> 0 || count($filterField > 0))
		{
			// Perform the simple search
			$basicResult = null;
			if(!empty($filter) && count($filter)>0)
			{
				$query = "SELECT distinct b.`id` FROM #__users b";
		
				if( $avatarOnly )
				{
					$query	.= ' INNER JOIN #__community_users AS c ON b.`id`=c.`userid`';
					$query	.= ' AND c.`thumb` != ' . $db->Quote( 'components/com_community/assets/default_thumb.jpg' );
				}
				$query .= " WHERE b.block = 0 AND ".implode(' AND ',$filter);

				$queryCnt	= 'SELECT COUNT(1) FROM ('.$query.') AS z';
				$db->setQuery($queryCnt);		
				$total	= $db->loadResult();
				
				$query .=  " LIMIT " . $limitstart . "," . $limit;
							
				$db->setQuery( $query );
				$finalResult = $db->loadResultArray();
				if($db->getErrorNum()) {
					JError::raiseError( 500, $db->stderr());
				}
			}
			
			// Appy pagination
			if (empty($this->_pagination))
			{		 	    
		 	    $this->_pagination = new JPagination($total, $limitstart, $limit);
		 	}
		} 				

		if(empty($finalResult))
			$finalResult = array(0);

		$id = implode(",",$finalResult);
		$where = array("`id` IN (".$id.")");
	    $result = $this->getFiltered($where);
		
		return $result;
	}
	
	function &getFiltered($wheres = array())
	{
		$db			= &$this->getDBO();
		
		$wheres[] = 'a.block = 0';
		 
		$query = "SELECT a.* , b.thumb , CASE WHEN c.userid IS NULL THEN 0 ELSE 1 END AS online "
			. ' FROM ' . $db->nameQuote('#__users') . ' as a INNER JOIN ' . $db->nameQuote('#__community_users') . ' as b on a.id = b.userid '
			. ' LEFT JOIN ' . $db->nameQuote('#__session') . ' AS c ON a.id = c.userid '
			. ' WHERE ' . implode( ' AND ', $wheres )
			. ' GROUP BY a.id ORDER BY `id` DESC ';

		$db->setQuery( $query );
		if($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr());
		}
		
		$result = $db->loadObjectList();
		return $result;
	}
	
}
