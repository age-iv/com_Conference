<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewHotel extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
			global $mainframe;
//Переменные
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$params	= &$mainframe->getParams();
//получение параметров активного пункта меню		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		
//если активный пункт меню - это объект
		if (is_object( $menu )) {
//то создаем новый класс
			$menu_params = new JParameter( $menu->params );
//если заголовок не задан, то устанавливаем свой
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Hotel' ));
			}
//или меню не объект, то тоже
		} else {
			$params->set('page_title',	JText::_( 'Hotel' ));
		}
//иначе получаем из параметров
		$document->setTitle( $params->get( 'page_title' ) );		

		if ($user->gid == 0)
		$pathway -> addItem(JText::_('New'));
//для зарегистрированных пользователей изменение
		if ($user->gid == 18)
		$pathway -> addItem(JText::_('Edit'));

//проверка форм
		JHTML::_('behavior.formvalidation');		

//Получаем модель		
		$model =& $this->getModel();
		//переменные для данных		
		$items		= $model->getData($user->username);

		$this->assignRef('user',		$user);
//передача параметров в default.php
		$this->assignRef('params',		$params);
//передача данных в default.php
		$this->assignRef('items',		$items);
		
			parent::display($tpl);
    }
}
?>