<x-alert name="error" id="error" class="alert-danger"></x-alert>

<x-form.floating-control name="name" placeholder="Classroom Name">
    <x-form.input name="name" value="{{ $classroom->name }}" placeholder="Classroom Name"></x-form.input>
    <x-slot:label>
        <label for="name">Classroom Name</label>
    </x-slot:label>
</x-form.floating-control>

<x-form.floating-control name="name" placeholder="Classroom Section">
    <x-form.input name="section" value="{{ $classroom->section }}" placeholder="Classroom Section"></x-form.input>
    <x-slot:label>
        <label for="section">Classroom Section</label>
    </x-slot:label>
</x-form.floating-control>

<x-form.floating-control name="name" placeholder="Classroom Subject">
    <x-form.input name="subject" value="{{ $classroom->subject }}" placeholder="Classroom Subject"></x-form.input>
    <x-slot:label>
        <label for="subject">Classroom Subject</label>
    </x-slot:label>
</x-form.floating-control>

<x-form.floating-control name="name" placeholder="Classroom Room">
    <x-form.input name="room" value="{{ $classroom->room }}" placeholder="Classroom Room"></x-form.input>
    <x-slot:label>
        <label for="room">Classroom Room</label>
    </x-slot:label>
</x-form.floating-control>

<x-form.floating-control name="name" placeholder="Classroom Image">
    @if($classroom->cover_image_path)
    <img src="{{ asset('storage/' . $classroom->cover_image_path) }}" alt="">
    @endif
    <x-form.input type="file" name="cover_image" value="{{ $classroom->cover_image }}" placeholder="Classroom Image"></x-form.input>
        <x-slot:label>
            <label for="cover_image">Classroom Image</label>
        </x-slot:label>
</x-form.floating-control>
<button type="submit" class="btn btn-primary">{{ $button_label }}</button>