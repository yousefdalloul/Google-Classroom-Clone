<li class="nav-item dropdown">
    <a class="nav-link btn dropdown-toggle " href="#" role="button" type="button" id="dropdownMenuButton"
       data-bs-toggle="dropdown" aria-expanded="false">
        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        </div>
        Notifications
        @if ($unreadCount)
            <span class="badge bg-primary m-1">{{ $unreadCount }}</span>
        @endif
    </a>

    <ul class="dropdown-menu" style="width: 330%; text-align: left;">
        <li>
            <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                <tbody>
                @foreach ($notifications as $notification)
                    <tr>
                        <td class="notification-cell">
                            @if ($notification->unread())
                                <span class="notification-badge">*</span>
                            @endif
                            <div class="notification-content">
                                <a class="dropdown-item" href="{{ $notification->data['link'] }}?nid={{ $notification->id }}">
                                    {{ __($notification->data['body']) }}
                                </a>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </li>
    </ul>

</li>