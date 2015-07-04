<?php 
    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . 'config/gitpub.config.php');
    require_once($thispath . 'lib/gitpub/gitpub.class.php');
    require_once($thispath . 'lib/gitpub/gitpub.extras.php');

    print "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
    print "<rss version=\"2.0\">\n"; 
    print "  <channel>\n";

    $gp = new GitPub($CONFIG);

    $repo = isset($_GET['repo']) ? $_GET['repo'] : null;
    $branch = isset($_GET['branch']) ? $_GET['branch'] : "master";

    if ( ! empty($repo) && ! empty($branch) ) {

        try {

            $gp->setRepo($repo);
            $gp->setBranch($branch);

            $link = 'http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri']."/".genLink(array(
                "repo" => $repo,
                "branch" => $branch,
                "nav" => "commits",
                "commit" => null)
            );

            print "    <title>Commit history for ".$repo." on branch ".$branch."</title>\n";
            print "    <link>".$link."</link>\n";
            print "    <description></description>\n";
            print "    <language>en-us</language>\n";
            print "    <docs>".$link."feed.php</docs>\n";
            print "    <generator>gitpub - repository viewer</generator>\n";

            $commits = $gp->getCommitLog(0, $CONFIG['commits_per_page']);

            foreach($commits as $commit) {

                print "    <item>\n";
                print "      <title>".htmlspecialchars($commit['summary'][0], ENT_QUOTES)."</title>\n";
                print "      <description>Commit ".substr($commit['commit'], 0, 10).
                    " authored by ".htmlspecialchars($commit['author'], ENT_QUOTES).
                    " at ".$commit['date']."</description>\n";
                print "      <link>".'http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri']."/".genLink(array(
                    "repo" => $repo,
                    "branch" => $branch,
                    "nav" => "commits",
                    "commit" => $commit['commit']))."</link>\n";
                print "      <pubDate>".date("r", $commit['epoch'])."</pubDate>\n";
                print "    </item>\n";
            }
        }
        catch (Exception $e) {

            $error = "Unknown repository.\n";
        }
    }
    // Display available repositories
    else {

        $link = 'http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri'].'/';

        print "    <title>GitPub Repositories</title>\n";
        print "    <link>$link</link>\n";
        print "    <description>Available repositories</description>\n";
        print "    <language>en-us</language>\n";
        print "    <docs>".$link."rss</docs>\n";
        print "    <generator>gitpub - repository viewer</generator>\n";

        foreach ($gp->repos as $repo) {

            print "    <item>\n";
            print "      <title>".htmlspecialchars($repo['name'], ENT_QUOTES)."</title>\n";
            print "      <description>".htmlspecialchars($repo['desc'], ENT_QUOTES)."</description>\n";
            print "      <link>".'http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri']."/".genLink(array("repo" => $repo['name']))."</link>\n";
            print "    </item>\n";
        }
    }
?>
  </channel>
</rss>
