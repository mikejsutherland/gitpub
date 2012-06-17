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
    <div class="footer">
        <div class="content">
            <div class="section right">
                Thanks ;)
                <ul>
                    <li><a href="http://git-scm.com/" target="_blank">Git - distributed version control</a></li>
                    <li><a href="https://github.com/" target="_blank">Github - social coding</a></li>
                    <li><a href="http://php.net/" target="_blank">PHP</a></li>
                </ul>
            </div>
            <div class="section right">
                Documentation
                <ul>
                    <li><a href="http://git-scm.com/documentation" target="_blank">Git - Documentation</a></li>
                    <li><a href="http://rogerdudler.github.com/git-guide/" target="_blank">Git - The simple guide ~ rogerdudler</a></li>
                    <li><a href="http://nvie.com/posts/a-successful-git-branching-model/" target="_blank">A successful Git branching model</a></li>
                </ul>
            </div>
            <div class="section right">
                About
                <ul>
                    <li><a href="http://codesmak.com" target="_blank">codesmak.com</a></li>
                    <li><a href="<?=$CONFIG['base_uri']."/".genLink(array("nav" => "files", "commit" => null, "o" => "README", "branch" => null));?>">Gitpub</a></li>
                </ul>
            </div>
            <br class="clear" />
        </div>
    </div>
    <div class="subfooter">
        <div class="content">
            <div class="">
                <div class="left stamp">&lt;/futile&gt;</div>
                <div class="left about">
                    gitpub -- copyright &copy; 2012 - <a href="http://codesmak.com" target="_blank">codesmak.com</a><br />
                    an exercise in futility...
                </div>
                <br class="clear" />
            </div>
        </div>
    </div>
</body>
</html>
