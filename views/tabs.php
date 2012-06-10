            <div class="navbar">
                Project: <a href="<?=$CONFIG['base_uri']."/?repo=".$_SESSION['repo'];?>"><?=$_SESSION['repo'];?></a><br />
                <span class="small"><?=$gp->getDescription();?></span>
            </div>
            
            <div class="tabbar">
                <ul id="tabs">
                    <li class="tab <?=isActiveTab('files');?>"><a href="<?=$CONFIG['base_uri']."/?repo=".$_SESSION['repo'].'&nav=files'?>">Files</a></li>
                    <li class="tab <?=isActiveTab('commits');?>"><a href="<?=$CONFIG['base_uri']."/?repo=".$_SESSION['repo'].'&nav=commits'?>">Commits</a></li>
                    <li class="tab <?=isActiveTab('branches');?>"><a href="<?=$CONFIG['base_uri']."/?repo=".$_SESSION['repo'].'&nav=branches'?>">Branches</a></li>
                </ul>
                <br class="clear" />
            </div>
