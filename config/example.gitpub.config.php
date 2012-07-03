<?

    #######################################################################
    # GitPub Settings:
    #
    # URI where gitpub will be served from (no trailing slash)
    #
    $CONFIG['base_uri'] = '/gitpub';

    # Set the path to the directory where you keep your
    # git repositories
    #
    $CONFIG['projects_dir'] = '/Volumes/Development';

    # Set the path to git (no trailing slash)
    #
    $CONFIG['git_path'] = '/usr/bin';

    # Number of commits to show per page
    #
    $CONFIG['commits_per_page'] = 15;

    # Caching -- controls caching of git command output
    # which can save overhead on larger or busier projects.
    # Requires a cache directory to be created and given
    # write permissions by the webserver.
    #
    $CONFIG['enable_cache'] = false;
    $CONFIG['cache_dir'] = '/tmp/gitpub.cache';

    # Allow cloning over http -- requires post-update hook to be
    # enabled. (cp hooks/post-update.sample hooks/post-update)
    #
    $CONFIG['enable_clone'] = true;

    #######################################################################
    # PHP Settings:
    #
    # The below php settings can be adjusted here if you are
    # experiencing odd behavior from gitpub. If all goes well
    # you should not need them.
    #

    # Increase the error reporting by uncommenting the below
    # two lines.
    #
    #error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    #ini_set('error_reporting', E_ALL);

    # Large files can cause out of memory exhausted errors
    # The memory limit can be adjusted here.
    #
    #ini_set("memory_limit","50M");

    # Disable output compression if tag downloads are corrupt
    #
    #ini_set('zlib.output_compression', 'Off');

?>
