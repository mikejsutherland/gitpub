<?
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

<?

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
                    print "<a class='ajaxy left' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $prev)) ."'>< Previous</a>";
                }

                if ( count($commits) == $CONFIG['commits_per_page'] ) {
                    print "<a class='ajaxy right' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $next)) ."'>Next ></a>";
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

<?
                    }

                    $prevts = $commit['date'];
?>

                <table class="commit browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?=$commit['date'];?></th>
                        </tr>
                    </thead>
                    <tbody>
<?
                }
?>
                        <tr class="<?=(($c = !$c)?'hl':'');?>">
                            <td>
                                <div class="right" style="text-align: right;">
                                    <span class="small grey">commit</span>
                                    <span class="blue"><a href='<?=$CONFIG['base_uri']."/".
                                        genLink(array("offset" => null, "commit" => $commit['commit']));?>'><?=substr($commit['commit'], 0, 10);?></a>
                                    </span>
                                    <br />
                                    <span class="smaller">
                                        <a href="<?=$CONFIG['base_uri']."/".
                                            genLink(array("commit" => $commit['commit'], "nav" => "files", "o" => null));?>">Browse code @ <?=substr($commit['commit'], 0, 7);?></a>
                                    </span>
                                </div>
                                <div class="left log">
                                    <strong><?=htmlspecialchars($commit['summary'][0], ENT_QUOTES);?></strong><br />
                                    <span class="small">
                                        <span class="blue"><?=htmlspecialchars($commit['author'], ENT_QUOTES);?></span>
                                        <span class="grey"> -- <?=((isset($commit['epoch']))?relativeDate($commit['epoch']):$commit['date']);?></span>
                                    </span>
                                </div>
                                <br class="clear" />
                            </td>
                        </tr>
<?
            }
?>
                    </tbody>
                </table>
<?
            if ( $offset > 0 || count($commits) == $CONFIG['commits_per_page'] ) {
                print "<div class='navbar'>";

                if ( $offset > 0 ) {
                    print "<a class='ajaxy left' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $prev)) ."'>< Previous</a>";
                }

                if ( count($commits) == $CONFIG['commits_per_page'] ) {
                    print "<a class='ajaxy right' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $next)) ."'>Next ></a>";
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
