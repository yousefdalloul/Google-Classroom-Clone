<x-alert name="error" type="danger"/>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $message)
            <li>{{$message}}</li>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <x-form.floating-control name="title">
            <x-slot:label>
                <label for="title">Title</label>
            </x-slot:label>
            <x-form.input name="title" :value="$classwork->title" placeholder="Title"></x-form.input>
        </x-form.floating-control>
        <x-form.floating-control name="description">
            <x-slot:label>
                <label for="description">Description (Optional)</label>
            </x-slot:label>
            <x-form.textarea name="description" :value="$classwork->description" placeholder="Description (Optional)"></x-form.textarea>
        </x-form.floating-control>
    </div>
    <div class="col-md-4">
        <x-form.floating-control name="published_at">
            <x-slot:label>
                <label for="published_at">Published Date</label>
            </x-slot:label>
            <x-form.input name="published_at" :value="$classwork->published_data" type="date"></x-form.input>
        </x-form.floating-control>
        <div class="mb-3">
        @foreach($classroom->students as $student)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="students[]" value="{{ $student->id }}" id="std-{{ $student->id }}" @checked(in_array(!isset($assigned) || $student->id,$assigned ?? []))>
                <label class="form-check-label" for="std-{{ $student->id }}">
                    {{ $student->name }}
                </label>
            </div>
        @endforeach
        </div>
        @if($type == 'assignment')
            <x-form.floating-control name="options.grade">
                <x-slot:label>
                    <label for="grade">Grade</label>
                </x-slot:label>
                <x-form.input name="options[grade]" :value="$classwork->options['grade'] ?? ''" type="number" min="0"></x-form.input>
            </x-form.floating-control>
            <x-form.floating-control name="options.due">
                <x-slot:label>
                    <label for="due">Due</label>
                </x-slot:label>
                <x-form.input name="options[due]" :value="$classwork->options['due'] ?? ''" type="date"></x-form.input>
            </x-form.floating-control>
        @endif

        <x-form.floating-control name="topic_id">
            <x-slot:label>
                <label for="topic_id">Topic (Optional)</label>
            </x-slot:label>
            <select class="form-select" name="topic_id" id="topic_id">
                @foreach ($classroom->topics as $topic)
                    <option @selected($topic->id == $classwork->topic_id) value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
                <option value="">No Topic</option>
            </select>
        </x-form.floating-control>

    </div>
</div>


@push('scripts')
    <script src="https://cdn.tiny.cloud/1/j9d86off54egxj7zvcozx5t096g6ikbr4a224tr5t1e4lt1y/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#description',
            plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
@endpush
