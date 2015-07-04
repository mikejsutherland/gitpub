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
            <div class="projbar">
                Project: <a href="<?php echo $CONFIG['base_uri']."/".genLink(array("nav" => null, "commit" => null, "o" => null, "branch" => null));?>"><?php echo $_SESSION['repo'];?></a> <a href="<?php echo $feed;?>"><img src="<?php echo $CONFIG['base_uri'];?>/docs/images/rss_icon.gif" /></a><br />
                <span class="small">
<?php
                try {
            
                    $description = $gp->getDescription();
                }
                catch (Exception $e) {

                    $description = "No description available.";
                }
                print $description;
?>
                </span>
            </div>
            
            <div class="tabbar">
                <ul id="tabs">
                    <li class="tab <?php echo isActiveTab('files');?>"><a href="<?php echo $CONFIG['base_uri']."/".genLink(array("nav" => "files", "commit" => null, "o" => null));?>">Files</a></li>
                    <li class="tab <?php echo isActiveTab('commits');?>"><a href="<?php echo $CONFIG['base_uri']."/".genLink(array("nav" => "commits", "commit" => null, "o" => null, "offset" => null));?>">Commits</a></li>
                    <li class="tab <?php echo isActiveTab('branches');?>"><a href="<?php echo $CONFIG['base_uri']."/".genLink(array("nav" => "branches", "commit" => null, "o" => null));?>">Branches</a></li>
                    <li class="tab <?php echo isActiveTab('tags');?>"><a href="<?php echo $CONFIG['base_uri']."/".genLink(array("nav" => "tags", "commit" => null, "o" => null));?>">Tags</a></li>
                </ul>
                <br class="clear" />
            </div>
