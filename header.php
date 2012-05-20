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

    require_once('lib/gitpub/gp.req.php');
    require_once('lib/gitpub/gp.repo.php');
    require_once('lib/gitpub/gp.view.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>GitPub - Were The Code Flows</title>

    <!-- jquery-ui stylesheet -->
    <!-- <link rel="stylesheet" type="text/css" media="all" href="docs/include/jqueryui/css/smoothness/jquery-ui-1.8.19.custom.css" /> -->

    <!-- jquery & jquery-ui javascript frameworks -->
    <script rel="javascript" type="text/javascript" src="docs/include/jquery/jquery-1.7.1.min.js"></script>
    <!-- <script rel="javascript" type="text/javascript" src="docs/include/jqueryui/js/jquery-ui-1.8.19.custom.min.js"></script> -->

    <!-- history.js -->
    <script rel="javascript" type="text/javascript" src="docs/include/history.js/jquery.history.js"></script>      
    <script rel="javascript" type="text/javascript" src="docs/include/history.js/jquery.scrollto.min.js"></script>
    <script rel="javascript" type="text/javascript" src="docs/include/history.js/ajaxify-html5.js"></script>

    <!-- gitpub stylesheet -->
    <link rel="stylesheet" type="text/css" media="all" href="docs/css/gitpub.css" />

    <!-- gitpub javascript library -->
    <script rel="javascript" type="text/javascript" src="docs/javascript/gitpub.js"></script>

</head>
<body>
