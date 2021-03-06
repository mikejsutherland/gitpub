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
                <div id="branchbrowser">

<?php

    try {

        $branches = $gp->getBranches();

        if ( count($branches) > 0 ) {

            $selected_branch = $gp->getCommitLog(0, 1, $_SESSION['branch'], "");
?>
                    <table class="branch browser">
                        <tbody>
<?php
            if ( count($selected_branch) > 0 ) {
?>
                            <tr class="base">
                                <td class="base_content">
                                    <div class="left log">
                                        <strong><a href='<?php echo $CONFIG['base_uri']."/". genLink(array());?>'><?php echo $_SESSION['branch'];?></a></strong><br />
                                        <span class="small grey">Last updated <?php echo relativeDate($selected_branch[0]['epoch']);?> by </span>
                                        <span class="small blue"><?php echo $selected_branch[0]['author'];?></span>
                                    </div>
                                    <div class="right small grey tright">
                                        Download<br />
                                        <a class="dl" href='<?php echo $CONFIG['base_uri']."/download.php?tag=HEAD";?>'>TAR</a>
                                        <a class="dl" href='<?php echo $CONFIG['base_uri']."/download.php?tag=HEAD&type=zip";?>'>ZIP</a>
                                    </div>
                                    <br class="clear" />
                                </td>
                            </tr>
<?php
            }

            $c = true;

            foreach ($branches as $branch) {        

                if ( $_SESSION['branch'] == $branch['branch'] || "origin/".$_SESSION['branch'] == $branch['branch'] ) { continue; }

                $branch_meta = $gp->getCommitLog(0, 1, $branch['commit'], "");
                $rev = $gp->getBranchRevisions($branch['branch']);

                $ahead = 0; $behind = 0;

                foreach ($rev as $commit) {
                                    
                    if ( preg_match("/^\>/", $commit) ) {
                        $ahead++;
                    }
                    elseif ( preg_match("/^\</", $commit) )  {
                        $behind++;
                    }
                }

?>
                            <tr class="<?php echo (($c = !$c)?'hl':'');?>">
                                <td>
                                    <div class="left log">
                                        <?php if ( $_SESSION['branch'] == $branch['branch'] ) { ?>
                                        <img src="<?php echo $CONFIG['base_uri'];?>/docs/images/asterisk.gif" /> <?php } ?>
                                        <strong><a href='<?php echo $CONFIG['base_uri']."/". genLink(array("branch" => $branch['branch']));?>'><?php echo $branch['name'];?></a></strong><br />
                                        <?php if ( count($branch_meta) > 0 ) { ?>
                                        <span class="small black">Last updated <?php echo relativeDate($branch_meta[0]['epoch']);?> by </span>
                                        <span class="small blue"><?php echo $branch_meta[0]['author'];?></span>
                                        <?php } else { ?>
                                        <span class="small black">No commits yet</span>
                                        <?php } ?>
                                    </div>
                                    <div class="right tright small grey">
                                        <?php echo $behind;?> behind | <?php echo $ahead;?> ahead
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
        }
        else {

            $error = "There are no branches.\n";
            include($thispath ."views/error.php");
        }
    }
    catch (Exception $e) {

        $error = "Error retrieving available branches.\n";
        include($thispath ."views/error.php");
    }
?>

            </div>
