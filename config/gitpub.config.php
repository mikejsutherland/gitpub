<?

    ## gitpub web settings ##
    $CONFIG['base_uri'] = '/gitpub';
    $CONFIG['commits_per_page'] = 15;
    $CONFIG['enable_cache'] = false;

    ## paths ##
    $CONFIG['projects_dir'] = '/Volumes/Development/git';
    $CONFIG['git_path'] = '/usr/local/git/bin';

    ## php settings ##
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set('error_reporting', E_ALL);
    //ini_set("memory_limit","50M");

    ## test strings ##
    #$_GET['repo'] = '';
    #$_GET['nav'] = ''; 
    #$_GET['o'] = ''; 
    #$_GET['commit'] = '';
    #$_GET['branch'] = '';

?>
