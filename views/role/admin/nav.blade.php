<nav class="nav-sidebar">
    <ul class="nav">
        @if( Auth::user()->can('admin.role.edit') )
        <li class="{{ Request::segment(4) == 'edit' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.role.edit', $role->id) }}">Role Settings</a>
        </li>
        @endif
        @if( Auth::user()->can('admin.role.user') )
        <li class="{{ Request::segment(4) == 'user' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.role.user', $role->id) }}"><strike>User List</strike></a>
        </li>
        @endif
        @if( Auth::user()->can('admin.role.permissions') )
        <li class="{{ Request::segment(4) == 'permissions' ? 'active' : '' }}">
            <a href="{{ URL::Route('admin.role.permissions', $role->id) }}">Role Permissions</a>
        </li>
        @endif
	</ul>
</nav>