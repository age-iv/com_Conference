<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<form action="<?php echo JFilterOutput::ampReplace($this->action); ?>" method="post" name="adminForm">

<?php
//если параметры - показывать заголовок страницы = 1
if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
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
<table  width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="right" colspan="4">
      <?php
//количество показываемых строк
      echo JText::_('Display Num') .'&nbsp;';
      echo $this->pagination->getLimitBox();
      ?>
      </td>
    </tr>
    <?php
//если параметры - показывать заголовки таблиц = 1
    if ( $this->params->def( 'show_headings', 1 ) ) : ?>
    <tr>
    <td width="10" style="text-align:center;" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_('Num'); ?>
    </td>
    <td style="text-align:center;" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_( 'FIO' ); ?>
    </td>
    <td style="text-align:center;" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_( 'Organisation' ); ?>
    </td>
    <td style="text-align:center;" class="sectiontableheader<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_( 'city' ); ?>
    </td>
      <?php endif; ?>
    </tr>
<?php
//перебираем массив
foreach ($this->items as $item) :
?>
<!--Четные строки помечаются другим цветом-->
    <tr class="sectiontableentry<?php echo $item->odd + 1; ?>">
      <td>
<!--нумерация строк-->
        <?php echo $this->pagination->getRowOffset( $item->count ); ?>
      </td>
<!--вывод значений-->
      <td><?php echo $item->surname.' '.$item->name; ?></td>
      <td><?php echo $item->organisation; ?></td>
      <td><?php echo $item->city; ?></td>
    </tr>
<?php
endforeach;
?>
<tr>
<!--получение стиля оформления для подвала-->
  <td align="center" colspan="4" class="sectiontablefooter<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
  <?php
//получение ссылок страниц
  echo $this->pagination->getPagesLinks(); ?>
  </td>
</tr>
<tr>
  <td colspan="4" align="right" class="pagecounter">
    <?php
//счетчик страниц
    echo $this->pagination->getPagesCounter(); ?>
  </td>
</tr>
</table>
</form>
