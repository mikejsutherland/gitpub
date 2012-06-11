<? include('header.php'); ?>

    <div class="masthead gradient_gray">
        <div class="content">

            <!-- Main gitpub link -->
            <a href="<?=$CONFIG['base_uri'];?>/">gitpub</a>

<? if ( isset($_SESSION['repo']) && $_SESSION['repo'] !== "" ) { ?>

            <!-- HTTP clone url -->
            <!--
            <input class="repourl" value="<?='http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri'].'/r/'.$_SESSION['repo']?>" spellcheck="false" readonly="readonly" />
            <span class="urlbox gradient_gray">HTTP</span>
            -->

            <!-- Display the Branch -->
            <span class="urlbox gradient_gray">branch: <em><?=$_SESSION['branch'];?></em></span>
<? } ?>
            <br class="clear" />
        </div>
    </div>

    <div class="page">
        <div id="main" class="content">

<? 
    if ( ! isset($_SESSION['repo']) || $_SESSION['repo'] == '' ) { 

        include('views/repos.php');

    } else {

        include('views/tabs.php');

        if ( $_SESSION['nav'] == 'files' ) { 
            include('views/files.php');
        }
        elseif ( $_SESSION['nav'] == 'commits' ) {

            // Commit diff
            if ( ! empty($_SESSION['commit']) ) {

                include('views/commitdiff.php');
            }
            // Commit browser
            else {

                include('views/commits.php');
            }
        }
        elseif ( $_SESSION['nav'] == 'branches' ) { 
            $error = "what...you don't dev on master?";
            include('include/error.php');
        }

    }
?>
 
        </div>
    </div>

<? include('footer.php'); ?>
