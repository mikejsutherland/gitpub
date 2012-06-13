            <div id="commitbrowser">

<?

    $offset_inc = 10;
    $offset = ( ! empty($_GET['offset']) ) ? $_GET['offset'] : 0;
    if ( $offset < 0 ) { $offset = 0; }

    $next = $offset + $offset_inc;
    $prev = $offset - $offset_inc;

    $commits = $gp->getCommitLog($offset, $offset_inc);

    // Navigated too far
    if ( $offset > 0 && count($commits) == 0 ) {

        print "<div class='navbar'><a class='ajaxy left' href='".$CONFIG['base_uri']."/".
            genLink(array("offset" => $prev))."'>< Previous</a><br class='clear' /></div>";
        $error = "There are no more commits to view.\n";
        include($thispath ."include/error.php");
    }
    elseif ( count($commits) > 0 ) {

        $c = false;
        $prevts = null;

        if ( $offset > 0 || count($commits) == $offset_inc ) {
            print "<div class='navbar'>";

            if ( $offset > 0 ) {
                print "<a class='ajaxy left' href='".$CONFIG['base_uri']."/". genLink(array("offset" => $prev)) ."'>< Previous</a>";
            }

            if ( count($commits) == $offset_inc ) {
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
    } 
    else {

        $error = "There are no commits.\n";
        include($thispath ."include/error.php");
    }

?>

            </div>
