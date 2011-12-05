<?php
// пр€мой доступ отсутствует
defined( '_JEXEC' ) or die( 'Restricted access' );

//определ€ем авторизовалс€ ли юзер
$user = & JFactory::getUser();
//если нет, требуем авторизацию
if (!$user->authorize( 'com_banners', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

// ”станавливаем директорию таблицы
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_conference'.DS.'tables');

//ѕолучаем адресную строку
$controllerName = JRequest::getCmd( 'act', 'conference' );

//если содержит conference, ничего не делаем
switch ($controllerName)
{
	default:
	case 'conference':
	JToolBarHelper::title(JText::_('Conference'));
	JSubMenuHelper::addEntry(JText::_('Conference'));
	break;
//если содержит reg, отмечаем –егистраци€
	case 'reg':
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg',true);
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel');
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis');
		break;
//если содержит hotel, отмечаем √остиница
	case 'hotel':
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg');
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel',true);
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis');
		break;
//если содержит tesis, отмечаем “езисы
	case 'tesis': 
		JSubMenuHelper::addEntry(JText::_('Registration'), 'index.php?option=com_conference&act=reg');
		JSubMenuHelper::addEntry(JText::_('Hotel'), 'index.php?option=com_conference&act=hotel');
		JSubMenuHelper::addEntry(JText::_('Tesis'), 'index.php?option=com_conference&act=tesis',true);
	break;
//если содержит params, отмечаем Ќастройки
}

switch ($controllerName)
{
//по умолчанию присваиваем conference
	default:
		$controllerName = 'conference';
//если равно conference, то ничего не делаем
	case 'conference' :
//если равно reg	
	case 'reg' :
//получение адресной строки
		$task = JRequest::getCmd('act');
//проверка есть ли в адресной строке reg
		if ($task == 'reg') {
//если да, присваиваем переменной
			$controllerName = 'reg';
		}
//подключаем контроллер
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// »м€ контроллера = conferencesController + hotel, reg, tesis или conference, в зависимости от switch
$controllerName    = 'ConferencesController'.$controllerName;
//регистрируем новый класс
$controller   = new $controllerName();

// ¬ыполн€ем задачу Request
$controller->execute( JRequest::getCmd( 'task' ) );

// ѕереадресаци€, если указано в контроллере
$controller->redirect();
break;
//если равно hotel
	case 'hotel':
//получение адресной строки
		$task = JRequest::getCmd('act');
//проверка есть ли в адресной строке hotel
		if ($task == 'hotel') {
//если да, присваиваем переменной
			$controllerName = 'hotel';
		}
//подключаем контроллер
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// »м€ контроллера = conferencesController + hotel или reg, в зависимости от switch
$controllerName    = 'conferencesController'.$controllerName;
//регистрируем новый класс
$controller   = new $controllerName();

// ¬ыполн€ем задачу Request
$controller->execute( JRequest::getCmd( 'task' ) );

// ѕереадресаци€, если указано в контроллере
$controller->redirect();
break;
	case 'tesis':
//получение адресной строки
		$task = JRequest::getCmd('act');
//проверка есть ли в адресной строке hotel
		if ($task == 'tesis') {
//если да, присваиваем переменной
			$controllerName = 'tesis';
		}
//подключаем контроллер
		require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );

// »м€ контроллера = conferencesController + hotel или reg, в зависимости от switch
$controllerName    = 'conferencesController'.$controllerName;
//регистрируем новый класс
$controller   = new $controllerName();

// ¬ыполн€ем задачу Request
$controller->execute( JRequest::getCmd( 'task' ) );

// ѕереадресаци€, если указано в контроллере
$controller->redirect();
break;
}
?>