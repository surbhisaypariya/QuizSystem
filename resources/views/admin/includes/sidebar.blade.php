<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('subject.index') }}">
                <i class="menu-icon mdi mdi-book"></i>
                <span class="menu-title">Subject</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('question.index') }}">
                <i class="menu-icon mdi mdi-help"></i>
                <span class="menu-title">Question</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="menu-icon mdi mdi-logout"></i>
            <span class="menu-title">Logout</span>
        </a>
    </li>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
</nav>