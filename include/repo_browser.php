<? 

    if ( count($gp->repos) > 0 ) {

?>
            <div class="box corners">
                <div class="boxhead gradient_aqua">1 person hosting over <?=(count($gp->repos)-1);?>+ repositories</div>
                <div class="boxbody">
                    Available Git Repositories:<br /><?="\n"; viewRepos($gp->repos);?>
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
