<div class="row permission-groups">
@if (count($permissions))
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5"><h5><strong>{{ $title }}</strong></h5></div>
            <div class="col-md-7">
                <select name="" class="master-select form-control">
                    <option disabled selected>Set all the permissions below to:</option>
                    <option class="inherit">Inherit</option>
                    <option class="privilege">Allow</option>
                    <option class="restriction">Deny</option>
                </select>
            </div>
        </div>
        <div class="row">
            <hr>
        </div>
    @foreach ($permissions as $permission)
        <div class="row permission-row">
            <div class="col-md-5"><h5>
                @if (!empty($permission->readable_name))
                <span data-toggle="tooltip" data-title="{{ $permission->readable_name }}" data-placement="top" data-trigger="hover focus">
                    <i class="fa fa-question-circle"></i>
                </span>
                @endif
                {{ ucwords(str_replace('.', ' ', $permission->action)) }}
            </h5></div>
            <div class="col-md-7">
                @set($type, $role->getPermissionProperty($permission->id, 'type'))
                <select name="permissions[{{ $permission->action.'@'.$permission->resource_type }}]" class="form-control">
                    <option value="inherit" class="inherit">Inherit</option>
                    <option value="privilege" class="privilege"{{ $type === 'privilege' ? ' selected="selected"' : '' }}>
                        Allow
                    </option>
                    <option value="restriction" class="restriction"{{ $type === 'restriction' ? ' selected="selected"' : '' }}>
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
