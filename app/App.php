<?php

declare(strict_types = 1);

//Search for all the transacion files on the directory
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

//Get the data from the CSV files
function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if (!file_exists($fileName)) {
        trigger_error('File do not exists', E_USER_ERROR);
    }

    $file = fopen($fileName, 'r');
    $transactions = [];

    //Ignore the first line (file headers)
    $transaction = fgetcsv($file);

    while (($transaction = fgetcsv($file)) !== false) {
        
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }
        
        $transactions[] = $transaction;
    }

    return $transactions;
}

//Process the transaction data to bring the valid data
function extractTransaction(array $transactionRow): array
{
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    //Delete the symbols and return a floar instead of string
    $amount = (float) str_replace(['$',','], '', $amount);

    return [
        'date'        => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount'      => $amount
    ];
}

function calculateTotals(array $transactions): array
{
    $totals = [
        'totalIncome'  => 0,
        'totalExpense' => 0,
        'netTotal'     => 0
    ];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];
        
        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }

    }

    return $totals;
}