$(document).ready(function() {

    // Initial the pretty print methods
    prettyPrint();

});

// history.js customizations
// -- called after content is fetched and displayed
//
fileBrowserLoaded = function() {

    // enable syntax highlighting
    prettyPrint();

    return;
}
