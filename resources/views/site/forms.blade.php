@extends('layouts.layout')

@section('title', 'Forms')
@section('content')
<div class="container card p-3 mt-2 mb-3">
    <h1 class="h1">My Forms</h1>
    @foreach ($forms as $form )
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

                                    @foreach ($question->choices as $choice )
                                        <div class="card p-3 mt-2 mb-3">
                                            {{$choice->choice}}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                    <div class="mb-3">
                        @if (isset($uneditables) && array_key_exists($form->id, $uneditables))
                            <div class="alert alert-danger" role="alert">
                                You are not allowed to edit this form, because it has already been answered by another user!
                            </div>
                        @else
                            <button type="button" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                <a href="{{route('forms.edit',['form'=>$form->id])}}">Modify this form</a>
                            </button>
                        @endif

                    </div>
                    <div class="mb-3">
                        @if (isset($uneditables) && !array_key_exists($form->id, $uneditables) || $uneditables===null)
                            <div class="alert alert-danger" role="alert">
                                Statics are not available, because nobody filled this form!
                            </div>
                        @else
                            <button type="button" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                <a href="{{route('forms.show',['form'=>$form->id])}}">Form's statics</a>
                            </button>
                        @endif

                    </div>
            </div>
    @endforeach
            {{$forms->links()}}
</div>
@endsection
