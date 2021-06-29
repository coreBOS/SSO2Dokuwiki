<?php
/**
 * DokuWiki Plugin authcorebos (Auth Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Joe Bordes <joe@tsolucio.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}
include_once DOKU_INC.'lib/plugins/authplain/auth.php';
include_once DOKU_INC.'lib/plugins/authcorebos/cblib/WSClient.php';

class auth_plugin_authcorebos extends auth_plugin_authplain
{

    private $ufirstname = '';
    private $ulastname = '';
    private $ufullname = '';
    private $uemail = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(); // for compatibility
        $this->writeCBInstalls();
        $this->success = true;
    }

    private function writeCBInstalls() {
        file_put_contents(DOKU_INC.'conf/corebosinstalls', $this->getConf('corebosinstalls'));
    }

    private function cbLogin($prefix, $user, $password) {
        $cburl = '';
        if (empty($prefix)) {
            $cburl = $this->getConf('corebosurl01');
        } else {
            for ($i = 1; $i <= $this->getConf('corebosinstalls'); $i++) {
                $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
                if ($this->getConf('corebospfix'.$pad)==$prefix) {
                    $cburl = $this->getConf('corebosurl'.$pad);
                    break;
                }
            }
        }
        if ($cburl != '') {
            $bauser = $this->getConf('basicauthuser');
            $bapass = $this->getConf('basicauthpass');
            if (!empty($bauser) && !empty($bapass)) { // basic authentication > same user for all installs
                $cburl = substr($cburl, 0, strpos($cburl, '://')+3).$bauser.':'.$bapass.'@'.substr($cburl, strpos($cburl, '://')+3);
            }
            $cbconn = new Vtiger_WSClient($cburl);
            $login = $cbconn->doLogin($user, $password, true);
            if ($login) {
                $uinfo = $cbconn->doInvoke('getPortalUserInfo', array(), 'POST');
                $this->ufirstname = html_entity_decode($uinfo['first_name'], ENT_QUOTES, 'UTF8');
                $this->ulastname = html_entity_decode($uinfo['last_name'], ENT_QUOTES, 'UTF8');
                $this->ufullname = $this->ufirstname.' '.$this->ulastname;
                $this->uemail = $uinfo['email1'];
                $cbconn->doLogout();
                return true;
            }
        }
        return false;
    }

    /**
     * Check user+password
     *
     * May be ommited if trustExternal is used.
     *
     * @param   string $user the user name
     * @param   string $pass the clear text password
     *
     * @return  bool
     */
    public function checkPass(&$user, $pass)
    {
        $prefix = $_REQUEST['cb'];
        $userinfo = $this->getUserData($user);
        if ($userinfo) {
            // if found > we authenticate locally
            if (in_array('admin', $userinfo['grps'])) {
                // if is admin, we only validate locally
                return auth_verifyPassword($pass, $this->users[$user]['pass']);
            }
            $auth = auth_verifyPassword($pass, $this->users[$user]['pass']);
            if ($auth) {
                return true; // dokuwiki says it is ok
            } else {
                // we ask coreBOS, in case they have changed their password and are giving us the new one
                if ($this->cbLogin($prefix, $user, $pass)) {
                    // coreBOS says it is ok, so we update password and authorize
                    $_REQUEST['u'] = $prefix.'@'.$user;
                    $user = $prefix.'@'.$user;
                    $this->modifyUser($user, array('pass'=>$pass));
                    return true;
                }
            }
        }
        if (!empty($prefix)) {
            $userinfo = $this->getUserData($prefix.'@'.$user);
            if ($userinfo) {
                $usernoprefix = $user;
                $user = $prefix.'@'.$user;
                $_REQUEST['u'] = $user;
                // if found with corebos install prefix > we authenticate locally
                if (in_array('admin', $userinfo['grps'])) {
                    // if is admin, we only validate locally
                    return auth_verifyPassword($pass, $this->users[$user]['pass']);
                }
                $auth = auth_verifyPassword($pass, $this->users[$user]['pass']);
                if ($auth) {
                    return true; // dokuwiki says it is ok
                } else {
                    // we ask coreBOS, in case they have changed their password and are giving us the new one
                    if ($this->cbLogin($prefix, $usernoprefix, $pass)) {
                        // coreBOS says it is ok, so we update password and authorize
                        $this->modifyUser($user, array('pass'=>$pass));
                        return true;
                    }
                }
            }
        }
        // not found so we try to authenticate with coreBOS
        if ($this->cbLogin($prefix, $user, $pass)) {
            // coreBOS says it is ok, so we create locally and authorize
            $user = $prefix.'@'.$user;
            $_REQUEST['u'] = $user;
            $this->createUser($user, $pass, $this->ufullname, $this->uemail, $this->getConf('newuserrole'));
            return true;
        }
        return false;
    }

}

