<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ConferenceModelRegistration extends JModel
{
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe;

		$config = JFactory::getConfig();
	}

	function store($data, $user_post)
	{
		$user = $this->getData($data['username']);

//реквайр таблицы
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'reg.php');
//подключение таблицы reg
		$row =& JTable::getInstance('reg', 'Table');
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check()) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
		}
		
		if ( $data['username'] == $user->username)
		{
			if (!$row->store(true)) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
			}
		} else {
			if (!$row->store()) {
			JError::raiseWarning( 500, $row->getError() );
			return false;
			}
		}
		
		//unset ($data);
	if ( $data['username'] == $username)
	{		
		$user 		= clone(JFactory::getUser());
		$username	= $user->get('username');
		
		if (!$user->bind($user_post)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$user->check()) {
			$this->setError($user->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$user->save()) {
			$this->setError( $user->getError() );
			return false;
		}
	
		$session =& JFactory::getSession();
		$session->set('user', $user);

		// check if username has been changed
		if ( $username != $user->get('username') )
		{
			$table = $this->getTable('session', 'JTable');
			$table->load($session->getId());
			$table->username = $user->get('username');
			$table->store();

		}
	}
		return true;
	}
	
	function getData($username)
	{
	if($username)
	{
	$action= 'SELECT *';
	$where=' WHERE username='.$username;
//если данные пустые
	if (empty($this->_data)) {
//выполняется запрос к БД
			$query = $this->_buildQuery($action, $where);
//заполняет данные содержимым
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		
	
//возвращает данные
		return $this->_data;
	}
	}
	
	function _buildQuery($action, $where=null)
	{
//выбор из БД
		$query = $action .
			' FROM #__register'.
			$where
			;
//возврат запроса
		return $query;
	}
	
	function getMaxID()
	{
//если данные пустые
		if (empty($this->_data))
		{
			$action = 'SELECT MAX(id)';
			
//выполняется запрос к БД получаем максимальный id
			$query = $this->_buildQuery($action);
//результат
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadResult();
			if (!$this->_db->query()) {
				echo "It's very bad, wrong id :(";
			}
		}
		
//возвращает данные
		return $this->_data;
	}
	
	function deleteFromRegister($total)
	{
			$action = 'DELETE';
			$where = ' WHERE id="'.$total.'"';
			$query = $this->_buildQuery($action, $where);
			$this->_db->setQuery($query);
			
			if (!$this->_db->query()) {
				echo "It's very bad, wrong data of member :(";
			}
			
//возвращает данные
		return true;
	}
	
	function deleteFromUser($username)
	{
	$query = 'DELETE FROM #__users'
				. ' WHERE username = "' . $username.'"'
				;
	$this->_db->setQuery( $query );
	
	if (!$this->_db->query()) {
				echo "It's very bad, wrong data of user :(";
	}
	
	return true;
	}
	
	function getParams($val)
	{
		$query = 'SELECT * FROM #__params'
			. ' WHERE type = "' . $val.'"'
			;
		$this->_db->setQuery( $query );
		$this->_data = $this->_db->loadObjectList();		
		
		if (!$this->_db->query()) {
				echo "It's very bad, wrong query :(";
	}
	return $this->_data;
	}

}