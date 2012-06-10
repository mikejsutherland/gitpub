            <div id="filebrowser">

                <div class="navbar">
<?

    $navlinks = "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/?repo=". $_SESSION['repo'] ."&nav=files'>". $_SESSION['repo'] ."</a>";

    if ( ! empty($_SESSION['obj']) ) { 

        $pathsegments = explode('/', preg_replace("/\/$/", "", $_SESSION['obj']));
        $pathpieces = count($pathsegments);

        if ( $pathpieces > 0 ) {

            $navlinks .= "/";
            $c = 0;

            $base = Array();

            foreach ($pathsegments as $piece) {

                array_push($base, $piece);
                $c++;

                if ( $c < $pathpieces ) {

                    $navlinks .= "<a class='ajaxy' href='". $CONFIG['base_uri']
                        ."/?repo=". $_SESSION['repo']
                        ."&nav=files&o=". base64_encode(implode('/',$base) ."/")
                        ."'>$piece</a>";

                    $navlinks .= "/";
                }
                else {

                    $navlinks .= "<a class='ajaxy' href='". $CONFIG['base_uri']
                        ."/?repo=". $_SESSION['repo']
                        ."&nav=files&o=". base64_encode(implode('/',$base))
                        ."'>$piece</a>";
                }
            }
        }
    }

    print "$navlinks\n";
?>

                </div>

<?

    // Display file
    if ( ! empty($_SESSION['obj']) && ! preg_match("/\/$/", $_SESSION['obj']) ) {

        $path = preg_replace("/\/$/", "", $_SESSION['obj']);
        $path_segments = explode('/', $path);
        $file = array_pop($path_segments);

?>
                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?=$file;?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class='fileviewer'><?=$gp->getFile($_SESSION['obj']);?></div></td>
                        </tr>
                    </tbody>
                </table>
<?

    }
    // Display file tree
    else {

        $files = $gp->getTree($_SESSION['obj']);

        if ( count($files) > 0 ) {

?>
                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th style="width: 20px;"></th>
                            <th style="width: 200px;">name</th>
                            <th style="width: 100px;">age</th>
                            <th>message</th>
                        </tr>
                    </thead>
                    <tbody>
<?

            if ( ! empty($_SESSION['obj']) ) {

                $path = preg_replace("/\/$/", "", $_SESSION['obj']);
                $parent = explode('/', $path);
                array_pop($parent);

                $parent_uri = $CONFIG['base_uri'] ."/". $_SESSION['repo'] .'/files/'; 

                if ( ! empty($parent) ) {

                    $parent_uri .= base64_encode(implode('/', $parent) ."/") ."/";
                }
?>
                        <tr>
                            <td class=''> </td>
                            <td><a class='ajaxy' href='<?=$parent_uri;?>'>..</a></td>
                            <td></td>
                            <td></td>
                        </tr>
<?
            }

            foreach ($files as $file) {

                # Check if its a directory        
                if ( strpos($file, "/")  ) {

                    $dir = strstr($file, "/", 1);

                    print str_pad("", 24) . "<tr>\n";
                    print str_pad("", 28) . "<td class='dir_icon'> </td>\n";
                    print str_pad("", 28) . "<td><a class='ajaxy' href='". $CONFIG['base_uri'] ."/". $_SESSION['repo'] .'/files/'.
                        base64_encode($_SESSION['obj'] . $dir ."/") ."/'>$dir/</a></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 24) . "</tr>\n";

                }
                else {

                    print str_pad("", 24) . "<tr>\n";
                    print str_pad("", 28) . "<td class='file_icon'> </td>\n";
                    print str_pad("", 28) . "<td><a class='ajaxy' href='". $CONFIG['base_uri'] ."/". $_SESSION['repo'] .'/files/'.
                        base64_encode($_SESSION['obj'] . $file) ."/'>$file</a></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 28) . "<td></td>\n";
                    print str_pad("", 24) . "</tr>\n";
                }

            }
?>
                    </tbody>
                </table>
<?
        }
        else {

            $error = "This repository has no files yet.\n";
            include($thispath. 'include/error.php');
        }
    }
?>
            </div>

