#extends('layout')

#section('content')

    <h2>{{ __('welcome') }}</h2>
    <h3>currentLocalize is :{{ $currentLocalize }}</h3>

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

#endsection