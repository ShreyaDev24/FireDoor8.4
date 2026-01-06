@extends('layouts.Master')

@section('main_section')
    @if (session()->has('error'))
        <style type="text/css">
            #useremail {
                border-color: red
            }

        </style>
    @endif

<div class="container">
<h2>Send Notification</h2>
   <form method="POST" action="{{ route('admin.notifications.store') }}">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Message</label>
            <textarea name="message" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="system">System</option>
                <option value="update">System Update</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Priority</label>
            <select name="priority" class="form-control">
                <option value="normal">Normal</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Target</label>
            <select name="target_type" class="form-control">
                <option value="all">All Users</option>
                <option value="company">Specific Company</option>
            </select>
        </div>

        <button class="btn btn-primary">Send Notification</button>

        </form>
</div>
@endsection
