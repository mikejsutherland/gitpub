<? include('header.php'); ?>

    <div class="masthead gradient_gray">
        <div class="content"><a href="<?=$CONFIG['base_uri'];?>/">gitpub</a></div>
    </div>

<?
    # stuff that should no occur here
    # todo: move it!

    $_SESSION['nav'] = isset($_GET['nav']) ? $_GET['nav'] : 'files'; # default view mode

    if ( isset($_GET['repo']) ) { $_SESSION['repo'] = $_GET['repo']; } else { $_SESSION['repo'] = ''; }
    if ( isset($_GET['branch']) ) { $_SESSION['GIT']['branch'] = $_GET['branch']; } else { $_SESSION['GIT']['branch'] = 'master'; }

    $repos = getRepos($_SESSION['CONFIG']['repo_directory']);
    $repo_count = count($repos);

    $_SESSION['filepath'] = ( isset($_GET['cwd']) ) ? base64_decode($_GET['cwd']) : "";

    # If provided a repo load the git object
    #
    if ( isset($_SESSION['repo']) && $_SESSION['repo'] !== "" ) {

        $_SESSION['GIT']['repo'] = new Git($_SESSION['CONFIG']['repo_directory'] ."/". $_SESSION['repo']);
        $_SESSION['GIT']['tip'] = $_SESSION['GIT']['repo']->getTip($_SESSION['GIT']['branch']);
        $_SESSION['GIT']['object'] = $_SESSION['GIT']['repo']->getObject($_SESSION['GIT']['tip']);
    }
?>

    <div class="page">
        <div id="main" class="content">

<? 
    if ( ! isset($_SESSION['repo']) || $_SESSION['repo'] == '' ) { 

        include('include/repo_browser.php');

    } else {
?>

            <div class="navbar">
                <a href="<?=$CONFIG['base_uri'];?>/?repo=<?=$_SESSION['repo'];?>"><?=$_SESSION['repo'];?></a><? if ( isset($_SESSION['filepath']) && $_SESSION['filepath'] !== "" ) { print getFileTreeNav($_SESSION['filepath']); } print "\n"; ?>
            </div>
            <div class="urlbox">
                <label>
                <input class="repourl" value="<?='http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri'].'/r/'.$_SESSION['repo']?>" spellcheck="false" readonly="readonly" />
                <span class="gradient_gray">HTTP</span>
                <span class="gradient_gray">branch: <em><?=$_SESSION['GIT']['branch'];?></em></span>
                <br class="clear" />
            </div>
            <div>
                <ul id="tabs">
                    <li class="tab <?=isActiveTab('files');?>"><a href="?repo=<?=$_SESSION['repo'];?>&nav=files">Files</a></li>
                    <li class="tab <?=isActiveTab('commits');?>"><a href="?repo=<?=$_SESSION['repo'];?>&nav=commits">Commits</a></li>
                    <li class="tab <?=isActiveTab('branches');?>"><a href="?repo=<?=$_SESSION['repo'];?>&nav=branches">Branches</a></li>
                </ul>
            </div>

<? if ( $_SESSION['nav'] == 'files' ) { ?>

            <div id="filebrowser">

<?
        $tree = $_SESSION['GIT']['object']->getTree();
        $files = $tree->listRecursive();

        #$test = get_object_vars($tree);

        #$t = $test['nodes']['lib']['object']->getTip('master');

    #print "<pre>";
    #print_r($t);
    #print "</pre>\n"

?>
                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th style="width: 20px;"></th>
                            <th style="width: 200px;">name</th>
                            <th style="width: 100px;">age</th>
                            <th>message</th>
                        </tr>
                    </thead>
                    <tbody>
<?
    if ( count($files) > 0 ) { 
        viewFileBrowserTable($files);
    } 
?>
                    </tbody>
                </table>

            </div>

<? } elseif ( $_SESSION['nav'] == 'commits' ) { ?>

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

                        <? if ( count($hist) > 0 ) { foreach($hist as $commit) {
                            $com = get_object_vars($commit); 
                            $details = get_object_vars($com['author']);
                            $history = get_object_vars($com['history']);
                            print str_pad("", 20) . "<tr>\n";
                            print str_pad("", 24) . "<td class='small'>". $details['name'] ."</td>\n";
                            print str_pad("", 24) . "<td class='small'>". strftime('%F %T', $details['time']) ."</td>\n";
                            print str_pad("", 24) . "<td>". $com['summary'] ."</td>\n";
                            print str_pad("", 24) . "<td class='small'>\n";
                            print str_pad("", 28) .  $commit_id ."<br />\n";
                            print str_pad("", 28) . "<span class='smaller'>tree: ". sha1_hex($com['tree']) ."</span>\n";
                            print str_pad("", 24) . "</td>\n";
                            print str_pad("", 20) . "</tr>\n";
                            $commit_id = sha1_hex($com['parents'][0]);
                            # todo: add com['tree'] too!
                        } } ?>

                </tbody>
            </table>

<? } elseif ( $_SESSION['nav'] == 'branches' ) { ?>

    what...you don't dev on master?



<? } ?>

<? } ?>
        </div>
    </div>

<? include('footer.php'); ?>
