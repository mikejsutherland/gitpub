<?
    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    include_once($thispath . 'config/gitpub.config.php');
    require_once($thispath . 'lib/gitpub/gitpub.class.php');

    $gp = new GitPub($CONFIG);

    $tmp = $gp->repos;
    #$gp->setRepo('electronicstracker.com');
    #$gp->setRepo('gitpub');
    #$gp->setCommitID();
    #$gp->run('log');

    #print $gp->abbr_commit ."\n";
    #print $gp->commit ."\n";
    #$tmp = $gp->cmd['results'];

    var_dump($tmp);
?>
