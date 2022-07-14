<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\User;
use App\Models\Question;
use App\Models\Choice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Form::where('created_by', '=', Auth::user()->id)->orderbyDesc('updated_at')->paginate(5);
        $uneditables=null;
        foreach ($forms as $key => $form) {
            foreach ($form->questions as $qkey => $question) {
                if(count($question->answers->toArray())>0){
                    $uneditables[$form['id']]=true;
                }
            }
        }
        // dump("uneditables:");
        // dump($uneditables);
        return view('site.forms', ['forms' => $forms, 'uneditables' => $uneditables]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('site.new-form');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->input('questions'));
        $validated = $request->validate([
            'title' => 'string|required',
            'auth_required' => 'integer|min:0|max:1|required',
            'expires_at' => 'date|after:tomorrow|required',
            'questions' => 'required',
            'questions.*.question' => 'required|string',
            'questions.*.answer_type' => 'integer|min:0|max:2|required',
            'questions.*.required' => 'integer|min:0|max:1|required',
            'questions.*.choices' => 'required_if:questions.*.answer_type,==,1|required_if:questions.*.answer_type,==,2|min:2',
            'questions.*.choices.*.choice' => 'required_if:questions.*.answer_type,==,1|required_if:questions.*.answer_type,==,2|string',
        ]);
        $validated['expires_at']=Carbon::parse($validated['expires_at'])->format('d-m-Y H:i:s');
        $form=new Form($validated);
        $form->user()->associate(Auth::user());
        $form->save();
        foreach ($validated['questions'] as $q_key => $question) {
            if($question['answer_type'] ==0){
                $question['answer_type']='TEXTAREA';
            }
            else if($question['answer_type'] ==1){
                $question['answer_type']='ONE_CHOICE';
            }
            else if($question['answer_type']==2){
                $question['answer_type']='MULTIPLE_CHOICE';
            }
            $_question=new Question($question);
            $_question->form()->associate($form);
            $_question->save();
            if(array_key_exists('choices',$question)){
                foreach ($question['choices'] as $c_key => $choice) {
                    $choice=new Choice($choice);
                    $choice->question()->associate($_question);
                    $choice->save();
                }
            }
        }
        return redirect()->route('forms.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form= Form::findOrFail($id);
        if ($form->created_by!==Auth::id()) {
            abort(401);
        }
        $users= User::all();
        $questionsAnswer=null;
        foreach ($form->questions as $key => $question) {
            if($question['answer_type']==='TEXTAREA'){
                    $i=0;
                foreach ($question->answers as $akey => $answer){
                    $textID=$answer['user_id'];
                    if($answer['user_id']===null){
                        $i=$i+1;
                        $textID='Guest_'.$i;
                    }
                    $questionsAnswer[$question['id']]['texts'][$textID]=$answer['answer'];
                }
            }
            else{
                foreach($question->choices as $ckey => $choice){
                    $questionsAnswer[$question['id']]['CHOICES'][$choice['id']]=0;
                }
                foreach ($question->answers as $akey => $answer){
                    if(!array_key_exists($answer['choice_id'],$questionsAnswer)){
                        $questionsAnswer[$question['id']]['CHOICES'][$answer['choice_id']]=$questionsAnswer[$question['id']]['CHOICES'][$answer['choice_id']]+1;
                    }
                }
                arsort($questionsAnswer[$question['id']]['CHOICES']);
                $noanswer=true;
                foreach($question->choices as $ckey => $choice){
                    $noanswer = $noanswer && $questionsAnswer[$question['id']]['CHOICES'][$choice['id']]==0;
                }
                if($noanswer){
                    $questionsAnswer[$question['id']]['CHOICES']=null;
                }
            }
        }
        return view('site.statics',['form'=>$form,'users'=>$users, 'questionsAnswer'=>$questionsAnswer]);
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);
        if ($form->created_by!==Auth::id()) {
            abort(401);
        }
        return view('site.modify',['form'=> $form]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'string|required',
            'auth_required' => 'integer|min:0|max:1|required',
            'expires_at' => 'date|after:tomorrow|required',
            'questions' => 'required',
            'questions.*.question' => 'required|string',
            'questions.*.answer_type' => 'integer|min:0|max:2|required',
            'questions.*.required' => 'integer|min:0|max:1|required',
            'questions.*.choices' => 'required_if:questions.*.answer_type,==,1|required_if:questions.*.answer_type,==,2',
            'questions.*.choices.*.choice' => 'required_if:questions.*.answer_type,==,1|required_if:questions.*.answer_type,==,2|string',
        ]);
        $validated['expires_at']=Carbon::parse($validated['expires_at'])->format('d-m-Y H:i:s');
        $form = Form::findOrFail($id);
        if ($form->created_by!==Auth::id()) {
            abort(401);
        }
        $form->update($validated);
        foreach ($form->questions as $question) {
            if(count($question->choices->toArray())>0){
                foreach ($question->choices as $choice) {
                    $choice->question()->dissociate();
                    $choice->delete();
                }
            }
            $question->form()->dissociate();
            $question->delete();
        }
        foreach ($validated['questions'] as $q_key => $question) {
            if($question['answer_type'] ==0){
                $question['answer_type']='TEXTAREA';
            }
            else if($question['answer_type'] ==1){
                $question['answer_type']='ONE_CHOICE';
            }
            else if($question['answer_type']==2){
                $question['answer_type']='MULTIPLE_CHOICE';
            }
            $_question=new Question($question);
            $_question->form()->associate($form);
            $_question->save();
            if(array_key_exists('choices',$question)){
                foreach ($question['choices'] as $c_key => $choice) {
                    $choice=new Choice($choice);
                    $choice->question()->associate($_question);
                    $choice->save();
                }
            }
        }
        return redirect()->route('forms.index');
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
