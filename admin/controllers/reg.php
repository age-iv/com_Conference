<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


class ConferencesControllerReg extends JController
{

  function __construct( $config = array() )
  {
    parent::__construct( $config );
    
    $this->registerTask( 'add',     'edit' );
    $this->registerTask( 'apply',   'save' );
    $this->registerTask( 'resethits', 'save' );
    $this->registerTask( 'unpublish', 'publish' );
  }
  
  function display()
  {
    global $mainframe;

//объявление переменной, определение БД
    $db =& JFactory::getDBO();

    $context      = 'com_conference.conferencereg.list.';
//фильтр по полям заголовка таблицы, по умолчанию surname
    $filter_order   = $mainframe->getUserStateFromRequest( $context.'filter_order',   'filter_order',   'surname',  'cmd' );
//переменная указывающая порядок сортировки (по возрастанию или по убыванию)
    $filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '',     'word' );
    $search       = $mainframe->getUserStateFromRequest( $context.'search',     'search',     '',     'string' );

//количество строк
    $limit    = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
//количество строк, с которого начинать
    $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

    $where = array();
//фильтрация по поиску
    if ($search) {
      $where[] = 'LOWER(surname) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(organisation) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(city) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(country) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    }

    $where    = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
//упорядочение
    $orderby  = ' ORDER BY '. $filter_order.' '.$filter_order_Dir;
//запрос к таблице - определение количества записей
    $query = 'SELECT COUNT(*)'
    . ' FROM #__register'
    . $where
    ;
//посылаем запрос в БД
    $db->setQuery( $query );
//возвращает результаты
    $total = $db->loadResult();

//импорт класса pagination
    jimport('joomla.html.pagination');
//объявление этого класса
    $pageNav = new JPagination( $total, $limitstart, $limit );

//запрос к таблице - выбор необходимых значений
    $query = 'SELECT *'
    . ' FROM #__register'
    . $where
    . $orderby
    ;
//посылаем запрос в БД
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
//возвращает результаты
    $rows = $db->loadObjectList();

//выбираем из таблицы Пользователи
    $query = 'SELECT id, username, lastvisitDate, registerDate, usertype'
    . ' FROM #__users'
    ;
    $db->setQuery( $query );
    $tmp = $db->loadObjectList();
//сопоставляет username из разных таблиц
for ($i=0;$i<count($tmp);$i++)
{
  for ($j=0;$j<count($rows);$j++)   
  {
    if ($tmp[$i]->username === $rows[$j]->username)
    {
      $rows[$j]->lastvisitDate = $tmp[$i]->lastvisitDate;
      $rows[$j]->registerDate = $tmp[$i]->registerDate;
      $rows[$j]->usertype = $tmp[$i]->usertype;
      $rows[$j]->usid = $tmp[$i]->id;
    }
  }
}

//выбираем из таблицы Hotel
    $query = 'SELECT *'
    . ' FROM #__hotel'
    ;
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
    $htl = $db->loadObjectList();
//сопоставляет username из разных таблиц
for ($i=0;$i<count($htl);$i++)
{
  for ($j=0;$j<count($rows);$j++)   
  {
    if ($htl[$i]->username === $rows[$j]->username)
    {
      $rows[$j]->hotel = $htl[$i]->hotel;
      $rows[$j]->type_room = $htl[$i]->type_room;
      $rows[$j]->visit_date = $htl[$i]->visit_date;
      $rows[$j]->exit_date = $htl[$i]->exit_date;
      $rows[$j]->wish = $htl[$i]->wish;
      $rows[$j]->hotid = $htl[$i]->id;
    }
  }
} 

//выбираем из таблицы Тезисы
    $query = 'SELECT *'
    . ' FROM #__tesis'
    ;
    $db->setQuery( $query );
    $ts = $db->loadObjectList();
//сопоставляет username из разных таблиц
for ($i=0;$i<count($ts);$i++)
{
  for ($j=0;$j<count($rows);$j++)   
  {
    if ($ts[$i]->username === $rows[$j]->username)
    {
      $rows[$j]->authors[$i] = $ts[$i]->authors;
      $rows[$j]->name_lecture[$i] = $ts[$i]->name_lecture;
      $rows[$j]->type_lecture[$i] = $ts[$i]->type_lecture;
      $rows[$j]->tid[$i] = $ts[$i]->id;
    }
  }
} 

    $javascript   = 'onchange="document.adminForm.submit();"';

//порядок в таблице
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order']   = $filter_order;

//поисковый фильтр
    $lists['search']= $search;    
//подключение вида    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'reg.php');
//вызов процедуры отображения
  ConferencesViewReg::Regs( $rows, $pageNav, $lists );
  }
  
