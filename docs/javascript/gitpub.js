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

$(document).ready(function() {

    // Initial the pretty print methods
    prettyPrint();

    // Select the text inside input box when clicked
    $("input[type=text]").click(function(){
        // Select entire field contents
        $(this).select();
        return false;
    });

});

// history.js customizations
// -- called after content is fetched and displayed
//
fileBrowserLoaded = function() {

    // enable syntax highlighting
    prettyPrint();

    return;
}
