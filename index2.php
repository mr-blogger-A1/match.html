<?php

$api_url = 'https://super-fog-7140.bohmoh280.workers.dev/api/matches/';

// تهيئة cURL
$ch = curl_init();

// إعداد خيارات cURL
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // لكي يعود المحتوى كسلسلة نصية بدلاً من عرضه مباشرة

// تنفيذ طلب cURL
$response = curl_exec($ch);

// التحقق من وجود أخطاء في cURL
if (curl_errno($ch)) {
    echo 'خطأ في cURL: ' . curl_error($ch);
    exit;
}

// إغلاق جلسة cURL
curl_close($ch);

// تحويل البيانات من JSON إلى مصفوفة PHP
$matches = json_decode($response, true);

// التحقق من أن البيانات صالحة وليست فارغة
if ($matches && is_array($matches) && !empty($matches)) {
    echo '<h1>قائمة المباريات</h1>';
    echo '<ul style="list-style-type: none; padding: 0;">';
    
    // التكرار على كل مباراة وعرض تفاصيلها
    foreach ($matches as $match) {
        $team1 = isset($match['team1']) ? htmlspecialchars($match['team1']) : 'غير معروف';
        $team2 = isset($match['team2']) ? htmlspecialchars($match['team2']) : 'غير معروف';
        $date = isset($match['date']) ? date('Y-m-d H:i', strtotime($match['date'])) : 'غير معروف';
        $score = isset($match['score']) ? htmlspecialchars($match['score']) : 'لم تبدأ بعد';
        
        echo '<li style="border: 1px solid #ccc; margin-bottom: 10px; padding: 15px; border-radius: 8px; background-color: #f9f9f9;">';
        echo "<h3>{$team1} vs {$team2}</h3>";
        echo "<p><strong>التاريخ والوقت:</strong> {$date}</p>";
        echo "<p><strong>النتيجة:</strong> {$score}</p>";
        echo '</li>';
    }
    
    echo '</ul>';
} else {
    echo '<p>لا توجد بيانات متاحة للمباريات.</p>';
}

?>
