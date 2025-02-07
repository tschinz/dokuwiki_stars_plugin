<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 * @author     Zahno Silvan <zaswiki@gmail.com>
 * @author     Bart Vaes <bart.at.foss42@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

class action_plugin_stars2 extends DokuWiki_Action_Plugin {

    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Zahno Silvan',
            'email'  => 'zaswiki@gmail.com',
            'date'   => '2025-02-07',
            'name'   => 'Stars2 Plugin',
            'desc'   => 'Embedding Rating Stars',
            'url'    => 'https://github.com/tschinz/dokuwiki_stars_plugin',
        );
    }

    /**
     * register the eventhandlers
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public function register(Doku_Event_Handler $controller){
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handle_toolbar', array (5));
    }

    function handle_toolbar(&$event, $param) {
        $event->data[] = array (
            'type' => 'picker',
            'title' => $this->getLang('star'),
            'icon' => '../../plugins/stars2/images/toolbar/star.png',
            'list' => array(
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star1'),
                    'icon'   => '../../plugins/stars2/images/toolbar/star1.png',
                    'insert'=> '{{stars>1/5}}'
                ),
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star2'),
                    'icon'   => '../../plugins/stars2/images/toolbar/star2.png',
                    'insert' => '{{stars>2/5}}'
                ),
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star3'),
                    'icon'   => '../../plugins/stars2/images/toolbar/star3.png',
                    'insert' => '{{stars>3/5}}'
                ),
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star4'),
                    'icon'   => '../../plugins/stars2/images/toolbar/star4.png',
                    'insert' => '{{stars>4/5}}'
                ),
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star5'),
                    'icon'   => '../../plugins/stars2/images/toolbar/star5.png',
                    'insert' => '{{stars>5/5}}'
                ),
                array(
                    'type'   => 'insert',
                    'title'  => $this->getLang('star_not_rated'),
                    'icon'   => '../../plugins/stars2/images/toolbar/starnry.png',
                    'insert' => '{{stars>-1/5}}'
                ),
            )
        );
    }
}

