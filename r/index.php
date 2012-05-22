<?
   /*
    * This file will with the help of the associated .htaccess file
    * return any corresponding git repo file, as would be requested
    * via a http git clone request.
    *
    */

    # Load the config file so we know where the repo's are
    include_once('config/gitpub.config.php');
    include_once('lib/gitpub/gp.req.php');

    debug($_GET['l']);

    if ( preg_match("/git/", $_SERVER['HTTP_USER_AGENT']) ) {

        # Set the path of the requested file
        $requested_file = $CONFIG['repo_directory'] .'/'. $_GET['l'];

        if ( file_exists($requested_file) ) {

            # Set the content-type
            header("Content-type: ". mime_content_type($requested_file) ."\n\n");

            $fp = fopen($requested_file, 'rb');
            fpassthru($fp);
            #print file_get_contents($requested_file);
        }
        elseif ( preg_match("/(.+)\/info\/refs$/", $requested_file, $repo) ) {

            $cmd = "/usr/local/git/bin" . "/git-upload-pack '". "/Volumes/Development/git/gitpub/" . "'";
            debug("running: $cmd");
            exec($cmd);

        }
    }

    # Redirect to parent if request didn't come from git
    else {

        header("Location: ". $CONFIG['base_uri'] .'/');

    }


    function getMimeType($filename) {

        if ( function_exists('finfo_open') ) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    function debug($msg) {

        $file = "gitpup.log";
        $fh = fopen($file, 'a') or die("can't open file");
        fwrite($fh, "$msg\n");
        fclose($fh);
    }

?>
