<?php

$elements = [1, 2];
$playerBalance = 100;
$rows = 3;
$column = 3;

function calculateBaseMultiplier($rows, $columns, $elementCount) {
    $winMultiplier = 1;
    $winMultiplier += ($elementCount - 2) * 0.4;
    $winMultiplier -= ($columns - 3) * 0.2;
    $winMultiplier += ($rows - 3) * 0.3;
    return $winMultiplier;
}

function checkWin($slotMachine, $rows, $columns, $elementCount) {
    $winMultiplier = calculateBaseMultiplier($rows, $columns, $elementCount);
    $hasWinningRow = false;

    foreach ($slotMachine as $row) {
        if (count(array_unique($row)) == 1) {
            $hasWinningRow = true;
            break;
        }
    }

    return $hasWinningRow ? $winMultiplier : 0;
}

while (true) {
    $baseMultiplier = calculateBaseMultiplier($rows, $column, count($elements));
    echo "Current win multiplier: $baseMultiplier\n";
    echo "1. Play\n2. Change table size\n3. Change element count\n";
    echo "4. Balance\n5. Cash Out\n6. Get more credits\n";
    $playerChoice = readline("Enter your choice: ");
    switch ($playerChoice) {
        case '1':
            while (true) {
                $betAmount = readline("Bet Amount: ");
                if ($betAmount > $playerBalance) {
                    echo "You don't have enough balance to make a bet. Your balance is $playerBalance\n";
                    continue;
                }
                $playerBalance -= $betAmount;
                $button = readline("Type 'roll' to roll or 'menu' to return to main menu\n");
                if ($button == "menu") {
                    break;
                }
                if ($button == "roll") {
                    $slotMachine = [];
                    for ($i = 0; $i < $rows; $i++) {
                        $slotMachine[$i] = [];
                        for ($j = 0; $j < $column; $j++) {
                            $slotMachine[$i][$j] = $elements[array_rand($elements)];
                        }
                    }
                    foreach ($slotMachine as $row) {
                        echo implode(" ", $row) . "\n";
                    }
                    $winMultiplier = checkWin($slotMachine, $rows, $column, count($elements));
                    if ($winMultiplier > 0) {
                        $playerBalance += $winMultiplier * $betAmount;
                        echo "You won! Your new balance is: " . $playerBalance . "\n";
                    } else {
                        echo "You lost! Your new balance is: " . $playerBalance . "\n";
                    }
                }
            }
            break;

        case '2':
            $rows = readline("How many rows? \n");
            $column = readline("How many columns? \n");
            if ($rows < 1 || $column < 2) {
                echo "Error: Rows must be 1 or more and columns must be 2 or more \n";
            }
            break;

        case '3':
            $elementCount = readline("How many elements? \n");
            if ($elementCount < 2) {
                echo "Error: Element count must be 2 or more \n";
                break;
            }
            $elements = range(1, $elementCount);
            break;

        case '4':
            echo "Your balance is: $playerBalance \n";
            break;

        case '5':
            echo "Cashing out... Your balance is: $playerBalance \n";
            exit();

        case '6':
            $moreCredits = readline("Enter the amount of credits you want to add: \n");
            if ($moreCredits > 0) {
                $playerBalance += $moreCredits;
                echo "Credits added. Your new balance is: $playerBalance \n";
            } else {
                echo "Invalid amount entered. \n";
            }
            break;

        default:
            echo "Invalid choice.\n";
    }
}
?>
