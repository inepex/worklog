<div class="header">
 <div class="navbar navbar-inverse">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand" href="index.php" style="color: #ffffff !important;"><img src="images/logo.png" style="margin-right: 5px; margin-top: -6px;">Worklog</a>
                  <div class="nav-collapse collapse navbar-responsive-collapse">
                    <ul class="nav">
                      <li class="active"><a href="#">+New Project</a></li>

                    </ul>
                    
                    <ul class="nav pull-right">
                      <li class="active"><a href="index.php">Log View</a></li>
                      <li class="active"><a href="project_stat.php">ProjectStat View</a></li>
                      <li class="active"><a href="summary.php">Summary</a></li>
                      
                      
                      <li class="divider-vertical"></li>
                      <?php 
						if ($_SESSION['loggedin_worklog_admin']=="true") {
				
						if ($_SERVER['QUERY_STRING']=='') {
							$logoutlink="$_SERVER[PHP_SELF]?log=logout";
						} else {
							$logoutlink="$_SERVER[REQUEST_URI]&log=logout";
						}
						}
						?>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logged in as Hidi Tibor <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="user_edit.php">Settings</a></li>
                          <li><a href="<?php echo $logoutlink; ?>">Logout</a></li>
                        </ul>
                      </li>
                       
                    </ul>
                  </div><!-- /.nav-collapse -->
                </div>
              </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
</div>
