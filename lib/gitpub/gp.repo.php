<?

    # Show the file as stored in the repo
    #
    function showFileFromRepo($commit,$file) {

        #print $_SESSION['CONFIG']['git'] ." show $commit:$file\n";

        $results = Array();

        exec($_SESSION['CONFIG']['git'] ."/git show $commit:$file", $results);

        return htmlspecialchars(implode("\n", $results)) ."\n";
    }


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
