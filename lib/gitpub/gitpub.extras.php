<?

    function isActiveTab($val) {

        if ( isset($_SESSION['nav']) && $_SESSION['nav'] == $val ) {
            print "active";
        }

        return;
    }

?>
