<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewRegistration extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
//Переменные
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$params	= &$mainframe->getParams();
//получение параметров активного пункта меню		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

/*		
//если активный пункт меню - это объект
		if (is_object( $menu )) {
//то создаем новый класс
			$menu_params = new JParameter( $menu->params );
//если заголовок не задан, то устанавливаем свой
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$user->gid == 18? JText::_( 'Registration' ).': ['.JText::_( 'Edit').']' : JText::_( 'Registration' ));
			}
//или меню не объект, то тоже
		} else {
			$params->set('page_title',	$user->gid == 18? JText::_( 'Registration' ).': ['.JText::_( 'Edit').']' : JText::_( 'Registration' ));
		}
*/
//иначе получаем из параметров
		$document->setTitle( $params->get( 'page_title' ) );		

//проверка форм
		JHTML::_('behavior.formvalidation');		

//Получаем модель		
		$model =& $this->getModel();
//переменные для данных		
		$items		=& $model->getData($user->username);
		$item_id = $model->getMaxID();
		$country = &$model->getParams('country');
		$academic_degree = &$model->getParams('degree');
		$academic_rank = &$model->getParams('rank');
		
		$this->assignRef('user',		$user);
//передача параметров в default.php
		$this->assignRef('params',		$params);
		$post = JRequest::get('post');
		
//передача данных в default.php
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