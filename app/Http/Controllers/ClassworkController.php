<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Classroom $classroom)
    {
        $this->authorize('viewAny',[Classwork::class,$classroom]);

        $classworks = $classroom->classworks()
            ->with('topic') // Eager loading
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
            ->paginate(5);


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
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,Classroom $classroom)
    {
        $this->authorize('create',[Classwork::class,$classroom]);

//        $response = Gate::inspect('classworks.create',[$classroom]);
//        if (!$response->allowed()){
//            abort(403,$response->message());
//        }
//        Gate::authorize('classworks.create',[$classroom]);

//        if (!Gate::allows('classworks.create',[$classroom])){
//            abort(403);
//        }

        $type = $this->getType($request);
        $classwork = new Classwork();

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
            'type' => $type->value,
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
            });
        } catch (QueryException $e){
            return back()->with('error',$e->getMessage());
        }

        return redirect()->route('classrooms.classworks.index', ['classroom' => $classroom->id])
            ->with('success', __('Classwork created!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('view',$classwork);
//        Gate::authorize('classworks.view',[$classwork]);

        $submissions = Auth::user()
            ->submissions()
            ->where('classwork_id',$classwork->id)
            ->get();

        //$classwork->load('comments.user');
        return view('classworks.show',compact('classroom','classwork','submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update',$classwork);
        $type = $classwork->type->value;

        $assigned = $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit',compact('classroom','classwork','assigned','type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('update',$classwork);

        $type = $classwork->type;

        $request->validate([
            'title' => ['required', 'nullable', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable', 'int', 'exists:topics,id'],
            'type' => ['required', 'in:assignment,material,question'],
            'options.grade' => [Rule::requiredIf(fn()=>$type == 'assignment'),'numeric','min:0'],
            'options.due' => ['nullable','date','after:published_at'],
        ]);

        $classwork->update($request->all());
        $classwork->users()->sync($request->input('students'));

        return back()
            ->with('success',__('Classwork Update!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, Classwork $classwork)
    {
        $this->authorize('delete',$classwork);
    }
}
