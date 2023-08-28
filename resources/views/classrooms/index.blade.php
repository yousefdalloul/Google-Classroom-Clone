<x-main-layout title="Classroom">
    <div class= "container">
        <h1>Classrooms</h1>

        <x-alert name="success" id="success" class="alert-success"></x-alert>
        <x-alert name="error" id="error" class="alert-danger"></x-alert>

        <div class="row">
            @foreach($classrooms as $classroom)
                <div class="col-md-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $classroom->cover_image_path) }}" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{ $classroom->name }}</h5>
                            <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('classrooms.show',$classroom->id) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('classrooms.edit',$classroom->id) }}" class="btn btn-sm btn-dark">Edit</a>
                                <form action="{{ route('classrooms.destroy',$classroom->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>console.log('@@stack')</script>
    @endpush

</x-main-layout>