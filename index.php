<?php
declare(strict_types = 1);

$incomingYear = NULL;  // any year or NULL
$incomingMonth = NULL;  // any month number 1-12 or NULL
$incomingMonthQuantity = NULL;  // amount of month to make schedule

printScdedule($incomingYear, $incomingMonth, $incomingMonthQuantity);









function printScdedule (?int $year, ?int $month, ?int $monthQuantity): void {
    $selectedYear = $year || $year === 0 ? strval($year) : date('Y');
    $selectedMonth = $month && (0 < $month && $month < 13) ? strval($month) : date('m');
    $selectedmonthQuantity = $monthQuantity ? $monthQuantity : 1;

    $startDate = DateTime::createFromFormat('d-m-Y', "01-$selectedMonth-$selectedYear");
    $delay = 0;
    monthEnum($startDate, $selectedmonthQuantity, $delay);

    echo str_repeat(PHP_EOL, 2);
}

function getWeekDays(): string {
    return "ПН  ВТ  СР  ЧТ  ПТ  СБ  ВС " . PHP_EOL;
}

function printBlueText(string $text): void {
    echo "\033[34m $text \033[0m";
}

function printRedText(string $text): void {
    echo "\033[31m$text\033[0m";
}

function printGreenText(string $text): void {
    echo "\033[32m$text\033[0m";
}

function getRussianMonth(int $month): string {
    $months = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];
    return $months[$month];
}

function printMonthAndDays(string $text): void {
    echo PHP_EOL;
    printBlueText($text);
    echo PHP_EOL;
    printBlueText(getWeekDays());
}

function zeroAtStartPos(int $dayNumber): string {
    return ($dayNumber < 10 ? '0' . strval($dayNumber) : strval($dayNumber));
}

function printDaysEnumeration(DateTime $startDate, int &$delay): void {
    $daysPerMonth = cal_days_in_month(CAL_GREGORIAN, intval($startDate->format('m')), intval($startDate->format('Y')));
    $startPosition = intval($startDate->format('N'));
    echo str_repeat(' ', $startPosition*4 - 4);
    $currentPosition = $startPosition;

    $oldWorkingDay = DateTime::createFromFormat('d-m-Y', $startDate->format('d-m-Y'));
    for ($j = 1; $j <= $daysPerMonth; $j++) {
        if ($currentPosition > 7) {
            echo PHP_EOL . ' ';
            $currentPosition = 1;
        }
        
        colorizeDay($j, $currentPosition, $oldWorkingDay, $startDate, $delay);

        $currentPosition++;
        //echo zeroAtStartPos($j) . '  ';
        $startDate->modify("+1 day");
    }
}

function monthEnum(DateTime $startDate, int $selectedmonthQuantity, int &$delay) {
    for ($i = 0; $i < $selectedmonthQuantity; $i++) {
        $currentText = getRussianMonth(intval($startDate->format('m')) - 1) . ', ' . $startDate->format('Y');
        printMonthAndDays($currentText);

        printDaysEnumeration($startDate, $delay);

        echo PHP_EOL;
        //echo $a->format('d-m-Y');
        //$startDate->modify("+1 month");
    }
}

function colorizeDay(int $currentDay, int $currentWeekDay, DateTime $oldWorkingDay, DateTime $startDate, int &$delay): void {
    if ($currentWeekDay === 6 || $currentWeekDay === 7) {
        $delay = 0;
        printGreenText(zeroAtStartPos($currentDay) . '  ');
    } else {
        if ($delay) {
            printGreenText(zeroAtStartPos($currentDay) . '  ');
            $delay-=1;
        } else {
            printRedText(zeroAtStartPos($currentDay) . '  ');
            $delay = 2;
        }
    }

    // Сдлеать проверки:
    // если текущий день выходной и нет отметки плюс1, то зеленый. и отметка, плюс1. если выходной и было плюс1, то плюс2.
    // если текущий день будний, то если текущий день - омтетка с плюсом = старый рабочий день, то он тоже рабочий, иначе нет
    
    
    //$oldWorkingDay;
    //$date->modify('-2 day');
    //echo ' *'.$date->format('d').'* ';
    //echo ' *'.$startDate->format('d').'* ';
}