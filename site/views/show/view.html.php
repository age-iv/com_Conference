<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
 
class ConferenceViewShow extends JView
{
    function display($tpl = null)
    {
			global $mainframe;
//Переменные
		$pathway  =& $mainframe->getPathway();
		$document =& JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$params	= &$mainframe->getParams();
//получение параметров активного пункта меню		
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
//переменные для данных и для паджинации		
		$items		= &$this->get('data' );
		$pagination	= &$this->get('pagination');
		
//если активный пункт меню - это объект
		if (is_object( $menu )) {
//то создаем новый класс
			$menu_params = new JParameter( $menu->params );
//если заголовок не задан, то устанавливаем свой
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	JText::_( 'list_of_member' ));
			}
//или меню не объект, то тоже
		} else {
			$params->set('page_title',	JText::_( 'list_of_member' ));
		}
//иначе получаем из параметров
		$document->setTitle( $params->get( 'page_title' ) );

		$k = 0;
		$count = count($items);
		for($i = 0; $i < $count; $i++)
		{
			$item =& $items[$i];
//счетчик для выделения строк разным цветом - это используется в default.php
			$item->odd		= $k;
//нумерация записей - это используется в default.php
			$item->count	= $i;
			$k = 1 - $k;
		}

//передача параметров в default.php
		$this->assignRef('params',		$params);
//передача данных в default.php
		$this->assignRef('items',		$items);
//передача паджинации в default.php
		$this->assignRef('pagination',	$pagination);
		$this->assign('action',	$uri->toString());
		
        parent::display($tpl);
    }
}
?>