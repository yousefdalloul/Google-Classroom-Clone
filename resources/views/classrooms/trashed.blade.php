<x-main-layout title="Classroom Trashed">
    <div class= "container">
        <h1>Classrooms Trashed</h1>

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
                                <form action="{{ route('classrooms.restore',$classroom->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-sm btn-success"> Restore </button>
                                </form>
                                <form action="{{ route('classrooms.force-delete',$classroom->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Force Delete</button>
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