<?
   /*
    * Copyright (c) 2012 codesmak.com
    *
    * This file is part of gitpub.
    *
    * gitpub is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
    *
    * gitpub is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with gitpub.  If not, see <http://www.gnu.org/licenses/>.
    *
    */

    function genLink($params = array()) {

        $uri = "";
        $link = ""; 

        // GET vars are parsed/stored as SESSION vars, include them
        $default_params = isset($_SESSION) ? $_SESSION : array();
        $parameters = array_merge($default_params, $params);

        $mod_rewrite = has_mod_rewrite();

        foreach ($parameters as $key => $val) {

            if ( empty($val) || !isset($val) ) { continue; }

            if ( $mod_rewrite ) {

                if ( $key == 'repo' || $key == 'branch' || $key == 'nav' ) { continue; }
            }

            if ( $key == "o" ) {

                $link .= "&o=". base64_encode($val);
            }
            else {
                $link .= "&$key=$val";
            }
        }

        if ( $mod_rewrite ) {

            $uri .= (isset($parameters['repo'])) ? $parameters['repo'] ."/" : "";
            $uri .= (isset($parameters['branch'])) ? $parameters['branch'] ."/" : "";
            $uri .= (isset($parameters['nav'])) ? $parameters['nav'] ."/" : "";
        }

        if ( ! empty($link) ) {

            $link[0] = "?"; 
            $link = str_replace('&', '&amp;', $link);
        }

        return $uri . $link;
    }

    function has_mod_rewrite() {

        if ( function_exists("apache_get_modules") ) {

            return ($_SERVER['HTTP_MOD_REWRITE'] == 'On' || in_array('mod_rewrite', apache_get_modules())) ? true : false;
        }
        else {

            return ($_SERVER['HTTP_MOD_REWRITE'] == 'On') ? true : false;
        }
    }

    function relativeDate($date) {

        // Display the relative time difference between then and now
        // Source: http://stackoverflow.com/a/10747954

        $now = time();
        $diff = $now - $date;

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s seconds ago' : 'a second ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s minutes ago' : 'one minute ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 24){
            return sprintf($diff > 1 ? '%s hours ago' : 'an hour ago', $diff);
        }

        $diff = floor($diff/24);

        if ($diff < 7){
            return sprintf($diff > 1 ? '%s days ago' : 'yesterday', $diff);
        }

        if ($diff < 30) {
            $diff = floor($diff / 7);

            return sprintf($diff > 1 ? '%s weeks ago' : 'one week ago', $diff);
        }

        $diff = floor($diff/30);

        if ($diff < 12){
            return sprintf($diff > 1 ? '%s months ago' : 'last month', $diff);
        }

        $diff = date('Y', $now) - date('Y', $date);

        return sprintf($diff > 1 ? '%s years ago' : 'last year', $diff);
    }

    function isActiveTab($val) {

        if ( isset($_SESSION['nav']) && $_SESSION['nav'] == $val ) {
            print "active";
        }

        return;
    }

?>
