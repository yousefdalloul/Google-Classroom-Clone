<?php

namespace App\Http\Controllers;

use App\Enums\ClassworkType;
use App\Events\ClassworkCreated;
use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Classroom $classroom)
    {
        $this->authorize('view-any', [classwork::class, $classroom]);

        $classworks = $classroom->classworks()
            ->with('topic') // Eager loading
            ->withCount([
                'users as assigned_count' => function ($query) {
                    $query->where('classwork_user.status', '=', 'assigned');
                },
                'users as turnedin_count' => function ($query) {
                    $query->where('classwork_user.status', '=', 'submitted');
                },
                'users as graded_count' => function ($query) {
                    $query->whereNotNull('classwork_user.grade');
                },
            ])
            ->filter($request->query())      //ScopeFilter
            ->latest('published_at','DEC')   //Query Builder
            // if ($request->search){
            //     $query->where('title','LIKE',"%{$request->search}%")
            //           ->orWhere('description','LIKE',"%{$request->search}%");
            // }
            // $classworks = $query->paginate(5);
            ->where(function ($query) {
                $query->WhereHas('users', function ($query) {
                    $query->where('id', '=', Auth::id());
                })
                    ->orWhereHas('classroom.teachers', function ($query) {
                        $query->where('id', '=', Auth::id());
                    });
            })
            /* ->where(function ($query) {
                $query->whereRaw('EXISTS (SELECT 1 FROM classwork_user
                WHERE classwork_user.classwork_id = classworks.id
                AND classwork_user.user_id = ?
                )', [
                    Auth::id()
                ]);
                $query->orWhereRaw('EXISTS (SELECT 1 FROM classroom_user
                WHERE classroom_user.classroom_id = classworks.classroom_id
                AND classroom_user.user_id = ?
                AND classroom_user.role = ?
                )', [
                    Auth::id(),
                    'teacher',
                ]);
            })*/
            ->paginate();


        //dd($classworks->groupBy('topicid'));

        return view('classworks.index',[
            'classroom'=>$classroom,
            'classworks'=>$classworks,
        ]);
    }

    public function getType(Request $request)
    {
        $type = $request->query('type');
        $allowed_types = [
            Classwork::TYPE_ASSIGNMENT,Classwork::TYPE_MATERIAL,Classwork::TYPE_QUESTION,
        ];

        if (!in_array($type,$allowed_types)){
            $type = Classwork::TYPE_ASSIGNMENT;

        }
        return $type;

        try {
            return ClassworkType::from($request->query('type'));
        }catch (ValueError $e){
            return Classwork::TYPE_ASSIGNMENT;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Classroom $classroom, classwork $classwork)
    {
//        if (!Gate::allows('classworks.create', [$classroom])) {
//            abort(403);
//        } // excute Authorization
        $this->authorize('create', [classwork::class, $classroom]);

//        $response = Gate::inspect('classworks.create',[$classroom]);
//        if (!$response->allowed()){
//            abort(403,$response->message());
//        }
//        Gate::authorize('classworks.create',[$classroom]);

//        if (!Gate::allows('classworks.create',[$classroom])){
//            abort(403);
//        }

        $type = $this->getType($request);
        $classwork = new classwork();
        $classworks = classwork::all();

        return view('classworks.create',compact('classroom','classwork','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
        $this->authorize('create',[Classwork::class,$classroom]);

//        if (Gate::denies('classworks.create',[$classroom])){
//            abort(403);
//        }
        $type = $this->getType($request);

        $request->validate([
            'title' => ['required', 'nullable', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'type' => ['required', 'in:assignment,material,question'],
            'options.grade' => [Rule::requiredIf(fn()=>$type == 'assignment'),'numeric','min:0'],
            'options.due' => ['nullable','date','after:published_at'],
        ]);

        $request->merge([
            'user_id' => Auth::id(),
            'type' => $type,
            'classroom_id' => $classroom->id,
        ]);


        try {
            DB::transaction(function () use ($classroom, $request, $type) {
                $classwork = $classroom->classworks()->create($request->all());

//                $studentIds = $request->input('students');
//                $existingStudentIds = $classroom->users->pluck('id')->toArray();
//
//                // Filter out students that are already attached to the classroom
//                $newStudentIds = array_diff($studentIds, $existingStudentIds);
//
//                // Attach the remaining students
//                $classroom->users()->attach($newStudentIds);

                $classwork->users()->attach($request->input('students'));
                //event(new ClassworkCreated($classwork));

                ClassworkCreated::dispatch($classwork);


            });
        } catch (\Exception $e){
            return back()->with('error',$e->getMessage());
        }

        return redirect()->route('classrooms.classworks.index', ['classroom' => $classroom->id])
            ->with('success', __('Classwork created!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, classwork $classwork)
    {
        $this->authorize('view', $classwork);
        // Gate::authorize('classworks.create', [$classwork]);


        $submissions = Auth::user()
            ->submissions()
            ->where('classwork_id', $classwork->id)
            ->get();
        // if(){}
        // $invitation_link  = URL::signedRoute('classworks.link', [
        //     // $invitation_link  = URL::temporarySignedRoute('classrooms.join', now()->addHours(3) ,[
        //     'classroom' => $classroom->id,
        //     'classwork' => $classwork->id,
        // ]);
        $classwork->load('comments.user');

        return View::make('classworks.show', compact('classroom', 'classwork', 'submissions'));
        // ->with([
        //     'invitation_link' => $invitation_link,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Classroom $classroom, classwork $classwork)
    {
        // $this->authorize('update', $classwork);
        $classwork = $classroom->classworks()
            ->findOrFail($classwork->id);
        $type = $classwork->type->value;

        $assigned = $classwork->users()
            ->pluck('id')
            ->toArray(); // تحول العنصر لاوبجكت

        return view('classworks.edit', compact('classroom', 'classwork', 'type', 'assigned'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, classwork $classwork)
    {

        // $this->authorize('update', $classwork);
        $type = $classwork->type;

        $validate =  $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            // 'student' => ['nullable'],
            'options.grade' => [Rule::requiredIf(fn () => $type == 'assignment' || 'question'), 'numeric', 'min:0'],
            'options.due' => ['nullable', 'date', 'after:published_at'],
        ]);

        $classwork->update($request->all());
        return View::make('classworks.show', compact('classroom', 'classwork'))
            ->with('success', __('Classwork Updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, classwork $classwork)
    {
        // $this->authorize('delete', $classwork);
        $classwork->delete();

        return redirect()->route('classrooms.classworks.index', $classroom->id)
            ->with('success', 'Classwork deleted');
    }
}
