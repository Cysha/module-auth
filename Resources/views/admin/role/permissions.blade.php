@extends(partial('auth::admin.role._layout'))

@section('role-form')
{!! Former::horizontal_open() !!}
        <div class="alert alert-info">
            <p><strong>Warning:</strong> This panel will show any permission groups that has been specified. However, it will not show any permissions that have a resource_id attached.</p>
        </div>
    <div class="panel panel-default panel-permissions">
        <div class="panel-heading">
            <h3 class="panel-title">Permissions</h3>
        </div>
        <div class="panel-body no-padding row">
            <?php
                $tier_one = [];
                $tier_two = [];

                foreach ($groups as $group) {
                    list($a, $b) = explode('_', $group);
                    $tier_one[] = $a;
                    $tier_two[$a][] = $b;
                }
                $tier_one = array_unique($tier_one);

            ?>

            <div class="col-md-2 permissions-nav module">
                <ul class="nav nav-pills nav-stacked" id="permissions">
                    <li class="disabled">Module</li>
                @set($active_t1, false)
                @foreach($tier_one as $module)
                    @if($active_t1 === false)
                        @set($active_t1, true)
                        <li class="active">
                    @else
                        <li>
                    @endif

                        <a href="#t1_{{ $module }}" data-toggle="pill">{{ ucwords($module) }} <span class="badge pull-right">{{ count($tier_two[$module]) }}</span></a>
                    </li>
                @endforeach
                </ul>
            </div>

            <div class="col-md-10">
                <div class="tab-content">
                @set($active_t1, false)
                @foreach($tier_one as $group)
                    @if($active_t1 === false)
                        @set($active_t1, true)
                        <div class="tab-pane active" id="t1_{{ $group }}">
                    @else
                        <div class="tab-pane" id="t1_{{ $group }}">
                    @endif

                        <div class="row">
                            <div class="col-md-3 permissions-nav model">
                                <ul class="nav nav-pills nav-stacked" id="permissions">
                                    <li class="disabled">Group</li>

                                @set($active_t2, false)
                                @foreach($tier_two[$group] as $model)
                                    @if($active_t2 === false)
                                        @set($active_t2, true)
                                        <li class="active">
                                    @else
                                        <li>
                                    @endif

                                        <a href="#t2_{{ $group.'_'.$model }}" data-toggle="pill">{{ ucwords($model) }} <span class="badge pull-right">{{ count($permissions->where('resource_type', $group.'_'.$model)->where('resource_id', NULL)) }}</span></a>
                                    </li>
                                @endforeach
                                </ul>
                            </div>

                            <div class="col-md-9">
                                <div class="tab-content">
                                @set($active_t2, false)
                                @foreach($tier_two[$group] as $model)
                                    @if($active_t2 === false)
                                        @set($active_t2, true)
                                        <div class="tab-pane active" id="t2_{{ $group.'_'.$model }}">
                                    @else
                                        <div class="tab-pane" id="t2_{{ $group.'_'.$model }}">
                                    @endif

                                        @set($perms, $permissions->where('resource_type', $group.'_'.$model)->where('resource_id', NULL)->sortBy('resource_type'))
                                        @include(partial('auth::admin.partials.permissions'), [
                                            'title' => ucwords($group.' &raquo; '.$model),
                                            'permissions' => $perms,
                                            'role' => $role,
                                        ])
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
                </div>
            </div>

        </div>
        <div class="panel-footer clearfix">
            <button class="btn-labeled btn btn-success pull-right" type="submit">
                <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
            </button>
        </div>
    </div>
{!! Former::close() !!}

<script>
    (function ($) {
        $('select.master-select').on('change', function () {
            var value = $(this).find(':selected').attr('class');

            $(this)
                .parents('.permission-groups')      /* goto parent */
                .find('.permission-row select')     /* find the children select boxes */
                .val(value)                         /* change the values */
                .change();                          /* trigger a change to make it update */
        });
    })(jQuery);
</script>
@stop
