#extends('layout')

#section('content')

    <h2>{{ __('welcome') }}</h2>

    #istrue($isOk)
        <p>The variable is true!</p>
    #endif

    #if($welcome !== 'welcome')
        <p>is arash</p>
    #else
        <p>is not arash</p>
    #endif

    #isnull($family)
        <p>family is null</p>
    #endif

    <form action="{{ route('handleForm') }}" method="post">
        <input type="text" name="fname" placeholder="enter fname">
        <input type="submit" value="submit">
    </form>

#endsection