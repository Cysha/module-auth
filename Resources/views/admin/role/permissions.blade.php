@extends(partial('auth::admin.role._layout'))

@section('role-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Permissions</h3>
        </div>
        <div class="panel-body no-padding row">
            <div class="col-md-3">
                <ul class="nav nav-pills nav-stacked" id="permissions">
                @set($active, false)
                @foreach ($groups as $group)
                    @if($active === false)
                        @set($active, true)
                        <li class="active">
                    @else
                        <li>
                    @endif

                        <a href="#{{ str_slug($group) }}" data-toggle="pill">{{ ucwords(str_replace('_', ' ', $group)) }}</a>
                    </li>
                @endforeach
                </ul>
            </div>

            <div class="col-md-9">
                <div class="tab-content">
                @set($active, false)
                @foreach ($groups as $group)
                    @if($active === false)
                        @set($active, true)
                        <div class="tab-pane active" id="{{ str_slug($group) }}">
                    @else
                        <div class="tab-pane" id="{{ str_slug($group) }}">
                    @endif

                        @include(partial('auth::admin.partials.permissions'), [
                            'permissions' => $permissions->where('resource_type', $group),
                            'role' => $role
                        ])
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
            var className = $(this).find(':selected').attr('class'),
                selector = $(this).closest('.tab-content').attr('class') + ' .permission-row select.form-control';

            $(selector).val( $(selector).find('option.' + className).val() );
        });
    })(jQuery);
</script>
@stop
