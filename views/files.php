            <div id="filebrowser">

<?
    $files = $gp->getTree();

    if ( count($files) > 0 ) {

?>
                <table class="file browser">
                    <thead>
                        <tr class="gradient_gray">
                            <th style="width: 20px;"></th>
                            <th style="width: 200px;">name</th>
                            <th style="width: 100px;">age</th>
                            <th>message</th>
                        </tr>
                    </thead>
                    <tbody>
<?=viewFileBrowserTable($files);?>
                    </tbody>
                </table>
<?
    }
    else {

        $error = "This repository has no files yet.\n";
        include($thispath. 'include/error.php');

    }
?>
            </div>

