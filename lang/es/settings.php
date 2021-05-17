<?php
/**
 * Spanish language file for authcorebos plugin
 *
 * @author Joe Bordes <joe@tsolucio.com>
 */

// keys need to match the config setting name
$lang['newuserrole'] = 'Rol(es) de usuarios nuevos';
$lang['basicauthuser'] = 'Usuario Basic Auth';
$lang['basicauthpass'] = 'Contraseña Basic Auth';
$lang['corebosinstalls'] = 'Número de instalaciones';
if (file_exists(DOKU_INC.'conf/corebosinstalls')) {
	$numinstalls = (int)file_get_contents(DOKU_INC.'conf/corebosinstalls');
} else {
	$numinstalls = 1;
}
$acb_corebosname = 'coreBOS';
for ($i = 1; $i <= $numinstalls; $i++) {
	$pad = str_pad($i, 2, '0', STR_PAD_LEFT);
	$lang['corebosname'.$pad] = $acb_corebosname.' Nombre '.$pad;
	$lang['corebospfix'.$pad] = $acb_corebosname.' Prefijo '.$pad;
	$lang['corebosurl'.$pad] = $acb_corebosname.' URL '.$pad;
}
