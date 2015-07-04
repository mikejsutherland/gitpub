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
            <div id="commitbrowser">
<?php

    try {

        $diffs = $gp->getCommitDiff($_SESSION['commit']);

        // Display file diffs
        if ( count($diffs) > 0 ) {

?>
                <div class="navbar">
                    Commit diff <?php echo $_SESSION['commit'];?>
                </div>

                <table class="commit browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?php echo $diffs['commit_info']['date'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hl">
                            <td>
                                <div class="right" style="text-align: right;">
                                    <span class="small grey">commit</span>
                                    <span class="small black"><?php echo $diffs['commit_info']['commit']?></span>
                                    <br />
                                    <span class="small">
                                        <a href="<?php echo $CONFIG['base_uri']."/".genLink(array("commit" => $diffs['commit_info']['commit'], "nav" => "files", "o" => null));?>">Browse code @ <?php echo substr($diffs['commit_info']['commit'], 0, 7);?></a>
                                    </span>
                                </div>
                                <div class="left log">
                                    <strong><?php echo htmlspecialchars($diffs['commit_info']['summary'][0], ENT_QUOTES);?></strong><br />
                                    <span class="small">
                                        <span class="blue"><?php echo htmlspecialchars($diffs['commit_info']['author'], ENT_QUOTES);?></span>
                                        <span class="grey"> -- <?php echo ((isset($diffs['commit_info']['epoch']))?relativeDate($diffs['commit_info']['epoch']):$diffs['commit_info']['date']);?></span>
                                    </span>
                                </div>
                                <br class="clear" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />

<?php

            foreach ( $diffs['diffs'] as $filediff ) {
?>

                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th>
                                <?php echo $filediff['file'];?>
                                <span class="right blue">
<?php
    if ( $filediff['mode'] !== "deleted" ) {

        print "<a href='". $CONFIG['base_uri'] ."/".
            genLink(array(
                "o" => $filediff['file'],
                "commit" => $diffs['commit_info']['commit'],
                "nav" => "files"
            )). "'>View file @ ". substr($diffs['commit_info']['commit'], 0, 7) ."</a>";
    }
?>
                                </span>
                                <br class='clear' />
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class='fileviewer'>
<?php

    $class = "";
    $action = "";

    if ( isset($filediff['lines']) ) {
        $action = ", with ". $filediff['lines'];
        $action .= ($filediff['lines']>1) ? " lines" : " line";
    }

    if ( $filediff['mode'] == "new" ) {
        $class = "add";
    }
    elseif ( $filediff['mode'] == "deleted" ) {
        $class = "remove";
    }
    
?>


                                    <pre class='diff'>
<?php 

                if ( $filediff['mode'] == "new" ) {

                    if ( $filediff['type'] == "text" ) {

                        print "<span class='$class'>". ucfirst($filediff['mode']) ." file$action.</span>\n";
                    }
                    else {

                        print "<span class='$class'>". ucfirst($filediff['mode']) ." ". $filediff['type'] ." file.</span>\n";
                    }
                }
                elseif ( $filediff['mode'] == "deleted" ) {

                    print "<span class='$class'>". ucfirst($filediff['mode']) ." file.</span>\n";
                }
                else {

                    foreach ($filediff['diff'] as $line) {

                        if ( preg_match("/^@@/", $line) ) {

                            print "<span class='info'>$line</span>\n";
                        }
                        elseif ( preg_match("/^\+/", $line) ) {

                            print "<span class='add'>$line</span>\n";
                        }
                        elseif ( preg_match("/^-/", $line) ) {

                            print "<span class='remove'>$line</span>\n";
                        }
                        else {

                            print "<span class=''>$line</span>\n";
                        }
                    }
                }

?>
                                    </pre>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />
<?php
            }
        }
        // Display error
        else {

            $error = "Unable to display commit diff\n";
            include($thispath ."views/error.php");
        }

    }
    catch (Exception $e) {

        $error = "Unable to retrieve the requested commit.";
        include($thispath ."views/error.php");
    }

?>
            </div>

