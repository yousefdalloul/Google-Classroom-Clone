<x-main-layout title="Create Classwork">
    <div class= "container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>{{ $classwork->title }}</h3>
        <x-alert name="success" id="success" class="alert-success"></x-alert>
        <hr>
        <div>
            <p>{{ $classwork->discription }}</p>
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
        <div class="mt-4">
            @foreach ($classwork->comments as $comment)
                <div>
                    <div class="col-md-2">
                        <img src="">
                    </div>
                    <div class="col-md-10">
                            <p>By: {{ $comment->user->name }}. Time: {{ $comment->created_at->diffForHumans() }}</p>
                            <p>By: Unknown User. Time: {{ $comment->created_at }}</p>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-main-layout>
