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
                <input class="masthead_repo_url" type="text" value="<?=$repo_url;?>" spellcheck="false" readonly="readonly" />
                <span class="masthead_label masthead_button black">Clone </span>
            </label>
<?
        }

        try {

            $branches = $gp->getBranches();
#print_r($branches);
?>
            <select name="branch_dropdown" class="branch_dropdown right">
<?
            if ( ! isset($_SESSION['branch']) || empty($_SESSION['branch']) ) {
                    
                print "<option class=\"selectBox-opt\" value=\"\" selected=\"selected\"></option>\n";
            }

            foreach ($branches as $b) {

                $uri = $CONFIG['base_uri'] .'/'. genLink(array("branch" => $b['branch']));

                if ( isset($_SESSION['branch']) && ( $_SESSION['branch'] == $b['name'] || $_SESSION['branch'] == 'origin/'.$b['name'] )) {

                    print "<option class=\"selectBox-opt\" value=\"".$uri."\" selected=\"selected\">".$b['name']."</option>\n";
                }
                else {

                    print "<option class=\"selectBox-opt\" value=\"".$uri."\">".$b['name']."</option>\n";
                }
            }
?>
            </select>
            <span class="masthead_label black">Branch: </span>
<?
        }
        catch (Exception $e) {

                
        }
    } 
?>
            <br class="clear" />
        </div>
    </div>

    <div class="page">
        <div id="main" class="content">

<? 
    if ( ! isset($_SESSION['repo']) || $_SESSION['repo'] == '' ) { 

        session_destroy();
        include('views/repos.php');

    } else {

        include('views/tabs.php');

        if ( ! empty($error) ) {

            // Throw an error
            include('views/error.php');
            print "<br />\n";
        }

        if ( $_SESSION['nav'] == 'files' ) { 

            // Display the file navigator
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
        else {

            // Throw an error
            $error = "Unknown view.";
            include('views/error.php');
        }
    }

?>
 
        </div>
    </div>

<? include('footer.php'); ?>
