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
                <div id="tagbrowser">

<?

    try {

        $tags = $gp->getTags();

?>
                    <table class="tag browser">
                        <thead>
                            <tr class="gradient_gray">
                                <th>Tags</th>
                            </tr>
                        </thead>
                        <tbody>
<?
        $c = true;

        foreach ($tags as $ver => $msg) {        
?>
                            <tr class="<?=(($c = !$c)?'hl':'');?>">
                                <td>
                                    <div class="">
                                        <strong><a href=''><?=$ver?></a></strong>
                                        <? if ( ! empty($msg) ) { ?>
                                        <span class="small black"> - <?=$msg;?></span>
                                        <? } ?>
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
    catch (Exception $e) {

        $error = $e;
        include($thispath ."include/error.php");
    }
?>

            </div>
