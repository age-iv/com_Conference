<?php
/**
 * Hello World table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license   GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableTesis extends JTable
{
  /**
   * Primary Key
   *
   * @var int
   */
  var $id = null;

  /**
   * @var string
   */
  var $authors = null;
  var $supervisor = null;
  var $name_lecture = null;
  var $type_lecture = null;
  var $sections = null;
  var $username = null;
  var $file_name = null;
  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct(& $db) {
    parent::__construct('#__tesis', 'id', $db);
  }
  
    function check()
  {
    jimport('joomla.mail.helper');

    // Validate user information
    if (trim( $this->authors ) == '') {
      $this->setError( JText::_( 'Please enter authors.' ) );
      return false;
    }
    
    if (trim( $this->supervisor ) == '') {
      $this->setError( JText::_( 'Please enter supervisor.' ) );
      return false;
    }
    
    if (trim( $this->name_lecture ) == '') {
      $this->setError( JText::_( 'Please enter name lecture.' ) );
      return false;
    }
    
    
    if (trim( $this->type_lecture ) == '') {
      $this->setError( JText::_( 'Please select lecture type.' ) );
      return false;
    }
    
    if (trim( $this->sections ) == '') {
      $this->setError( JText::_( 'Please select sections.' ) );
      return false;
    }
    
    if (trim( $this->file_name ) == '') {
      $this->setError( JText::_( 'Please select file.' ) );
      return false;
    }

    return true;
    }
}
?>
