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

//���������� ����������, ����������� ��
    $db =& JFactory::getDBO();

    $context      = 'com_conference.conferencereg.list.';
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
      .'OR LOWER(organisation) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(city) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
      .'OR LOWER(country) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
    }

    $where    = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
//������������
    $orderby  = ' ORDER BY '. $filter_order.' '.$filter_order_Dir;
//������ � ������� - ����������� ���������� �������
    $query = 'SELECT COUNT(*)'
    . ' FROM #__register'
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
    $query = 'SELECT *'
    . ' FROM #__register'
    . $where
    . $orderby
    ;
//�������� ������ � ��
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
//���������� ����������
    $rows = $db->loadObjectList();

//�������� �� ������� ������������
    $query = 'SELECT id, username, lastvisitDate, registerDate, usertype'
    . ' FROM #__users'
    ;
    $db->setQuery( $query );
    $tmp = $db->loadObjectList();
//������������ username �� ������ ������
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

//�������� �� ������� Hotel
    $query = 'SELECT *'
    . ' FROM #__hotel'
    ;
    $db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
    $htl = $db->loadObjectList();
//������������ username �� ������ ������
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

//�������� �� ������� ������
    $query = 'SELECT *'
    . ' FROM #__tesis'
    ;
    $db->setQuery( $query );
    $ts = $db->loadObjectList();
//������������ username �� ������ ������
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

//������� � �������
    $lists['order_Dir'] = $filter_order_Dir;
    $lists['order']   = $filter_order;

//��������� ������
    $lists['search']= $search;    
//����������� ����    
  require_once(JPATH_COMPONENT.DS.'views'.DS.'reg.php');
//����� ��������� �����������
  ConferencesViewReg::Regs( $rows, $pageNav, $lists );
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
//���� ��������������, �������� �������� usid
    if ($this->_task == 'edit') {
      $usid = JRequest::getVar('usid', array(0), 'method', 'array');
      $usid = array((int) $usid[0]);
    } else {
      $usid = array( 0 );
    }
//���� ��������������, �������� �������� hotid
    if ($this->_task == 'edit') {
      $hotid  = JRequest::getVar('hotid', array(0), 'method', 'array');
      $hotid  = array((int) $hotid[0]);
    } else {
      $hotid  = array( 0 );
    }

//���� ��������������, �������� �������� tid
    if ($this->_task == 'edit') {
      $tid  = JRequest::getVar('tid');
    //  $tid  = explode(',',$tid[0]);//array((int) $tid[0]);
    } else {
      $tid  = array( 0 );
    }
  
//������������ �������  
  $row =& JTable::getInstance('Reg', 'Table');
//�������  �������� �� ��������� cid
  $row->load( $cid[0] );
//������� �������������
  $user = & JUser::getTable();
//�������  �������� ���������� ������������
  $user ->load($usid[0]);
//������������ ������� Hotel
  $hotel =& JTable::getInstance('Hotel', 'Table');
//�������  �������� �� ��������� hotid
  $hotel->load( $hotid[0] );
  
  
    
    $query = 'SELECT *'
    . ' FROM #__tesis '
    . 'WHERE id IN ('.$tid.')'
    ;
    $db->setQuery( $query );
    $tesis = $db->loadObjectList();
/*//������������ ������� Tesis
$tesis =& JTable::getInstance('Tesis', 'Table');
for($i=0;$i<count($tid);$i++) {
  
//�������  �������� �� ��������� tid
  $tesis->load( $tid[$i] );
}*/
//����������� ����  
  require_once(JPATH_COMPONENT.DS.'views'.DS.'reg.php');  
//����� ��������� �����������
  ConferencesViewReg::Reg($row, $user, $hotel, $tesis);
  }
  
  function cancel()
  {
//��������� 
    $msg = JText::_( 'Operation Cancelled' );
//��������
    $this->setRedirect( 'index.php?option=com_conference&act=reg', $msg );
  }
  
  function save()
  {
    global $mainframe;

    JRequest::checkToken() or jexit( 'Invalid Token' );
    $option = JRequest::getCmd( 'option');
//���������� ����������
    $db =& JFactory::getDBO();    
    $me = & JFactory::getUser();
    $MailFrom = $mainframe->getCfg('mailfrom');
    $FromName = $mainframe->getCfg('fromname');
    $SiteName = $mainframe->getCfg('sitename');

    $post = JRequest::get('post');

    
//�������� ������ JUser �������, �� ��������� id 0
    $ch_user = new JUser(JRequest::getVar( 'usid', 0, 'post', 'int'));
//��������� ����� ������������
    $ch_user->set('name',$post['surname'].' '.$post['name']);
//������ �� post ��� ������������
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

//�������� �������, ���������� ������ � ������
    if (!$ch_user->bind($post_user))
    {
      $mainframe->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
      $mainframe->enqueueMessage($ch_user->getError(), 'error');
      return $this->execute('edit');
    }

//��������� �� �������� �� ������������ �����
    $isNew  = ($ch_user->get('id') < 1);
//���������� ������������
    if (!$ch_user->save())
    {

      $mainframe->enqueueMessage(JText::_('CANNOT SAVE THE USER INFORMATION'), 'message');
      $mainframe->enqueueMessage($ch_user->getError(), 'error');
      return $this->execute('edit');
    }
//���� ����� ������������, �� ������������ e-mail, ��� �� ��������
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
    
//����������� ������� reg
  $row =& JTable::getInstance('reg', 'Table');
//�������� ������� $post
    if (!$row->bind( $post )) {
      return JError::raiseWarning( 500, $row->getError() );
    }
// ��� �������� ���������� ������, ���������, ����� ����� �������� ������...������ ����� ���
/*    if (!$row->check()) {
      return JError::raiseWarning( 500, $row->getError() );
    } */
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
//��������
    $this->setRedirect( 'index.php?option=com_conference&act=reg' );

//���������� ����������
    $db   =& JFactory::getDBO();
    $cid  = JRequest::getVar( 'username', array(), 'post', 'array' );
//����������� ���������� �������
    $n    = count( $cid );
//���� ���� ������

    if ($n)
    {
//������� ������ �����������
      $query = 'DELETE FROM #__register'
      . ' WHERE username = "' . implode( '"  OR username = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//���� �� �������� ������
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
//������� �������������
      $query = 'DELETE FROM #__users'
      . ' WHERE username = "' . implode( '"  OR username = "', $cid ).'"'
      ;
      $db->setQuery( $query );
//���� �� �������� ������
      if (!$db->query()) {
        JError::raiseWarning( 500, $db->getError() );
      }
    }
//���������: ��� ������!
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
//��������������� �����
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