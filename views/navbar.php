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
                <div class="navbar">
<?

    $navlinks = "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/". genLink(array("o" => null)) ."'>". $_SESSION['repo'] ."</a>";

    if ( ! empty($_SESSION['o']) ) {

        $pathsegments = explode('/', preg_replace("/\/$/", "", $_SESSION['o']));
        $pathpieces = count($pathsegments);

        if ( $pathpieces > 0 ) {

            $navlinks .= "/";
            $c = 0;

            $base = Array();

            foreach ($pathsegments as $piece) {

                array_push($base, $piece);
                $c++;

                if ( $c < $pathpieces ) {

                    $navlinks .= "<a class='ajaxy' href='". $CONFIG['base_uri'] ."/".
                        genLink(array("o" => implode('/',$base) ."/"))
                        ."'>$piece</a>";

                    $navlinks .= "/";
                }
                else {

                    $navlinks .= $piece;
                }
            }
        }
    }

    if ( ! empty($_SESSION['commit']) ) {

        $navlinks .= " @ <span class=''><a href='". $CONFIG['base_uri'] ."/". genLink(array("nav" => "commits", "o" => null)) ."'>". substr($_SESSION['commit'], 0, 7) ."</a></span>";
    }

    print "$navlinks\n";
?>

                </div>
