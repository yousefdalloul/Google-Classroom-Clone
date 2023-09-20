<x-main-layout title="Create Classwork">
    <div class="container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>{{ $classwork->title }}</h3>
        <x-alert name="success" id="success" class="alert-success"></x-alert>
        <x-alert name="error" id="success" class="alert-danger"></x-alert>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div>
                    {!! $classwork->description !!}
                </div>
                <h4>Comments</h4>
                <form action="{{ route('comments.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $classwork->id }}">
                    <input type="hidden" name="type" value="classwork">
                    <div class="d-flex">
                        <div class="col-8">
                            <x-form.floating-control name="description">
                                <x-slot:label>
                                    <label for="description">Comment</label>
                                </x-slot:label>
                                <x-form.textarea name="content" placeholder="Comment"></x-form.textarea>
                            </x-form.floating-control>
                        </div>
                        <div class="ms-1">
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </div>
                    </div>
                </form>
                <div class="bg-light">
                    @foreach ($classwork->comments as $comment)
                        <div class="row m-lg-3 ">
                            <div class="col-md-2 ">
                                <div class="media-body mt-2">
                                    <img src="https://ui-avatars.com/api/?name={{ $comment->user->name }}&size=70&background=5EBEF5&color=fff"
                                         class="mr-3x` WqfsMd" alt="User Avatar">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="media ruTJle mt-2">
                                    <div class="media-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <a href="#" class="gJItbc asQXV">{{ $comment->user->name }}</a>
                                            <span
                                                    class="mr-4">{{ $comment->created_at->diffForHumans(null) }}</span>
                                            <div class="thiSD Gh0umc">
                                                <div class="dropdown">
                                                    {{-- <a class="btn btn-link" type="button" id="commentOptionsDropdown"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg focusable="false" width="24" height="24" viewBox="0 0 24 24"
                                                    class=" NMm5M">
                                                    <path
                                                        d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="commentOptionsDropdown">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Delete</a>
                                            </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="VSWCL tLDEHd mb-2">
                                            <span>{{ $comment->content }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                @can('submissions.create',[$classwork])
                <div class="bordered rounded p-3 bg-light">
                    <h4>Submissions</h4>
                    @if($submissions->count())
                        <ul>
                            @foreach($submissions as $submission)
                                <li><a href="{{ route('submissions.file',$submission->id) }}">File #{{ $loop->iteration }}</a></li>
                            @endforeach
                        </ul>
                    @else
                    <form action="{{ route('submissions.store',$classwork->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <x-form.floating-control name="files">
                            <x-slot:label>
                                <label for="files">Upload Files</label>
                            </x-slot:label>
                            <x-form.input type="file" name="files[]" multiple accept="image/*,application/pdf" placeholder="Select Files"></x-form.input>
                        </x-form.floating-control>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    @endif
                </div>
                @endcan
            </div>
        </div>
    </div>
</x-main-layout>
