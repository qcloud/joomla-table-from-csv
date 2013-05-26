<?php
/*
* mod_table_from_csv is just a simple module that loads shared bookmarks from Xmarks and displays it in Joomla.
* @copyright (c) Copyright: Cecilomar Design.
* @author info@cecilomar.com 
* @date 2009.07.23
* @package Joomla1.5
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GET MODULE PARAMETERS  //////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

// The Module Class Suffix
$dcsv['tableid']		= $params->get( 'tableid' );

// Will be sortable?
$dcsv['sortable']		= $params->get( 'sortable' );

// Cache the RSS file and module true or false
$dcsv['cache']		= $params->get( 'cache' );

// Time in minutes for the RSS to be cached
$dcsv['cachetime']	= $params->get( 'cachetime' );

// URL of the CSS
$dcsv['file']		= $params->get( 'dcsvfile' );

// Style Variables //////////////////////////////////////////////////////////////////////////////////
$dcsv['tablewidth']			= $params->get( 'tablewidth' );
$dcsv['tablebgcolor']		= $params->get( 'tablebgcolor' );
$dcsv['tableheaderbgcolor']	= $params->get( 'tableheaderbgcolor' );
$dcsv['tabledatabgcolor']	= $params->get( 'tabledatabgcolor' );
$dcsv['border']				= $params->get( 'border' );
$dcsv['cellspacing']		= $params->get( 'cellspacing' );
$dcsv['cellpadding']		= $params->get( 'cellpadding' );

// New Variables ////////////////////////////////////////////////////////////////////////////////////
// Where the cache will be stored
$dcsv['cachefile']	= dirname(__FILE__).'/tmp/'.md5($dcsv['file']);
// For the deault CSS
$dcsv['classcss']	= '.'.$dcsv['tableid'].' ';

/////////////////////////////////////////////////////////////////////////////////////////////////////
//  CACHE  //////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if (file_exists($dcsv['cachefile']) && (time() - $dcsv['cachetime'] < filemtime($dcsv['cachefile']))) {

} else {
	// If there is no chache saved or is older than the cache time create a new cache
	// open the cache file for writing
	$fp = fopen($dcsv['cachefile'], 'w');
	// save the contents of output buffer to the file
	fwrite($fp, file_get_contents($dcsv['file']));
	// close the file
	fclose($fp);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
//  LOAD THE CACHE AND PROCESS THE CSV  /////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

// ParseCSV class.
require_once('csv_parser.php');
// New ParseCSV object.
$csv = new parseCSV();
// Parse CSV with auto delimiter detection
$csv->auto($dcsv['cachefile']);

/////////////////////////////////////////////////////////////////////////////////////////////////////
//  DISPLAY THE PROCESSED DATA  /////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

// include the table_sorter.js only if $dcsv['sortable'] is true
if ($dcsv['sortable']){

?>

<script type="text/javascript" src="modules/mod_table_from_csv/table_sorter.js"></script>

<?php } ?>

<style type="text/css" media="screen">
	#<?php echo $dcsv['tableid']; ?> th { background-color: <?php echo $dcsv['tableheaderbgcolor']; ?>; padding:3px 4px; cursor:pointer;}
	#<?php echo $dcsv['tableid']; ?> td { background-color: <?php echo $dcsv['tabledatabgcolor']; ?>; padding:3px 4px; }
	#<?php echo $dcsv['tableid']; ?> tfoot { display:none; }
</style>
<table id="<?php echo $dcsv['tableid']; ?>" class="table_from_csv_sortable<? if ($dcsv['sortable'] == false){ echo '_not';} ?>" width="<?php echo $dcsv['tablewidth']; ?>" border="<?php echo $dcsv['border']; ?>" cellspacing="<?php echo $dcsv['cellspacing']; ?>" cellpadding="<?php echo $dcsv['cellpadding']; ?>" bgcolor="<?php echo $dcsv['tablebgcolor']; ?>">
	<tr>
		<?php 
		
		$i=1;
		foreach ($csv->titles as $value): ?>
		<th class="col<?php echo $i;?>"><?php echo $value; ?></th>
		<?php 
		$i++;
		endforeach; ?>
	</tr>
	<?php foreach ($csv->data as $key => $row): ?>
	<tr>
		<?php foreach ($row as $value): ?>
		<td><?php echo $value; ?></td>
		<?php endforeach; ?>
	</tr>
	<?php endforeach; ?>
</table>

<?php 

// Unset Useless Variables to finish whithout worries...
unset($dcsv);

?>