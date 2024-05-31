<?php
    class User {
        public $username;
        public $email;
        public $password;
    
        protected function __construct($username, $email, $password) {
            // التحقق من أن عنوان البريد الإلكتروني صحيح
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email address.");
            }
    
            // التحقق من أن كلمة المرور طويلة بما فيه الكفاية
            if (strlen($password) < 6) {
                throw new Exception("Password must be at least 6 characters long.");
            }
    
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
    
            // يمكنك هنا إضافة تحقق إضافي حسب الحاجة، مثل التحقق من الاسم
        }
    
        public function saveToDatabase() {
            // هنا يمكن إضافة كود لحفظ بيانات المستخدم في قاعدة البيانات
            // في هذا المثال، سيتم طباعة البيانات فقط لأغراض التوضيح
            echo "Saving to database: Username: {$this->username}, Email: {$this->email}, Password: {$this->password}";
        }
    }
    
    // محاولة إنشاء كائن مع بيانات صحيحة
    try {
        $user1 = new User  ("john_doe", "john@example.com", "password123");
        $user1->saveToDatabase();  // سيتم حفظها في قاعدة البيانات
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    // محاولة إنشاء كائن مع بيانات غير صحيحة
    try {
        $user2 = new User("jane_smith", "jane@example", "pass");  // بريد غير صحيح وكلمة مرور قصيرة
        $user2->saveToDatabase();  // لن يتم حفظها وسترمي استثناء
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();  // سيطبع: Error: Invalid email address.
    }
    