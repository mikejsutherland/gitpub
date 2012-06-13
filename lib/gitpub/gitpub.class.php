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

            // Set the name of the repo
            $this->repo = $repo;
            // Set the path of the repo
            $this->repodir = $this->opts['projects_dir'] ."/". $repo;
            if ( $this->_isLocal() ) {
                $this->repodir .= "/.git";
            }
            // Set the top commit id
            $this->setCommitId();
            // Set the tip (top commit id)
            $this->tip = $this->commit;
        }

        public function setBranch($branch) {

            $this->branch = $branch;
        }

        public function setProjectsDir($dir) {

            $this->projectsdir = $dir;
        }

        public function setRepos($projects_dir) { 

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

        public function getDescription($dir = null) {

            if ( empty($dir) ) {
                $dir = $this->repodir;
            }

            return file_get_contents($dir ."/description", NULL);
        }

        public function getFile($file, $commit = null) {

            if ( empty($commit) ) {
                $commit = $this->branch;
            }

            $this->run("show $commit:$file");

            if ( preg_match("/\.(jpg|jpeg|png|gif|ico|bmp)$/i", $file) ) {

                return "<img src='data:image/png;base64,". base64_encode($this->cmd['results']) ."' />\n";
            }
            else {

                #$str = implode("\n", $this->cmd['results']);
                $str = $this->cmd['results'];

                # verify we have ascii data
                if ( mb_check_encoding($str, 'ASCII') ) {

                    # plain text (non code)
                    if ( preg_match("/\.(txt)$/i", $file) ) {
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
                $commit = $this->branch;
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

        public function getCommitDiff($commit = null) {

            if ( empty($commit) ) {
                $commit = $this->branch;
            }

            $args = array("--date=raw");

            $this->run("show $commit", $args);

            $results = explode("\n", $this->cmd['results']);
            $commit = array();
            $commit_info = array();
            $diff_info = array();

            foreach ($results as $line) {

                // Process the file diffs
                if ( isset($commit['diffs']) ) {

                    if ( preg_match("/^diff --git.+\sb\/(.+)$/", $line, $matches) ) {

                        if ( isset($diff_info) && count($diff_info) > 0 ) {

                            array_push($commit['diffs'], $diff_info);
                            unset($diff_info);
                            $diff_info = array();
                            $diff_info['meta'] = array();
                            $diff_info['diff'] = array();
                            $diff_info['file'] = $matches[1];
                        }

                        array_push($diff_info['meta'], htmlspecialchars($line));
                    }
                    elseif ( preg_match("/^index\s+[a-zA-z0-9]{7,}\.\.[a-zA-z0-9]{7,}|^[-+]{3}\s.+|^[a-zA-Z0-9]+ file mode/", $line) ) {

                        array_push($diff_info['meta'], htmlspecialchars($line));
                    }
                    elseif ( preg_match("/^@@\s/", $line) ) {

                        array_push($diff_info['diff'], htmlspecialchars($line));
                    }
                    else {

                        array_push($diff_info['diff'], htmlspecialchars($line));
                    }
                } 
                // Process the commit message section
                else {
                
                    if ( preg_match("/^commit\s+(.+)$/i", $line, $matches) ) {

                        $commit_info = array();
                        $commit_info['commit'] = $matches[1];
                    }
                    elseif ( preg_match("/^Author:\s+(.+)\s+<(.*)>$/i", $line, $matches) ) {

                        $commit_info['author'] = $matches[1];
                        $commit_info['email'] = $matches[2];
                    }
                    elseif ( preg_match("/^Date:\s+(.+)$/i", $line, $matches) ) {

                        $commit_info['date'] = $matches[1];

                        if ( preg_match("/^(\d+)\s+([-+0-9]+)$/", $commit_info['date'], $matches) ) {

                            $commit_info['date'] = date("M j, Y", $matches[1]);
                            $commit_info['time'] = date("H:i:s", $matches[1]);
                            $commit_info['epoch'] = $matches[1];
                            $commit_info['tz'] = $matches[2];
                        }
                    }
                    elseif ( empty($line) || preg_match("/^\s*$/", $line) ) {

                        continue;
                    }
                    elseif ( preg_match("/^diff --git.+\s[b]\/(.+)$/", $line, $matches) ) {

                        $commit['commit_info'] = $commit_info;
                        $commit['diffs'] = array();
                        $diff_info['meta'] = array();
                        $diff_info['diff'] = array();
                        $diff_info['file'] = $matches[1];
                        array_push($diff_info['meta'], htmlspecialchars($line)); 
                    }
                    else {

                        if ( ! isset($commit_info['summary']) ) {

                            $commit_info['summary'] = array();
                        }

                        array_push($commit_info['summary'], ltrim($line));
                    }
                }
            }

            if ( count($diff_info) > 0 ) {

                array_push($commit['diffs'], $diff_info);
            }

            return $commit;
        }

        public function getBranches($branch = "master") {

            $args = array("-v", "--list", "--no-abbrev", "--no-merged HEAD");
            if ( $this->_isLocal() ) { array_push($args, "-r"); } // read remotes if local

            $this->run("branch", $args);

            $results = explode("\n", $this->cmd['results']);
            $results = array_filter($results, 'strlen'); // remove null values
            $results = array_map('trim', $results); // clear tabs/spaces

            $branches = array();

            // only master branch or other...
            if ( count($results) ) {

                foreach ($results as $line) {

                    preg_match("/^([\*]*.+?)\s+([a-z0-9]+)\s+(.+)$/", $line, $matches); 

                    $branch = array();
                    $branch['branch'] = preg_replace("/^\*\s/", "", $matches[1]);
                    $branch['name'] = preg_replace("/^origin\//", "", $branch['branch']);
                    $branch['commit'] = $matches[2];
                    $branch['message'] = $matches[3];

                    array_push($branches, $branch);

                }
            }

            return $branches;
        }

        public function getBranchRevisions($branch = "master") {

            $args = array("--left-right", "HEAD...$branch");

            $this->run("rev-list", $args);

            $results = explode("\n", $this->cmd['results']);
            $results = array_filter($results, 'strlen'); // remove null values
            $results = array_map('trim', $results); // clear tabs/spaces

            return $results;
        }

        public function getCommitLog($start = 0, $max = null, $branch = null) {

            # --max-count=<number> Limit the number of commits to output.
            # --skip=<number> Skip number commits before starting to show the commit output.

            $args = array("--skip=$start", "--date=raw", "--no-merges");

            if ( isset($max) ) { 
                array_push($args, "--max-count=$max");
            }

            if ( empty($branch) ) { 
                $branch = $this->branch;
            }

            if ( $branch !== "master" ) {
                $branch .= " ^master";
            }

            $this->run("log $branch", $args); 

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
                elseif ( preg_match("/^Author:\s+(.+)\s+<(.*)>$/i", $line, $matches) ) {

                    $commit_info['author'] = $matches[1];
                    $commit_info['email'] = $matches[2];
                }
                elseif ( preg_match("/^Date:\s+(.+)$/i", $line, $matches) ) {

                    $commit_info['date'] = $matches[1];

                    if ( preg_match("/^(\d+)\s+([-+0-9]+)$/", $commit_info['date'], $matches) ) {

                        $commit_info['date'] = date("M j, Y", $matches[1]);
                        $commit_info['time'] = date("H:i:s", $matches[1]);
                        $commit_info['epoch'] = $matches[1];
                        $commit_info['tz'] = $matches[2];
                    }
                }
                elseif ( empty($line) || preg_match("/^\s*$/", $line) ) { 

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

            return ( $this->_isRemote($dir) || $this->_isLocal($dir) );
        }

        protected function _isRemote($dir = null) {

            if ( empty($dir) ) {
                $dir = $this->opts['projects_dir'] ."/". $repo;
            }

            return is_dir($dir ."/refs");
        }

        protected function _isLocal($dir = null) {

            if ( empty($dir) ) {
                $dir = $this->opts['projects_dir'] ."/". $this->repo;
            }

            return is_dir($dir ."/.git/refs");
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
                $this->repodir . implode(" ", $switches) . 
                " $gitcmd ". implode(" ", $args)
            ;            
            #print "DEBUG : ". $res['cmd'] ."\n";

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
