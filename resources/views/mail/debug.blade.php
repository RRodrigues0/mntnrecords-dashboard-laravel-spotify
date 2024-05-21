<!DOCTYPE html>
<html>
<body>
    <div>
        {!! $text !!}

        @if ($text)
          <br/><br/>
        @endif
        
        <small>{!! $date !!}</small>
    </div>
</body>
</html>