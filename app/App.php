<?php

declare(strict_types = 1);

// Your Code
function getTransactionFiles(string $dirPath): array 
{
    $files = [];
    foreach(scandir($dirPath) as $file) {
        //Filter the directories and just bring the files
        if (is_dir($file)) {
            continue;
        }
        //Full path to the file
        $files[] = $dirPath . $file;
    }
    return $files;
}

function getTransactions(string $fileName): array
{
    if (!file_exists($fileName)) {
        trigger_error('File do not exists', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');
    $transactions = [];

    //Ignore the first line (file headers)
    $transaction = fgetcsv($file);

    while (($transaction = fgetcsv($file)) !== false) {
        $transactions[] = $transaction;
    }

    return $transactions;
}
