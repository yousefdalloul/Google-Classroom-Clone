<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Classwork;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClassworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Classroom $classroom)
    {
        $classworks = $classroom->classworks()
            ->with('topic') // Eager loading
            ->filter($request->query())      //ScopeFilter
            ->latest('published_at','DEC')   //Query Builder
            // if ($request->search){
            //     $query->where('title','LIKE',"%{$request->search}%")
            //           ->orWhere('description','LIKE',"%{$request->search}%");
            // }
            // $classworks = $query->paginate(5);
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

        $type = $this->getType($request);
        $classwork = new Classwork();

        return view('classworks.create',compact('classroom','classwork','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Classroom $classroom)
    {
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
            });
        } catch (QueryException $e){
            return back()->with('error',$e->getMessage());
        }


        return redirect()->route('classrooms.classworks.index', ['classroom' => $classroom->id])
            ->with('success', 'Classwork created!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom, Classwork $classwork)
    {
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
        $type = $classwork->type;
        $assigned = $classwork->users()->pluck('id')->toArray();

        return view('classworks.edit',compact('classroom','classwork','assigned','type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, Classwork $classwork)
    {
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
            ->with('success','Classwork Update!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classwork $classwork)
    {
        //
    }
}
