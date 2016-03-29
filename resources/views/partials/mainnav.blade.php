<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->email }}</strong>
                             </span> 
                    </div>
                    <div class="logo-element">
                        GR+
                    </div>
                </li>
                <li>
                    <a href="/"><i class="fa fa-th-large"></i> <span class="nav-label">Calendar</span> </a>
                </li>
            </ul>

        </div>
    </nav>