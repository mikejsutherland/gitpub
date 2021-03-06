<?php

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
    require_once($thispath . 'lib/gitpub/gitpub.extras.php');

    $gp = new GitPub($CONFIG);

    // Set the repo
    $_SESSION['repo'] = isset($_GET['repo']) ? $_GET['repo'] : null;
    // Set the view
    $_SESSION['nav'] = isset($_GET['nav']) ? $_GET['nav'] : 'files'; # default view mode
    // Set the object
    $_SESSION['o'] = (isset($_GET['o']) && base64_decode($_GET['o'], true) && strlen($_GET['o']) % 4 == 0) ? base64_decode($_GET['o']) : ""; 
    // Set the commit id
    $_SESSION['commit'] = isset($_GET['commit']) ? $_GET['commit'] : null;
    // Set the branch
    $_SESSION['branch'] = isset($_GET['branch']) ? $_GET['branch'] : null;
    // Set the base feed path
    $feed = $CONFIG['base_uri'] ."/feed.php";

    // Set the repo
    if ( ! empty($_SESSION['repo']) ) {

        try {

            $gp->setRepo($_SESSION['repo']);
        }
        catch (Exception $e) {

            $_SESSION['repo'] = "";
            $error = "Unknown repository.\n";
        }

        // No branch set, look for a master branch
        if ( empty($_SESSION['branch']) ) {

            try {

                $branches = $gp->getBranches();

                foreach ($branches as $b) {

                    if ( $b['branch'] == 'master' || $b['branch'] == 'origin/master' ) {

                        $_SESSION['branch'] = 'master';
                        break;
                    }
                }

            }
            catch (Exception $e) {

                $_SESSION['branch'] = null;
            }
        }

        // Set the branch
        if ( ! empty($_SESSION['branch']) ) {

            try {

                $gp->setBranch($_SESSION['branch']);
                $feed .= "?repo=".$_SESSION['repo']."&branch=".$_SESSION['branch'];
            }
            catch (Exception $e) {

                $_SESSION['branch'] = null;
                $error = "The requested branch does not exist.\n";

                // Force to branches tab
                $_SESSION['nav'] = 'branches';
                $feed = $CONFIG['base_uri'] ."/feed.php";
            }
        }
    }

    header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo (isset($_SESSION['repo'])) ? "gitpub / ".$_SESSION['repo'] : "gitpub";?></title>
    <link rel="shortcut icon" href="data:,">
    <link href="<?php echo $feed;?>" rel="alternate" type="application/rss+xml" title="gitpub - available repositories" />

    <!-- google-code-prettify -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $CONFIG['base_uri'];?>/docs/include/google-code-prettify/prettify.css" />

    <!-- jquery javascript frameworks -->
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/include/jquery/jquery-1.7.1.min.js"></script>

    <!-- jquery-selectBox -->
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/include/jquery-selectBox/jquery.selectBox.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $CONFIG['base_uri'];?>/docs/include/jquery-selectBox/jquery.selectBox.css" />

    <!-- google-code-prettify -->
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/include/google-code-prettify/prettify.js"></script>

    <!-- history.js -->
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/include/history.js/jquery.history.js"></script>      
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/include/history.js/ajaxify-html5.js"></script>

    <!-- gitpub stylesheet -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo $CONFIG['base_uri'];?>/docs/css/gitpub.css" />
    <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="<?php echo $CONFIG['base_uri'];?>/docs/css/ie.gitpub.css" /><![endif]-->

    <!-- gitpub javascript library -->
    <script type="text/javascript" src="<?php echo $CONFIG['base_uri'];?>/docs/javascript/gitpub.js"></script>

</head>
<body>
