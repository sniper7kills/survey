<h1>{{$survey->name}}</h1>
<p>{{$survey->description}}</p>
<h2>Results</h2>
<p>{{$survey->responses()->count()}} Responses</p>
<hr>
@foreach($survey->questions as $question)
    {{$question->question}}
    @if($question->type != "text")
        <ul>
            @foreach($question->options as $option)
                <li>{{$option->value}} - {{$option->answers()->count()}}</li>
            @endforeach
        </ul>
    @else
        <ul>
            @foreach($question->answers()->limit(5)->get() as $answer)
                <li>{{$answer->answer}}</li>
            @endforeach
        </ul>
        @if($question->answers()->count() > 0)
            <a href="{{route('survey.results.question',['survey'=>$survey,'question'=>$question])}}">View More Results</a>
        @endif
    @endif
    <hr>
@endforeach