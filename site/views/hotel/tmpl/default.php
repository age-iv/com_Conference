<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script src="<?php echo $this->baseurl ?>/components/com_conference/js/val.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_conference/js/style.css" type="text/css" />
<?php
  if(isset($this->_message)){
    $this->display('message');
  }
?>

<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" id="josForm" name="josForm" class="form-validate"  autocomplete="off">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif;?>

<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php if (  $this->params->def( 'show_comp_description', 1 ) ) : ?>
<tr>
  <td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php
    echo $this->params->get('description');
  ?>
  </td>
</tr>
<?php endif; ?>
</table>

<table cellpadding="5" cellspacing="0" border="0" width="100%" align="center">
<?php if ( $this->params->get( 'show_hotel', 1 ) ) : 
$hotel = explode('|',$this->params->get( 'hotel' ));?>
<tr>
  <td>
    <label id="hotelmsg" for="hotel">
      <?php echo JText::_( 'hotel' ); ?>:
    </label>
  </td>
  <td>
<?php
$type[] = JHTML::_('select.option',  "", JText::_('--Select hotel--') );
for ($i=0;$i<count($hotel);$i++):
$type[] = JHTML::_('select.option',  $hotel[$i], $hotel[$i] );
endfor;
echo JHTML::_('select.genericlist', $type, 'hotel', 'class="inputbox required"', 'value', 'text', $this->items->hotel);
unset($type);
?><noscript> *</noscript>
<div id="hotelstk" class="stiker"><?php echo JText::_('Please select hotel');?></div>
  </td>
</tr>
<?php endif;
if ( $this->params->get( 'show_type_room', 1 ) ) :
$type_room = explode('|',$this->params->get( 'type_room' ));?>
<tr>
  <td>
    <label id="type_roommsg" for="type_room">
      <?php echo JText::_( 'type_room' ); ?>:
    </label>
  </td>
    <td>
<?php

$type[] = JHTML::_('select.option',  "", JText::_('--Select type_room--') );
for ($i=0;$i<count($type_room);$i++):
$type[] = JHTML::_('select.option',  $type_room[$i], $type_room[$i] );
endfor;
echo JHTML::_('select.genericlist', $type, 'type_room', 'class="inputbox required"', 'value', 'text', $this->items->type_room);
unset($type);
?><noscript> *</noscript>
<div id="type_roomstk" class="stiker"><?php echo JText::_('Please select type room');?></div>
    </td>
</tr>
<?php endif;?>
          <tr>
            <td>
              <label id="visit_datemsg" for="visit_date">
                <?php echo JText::_( 'visit_date' ); ?>:
              </label>
            </td>
            <td>
              <?php echo JHTML::calendar($this->items->visit_date, 'visit_date', 'visit_date', '%d.%m.%Y','size="40" class="inputbox required validate-date" maxlength="12"'); ?><noscript> *</noscript>
              <div id="visit_datestk" class="stiker"><?php echo JText::_('Please select visit date');?></div>
            </td>
          </tr>
          <tr>
            <td>
              <label id="exit_datemsg" for="exit_date">
                <?php echo JText::_( 'exit_date' ); ?>:
              </label>
            </td>
            <td>
              <?php echo JHTML::calendar($this->items->exit_date, 'exit_date', 'exit_date', '%d.%m.%Y', 'size="40" class="inputbox required validate-date" maxlength="12"'); ?><noscript> *</noscript>
              <div id="exit_datestk" class="stiker"><?php echo JText::_('Please select exit date');?></div>
            </td>
          </tr>
<?php if ( $this->params->get( 'show_address', 1 ) ) : ?>
          <tr>
            <td>
              <label id="addressmsg" for="address">
                <?php echo JText::_( 'address' ); ?>:
              </label>
            </td>
            <td>
              <textarea class="inputbox required" cols="36" rows="2" name="address" id="address"><?php echo $this->items->address;?></textarea><noscript> *</noscript>
              <div id="addressstk" class="stiker"><?php echo JText::_('Please enter address');?></div>
            </td>
          </tr>
<?php endif;
if ( $this->params->get( 'show_type_room', 1 ) ) : ?>
          <tr>
            <td>
              <label id="wishmsg" for="wish">
                <?php echo JText::_( 'wish' ); ?>:
              </label>
            </td>
            <td>
              <textarea class="inputbox" cols="36" rows="4" name="wish" id="wish"><?php echo $this->items->wish;?></textarea>
            </td>
          </tr>
<?php endif;?>
<tr>
  <td colspan="2">
    <div id="common" class="common"><?php echo JText::_('One or more fields empty');?></div>
    <noscript><div id="common_noscript"><?php echo JText::_('REGISTER_REQUIRED');?></div></noscript>
  </td>
</tr>
</table>
  <button class="button validate" type="submit"><?php echo JText::_('Entrench'); ?></button>
  <button class="button" type="reset"><?php echo JText::_('Reset'); ?></button>
  <input type="hidden" name="task" value="save_hotel" />
  <input type="hidden" name="id" value="<?php echo $this->items->id;?>" />
  <?php echo JHTML::_( 'form.token' ); ?>
</form>