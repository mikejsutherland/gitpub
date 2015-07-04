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

    $thispath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

    require_once($thispath . 'config/gitpub.config.php');
    require_once($thispath . 'lib/gitpub/gitpub.class.php');

    session_start();

    if ( isset($_SESSION['repo']) && isset($_GET['tag']) ) {

        $tag = $_GET['tag'];
        $type = (isset($_GET['type'])) ? $_GET['type'] : "tar";

        $fn = $_SESSION['repo'];
        $fn .= ($tag[0] == "v") ? "-". substr($tag, 1) : ($tag !== "HEAD") ? "-". $tag : "";

        // Instantiate repository
        $gp = new GitPub($CONFIG);
        $gp->setRepo($_SESSION['repo']);

        try {

            // Fetch the archive
            $gp->getArchive($tag, $type);

            // Set the headers accordingly
            header("Content-Type: application/octet-stream");
            header("Content-disposition: attachment; filename=\"$fn.$type\"");
            header("Content-Length: ". $gp->cmd['size']);

            // Send the archive
            echo $gp->cmd['results'];
        }
        catch (Exception $e) {

            $error = "The requested tag could not be retrieved.";
            include($thispath .'views/error.php');
        }
    }
    else {

        header("Location: ". $CONFIG['base_uri'] .'/');
    }

?>