  function edit()
  {
//объявление переменной, определение БД
    $db =& JFactory::getDBO();
  
//если редактирование, получает значение cid
    if ($this->_task == 'edit') {
      $cid  = JRequest::getVar('cid', array(0), 'method', 'array');
      $cid  = array((int) $cid[0]);
    } else {
      $cid  = array( 0 );
    }
//если редактирование, получает значение usid
    if ($this->_task == 'edit') {
      $usid = JRequest::getVar('usid', array(0), 'method', 'array');
      $usid = array((int) $usid[0]);
    } else {
      $usid = array( 0 );
    }
//если редактирование, получает значение hotid
    if ($this->_task == 'edit') {
      $hotid  = JRequest::getVar('hotid', array(0), 'method', 'array');
      $hotid  = array((int) $hotid[0]);
    } else {
      $hotid  = array( 0 );
    }

//если редактирование, получает значение tid
    if ($this->_task == 'edit') {
      $tid  = JRequest::getVar('tid');
    //  $tid  = explode(',',$tid[0]);//array((int) $tid[0]);
    } else {
      $tid  = array( 0 );
    }
  
//подключается таблица  
  $row =& JTable::getInstance('Reg', 'Table');
//выводит  значения по заданному cid
  $row->load( $cid[0] );
//таблица пользователей
  $user = & JUser::getTable();
//выводит  значения связанного пользователя
  $user ->load($usid[0]);
//подключается таблица Hotel
  $hotel =& JTable::getInstance('Hotel', 'Table');
//выводит  значения по заданному hotid
  $hotel->load( $hotid[0] );
  
  
    
    $query = 'SELECT *'
    . ' FROM #__tesis '
    . 'WHERE id IN ('.$tid.')'
    ;
    $db->setQuery( $query );
    $tesis = $db->loadObjectList();
/*//подключается таблица Tesis
$tesis =& JTable::getInstance('Tesis', 'Table');
for($i=0;$i<count($tid);$i++) {
  
//выводит  значения по заданному tid
  $tesis->load( $tid[$i] );
}*/
//подключение вида  
  require_once(JPATH_COMPONENT.DS.'views'.DS.'reg.php');  
//вызов процедуры отображения
  ConferencesViewReg::Reg($row, $user, $hotel, $tesis);
  }
  
  function cancel()
  {
//сообщение 
    $msg = JText::_( 'Operation Cancelled' );
//редирект
    $this->setRedirect( 'index.php?option=com_conference&act=reg', $msg );
  }
  
