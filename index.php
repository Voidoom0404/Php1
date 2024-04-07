<?php 
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

$surname = 'Иванов';
$name = 'Иван';
$patronomyc = 'Иванович';

function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc;
}
echo (getFullnameFromParts($surname, $name, $patronomyc)) . "\n";
echo "\n";

function getPartsFromFullname($name) {
    $a = ['surname', 'name', 'patronomyc'];
    $b = explode(' ', $name);
    return array_combine($a, $b);
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    print_r(getPartsFromFullname($name));
}

function getShortName($name) {
    $arr = getPartsFromFullname($name);
    $firstName = $arr['name'];
    $surname = $arr['surname'];
    return $firstName . ' ' . mb_substr($surname, 0, 1) . '.';
}

foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    echo getShortName($name) . "\n";
}
echo "\n";

function getGenderFromName($name) {
    $arr = getPartsFromFullname($name);
    $surname = $arr['surname'];
    $firstName = $arr['name'];
    $patronomyc = $arr['patronomyc'];
    $sumGender = 0;

    if (mb_substr($surname, -1, 1) === 'в') {
        $sumGender++;
    } elseif (mb_substr($surname, -2, 2) === 'ва') {
        $sumGender--;
    }

    if ((mb_substr($firstName, -1, 1) == 'й') || (mb_substr($firstName, -1, 1) == 'н')) {
        $sumGender++;
    } elseif (mb_substr($firstName, -1, 1) === 'а') {
        $sumGender--;
    }

    if (mb_substr($patronomyc, -2, 2) === 'ич') {
        $sumGender++;
    } elseif (mb_substr($patronomyc, -3, 3) === 'вна') {
        $sumGender--;
    }

    return ($sumGender <=> 0);
}
foreach ($example_persons_array as $value) {
    $name = $value['fullname'];
    
    if (getGenderFromName($name) === 1) {
        echo 'мужской пол ' . ($name) . "\n";
    } elseif (getGenderFromName($name) === -1) {
        echo 'женский пол ' . ($name) . "\n";
    } else {
        echo 'неопределённый пол ' . ($name) . "\n";
    }
}
echo "\n";

function getGenderDescription($persons) {
    
    $men = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderMen = getGenderFromName($fullname);
        if ($genderMen > 0) {
            return $genderMen;
        }    
    });

    $women = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderWomen = getGenderFromName($fullname);
        if ($genderWomen < 0) {
            return $genderWomen;
        }
    });

    $failedGender = array_filter($persons, function ($persons) {
        $fullname = $persons['fullname'];
        $genderFailed = getGenderFromName($fullname);
        if ($genderFailed == 0) {
            return $genderFailed + 1;
        }
    });

    $allMen = count($men);
    $allWomen = count($women);
    $allFailedGender = count($failedGender);
    $allPeople = count($persons);

    $percentMen = round((100 / $allPeople * $allMen), 1); 
    $percentWomen = round((100 / $allPeople * $allWomen), 1);
    $percenFailedGender = round((100 / $allPeople * $allFailedGender), 1);

    return <<< END
Гендерный состав аудитории:
---------------------------
Мужчины - $percentMen%
Женщины - $percentWomen%
Неудалось определить - $percenFailedGender%
END;
} 

echo getGenderDescription($example_persons_array) . "\n";
echo "\n";

$surname = 'ИВАНОВ';
$name = 'Иван';
$patronomyc = 'Иванович';

function getPerfectPartner($surname, $name, $patronomyc, $persons) {

    $surnameNorm = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $patronomycNorm = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
    $nameNorm = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);

    $fullNameNorm = getFullnameFromParts($surnameNorm, $nameNorm, $patronomycNorm);  
    $shortNameNorm = getShortName($fullNameNorm);                                    
    $genderFullNameNorm = getGenderFromName($fullNameNorm);                          

    $allPersons = count($persons);

    
    
    do {
        $personsNumRand = rand(0, $allPersons - 1); 
        $personFullNameRand = $persons[$personsNumRand]['fullname'];         
        $personFullNameRandGender = getGenderFromName($personFullNameRand);  
    } while (($genderFullNameNorm == $personFullNameRandGender) || ($personFullNameRandGender == 0));

    $personShortNameRand = getShortName($personFullNameRand);   
    $percentPerfect = rand(5000, 10000) / 100;                  
        
    return <<< END
    $shortNameNorm + $personShortNameRand =
    ♡ Идеально на $percentPerfect% ♡
    END;
}
echo getPerfectPartner($surname, $name, $patronomyc, $example_persons_array) . "\n";
