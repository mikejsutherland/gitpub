            <div class="navbar">
                Project: <a href="<?=$CONFIG['base_uri']."/".$_SESSION['repo'];?>/"><?=$_SESSION['repo'];?></a>
            </div>
            
            <div class="tabbar">
                <ul id="tabs">
                    <li class="tab <?=isActiveTab('files');?>"><a href="<?=$CONFIG['base_uri']."/".$_SESSION['repo'].'/files/'?>">Files</a></li>
                    <li class="tab <?=isActiveTab('commits');?>"><a href="<?=$CONFIG['base_uri']."/".$_SESSION['repo'].'/commits/'?>">Commits</a></li>
                    <li class="tab <?=isActiveTab('branches');?>"><a href="<?=$CONFIG['base_uri']."/".$_SESSION['repo'].'/branches/'?>">Branches</a></li>
                </ul>
                <br class="clear" />
            </div>
