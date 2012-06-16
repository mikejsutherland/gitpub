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
