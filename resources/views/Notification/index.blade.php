@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">

    <div class="app-main__inner">

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Notification List</h5>
                        </div>
                    </div>
                    @if(Auth::user()->UserType==1)
                    <div class="col-sm-6 ">
                        <a href="{{ route('admin.notifications.create') }}"  class="btn-shadow btn btn-info float-right">Send Notification</a>
                    </div>
                    @endif
                </div>
                <hr>

                <div class="table-responsive">
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($notifications as $index => $note)
                            <tr class="{{ $note->is_read ? '' : 'table-warning' }}">
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $note->title }}</strong>
                                </td>

                                <td>
                                    {{ $note->message }}
                                </td>

                                <td>
                                    {{ $note->created_at->format('d M Y, h:i A') }}
                                </td>

                                <td class="text-center">
                                    @if($note->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unread</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if(!$note->is_read)
                                        <form method="POST"
                                            action="{{ route('notifications.read', $note->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif

                                     @if(!empty($note->video_url))
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger ms-1 play-video"
                                                data-video="{{ $note->video_url }}">
                                            🎬
                                        </button>
                                    @endif

                                    @if(!empty($note->action_url))
                                        <a href="{{ $note->action_url }}"
                                        class="btn btn-sm btn-outline-secondary">
                                            View
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No notifications found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="videoModal" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-lg" style="margin-top:70px">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Update Video</h5>
            </div>

            <div class="modal-body">

                <!-- HTML5 video (MP4) -->
                <video id="html5Video"
                       controls
                       style="width:100%; border-radius:6px; display:none;">
                    <source src="" type="video/mp4">
                </video>

                <!-- YouTube / external video -->
                <iframe id="iframeVideo"
                        style="width:100%; height:400px; display:none;"
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen>
                </iframe>

            </div>

        </div>
    </div>
</div>



@endsection

@section('js')
<script>
$(document).ready(function () {

    $('.play-video').on('click', function () {

        let videoUrl = $(this).data('video');

        // reset
        $('#html5Video').hide();
        $('#iframeVideo').hide();
        $('#html5Video source').attr('src', '');
        $('#iframeVideo').attr('src', '');

        // YouTube / external link
        if (videoUrl.includes('youtube') || videoUrl.includes('youtu.be')) {

            let embedUrl = videoUrl;

            if (videoUrl.includes('watch?v=')) {
                embedUrl = videoUrl.replace('watch?v=', 'embed/');
            }

            $('#iframeVideo')
                .show()
                .attr('src', embedUrl + '?autoplay=1');

        }
        // MP4 file
        else {

            $('#html5Video')
                .show()
                .find('source')
                .attr('src', videoUrl);

            $('#html5Video')[0].load();
            $('#html5Video')[0].play();
        }

        $('#videoModal').modal('show');
    });

    $('#videoModal').on('hidden.bs.modal', function () {
        $('#html5Video')[0]?.pause();
        $('#html5Video source').attr('src', '');
        $('#iframeVideo').attr('src', '');
    });

});


document.querySelectorAll('.mark-read').forEach(btn => {
    btn.addEventListener('click', function () {
        fetch('/notifications/read/' + this.dataset.id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    });
});
</script>
@endsection



