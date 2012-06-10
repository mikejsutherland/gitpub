            <div id="commitbrowser">

<?

    $offset_inc = 10;
    $offset = ( ! empty($_SESSION['obj']) ) ? $_SESSION['obj'] : 0;
    if ( $offset < 0 ) { $offset = 0; }

    $next = $offset + $offset_inc;
    $prev = $offset - $offset_inc;

    $commits = $gp->getCommitLog($offset, $offset_inc);

    // Navigated too far
    if ( $offset > 0 && count($commits) == 0 ) {

        print "<div class='navbar'><a class='ajaxy' href='".$CONFIG['base_uri']."/?repo=".$_SESSION['repo']."&nav=commits&o=".base64_encode($prev)."'>Previous</a></div>";
        $error = "There are no more commits to view.\n";
        include($thispath ."include/error.php");
    }
    elseif ( count($commits) > 0 ) {

        $c = false;
        $prevts = null;

        print "<div class='navbar'>";
        if ( $offset > 0 ) {

            print "<a class='ajaxy' href='".$CONFIG['base_uri']."/?repo=".$_SESSION['repo']."&nav=commits&o=".base64_encode($prev)."'>Previous</a>|";
        }
        print "<a class='ajaxy' href='".$CONFIG['base_uri']."/?repo=".$_SESSION['repo']."&nav=commits&o=".base64_encode($next)."'>Next</a>";
        print "<br class='clear' /></div>";

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
                                <div class="right">
                                    <?=substr($commit['commit'], 0, 7);?>
                                </div>
                                <div class="left">
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
