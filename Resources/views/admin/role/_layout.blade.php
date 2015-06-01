<div class="row">
    <div class="col-md-{{ $col_one or '3'}}">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Role Manager</div>
            </div>
            <div class="panel-body">@menu('backend_role_menu')</div>
        </div>
    </div>
    <div class="col-md-{{ $col_two or '9'}}">
        @yield('role-form')
    </div>
</div>
