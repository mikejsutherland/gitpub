<?

    function relativeDate($date) {

        // Display the relative time difference between then and now
        // Source: http://stackoverflow.com/a/10747954

        $now = time();
        $diff = $now - $date;

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s seconds ago' : 'a second ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s minutes ago' : 'one minute ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 24){
            return sprintf($diff > 1 ? '%s hours ago' : 'an hour ago', $diff);
        }

        $diff = floor($diff/24);

        if ($diff < 7){
            return sprintf($diff > 1 ? '%s days ago' : 'yesterday', $diff);
        }

        if ($diff < 30) {
            $diff = floor($diff / 7);

            return sprintf($diff > 1 ? '%s weeks ago' : 'one week ago', $diff);
        }

        $diff = floor($diff/30);

        if ($diff < 12){
            return sprintf($diff > 1 ? '%s months ago' : 'last month', $diff);
        }

        $diff = date('Y', $now) - date('Y', $date);

        return sprintf($diff > 1 ? '%s years ago' : 'last year', $diff);
    }

    function isActiveTab($val) {

        if ( isset($_SESSION['nav']) && $_SESSION['nav'] == $val ) {
            print "active";
        }

        return;
    }

?>
