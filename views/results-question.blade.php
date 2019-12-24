<h1>{{$survey->name}}</h1>
<p>{{$survey->description}}</p>
<h2>{{$question->question}}</h2>
<hr />
<ul>
    @foreach($answers as $answer)
        <li>{{$answer->answer}}</li>
    @endforeach
</ul>
