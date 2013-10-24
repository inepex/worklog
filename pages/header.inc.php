<div class="header">
 <div class="navbar navbar-inverse">
              <div class="navbar-inner">
                <div class="container">
                
                  <a class="brand" href="index.php" style="color: #ffffff !important;"><img src="images/logo.png" style="margin-right: 5px; margin-top: -6px;">Worklog</a>
                 
                 <?php  if ($_SESSION['loggedin_worklog']=="true") { ?>
                 
                 
                    <ul class="nav">
                      <li><a href="project_new.php">+New Project</a></li>

                    </ul>
                    
                    <ul class="nav pull-right">
                      <li <?php if (strpos($_SERVER['PHP_SELF'],'index.php')) echo 'class="active"'; ?>><a href="index.php" >Logs</a></li>
                      <li <?php if (strpos($_SERVER['PHP_SELF'],'project_stat.php')) echo 'class="active"'; ?>><a href="project_stat.php">Status</a></li>
                      
                       <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Scrum <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="scrum_list.php">Scrum by User</a></li>
                          <li><a href="scrum_list_by_date.php">Scrum by Date</a></li>
                        </ul>
                      </li>
                      
                      <li <?php if (strpos($_SERVER['PHP_SELF'],'summary.php')) echo 'class="active"'; ?>><a href="summary.php">Summary</a></li>
                      <li <?php if (strpos($_SERVER['PHP_SELF'],'projects_list.php')) echo 'class="active"'; ?>><a href="projects_list.php?search=&order_by=worklog_project_id&order=asc&page=0&project_status=1" >All Projects</a></li>
                      <li <?php if (strpos($_SERVER['PHP_SELF'],'help.php')) echo 'class="active"'; ?>><a href="help.php" >Help</a></li>
                      
                   
                      
                      
                      <li class="divider-vertical"></li>
                      <?php 
						if ($_SESSION['loggedin_worklog']=="true") {
				
						if ($_SERVER['QUERY_STRING']=='') {
							$logoutlink="$_SERVER[PHP_SELF]?log=logout";
						} else {
							$logoutlink="$_SERVER[REQUEST_URI]&log=logout";
						}
						}
						?>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logged in as <?php echo $_SESSION['entername'];?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="user_edit.php">Settings</a></li>
                          <li><a href="<?php echo $logoutlink; ?>">Logout</a></li>
                        </ul>
                      </li>
                       
                    </ul>
                  <!-- /.nav-collapse -->
                  
                  <?php } ?>
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
</div>
