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

    <?php
        $age = 23;
    ?>

    <b>{{ $age }}</b>

    <form action="{{ route('handleForm') }}" method="post">
        #csrf
        <input type="text" name="fname" placeholder="enter fname">
        <input type="submit" value="submit">
    </form>

    <hr><hr>
    <p>new decoration methods</p>

    #foreach($items as $item)
        #if($item == 'skip')
            #continue
        #endif

        #if($item == 'stop')
            #break
        #endif

        <p>{{ $item }}</p>
    #endforeach

    <hr>
    #repeat('it`s cool | ', 3)

    <hr>
    #link('https://example.com')

    <hr>

    #concat(['Hello', ' ', 'World', '!'])

    <hr>

    #default([$family, 'Guest'])
    <hr>

    #isgreater([10, 5])
        <p>۱۰ بزرگ‌تر از ۵ است.</p>
    #endisgreater

    #isless([3, 7])
        <p>۳ کوچک‌تر از ۷ است.</p>
    #endisless

    #fileexists('/path/to/file.txt')
        <p>فایل موجود است.</p>
    #endfileexists


    #inject(
        'simple',
        App\Http\Controllers\SimpleController
    )

    {{ $simple->index() }}

#endsection