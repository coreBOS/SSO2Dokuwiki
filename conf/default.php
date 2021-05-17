<?php
/**
 * Default settings for the authcorebos plugin
 *
 * @author Joe Bordes <joe@tsolucio.com>
 */

$conf['newuserrole']   = '@ALL';
$conf['basicauthuser'] = '';
$conf['basicauthpass'] = '';
if (file_exists(DOKU_INC.'conf/corebosinstalls')) {
	$acb_numinstalls = (int)file_get_contents(DOKU_INC.'conf/corebosinstalls');
} else {
	$acb_numinstalls = 1;
}
$conf['corebosinstalls'] = $acb_numinstalls;
for ($i = 1; $i <= $acb_numinstalls; $i++) {
	$pad = str_pad($i, 2, '0', STR_PAD_LEFT);
	$conf['corebosname'.$pad] = '';
	$conf['corebospfix'.$pad] = '';
	$conf['corebosurl'.$pad] = '';
}
