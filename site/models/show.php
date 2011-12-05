<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ConferenceModelShow extends JModel
{
  var $_data = null;
  var $_total = null;
  var $_pagination = null;

  function __construct()
  {
    parent::__construct();

    global $mainframe;

    $config = JFactory::getConfig();

//лимит записей
    $this->setState('limit', $mainframe->getUserStateFromRequest('com_conference.limit', 'limit', $config->getValue('config.list_limit'), 'int'));
    $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

//получение состояния лимита
    $this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));
  }
  
  function getData()
  {
//если данные пустые
    if (empty($this->_data))
    {
//выполняется запрос к БД
      $query = $this->_buildQuery();
//заполняет данные содержимым в соответствии с лимитами
      $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
      
//количество записей
      $total = count($this->_data);
//цикл, данные сохраняются в массив item
      for($i = 0; $i < $total; $i++)
      {
        $item =& $this->_data[$i];
      }
    }
//возвращает данные
    return $this->_data;
  }
  
    function getTotal()
  {
//если пустая переменная
    if (empty($this->_total))
    {
//запрос
      $query = $this->_buildQuery();
//количество записей в таблице
      $this->_total = $this->_getListCount($query);
    }
//возвращает кол-во записей
    return $this->_total;
  }
  
  function getPagination()
  {
//если пустая переменная
    if (empty($this->_pagination))
    {
//подключается паджинация
      jimport('joomla.html.pagination');
//объявление класса, с количеством записей и лимитами
      $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
    }
//возвращает переменную
    return $this->_pagination;
  }
  
    function _buildQuery()
  {
//выбор из БД
    $query = 'SELECT surname, name, organisation, city' .
      ' FROM #__register'.
      ' ORDER BY surname';
//возврат запроса
    return $query;
  }
}