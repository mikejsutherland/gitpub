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
?>
                <div id="tagsbrowser">

<?php

    try {

        $tags = $gp->getTags();

        $c = true;

        if ( count($tags) ) {
?>
                    <table class="tags browser">
                        <thead>
                            <tr class="gradient_gray">
                                <th>Tags</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
            foreach ($tags as $ver => $msg) {        
?>
                            <tr class="<?php echo (($c = !$c)?'hl':'');?>">
                                <td>
                                    <div class="left">
                                        <strong><a href='<?php echo $CONFIG['base_uri']."/download.php?tag=$ver&amp;type=zip";?>'><?php echo $ver?></a></strong>
                                        <?php if ( ! empty($msg) ) { ?>
                                        <span class="grey"> - <?php echo $msg;?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="right small tright grey">
                                        Download<br />
                                        <a class="dl" href='<?php echo $CONFIG['base_uri']."/download.php?tag=$ver";?>'>TAR</a>
                                        <a class="dl" href='<?php echo $CONFIG['base_uri']."/download.php?tag=$ver&amp;type=zip";?>'>ZIP</a>
                                    </div>
                                    <br class="clear" />
                                </td>
                            </tr>
<?php
            }
?>
                        </tbody>
                    </table>
<?php
        }
        else {

            $error = "There are no tags.\n";
            include($thispath ."views/error.php");
        }
    }
    catch (Exception $e) {

        $error = "Unable to retrieve tags.";
        include($thispath ."views/error.php");
    }
?>

            </div>
