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

    $cachedir = "cache/". $_SESSION['repo'];

    // Create the cache dir if it doesn't exist
    if ( ! is_dir("$cachedir") ) {
        mkdir("$cachedir", 0777, true);
    }

    // Flush the cache if the meta value is no longer current
    if ( cache_meta("$cachedir/meta") !== $gp->tip ) {

        if ( invalidate_cache("$cachedir") ) {
            cache_meta("$cachedir/meta", $gp->tip);
        }
    }

    // Set the cache file
    $cachefile = basename($_SERVER['SCRIPT_URI']);
    if ($_SERVER['QUERY_STRING']!='') {
        $cachefile .= '_'.base64_encode($_SERVER['QUERY_STRING']);
    }
    $cachefile = sha1($cachefile) .".cache.html";

    // Request is already cached, return contents from cache
    if ( file_exists("$cachedir/$cachefile") ) {

        readfile("$cachedir/$cachefile"); 
        exit; // exit the script
    }
    // Start the caching process
    else {

        ob_start(); // enable the output buffer
    }

    function cache_meta($metafile, $meta = null) {

        // Read the cache meta value
        if ( empty($meta) ) {

            $cachemeta = null;

            if ( file_exists("$metafile") ) {
                $cachemeta = file_get_contents("$metafile");
            }
            return $cachemeta;
        }
        // Write the meta value
        else {

            $fp = fopen("$metafile", 'w');
            fwrite($fp, $meta);
            fclose($fp);
        }

        return;
    }

    function invalidate_cache($path) {

        // deletes all files in a given path

        if ( is_dir("$path") ) {

            $cachefiles = scandir("$path");

            foreach ($cachefiles as $file) {

                if ( $file == "." || $file == ".." ) { continue; }

                chmod("$path/$file", 0777);
                unlink("$path/$file");
            }
            
            return true;
        }

        return false;
    }

    function write_cache($cachefile = null) {

        print "<!-- cache generated at: ". time() ." -->\n";

        // Set the cache dir
        $cachedir = "cache/". $_SESSION['repo'];

        // Set the cache file
        if ( empty($cachefile) ) {

            $cachefile = basename($_SERVER['SCRIPT_URI']);
            if ($_SERVER['QUERY_STRING']!='') {
                $cachefile .= '_'.base64_encode($_SERVER['QUERY_STRING']);
            }
            $cachefile = sha1($cachefile) .".cache.html";
        }

        $fp = fopen("$cachedir/". $cachefile, 'w'); 
        fwrite($fp, ob_get_contents()); 
        fclose($fp);
        ob_end_flush();
    }

?>
