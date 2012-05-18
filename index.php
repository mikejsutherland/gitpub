<? include('header.php'); ?>

    <div class="masthead gradient_gray">
        <div class="content"><a href="<?=$CONFIG['base_uri'];?>/">gitpub</a></div>
    </div>

<?
    # stuff that should no occur here
    # todo: move it!

    $_SESSION['nav'] = isset($_GET['nav']) ? $_GET['nav'] : 'files'; # default view mode

    if ( isset($_GET['repo']) ) { $_SESSION['repo'] = $_GET['repo']; } else { $_SESSION['repo'] = ''; }

    $repos = getRepos($CONFIG['repo_directory']);
    $repo_count = count($repos);

    $filepath = ( isset($_GET['cwd']) ) ? base64_decode($_GET['cwd']) : "";

?>

    <div class="page">
        <div id="main" class="content">

<? if ( ! isset($_SESSION['repo']) || $_SESSION['repo'] == '' ) { ?>

            <div class="box corners">
                <div class="boxhead gradient_aqua">1 person hosting over <?=($repo_count-1);?>+ repositories</div>
                <div class="boxbody">

                Available Git Repositories:<br />
                <?=viewRepos($repos);?>

                </div>
            </div>

<? } else { ?>

            <div class="navbar">
                <a href="<?=$CONFIG['base_uri'];?>/">projects</a>/<a href="<?=$CONFIG['base_uri'];?>/?repo=<?=$_SESSION['repo'];?>"><?=$_SESSION['repo'];?></a>
<? 
    if ( isset($filepath) && $filepath !== "" ) {

        $pathsegments = explode('/', $filepath);
        $pathpieces = count($pathsegments);
#print "<!-- $pathpieces: $filepath ". print_r(explode('/', $filepath))." -->\n";

        if ( $pathpieces > 0 ) {

            print "/";
            $c = 0;

            $base = Array();

            foreach ($pathsegments as $piece) {

                array_push($base, $piece);

                $c++;
                print "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode(implode('/',$base)) ."'>$piece</a>";
                if ( $c < $pathpieces ) { print "/"; }
            }
        }

    }
?>
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

    $gitrepo = new Git($CONFIG['repo_directory'] ."/". $_SESSION['repo']);
    $master_name = $gitrepo->getTip('master');

    $master = $gitrepo->getObject($master_name);
    $tree = $master->getTree();
    $files = $tree->listRecursive();

#    print "<pre>";
#    print_r($files);
#    print "</pre>\n";


    if ( count($files) > 0 ) {
?>
        <table class="filebrowser">
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
    }

    $c = 0;

    foreach ($files as $fl => $val) {

        #if ( $c < 1 ) { sleep(1); $c++; }

        $file = $fl;
        $fullpath = '';

        #print "<!-- $file -->\n";

        if ( isset($filepath) && $filepath != '' ) {

            # Skip any files not from this file path
            if ( ! preg_match("|^$filepath/|", $file) ) { continue; }

            # Strip off the file path
            $file = preg_replace("|^$filepath/|", "", $file);

            $fullpath = "$filepath/";
        }

        #print "<!-- FP: $fullpath file: $file -->\n";

        # Check if its a directory        
        if ( strpos($file, "/")  ) {

            $dir = strstr($file, "/", 1);

            if ( $dir == $prevdir ) { continue; }

            $hist = $master->getHistory($val);
            $lhist = get_object_vars(array_pop($hist));

            print "<tr><td></td><td>";
            print "<a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $dir) ."'>$dir/</a><br />\n";
            print "</td><td></td><td>". $lhist['summary'] ."</td></tr>\n";

            $prevdir = $dir;
        }
        else {

            $hist = $master->getHistory($val);
            $lhist = get_object_vars(array_pop($hist));

            print "<tr><td></td><td>";
            print "<a class='ajaxy' href='?repo=". $_SESSION['repo'] ."&nav=files&cwd=". base64_encode($fullpath . $file) ."'>$file</a><br />\n";
            print "</td><td></td><td>". $lhist['summary'] ."</td></tr>\n";
        }
    }


?>
                    </tbody>
                </table>

            </div>

<? } elseif ( $_SESSION['nav'] == 'commits' ) { ?>

<?
    $gitrepo = new Git($CONFIG['repo_directory'] ."/". $_SESSION['repo']);

    $master_name = $gitrepo->getTip('master');

    $master = $gitrepo->getObject($master_name);
    $hist = $master->getHistory();

    print "<pre>";
    var_dump($hist);
    print "</pre>\n";
?>

<? } elseif ( $_SESSION['nav'] == 'branches' ) { ?>

    hrm....branches you say....



<? } ?>

<? } ?>
        </div>
    </div>

<? include('footer.php'); ?>
