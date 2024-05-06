<?php

use Lib\FormHandler;
use Lib\Prisma\Classes\Prisma;
use Lib\Auth\Auth;

$form = new FormHandler($_POST);
$message = '';
$messageType = '';

if ($form->validate()) {
    $formData = $form->getData();
    $prisma = new Prisma();

    $user = $prisma->user->findUnique([
        'where' => [
            'email' => $formData->email
        ],
        'include' => ['userRole' => true]
    ], true);

    if ($user) {
        if (password_verify($formData->password, $user->password)) {
            $auth = new Auth();

            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->userRole[0]->name
            ];

            $auth->authenticate($userInfo);
            redirect('/dashboard');
        } else {
            $message = "Invalid password.";
            $messageType = 'error';
        }
    } else {
        $message = "User not found.";
        $messageType = 'error';
    }
}

?>


<div class="flex flex-col w-full max-w-md px-4 py-8 bg-white rounded-lg shadow dark:bg-gray-800 sm:px-6 md:px-8 lg:px-10 border">
    <div class="self-center mb-6 text-xl font-light text-gray-600 sm:text-2xl dark:text-white">
        Login To Your Account
    </div>
    <div class="flex gap-4 item-center">
        <a href="/api/auth/signin/github" type="button" class="py-2 px-4 flex justify-center items-center  bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg gap-2">
            <svg viewBox="0 0 16 16" width="32" height="32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.62-.01 1.06.57 1.21.81.7 1.21 1.82.86 2.26.66.07-.51.27-.86.49-1.06-1.68-.19-3.44-.84-3.44-3.75 0-.83.3-1.51.79-2.04-.08-.19-.34-.97.08-2.02 0 0 .64-.21 2.1.82.61-.17 1.26-.25 1.91-.25.65 0 1.3.08 1.91.25 1.46-1.02 2.1-.82 2.1-.82.42 1.05.16 1.83.08 2.02.49.53.79 1.21.79 2.04 0 2.92-1.76 3.56-3.44 3.75.28.24.53.71.53 1.44 0 1.04-.01 1.88-.01 2.14 0 .21.15.46.55.38C13.71 14.53 16 11.54 16 8c0-4.42-3.58-8-8-8z" />
            </svg>
            Github
        </a>
        <a href="/api/auth/signin/google" type="button" class="py-2 px-4 flex justify-center items-center  bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
            <svg width="32" height="32" fill="currentColor" class="mr-2" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z">
                </path>
            </svg>
            Google
        </a>
    </div>
    <span class="<?= $messageType === 'error' ? 'text-red-500' : 'text-blue-500' ?> text-center"><?= $message ?></span>
    <div class="mt-8">
        <form method="post">
            <div class="flex flex-col mb-2">
                <div class="flex relative ">
                    <span class="rounded-l-md inline-flex  items-center px-3 border-t bg-white border-l border-b  border-gray-300 text-gray-500 shadow-sm text-sm">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1792 710v794q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-794q44 49 101 87 362 246 497 345 57 42 92.5 65.5t94.5 48 110 24.5h2q51 0 110-24.5t94.5-48 92.5-65.5q170-123 498-345 57-39 100-87zm0-294q0 79-49 151t-122 123q-376 261-468 325-10 7-42.5 30.5t-54 38-52 32.5-57.5 27-50 9h-2q-23 0-50-9t-57.5-27-52-32.5-54-38-42.5-30.5q-91-64-262-182.5t-205-142.5q-62-42-117-115.5t-55-136.5q0-78 41.5-130t118.5-52h1472q65 0 112.5 47t47.5 113z">
                            </path>
                        </svg>
                    </span>
                    <input type="text" id="sign-in-email" class=" rounded-r-lg flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Your email" <?= $form->register("email", ['required' => ['value' => true]]); ?> />
                </div>
            </div>
            <div class="flex flex-col mb-6">
                <div class="flex relative ">
                    <span class="rounded-l-md inline-flex  items-center px-3 border-t bg-white border-l border-b  border-gray-300 text-gray-500 shadow-sm text-sm">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1376 768q40 0 68 28t28 68v576q0 40-28 68t-68 28h-960q-40 0-68-28t-28-68v-576q0-40 28-68t68-28h32v-320q0-185 131.5-316.5t316.5-131.5 316.5 131.5 131.5 316.5q0 26-19 45t-45 19h-64q-26 0-45-19t-19-45q0-106-75-181t-181-75-181 75-75 181v320h736z">
                            </path>
                        </svg>
                    </span>
                    <input class=" rounded-r-lg flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Your password" <?= $form->register("password", ['required' => ['value' => true], 'password' => ['value' => true]]); ?> />
                </div>
            </div>
            <div class="flex items-center mb-6 -mt-4">
                <div class="flex ml-auto">
                    <a href="#" class="inline-flex text-xs font-thin text-gray-500 sm:text-sm dark:text-gray-100 hover:text-gray-700 dark:hover:text-white">
                        Forgot Your Password?
                    </a>
                </div>
            </div>
            <div class="flex w-full">
                <button type="submit" class="py-2 px-4  bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 focus:ring-offset-purple-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg">
                    Login
                </button>
            </div>
        </form>
    </div>
    <div class="flex items-center justify-center mt-6">
        <a href="#" target="_blank" class="inline-flex items-center text-xs font-thin text-center text-gray-500 hover:text-gray-700 dark:text-gray-100 dark:hover:text-white">
            <span class="ml-2">
                You don&#x27;t have an account?
            </span>
        </a>
    </div>
</div>