  function save()
  {
    global $mainframe;

    JRequest::checkToken() or jexit( 'Invalid Token' );
    $option = JRequest::getCmd( 'option');
//объявление переменных
    $db =& JFactory::getDBO();    
    $me = & JFactory::getUser();
    $MailFrom = $mainframe->getCfg('mailfrom');
    $FromName = $mainframe->getCfg('fromname');
    $SiteName = $mainframe->getCfg('sitename');

    $post = JRequest::get('post');

    
//создание нового JUser объекта, по умолчанию id 0
    $ch_user = new JUser(JRequest::getVar( 'usid', 0, 'post', 'int'));
//установка имени пользователя
    $ch_user->set('name',$post['surname'].' '.$post['name']);
//массив из post для пользователя
    $post_user['id'] = $ch_user->get('id');
    $post_user['name'] = $ch_user->get('name');
    $post_user['username'] = $post['username'];
    $post_user['email'] = $post['email'];
    $post_user['password'] = $post['password'];
    $post_user['password2'] = $post['password2'];
    $post_user['usertype'] = $post['usertype'];
    $post_user['gid'] = $post['gid'];
    $post_user['params'] = "language=ru-RU \n timezone=3";
    
    $original_gid = $post['gid'];
    
    $post['username'] = JRequest::getVar('username', '', 'post', 'username');
    $post['password'] = JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
    $post['password2']  = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);

//проверка массива, шифрование пароля и прочее
    if (!$ch_user->bind($post_user))
    {
      $mainframe->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
      $mainframe->enqueueMessage($ch_user->getError(), 'error');
      return $this->execute('edit');
    }

//проверяет не является ли пользователь новым
    $isNew  = ($ch_user->get('id') < 1);
//сохранение пользователя
    if (!$ch_user->save())
    {

      $mainframe->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
      $mainframe->enqueueMessage($ch_user->getError(), 'error');
      return $this->execute('edit');
    }
//Если новый пользователь, то отправляется e-mail, что он добавлен
    if ($isNew)
    {
      $adminEmail = $me->get('email');
      $adminName  = $me->get('name');

      $subject = JText::_('NEW_USER_MESSAGE_SUBJECT');
      $message = sprintf ( JText::_('NEW_USER_MESSAGE'), $ch_user->get('name'), $SiteName, JURI::root(), $ch_user->get('username'), $ch_user->password_clear );

      if ($MailFrom != '' && $FromName != '')
      {
        $adminName  = $FromName;
        $adminEmail = $MailFrom;
      }
      JUtility::sendMail( $adminEmail, $adminName, $ch_user->get('email'), $subject, $message );
    }
    
//подключение таблицы reg
  $row =& JTable::getInstance('reg', 'Table');
//проверка массива $post
    if (!$row->bind( $post )) {
      return JError::raiseWarning( 500, $row->getError() );
    }
// это проверка валидности данных, отключена, иначе будет выпадать ошибка...админу можно все
/*    if (!$row->check()) {
      return JError::raiseWarning( 500, $row->getError() );
    } */
//сохранение
    if (!$row->store()) {
      return JError::raiseWarning( 500, $row->getError() );
    }
    
    $row->checkin();
//редирект, соответственно для сохранить и применить
    $task = JRequest::getCmd( 'task' );
        switch ($task)
    {
      case 'apply':
        $link = 'index.php?option=com_conference&act=reg&task=edit&cid[]='. $post['cid']. '&usid[]='.$post['usid'].'' ;
        break;
      case 'save':
      default:
        $link = 'index.php?option=com_conference&act=reg';
        break;
    }

    $this->setRedirect( $link, JText::_( 'Item Saved' ) );
  }
  
  function remove()
  {
// Check for request forgeries
    JRequest::checkToken() or jexit( 'Invalid Token' );
//редирект
    $this->setRedirect( 'index.php?option=com_conference&act=reg' );

//объявление переменных
    $db   =& JFactory::getDBO();
    $cid  = JRequest::getVar( 'username', array(), 'post', 'array' );
//определение количества записей
    $n    = count( $cid );
//если есть записи

    if ($n)
    {
//удаляет членов конференции
      $query = 'DELETE FROM #__register'
      . ' WHERE username = "' . implode( '"  OR username = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//если не проходит запрос
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
//удаляем пользователей
      $query = 'DELETE FROM #__users'
      . ' WHERE username = "' . implode( '"  OR username = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//если не проходит запрос
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
    }
//сообщение: все удачно!
    $this->setMessage( JText::sprintf( 'Items removed', $n ) );
  }
  
  function toCSV()
  {
	jimport( 'joomla.filesystem.file' );
	
    $db =& JFactory::getDBO();
    
      $query = 'SELECT a.*, b.*, c.* FROM #__register a'
			.' LEFT JOIN #__hotel b ON a.username = b.username'
			.' LEFT JOIN #__tesis c ON a.username = c.username'
			;

    $db->setQuery( $query );
    if (!$db->query()) {
      JError::raiseWarning( 500, $db->getError() );
    }
    $total = $db->loadObjectList();

  foreach ($total as $line)
  {
    foreach ($line as $ln)
    {
//перекодирование строк
    $ln = iconv("UTF-8", "CP1251//IGNORE", $ln);  
	$fp = $fp.$ln.';';
    }
	$fp = $fp."\n";
  }
  JFile::write(JPATH_SITE.DS.'uploads'.DS.'export'.DS.JFactory::getDate()->toFormat('%d.%m.%Y').'-registration.csv', $fp);
  
  $link = DS.'uploads'.DS.'export'.DS.JFactory::getDate()->toFormat('%d.%m.%Y').'-registration.csv';
  $this->setRedirect( $link, JText::_( 'Export complete' ) );
  }
}
?>