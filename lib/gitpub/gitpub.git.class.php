<?

    class git extends gitpub {

        public function setCommitId($id = null) {

            if ( empty($id) ) {

                # --no-notes
                $this->run('log', array("--skip=0","--max-count=1", "--no-notes"));
                preg_match("/commit ([a-zA-Z0-9]+)/", $this->cmd['results'], $commit_sha1);

                $this->commit = ( isset($commit_sha1[1]) ) ? $commit_sha1[1] : null;
            }
            else {

                $this->commit = $id;
            }

            // Set the abbreviated commit id as well
            $this->setAbbrCommitId($this->commit);
        }

        public function setAbbrCommitId($id) {

            $this->abbr_commit = substr($id, 0, 7);
        }

        public function getCommitLog($start = 0, $max = 10) {

            # --max-count=<number> Limit the number of commits to output.
            # --skip=<number> Skip number commits before starting to show the commit output.
            $this->run('log', array("--skip=$start","--max-count=$max"));
        }

        // execution
        public function run($gitcmd, $args = array(), $switches = array()) {

            if ( empty($gitcmd) ) { return null; }

            // Reset the command cache
            $res = array();

            // Define the command to be executed
            $res['cmd'] = $this->_chopPath($this->opts['git_path']) . "/git --no-pager --git-dir=" .
                $this->projectsdir . "/" . $this->repo . implode(" ", $switches) . 
                " $gitcmd ". implode(" ", $args)
            ;            

            // Enable output buffering
            ob_start();
            // Execute the command
            passthru($res['cmd'], $res['rc']);
            // Store the results from the output buffer
            $res['results'] = ob_get_contents();
            // Close the output buffer
            ob_end_clean();

            $this->cmd = $res;
        }
    }

?>
