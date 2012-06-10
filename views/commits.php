<?

    $commits = $gp->getCommitLog($_GET['offset'], 15);

    if ( count($commits) > 0 ) {

        $c = false;
        $prevts = null;

        foreach($commits as $commit) {

            if ( $prevts !== $commit['date'] ) {

                $c = false;
                $prevts = $commit['date'];

                if ( ! empty($prevts) ) {
?>
                </tbody>
            </table>
            <br />

<?
                }
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

