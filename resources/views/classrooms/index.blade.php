<x-main-layout :title="__('Classroom')">
    <div class= "container">
        <h1>{{__('Classrooms')}}</h1>

        <x-alert name="success" id="success" class="alert-success"></x-alert>
        <x-alert name="error" id="error" class="alert-danger"></x-alert>

        <div class="row">
            @foreach($classrooms as $classroom)
                <div class="col-md-3">
                    <div class="card">
                            <img src="{{ $classroom->cover_image_url }}" class="card-img-top" alt="">

                            <div class="card-body">
                            <h5 class="card-title">{{ $classroom->name }}</h5>
                            <p class="card-text">{{ $classroom->section }} - {{ $classroom->room }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ $classroom->url }}" class="btn btn-primary">{{__('View')}}</a>
                                <a href="{{ route('classrooms.edit',$classroom->id) }}" class="btn btn-sm btn-dark">{{__('Edit')}}</a>
                                <form action="{{ route('classrooms.destroy',$classroom->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">{{__('Delete')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            fetch('/api/v1/classrooms')
                .then(res => res.json())
                .then(json => {
                  let ul = document.getElementById('classrooms');
                  for(let i in json.data){
                      ul.innerHTML += `<li>${json.data[i].name}</li>`
                  }
                })
        </script>
    @endpush

</x-main-layout>