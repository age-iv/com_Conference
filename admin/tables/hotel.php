<?php
defined('_JEXEC') or die('Restricted access');

class TableHotel extends JTable
{
  var $id = null;
  var $hotel = null;
  var $type_room = null;
  var $visit_date = null;
  var $exit_date = null;
  var $address = null;
  var $wish = null;
  var $username = null;
  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct(& $db) {
    parent::__construct('#__hotel', 'id', $db);
  }
  
    function check()
  {
    jimport('joomla.mail.helper');
    
    if (trim( $this->type_room ) == '') {
      $this->setError( JText::_( 'Please select room type.' ) );
      return false;
    }
    
    if (trim( $this->visit_date ) == '') {
      $this->setError( JText::_( 'Please enter your visit date.' ) );
      return false;
    }

    if (trim( $this->exit_date ) == '') {
      $this->setError( JText::_( 'Please enter a exit date.') );
      return false;
    }
    
    if (trim( $this->address ) == '') {
      $this->setError( JText::_( 'Please enter address.') );
      return false;
    }

    return true;
  }

    function store( $updateNulls=false )
  {

    $k = $this->_tbl_key;
    $key =  $this->$k;

    if ($key)
    {
      // existing record
      $ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, $updateNulls );
    }
    else
    {
      // new record
      $ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
    }

    if( !$ret )
    {
      $this->setError( strtolower(get_class( $this ))."::". JText::_( 'store failed' ) ."<br />" . $this->_db->getErrorMsg() );
      return false;
    }
    else
    {
      return true;
    }
  }
  
}
?>
