<?

    # Include the main configuration file
    require_once('config/gitpub.config.php');
    # Stash the configuration in the session
    $_SESSION['CONFIG'] = $CONFIG;

    # Load glip to interact with git repos
    require_once('lib/glip/glip.php');

?>
