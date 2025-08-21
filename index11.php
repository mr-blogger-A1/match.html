<?php

// 1. إعداد رأس الاستجابة لـ JSON
header('Content-Type: application/json');

// 2. دالة لجلب بيانات المباريات (يمكن استبدالها بجلب من قاعدة بيانات)
function getMatchesData() {
    // استخدم بياناتك الأصلية مع تواريخ وأوقات البدء والانتهاء
    return [
        [
            'id' => 1,
            'home_team' => 'الهلال',
            'home_team_logo' => 'http://your-domain.com/images/logos/alhilal.png',
            'away_team' => 'النصر',
            'away_team_logo' => 'http://your-domain.com/images/logos/alnassr.png',
            'start_time' => '2025-08-21T20:00:00', // المباراة انتهت حسب الوقت الحالي
            'end_time' => '2025-08-21T21:45:00',
        ],
        [
            'id' => 2,
            'home_team' => 'الأهلي',
            'home_team_logo' => 'http://your-domain.com/images/logos/alahli.png',
            'away_team' => 'الاتحاد',
            'away_team_logo' => 'http://your-domain.com/images/logos/alittihad.png',
            'start_time' => '2025-08-21T13:00:00', // المباراة جارية
            'end_time' => null,
        ],
        [
            'id' => 3,
            'home_team' => 'الشباب',
            'home_team_logo' => 'http://your-domain.com/images/logos/alshabab.png',
            'away_team' => 'التعاون',
            'away_team_logo' => 'http://your-domain.com/images/logos/altaawoun.png',
            'start_time' => '2025-08-22T18:30:00', // مباراة قادمة
            'end_time' => null,
        ]
    ];
}

// 3. دالة لمعالجة حالة المباراة وتنسيق البيانات
function processMatchStatus($match) {
    $now = new DateTime('now', new DateTimeZone('Africa/Cairo')); // تحديد التوقيت المحلي لمصر (EEST)
    $start_time = new DateTime($match['start_time']);

    $match['display_date'] = $start_time->format('Y-m-d'); // تنسيق التاريخ دائماً

    if ($start_time > $now) {
        // المباراة قادمة
        $match['status'] = 'قادمة';
        $match['display_time'] = $start_time->format('H:i');
        $match['score'] = 'قريباً';
    } else {
        // المباراة بدأت أو انتهت
        if ($match['end_time'] !== null) {
            $end_time = new DateTime($match['end_time']);
            if ($end_time < $now) {
                // المباراة انتهت
                $match['status'] = 'انتهت';
                $match['score'] = '2 - 1'; // نتيجة افتراضية، يجب جلبها من مكان آخر
                $match['display_time'] = 'انتهت';
            } else {
                // المباراة جارية (بدأت ولم تنتهِ بعد)
                $match['status'] = 'قيد اللعب';
                $match['score'] = '0 - 0'; // نتيجة افتراضية، يجب جلبها من مكان آخر
                $match['display_time'] = 'مباشر';
            }
        } else {
            // المباراة جارية (وقت الانتهاء غير محدد)
            $match['status'] = 'قيد اللعب';
            $match['score'] = '0 - 0'; // نتيجة افتراضية، يجب جلبها من مكان آخر
            $match['display_time'] = 'مباشر';
        }
    }

    // حذف الحقول الداخلية التي لا نريد عرضها في الـ API النهائي
    unset($match['start_time']);
    unset($match['end_time']);

    return $match;
}

// 4. الدالة الرئيسية للتعامل مع الطلبات
function handleApiRequest() {
    $matches = getMatchesData();
    $processedMatches = [];
    $match_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $foundMatch = null;

    foreach ($matches as $match) {
        $processedMatch = processMatchStatus($match);
        $processedMatches[] = $processedMatch;

        if ($match_id !== null && $processedMatch['id'] === $match_id) {
            $foundMatch = $processedMatch;
        }
    }

    if ($match_id !== null) {
        if ($foundMatch) {
            echo json_encode($foundMatch, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            http_response_code(404); // لم يتم العثور
            echo json_encode(['error' => 'Match not found'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode($processedMatches, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}

// تشغيل الدالة الرئيسية
handleApiRequest();

?>
