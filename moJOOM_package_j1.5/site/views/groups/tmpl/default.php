<?php
/** 
 * Groups View for Mojoom Component
 * 
 * @package   Mojoom
 * @subpackage Components
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

ini_set("display_errors","0"); ?>
<?php
$data = JRequest::get( 'post' );
$user =& JFactory::getUser();
if($user->guest) 
{
?>
	<script>window.location.href="index.php?option=com_mojoom&view=mojoom_login&layout=form";</script>
<?php 
} 
else
{
?>
<link href="./components/com_mojoom/css/mojoom.css" rel="stylesheet" type="text/css" />
        
		<div id="header_text">
			<div id="back"><input type="button" onclick="javascript:history.back();" class="back" value="Back"></div>
				<?php $uid = JFactory::getUser()->id; if($uid == $this->user_id || $this->user_id == 0 ) { ?>
                 <div id="header_title_groups">
					<ul id="sub_menu">
						<li class="leftrndcrnr active"><span>&nbsp;</span></li>
						<li id="left"><a href="index.php?option=com_mojoom&controller=groups&task=mygroups" class="active"><?php echo JText::_('GROUP BUL'); ?></a></li><li class="saperator"></li>
						<li id="mid"><a href="index.php?option=com_mojoom&controller=groups&task=myinvite"><?php echo JText::_('MY INVITES'); ?></a></li>
						<li class="saperator"></li>
						<li id="right"><a href="index.php?option=com_mojoom&controller=groups&task=create"><?php echo JText::_('CREATE BUL'); ?></a></li>
						<li class="rightrndcrnr"></li>
					</ul>
			</div>
            <?php } ?>
		</div>
<?php
	$document = JFactory::getDocument();
	$document->setTitle(JText::_('GROUP TITLE'));
	if( $this->groups )
	{
		
		
		?> 
		<script language="javascript" type="text/javascript">
		function filtergroups()
		{
		
			var selobj = document.getElementById('category_id');
			var index = selobj.selectedIndex;
			catid = selobj.options[index].value;
			//alert(catid);
			//alert("index.php?option=com_mojoom&controller=groups&task=mygroups&category_id="+ catid);
			if(catid == 'MyGroups')
			{
			
				window.location.href = "index.php?option=com_mojoom&controller=groups&task=mygroups&user_id=" + <?php echo JFactory::getUser()->id;?>;
			}
			else
			{
				window.location.href = "index.php?option=com_mojoom&controller=groups&task=mygroups&category_id="+ catid;
			}
		}
		</script>
		<div class="componentcontent">
			<div class="groupscontainer">
           	<?php $uid = JFactory::getUser()->id; if($uid == $this->user_id || $this->user_id == 0 ) { ?>
			<?php echo JText::_('FILTER BY');?>&nbsp;
			<select name="category_id" id="category_id" onchange="filtergroups();">
						<option value="0" <?php if($this->category_id == 0 && $this->user_id==0){ ?> selected="selected" <?php } ?>><?php echo JText::_('ALL GROUP'); ?></option>
						<option value="MyGroups" <?php if($this->category_id == 0 && $this->user_id!=0){ ?> selected="selected" <?php } ?>><?php echo JText::_('MY GROUP'); ?></option>
				<?php foreach($this->cat as $ct)
					{?>
						<option value="<?php echo $ct->id; ?>" <?php if($this->category_id == $ct->id ){ ?> selected="selected" <?php } ?>><?php echo $ct->name; ?></option>
					<?php
					}
					?>
			</select>
            
			<br /><br />
            <?php } ?>
			<?php
				foreach($this->groups as $group)
				{
					?>
					<div class="community-groups-results-item">	
						<div class="community-groups-results-left">
							<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id=' . $group->id );?>">
							<?php
								$avtarimg = '';
								if($group->thumb == '')
									$avatarimg = 'components/com_community/templates/blackout/images/group_thumb.png';
								else
									$avatarimg = $group->thumb;
							?>
							
							<img class="avatar" src="<?php echo $avatarimg;?>" alt="<?php echo JText::_($group->name); ?>"/></a>
						</div>
						<div class="community-groups-results-right">
							<h3 class="groupName">
								<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewgroup&group_id=' . $group->id );?>"><?php echo JText::_($group->name); ?></a>
							</h3>
							<span class="icon-group" style="margin-right: 5px;float:right;">&nbsp;(
								<a href="<?php echo JRoute::_( 'index.php?option=com_mojoom&controller=groups&task=viewmembers&group_id=' . $group->id ); ?>"><?php
								if($group->membercount == 1)
									echo JText::sprintf('MEM INV', $group->membercount);
								else
									echo JText::sprintf('MEMS INV', $group->membercount);
									?></a> )
							</span>
						<div class="groupDescription">
							<?php
								$totwords = str_word_count($group->description);
								$numwords = 10; 
								if($totwords > $numwords)
								{
							 		preg_match("/(\S+\s*){0,$numwords}/", $group->description, $regs); 
							 		$shortdesc = trim($regs[0]); 
						 			echo JText::_($shortdesc)." ..."; 
								}
								else
								{
									echo JText::_($group->description);
								}
								?></div>
						<div class="groupCreated small"><?php echo JText::sprintf('CREATE ON INVIT' , JHTML::_('date', $group->created, JText::_('%d %b %Y')) );?></div>    
						<div class="groupActions1">
	
							<span class="icon-discuss" style="margin-right: 5px;">
								<?php
									if($group->discusscount == 0 || $group->discusscount == 1 )
										 echo JText::sprintf('DISCUSSION INVIT', $group->discusscount);
									else
										echo JText::sprintf('DISCUSSIONS INVIT', $group->discusscount);
									?>
							</span>
	
							<span class="icon-wall" style="margin-right: 5px;">
								<?php
									if($group->wallcount == 0  || $group->wallcount == 1 )
										 echo JText::sprintf('WALL POST INVIT', $group->wallcount);
									else
										 echo JText::sprintf('WALL POSTS INVIT', $group->wallcount);
									?>
							</span>
						</div>
					</div>
				</div>
			<?php
			}
			?>	
		</div>
	</div>
		<?php
		
	}
	else
	{
		?>
		<div class="componentcontent">
			<div class="groupscontainer">
				<?php
				echo JText::_('NO GROUP FOUND');
				?>
			</div>
		</div>
		<?php
	}
	
} 
?>