@php
use Carbon\Carbon;
@endphp

@extends('layouts.layout')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuid.min.js"
        integrity="sha512-UNM1njAgOFUa74Z0bADwAq8gbTcqZC8Ej4xPSzpnh0l6KMevwvkBvbldF9uR++qKeJ+MOZHRjV1HZjoRvjDfNQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
@section('title', 'New Form')
@section('content')
    <form action="{{ route('forms.update', ['form' => $form->id]) }}" method="POST">
        @csrf
        @isset($form)
            @method('put')
        @endisset
        <div class="card p-3 mt-2 mb-3">
            <div class="form-group mb-3">
                <input type="text" class="form-control  @error('title') is-invalid @enderror" placeholder="Title"
                    name="title" id="title"
                    @isset($form) value="{{ old('title', $form->title) }}"
                    @else
                        value="{{ old('title') }}" @endisset />
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group row mb-3">
                <div class="col">
                    <label for="authrequired">Login required?</label>
                    <select class="form-select @error('auth_required') is-invalid @enderror" name="auth_required"
                        id="authrequired"
                        @isset($form) value="{{ old('title', $form->auth_required) }}"
                    @else
                        value="{{ old('title') }}" @endisset">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="expires_at">Expires at</label>
                <input type="datetime-local" name="expires_at"
                    class="form-control  @error('expires_at') is-invalid @enderror"
                    @isset($form) value="{{ old('expires_at', Carbon::parse($form->expires_at)->format('Y-m-d\TH:i')) }}"
                    @else
                        value="{{ old('expires_at') }}" @endisset">
                @error('expires_at')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <h2>Questions</h2>

        <div class="card p-3 mt-2 mb-3">

            <div id="groups">
                @if ((isset($form) && $form->questions->toArray() !== null) || old('questions') !== null)
                    @php
                        $groups = isset($form) ? old('questions', $form->questions->toArray()) : old('questions');
                    @endphp
                    @foreach ($groups as $question_key => $question)
                        @php
                            if (array_key_exists('id', $question)) {
                                $question_key = $question['id'];
                            }
                        @endphp
                        <div class="mb-3" id="group_{{ $question_key }}">
                            <h4>New Question</h4>
                            <div class="card p-3 mt-2 mb-3">
                                <div class="form-group mb-3">
                                    <input type="text"
                                        class="form-control @if (count($errors->get('questions.' . $question_key . '.question')) >= 1) ) is-invalid @endif"
                                        id="title_{{ $question_key }}" name="questions[{{ $question_key }}][question]"
                                        placeholder="Question"
                                        value="{{ array_key_exists('question', $question) ? $question['question'] : '' }}" \>
                                    @if (count($errors->get('questions.' . $question_key . '.question')) >= 1)
                                        <div class="invalid-feedback">
                                            {{ $errors->get('questions.' . $question_key . '.question')[0] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="selector_{{ $question_key }}">Answer type:</label>
                                    <select id="selector_{{ $question_key }}"
                                        name="questions[{{ $question_key }}][answer_type]"
                                        class="form-select  @if (count($errors->get('questions.' . $question_key . '.answer_type')) >= 1) ) is-invalid @endif">
                                        <option value="x" disabled @if (!array_key_exists('answer_type', $question)) selected @endif>
                                            Choose one
                                        </option>
                                        <option value="0" @if (array_key_exists('answer_type', $question) && $question['answer_type'] === '0') selected @endif>Textarea
                                        </option>
                                        <option value="1" @if (array_key_exists('answer_type', $question) && $question['answer_type'] === '1') selected @endif>One
                                            Choice
                                        </option>
                                        <option value="2" @if (array_key_exists('answer_type', $question) && $question['answer_type'] === '2') selected @endif>Multiple
                                            Choice
                                        </option>
                                    </select>

                                    @if (count($errors->get('questions.' . $question_key . '.answer_type')) >= 1)
                                        <div class="invalid-feedback">
                                            {{ $errors->get('questions.' . $question_key . '.answer_type')[0] }}
                                        </div>
                                    @endif

                                </div>
                                <div class="form-group mb-3">
                                    <label for="required_{{ $question_key }}">Has to be filled:</label>
                                    <select id="required_{{ $question_key }}"
                                        name="questions[{{ $question_key }}][required]"
                                        class="form-select  @if (count($errors->get('questions.' . $question_key . '.required')) >= 1) ) is-invalid @endif"
                                        value="{{ old('questions.' . $question_key . '.required') }}">
                                        <option value="1" @if (array_key_exists('required', $question) && $question['required'] === '1') selected @endif>Yes
                                        </option>
                                        <option value="0" @if (array_key_exists('required', $question) && $question['required'] === '0') selected @endif>No
                                        </option>
                                    </select>
                                    @if (count($errors->get('questions.' . $question_key . '.required')) >= 1)
                                        <div class="invalid-feedback">
                                            {{ $errors->get('questions.' . $question_key . '.required')[0] }}
                                        </div>
                                    @endif
                                </div>
                                @if(array_key_exists('choices',$question))
                                    <div id="choices_group_{{ $question_key }}">
                                        @foreach (count($question->choices)> 0 ? old('questions.' . $question_key . '.choices', $question->choices) : old('questions.' . $question_key . '.choices') as $choice_key => $choice)
                                            @php
                                                if (array_key_exists('id', $choice)) {
                                                    $choice_key = $choice['id'];
                                                }
                                            @endphp
                                            <div class="card p-3 mt-2 mb-2"
                                                id="choice_{{ $question_key }}_{{ $choice_key }}">
                                                <div class="form-group mb-3">
                                                    <input type="text"
                                                        class="form-control @if (count($errors->get('questions.' . $question_key . '.choices.' . $choice_key . '.choice')) >= 1) is-invalid @endif"
                                                        id="textinput_{{ $choice_key }}"
                                                        name='questions[{{ $question_key }}][choices][{{ $choice_key }}][choice]'
                                                        placeholder="Choice"
                                                        value="{{ $choice['choice'] !== null ? $choice['choice'] : '' }}">
                                                    @if (count($errors->get('questions.' . $question_key . '.choices.' . $choice_key . '.choice')) >= 1)
                                                        <div class="invalid-feedback">
                                                            {{ $errors->get('questions.' . $question_key . '.choices.' . $choice_key . '.choice')[0] }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="delete-choice btn btn-danger"
                                                        data-group-id="{{ $choice_key }}">Delete choice</button>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div
                                            class="@if ($form->questions->get($question_key) !== null &&
                                                array_key_exists('answer_type', $form->questions->get($question_key)) &&
                                                ($form->questions->get($question_key)['answer_type'] === '0' ||
                                                    ($form->questions->find($question_key)['answer_type'] === '1' &&
                                                        count($form->questions->find($question_key)->choices->toArray()) > 0))) invisible @endif d-flex justify-content-center mb-3">
                                            <button type="button"
                                                class="mb-2 btn btn-secondary @if (count($errors->get('questions.' . $question_key . '.choices')) >= 1) btn-danger @endif"
                                                id="add-choice_group_{{ $question_key }}">New Choice</button>
                                            @if (count($errors->get('questions.' . $question_key . '.choices')) >= 1)
                                                <div class="d-flex justify-content-center alert alert-danger">
                                                    You need to add at least one choice!
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center mb-3">
                                    <button type="button" class="delete-group btn btn-danger"
                                        data-group-id="{{ $question_key }}">Delete question</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            <div class="d-flex justify-content-center ">

                <button type="button"
                    class="mb-2 btn d-flex justify-content-center btn-secondary @error('questions') btn-danger is-invalid @enderror"
                    id="add-group">New Question</button>
                @error('questions')
                    <div class="d-flex justify-content-center alert alert-danger">
                        You need to add at least one question!
                    </div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-secondary">Save Form</button>
        </div>
    </form>

    <script>
        // Template a group-hoz
        const template = `
        <div class="mb-3" id="group_#ID#">
            <h4>New question</h4>
            <div class="card p-3 mt-2 mb-3">
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="title_#ID#" name="questions[#ID#][question]" placeholder="Question">
                </div>
                <div class="form-group mb-3">
                    <label for="selector_#ID#">Answer type:</label>
                    <select id="selector_#ID#" name="questions[#ID#][answer_type]" class="form-select">
                        <option value="" disabled selected>Choose one</option>
                        <option value="0">Text</option>
                        <option value="1">One Choice</option>
                        <option value="2">Multiple Choice</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                                <label for="required_#ID#">Has to be filled:</label>
                                <select id="required_#ID#" name="questions[#ID#][required]" class="form-select">
                                    <option value="1" >Yes</option>
                                    <option value="0" >No</option>
                                </select>
                            </div>
                <div id="choices_group_#ID#">
                    <div class=" invisible mb-2 d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" id="add-choice_group_#ID#">New Choice</button>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="delete-group btn btn-danger" data-group-id="#ID#">Delete question</button>
                </div>
            </div>
        </div>
    `;
        const choiceTemplate = `
            <div class="card p-3 mt-2 mb-3" id="choice_#ID#_#ID2#">
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="Choice_textinput_#ID2#" name="questions[#ID#][choices][#ID2#][choice]" placeholder="Choice">
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="delete-choice btn btn-danger" id="delete_choice_#ID2#" data-group-id="#ID2#">Delete choice</button>
                </div>
            </div>
    `;
        const groups = document.querySelector('#groups');
        const addGroup = document.querySelector('button#add-group');
        addGroup.addEventListener('click', (event) => {

            let group = document.createElement("div");
            let _id = uuid.v4();
            group.innerHTML = template.replaceAll('#ID#', _id);
            groups.appendChild(group);

        });
        // ??ltal??nos esem??ny, mivel a delete-group-okat dinamikusan adjuk hozz??
        document.addEventListener('click', (event) => {
            console.log(event.target);
            if (event.target && event.target.classList.contains('delete-group')) {
                //console.log(event.target.dataset);
                const group = document.querySelector(`#group_${event.target.dataset.groupId}`);
                group.remove();
            } else if (event.target && event.target.id.includes('selector')) {
                let id = event.target.id.split('_')[1]
                console.log(event);
                if (event.target.value == 0) {

                    //new choice
                    let choices = document.querySelector(`#choices_group_${id}`);
                    Array.from(choices.children).forEach(element => {
                        console.log(element);
                        if (element.children[0] != null && !element.children[0].id.includes(
                                "add-choice_")) {
                            console.log(element);
                            element.remove();
                        } else {
                            console.log(element);
                            element.className = element.className.replaceAll(' visible', ' invisible');
                        }
                    });
                } else {
                    let choices = document.querySelector(`#choices_group_${id}`);
                    Array.from(choices.children).forEach(element => {
                        console.log(element);
                        if (element.children[0] != null && !element.children[0].id.includes(
                                "add-choice_")) {
                            console.log(element);
                            element.remove();
                        } else {
                            console.log(element);
                            element.className = element.className.replaceAll(' invisible', ' visible');
                        }
                    });
                }
            } else if (event.target && event.target.className.includes("delete-choice")) {
                let BTNid = event.target.id.split('_')[2];
                let choice = event.target.parentElement;
                if (choice.parentElement.classList.length > 0) {
                    choice = choice.parentElement;
                }
                console.log(choice);
                let question_key = choice.id.split('_')[1];
                choice.remove();
            } else if (event.target && event.target.id.includes("add-choice_group_")) {
                //new choice
                let questionid = event.target.id.split('_')[2];
                let choice = document.createElement('div');
                let _id = uuid.v4();
                choice.innerHTML = choiceTemplate.replaceAll('#ID2#', _id);
                choice.innerHTML = choice.innerHTML.replaceAll('#ID#', questionid);
                let BTNid = event.target.id.split('_')[2];
                let choices_group = document.querySelector(`#choices_group_${BTNid}`);
                choices_group.insertBefore(choice, event.target.parentElement);
            }
        });
        let buttons = document.querySelectorAll('button');
        Array.from(buttons).forEach(element => {
            if (element.id.includes('add-choice_group_')) {
                let id = element.id.split('_')[2];
                let selector = document.querySelector(`#selector_${id}`);
                if (selector.value != 0) {
                    element.parentElement.className = element.parentElement.className.replaceAll(' invisible',
                        ' visible');
                }
            }
        });
    </script>
@endsection
