<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class ConferencesViewConference
{

	function Conferences()
	{
?>
<table>
	<tr>
		<td width="35%"></td>
		<td align="center">
			<div id="cpanel">
		<?php
			$link = 'index.php?option=com_conference&amp;act=reg';
			$this->quickiconButton( $link, 'icon-48-article-add.png', JText::_( 'Registration' ) );

			$link = 'index.php?option=com_conference&act=hotel';
			$this->quickiconButton( $link, 'icon-48-frontpage.png', JText::_( 'Hotel' ) );

			$link = 'index.php?option=com_conference&act=tesis';
			$this->quickiconButton( $link, 'icon-48-article.png', JText::_( 'Tesis' ) );
			?>
			</div>
	</td>
	<td width="35%"></td>
	</tr>
</table>
<?php
	}
}
?>