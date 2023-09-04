<x-main-layout title="Create Classwork">
    <div class= "container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>Create Classwork</h3>
        <hr>
        <form action="{{ route('classrooms.classworks.store', [$classroom->id, 'type' => $type]) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <x-form.floating-control name="title">
                        <x-slot:label>
                            <label for="title">Title</label>
                        </x-slot:label>
                        <x-form.input name="title" placeholder="Title"></x-form.input>
                    </x-form.floating-control>
                    <x-form.floating-control name="description">
                        <x-slot:label>
                            <label for="description">Description (Optional)</label>
                        </x-slot:label>
                        <x-form.input name="description" placeholder="Description (Optional)"></x-form.input>
                    </x-form.floating-control>
                </div>
                <div>
                    @foreach($classroom->students as $student)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" id="std-{{ $student->id }}" checked>
                            <label class="form-check-label" for="std-{{ $student->id }}">
                                {{ $student->name }}
                            </label>
                        </div>
                    @endforeach

                </div>
                <div class="col-md-4">
                    <x-form.floating-control name="topic_id">
                        <x-slot:label>
                            <label for="topic_id">Topic (Optional)</label>
                        </x-slot:label>
                        <select class="form-select" name="topic_id" id="topic_id">
                            <option value="">No Topic</option>
                            @foreach ($classroom->topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                        <x-form.error name="topic_id"></x-form.error>
                    </x-form.floating-control>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</x-main-layout>
