# هياكل جداول قاعدة البيانات — مدرسة زاد الغد

---

## 1. students — الطلاب
> جدول موجود مسبقاً — يحتاج migration للتعديل

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | مفتاح أساسي جديد |
| `parent_id` | bigint | FK → parents.id, nullable | ولي الأمر |
| `IDNumber` | string(20) | UNIQUE, NOT NULL | رقم الهوية |
| `studentName` | string(20) | NOT NULL | الاسم الأول |
| `FatherName` | string(20) | NOT NULL | اسم الأب |
| `GrandfatherName` | string(20) | nullable | اسم الجد |
| `lastName` | string(20) | NOT NULL | اسم العائلة |
| `dateOfBirth` | date | NOT NULL | تاريخ الميلاد |
| `gender` | enum(male, female) | NOT NULL | الجنس |
| `gradeByAge` | string(20) | nullable | الصف حسب العمر |
| `lastCertificateObtained` | string(20) | nullable | آخر شهادة |
| `Parentmobile` | string(15) | NOT NULL | هاتف ولي الأمر |
| `RelativeGuardian` | string(20) | nullable | اسم الوصي |
| `healthCondition` | enum(Healthy, disabled, injured) | default: Healthy | الحالة الصحية |
| `registrationDate` | date | NOT NULL | تاريخ التسجيل |
| `created_at` / `updated_at` | timestamp | auto | |
| `deleted_at` | timestamp | nullable | Soft Delete |

> **التعديلات المطلوبة على هذا الجدول:**
> - إضافة `id` bigint كـ PK
> - إضافة `parent_id` FK
> - تحويل `IDNumber` من PK إلى UNIQUE
> - حذف `paymentStatus`
> - حذف `RegistrationStatusMinistry`
 > - حذف    `OrphanStatus`

---

## 2. parents — أولياء الأمور

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `first_name` | string(20) | NOT NULL | الاسم الأول |
| `second_name` | string(20) | NOT NULL | اسم الأب |
| `third_name` | string(20) | NOT NULL | اسم العائلة |
| `gender` | enum(male, female) | NOT NULL | الجنس |
| `birth_date` | date | nullable | تاريخ الميلاد |
| `phone` | string(15) | NOT NULL | رقم الهاتف |
| `national_id` | string(20) | UNIQUE, nullable | رقم الهوية |
| `relation` | enum(father, mother, brother, sister, uncle, aunt, grandfather, grandmother, other) | NOT NULL | صلة القرابة بالطالب |
| `address` | string | nullable | العنوان |
| `housing_status` | enum(owned, rented, tent, displaced) | nullable | حالة السكن: ملك / إيجار / خيمة / نازح |
| `work` | string(50) | nullable | العمل |
| `orphan_status_student` | enum(not_orphan, father, mother, both) | default: not_orphan | حالة يتم الأبناء: ليس يتيماً / يتيم الأب / يتيم الأم / يتيم الأبوين |
| `created_at` / `updated_at` | timestamp | auto | |
| `deleted_at` | timestamp | nullable | Soft Delete |

---

## 3. levels — الصفوف والدورات

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `name` | string | NOT NULL | اسم الصف أو الدورة |
| `type` | enum(grade, course) | NOT NULL | صف دراسي / دورة |
| `min_age` | tinyint | nullable | الحد الأدنى للعمر — للصفوف فقط |
| `max_age` | tinyint | nullable | الحد الأقصى للعمر — للصفوف فقط |
| `is_active` | boolean | default: true | هل متاح للتسجيل |
| `created_at` / `updated_at` | timestamp | auto | |
| `deleted_at` | timestamp | nullable | Soft Delete |

---

## 4. classes — الشعب الدراسية

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `level_id` | bigint | FK → levels.id, NOT NULL | الصف أو الدورة التابعة لها |
| `name` | string | NOT NULL | اسم الشعبة — مثل "أ" أو "المجموعة الصباحية" |
| `academic_year` | string | NOT NULL | العام الدراسي — مثل "2024-2025" |
| `price` | decimal(8,2) | NOT NULL | رسوم هذه الشعبة بالشيكل |
| `start_date` | date | NOT NULL | تاريخ بداية الشعبة |
| `end_date` | date | NOT NULL | تاريخ نهاية الشعبة |
| `min_capacity` | tinyint | nullable | الحد الأدنى لعدد الطلاب |
| `max_capacity` | tinyint | nullable | الحد الأقصى لعدد الطلاب |
| `created_at` / `updated_at` | timestamp | auto | |
| `deleted_at` | timestamp | nullable | Soft Delete |

