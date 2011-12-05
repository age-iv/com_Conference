<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ConferencesViewTesis
{
  function setTesissToolbar()
  {
?>
<style>
.icon-32-export
{
  background-image:url(components/com_conference/images/icon-32-export.png);
}
.icon-48-icon
{
  background-image:url(components/com_conference/images/tesis-icon-48.png);
}
</style>
<?php
//заголовок
    JToolBarHelper::title( JText::_( 'List of tesis' ), 'icon.png' );
//управляющие кнопки: удалить, изменить, новый
    JToolBarHelper::Custom('toCSV', 'export', '', JText::_('Export'), false);
    JToolBarHelper::divider();
    JToolBarHelper::spacer();
    JToolBarHelper::addNewX();
    JToolBarHelper::editListX();
    JToolBarHelper::deleteList();
  }

  function Tesiss( &$rows, &$pageNav, &$lists )
  {
//вызов заголовка и кнопок
    ConferencesViewTesis::setTesissToolbar();

    $user =& JFactory::getUser();
    JHTML::_('behavior.tooltip');
    ?>
<!--Таблица фильтра-->

<form action="index.php?option=com_conference&act=tesis" method="post" name="adminForm">
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
          <?php echo JHTML::_('grid.sort', 'FIO', 'surname', @$lists['order_Dir'], @$lists['order']); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JText::_('username'); ?>
        </th>
        <th width="15%" class="title" >
          <?php echo JHTML::_('grid.sort',   'authors', 'authors', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="15%" class="title" >
          <?php echo JHTML::_('grid.sort',   'supervisor', 'supervisor', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="30%" class="title" >
          <?php echo JHTML::_('grid.sort',   'name_lecture', 'name_lecture', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'type_lecture', 'type_lecture', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'sections', 'sections', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'file_name', 'file_name', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>

      </tr>
    </thead>
<!--Подвал-->
    <tfoot>
      <tr>
        <td colspan="13">
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
        
        $link   = 'index.php?option=com_conference&amp;act=tesis&amp;task=edit&amp;cid[]='. $row->id.'';

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
          <?php echo $row->authors; ?>
        </td>
        <td>
          <?php echo $row->supervisor; ?>
        </td>
        <td>
          <?php echo $row->name_lecture; ?>
        </td>
        <td align="center">
          <?php echo $row->type_lecture;?>
        </td>
        <td align="center">
          <?php echo $row->sections;?>
        </td>
        <td align="center">
        <a href="<?php echo $row->file_name;?>">
          <?php echo JText::_('file'); ?>
        </a>
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
  
    function setTesisToolbar()
  {
    $task = JRequest::getVar( 'task', '', 'method', 'string');
//заголовок
    JToolBarHelper::title( $task == 'add' ? JText::_( 'Tesis' ). ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Tesis') . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
//управляющие кнопки: сохранить, применить, отмена
    JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::cancel();
  }
  
    function Tesis($row, $count_users, $arr_users)
  {
//вызов заголовка и кнопок
    ConferencesViewTesis::setTesisToolbar();
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
<form action="<?php JRoute::_('index.php?option=com_conference&act=tesis');?>" method="post" name="adminForm" autocomplete="off">
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
      <legend><?php echo JText::_( 'Tesis' ); ?></legend>
        <table class="admintable">
          <tr>
            <td class="key">
              <label id="authorsmsg" for="authors">
                <?php echo JText::_( 'authors' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="authors" id="authors" size="40" value="<?php echo $row->authors;?>" class="inputbox" />
            </td>
          </tr>
		  <tr>
            <td class="key">
              <label id="supervisormsg" for="supervisor">
                <?php echo JText::_( 'supervisor' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="supervisor" id="supervisor" size="40" value="<?php echo $row->supervisor;?>" class="inputbox" />
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="name_lecturemsg" for="name_lecture">
                <?php echo JText::_( 'name_lecture' ); ?>:
              </label>
            </td>
            <td>
              <textarea class="inputbox" cols="30" rows="8" name="name_lecture" id="name_lecture"><?php echo $row->name_lecture;?></textarea>
            </td>
          </tr>
          <tr>
            <td class="key">
              <label id="type_lecturemsg" for="type_lecture">
                <?php echo JText::_( 'type_lecture' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="type_lecture" id="type_lecture" size="40" value="<?php echo $row->type_lecture;?>" class="inputbox" />
            </td>
          </tr>
		  <tr>
            <td class="key">
              <label id="sectionsmsg" for="sections">
                <?php echo JText::_( 'sections' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="sections" id="sections" size="40" value="<?php echo $row->sections;?>" class="inputbox" />
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