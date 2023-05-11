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

function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if (!file_exists($fileName)) {
        trigger_error("File {$fileName} does not exist", E_USER_ERROR);
    }

    /* To work with different transaction extractor functions
    the parent function receive a optional callable function parameter */
    if ($transactionHandler != null) {
        $transaction = $transactionHandler;
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
        $transactions[] = extractTransaction($transaction);
    };

    return $transactions;
}

// Formater for the transactions values
function extractTransaction(array $transactionRow): array {
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$',','],'', $amount);

    return [
        'date'        => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount'      => $amount
    ];
}