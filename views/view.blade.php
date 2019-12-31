<h1>{{$survey->name}}</h1>
<h2>{{$survey->description}}</h2>

<form method="post">
    @csrf
@forelse($survey->questions as $question)
    <p>
        {{$question->question}} @if($question->required)<small>Required</small>@endif
        @if($errors->has($question->id))
            <p>
            @foreach($errors->get($question->id) as $error)
                {{$error}}
            @endforeach
            </p>
        @endif
        @switch($question->type)
            @case('text')
                <textarea name="{{$question->id}}">{{old($question->id)}}</textarea>
                @break
            @case('radio')
                @forelse($question->options as $option)
                    {{$option->value}}<input type="radio" name="{{$question->id}}" value="{{$option->id}}" @if(old($question->id) == $option->id) checked @endif />
                @empty
                    <b>Error Generating Options</b>
                @endforelse
                @break
            @case('checkbox')
                @forelse($question->options as $option)
                    {{$option->value}}<input type="checkbox" name="{{$question->id}}[]" value="{{$option->id}}" @if(in_array($option->id,old($question->id,[]))) checked @endif />
                @empty
                    <b>Error Generating Options</b>
                @endforelse
                @break
            @case('select')
                <select name="{{$question->id}}">
                    <option>Select one</option>
                    @forelse($question->options as $option)
                        <option value="{{$option->id}}" @if(old($question->id) == $option->id) selected @endif >{{$option->value}}</option>
                    @empty
                        <option>Error Generating Options</option>
                    @endforelse
                </select>
                @break
            @default
                <b>Error Generating Question</b>
        @endswitch
    </p>
@empty
    <p>No Questions</p>
@endforelse
    <input type="submit" value="Submit Survey">
</form>