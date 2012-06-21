<?
    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . 'config/gitpub.config.php');
    require_once($thispath . 'lib/gitpub/gitpub.class.php');

    session_start();

    if ( isset($_SESSION['repo']) && isset($_GET['tag']) ) {

        $tag = $_GET['tag'];
        $type = (isset($_GET['type'])) ? $_GET['type'] : "tar";

        $fn = $_SESSION['repo'];
        $fn .= ($tag[0] == "v") ? "-". substr($tag, 1) : "-". $tag;

        if ( ini_get('zlib.output_compression') ) {
            ini_set('zlib.output_compression', 'Off');
        }

        header("Content-Type: application/octet-stream");
        header("Content-disposition: attachment; filename=\"$fn.$type\"");
        #header("Content-Transfer-Encoding: binary"); 

        $gp = new GitPub($CONFIG);
        $gp->setRepo($_SESSION['repo']);

        try {

            $gp->getArchive($tag, $type);
            header("Content-Length: ". $gp->cmd['size']);
            echo $gp->cmd['results'];
        }
        catch (Exception $e) {

            print "$e\n";
        }
    }
    else {

        header("Location: ". $CONFIG['base_uri'] .'/');
    }

?>
