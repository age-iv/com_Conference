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

//����� �������
    $this->setState('limit', $mainframe->getUserStateFromRequest('com_conference.limit', 'limit', $config->getValue('config.list_limit'), 'int'));
    $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

//��������� ��������� ������
    $this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));
  }
  
  function getData()
  {
//���� ������ ������
    if (empty($this->_data))
    {
//����������� ������ � ��
      $query = $this->_buildQuery();
//��������� ������ ���������� � ������������ � ��������
      $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
      
//���������� �������
      $total = count($this->_data);
//����, ������ ����������� � ������ item
      for($i = 0; $i < $total; $i++)
      {
        $item =& $this->_data[$i];
      }
    }
//���������� ������
    return $this->_data;
  }
  
    function getTotal()
  {
//���� ������ ����������
    if (empty($this->_total))
    {
//������
      $query = $this->_buildQuery();
//���������� ������� � �������
      $this->_total = $this->_getListCount($query);
    }
//���������� ���-�� �������
    return $this->_total;
  }
  
  function getPagination()
  {
//���� ������ ����������
    if (empty($this->_pagination))
    {
//������������ ����������
      jimport('joomla.html.pagination');
//���������� ������, � ����������� ������� � ��������
      $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
    }
//���������� ����������
    return $this->_pagination;
  }
  
    function _buildQuery()
  {
//����� �� ��
    $query = 'SELECT surname, name, organisation, city' .
      ' FROM #__register'.
      ' ORDER BY surname';
//������� �������
    return $query;
  }
}