<?php
// تضمين مكتبة العميل PHP لخدمة Google API
require_once 'vendor/autoload.php'; // قم بتعديل المسار حسب الحاجة

// إعداد المصادقة
$client = new Google_Client();
$client->setApplicationName('اسم التطبيق الخاص بك');
$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
$client->setAuthConfig('المسار/إلى/ملف/الاعتمادات.json'); // المسار إلى ملف JSON الخاص بك لاعتمادات Google Sheets API

// الاتصال بقاعدة البيانات الخاصة بك
$host = 'localhost';
$dbname = 'اسم_قاعدة_البيانات_الخاصة_بك';
$username = 'اسم_المستخدم_الخاص_بك';
$password = 'كلمة_المرور_الخاصة_بك';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "فشل الاتصال: " . $e->getMessage();
    die();
}

// استعلام قاعدة البيانات الخاصة بك لاسترداد البيانات
$stmt = $conn->prepare("SELECT * FROM اسم_الجدول_الخاص_بك");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تهيئة خدمة Google Sheets
$service = new Google_Service_Sheets($client);

// تحديد معرف جدول البيانات والنطاق
$spreadsheetId = 'معرف_جدول_البيانات_الخاص_بك';
$range = 'Sheet1!A1';

// استعداد البيانات للإدخال إلى جدول البيانات
$values = [];
foreach ($data as $row) {
    $values[] = [$row['اسم_العمود1'], $row['اسم_العمود2'], $row['اسم_العمود3']]; // قم بتعديل أسماء الأعمدة وفقًا لهيكل قاعدة البيانات وجدول البيانات
}

// إنشاء الطلب لتحديث جدول البيانات
$requestBody = new Google_Service_Sheets_ValueRange([
    'values' => $values
]);
$params = [
    'valueInputOption' => 'RAW'
];
$request = $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);

// تنفيذ الطلب
$response = $request->execute();

// إخراج الاستجابة
print_r($response);
?>
