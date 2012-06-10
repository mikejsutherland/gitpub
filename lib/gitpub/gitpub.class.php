<?

    class gitpub {

        public $opts;

        public function __construct($opts = array()) {

            $default_opts = array(
                'git_path' => '/usr/local/git/bin',
                'projects_dir' => '',
                'branch' => 'master',
                'repo' => '',
            );
            $this->opts = array_merge($default_opts, $opts);

            // Set projects dir 
            $this->setProjectsDir($this->opts['projects_dir']);
            $this->setRepos($this->opts['projects_dir']);
            $this->setBranch($this->opts['branch']);
        }

        public function __get($var){

            return isset( $this->{$var} ) ? $this->{$var} : null;
        }

        public function setRepo($repo) {

            $this->repo = $repo;
            $this->setCommitId();
            $this->tip = $this->commit;
        }

        public function setBranch($branch) {

            $this->branch = $branch;
        }

        public function setProjectsDir($dir) {

            $this->projectsdir = $dir;
        }

        public function setRepos($projects_dir) { # = $this->repodir) {

            $repos = array();
            $dirs = scandir($projects_dir, NULL);

            foreach ($dirs as $dir) {

                $this_path = $projects_dir . "/$dir";

                # Skip current, parent and anything that doesn't contain a .git subdir
                if ( $dir == '.' || $dir == '..' || ! is_dir("$this_path") || ! $this->isGitRepo($this_path) ) { continue; }

                # Stash the repo info
                $repo['name'] = $dir;
                $repo['path'] = $this_path;

                array_push($repos, $repo);

            }

            $this->repos = $repos;
        }

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

        public function getFile($file, $commit = null) {

            if ( empty($commit) ) {
                $commit = $this->tip;
            }

            $this->run("show $commit:$file");

            if ( preg_match("/\.(jpg|jpeg|png|gif|ico|bmp)$/", $file) ) {

                return "<img src='data:image/png;base64,". base64_encode($this->cmd['results']) ."' />\n";
            }
            else {

                #$str = implode("\n", $this->cmd['results']);
                $str = $this->cmd['results'];

                # verify we have ascii data
                if ( mb_check_encoding($str, 'ASCII') ) {

                    # plain text (non code)
                    if ( preg_match("/\.(txt)$/", $file) ) {
                        return "<pre class='prettyprint linenums nocode'>". htmlspecialchars($str) ."</pre>\n";
                    }
                    else {
                        return "<pre class='prettyprint linenums'>". htmlspecialchars($str) ."</pre>\n";
                    }
                }
                else {
                    return "<div class='message'>This file cannot be viewed online.\n</div>\n";
                }
            }
        }

        public function getTree($file = null, $commit = null) {

            if ( empty($commit) ) {
                $commit = $this->tip;
            }

            $this->run("show $commit:$file");

            $results = explode("\n", $this->cmd['results']);
            $files = array();

            foreach ($results as $line) {

                if ( preg_match("/^tree.+/", $line) ) {
                    continue;
                }
                elseif ( empty($line) || $line == "" ) {
                    continue;
                }

                array_push($files, $line);
            }

            return $files;
        }

        public function getCommitLog($start = 0, $max = null) {

            # --max-count=<number> Limit the number of commits to output.
            # --skip=<number> Skip number commits before starting to show the commit output.

            $args = array("--skip=$start");

            if ( isset($max) ) { 
                array_push($args, "--max-count=$max");
            }

            $this->run('log', $args); 


            $results = explode("\n", $this->cmd['results']);
            $commits = array();
            $commit_info = array();

            foreach ($results as $line) {

                if ( preg_match("/^commit\s+(.+)$/i", $line, $matches) ) {

                    if ( isset($commit_info) && count($commit_info) > 0 ) {

                        array_push($commits, $commit_info);
                        unset($commit_info);
                    }
                        
                    $commit_info = array();
                    $commit_info['commit'] = $matches[1];
                }
                elseif ( preg_match("/^Author:\s+(.+)$/i", $line, $matches) ) {

                    $commit_info['author'] = $matches[1];
                }
                elseif ( preg_match("/^Date:\s+(.+)$/i", $line, $matches) ) {

                    $commit_info['date'] = $matches[1];
                }
                elseif ( empty($line) || $line == "" ) { 

                    continue;
                }
                else {

                    if ( ! isset($commit_info['summary']) ) {

                        $commit_info['summary'] = array();
                    }

                    array_push($commit_info['summary'], ltrim($line));
                }
            }

            if ( count($commit_info) > 0 ) {

                array_push($commits, $commit_info);
            }

            return $commits;
        }

        // internal functions
        //
        public function isGitRepo($dir) {

            return is_dir($dir .'/refs');
        }

        protected function _chopPath($path) {

            return preg_replace("/\/$/", "", $path);
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
            //print "DEBUG : ". $res['cmd'] ."\n";

            // Enable output buffering
            ob_start();
            // Execute the command
            passthru($res['cmd'], $res['rc']);
            // Store the results from the output buffer
            $res['results'] = ob_get_contents();
            // Close the output buffer
            ob_end_clean();

            if ( $res['rc'] !== 0 ) {

                throw new Exception("Error running command: '". $res['cmd'] ."', rc: ". $res['rc'] ."\n");
            }

            // Store the result
            $this->cmd = $res;
        }

    }

?>
