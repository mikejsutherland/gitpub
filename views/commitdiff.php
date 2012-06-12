            <div id="commitbrowser">

                <div class="navbar">
                    Commit diff <?=$_SESSION['commit'];?>
                </div>

<?

    try {

        $diffs = $gp->getCommitDiff($_SESSION['commit']);

        // Display file diffs
        if ( count($diffs) > 0 ) {

?>

                <table class="commit browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th><?=$diffs['commit_info']['date'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hl">
                            <td>
                                <div class="right" style="text-align: right;">
                                    <span class="small grey">commit</span>
                                    <span class="small black"><?=$diffs['commit_info']['commit']?></span>
                                    <br />
                                    <span class="small">
                                        <a href="<?=$CONFIG['base_uri']."/".genLink(array("commit" => $diffs['commit_info']['commit'], "nav" => "files", "o" => null));?>">Browse code @ <?=substr($diffs['commit_info']['commit'], 0, 7);?></a>
                                    </span>
                                </div>
                                <div class="left log">
                                    <strong><?=htmlspecialchars($diffs['commit_info']['summary'][0], ENT_QUOTES);?></strong><br />
                                    <span class="small">
                                        <span class="blue"><?=htmlspecialchars($diffs['commit_info']['author'], ENT_QUOTES);?></span>
                                        <span class="grey"> -- <?=((isset($diffs['commit_info']['epoch']))?relativeDate($diffs['commit_info']['epoch']):$diffs['commit_info']['date']);?></span>
                                    </span>
                                </div>
                                <br class="clear" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />

<?

            foreach ( $diffs['diffs'] as $filediff ) {
?>

                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th>
                                <?=$filediff['file'];?>
                                <span class="right blue">
                                    <a href='<?=$CONFIG['base_uri']."/".genLink(array("o" => $filediff['file'], "commit" => $diffs['commit_info']['commit'], "nav" => "files"));?>'>View file @ <?=substr($diffs['commit_info']['commit'], 0, 7);?></a>
                                </span>
                                <br class='clear' />
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class='fileviewer'>
                                    <pre class='diff'>
<?
                #htmlspecialchars(implode("\n", $filediff['diff']));

                foreach ($filediff['diff'] as $line) {

                    if ( preg_match("/^@@/", $line) ) {

                        print "<span class='info'>$line</span>\n";
                    }
                    elseif ( preg_match("/^\+/", $line) ) {

                        print "<span class='add'>$line</span>\n";
                    }
                    elseif ( preg_match("/^-/", $line) ) {

                        print "<span class='remove'>$line</span>\n";
                    }
                    else {

                        print "<span class=''>$line</span>\n";
                    }
                }
?>
                                    </pre>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />
<?
            }
        }
        // Display error
        else {

            $error = "Unable to display commit diff\n";
            include($thispath ."include/error.php");
        }

    }
    catch (Exception $e) {

        $error = $e;
        include($thispath ."include/error.php");
    }

?>
            </div>

