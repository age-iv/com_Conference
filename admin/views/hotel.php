<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ConferencesViewHotel
{
  function setHotelsToolbar()
  {
?>
<style>
.icon-32-export
{
  background-image:url(components/com_conference/images/icon-32-export.png);
}
.icon-48-icon
{
  background-image:url(components/com_conference/images/hotel-icon-48.png);
}
</style>
<?php
//заголовок
    JToolBarHelper::title( JText::_( 'List of Rooms' ), 'icon.png' );
//управляющие кнопки: удалить, изменить, новый
    JToolBarHelper::Custom('toCSV', 'export', '', JText::_('Export'), false);
    JToolBarHelper::divider();
    JToolBarHelper::spacer();
    JToolBarHelper::addNewX();
    JToolBarHelper::editListX();
    JToolBarHelper::deleteList();
  }

  function Hotels( &$rows, &$pageNav, &$lists )
  {
//вызов заголовка и кнопок
    ConferencesViewHotel::setHotelsToolbar();

    $user =& JFactory::getUser();
    JHTML::_('behavior.tooltip');
    ?>
<!--Таблица фильтра-->
<form action="index.php?option=com_conference&act=hotel" method="post" name="adminForm">
  <table>
    <tr>
      <td width="100%">
        <?php echo JText::_( 'Filter' ); ?>:
        <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
        <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_type').value='0';this.form.getElementById('filter_logged').value='0';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
      </td>
    </tr>
  </table>
<!--Основная таблица со списком-->
  <table class="adminlist" cellpadding="1">
<!--Заголовки колонок-->
    <thead>
      <tr>
        <th width="2%" class="title">
          <?php echo JText::_( 'NUM' ); ?>
        </th>
        <th width="3%" class="title">
          <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
        </th>
        <th class="title">
          <?php echo JHTML::_('grid.sort',   'FIO', 'surname', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JText::_('username'); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'city', 'city', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'hotel', 'hotel', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'visit_date', 'visit_date', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'exit_date', 'exit_date', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="10%" class="title" >
          <?php echo JHTML::_('grid.sort',   'type_room', 'type_room', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="10%" class="title" >
          <?php echo JHTML::_('grid.sort',   'address', 'address', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="10%" class="title">
          <?php echo JHTML::_('grid.sort',   'E-Mail', 'email', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="15%" class="title">
          <?php echo JText::_('Wish'); ?>
        </th>

      </tr>
    </thead>
<!--Подвал-->
    <tfoot>
      <tr>
        <td colspan="14">
          <?php  echo $pageNav->getListFooter(); ?>
        </td>
      </tr>
    </tfoot>
<!--Основная часть-->
    <tbody>
    <?php
      $k = 0;
      for ($i=0, $n=count( $rows ); $i < $n; $i++)
      {
        $row  =& $rows[$i];
        
        $link   = 'index.php?option=com_conference&amp;act=hotel&amp;task=edit&amp;cid[]='. $row->id.'';

      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
          <?php echo $i+1+$pageNav->limitstart;?>
        </td>
        <td>
          <?php echo JHTML::_('grid.id', $i, $row->id, false, 'cid'); ?>
        </td>
        <td>
          <a href="<?php echo $link; ?>">
            <?php echo $row->surname.' '.$row->name; ?></a>
        </td>
        <td>
          <?php echo $row->username; ?>
        </td>
        <td>
          <?php echo $row->city; ?>
        </td>
        <td>
          <?php echo $row->hotel; ?>
        </td>
        <td align="center">
          <?php echo $row->visit_date;?>
        </td>
        <td align="center">
          <?php echo $row->exit_date; ?>
        </td>
        <td nowrap="nowrap">
          <?php echo $row->type_room; ?>
        </td>
        <td>
          <?php echo $row->address; ?>
        </td>
        <td>
          <a href="mailto:<?php echo $row->email; ?>">
            <?php echo $row->email; ?></a>
        </td>
        <td>
          <?php echo $row->wish; ?>
        </td>
      </tr>
      <?php
        $k = 1 - $k;
        }
      ?>
    </tbody>
  </table>
<!--Скрытые поля (служебная информация)-->
  <input type="hidden" name="option" value="com_conference" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
    <?php
  }
  
    function setHotelToolbar()
  {
    $task = JRequest::getVar( 'task', '', 'method', 'string');
//заголовок
    JToolBarHelper::title( $task == 'add' ? JText::_( 'Hotel' ). ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Hotel') . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
//управляющие кнопки: сохранить, применить, отмена
    JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::cancel();
  }
  
    function Hotel($row, $count_users, $arr_users)
  {
//вызов заголовка и кнопок
    ConferencesViewHotel::setHotelToolbar();
//делает меню не активным   
    JRequest::setVar( 'hidemainmenu', 1 );
// очистка данных записи
  JFilterOutput::objectHTMLSafe( $user, ENT_QUOTES, '' );
?>
<script language="javascript" type="text/javascript">

  function submitbutton(pressbutton) {
    var form = document.adminForm;
    if (pressbutton == 'cancel') {
      submitform( pressbutton );
      return;
    }
    var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");

    // do field validation
    if (form.username.value == 0) {
      alert( "<?php echo JText::_( 'You must provide a username.', true ); ?>" );
    } else {
      submitform( pressbutton );
    }
    
  }
</script>
<form action="<?php JRoute::_('index.php?option=com_conference&act=hotel');?>" method="post" name="adminForm" autocomplete="off">
<!--Гостиница-->
  <div class="col width-70">
    <fieldset class="adminform">
      <legend><?php echo JText::_( 'User' ); ?></legend>
        <table class="admintable">
          <tr>
            <td height="40" class="key">
              <label id="usernamemsg" for="username">
              <?php echo JText::_( 'Name' ); ?>:
              </label>
            </td>
            <td>
              <?php
              $user[] = JHTML::_('select.option',  ' ', '--Select user--' );
              for ($i=0;$i<$count_users; $i++)
              {
              $user[] = JHTML::_('select.option',  $arr_users[$i]->username, $arr_users[$i]->name );
              }
              echo JHTML::_('select.genericlist', $user, 'username', 'class="inputbox"', 'value', 'text', $row->username);
              ?>
            </td>
          </tr>
      <legend><?php echo JText::_( 'Hotel' ); ?></legend>
        <table class="admintable">
          <tr>
            <td height="40" class="key">
              <label id="hotelmsg" for="hotel">
              <?php echo JText::_( 'hotel' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="hotel" id="hotel" size="70" value="<?php echo $row->hotel;?>" class="inputbox" />
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="type_roommsg" for="type_room">
                <?php echo JText::_( 'type_room' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="type_room" id="type_room" size="70" value="<?php echo $row->type_room;?>" class="inputbox" />
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="visit_datemsg" for="visit_date">
                <?php echo JText::_( 'visit_date' ); ?>:
              </label>
            </td>
            <td>
              <?php echo JHTML::calendar($row->visit_date, 'visit_date', 'visit_date', '%d.%m.%Y','size=70'); ?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="exit_datemsg" for="exit_date">
                <?php echo JText::_( 'exit_date' ); ?>:
              </label>
            </td>
            <td>
              <?php echo JHTML::calendar($row->exit_date, 'exit_date', 'exit_date', '%d.%m.%Y', 'size=70'); ?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="wishmsg" for="wish">
                <?php echo JText::_( 'wish' ); ?>:
              </label>
            </td>
            <td>
              <textarea class="inputbox" cols="40" rows="8" name="wish" id="wish"><?php echo $row->wish;?></textarea>
            </td>
          </tr>
        </table>
    </fieldset>
  </div>  
  <div class="clr"></div>
  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="option" value="com_conference" />
  <input type="hidden" name="cid" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="hotel" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
  }
}
?>