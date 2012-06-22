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

    include('header.php');
?>

    <div class="masthead gradient_gray">
        <div class="content">

            <!-- Main gitpub link -->
            <a href="<?=$CONFIG['base_uri'];?>/">gitpub</a>

<? 
    if ( isset($_SESSION['repo']) && $_SESSION['repo'] !== "" ) { 

        if ( $CONFIG['enable_clone'] ) {

            $repo_url = 'http://'.$_SERVER['HTTP_HOST'].$CONFIG['base_uri'].'/r/'.$_SESSION['repo'];
?>
            <label>
                <input class="repourl" type="text" value="<?=$repo_url;?>" spellcheck="false" readonly="readonly" />
                <span class="urlbox gradient_gray">HTTP</span>
            </label>
<?
        }
?>
            <!-- Display the Branch -->
            <span class="urlbox gradient_gray"><span class="black">branch:</span> <?=preg_replace("/^origin\//", "", $_SESSION['branch']);?></span>
<?
    } 
?>
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

            // Commit browser
            include('views/branches.php');
        }
        elseif ( $_SESSION['nav'] == 'tags' ) {

            // Tag browser
            include('views/tags.php');
        }

    }
?>
 
        </div>
    </div>

<? include('footer.php'); ?>
