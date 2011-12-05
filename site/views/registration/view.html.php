<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewRegistration extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
//����������
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$params	= &$mainframe->getParams();
//��������� ���������� ��������� ������ ����		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

/*		
//���� �������� ����� ���� - ��� ������
		if (is_object( $menu )) {
//�� ������� ����� �����
			$menu_params = new JParameter( $menu->params );
//���� ��������� �� �����, �� ������������� ����
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$user->gid == 18? JText::_( 'Registration' ).': ['.JText::_( 'Edit').']' : JText::_( 'Registration' ));
			}
//��� ���� �� ������, �� ����
		} else {
			$params->set('page_title',	$user->gid == 18? JText::_( 'Registration' ).': ['.JText::_( 'Edit').']' : JText::_( 'Registration' ));
		}
*/
//����� �������� �� ����������
		$document->setTitle( $params->get( 'page_title' ) );		

//�������� ����
		JHTML::_('behavior.formvalidation');		

//�������� ������		
		$model =& $this->getModel();
//���������� ��� ������		
		$items		=& $model->getData($user->username);
		$item_id = $model->getMaxID();
		$country = &$model->getParams('country');
		$academic_degree = &$model->getParams('degree');
		$academic_rank = &$model->getParams('rank');
		
		$this->assignRef('user',		$user);
//�������� ���������� � default.php
		$this->assignRef('params',		$params);
		$post = JRequest::get('post');
		
//�������� ������ � default.php
		$this->assignRef('post',		$post);
		$this->assignRef('items',		$items);
		$this->assignRef('country',		$country);
		$this->assignRef('academic_degree',		$academic_degree);
		$this->assignRef('academic_rank',		$academic_rank);
		$this->assignRef('id',		$item_id);
		
			parent::display($tpl);
    }
}
?>