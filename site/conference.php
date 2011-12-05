<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT.DS.'controller.php');

//регистрируем новый класс
$controller   = new ConferenceController();

// Выполняем задачу Request
$controller->execute( JRequest::getCmd( 'task' ) );

// Переадресация, если указано в контроллере
$controller->redirect();
?>