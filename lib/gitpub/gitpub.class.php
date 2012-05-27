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
                if ( $dir == '.' || $dir == '..' || ! $this->isGitRepo($this_path) ) { continue; }

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

        public function getCommitLog($start = 0, $max = 10) {

            # --max-count=<number> Limit the number of commits to output.
            # --skip=<number> Skip number commits before starting to show the commit output.
            $this->run('log', array("--skip=$start","--max-count=$max"));
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
