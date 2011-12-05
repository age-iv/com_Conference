<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


class ConferencesControllerHotel extends JController
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

//объ€вление переменной, определение Ѕƒ
  $db =& JFactory::getDBO();

    $context      = 'com_conference.conferencehotel.list.';
//фильтр по пол€м заголовка таблицы, по умолчанию surname
    $filter_order   = $mainframe->getUserStateFromRequest( $context.'filter_order',   'filter_order',   'surname',  'cmd' );
//переменна€ указывающа€ пор€док сортировки (по возрастанию или по убыванию)
    $filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '',     'word' );
    $search       = $mainframe->getUserStateFromRequest( $context.'search',     'search',     '',     'string' );

//количество строк
    $limit    = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
//количество строк, с которого начинать
    $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

    $where = array();
//фильтраци€ по поиску
    if ($search) {
      $where[] = 'LOWER(surname) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(hotel) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
	  .'OR LOWER(type_room) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      ;
    }

    $where    = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
//упор€дочение
    $orderby  = ' ORDER BY '. $filter_order.' '.$filter_order_Dir;
//запрос к таблице - определение количества записей
    $query = 'SELECT COUNT(*)'
    . ' FROM #__hotel'
    . $where
    ;
//посылаем запрос в Ѕƒ
    $db->setQuery( $query );
//возвращает результаты
    $total = $db->loadResult();

//импорт класса pagination
    jimport('joomla.html.pagination');
//объ€вление этого класса
    $pageNav = new JPagination( $total, $limitstart, $limit );

//запрос к таблице - выбор необходимых значений
    $query = 'SELECT a.*, b.username, b.surname, b.name, b.city, b.email'
    . ' FROM #__hotel a'
	. ' LEFT JOIN #__register b ON a.username = b.username'
    //. ' WHERE a.username = b.username'
    . $where
	. $orderby
    ;
//посылаем запрос в Ѕƒ
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
//возвращает результаты
    $rows = $db->loadObjectList();

    $javascript   = 'onchange="document.adminForm.submit();"';

//пор€док в таблице
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order']   = $filter_order;

//поисковый фильтр
    $lists['search']= $search;

//подключение вида    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'hotel.php');
//вызов процедуры отображени€
  ConferencesViewHotel::Hotels( $rows, $pageNav, $lists );
  
  }
  
  function edit()
  {
//объ€вление переменной, определение Ѕƒ
  $db =& JFactory::getDBO();

//если редактирование, получает значение cid
    if ($this->_task == 'edit') {
      $cid  = JRequest::getVar('cid', array(0), 'method', 'array');
      $cid  = array((int) $cid[0]);
    } else {
      $cid  = array( 0 );
    }
    
//подключаетс€ таблица  
  $row =& JTable::getInstance('Hotel', 'Table');
//выводит  значени€ по заданному cid
  $row->load( $cid[0] );
  
    $query = 'SELECT COUNT(*)'
    . ' FROM #__users'
    ;
    $db->setQuery( $query);
    $count_users = $db->loadResult();

    $query = 'SELECT username, name'
    . ' FROM #__users'
    ;
    $db->setQuery( $query);
    $arr_users = $db->loadObjectList();
    
//подключение вида    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'hotel.php');
//вызов процедуры отображени€
  ConferencesViewHotel::Hotel($row, $count_users, $arr_users);
  }
  
  function cancel()
  {
//сообщение 
    $msg = JText::_( 'Operation Cancelled' );
//редирект
    $this->setRedirect( 'index.php?option=com_conference&act=hotel', $msg );
  }
  
    function save()
  {
    global $mainframe;

    JRequest::checkToken() or jexit( 'Invalid Token' );
    $option = JRequest::getCmd( 'option');
//объ€вление переменных
    $db =& JFactory::getDBO();    

    $post = JRequest::get('post');

//подключение таблицы hotel
  $row =& JTable::getInstance('hotel', 'Table');
//проверка массива $post
    if (!$row->bind( $post )) {
      return JError::raiseWarning( 500, $row->getError() );
    }

    if (!$row->check()) {
      return JError::raiseWarning( 500, $row->getError() );
    }
//сохранение
    if (!$row->store()) {
      return JError::raiseWarning( 500, $row->getError() );
    }
      
    $row->checkin();
//редирект, соответственно дл€ сохранить и применить
    $task = JRequest::getCmd( 'task' );
        switch ($task)
    {
      case 'apply':
        $link = 'index.php?option=com_conference&act=hotel&task=edit&cid[]='. $post['cid'].'' ;
        break;
      case 'save':
      default:
        $link = 'index.php?option=com_conference&act=hotel';
        break;
    }

    $this->setRedirect( $link, JText::_( 'Item Saved' ) );
  }
  
    function remove()
  {
// Check for request forgeries
    JRequest::checkToken() or jexit( 'Invalid Token' );
//редирект
    $this->setRedirect( 'index.php?option=com_conference&act=hotel' );

//объ€вление переменных
    $db   =& JFactory::getDBO();
    $cid  = JRequest::getVar( 'cid', array(), 'post', 'array' );
    JArrayHelper::toInteger($cid);
//определение количества записей
    $n    = count( $cid );
//если есть записи
    if ($n)
    {
//удал€ет заказанные номера
      $query = 'DELETE FROM #__hotel'
      . ' WHERE id = "' . implode( '"  OR id = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//если не проходит запрос
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
    }
//сообщение: все удачно!
    $this->setMessage( JText::sprintf( 'Items removed', $n ) );
   // echo "<pre>"; print_r($cid);echo "</pre>";
  }
  
  function toCSV()
  {
	jimport( 'joomla.filesystem.file' );
	
    $db =& JFactory::getDBO();
    
    $query = 'SELECT a.username,a.surname,a.name,a.city,a.email,b.*'
    . ' FROM #__register a,#__hotel b'
    . ' WHERE a.username = b.username'
    . $orderby
    ;
    $db->setQuery( $query );
//если не проходит запрос
    if (!$db->query()) {
      JError::raiseWarning( 500, $db->getError() );
    }
    $total = $db->loadAssocList();
  
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
  JFile::write(JPATH_SITE.DS.'uploads'.DS.'export'.DS.JFactory::getDate()->toFormat('%d.%m.%Y').'-hotel.csv', $fp);
  
  $link = DS.'uploads'.DS.'export'.DS.JFactory::getDate()->toFormat('%d.%m.%Y').'-hotel.csv';
  $this->setRedirect( $link, JText::_( 'Export complete' ) );
  }
}
?>