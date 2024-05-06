<?php

use Lib\FormHandler;
use Lib\Prisma\Classes\Prisma;

$form = new FormHandler($_POST);
$message = '';
$messageType = '';

if ($form->validate()) {
    $formData = $form->getData();
    $prisma = new Prisma();
    $userFound = $prisma->user->findUnique([
        'where' => [
            'email' => $formData->email
        ]
    ]);

    if ($userFound) {
        $message = "User all ready exists.";
        $messageType = 'error';
    } else {
        $user = $prisma->user->create([
            'data' => [
                'name' => $formData->name,
                'email' => $formData->email,
                'password' => password_hash($formData->password, PASSWORD_DEFAULT),
                'userRole' => [
                    'connectOrCreate' => [
                        'where' => [
                            'name' => 'User'
                        ],
                        'create' => [
                            'name' => 'User'
                        ]
                    ]
                ]
            ]
        ]);

        $message = "User created successfully.";
        $messageType = 'success';
    }
}

?>


<div class="flex flex-col max-w-md px-4 py-8 bg-white rounded-lg shadow dark:bg-gray-800 sm:px-6 md:px-8 lg:px-10 border">
    <div class="self-center mb-2 text-xl font-light text-gray-800 sm:text-2xl dark:text-white">
        Create a new account
    </div>
    <span class="justify-center text-sm text-center text-gray-500 flex-items-center dark:text-gray-400">
        Already have an account ?
        <a href="/login" class="text-sm text-blue-500 underline hover:text-blue-700">
            Sign in
        </a>
    </span>
    <span class="<?= $messageType === 'error' ? 'text-red-500' : 'text-blue-500' ?> text-center"><?= $message ?></span>
    <div class="p-6 mt-8">
        <form method="post">
            <div class="flex flex-col mb-2">
                <div class=" relative ">
                    <input type="text" class="rounded-lg flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Name" <?= $form->register("name", ['required' => ['value' => true]]) ?> />
                </div>
            </div>
            <div class="flex flex-col mb-2">
                <div class=" relative ">
                    <input class="rounded-lg flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Email" <?= $form->register("email", ['required' => ['value' => true]]) ?> />
                </div>
            </div>
            <div class="flex flex-col mb-2">
                <div class=" relative ">
                    <input class="rounded-lg flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="Password" <?= $form->register("password", ['required' => ['value' => true], 'password' => ['value' => true]]) ?> />
                </div>
            </div>
            <div class="flex w-full my-4">
                <button type="submit" class="py-2 px-4  bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 focus:ring-offset-purple-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>