<?  if ( count($gp->repos) > 0 ) { ?><?="\n";?>

            <div class="box corners">
                <div class="boxhead gradient_aqua">1 person hosting over <?=(count($gp->repos)-1);?>+ repositories</div>
                <div class="boxbody">
                    Available Git Repositories:<br />
                    <ul>
                        <? foreach ($gp->repos as $repo) { ?><?="\n";?>
                        <li><a href='<?=$CONFIG['base_uri'] ."/". genLink(array("repo" => $repo['name']));?>'><?=$repo['name'];?></a></li>
                        <? } ?><?="\n";?>
                    </ul>
                </div>
            </div>
<?
    } else { 

        if ( empty($gp->projectsdir) ) {
            $error = "The projects directory is not defined\n";
        }
        else {
            $error = "No git repositories found at: ". $gp->projectsdir;
        }

        include($thispath."include/error.php");
    }
?>
