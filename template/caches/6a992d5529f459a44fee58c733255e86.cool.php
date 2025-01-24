<?php $this->extend('layout'); ?>

<?php $this->startSection('content'); ?>

    <h2><?php echo htmlspecialchars(__('welcome'), ENT_QUOTES, 'UTF-8'); ?></h2>

    <?php if (!empty($isOk) && $isOk === true): ?>
        <p>The variable is true!</p>
    <?php endif; ?><?php if ($welcome !== 'welcome'): ?>
        <p>is arash</p>
    <?php else: ?><p>is not arash</p>
    <?php endif; ?><?php if (is_null($family)): ?>
        <p>family is null</p>
    <?php endif; ?><?php
        $age = 23;
    ?>

    <b><?php echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8'); ?></b>

    <form action="<?php echo htmlspecialchars(route('handleForm'), ENT_QUOTES, 'UTF-8'); ?>" method="post">
        <input 
                type='hidden' 
                name='_token' 
                value="bd54230f11a09de0bc8298724287c78a9b5c10ba5c792ae6d8ade4a7565ea535"
                ><input type="text" name="fname" placeholder="enter fname">
        <input type="submit" value="submit">
    </form>

    <hr><hr>
    <p>new decoration methods</p>

    <?php foreach ($items as $item): ?>
        <?php if ($item == 'skip'): ?>
            <?php continue; ?><?php endif; ?><?php if ($item == 'stop'): ?>
            <?php break; ?><?php endif; ?><p><?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endforeach; ?><hr>
    <?php echo str_repeat('it`s cool | ', 3); ?>

    <hr>
    <?php echo '<a href="' . htmlspecialchars('https://example.com', ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars('https://example.com', ENT_QUOTES, 'UTF-8') . '</a>'; ?>

    <hr>

    <?php echo implode('', ['Hello', ' ', 'World', '!']); ?>

    <hr>

    <?php echo !empty([$family, 'Guest'][0]) ? [$family, 'Guest'][0] : [$family, 'Guest'][1]; ?>
    <hr>

    <?php if ([10, 5][0] > [10, 5][1]): ?>
        <p>۱۰ بزرگ‌تر از ۵ است.</p>
    <?php endif; ?><?php if ([3, 7][0] < [3, 7][1]): ?>
        <p>۳ کوچک‌تر از ۷ است.</p>
    <?php endif; ?><?php if (file_exists('/path/to/file.txt')): ?>
        <p>فایل موجود است.</p>
    <?php endif; ?><?php $simple = new App\Http\Controllers\SimpleController(); ?>

    <?php echo htmlspecialchars($simple->index(), ENT_QUOTES, 'UTF-8'); ?>

    <hr><hr>


    <?php echo strtoupper($full_name); ?>
<br>

    <?php echo lastElement([1, 2, 3]); ?>
<br>
    <?php echo firstElement([1, 2, 3]); ?>


<?php $this->endSection(); ?>