<?php
/**
 * Mojoom config table class
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Mojoom config Table class
 * @package    Mojoom
 * @subpackage Components
 */
class TableMojoom_config extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id = null;
	var $user_id = null;
	var $iphone_template = null;
	var $iphonetemplatetheme = null;
	var $iphonelogo = null;
	var $iphonejoomlasearch = null;
	var $socialneticons = null;
	var $facebookicon = null;
	var $facebooklink = null;
	var $twittericon = null;
	var $twitterlink = null;
	var $linkedinicon = null;
	var $linkedinlink = null;
	var $iphonehomepage = null;
	var $iphoneprofilepage = null;
	var $iphoneaboutuspage = null;
	var $iphonemorepage = null;
	var $tmpl_iphone_module1 = null;
	var $tmpl_iphone_module2 = null;
	var $tmpl_iphone_module3 = null;
	var $iphonefooter = null;
	var $iphonebacktotop = null;
	var $date = null;
	var $published = null;
			
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableMojoom_config(& $db) {
		parent::__construct('#__mojoom_config', 'id', $db);
	}
}