<?php

// ضبط رأس الصفحة (Header) لإرسال رد JSON
header('Content-Type: application/json');

// ضبط المنطقة الزمنية
date_default_timezone_set('Africa/Cairo');

// دالة لتحديد حالة المباراة وتحديث الوقت
function getMatchStatus($match) {
    $start_time = new DateTime($match['Time-Start']);
    $end_time = new DateTime($match['Time-End']);
    $now = new DateTime();
    $status = "لم تبدأ بعد";
    $time_now = 0;

    if ($now >= $start_time && $now < $end_time) {
        $status = "جارية";
        $interval = $now->diff($start_time);
        $time_now = $interval->i + ($interval->h * 60);
    } elseif ($now >= $end_time) {
        $status = "انتهت";
    }
    
    // إضافة الحالة والوقت الجديد إلى بيانات المباراة
    $match['status'] = $status;
    $match['Time-Now'] = $time_now;
    
    return $match;
}

// مصفوفة بيانات المباريات
$matches_data = [
    [
        "match_id" => 1,
        "team_home" => "الأهلي",
        "team_away" => "الزمالك",
        "score_home" => 2,
        "score_away" => 1,
        "Time-Start" => "2025-08-21T19:00",
        "Time-End" => "2025-08-21T21:00",
        "Time-Now" => null,
        "Time-Zone" => "+02:00",
        "league" => "الدوري المصري الممتاز"
    ],
    [
        "match_id" => 2,
        "team_home" => "ريال مدريد",
        "team_away" => "برشلونة",
        "score_home" => null,
        "score_away" => null,
        "Time-Start" => "2025-08-23T21:30",
        "Time-End" => "2025-08-23T23:30",
        "Time-Now" => null,
        "Time-Zone" => "+02:00",
        "league" => "الدوري الإسباني"
    ],
    [
        "match_id" => 3,
        "team_home" => "مانشستر سيتي",
        "team_away" => "ليفربول",
        "score_home" => 0,
        "score_away" => 0,
        "Time-Start" => "2025-08-21T17:45",
        "Time-End" => "2025-08-21T19:45",
        "Time-Now" => null,
        "Time-Zone" => "+02:00",
        "league" => "الدوري الإنجليزي الممتاز"
    ]
];

// تطبيق الدالة على كل مباراة في المصفوفة
$updated_matches = array_map('getMatchStatus', $matches_data);

// إنشاء مصفوفة الرد النهائية
$response = [
    "success" => true,
    "message" => "تم جلب بيانات المباريات بنجاح.",
    "matches" => $updated_matches
];

// تحويل المصفوفة إلى JSON وإرسالها
echo json_encode($response, JSON_PRETTY_PRINT);

?>
