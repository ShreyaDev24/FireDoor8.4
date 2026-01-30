@extends('layouts.Master')

@section('main_section')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Create Support Ticket</h5>
                </div>
                <hr>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong><br>
                        {{ session('success') }}<br><br>

                        Your Ticket Reference:
                        <strong>#{{ session('ticket_id') }}</strong>

                    </div>
                    @endif


                    <form method="POST" action="/help-center" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subject</label>
                                <input type="text"
                                       name="subject"
                                       class="form-control"
                                       placeholder="Enter subject"
                                       required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Contact Email</label>
                                <input type="email"
                                    name="contact_email"
                                    class="form-control"
                                    placeholder="Enter your email"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category" class="form-control">
                                    <option value="help">Help</option>
                                    <option value="bug">Bug</option>
                                    <option value="feature">Feature Request</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Priority</label>
                                <select name="priority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Message</label>
                            <textarea name="message"
                                      class="form-control"
                                      rows="5"
                                      placeholder="Describe your issue clearly..."
                                      required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                Submit Ticket
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
