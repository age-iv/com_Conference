<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewHotel extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
			global $mainframe;
//����������
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$params	= &$mainframe->getParams();
//��������� ���������� ��������� ������ ����		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		
//���� �������� ����� ���� - ��� ������
		if (is_object( $menu )) {
//�� ������� ����� �����
			$menu_params = new JParameter( $menu->params );
//���� ��������� �� �����, �� ������������� ����
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Hotel' ));
			}
//��� ���� �� ������, �� ����
		} else {
			$params->set('page_title',	JText::_( 'Hotel' ));
		}
//����� �������� �� ����������
		$document->setTitle( $params->get( 'page_title' ) );		

		if ($user->gid == 0)
		$pathway -> addItem(JText::_('New'));
//��� ������������������ ������������� ���������
		if ($user->gid == 18)
		$pathway -> addItem(JText::_('Edit'));

//�������� ����
		JHTML::_('behavior.formvalidation');		

//�������� ������		
		$model =& $this->getModel();
		//���������� ��� ������		
		$items		= $model->getData($user->username);

		$this->assignRef('user',		$user);
//�������� ���������� � default.php
		$this->assignRef('params',		$params);
//�������� ������ � default.php
		$this->assignRef('items',		$items);
		
			parent::display($tpl);
    }
}
?>