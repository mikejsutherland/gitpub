<?
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

    class gitpub {

        public $opts;

        public function __construct($opts = array()) {

            $default_opts = array(
                'git_path' => '/usr/local/git/bin',
                'projects_dir' => '',
                'branch' => 'master',
                'repo' => '',
                'enable_cache' => false,
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

            // Get all the tips
            $this->setTips();

            // Set the top commit id
            $this->setCommitId();

            // Set the tip (top commit id)
            $this->tip = $this->commit;

            // Set the directory to cache to            
            if ( $this->opts['enable_cache'] ) {
                
                // Initialize the cache dir
                $this->cachedir = "cache/". $repo;
                $this->newCache();

                // Flush the cache if necessary
                if ( $this->metaCache() !== implode(",", $this->tips) ) {

                    // Flush the cache
                    $this->flushCache();
                    // Update the cache meta
                    $this->metaCache(implode(",", $this->tips));
                }

                // Turn on caching
                $this->enable_cache = $this->opts['enable_cache'];
            }

            return;
        }

        // Create the directory to cache to
        public function newCache() {

            if ( ! isset($this->repo) ) {
                return false;
            }

            // Create the cache dir if it doesn't exist
            if ( ! is_dir("$this->cachedir") ) {
                mkdir("$this->cachedir", 0777, true);
            }

            return;
        }

        // Removes all cache files
        public function flushCache() {

            if ( ! isset($this->repo) ) {
                return false;
            }

            if ( is_dir("$this->cachedir") ) {

                $cache = scandir("$this->cachedir");

                foreach ($cache as $fn) {

                    if ( $fn == "." || $fn == ".." ) { continue; }

                    chmod("$this->cachedir/$fn", 0777);
                    unlink("$this->cachedir/$fn");
                }
            }

            return;
        }

        // Set an abstract data point
        public function metaCache($meta = null) {

            $metafile = $this->cachedir ."/meta";

            // Fetch the cache meta value
            if ( empty($meta) ) {

                $cachemeta = null;

                if ( file_exists("$metafile") ) {
                    $cachemeta = file_get_contents("$metafile");
                }
                return $cachemeta;
            }
            // Write the cache meta value
            else {

                $fp = fopen("$metafile", 'w');
                fwrite($fp, $meta);
                fclose($fp);
            }

            return;
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

        public function setTips() {

            $this->tips = array();

            $branches = $this->getBranches();

            foreach ($branches as $branch) {

                $this->tips[$branch['branch']] = $branch['commit'];
            }

            // Sort the results by branch name
            ksort($this->tips);
        }

        public function setCommitId($id = null) {

            if ( count($this->tips) ) {

                $this->commit = $this->tips[$this->branch];
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

                // IE <= 7 does not support data urls
                preg_match("/MSIE\s+(\d+)/", $_SERVER['HTTP_USER_AGENT'], $ie);

                if( isset($ie[1]) && $ie[1] <= 7 ) {

                    return "<div class='message'>Your browser does not support dynamically viewing images.\n</div>\n";
                }
                elseif ( isset($ie[1]) && $ie[1] == 8 && $this->cmd['size'] >= (32*1024) ) {

                    return "<div class='message'>Your browser does not support dynamically viewing images larger than 32kb.\n</div>\n";
                }
                else {

                    return "<img src='data:image/png;base64,". base64_encode($this->cmd['results']) ."' />\n";
                }
            }
            else {

                $str = $this->cmd['results'];

                # verify we have ascii data
                if ( mb_check_encoding($str, 'ASCII') ) {

                    # plain text (non code)
                    if ( preg_match("/README|LICENSE/i", $file) ) {
                        return "<pre class='prettyprint nocode'>". htmlspecialchars($str) ."</pre>\n";
                    }
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
                            $diff_info['mode'] = null;
                            $diff_info['type'] = 'text';
                            $diff_info['lines'] = 0;
                        }

                        array_push($diff_info['meta'], htmlspecialchars($line));
                    }
                    elseif ( preg_match("/^index\s+[a-zA-Z0-9]{7,}\.\.[a-zA-Z0-9]{7,}|^[-\+]{3}\s.+/", $line) ) {

                        array_push($diff_info['meta'], htmlspecialchars($line));
                    }
                    elseif ( preg_match("/^([a-zA-Z0-9]+) file mode/", $line, $matches) ) {

                        $diff_info['mode'] = strtolower($matches[1]);
                    }
                    elseif ( preg_match("/^([a-zA-Z]+) files \/dev/", $line, $matches) ) {

                        $diff_info['type'] = strtolower($matches[1]);
                    }
                    elseif ( preg_match("/^@@\s/", $line) ) {

                        array_push($diff_info['diff'], htmlspecialchars($line));
                    }
                    else {

                        $diff_info['lines']++;

                        if ( empty($diff_info['mode']) ) {

                            array_push($diff_info['diff'], htmlspecialchars($line));
                        }
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
                        $diff_info['mode'] = null;
                        $diff_info['type'] = 'text';
                        $diff_info['lines'] = 0;
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

        public function getArchive($tag = null, $format = "tar") {

            # to stdout git archive --format=tar --prefix=gitpub-1.0.0/ v1.0.0
            # to gz git archive --format=tar --prefix=gitpub-1.0.0/ v1.0.0 | gzip >gitpub-1.0.0.tar.gz

            $prefix = $this->repo;

            if ( empty($tag) ) {
                $tag = "HEAD";
            }
            else {

                $prefix .= ($tag[0] == "v") ? "-". substr($tag, 1) : "-". $tag;
            }

            $args = array("--format=$format", "--prefix=$prefix/", $tag);

            $this->run("archive", $args);
        }

        public function getTags() {

            # -l, list tags
            # -n1, print the first line of the message
            $args = array("-l", "-n1");
            #if ( $this->_isLocal() ) { array_push($args, "-r"); } // read remotes if local

            $this->run("tag", $args);

            $results = explode("\n", $this->cmd['results']);
            $results = array_filter($results, 'strlen'); // remove null values
            $results = array_map('trim', $results); // clear tabs/spaces

            $tags = array();

            foreach ($results as $line) {

                if ( preg_match("/^\s*([^\s]+)\s*(.*)$/", $line, $matches) ) {

                    $tags[$matches[1]] = $matches[2];
                }
            }

            return $tags;
        }

        public function getBranches($branch = "master") {

            # --list (not available in 1.7.4.1)
            # --no-merged HEAD causes unmerged branches to not show up
            $args = array("-v", "-l", "--no-abbrev");
            if ( $this->_isLocal() ) { array_push($args, "-r"); } // read remotes if local

            // Disable caching
            $this->enable_cache = false;

            // Run the command
            $this->run("branch", $args);

            // Reset the cache
            $this->enable_cache = $this->opts['enable_cache'];

            $results = explode("\n", $this->cmd['results']);
            $results = array_filter($results, 'strlen'); // remove null values
            $results = array_map('trim', $results); // clear tabs/spaces

            $branches = array();

            if ( count($results) ) {

                foreach ($results as $line) {

                    if ( preg_match("/^([\*]*.+?)\s+([a-z0-9]+)\s+(.+)$/", $line, $matches) ) { 

                        $branch = array();
                        $branch['branch'] = preg_replace("/^\*\s/", "", $matches[1]);
                        $branch['name'] = $branch['branch']; #preg_replace("/^origin\//", "", $branch['branch']);
                        $branch['commit'] = $matches[2];
                        $branch['message'] = $matches[3];

                        array_push($branches, $branch);
                    }
                }
            }

            return $branches;
        }

        public function getBranchRevisions($branch = "master") {

            $args = array("--left-right", "master...$branch");

            $this->run("rev-list", $args);

            $results = explode("\n", $this->cmd['results']);
            $results = array_filter($results, 'strlen'); // remove null values
            $results = array_map('trim', $results); // clear tabs/spaces

            return $results;
        }

        public function getCommitLog($start = 0, $max = null, $branch = null, $ignore = "^master") {

            # --max-count=<number> Limit the number of commits to output.
            # --skip=<number> Skip number commits before starting to show the commit output.

            $args = array("--skip=$start", "--date=raw");

            if ( ! empty($max) ) { 
                array_push($args, "--max-count=$max");
            }

            if ( empty($branch) ) { 
                $branch = $this->branch;
            }

            if ( $branch !== "master" ) {
                $branch .= " $ignore";
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
            $res['cmd'] = escapeshellcmd($this->_chopPath($this->opts['git_path']) . "/git --no-pager --git-dir=" .
                $this->repodir . implode(" ", $switches) . 
                " $gitcmd ". implode(" ", $args)
            );            
            print "<pre>DEBUG : ". $res['cmd'] ."</pre><br />\n";

            if ( $this->enable_cache ) {

                // Invalidate cache if the commit tip has changed
                if ( $this->metaCache() !== implode(",", $this->tips) ) {

                    $this->flushCache();
                    $this->metaCache(implode(",", $this->tips));
                }

                $cachefile = sha1($res['cmd']) .".cache";

                // Return the cached result if present
                if ( file_exists($this->cachedir ."/$cachefile") ) {

                    // Start the output buffer
                    ob_start();
                    // Read the cached results
                    readfile($this->cachedir ."/$cachefile");
                    // Store the results from the output buffer
                    $res['size'] = ob_get_length();
                    $res['results'] = ob_get_contents();
                    // Close the output buffer
                    ob_end_clean();

                    // Store the result
                    $this->cmd = $res;

                    return;
                }
            }

            // Enable output buffering
            ob_start();
            // Execute the command
            passthru($res['cmd'], $res['rc']);
            // Store the results from the output buffer
            $res['size'] = ob_get_length();
            $res['results'] = ob_get_contents();

            if ( $this->enable_cache ) {

                // Cache the results
                $fp = fopen("$this->cachedir/". $cachefile, 'w');
                fwrite($fp, ob_get_contents());
                fclose($fp);
            }

            // Close the output buffer
            ob_end_clean();

            if ( $res['rc'] !== 0 ) {

                error_log("Error running command: '". $res['cmd'] ."', rc: ". $res['rc'], 0);
                throw new Exception("An error occurred.\n");
            }

            // Store the result
            $this->cmd = $res;
        }
    }

?>
