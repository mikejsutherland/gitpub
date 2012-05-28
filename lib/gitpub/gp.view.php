<?

    # Generate the table for the commit browser
    #
    function viewCommitHistoryTable($hist, $commit_id) {

        if ( count($hist) > 0 ) {

            foreach($hist as $commit) {

                $com = get_object_vars($commit);
                $details = get_object_vars($com['author']);
                $history = get_object_vars($com['history']);

                print str_pad("", 20) . "<tr>\n";
                print str_pad("", 24) . "<td class='small'>". $details['name'] ."</td>\n";
                print str_pad("", 24) . "<td class='small'>". strftime('%F %T', $details['time']) ."</td>\n";
                print str_pad("", 24) . "<td>". htmlspecialchars($com['summary'], ENT_QUOTES) ."</td>\n";
                print str_pad("", 24) . "<td class='small'>\n";
                print str_pad("", 28) .  $commit_id ."<br />\n";
                print str_pad("", 28) . "<span class='smaller'>tree: ". sha1_hex($com['tree']) ."</span>\n";
                print str_pad("", 24) . "</td>\n";
                print str_pad("", 20) . "</tr>\n";
                $commit_id = sha1_hex($com['parents'][0]);

            }
        } 

        return;
    }

    # Generate the table for the file browser
    #
    function viewFileBrowserTable($files, $tree) {

        $filepath = $_SESSION['filepath'];
        $c = 0;

        foreach ($files as $fl => $val) {

            #if ( $c < 1 ) { sleep(1); $c++; }

            $file = $fl;
            $fullpath = '';

            #print "<!-- $filepath -->\n";

            if ( isset($filepath) && $filepath != '' ) {

                # File view
                if ( $filepath == $file ) {

                    $commit = sha1_hex($_SESSION['GIT']['tip']);

                    print str_pad("", 24) . "<tr>\n";
                    print str_pad("", 28) . "<td colspan='4'>\n";
                    print str_pad("", 32) . "<div class='fileviewer'>". showFileFromRepo($commit,$file) ."</div>\n";
                    print str_pad("", 28) . "</td>\n";
                    print str_pad("", 24) . "</tr>\n";
                    break;
                }

                # Skip any files not from this file path
                if ( ! preg_match("|^$filepath/|", $file) ) { continue; }

                # Strip off the file path
                $file = preg_replace("|^$filepath/|", "", $file);

                $fullpath = "$filepath/";

                # Determine the parent
                if ( $c == 0 ) {

                    $parent = explode('/', $filepath);
                    array_pop($parent);

                    print str_pad("", 24) . "<tr>\n";
                    print str_pad("", 28) . "<td class=''> </td>\n";
                    print str_pad("", 28) . "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode(implode('/', $parent)) ."'>..</a></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 24) . "</tr>\n";

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

                print str_pad("", 24) . "<tr>\n";
                print str_pad("", 28) . "<td class='dir_icon'> </td>\n";
                print str_pad("", 28) . "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $dir) ."'>$dir/</a></td>\n";
                print str_pad("", 28) . "<td></td>\n";
                print str_pad("", 28) . "<td>". $lhist['summary'] ."</td>\n";
                print str_pad("", 24) . "</tr>\n";

                $prevdir = $dir;
            }
            else {

                #$hist = $master->getHistory($val);
                #$lhist = get_object_vars(array_pop($hist));

                print str_pad("", 24) . "<tr>\n";
                print str_pad("", 28) . "<td class='file_icon'> </td>\n";
                print str_pad("", 28) . "<td><a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $file) ."'>$file</a></td>\n";
                print str_pad("", 28) . "<td></td>\n";
                print str_pad("", 28) . "<td>". $lhist['summary'] ."</td>\n";
                print str_pad("", 24) . "</tr>\n";
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

        print str_pad("", 20) . "<ul>\n";

        foreach ($repos as $repo) {

            print str_pad("", 24) ."<li><a href='?repo=". $repo['name'] ."'>". $repo['name'] ."</a></li>\n";
        }

        print str_pad("", 20) ."</ul>\n";

        return;
    }

    function isActiveTab($val) {

        if (isset($_SESSION['nav']) && $_SESSION['nav'] == $val ) {
            print "active";
        }

        return;
    }

?>
