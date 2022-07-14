@extends('layouts.layout')
@section('content')
<div class="card bg-secondary p-3 mt-2 mb-3">
    <div class="card p-3 mt-2 mb-3">
        <h2 class="h2">{{$form->title}}</h2>
        <h4 class="h4">Expires at: {{$form->expires_at}}</h4>
    </div>
    <div class="card p-3 mt-2 mb-3">
        <h3 class="h3">Questions</h3>
        @php
            $i=0;
        @endphp
        @foreach ($form->questions as $question)
            @php
                $i=$i+1;
            @endphp
            <div class="card p-3 mt-2 mb-3">
                <h4 class="h4">{{$i}}. Question: {{$question->question}}</h4>
                @if (count($question->choices)>0)
                    <div class="card p-3 mt-2 mb-3">
                        <h5 class="h5">Choices</h5>
                        @if (isset($questionsAnswer)
                            && array_key_exists('CHOICES',$questionsAnswer[$question['id']])
                            && $questionsAnswer[$question['id']]['CHOICES']!==null)
                            @foreach ($questionsAnswer[$question['id']]['CHOICES'] as $choiceKEY => $choicecounter )
                                <div class="card p-3 mt-2 mb-3">
                                    {{$question->choices->find($choiceKEY)->choice}} <strong>({{$choicecounter}})</strong>
                                </div>
                            @endforeach
                        @elseif(isset($questionsAnswer)
                            && array_key_exists('CHOICES',$questionsAnswer[$question['id']])
                            && $questionsAnswer[$question['id']]['CHOICES']===null)
                                @foreach ($question->choices as $choice )
                                <div class="card p-3 mt-2 mb-3">
                                    {{$choice->choice}}
                                </div>
                                @endforeach
                                <div class="p-3 mt-2 mb-3 alert alert-danger" role="alert">
                                    Nobody answered this question!
                                </div>
                        @endif

                    </div>
                @else
                    @if(isset($questionsAnswer) && array_key_exists($question['id'],$questionsAnswer))
                        @foreach ($questionsAnswer[$question['id']]['texts'] as $tkey =>$text)
                            <div class="alert alert-info fade show">
                                <strong>{{$users->find($tkey)!==null? $users->find($tkey)->name:$tkey}}</strong>
                                {{$text}}
                            </div>
                        @endforeach
                    @elseif(!array_key_exists($question['id'],$questionsAnswer))
                        <div class="p-3 mt-2 mb-3 alert alert-danger" role="alert">
                            Nobody answered this question!
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
