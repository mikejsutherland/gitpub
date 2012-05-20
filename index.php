<? include('header.php'); ?>

    <div class="masthead gradient_gray">
        <div class="content">
            <a href="<?=$CONFIG['base_uri'];?>/">gitpub</a>
<? if ( isset($_SESSION['repo']) && $_SESSION['repo'] !== "" ) { ?>
            <input class="repourl" value="<?='http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri'].'/r/'.$_SESSION['repo']?>" spellcheck="false" readonly="readonly" />
            <span class="urlbox gradient_gray">HTTP</span>
            <span class="urlbox gradient_gray">branch: <em><?=$_SESSION['GIT']['branch'];?></em></span>
            <br class="clear" />
<? } ?>
        </div>
    </div>

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

<? } elseif ( $_SESSION['nav'] == 'commits' ) { include('include/commit_browser.php'); ?>

<? } elseif ( $_SESSION['nav'] == 'branches' ) { ?> what...you don't dev on master?  <? } ?>

<? } ?>
        </div>
    </div>

<? include('footer.php'); ?>
