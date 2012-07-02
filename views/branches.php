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
                <div id="branchbrowser">

<?

    try {

        $branches = $gp->getBranches();
        $branch_meta = $gp->getCommitLog(0, 1, "master");

?>
                    <table class="branch browser">
                        <tbody>
                            <tr class="base">
                                <td class="base_content">
                                    <div class="left log">
                                        <strong><a href='<?=$CONFIG['base_uri']."/". genLink(array("branch" => "master"));?>'>master</a></strong><br />
                                        <span class="small grey">Last updated <?=relativeDate($branch_meta[0]['epoch']);?> by </span>
                                        <span class="small blue"><?=$branch_meta[0]['author'];?></span>
                                    </div>
                                    <div class="right small">
                                        <a class="dl" href='<?=$CONFIG['base_uri']."/download.php?tag=HEAD";?>'>Download tarball</a><br />
                                        <a class="dl" href='<?=$CONFIG['base_uri']."/download.php?tag=HEAD&amp;type=zip";?>'>Download zip</a>
                                    </div>
                                    <br class="clear" />
                                </td>
                            </tr>
<?
        $c = true;

        foreach ($branches as $branch) {        

            if ( preg_match("/master/i", $branch['branch']) ) { continue; }

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
                            <tr class="<?=(($c = !$c)?'hl':'');?>">
                                <td>
                                    <div class="left log">
                                        <? if ( $_SESSION['branch'] == $branch['branch'] ) { ?>
                                        <img src="<?=$CONFIG['base_uri'];?>/docs/images/asterisk.gif" /> <? } ?>
                                        <strong><a href='<?=$CONFIG['base_uri']."/". genLink(array("branch" => $branch['branch']));?>'><?=$branch['name'];?></a></strong><br />
                                        <? if ( count($branch_meta) > 0 ) { ?>
                                        <span class="small black">Last updated <?=relativeDate($branch_meta[0]['epoch']);?> by </span>
                                        <span class="small blue"><?=$branch_meta[0]['author'];?></span>
                                        <? } else { ?>
                                        <span class="small black">No commits yet</span>
                                        <? } ?>
                                    </div>
                                    <div class="right tright small grey">
                                        <?=$behind;?> behind | <?=$ahead;?> ahead
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

    }
    catch (Exception $e) {

        $error = $e;
        include($thispath ."views/error.php");
    }
?>

            </div>
