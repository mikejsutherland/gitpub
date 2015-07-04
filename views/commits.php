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

    $offset = 0;

    if ( ! empty($_GET['offset']) ) {

        $offset = $_GET['offset'];

        // Reset offset to 0 if its out of range or not a number
        if ( ! preg_match("/^\d+$/", $offset) || $offset < 0 ) { $offset = 0; }
    }

    $next = $offset + $CONFIG['commits_per_page'];
    $prev = $offset - $CONFIG['commits_per_page'];

    try {

        $commits = $gp->getCommitLog($offset, $CONFIG['commits_per_page']);

        // Navigated too far
        if ( $offset > 0 && count($commits) == 0 ) {

            print "<div class='navbar'><a class='ajaxy left' href='".$CONFIG['base_uri']."/".
                genLink(array("offset" => $prev))."'>< Previous</a><br class='clear' /></div>";
            $error = "There are no more commits to view.\n";
            include($thispath ."views/error.php");
        }
        elseif ( count($commits) > 0 ) {

            $c = false;
            $prevts = null;

            if ( $offset > 0 || count($commits) == $CONFIG['commits_per_page'] ) {
                print "<div class='navbar'>";

                if ( $offset > 0 ) {
                    print "<a class='ajaxy left' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $prev)) ."'>< Newer</a>";
                }

                if ( count($commits) == $CONFIG['commits_per_page'] ) {
                    print "<a class='ajaxy right' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $next)) ."'>Older ></a>";
                }
                print "<br class='clear' /></div>";
            }

            foreach($commits as $commit) {

                if ( $prevts !== $commit['date'] ) {

                    $c = false;

                    if ( ! empty($prevts) ) {
?>
                    </tbody>
                </table>
                <br />

<?php
                    }

                    $prevts = $commit['date'];
?>

                <table class="commit browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?php echo $commit['date'];?></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                }
?>
                        <tr class="<?php echo (($c = !$c)?'hl':'');?>">
                            <td>
                                <div class="right tright grey">
                                    <span class="small">Show commit:</span> <a href='<?php echo $CONFIG['base_uri']."/".
                                        genLink(array("offset" => null, "commit" => $commit['commit']));?>'><?php echo substr($commit['commit'], 0, 7);?></a><br />
                                    <span class="small"><a href="<?php echo $CONFIG['base_uri']."/".
                                        genLink(array("commit" => $commit['commit'], "nav" => "files", "o" => null));?>">Browse code @ <?php echo substr($commit['commit'], 0, 7);?></a>
                                    </span>
                                </div>
                                <div class="left log">
                                    <strong><?php echo htmlspecialchars($commit['summary'][0], ENT_QUOTES);?></strong><br />
                                    <span class="small">
                                        <span class="blue"><?php echo htmlspecialchars($commit['author'], ENT_QUOTES);?></span>
                                        <span class="grey"> -- <?php echo ((isset($commit['epoch']))?relativeDate($commit['epoch']):$commit['date']);?></span>
                                    </span>
                                </div>
                                <br class="clear" />
                            </td>
                        </tr>
<?php
            }
?>
                    </tbody>
                </table>
<?php
            if ( $offset > 0 || count($commits) == $CONFIG['commits_per_page'] ) {
                print "<div class='navbar'>";

                if ( $offset > 0 ) {
                    print "<a class='ajaxy left' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $prev)) ."'>< Newer</a>";
                }

                if ( count($commits) == $CONFIG['commits_per_page'] ) {
                    print "<a class='ajaxy right' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $next)) ."'>Older ></a>";
                }
                print "<br class='clear' /></div>";
            }

        } 
        else {

            $error = "There are no commits.\n";
            include($thispath ."views/error.php");
        }
    }
    catch (Exception $e) {

        $error = "Unable to retrieve commits.";
        include($thispath ."views/error.php");
    }
?>
            </div>
