# 🏫 مدرسة زاد الغد — نظام الإدارة المدرسية

نظام متكامل لإدارة شؤون الطلاب والتسجيل والرسوم والإحصائيات، مبني بـ Laravel 10 وواجهة AdminLTE مع دعم كامل للغة العربية واتجاه RTL.

---

## 📋 جدول المحتويات

- [المميزات](#المميزات)
- [المتطلبات التقنية](#المتطلبات-التقنية)
- [التثبيت](#التثبيت)
- [هيكل قاعدة البيانات](#هيكل-قاعدة-البيانات)
- [هيكل المشروع](#هيكل-المشروع)
- [الوحدات المطوّرة](#الوحدات-المطوّرة)
- [النماذج والعلاقات](#النماذج-والعلاقات)
- [قواعد التحقق](#قواعد-التحقق)
- [التطوير](#التطوير)

---

## ✨ المميزات

- 📚 إدارة شاملة للطلاب مع بيانات ولي الأمر
- 🏫 إدارة الشعب الدراسية والصفوف والرسوم
- 📝 نظام تسجيل وتتبع حالات الطلاب
- 💰 إدارة الدفعات والخصومات
- 🧮 تحديد الصف الدراسي تلقائيًا حسب عمر الطالب
- 📊 لوحة تحكم تفاعلية وإحصائيات مباشرة
- 📁 تصدير بيانات الطلاب إلى Excel
- 🔎 نظام بحث وفلترة متقدم
- 📱 تصميم متجاوب لجميع الأجهزة
- 🌐 دعم كامل للغة العربية و RTL
- 🔐 نظام مصادقة وحماية باستخدام Laravel Sanctum

---

## ⚙️ المتطلبات التقنية

| المكوّن | الإصدار |
|---|---|
| PHP | >= 8.1 |
| Laravel | 10 |
| MySQL | >= 5.7 |
| Composer | latest |
| Node.js & NPM | latest |

**الحزم الرئيسية:** AdminLTE 3 — Bootstrap 5 — Laravel Sanctum — Laravel Excel — Vite

---

## 🚀 التثبيت

### 1. استنساخ المشروع

```bash
git clone <repository-url>
cd zadAlghadSchool
```

### 2. تثبيت الاعتماديات

```bash
composer install
npm install
```

### 3. إعداد ملف البيئة

```bash
cp .env.example .env
php artisan key:generate
```

عدّل بيانات قاعدة البيانات في `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zad_alghad
DB_USERNAME=root
DB_PASSWORD=
```

### 4. تشغيل الـ Migrations

```bash
php artisan migrate
```

### 5. بناء الأصول وتشغيل المشروع

```bash
npm run build
php artisan serve
```

---

## 🗄️ هيكل قاعدة البيانات

تتكون قاعدة البيانات من 9 جداول:

| الجدول | الوصف |
|---|---|
| `parents` | أولياء الأمور |
| `levels` | الصفوف الدراسية |
| `discounts` | أنواع الخصومات |
| `students` | بيانات الطلاب |
| `classes` | الشعب الدراسية |
| `registrations` | تسجيلات الطلاب في الشعب |
| `student_statuses` | سجل تاريخ حالات الطالب |
| `payments` | الدفعات المالية |
| `student_discounts` | الخصومات المطبّقة على التسجيلات |

### إنشاء الـ Migrations

```bash
php artisan make:migration create_parents_table
php artisan make:migration create_levels_table
php artisan make:migration create_discounts_table
php artisan make:migration modify_students_table --table=students
php artisan make:migration create_classes_table
php artisan make:migration create_registrations_table
php artisan make:migration create_student_statuses_table
php artisan make:migration create_payments_table
php artisan make:migration create_student_discounts_table
```

> **ملاحظة:** الترتيب الزمني للـ migrations مهم لضمان تنفيذ الـ Foreign Keys بالترتيب الصحيح.

---

## 📁 هيكل المشروع

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── SchoolClassController.php
│   │   └── students/
│   │       └── StudentController.php
│   └── Requests/
│       └── SchoolClassRequest.php
├── Models/
│   ├── Student.php
│   ├── Guardian.php
│   ├── Level.php
│   ├── Discount.php
│   ├── SchoolClass.php
│   ├── Registration.php
│   ├── StudentStatus.php
│   ├── Payment.php
│   └── StudentDiscount.php
database/
└── migrations/
resources/
└── views/
    ├── dashboard.blade.php
    ├── classes/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   ├── show.blade.php
    │   └── _form.blade.php
    └── students/
routes/
└── web.php
```

---

## 🔧 الوحدات المطوّرة

### الطلاب (Students)
إدارة كاملة لبيانات الطلاب تشمل:
- المعلومات الشخصية: الاسم الكامل، رقم الهوية، تاريخ الميلاد، الجنس
- المعلومات الأكاديمية: الصف الدراسي، آخر شهادة
- معلومات ولي الأمر
- الحالة الصحية
- البحث والفلترة المتقدمة
- التصدير إلى Excel

**ميزة تحديد الصف تلقائيًا حسب العمر:**

| العمر عند تاريخ الاعتماد | الصف |
|---|---|
| 4 سنوات | بستان |
| 5 سنوات | تمهيدي |
| 6 سنوات | الأول |
| 7 سنوات | الثاني |

يتم احتساب العمر ديناميكيًا وفق السنة الدراسية الحالية دون الحاجة لتعديل الكود سنويًا.

---

### الشعب الدراسية (SchoolClass)
CRUD كامل يشمل:
- ربط الشعبة بالصف الدراسي (`level_id`)
- السنة الدراسية (`academic_year`)
- الرسوم الدراسية (`price`)
- تاريخي البداية والنهاية (`start_date` / `end_date`)
- الحد الأدنى والأقصى للطلاب (`min_capacity` / `max_capacity`)
- حماية الحذف عند وجود طلاب مسجلين
- فلترة بالصف والسنة الدراسية والاسم

```bash
# يضاف في routes/web.php
Route::resource('classes', SchoolClassController::class);
```

---

## 🔗 النماذج والعلاقات

```
Guardian        →  hasMany     →  Student
Student         →  belongsTo   →  Guardian
                →  hasMany     →  Registration
Level           →  hasMany     →  SchoolClass
SchoolClass     →  belongsTo   →  Level
                →  hasMany     →  Registration
Registration    →  belongsTo   →  Student
                →  belongsTo   →  SchoolClass
                →  hasMany     →  StudentStatus
                →  hasMany     →  Payment
                →  hasMany     →  StudentDiscount
StudentStatus   →  belongsTo   →  Registration
Payment         →  belongsTo   →  Registration
StudentDiscount →  belongsTo   →  Registration
                →  belongsTo   →  Discount
Discount        →  hasMany     →  StudentDiscount
```

---

## ✅ قواعد التحقق

- رقم الهوية يجب أن يكون فريدًا
- تاريخ نهاية الشعبة يجب أن يكون بعد تاريخ البداية
- الحد الأقصى للطلاب يجب أن يكون أكبر من أو يساوي الحد الأدنى
- التحقق من صحة أرقام الهواتف والتواريخ
- منع إدخال بيانات ناقصة في الحقول المطلوبة

---

## 🛠️ التطوير

```bash
# تشغيل Vite أثناء التطوير
npm run dev

# تشغيل الاختبارات
php artisan test

# تنسيق الكود
php artisan pint

# التراجع عن الـ migrations
php artisan migrate:rollback
```

### Git Workflow

```bash
git checkout -b dev
git add .
git commit -m "وصف التغييرات"
git push origin dev
```

---

## 🗺️ خارطة التطوير القادمة

- [ ] CRUD كامل لـ `registrations`
- [ ] CRUD كامل لـ `payments`
- [ ] CRUD كامل لـ `discounts`
- [ ] CRUD كامل لـ `parents`
- [ ] لوحة إحصائيات متقدمة
- [ ] نظام التقارير والطباعة

---

## 🤝 المساهمة

1. Fork المشروع
2. أنشئ فرعًا جديدًا: `git checkout -b feature/اسم-الميزة`
3. نفّذ تعديلاتك وارفعها
4. افتح Pull Request

---

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT.
