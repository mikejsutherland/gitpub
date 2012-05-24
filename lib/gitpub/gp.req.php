<?

    # Enable for debugging output
    #
    #ini_set ("display_errors", "1");
    #error_reporting(E_ALL);

    # Include the main configuration file
    include_once('config/gitpub.config.php');

    # Stash the configuration in the session
    $_SESSION['CONFIG'] = $CONFIG;

    # Load glip to interact with git repos
    require_once('lib/glip/glip.php');

    # Load geshi
    include_once('lib/geshi/geshi.php');

?>
