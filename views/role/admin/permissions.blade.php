<div class="row">
    <div class="col-md-2"> @include('users::role.admin.nav') </div>
    <div class="col-md-10">
    @if( count($permissions) )
    <?php
        $groups = array();
        foreach($permissions as $perm){
            list($group, $section) = explode('.', $perm->name);
            $groups[ $group ][ $section ][ $perm->id ] = $perm;
        }
    ?>

    {{ Former::horizontal_open() }}
    <div class="panel panel-default">
        <div class="panel-heading">
            Permission Set for {{ $role->name }}
            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Update Permissions
            </button>
        </div>
        <div class="panel-body tabbable tabs-left">
            <div class="panel-group" id="permissions">
            <?php
                $i = 0;
                foreach($groups as $key => $section):
            ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a data-toggle="collapse" data-parent="#permissions" href="#{{ Str::slug($key, '-') }}">
                            <h4 class="panel-title">
                                {{ Str::title($key) }}
                            </h4>
                        </a>
                    </div>
                <?php
                echo '<div class="panel-collapse collapse'.($i === 0 ? ' in' : '').'" id="'.Str::slug($key, '-').'"><div class="panel-body">';
                    foreach($section as $group => $perms):
                ?>
                    <div class="page-header">
                        <h4>{{ Str::title($group) }}</h4>

                    </div>
                    <div class="col-md-12">
                        <div class="pull-right">
                            <span class="">
                                <a href="#toggle_{{ $group }}" id="toggle_{{ $group }}">Swap </a> &nbsp;|
                                <a href="#select_{{ $group }}" id="select_{{ $group }}">Select All </a>&nbsp;|
                                <a href="#deslect_{{ $group }}" id="deselect_{{ $group }}">Deselect All</a>
                            </span>
                        </div>
                        <?php
                        foreach($perms as $permID => $perm):
                        ?>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            {{ Form::checkbox(
                                                'permissions['.$perm->id.']',
                                                $perm->id,
                                                $role->has( $perm->name ),
                                                [ 'id' => 'permission['.$group.']['.$perm->id.']' ]
                                            ) }}

                                            <?php $permLabel = explode('.', $perm->name); ?>
                                            {{ Form::label(
                                                'permission_' . $perm->id,
                                                Str::title(last($permLabel))
                                            ) }}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $perm->description }}
                                    </div>
                                </div>
                            </div>
            <?php
                        endforeach;
                    echo '</div>';
                    endforeach;

                echo '</div></div></div>';
                $i++;
                endforeach;
            ?>
            </div>
        </div>
    </div>
    {{ Former::close() }}

    @endif
    </div>
</div>
