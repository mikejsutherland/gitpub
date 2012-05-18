<?

    function viewRepos($repos) {

        print "<ul>";

        foreach ($repos as $repo) {

            print "<li><a href='?repo=". $repo['name'] ."'>". $repo['name'] ."</a></li>\n";
        }

        print "</ul>\n";

        return;
    }

    function isActiveTab($val) {

        if (isset($_SESSION['nav']) && $_SESSION['nav'] == $val ) {
            print "active";
        }

        return;
    }

?>
