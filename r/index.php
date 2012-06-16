<?
    if ( empty($_GET['service']) ) {
        header('Content-Type', 'text/plain');
    }
    else {
        header('Content-Type', "application/x-". $_GET['service'] ."-advertisement");
    }
    header('Expires', 'Fri, 01 Jan 1980 00:00:00 GMT');
    header('Pragma', 'no-cache');
    header('Cache-Control', 'no-cache, max-age=0, must-revalidate');

   /*
    * This file will with the help of the associated .htaccess file
    * return any corresponding git repo file, as would be requested
    * via a http git clone request.
    *
    */

    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . '../config/gitpub.config.php');

    $repo = null;
    $request = null;

    if ( preg_match("/^([^\/]+)\/(.+)$/", $_GET['uri'], $matches) ) {

        $repo = $matches[1];
        $request = $matches[2];
    }
    elseif ( preg_match("/^([^\/]+)/", $_GET['uri'], $matches) ) {

        $repo = $matches[1];
        $request = (isset($matches[2])) ? $matches[2] : null;
    }

    // Redirect on empty request
    if ( empty($request) ) {
        header("Location: ". $CONFIG['base_uri'] .'/');
    }
    else {

        // Reject any file request which contains parent notation
        if ( preg_match("/\.\./", $request) ) {
            print "die die die\n";
            exit;
        }

        // Basic user-agent check
        if ( preg_match("/git/", $_SERVER['HTTP_USER_AGENT']) ) {

            $repo_path = $CONFIG['projects_dir'] ."/$repo"; 
            $repo_path .= is_dir($repo_path ."/refs") ? "" : is_dir($repo_path ."/.git/refs") ? "/.git" : "";

            $requested_file = $repo_path ."/". $request;

            if ( preg_match("/^info\/refs/", $request) ) {

                $branches = scandir($repo_path ."/refs/heads");

                foreach ($branches as $branch) {

                    $requested_file = $repo_path ."/refs/heads/$branch";

                    if ( $branch == '.' || $branch == '..' || ! is_file("$requested_file") ) { continue; }

                    print trim(file_get_contents($requested_file)) ."\trefs/heads/$branch\n";
                }
            }
            #elseif ( preg_match("/^HEAD$/", $request) ) {

            #    print "ref: refs/heads/master\n";
            #}
            elseif ( file_exists($requested_file) ) {

                $fp = fopen($requested_file, 'rb');
                fpassthru($fp);
            }
            else {

                print "die die die\n";
                exit;
            }
        }
        else {

            header("Location: ". $CONFIG['base_uri'] .'/');
        }
    }

?>
