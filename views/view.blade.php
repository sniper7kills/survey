<h1>{{$survey->name}}</h1>
<p>{{$survey->description}}</p>

<hr>
<form method="post" action="{{route('survey.submit',$survey)}}">
    @csrf
    @foreach($survey->questions as $question)
        <b>{{$question->question}}</b><br />
        @if($question->type == "text")
            <textarea name="{{$question->id}}"></textarea>
        @elseif($question->type == "radio")
            @foreach($question->options as $option)
                <b>{{$option->value}}</b><input type="radio" name="{{$question->id}}" value="{{$option->id}}">
            @endforeach
        @elseif($question->type == "checkbox")
            @foreach($question->options as $option)
                <b>{{$option->value}}</b><input type="checkbox" name="{{$question->id}}" value="{{$option->id}}">
            @endforeach
        @elseif($question->type == "select")
            <select name="{{$question->id}}">
                @foreach($question->options as $option)
                    <option value="{{$option->id}}">{{$option->value}}</option>
                @endforeach
            </select>
        @endif
        <br />
    @endforeach
    <input type="submit" value="Submit Survey">
</form>