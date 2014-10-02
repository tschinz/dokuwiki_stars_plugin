<?php
/*
 * DokuWiki stars plugin
 * 2014 Zahno Silvan
 * Usage:
 *
 * {{stars>num}}         -- num = 5 or 5/7 or 100/1000
 *                       -- num = -1 gives a "not rated yet"
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the LGNU Lesser General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
if(!defined('DOKU_PLUGIN_IMAGES')) define('DOKU_PLUGIN_IMAGES',DOKU_BASE.'lib/plugins/stars/images/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_stars2 extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo(){
      return array(
        'author' => 'Zahno Silvan',
        'email'  => 'zaswiki@gmail.com',
        'date'   => '2014-10-02',
        'name'   => 'Stars2 Plugin',
        'desc'   => 'Embedding Rating Stars',
        'url'    => 'http://zawiki.zapto.org/doku.php/tschinz:dw_stars',
      );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
      return 'substition';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
      return 299;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('\{\{stars>.*?\}\}',$mode,'plugin_stars');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
      switch ($state) {
        case DOKU_LEXER_ENTER :
          break;
        case DOKU_LEXER_MATCHED :
          break;
        case DOKU_LEXER_UNMATCHED :
          break;
        case DOKU_LEXER_EXIT :
          break;
        case DOKU_LEXER_SPECIAL :
          return $match;
          break;
      }
      return array();
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
      if($mode == 'xhtml' || $mode == 'odt')
      {
        // strip {{stars> from start
        $data = substr($data,8);
        // strip }} from end
        $data = substr($data,0,-2);
        $num = $data;
        
        if (empty($num))
          $num = "0/5";
          $empty = true;

        // Get seperate num's
        $num=split('/',$num); // Strip size
        if (!isset($num[1])) 
          $num[1] = $num[0];
        
        if ($num[0]>$num[1]) 
          $num[1]=$num[0];
        
        if ($num[1]>10) 
        {
          $num[0] = 10 * $num[0] / $num[1];
          $num[1] = 10;
        } // end if ($num[1]>10) 
            
        if ($mode == 'xhtml')
        {
          if ($empty == true)
            $renderer->doc .= $this->_Stars_static($num);
          else
            $renderer->doc .= $this->_Stars_dynamic($num);
        }
        else
          $this->_Stars_static_for_odt($renderer, $num);

        return true;
        }

      return false;
    }

    function _Stars_static($d)
    {
      $options['height']       = $this->getConf('height');
      $string='<span class="starspan" onload="loadStars()" alt="' . $d[0] . '/' . $d[1] . ' stars">';
        
      // Not rated yet images
      if($d[0] < 0)
      {
        $d[0] = -$d[0];
        $nry = true;
      }
      else
        $nry = false;
        
      // render full stars
      for($i=1; $i<=$d[0]; $i++)
        if($nry == true)
          $string .= '<img class="fullstarimage" id="'.$i.'" height="'.$options['height'].'" src="' . DOKU_PLUGIN_IMAGES . 'unknownstar.png"/>';
        else
          $string .= '<img class="fullstarimage" id="'.$i.'" height="'.$options['height'].'" src="' . DOKU_PLUGIN_IMAGES . 'fullstar.png"/>';
                
                
      // render half star if necessary
      if($i-.5 <= $d[0])
      {
        $string .= '<img class="halfstarimage" id="'.$i.'" height="'.$options['height'].'" src="' . DOKU_PLUGIN_IMAGES . 'halfstar.png"/>';
        $i+= .5;
      } // end if($i-$d[0] > 0)

      for($i;$i<=$d[1];$i++)
        $string .= '<img class="emptystarimage" id="'.$i.'" height="'.$options['height'].'" src="' . DOKU_PLUGIN_IMAGES . 'emptystar.png"/>';
                
      $string .= '</span>';
        
      return $string;
    } // end function _Stars_static($d)
    
    function _Stars_dynamic($d)
    {
      $options['height']       = $this->getConf('height');
      $string='<span class="starspan" onload="loadStars()" alt="' . $d[0] . '/' . $d[1] . ' stars">';

      // render full stars
      for($i=1; $i<=$d[0]; $i++)
        $string .= '<img class="fullstarimage" id="'.$i.'" height="'.$options['height'].'" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" src="' . DOKU_PLUGIN_IMAGES . 'fullstar.png"/>';

      // render half star if necessary
      if($i-.5 <= $d[0])
      {
        $string .= '<img class="halfstarimage" id="'.$i.'" height="'.$options['height'].'" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" src="' . DOKU_PLUGIN_IMAGES . 'halfstar.png"/>';
        $i+= .5;
      } // end if($i-$d[0] > 0)

      for($i;$i<=$d[1];$i++)
        $string .= '<img class="emptystarimage" id="'.$i.'" height="'.$options['height'].'" onmouseover="highlight(this.id)" onclick="setStar(this.id)" onmouseout="losehighlight(this.id)" src="' . DOKU_PLUGIN_IMAGES . 'emptystar.png"/>';

      $string .= '</span>';

      return $string;
    } // end function _Stars_dynamic($d)

    function _Stars_static_for_odt(&$renderer, $d)
    {
      // Prepare the full path for the function _odtAddImage
      // otherwise the file will not be found!
      $src_unknown = DOKU_INC . DOKU_PLUGIN_IMAGES . 'unknownstar.png';
      $src_full    = DOKU_INC . DOKU_PLUGIN_IMAGES . 'fullstar.png';
      $src_half    = DOKU_INC . DOKU_PLUGIN_IMAGES . 'halfstar.png';
      $src_empty   = DOKU_INC . DOKU_PLUGIN_IMAGES . 'emptystar.png';

      // Not rated yet images
      if($d[0] < 0){
        $d[0] = -$d[0];
        $nry = true;
      }
      else
        $nry = false;
      
      // render full stars
      for($i=1; $i<=$d[0]; $i++)
      {
        if($nry == true)
          $renderer->_odtAddImage($src_unknown);
        else
          $renderer->_odtAddImage($src_full);
      }    
              
      // render half star if necessary
      if($i-.5 <= $d[0])
      {
        $renderer->_odtAddImage($src_half);
        $i+= .5;
      } // end if($i-$d[0] > 0)
          
      for($i;$i<=$d[1];$i++)
      {
        $renderer->_odtAddImage($src_empty);
      }
    } // end function _Stars_static_for_odt($d)
    
}

