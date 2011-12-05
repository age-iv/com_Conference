<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
 
class ConferenceViewShow extends JView
{
    function display($tpl = null)
    {
			global $mainframe;
//����������
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$params	= &$mainframe->getParams();
//��������� ���������� ��������� ������ ����		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
//���������� ��� ������ � ��� ����������		
		$items		= &$this->get('data' );
		$pagination	= &$this->get('pagination');
		
//���� �������� ����� ���� - ��� ������
		if (is_object( $menu )) {
//�� ������� ����� �����
			$menu_params = new JParameter( $menu->params );
//���� ��������� �� �����, �� ������������� ����
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'list_of_member' ));
			}
//��� ���� �� ������, �� ����
		} else {
			$params->set('page_title',	JText::_( 'list_of_member' ));
		}
//����� �������� �� ����������
		$document->setTitle( $params->get( 'page_title' ) );

		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];
//������� ��� ��������� ����� ������ ������ - ��� ������������ � default.php
			$item->odd		= $k;
//��������� ������� - ��� ������������ � default.php
			$item->count	= $i;
			$k = 1 - $k;
		}

//�������� ���������� � default.php
		$this->assignRef('params',		$params);
//�������� ������ � default.php
		$this->assignRef('items',		$items);
//�������� ���������� � default.php
		$this->assignRef('pagination',	$pagination);
		$this->assign('action',	$uri->toString());
		
        parent::display($tpl);
    }
}
?>