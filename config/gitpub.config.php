<?

    ## gitpub web settings ##
    $CONFIG['base_uri'] = '/gitpub';
    $CONFIG['commits_per_page'] = 15;

    ## paths ##
    $CONFIG['projects_dir'] = '/Volumes/Development/git';
    $CONFIG['git_path'] = '/usr/local/git/bin';

    ## Testing ##
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set('error_reporting', E_ALL);

?>
