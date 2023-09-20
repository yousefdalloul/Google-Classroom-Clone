<x-main-layout :title="$classroom->name">
    <div class="container">
        <h1>{{ $classroom->name }} (#{{ $classroom->id }})</h1>
        <h3>Classwork
            @can('create', ['App\\Models\classwork', $classroom])
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle shadow-primary" type="button" id="dropdownMenuButton1"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        + Create
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item"
                               href="{{ route('classrooms.classworks.create', ['classroom' => $classroom->id, 'type' => 'assignment']) }}">Assignment</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('classrooms.classworks.create', ['classroom' => $classroom->id, 'type' => 'question']) }}">Question</a>
                        </li>
                        <li><a class="dropdown-item"
                               href="{{ route('classrooms.classworks.create', ['classroom' => $classroom->id, 'type' => 'material']) }}">Material</a>
                        </li>
                    </ul>
                </div>
            @endcan
        </h3>
        <hr>
        <form action="{{ URL::current() }}" method="get" class="row row-cols-lg-auto g-3 align-items-center">
            <div class="col-12">
                <input type="text" placeholder="Search..." name="search" class="form-control">
            </div>
            <div class="col-12">
                <button class="btn btn-primary ms-2" type="submit">Find</button>
            </div>
        </form>

{{--                <h3>{{ $group->first()->topic->name }}</h3>--}}
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach($classworks as $classwork)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $classwork->id }}" aria-expanded="false" aria-controls="flush-collapse{{ $classwork->id }}">
                                    {{ $classwork->title }}
                                </button>
                            </h2>
                            <div id="flush-collapse{{ $classwork->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    {!! $classwork->description !!}
                                    <div>
                                        <a class="btn btn-sm btn-outline-dark" href="{{ route('classrooms.classworks.show',[$classwork->classroom_id,$classwork->id]) }}">View</a>
                                        <a class="btn btn-sm btn-outline-dark" href="{{ route('classrooms.classworks.edit',[$classwork->classroom_id,$classwork->id]) }}">Edit</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
{{--        @empty--}}
{{--            <p class="text-center fs-3">No classworks found.</p>--}}
{{--        @endforelse--}}
        {{ $classworks->withQueryString()->appends(['v1'=>1,])->links() }}
    </div>

    @push('scripts')
        <script>
             classroomId = "{{ $classwork->classroom_id }}";
        </script>
    @endpush

</x-main-layout>
