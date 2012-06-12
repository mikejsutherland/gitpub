
                <div id="branchbrowser">

<?

    try {

        $branches = $gp->getBranches();

        #print "<pre>"; print_r($branches); print "</pre>\n";

        $branch_meta = $gp->getCommitLog(0, 1, "master");

?>
                    <table class="branch browser">
                        <tbody>
                            <tr class="base">
                                <td>
                                    <div class="left log">
                                        <strong><a href='<?=$CONFIG['base_uri']."/". genLink(array("branch" => "master"));?>'>master</a></strong><br />
                                        <span class="small grey">Last updated <?=relativeDate($branch_meta[0]['epoch']);?> by </span>
                                        <span class="small blue"><?=$branch_meta[0]['author'];?></span>
                                    </div>
                                    <br class="clear" />
                                </td>
                            </tr>
<?
        $c = true;

        foreach ($branches as $branch) {        

            if ( preg_match("/master/i", $branch['name']) ) { continue; }


            $branch_meta = $gp->getCommitLog(0, 1, $branch['commit']);
            $rev = $gp->getBranchRevisions($branch['name']);

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
                                        <? if ( $_SESSION['branch'] == $branch['name'] ) { ?>
                                        <img src="<?=$CONFIG['base_uri'];?>/docs/images/asterisk.gif" /> <? } ?>
                                        <strong><a class="ajaxy" href='<?=$CONFIG['base_uri']."/". genLink(array("branch" => $branch['name']));?>'><?=$branch['name'];?></a></strong><br />
                                        <span class="small black">Last updated <?=relativeDate($branch_meta[0]['epoch']);?> by </span>
                                        <span class="small blue"><?=$branch_meta[0]['author'];?></span>
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
        include($thispath ."include/error.php");
    }
?>

            </div>
