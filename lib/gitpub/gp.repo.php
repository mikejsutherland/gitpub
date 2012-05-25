<?

    # Show the file as stored in the repo
    #
    function showFileFromRepo($commit,$file) {

        $cmd = $_SESSION['CONFIG']['git'] ."/git --no-pager --git-dir=".
            $_SESSION['CONFIG']['repo_directory'] .'/'.
            $_SESSION['repo'] ." show $commit:$file";

        #print "$cmd (rc: $rc)\n";

        if ( preg_match("/\.(jpg|jpeg|png|gif|ico|bmp)$/", $file) ) {

            ob_start();
            passthru("$cmd", $rc);
            $img = ob_get_contents();
            ob_end_clean();

            if ( $rc == 0 ) {

                return "<img src='data:image/png;base64,". base64_encode($img) ."' />\n";
            }
            else {
                return "error dislaying file: $file\n";
            }
        }
        # Assume text
        else {

            exec("$cmd", $results, $rc);

            if ( $rc == 0 ) {
                return "<pre class='prettyprint linenums'>". htmlspecialchars(implode("\n", $results)) ."</pre>\n";
            }
            else {
                return "error displaying file: $file\n";
            }
        }
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
