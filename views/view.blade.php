<h1>{{$survey->name}}</h1>
<p>{{$survey->description}}</p>

<hr>
@if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
@endif
<form method="post" action="{{route('survey.submit',$survey)}}">
    @csrf
    @foreach($survey->questions as $question)
        <b><i>{{$question->id}}</i> - {{$question->question}}</b><br />
        @if($question->required)
            <small>Required</small><br />
        @endif
        @if($question->type == "text")
            <textarea name="question-{{$question->id}}" value="{{old("question-".$question->id)}}"></textarea>
        @elseif($question->type == "radio")
            @foreach($question->options as $option)
                <b>{{$option->value}}</b>
                <input type="radio"
                       name="question-{{$question->id}}"
                       value="{{$option->id}}"
                       @if(old("question-".$question->id)==$option->id) selected @endif>
            @endforeach
        @elseif($question->type == "checkbox")
            @foreach($question->options as $option)
                <b>{{$option->value}}</b>
                <input type="checkbox"
                       name="question-{{$question->id}}[]"
                       value="{{$option->id}}"
                       @if(is_array(old("question-".$question->id)) && in_array($option->id, old("question-".$question->id))) checked @endif>
            @endforeach
        @elseif($question->type == "select")
            <select name="question-{{$question->id}}">
                <option>Select...</option>
                @foreach($question->options as $option)
                    <option value="{{$option->id}}" @if(old("question-".$question->id)==$option->id) checked @endif>{{$option->value}}</option>
                @endforeach
            </select>
        @endif
        <br />
    @endforeach
    <input type="submit" value="Submit Survey">
</form>