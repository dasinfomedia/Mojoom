<?php
/**
 * Search Controller for Mojoom Component
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
class MojoomControllerSearch extends MojoomController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	public function search()
	{
		$mainframe =& JFactory::getApplication();
		
		$data			= new stdClass();
		
		$search			= JRequest::get('REQUEST');
		$data->query	= JRequest::getVar( 'q', '', 'REQUEST' );
		$avatarOnly		= JRequest::getVar( 'avatar' , '' );
				
		if(isset($search))
		{
			$model =& $this->getModel('search');
			$data->result	= $model->searchPeople( $search , $avatarOnly );

			$ids	= array();
			if(! empty($data->result))
			{
				foreach($data->result as $item)
				{
					$ids[]	= $item->id;
				}				
			}
		}
		
		$data->pagination 	= $model->getPagination();

	}
}