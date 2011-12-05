<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class ConferenceViewTesis extends JView
{
    function display($tpl = null)
    {
		global $mainframe;
//Переменные
		$user = & JFactory::getUser();
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$config =& JComponentHelper::getParams('com_media');
		$params	= &$mainframe->getParams();
//получение параметров активного пункта меню		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		
		$config =& JComponentHelper::getParams('com_media');

		
//если активный пункт меню - это объект
		if (is_object( $menu )) {
//то создаем новый класс
			$menu_params = new JParameter( $menu->params );
//если заголовок не задан, то устанавливаем свой
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'Tesis' ));
			}
//или меню не объект, то тоже
		} else {
			$params->set('page_title',	JText::_( 'Tesis' ));
		}
//иначе получаем из параметров
		$document->setTitle( $params->get( 'page_title' ) );		

//проверка форм
		JHTML::_('behavior.formvalidation');		
		
		//JHTML::_('behavior.uploader', 'file-upload', array('onAllComplete' => 'function(){ ImageManager.refreshFrame(); }'));
//Получаем модель		
		$model =& $this->getModel();
		//переменные для данных		
		$items		= $model->getData($user->username);
		if ($config->get('enable_flash', 0)) {
			JHTML::_('behavior.uploader', 'upload=file', array('onAllComplete' => 'function(){ MediaManager.refreshFrame(); }'));
		}
		
		$this->assignRef('config', $config);		
		
		$this->assignRef('user',		$user);
//передача параметров в default.php
		$this->assignRef('params',		$params);
//передача данных в default.php
		$this->assignRef('items',		$items);
		
			parent::display($tpl);
    }
}
?>