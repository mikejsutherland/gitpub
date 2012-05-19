<?

    function getFileTreeNav($filepath) {

        $pathsegments = explode('/', $filepath);
        $pathpieces = count($pathsegments);
        $navlinks = "";

        if ( $pathpieces > 0 ) {

            $navlinks .= "/";
            $c = 0;

            $base = Array();

            foreach ($pathsegments as $piece) {

                array_push($base, $piece);
                $c++;

                $navlinks .= "<a class='ajaxy' href='". $_SESSION['CONFIG']['base_uri'] ."/?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode(implode('/',$base)) ."'>$piece</a>";

                if ( $c < $pathpieces ) { 
                    $navlinks .= "/";
                }
            }
        }

        return $navlinks;
    }

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