---

## 5. registrations — التسجيلات
> المحور المركزي للنظام

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `student_id` | bigint | FK → students.id, NOT NULL | الطالب المسجَّل |
| `class_id` | bigint | FK → classes.id, NOT NULL | الشعبة المسجَّل فيها |
| `registration_date` | date | NOT NULL | تاريخ التسجيل الفعلي |
| `ministry_registration` | enum(pending, registered, exempt) | NOT NULL | حالة التسجيل في الوزارة |
| `current_status` | enum(active, suspended, withdrawn) | NOT NULL | الحالة الحالية للطالب |
| `notes` | text | nullable | ملاحظات إدارية |
| `created_at` / `updated_at` | timestamp | auto | |
| `deleted_at` | timestamp | nullable | Soft Delete |

---

## 6. student_statuses — سجل حالات الطالب

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `registration_id` | bigint | FK → registrations.id, NOT NULL | التسجيل المرتبط |
| `status` | enum(active, suspended, withdrawn) | NOT NULL | منتظم / متوقف / مفصول |
| `status_date` | date | NOT NULL | تاريخ تغيير الحالة |
| `notes` | text | nullable | سبب التغيير |
| `created_at` / `updated_at` | timestamp | auto | |

---

## 7. payments — الدفعات

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `registration_id` | bigint | FK → registrations.id, NOT NULL | التسجيل المرتبط |
| `amount_due_month` | decimal(8,2) | NOT NULL | المبلغ المستحق لهذا الشهر |
| `total_outstanding` | decimal(8,2) | NOT NULL | إجمالي المبلغ المتراكم غير المدفوع — يُحدَّث عند كل دفعة |
| `amount_paid` | decimal(8,2) | NOT NULL | المبلغ المدفوع في هذه الدفعة |
| `due_date` | date | NOT NULL | تاريخ استحقاق الشهر |
| `paid_at` | timestamp | nullable | وقت الدفع الفعلي — null إذا لم يُدفع |
| `payment_method` | enum(cash, app) | nullable | طريقة الدفع: كاش / تطبيق |
| `status` | enum(pending, partial, paid) | NOT NULL | pending / partial / paid |
| `notes` | text | nullable | ملاحظات على الدفعة |
| `created_at` / `updated_at` | timestamp | auto | |

---

## 8. discounts — أنواع الخصومات

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `name` | string | NOT NULL | اسم الخصم — مثل "خصم الإخوة" |
| `type` | enum(general, special) | NOT NULL | عام (تلقائي) / خاص (يدوي) |
| `value` | decimal(8,2) | NOT NULL | قيمة الخصم |
| `value_type` | enum(percentage, fixed) | NOT NULL | نسبة مئوية / مبلغ ثابت |
| `start_date` | date | NOT NULL | تاريخ بداية فعالية الخصم |
| `end_date` | date | nullable | تاريخ نهاية فعالية الخصم — null يعني مفتوح |
| `is_active` | boolean | default: true | هل الخصم مفعّل |
| `notes` | text | nullable | شرح الخصم وشروطه |
| `created_at` / `updated_at` | timestamp | auto | |

---

## 9. student_discounts — الخصومات المطبقة

| الحقل | النوع | القيود | ملاحظة |
|---|---|---|---|
| `id` | bigint | PK, Auto Increment | |
| `registration_id` | bigint | FK → registrations.id, NOT NULL | التسجيل المرتبط |
| `discount_id` | bigint | FK → discounts.id, NOT NULL | نوع الخصم |
| `applied_value` | decimal(8,2) | NOT NULL | المبلغ المخصوم فعلياً بالشيكل |
| `reason` | string | nullable | سبب الخصم — مثل "أخ ثانٍ" |
| `applied_by` | bigint | nullable | ID المستخدم الذي طبّق الخصم |
| `created_at` / `updated_at` | timestamp | auto | |

---

## ملخص العلاقات

```
parents (1) ──────── (∞) students
students (1) ──────── (∞) registrations
levels   (1) ──────── (∞) classes
classes  (1) ──────── (∞) registrations
registrations (1) ── (∞) student_statuses
registrations (1) ── (∞) payments
registrations (1) ── (∞) student_discounts
discounts (1) ─────── (∞) student_discounts
```
