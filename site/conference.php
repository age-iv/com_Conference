<?php
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT.DS.'controller.php');

//������������ ����� �����
$controller   = new ConferenceController();

// ��������� ������ Request
$controller->execute( JRequest::getCmd( 'task' ) );

// �������������, ���� ������� � �����������
$controller->redirect();
?>