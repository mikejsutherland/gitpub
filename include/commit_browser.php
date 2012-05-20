<?
    $commit_id = sha1_hex($_SESSION['GIT']['tip']);
    $hist = $_SESSION['GIT']['object']->getHistory();
    # Reverse the history as we want the newest displayed first
    $hist = array_reverse($hist);

    #print "<pre>";
    #print_r(get_object_vars(array_shift($hist)));
    #print "</pre>\n"
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
<?=viewCommitHistoryTable($hist, $commit_id);?>
                </tbody>
            </table>
