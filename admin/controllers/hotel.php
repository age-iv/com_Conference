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

//���������� ����������, ����������� ��
  $db =& JFactory::getDBO();

    $context      = 'com_conference.conferencehotel.list.';
//������ �� ����� ��������� �������, �� ��������� surname
    $filter_order   = $mainframe->getUserStateFromRequest( $context.'filter_order',   'filter_order',   'surname',  'cmd' );
//���������� ����������� ������� ���������� (�� ����������� ��� �� ��������)
    $filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir', 'filter_order_Dir', '',     'word' );
    $search       = $mainframe->getUserStateFromRequest( $context.'search',     'search',     '',     'string' );

//���������� �����
    $limit    = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
//���������� �����, � �������� ��������
    $limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );

    $where = array();
//���������� �� ������
    if ($search) {
      $where[] = 'LOWER(surname) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(name) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(hotel) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
	  .'OR LOWER(type_room) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      ;
    }

    $where    = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
//������������
    $orderby  = ' ORDER BY '. $filter_order.' '.$filter_order_Dir;
//������ � ������� - ����������� ���������� �������
    $query = 'SELECT COUNT(*)'
    . ' FROM #__hotel'
    . $where
    ;
//�������� ������ � ��
    $db->setQuery( $query );
//���������� ����������
    $total = $db->loadResult();

//������ ������ pagination
    jimport('joomla.html.pagination');
//���������� ����� ������
    $pageNav = new JPagination( $total, $limitstart, $limit );

//������ � ������� - ����� ����������� ��������
    $query = 'SELECT a.*, b.username, b.surname, b.name, b.city, b.email'
    . ' FROM #__hotel a'
	. ' LEFT JOIN #__register b ON a.username = b.username'
    //. ' WHERE a.username = b.username'
    . $where
	. $orderby
    ;
//�������� ������ � ��
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
//���������� ����������
    $rows = $db->loadObjectList();

    $javascript   = 'onchange="document.adminForm.submit();"';

//������� � �������
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order']   = $filter_order;

//��������� ������
    $lists['search']= $search;

//����������� ����    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'hotel.php');
//����� ��������� �����������
  ConferencesViewHotel::Hotels( $rows, $pageNav, $lists );
  
  }
  
  function edit()
  {
//���������� ����������, ����������� ��
  $db =& JFactory::getDBO();

//���� ��������������, �������� �������� cid
    if ($this->_task == 'edit') {
      $cid  = JRequest::getVar('cid', array(0), 'method', 'array');
      $cid  = array((int) $cid[0]);
    } else {
      $cid  = array( 0 );
    }
    
//������������ �������  
  $row =& JTable::getInstance('Hotel', 'Table');
//�������  �������� �� ��������� cid
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
    
//����������� ����    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'hotel.php');
//����� ��������� �����������
  ConferencesViewHotel::Hotel($row, $count_users, $arr_users);
  }
  
  function cancel()
  {
//��������� 
    $msg = JText::_( 'Operation Cancelled' );
//��������
    $this->setRedirect( 'index.php?option=com_conference&act=hotel', $msg );
  }
  
    function save()
  {
    global $mainframe;

    JRequest::checkToken() or jexit( 'Invalid Token' );
    $option = JRequest::getCmd( 'option');
//���������� ����������
    $db =& JFactory::getDBO();    

    $post = JRequest::get('post');

//����������� ������� hotel
  $row =& JTable::getInstance('hotel', 'Table');
//�������� ������� $post
    if (!$row->bind( $post )) {
      return JError::raiseWarning( 500, $row->getError() );
    }

    if (!$row->check()) {
      return JError::raiseWarning( 500, $row->getError() );
    }
//����������
    if (!$row->store()) {
      return JError::raiseWarning( 500, $row->getError() );
    }
      
    $row->checkin();
//��������, �������������� ��� ��������� � ���������
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
//��������
    $this->setRedirect( 'index.php?option=com_conference&act=hotel' );

//���������� ����������
    $db   =& JFactory::getDBO();
    $cid  = JRequest::getVar( 'cid', array(), 'post', 'array' );
    JArrayHelper::toInteger($cid);
//����������� ���������� �������
    $n    = count( $cid );
//���� ���� ������
    if ($n)
    {
//������� ���������� ������
      $query = 'DELETE FROM #__hotel'
      . ' WHERE id = "' . implode( '"  OR id = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//���� �� �������� ������
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
    }
//���������: ��� ������!
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
//���� �� �������� ������
    if (!$db->query()) {
      JError::raiseWarning( 500, $db->getError() );
    }
    $total = $db->loadAssocList();
  
foreach ($total as $line)
  {
    foreach ($line as $ln)
    {
//��������������� �����
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