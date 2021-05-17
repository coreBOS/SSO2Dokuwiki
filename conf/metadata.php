<?php
/**
 * Options for the authcorebos plugin
 *
 * @author Joe Bordes <joe@tsolucio.com>
 */


$meta['newuserrole'] = array('array');
$meta['basicauthuser'] = array('string');
$meta['basicauthpass'] = array('password');
$meta['corebosinstalls'] = array('numeric', '_min' => 1);
if (file_exists(DOKU_INC.'conf/corebosinstalls')) {
	$acb_numinstalls = (int)file_get_contents(DOKU_INC.'conf/corebosinstalls');
} else {
	$acb_numinstalls = 1;
}
for ($i = 1; $i <= $acb_numinstalls; $i++) {
	$pad = str_pad($i, 2, '0', STR_PAD_LEFT);
	$meta['corebosname'.$pad] = array('string');
	$meta['corebospfix'.$pad] = array('string');
	$meta['corebosurl'.$pad] = array('string');
}

