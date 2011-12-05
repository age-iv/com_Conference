<?php
// ������ ������ �����������
defined( '_JEXEC' ) or die( 'Restricted access' );

//���������� ������������� �� ����
$user = & JFactory::getUser();
//���� ���, ������� �����������
if (!$user->authorize( 'com_banners', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

// ������������� ���������� �������
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_conference'.DS.'tables');

//�������� �������� ������
$controllerName = JRequest::getCmd( 'act', 'conference' );

//���� �������� conference, ������ �� ������
switch ($controllerName)
{
	default:
	case 'conference':
	JToolBarHelper::title(JText::_('Conference'));
	JSubMenuHelper::addEntry(JText::_('Conference'));
	break;
//���� �������� reg, �������� �����������
	case 'reg':
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg',true);
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel');
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis');
		break;
//���� �������� hotel, �������� ���������
	case 'hotel':
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg');
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel',true);
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis');
		break;
//���� �������� tesis, �������� ������
	case 'tesis': 
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg');
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel');
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis',true);
	break;
//���� �������� params, �������� ���������
}

switch ($controllerName)
{
//�� ��������� ����������� conference
	default:
		$controllerName = 'conference';
//���� ����� conference, �� ������ �� ������
	case 'conference' :
//���� ����� reg	
	case 'reg' :
//��������� �������� ������
		$task = JRequest::getCmd('act');
//�������� ���� �� � �������� ������ reg
		if ($task == 'reg') {
//���� ��, ����������� ����������
			$controllerName = 'reg';
		}
//���������� ����������
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// ��� ����������� = conferencesController + hotel, reg, tesis ��� conference, � ����������� �� switch
$controllerName    = 'ConferencesController'.$controllerName;
//������������ ����� �����
$controller   = new $controllerName();

// ��������� ������ Request
$controller->execute( JRequest::getCmd( 'task' ) );

// �������������, ���� ������� � �����������
$controller->redirect();
break;
//���� ����� hotel
	case 'hotel':
//��������� �������� ������
		$task = JRequest::getCmd('act');
//�������� ���� �� � �������� ������ hotel
		if ($task == 'hotel') {
//���� ��, ����������� ����������
			$controllerName = 'hotel';
		}
//���������� ����������
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// ��� ����������� = conferencesController + hotel ��� reg, � ����������� �� switch
$controllerName    = 'conferencesController'.$controllerName;
//������������ ����� �����
$controller   = new $controllerName();

// ��������� ������ Request
$controller->execute( JRequest::getCmd( 'task' ) );

// �������������, ���� ������� � �����������
$controller->redirect();
break;
	case 'tesis':
//��������� �������� ������
		$task = JRequest::getCmd('act');
//�������� ���� �� � �������� ������ hotel
		if ($task == 'tesis') {
//���� ��, ����������� ����������
			$controllerName = 'tesis';
		}
//���������� ����������
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// ��� ����������� = conferencesController + hotel ��� reg, � ����������� �� switch
$controllerName    = 'conferencesController'.$controllerName;
//������������ ����� �����
$controller   = new $controllerName();

// ��������� ������ Request
$controller->execute( JRequest::getCmd( 'task' ) );

// �������������, ���� ������� � �����������
$controller->redirect();
break;
}
?>