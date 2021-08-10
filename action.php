<?php
/**
 * DokuWiki Plugin authcorebos (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Joe Bordes <joe@tsolucio.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class action_plugin_authcorebos extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     *
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        global $conf;
        if($conf['authtype'] != 'authcorebos') return;
        $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'BEFORE', $this, 'handle_html_loginform_output');
    }

    /**
     * [Custom event handler which performs action]
     *
     * Called for event:
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     *
     * @return void
     */
    public function handle_html_loginform_output(Doku_Event $event, $param)
    {
        $cbinstalls = array();
        $numinstalls = $this->getConf('corebosinstalls');
        for ($i = 1; $i <= $numinstalls; $i++) {
            $pad = str_pad($i, 2, '0', STR_PAD_LEFT);
            $cbname = $this->getConf('corebosname'.$pad);
            $cburl = $this->getConf('corebosurl'.$pad);
            $cbpfix = $this->getConf('corebospfix'.$pad);
            if (!empty($cbname) && !empty($cburl) && $cbname!='coreBOS Name') {
                $cbinstalls[$cbpfix] = $cbname;
            }
        }
        $event->data->insertElement(
            1,
            form_makeListboxField(
                'cb',
                $cbinstalls,
                '',
                $this->getLang('coreBOS'),
                'login__corebos',
                'block'
            )
        );
    }

}

