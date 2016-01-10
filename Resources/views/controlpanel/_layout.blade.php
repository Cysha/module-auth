<div class="row">
    <div class="col-md-{{ $col_one or '3'}}">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">Control Panel</div>
            </div>
            <!-- <div class="panel-body"> -->
                @menu('frontend_user_controlpanel')
            <!-- </div> -->
        </div>
    </div>
    <div class="col-md-{{ $col_two or '9'}}">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">{{ $title or 'Control Panel' }}</div>
            </div>
            <div class="panel-body">
                @yield('control-form')
            </div>
        </div>
    </div>
</div>
