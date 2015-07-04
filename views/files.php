<?php
   /*
    * Copyright (c) 2012 codesmak.com
    *
    * This file is part of gitpub.
    *
    * gitpub is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
    *
    * gitpub is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with gitpub.  If not, see <http://www.gnu.org/licenses/>.
    *
    */
?>
            <div id="filebrowser">
<?php

    // Display file
    if ( ! empty($_SESSION['o']) && ! preg_match("/\/$/", $_SESSION['o']) ) {

        $path = preg_replace("/\/$/", "", $_SESSION['o']);
        $path_segments = explode('/', $path);
        $file = array_pop($path_segments);

        try {

            $file_contents = $gp->getFile($_SESSION['o'], $_SESSION['commit']);

            include($thispath .'views/navbar.php');
?>
                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?php echo $file;?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class='fileviewer'>
                                    <?php echo $file_contents;?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
<?php

        }
        catch (Exception $e) {

            $error = "Requested file is invalid.\n";
            include($thispath ."views/error.php");
        }

    }
    // Display file tree
    else {

        try {

            $files = $gp->getTree($_SESSION['o'], $_SESSION['commit']);

            if ( count($files) > 0 ) {

                include($thispath .'views/navbar.php');
?>
                    <table class="file browser">
                        <thead>
                            <tr class="gradient_gray">
                                <th style="width: 20px;"></th>
                                <th>name</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
                if ( ! empty($_SESSION['o']) ) {

                    $path = preg_replace("/\/$/", "", $_SESSION['o']);
                    $parent = explode('/', $path);
                    array_pop($parent);

                    $parent_uri = $CONFIG['base_uri'] ."/"; #?repo=". $_SESSION['repo'] .'&nav=files'; 

                    if ( ! empty($parent) ) {

                        $parent_uri .= genLink(array("o" => implode('/', $parent) ."/")); 
                    }
                    else {

                        $parent_uri .= genLink(array("o" => null));
                    }
?>
                        <tr>
                            <td class=''> </td>
                            <td><a class='ajaxy' href='<?php echo $parent_uri;?>'>..</a></td>
                        </tr>
<?php
                }

                foreach ($files as $file) {

                    # Check if its a directory        
                    if ( strpos($file, "/")  ) {

                        $dir = strstr($file, "/", 1);

                        print str_pad("", 24) . "<tr>\n";
                        print str_pad("", 28) . "<td class='dir_icon'> </td>\n";
                        print str_pad("", 28) . "<td><a class='ajaxy' href='". $CONFIG['base_uri'] ."/".
                            genLink(array("o" => $_SESSION['o'] . $dir ."/")) ."'>$dir/</a></td>\n";
                        print str_pad("", 24) . "</tr>\n";

                    }
                    else {

                        print str_pad("", 24) . "<tr>\n";
                        print str_pad("", 28) . "<td class='file_icon'> </td>\n";
                        print str_pad("", 28) . "<td><a class='ajaxy' href='". $CONFIG['base_uri'] ."/".
                            genLink(array("o" => $_SESSION['o'] . $file)) ."'>$file</a></td>\n";
                        print str_pad("", 24) . "</tr>\n";
                    }

                }
?>
                    </tbody>
                </table>
<?php
            }
            else {

                $error = "There are no files.\n";
                include($thispath. 'views/error.php');
            }
        }
        catch (Exception $e) {

            $error = "Requested file is invalid.\n";
            include($thispath ."views/error.php");
        }
    }
?>
            </div>

