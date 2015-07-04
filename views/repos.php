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

    if ( count($gp->repos) > 0 ) {
?>
            <div class="box corners">
                <div class="boxhead gradient_aqua">
                    1 person hosting
<?php
        if ( count($gp->repos) == 1 ) {
            print " 1 respository\n";
        }
        else {
            print "over ". (count($gp->repos)-1) ."+ repositories\n";
        }
?>
                </div>
                <div class="boxbody">
                    Available Git Repositories:<br />
                    <ul>
                        <?php foreach ($gp->repos as $repo) { ?><?php echo "\n";?>
                        <li><a href='<?php echo $CONFIG['base_uri'] ."/". genLink(array("repo" => $repo['name']));?>'><?php echo $repo['name'];?></a></li>
                        <?php } ?><?php echo "\n";?>
                    </ul>
                </div>
            </div>
<?php
    } else { 

        if ( empty($gp->projectsdir) ) {
            $error = "The projects directory is not defined\n";
        }
        else {
            $error = "No git repositories found at: ". $gp->projectsdir;
        }

        include($thispath."views/error.php");
    }
?>
