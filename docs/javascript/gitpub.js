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

    $("select").selectBox()
        .change( function() {
            window.location = $(this).val();
        }
    );

    // Init -- call to other functions if necessary
    tabLoaded();

    // Select the text inside input box when clicked
    $("input[type=text]").click(function(){
        // Select entire field contents
        $(this).select();
        return false;
    });

});

// The below function fires other functions when specific
// sections are displayed.  This is called by history.js
//
tabLoaded = function() {

    // Specific functions for the 'Files' tab
    if ( $('#filebrowser').length ) {

        // Initialize syntax highlighter
        prettyPrint();
    }

    return;
}
