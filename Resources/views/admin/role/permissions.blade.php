@extends(partial('auth::admin.role._layout'))

@section('role-form')
{!! Former::horizontal_open() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Permissions</h3>
        </div>
        <div class="panel-body no-padding">
            <table class="table table-striped">
                <thead>
                    <th>Action</th>
                    <th>Setting</th>
                    <th>Calculated Setting</th>
                </thead>

                <tbody>
                    @set($old_group, null)
                    @foreach( $permissions as $permission )
                        @if( $old_group != $permission->resource_type )
                           @set($old_group, $permission->resource_type)
                            <tr class="resource_{{ $permission->resource_type }}">
                                <th>
                                    {{ ucwords( str_replace('_', ' ', $permission->resource_type ) ) }}
                                </th>
                                <th colspan="">
                                    <select name="" class="form-control">
                                        <option selected="selected"></option>
                                        <option class="inherit">Inherit</option>
                                        <option value="privilege" class="priv">Privilege</option>
                                        <option value="restriction" class="restrict">Restriction</option>
                                    </select>
                                    <small>Apply to all in group</small>
                                </th>
                                <th></th>
                            </tr>
                        @endif

                        <tr class="resource_{{$permission->resource_type}}">
                            <td>{{ $permission->readable_name }}</td>
                            <td>
                                <select name="permisisons[{{ $permission->id }}]" id="" class="form-control">
                                    <option class="inherit">Inherit</option>
                                    <option value="privilege" class="priv" {{ $role->getPermissionProperty($permission->id, 'type') == 'privilege' ? 'selected="selected"' : '' }}>
                                        Privilege
                                    </option>
                                    <option value="restriction" class="restrict" {{ $role->getPermissionProperty($permission->id, 'type') == 'restriction' ? 'selected="selected"' : '' }}>
                                        Restriction
                                    </option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <button class="btn-labeled btn btn-success pull-right" type="submit">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Save
    </button>
{!! Former::close() !!}

<script>
    (function ($) {
        $('th .form-control').on('change', function () {
            var className = $(this).find(':selected').attr('class'),
                selector = 'tr.' + $(this).closest('tr').attr('class') + ' td .form-control';

            $(selector).val( $(selector).find('option.' + className).val() );
        });
    })(jQuery);
</script>
@stop
