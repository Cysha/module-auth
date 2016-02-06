<div class="panel panel-default">
    <div class="panel-heading">Latest Registered Users</div>
    <div class="panel-body">
        <ul class="users-list clearfix">
        @forelse ($users as $user)
            <li>
                <img src="{{ array_get($user, 'avatar') }}" class="img-circle" style="width: 45px;">
                <div><a class="users-list-name" href="#">{{ array_get($user, 'screenname') }}</a></div>
                <small class="muted">{!! array_get($user, 'registered.element') !!}</small>
            </li>
        @empty
            <li class="">No users signed up for a while.</li>
        @endforelse
        </ul>
    </div>
</div>
