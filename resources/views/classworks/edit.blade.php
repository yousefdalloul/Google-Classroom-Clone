<x-main-layout title="Create Classwork">
    <div class= "container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>Update Classwork</h3>
        <x-alert name="success" id="success" class="alert-success"></x-alert>
        <hr>
        <form action="{{ route('classrooms.classworks.update', ['classroom' => $classroom->id, 'classwork' => $classwork->id, 'type' => $type]) }}" method="post">
            @csrf
            @method('put')
            @include('classworks._form')

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</x-main-layout>
