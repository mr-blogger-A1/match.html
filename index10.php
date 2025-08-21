<?php
header('Content-Type: application/json');

// إعداد البيانات مع إضافة مسارات الشعارات
$matches = [
    [
        'id' => 1,
        'home_team' => 'مودرن سبورت',
        'home_team_logo' => 'https://i0.wp.com/www.yalla1shoot.com/wp-content/uploads/2025/08/50877-150x150.png', // مسار الشعار
        'away_team' => 'الزمالك',
        'away_team_logo' => 'https://i0.wp.com/www.yalla1shoot.com/wp-content/uploads/2025/08/8201-132x150.png', // مسار الشعار
        'start_time' => '2025-08-21T21:00:00',
        'end_time' => '2025-08-21T23:00:00',
    ],
    [
        'id' => 2,
        'home_team' => 'الأهلي',
        'home_team_logo' => 'http://your-domain.com/images/logos/alahli.png',
        'away_team' => 'الاتحاد',
        'away_team_logo' => 'http://your-domain.com/images/logos/alittihad.png',
        'start_time' => '2025-08-21T21:00:00',
        'end_time' => null,
    ],
    [
        'id' => 3,
        'home_team' => 'الشباب',
        'home_team_logo' => 'http://your-domain.com/images/logos/alshabab.png',
        'away_team' => 'التعاون',
        'away_team_logo' => 'http://your-domain.com/images/logos/altaawoun.png',
        'start_time' => '2025-08-22T18:30:00',
        'end_time' => null,
    ]
];

// باقي الكود الخاص بتحديد حالة المباراة كما هو
$now = new DateTime();

foreach ($matches as &$match) {
    $start_time = new DateTime($match['start_time']);

    if ($start_time > $now) {
        $match['status'] = 'قادمة';
        $match['display_time'] = $start_time->format('H:i');
        $match['display_date'] = $start_time->format('Y-m-d');
        $match['score'] = 'قريباً';
    } else {
        if ($match['end_time'] === null) {
            $match['status'] = 'قيد اللعب';
            $match['score'] = '0 - 0';
            $match['display_time'] = 'مباشر';
            $match['display_date'] = $start_time->format('Y-m-d');
        } else {
            $end_time = new DateTime($match['end_time']);
            if ($end_time < $now) {
                $match['status'] = 'انتهت';
                $match['score'] = '2 - 1';
                $match['display_time'] = 'انتهت';
                $match['display_date'] = $end_time->format('Y-m-d');
            }
        }
    }
}

foreach ($matches as &$match) {
    unset($match['start_time']);
    unset($match['end_time']);
}

echo json_encode($matches, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
