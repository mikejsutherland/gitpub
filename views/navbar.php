                <div class="navbar">
<?

    $navlinks = "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/". genLink(array("o" => null)) ."'>". $_SESSION['repo'] ."</a>";

    if ( ! empty($_SESSION['o']) ) {

        $pathsegments = explode('/', preg_replace("/\/$/", "", $_SESSION['o']));
        $pathpieces = count($pathsegments);

        if ( $pathpieces > 0 ) {

            $navlinks .= "/";
            $c = 0;

            $base = Array();

            foreach ($pathsegments as $piece) {

                array_push($base, $piece);
                $c++;

                if ( $c < $pathpieces ) {

                    $navlinks .= "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/".
                        genLink(array("o" => implode('/',$base) ."/"))
                        ."'>$piece</a>";

                    $navlinks .= "/";
                }
                else {

                    $navlinks .= $piece;
                }
            }
        }
    }

    if ( ! empty($_SESSION['commit']) ) {

        $navlinks .= " @ <span class=''><a href='". $CONFIG['base_uri'] ."/". genLink(array("nav" => "commits", "o" => null)) ."'>". substr($_SESSION['commit'], 0, 7) ."</a></span>";
    }

    print "$navlinks\n";
?>

                </div>
