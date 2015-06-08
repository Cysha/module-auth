<div class="row">
@if (count($permissions))
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5"><div class="pull-right">
                <small>Apply to all in group</small>
            </div></div>
            <div class="col-md-7">
                <select name="" class="master-select form-control">
                    <option disabled selected>Please Select</option>
                    <option class="inherit">Inherit</option>
                    <option class="privilege">Allow</option>
                    <option class="restrict">Deny</option>
                </select>
            </div>

        </div>
    @foreach ($permissions as $permission)
        <div class="row permission-row">
            <div class="col-md-5">
                @if (!empty($permission->readable_name))
                <span data-toggle="tooltip" data-title="{{ $permission->readable_name }}" data-placement="top" data-trigger="hover focus">
                    <i class="fa fa-question-circle"></i>
                </span>
                @endif
                {{ ucwords(str_replace('.', ' ', $permission->action)) }}
            </div>
            <div class="col-md-7">
                @set($type, $role->getPermissionProperty($permission->id, 'type'))
                <select name="permissions[{{ $permission->action.'@'.$permission->resource_type }}]" class="form-control">
                    <option value="inherit" class="inherit">Inherit</option>
                    <option value="privilege" class="privilege"{{ $type === 'privilege' ? ' selected="selected"' : '' }}>
                        Allow
                    </option>
                    <option value="restriction" class="restrict"{{ $type === 'restriction' ? ' selected="selected"' : '' }}>
                        Deny
                    </option>
                </select>
            </div>
        </div>
    @endforeach
    </div>
@else
    <div class="alert alert-info">Info: No permissions could be found.</div>
@endif
</div>
{!! \Debug::dump($permissions, ''); !!}
