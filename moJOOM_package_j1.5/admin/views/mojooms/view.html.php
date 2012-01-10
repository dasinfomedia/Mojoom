<?php
/**
 * Mojooms View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Mojooms View
 * @package    Mojoom
 * @subpackage Components
 */
class MojoomsViewMojooms extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Mobile Mojoom' ), 'generic.png' );
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JSubMenuHelper::addEntry(JText::_('Settings'), 'index.php?option=com_mojoom', true );
		
		// Get the data 
		$data = &$this->get('Data');
		
		// Get the list of the template installed
		$templates = &$this->get('Templates');
		
		// Get the module Position
		$positions = &$this->get('ModulePositions');

		$this->assignRef('templates',$templates);
		$this->assignRef('data',		$data);
		$this->assignRef('positions',		$positions);
		//$this->assignRef('tasks',		$tasks);

		parent::display($tpl);
	}
	
	function menuoptions()
	{
		/** @var JDatabase $db */
		$db =& JFactory::getDBO();
		$query = 'SELECT id, menutype, name, link, type, parent FROM #__menu WHERE published=1 ORDER BY menutype, parent, ordering';
		$db->setQuery($query);
		$mitems = $db->loadObjectList();
		$children = array();
		foreach($mitems as $v)
		{
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push($list, $v);
			$children[$pt] = $list;
		}
		$list = array();
		$id = intval($mitems[0]->parent);
		if(@$children[$id])
			$this->_TreeRecurse($id, '', $list, $children);
		$mitems = array();
		$lastMenuType = null;
		foreach($list as $list_a)
		{
			if($list_a->menutype != $lastMenuType)
			{
				if($lastMenuType)
					$mitems[] = JHTML::_('select.option', '</OPTGROUP>' );
				$mitems[] = JHTML::_('select.option', '<OPTGROUP>', $list_a->menutype);
				$lastMenuType = $list_a->menutype;
			}
			if($list_a->type == 'component')
				$link = $list_a->link.'&Itemid='.$list_a->id;
			else
				$link = '-';
			$mitems[] = JHTML::_('select.option', $link, $list_a->treename, 'value', 'text', $link=='-');
		}
		if($lastMenuType !== null)
			$mitems[] = JHTML::_('select.option', '</OPTGROUP>');
		return $mitems;
	}
	
	function _TreeRecurse($id, $indent, &$list, &$children, $level=0)
	{
		foreach($children[$id] as $v)
		{
			$id = $v->id;
			$list[$id] = $v;
			$list[$id]->treename = $indent.$v->name;
			if(@$children[$id] && $level<=99)
				$this->_TreeRecurse($id, $indent.'&nbsp;&nbsp;', $list, $children, $level+1);
		}
	}
}