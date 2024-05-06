<?php

use Lib\Auth\Auth;

$auth = new Auth();

if (isset($_GET['logout'])) {
    $auth->logout();
    redirect('/');
}

$authData = $auth->getPayload();
$imageUrl = $authData->image ?? null;

?>

<header class="px-4 lg:px-6 h-14 flex items-center fixed justify-between w-full">
    <a class="flex items-center justify-center" href="/">
        <img class="h-10 w-10" src="<?= $baseUrl ?>assets/images/prisma-php.png" alt="Prisma PHP">
        <span class="sr-only">Prisma PHP</span>
    </a>
    <nav class="ml-auto flex gap-4 sm:gap-6">

        <?php if ($auth->isAuthenticated()) : ?>
            <div class="flex items-center gap-4">
                <a class="text-sm font-medium hover:underline underline-offset-4" href="/dashboard">
                    Dashboard
                </a>

                <a href="#" class="relative block">
                    <?php if ($imageUrl) : ?>
                        <img alt="profil" src="<?= $imageUrl ?>" class="mx-auto object-cover rounded-full h-10 w-10 " />
                    <?php else : ?>
                        <img alt="profil" src="<?= $baseUrl ?>assets/images/profile.jpg" class="mx-auto object-cover rounded-full h-10 w-10 " />
                    <?php endif; ?>
                </a>

                <a class="text-sm font-medium hover:underline underline-offset-4" href="?logout">
                    Log out
                </a>
            </div>
        <?php else : ?>
            <a class="text-sm font-medium hover:underline underline-offset-4" href="/register">
                Register
            </a>
            <a class="text-sm font-medium hover:underline underline-offset-4" href="/login">
                Log in
            </a>
        <?php endif; ?>
    </nav>
</header>