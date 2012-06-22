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

   /*
    * This file will with the help of the associated .htaccess file
    * return any corresponding git repo file, as would be requested
    * via a http git clone request.
    *
    */

    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . '../config/gitpub.config.php');

    // Redirect if this feature is disabled
    if ( ! $CONFIG['enable_clone'] ) {

        header("Location: ". $CONFIG['base_uri'] .'/');
    }

    $repo = null;
    $request = null;

    if ( preg_match("/^([^\/]+)\/(.+)$/", $_GET['uri'], $matches) ) {

        $repo = $matches[1];
        $request = $matches[2];
    }
    elseif ( preg_match("/^([^\/]+)/", $_GET['uri'], $matches) ) {

        $repo = $matches[1];
        $request = null;
    }

    // Redirect on empty request
    if ( empty($request) ) {

        header("Location: ". $CONFIG['base_uri'] .'/');
    }
    else {

        // Reject any file request which contains parent notation
        if ( preg_match("/\.\./", $request) ) {

            error_log("gitpub: attempt to break out of repo directory", 0);
            exit;
        }

        // Basic user-agent check
        if ( preg_match("/git/", $_SERVER['HTTP_USER_AGENT']) ) {

            $repo_path = $CONFIG['projects_dir'] ."/$repo"; 
            $repo_path .= is_dir($repo_path ."/refs") ? "" : is_dir($repo_path ."/.git/refs") ? "/.git" : "";

            // Ensure update hook is enabled
            if ( file_exists($repo_path ."/hooks/update") ) {

                $requested_file = $repo_path ."/". $request;

                if ( file_exists($requested_file) ) {

                    // Set the headers
                    if ( empty($_GET['service']) ) {
                        header('Content-Type', 'text/plain');
                    }
                    else {
                        header('Content-Type', "application/x-". $_GET['service'] ."-advertisement");
                    }
                    header('Expires', 'Fri, 01 Jan 1980 00:00:00 GMT');
                    header('Pragma', 'no-cache');
                    header('Cache-Control', 'no-cache, max-age=0, must-revalidate');

                    // Return the file to git
                    $fp = fopen($requested_file, 'rb');
                    fpassthru($fp);
                }
                else {
            
                    if ( preg_match("/^info\/refs/", $request) ) {

                        error_log("gitpub: failed to find $request, did you run 'git update-server-info' on your repo?", 0);
                    }
                    else {

                        error_log("gitpub: failed to find $request", 0);
                    }

                    // Tell git its not available
                    header("HTTP/1.0 404 Not Found");
                }
            }
            // Update hook is not enabled, cannot accurately clone over http
            else {

                error_log("gitpub: failed to find $request, did you enable the update hook?", 0);
                header('HTTP/1.1 500 Internal Server Error');
            }
        }
        else {

            error_log("gitpub: http clone request did not originate from git", 0);
            header("Location: ". $CONFIG['base_uri'] .'/');
        }
    }

?>
