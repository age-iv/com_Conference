<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ConferencesViewReg
{
  function setRegsToolbar()
  {
?>
<style>
.icon-32-export
{
  background-image:url(components/com_conference/images/icon-32-export.png);
}
.icon-48-icon
{
  background-image:url(components/com_conference/images/register-icon-48.png);
}
</style>
<?php
//заголовок
    JToolBarHelper::title( JText::_( 'List of Registred' ), 'icon.png' );
//управляющие кнопки: удалить, изменить, новый
    JToolBarHelper::Custom('toCSV', 'export', '', JText::_('Export'), false);
    JToolBarHelper::divider();
    JToolBarHelper::spacer();
    JToolBarHelper::addNewX();
    JToolBarHelper::editListX();
    JToolBarHelper::deleteList();   
  }

  function Regs( &$rows, &$pageNav, &$lists )
  {
//вызов заголовка и кнопок
    ConferencesViewReg::setRegsToolbar();

    $user =& JFactory::getUser();
    JHTML::_('behavior.tooltip');
    ?>
<!--Таблица фильтра-->
<form action="index.php?option=com_conference&act=reg" method="post" name="adminForm">
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
          <?php echo JHTML::_('grid.sort',   'Username', 'username', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="10%" class="title" >
          <?php echo JHTML::_('grid.sort',   'Dept', 'dept', @$lists['order_Dir'], @$lists['order'] ); ?><br />
          <?php echo JHTML::_('grid.sort',   'Course_group', 'course_group', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="10%" class="title" >
          <?php echo JHTML::_('grid.sort',   'Academic_degree', 'academic_degree', @$lists['order_Dir'], @$lists['order'] ); ?><br />
          <?php echo JHTML::_('grid.sort',   'Academic_rank', 'academic_rank', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>         
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'Organisation', 'organisation', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'country', 'country', @$lists['order_Dir'], @$lists['order'] ); ?>
          <?php echo JHTML::_('grid.sort',   'city', 'city', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" >
          <?php echo JHTML::_('grid.sort',   'Hostel', 'hostel', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" nowrap="nowrap">
          <?php echo JText::_('Phone'); ?>
        </th>
        <th width="10%" class="title">
          <?php echo JHTML::_('grid.sort',   'E-Mail', 'email', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th>
        <th width="5%" class="title" nowrap="nowrap">
          <?php echo JText::_('Register Date'); ?>
        </th>
        <th width="5%" class="title">
          <?php echo JText::_('Last Visit'); ?>
        </th>
      </tr>
    </thead>
<!--Подвал-->
    <tfoot>
      <tr>
        <td colspan="15">
          <?php echo $pageNav->getListFooter(); ?>
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
        
        $img  = $row->hostel ?  'tick.png':'publish_x.png';
        $alt  = $row->hostel ? JText::_( 'Hostel' ) : JText::_( 'No hostel' );
		
		if($row->tid){
			$tid = implode(',',$row->tid);
		}else{
		$tid = '';
		}
		
        $link   = 'index.php?option=com_conference&amp;act=reg&amp;task=edit&amp;cid='. $row->id. '&amp;usid='.$row->usid.'&amp;hotid='.$row->hotid.'&amp;tid='.$tid;
        
        if ($row->lastvisitDate == "0000-00-00 00:00:00") {
          $lastvisit = JText::_( 'Never' );
        } else {
          $lastvisit  = JHTML::_('date', $row->lastvisitDate, '%Y-%m-%d %H:%M:%S');
        }
      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
          <?php echo $i+1+$pageNav->limitstart;?>
        </td>
        <td>
          <?php echo JHTML::_('grid.id', $i, $row->username, false, 'username'); ?>
        </td>
        <td>
          <a href="<?php echo $link; ?>">
            <?php echo $row->surname.' '.$row->name; ?></a>
        </td>
        <td>
          <?php echo $row->username; ?>
        </td>
        <td>
          <?php echo $row->dept; ?><br />
          <?php echo $row->course_group; ?>
        </td>
        <td>
          <?php echo $row->academic_degree; ?><br />
          <?php echo $row->academic_rank; ?>
        </td>
        <td>
          <?php echo $row->organisation; ?>
        </td>
        <td>
          <?php echo $row->country; ?><br />
          <?php echo $row->city; ?>
        </td>
        <td>
          <img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
        </td>
        <td>
          <?php echo $row->phone; ?>
        </td>
        <td>
          <a href="mailto:<?php echo $row->email; ?>">
            <?php echo $row->email; ?></a>
        </td>
        <td align="center">
          <?php echo $row->registerDate;?>
        </td>
        <td nowrap="nowrap">
          <?php echo $lastvisit; ?>
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
  
  function setRegToolbar()
  {
    $task = JRequest::getVar( 'task', '', 'method', 'string');
//заголовок
    JToolBarHelper::title( $task == 'add' ? JText::_( 'Member' ). ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>' : JText::_( 'Member') . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'generic.png' );
//управляющие кнопки: сохранить, применить, отмена
    JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::cancel();
  }

  function Reg($row, $user, $hotel, $tesis)
  {
//вызов заголовка и кнопок
    ConferencesViewReg::setRegToolbar();
//делает меню не активным   
    JRequest::setVar( 'hidemainmenu', 1 );
    
    jimport('joomla.html.pane');
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
    $pane = &JPane::getInstance('sliders', array('allowAllClose' => true));   

// очистка данных записи
  JFilterOutput::objectHTMLSafe( $user, ENT_QUOTES, '' );

  if ($user->get('lastvisitDate') == "0000-00-00 00:00:00") {
    $lvisit = JText::_( 'Never' );
  } else {
    $lvisit = JHTML::_('date', $user->get('lastvisitDate'), '%Y-%m-%d %H:%M:%S');
  }
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
    if (trim(form.username.value) == "") {
      alert( "<?php echo JText::_( 'You must provide a username.', true ); ?>" );
    } else if (form.email.value == "") {
      alert( "<?php echo JText::_( 'You must provide a email.', true ); ?>" );
    } else {
      submitform( pressbutton );
    }
    
  }
</script>
<form action="<?php JRoute::_('index.php?option=com_conference&act=reg');?>" method="post" name="adminForm" autocomplete="off">
<!--Член конференции-->
<div class="col width-30">
  <fieldset class="adminform">
    <legend><?php echo JText::_( 'Member Details' ); ?></legend>
      <table class="admintable" cellspacing="1">
        <tr>
          <td height="0" class="key">
          <label id="surnamemsg" for="surname">
          <?php echo JText::_( 'surname' ); ?>:
          </label>
          </td>
          <td height="30">
            <input type="text" name="surname" id="surname" size="45" value="<?php echo $row->surname;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="namemsg" for="name">
            <?php echo JText::_( 'name' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="name" id="name" size="45" value="<?php echo $row->name;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="sexmsg" for="sex">
            <?php echo JText::_( 'sex' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="sex" id="sex" size="45" value="<?php echo $row->sex;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="date_of_birthmsg" for="date_of_birth ">
            <?php echo JText::_( 'date_of_birth ' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="date_of_birth " id="date_of_birth " size="45" value="<?php echo $row->date_of_birth ;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="statusmsg" for="status">
            <?php echo JText::_( 'status' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="status" id="status " size="45" value="<?php echo $row->status ;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="academic_degreemsg" for="academic_degree">
            <?php echo JText::_( 'academic_degree' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="academic_degree" id="academic_degree" size="45" value="<?php echo $row->academic_degree;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="academic_rankmsg" for="academic_rank">
            <?php echo JText::_( 'academic_rank' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="academic_rank" id="academic_rank" size="45" value="<?php echo $row->academic_rank;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="jobmsg" for="job">
            <?php echo JText::_( 'job' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="job" id="job" size="45" value="<?php echo $row->job;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="organisationmsg" for="organisation">
            <?php echo JText::_( 'organisation' ); ?>:
            </label>
          </td>
          <td>
            <textarea class="inputbox" cols="25" rows="5" name="organisation" id="organisation"><?php echo $row->organisation;?></textarea>
          </td>
        </tr>
        
		<tr>
          <td height="30" class="key">
            <label id="deptmsg" for="dept">
            <?php echo JText::_( 'dept' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="dept" id="dept" size="45" value="<?php echo $row->dept;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
		<tr>
          <td height="30" class="key">
            <label id="course_groupmsg" for="dept">
            <?php echo JText::_( 'course_group' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="course_group" id="course_group" size="45" value="<?php echo $row->course_group;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
		<tr>
          <td height="30" class="key">
            <label id="countrymsg" for="country">
            <?php echo JText::_( 'country' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="country" id="country" size="45" value="<?php echo $row->country;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="regionmsg" for="region">
            <?php echo JText::_( 'region' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="region" id="region" size="45" value="<?php echo $row->region;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="citymsg" for="city">
            <?php echo JText::_( 'city' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="city" id="city" size="45" value="<?php echo $row->city;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="streetmsg" for="street">
            <?php echo JText::_( 'street' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="street" id="street" size="45" value="<?php echo $row->street;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="housemsg" for="house">
            <?php echo JText::_( 'house' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="house" id="house" size="45" value="<?php echo $row->house;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="flatmsg" for="flat">
            <?php echo JText::_( 'flat' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="flat" id="flat" size="45" value="<?php echo $row->flat;?>" class="inputbox" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="post_codemsg" for="post_code">
            <?php echo JText::_( 'post_code' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="post_code" id="post_code" size="45" value="<?php echo $row->post_code;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="phonemsg" for="phone">
            <?php echo JText::_( 'phone' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="phone" id="phone" size="45" value="<?php echo $row->phone;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="faxmsg" for="fax">
            <?php echo JText::_( 'fax' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="fax" id="fax" size="45" value="<?php echo $row->fax;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="emailmsg" for="email">
            <?php echo JText::_( 'Email' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" id="email" name="email" size="40" value="<?php echo $row->email;?>" class="inputbox required validate-email" maxlength="100" /> *
          </td>
        </tr>
        <tr>
          <td height="30" class="key">
            <label id="acomodation_personmsg" for="acomodation_person">
            <?php echo JText::_( 'acomodation_person' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="acomodation_person" id="acomodation_person" size="45" value="<?php echo $row->acomodation_person;?>" maxlength="50" />
          </td>
        </tr>
		<tr>
          <td height="30" class="key">
            <label id="hostelmsg" for="hostel">
            <?php echo JText::_( 'hostel' ); ?>:
            </label>
          </td>
          <td>
            <input type="text" name="hostel" id="hostel" size="45" value="<?php echo $row->hostel;?>" maxlength="50" />
          </td>
        </tr>
        <tr>
          <td colspan="2" height="30" class="key">
            <b><?php echo JText::_( 'REGISTER_REQUIRED' ); ?></b>
          </td>
        </tr> 
      </table>
    </fieldset>
  </div>
<!--Пользователь--> 
  <div class="col width-30">
    <fieldset class="adminform">
      <legend><?php echo JText::_( 'User' ); ?></legend>
        <table class="admintable">
          <tr>
            <td height="30" class="key">
              <label id="usernamemsg" for="username">
              <?php echo JText::_( 'Username' ); ?>:
              </label>
            </td>
            <td>
              <input type="text" name="username" id="username" size="30" value="<?php echo $user->username;?>" class="inputbox required" maxlength="50" /> *
            </td>
          </tr>
          <tr>
            <td class="key">
              <label for="password">
              <?php echo JText::_( 'New Password' ); ?>
              </label>
            </td>
            <td>
              <?php if($user->password) : ?>
              <input class="inputbox disabled" type="password" name="password" id="password" size="30" value="" disabled="disabled" />
              <?php else : ?>
              <input class="inputbox" type="password" name="password" id="password" size="30" value=""/>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <label for="password2">
              <?php echo JText::_( 'Verify Password' ); ?>
              </label>
            </td>
            <td>
              <?php if($user->password) : ?>
              <input class="inputbox disabled" type="password" name="password2" id="password2" size="30" value="" disabled="disabled" />
              <?php else : ?>
              <input class="inputbox" type="password" name="password2" id="password2" size="30" value=""/>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <?php echo JText::_( 'User type' ); ?>
            </td>
            <td>
              <?php echo $user->get('usertype');?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <?php echo JText::_( 'Register Date' ); ?>
            </td>
            <td>
              <?php echo JHTML::_('date', $user->get('registerDate'), '%Y-%m-%d %H:%M:%S');?>
            </td>
          </tr>
          <tr>
            <td class="key">
              <?php echo JText::_( 'Last Visit Date' ); ?>
            </td>
            <td>
              <?php echo $lvisit; ?>
            </td>
          </tr>
        </table>
    </fieldset>
  </div>
<!--Гостиница-->
  <div class="col width-30">
    <fieldset width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
      <legend><?php echo JText::_( 'Hotel' ); ?></legend>
        <table class="admintable">
          <tr>
            <td>
              <strong>
              <?php echo JText::_( 'hotel' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->hotel;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
                <?php echo JText::_( 'type_room' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->type_room;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
                <?php echo JText::_( 'visit_date' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->visit_date;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
                <?php echo JText::_( 'exit_date' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->exit_date;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
                <?php echo JText::_( 'address' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->address;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
                <?php echo JText::_( 'wish' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $hotel->wish;?>
            </td>
          </tr>
        </table>
    </fieldset>
  </div>  
<!--Тезисы-->
  <div class="col width-60">
    <fieldset width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
      <legend><?php echo JText::_( 'Tesis' ); ?></legend>
        <table class="admintable">
  <?php for ($i=0, $n=count( $tesis ); $i < $n; $i++) { ?>
          <tr>
            <td>
              <strong>
              <?php echo JText::_( 'authors' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $tesis[$i]->authors;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
              <?php echo JText::_( 'name_lecture' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $tesis[$i]->name_lecture;?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
              <?php echo JText::_( 'type_lecture' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $tesis[$i]->type_lecture;
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>
              <?php echo JText::_( 'sections' ); ?>:
              </strong>
            </td>
            <td>
              <?php echo $tesis[$i]->sections;
              ?>
            </td>
          </tr>
          <tr>
            <td colspan=2>
              <a href="<?=$tesis[$i]->file_name?>"><?= JText::_( 'show' ) ?></a>
            </td>
          </tr>
          <tr>
            <td colspan=2>
            <hr />
            </td>
          </tr>
  <?php } ?>
        </table>
    </fieldset>
  </div>

  <div class="clr"></div>
  <input type="hidden" name="gid" value="18" />
  <input type="hidden" name="usertype" value="Registered" />
  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="option" value="com_conference" />
  <input type="hidden" name="cid" value="<?php echo $row->id; ?>" />
  <input type="hidden" name="usid" value="<?php echo $user->id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="reg" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
  }
}
  ?>