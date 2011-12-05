<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewTesis extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
//����������
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$config =& JComponentHelper::getParams('com_media');
		$params	= &$mainframe->getParams();
//��������� ���������� ��������� ������ ����		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		
		$config =& JComponentHelper::getParams('com_media');

		
//���� �������� ����� ���� - ��� ������
		if (is_object( $menu )) {
//�� ������� ����� �����
			$menu_params = new JParameter( $menu->params );
//���� ��������� �� �����, �� ������������� ����
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Tesis' ));
			}
//��� ���� �� ������, �� ����
		} else {
			$params->set('page_title',	JText::_( 'Tesis' ));
		}
//����� �������� �� ����������
		$document->setTitle( $params->get( 'page_title' ) );		

//�������� ����
		JHTML::_('behavior.formvalidation');		
		
		//JHTML::_('behavior.uploader', 'file-upload', array('onAllComplete' => 'function(){ ImageManager.refreshFrame(); }'));
//�������� ������		
		$model =& $this->getModel();
		//���������� ��� ������		
		$items		= $model->getData($user->username);
		if ($config->get('enable_flash', 0)) {
			JHTML::_('behavior.uploader', 'upload=file', array('onAllComplete' => 'function(){ MediaManager.refreshFrame(); }'));
		}
		
		$this->assignRef('config', $config);		
		
		$this->assignRef('user',		$user);
//�������� ���������� � default.php
		$this->assignRef('params',		$params);
//�������� ������ � default.php
		$this->assignRef('items',		$items);
		
			parent::display($tpl);
    }
}
?>