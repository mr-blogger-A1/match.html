import json
import datetime
import pytz

def update_match_statuses_automatically(matches_data):
    """
    تحديث حالة المباريات تلقائياً بناءً على الوقت الحالي.
    :param matches_data: قائمة بقواميس المباريات (بيانات JSON).
    :return: قائمة محدثة بقواميس المباريات.
    """
    updated_matches = []
    
    # تحديد المنطقة الزمنية للقاهرة (EEST - Eastern European Summer Time)
    cairo_tz = pytz.timezone('Africa/Cairo')
    current_time_cairo = datetime.datetime.now(cairo_tz)
    
    print(f"Current time in Cairo (EEST): {current_time_cairo.strftime('%Y-%m-%d %H:%M:%S')}")

    for match in matches_data:
        status = match.get('status', 'غير معروف')
        match_time_str = match.get('matchTime', '')
        team1_score = match.get('team1Score', '')
        team2_score = match.get('team2Score', '')

        # منطق تحديث الحالة
        # الحالة الأولى: إذا كانت المباراة لم تبدأ أو قادمة
        if status == "لم تبدأ" or status == "قادمة":
            if match_time_str:
                try:
                    # تحليل وقت المباراة وتعيين نفس تاريخ اليوم والمنطقة الزمنية
                    match_time_obj = datetime.datetime.strptime(match_time_str, '%I:%M %p').replace(
                        year=current_time_cairo.year,
                        month=current_time_cairo.month,
                        day=current_time_cairo.day
                    )
                    # جعل كائن الوقت واعيًا بالمنطقة الزمنية
                    match_time_obj = cairo_tz.localize(match_time_obj)

                    # تحديد ما إذا كان وقت المباراة قد حان أو مر
                    if current_time_cairo >= match_time_obj:
                        # إذا كان الوقت قد حان أو مر، نعتبرها "مباشر"
                        match['status'] = "مباشر"
                        # منطق إضافي لتحديد ما إذا كانت "انتهت" بعد ذلك
                        # يمكن أن يكون هذا بناءً على مرور فترة زمنية كافية (مثلاً، 110 دقائق كحد أقصى للمباراة)
                        end_time_threshold = match_time_obj + datetime.timedelta(minutes=110) 
                        if current_time_cairo > end_time_threshold:
                            # إذا تجاوزت الوقت المتوقع للمباراة وتوفرت نتائج
                            if team1_score != "" and team2_score != "":
                                match['status'] = "انتهت"
                            else:
                                match['status'] = "انتهت" 
                    else:
                        match['status'] = "قادمة" # الوقت لم يحل بعد
                except ValueError:
                    # في حالة عدم القدرة على تحليل matchTime
                    print(f"Warning: Could not parse match time '{match_time_str}' for {match['team1Name']} vs {match['team2Name']}")
                    match['status'] = "غير معروف"
            else:
                # إذا لم يكن هناك matchTime وكانت الحالة "لم تبدأ" أو "قادمة"
                print(f"Warning: Missing matchTime for {match['team1Name']} vs {match['team2Name']}")
                match['status'] = "قادمة"

        # الحالة الثانية: إذا كانت المباراة مباشرة بالفعل
        elif status == "مباشر":
            # إذا كانت المباراة "مباشر" وتحتوي على نتائج (ليست فارغة)، يمكن اعتبارها "انتهت"
            if team1_score != "" and team2_score != "":
                match['status'] = "انتهت"
            
        # الحالة الثالثة: إذا كانت المباراة انتهت بالفعل
        elif status == "انتهت":
            # إذا كانت المباراة "انتهت" بالفعل، لا يوجد تغيير تلقائي مطلوب
            pass

        updated_matches.append(match)
    return updated_matches

# ----------------------------------------------------
# بيانات JSON
json_data = """
[
    {
        "team1Name": "المقاولون العرب",
        "team2Name": "حرس الحدود",
        "team1Logo": "https:\/\/tinyurl.com\/2ycz4s7y",
        "team2Logo": "https:\/\/tinyurl.com\/2cncansr",
        "team1Score": "0",
        "team2Score": "0",
        "status": "مباشر",
        "matchTime": ""
    },
    {
        "team1Name": "سموحة",
        "team2Name": "زد",
        "team1Logo": "https:\/\/tinyurl.com\/2cu7mb2j",
        "team2Logo": "https:\/\/tinyurl.com\/2dhcjakc",
        "team1Score": "",
        "team2Score": "",
        "status": "لم تبدأ",
        "matchTime": "09:00 م"
    },
    {
        "team1Name": "مودرن سبورت",
        "team2Name": "الزمالك",
        "team1Logo": "https:\/\/tinyurl.com\/25f287u4",
        "team2Logo": "https:\/\/tinyurl.com\/22am9a4e",
        "team1Score": "",
        "team2Score": "",
        "status": "لم تبدأ",
        "matchTime": "09:00 م"
    },
    {
        "team1Name": "إستوديانتس",
        "team2Name": "سيرو بورتينيو",
        "team1Logo": "https:\/\/tinyurl.com\/2beyxkl4",
        "team2Logo": "https:\/\/tinyurl.com\/25ndb9gq",
        "team1Score": "0",
        "team2Score": "0",
        "status": "انتهت",
        "matchTime": "01:00 ص"
    },
    {
        "team1Name": "إنترناسيونال",
        "team2Name": "فلامينجو",
        "team1Logo": "https:\/\/tinyurl.com\/25lp9prn",
        "team2Logo": "https:\/\/tinyurl.com\/26ykna38",
        "team1Score": "0",
        "team2Score": "2",
        "status": "انتهت",
        "matchTime": "03:30 ص"
    },
    {
        "team1Name": "نادي قطر",
        "team2Name": "السيلية",
        "team1Logo": "https:\/\/tinyurl.com\/23nf85hw",
        "team2Logo": "https:\/\/tinyurl.com\/2bud4s4d",
        "team1Score": "",
        "team2Score": "",
        "status": "لم تبدأ",
        "matchTime": "06:30 م"
    }
]
"""

# ----------------------------------------------------
# تنفيذ الكود:

# قراءة بيانات JSON من السلسلة النصية
matches_data = json.loads(json_data)

# تحديث حالات المباريات تلقائياً
updated_matches_data = update_match_statuses_automatically(matches_data)

# طباعة البيانات المحدثة
print("\n--- Updated Matches Data ---")
print(json.dumps(updated_matches_data, indent=4, ensure_ascii=False))
