<?

    # Return a list of all repo paths given a parent directory
    #
    function getRepos($repo_directory) {

        $repo_paths = Array();
        $repos = scandir($repo_directory, NULL);

        foreach ($repos as $repo) {

            $repo_path = $repo_directory . "/$repo";

            # Skip current, parent and anything that isn't a directory
            if ( $repo == '.' || $repo == '..' || ! is_dir($repo_path) ) { continue; } 

            # Stash the repo info
            $repo_info['name'] = $repo;
            $repo_info['path'] = $repo_path;


            array_push($repo_paths, $repo_info);
        }

        return $repo_paths;
    }

?>
