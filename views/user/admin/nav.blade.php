<nav class="nav-sidebar">
    <ul class="nav">
        @if( Auth::user()->can('admin.user.edit') )
        <li class="{{ Request::segment(4) == 'edit' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.user.edit', $user->id) }}">User Settings</a>
        </li>
        @endif
        @if( Auth::user()->can('admin.user.permissions') )
        <li class="{{ Request::segment(4) == 'permissions' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.user.permissions', $user->id) }}">User Permissions</a>
        </li>
        @endif
        @if( Auth::user()->can('admin.user.history') )
        <li class="{{ Request::segment(4) == 'history' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.user.history', $user->id) }}">User Changelog</a>
        </li>
        @endif
	</ul>
</nav>