<?php

declare(strict_types = 1);

// Your Code
function getTransactionFiles(string $dirPath): array {
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        }
        $files[] = $dirPath . $file;
    }

    return $files;  
}

function getTransactions(string $fileName): array
{
    if (!file_exists($fileName)) {
        trigger_error("File {$fileName} does not exist", E_USER_ERROR);
    }

    // Read the transactions file
    $file = fopen($fileName, 'r');
    
    /* This read the first line of the CVS (headers) and ommit it
    from the actual transactions */
    fgetcsv($file);

    /* If doesn't have an error. Saves each line transactions CSV files 
    as a new transaction on the transactions Array */
    $transactions = [];
    while(($transaction = fgetcsv($file)) !== false){
        $transactions[] = $transaction;
    };

    return $transactions;
}