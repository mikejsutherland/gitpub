<?

   /*
    * Copyright (c) 2012 codesmak.com
    *
    * This file is part of gitpub.
    *
    * gitpub is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
    *
    * gitpub is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with gitpub.  If not, see <http://www.gnu.org/licenses/>.
    *
    */

    session_start(); 

    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . 'config/gitpub.config.php');
    require_once($thispath . 'lib/gitpub/gitpub.class.php');

    $gp = new GitPub($CONFIG);

    require_once('lib/gitpub/gp.req.php');
    require_once('lib/gitpub/gp.repo.php');
    require_once('lib/gitpub/gp.view.php');

    # XXX - test string   ?repo=gitpub&nav=files&cwd=Lmh0YWNjZXNz
    #$_GET['repo'] = 'gitpub'; $_GET['nav'] = 'files'; $_GET['o'] = 'ZG9jcy9pbmNsdWRlL2hpc3RvcnkuanMvYWpheGlmeS1odG1sNS5qcw=='; 

    // Set the repo
    $_SESSION['repo'] = isset($_GET['repo']) ? $_GET['repo'] : null;
    // Set the view
    $_SESSION['nav'] = isset($_GET['nav']) ? $_GET['nav'] : 'files'; # default view mode
    // Set the object
    $_SESSION['obj'] = isset($_GET['o']) ? base64_decode($_GET['o']) : ""; 


    #if ( isset($_GET['repo']) ) { $_SESSION['repo'] = $_GET['repo']; } else { $_SESSION['repo'] = ''; }
    if ( isset($_GET['branch']) && ! empty($_GET['branch']) ) { 
        $_SESSION['GIT']['branch'] = $_GET['branch']; 
    }
    elseif ( empty($_SESSION['GIT']['branch']) ) { 
        $_SESSION['GIT']['branch'] = 'master';
    }

    $repos = getRepos($CONFIG['repo_directory']);
    $repo_count = count($repos);

    #$_SESSION['filepath'] = ( isset($_GET['o']) ) ? base64_decode($_GET['o']) : "";

    # If provided a repo load the git object
    #
    if ( isset($_SESSION['repo']) && ! empty($_SESSION['repo']) ) {

        // Set the repo
        $gp->setRepo($_SESSION['repo']);

        #try {

        #    $_SESSION['GIT']['repo'] = new Git($_SESSION['CONFIG']['repo_directory'] ."/". $_SESSION['repo']);
            #$_SESSION['GIT']['tip'] = $_SESSION['GIT']['repo']->getTip($_SESSION['GIT']['branch']);
        #}
        #catch (Exception $e) {

        #    $error = $e;
        #    include('include/error.php');
        #}

        #$_SESSION['GIT']['object'] = $_SESSION['GIT']['repo']->getObject($_SESSION['GIT']['tip']);
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>GitPub - Were The Code Flows</title>
    <base href="<?=$CONFIG['base_uri'];?>" target="_self" />

    <!-- jquery-ui stylesheet -->
    <!-- <link rel="stylesheet" type="text/css" media="all" href="<?=$CONFIG['base_uri'];?>/docs/include/jqueryui/css/smoothness/jquery-ui-1.8.19.custom.css" /> -->

    <!-- google-code-prettify -->
    <link rel="stylesheet" type="text/css" media="all" href="<?=$CONFIG['base_uri'];?>/docs/include/google-code-prettify/prettify.css" />

    <!-- jquery & jquery-ui javascript frameworks -->
    <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/include/jquery/jquery-1.7.1.min.js"></script>
    <!-- <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/include/jqueryui/js/jquery-ui-1.8.19.custom.min.js"></script> -->

    <!-- google-code-prettify -->
    <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/include/google-code-prettify/prettify.js"></script>

    <!-- history.js -->
    <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/include/history.js/jquery.history.js"></script>      
    <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/include/history.js/ajaxify-html5.js"></script>

    <!-- gitpub stylesheet -->
    <link rel="stylesheet" type="text/css" media="all" href="<?=$CONFIG['base_uri'];?>/docs/css/gitpub.css" />
    <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="<?=$CONFIG['base_uri'];?>/docs/css/ie.gitpub.css" /><![endif]-->


    <!-- gitpub javascript library -->
    <script rel="javascript" type="text/javascript" src="<?=$CONFIG['base_uri'];?>/docs/javascript/gitpub.js"></script>

</head>
<body>
