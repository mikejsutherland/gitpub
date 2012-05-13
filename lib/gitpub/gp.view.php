<?

    #print $CONFIG['repo_directory'] ."<<<\n";

    $repo = new Git($CONFIG['repo_directory'] ."/gitpub/");

    $master_name = $repo->getTip('master');
    $master = $repo->getObject($master_name);

?>
