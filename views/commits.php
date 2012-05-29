<?
    #$commit_id = sha1_hex($_SESSION['GIT']['tip']);
    #$hist = $_SESSION['GIT']['object']->getHistory();
    # Reverse the history as we want the newest displayed first
    #$hist = array_reverse($hist);

    #print "<pre>";
    #print_r(get_object_vars(array_shift($hist)));
    #print "</pre>\n"

    $commits = $gp->getCommitLog($_GET['offset'], 15);

    if ( count($commits) > 0 ) {

?>

            <table class="commit browser">
                <thead>
                    <tr class="gradient_gray">
                        <th>name</th>
                        <th>timestamp</th>
                        <th>message</th>
                        <th>commit</th>
                    </tr>
                </thead>
                <tbody>

<?      foreach($commits as $commit) { ?>

                    <tr>
                        <td class='small'><?=htmlspecialchars($commit['author'], ENT_QUOTES);?></td>
                        <td class='small'><?=$commit['date'];?></td>
                        <td><?=htmlspecialchars($commit['summary'][0], ENT_QUOTES);?></td>
                        <td class='small'><?=substr($commit['commit'], 0, 7);?></td>
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

