<?php
/**
 * Like View for Mojoom Component
 * 
 * @package    Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php  ini_set("display_errors","0");
$data = JRequest::get( 'get' );
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
<span id="like-container">
    <span class="like-snippet" id="like-events-1">                    
    <a onclick="like('1','<?php echo $data['id']; ?>','<?php echo $data['uid']; ?>')" href="javascript:void(0);"  <?php if($this->like === false) { ?> class="like_button" <?php } else { ?> class="like_button_active" <?php } ?> ><?php echo JText::_('LIKE'); ?></a>                        
    <a onclick="like('2','<?php echo $data['id']; ?>','<?php echo $data['uid']; ?>');" href="javascript:void(0);" <?php if($this->dislike === false) { ?> class="dislike_button" <?php } else { ?>class="dislike_button_active" <?php } ?> >0</a>                    
</span>
</span>
<div class="clr"></div>