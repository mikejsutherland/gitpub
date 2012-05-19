<?

    # Generate the table for the file browser
    #
    function viewFileBrowserTable($files) {

        $filepath = $_SESSION['filepath'];
        $c = 0;

        foreach ($files as $fl => $val) {

            #if ( $c < 1 ) { sleep(1); $c++; }

            $file = $fl;
            $fullpath = '';

            #print "<!-- $file -->\n";

            if ( isset($filepath) && $filepath != '' ) {

                # Skip any files not from this file path
                if ( ! preg_match("|^$filepath/|", $file) ) { continue; }

                # Strip off the file path
                $file = preg_replace("|^$filepath/|", "", $file);

                $fullpath = "$filepath/";

                # Determine the parent
                if ( $c == 0 ) {

                    $parent = explode('/', $filepath);
                    array_pop($parent);

                    print "<tr>\n<td class=''> </td>\n";
                    print "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode(implode('/', $parent)) ."'>..</a></td>\n";
                    print "<td></td>\n<td></td>\n</tr>\n";

                    $c++;
                }

            }

            #print "<!-- FP: $fullpath file: $file -->\n";

            # Check if its a directory        
            if ( strpos($file, "/")  ) {

                $dir = strstr($file, "/", 1);

                if ( $dir == $prevdir ) { continue; }

                #$hist = $master->getHistory($val);
                #$lhist = get_object_vars(array_pop($hist));

                print "<tr>\n<td class='dir_icon'> </td>\n";
                print "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $dir) ."'>$dir/</a></td>\n";
                print "<td></td>\n<td>". $lhist['summary'] ."</td>\n</tr>\n";

                $prevdir = $dir;
            }
            else {

                #$hist = $master->getHistory($val);
                #$lhist = get_object_vars(array_pop($hist));

                print "<tr>\n<td class='file_icon'> </td>\n";
                print "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $file) ."'>$file</a></td>\n";
                print "<td></td>\n<td>". $lhist['summary'] ."</td>\n</tr>\n";
            }

        }

        return;
    }

    # Navigation string for directory structure
    #
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

                $navlinks .= "<a class='ajaxy' href='". $_SESSION['CONFIG']['base_uri'] 
                    ."/?repo=". $_SESSION['repo'] 
                    ."&nav=files&cwd=". base64_encode(implode('/',$base)) 
                    ."'>$piece</a>";

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
