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
?>
                <div id="tagsbrowser">

<?

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
<?
            foreach ($tags as $ver => $msg) {        
?>
                            <tr class="<?=(($c = !$c)?'hl':'');?>">
                                <td>
                                    <div class="left">
                                        <strong><a href='<?=$CONFIG['base_uri']."/download.php?tag=$ver";?>'><?=$ver?></a></strong>
                                        <? if ( ! empty($msg) ) { ?>
                                        <span class="grey"> - <?=$msg;?></span>
                                        <? } ?>
                                    </div>
                                    <div class="right small blue">
                                        <a href='<?=$CONFIG['base_uri']."/download.php?tag=$ver";?>'>Download tarball</a><br />
                                        <a href='<?=$CONFIG['base_uri']."/download.php?tag=$ver&amp;type=zip";?>'>Download zip</a>
                                    </div>
                                    <br class="clear" />
                                </td>
                            </tr>
<?
            }
?>
                        </tbody>
                    </table>
<?
        }
        else {

            $error = "There are no tags yet.\n";
            include($thispath ."include/error.php");
        }
    }
    catch (Exception $e) {

        $error = $e;
        include($thispath ."include/error.php");
    }
?>

            </div>
