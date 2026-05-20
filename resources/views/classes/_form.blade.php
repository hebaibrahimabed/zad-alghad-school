{{--
    Partial مشترك بين create و edit
    المتغيرات المتاحة: $levels, $class (في حالة edit)
--}}

<div class="row">
    {{-- اسم الشعبة --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">اسم الشعبة <span class="text-danger">*</span></label>
            <input type="text"
                name="name"
                id="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="مثال: أ، ب، الفصل الأول..."
                value="{{ old('name', $class->name ?? '') }}"
                required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- الصف الدراسي --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="level_id">الصف الدراسي <span class="text-danger">*</span></label>
            <select name="level_id"
                id="level_id"
                class="form-control @error('level_id') is-invalid @enderror"
                required>
                <option value="">-- اختر الصف --</option>
                @foreach($levels as $level)
                <option value="{{ $level->id }}"
                    {{ old('level_id', $class->level_id ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
                @endforeach
            </select>
            @error('level_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    {{-- السنة الدراسية --}}
    <div class="col-md-4">
        <div class="form-group">
            <label for="academic_year">السنة الدراسية <span class="text-danger">*</span></label>
            <input type="text"
                name="academic_year"
                id="academic_year"
                class="form-control @error('academic_year') is-invalid @enderror"
                placeholder="مثال: 2024-2025"
                value="{{ old('academic_year', $class->academic_year ?? '') }}"
                required>
            @error('academic_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- السعر --}}
    <div class="col-md-4">
        <div class="form-group">
            <label for="price">الرسوم الدراسية (₪) <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number"
                    name="price"
                    id="price"
                    class="form-control @error('price') is-invalid @enderror"
                    placeholder="0.00"
                    min="0"
                    step="0.01"
                    value="{{ old('price', $class->price ?? '') }}"
                    required>
                <div class="input-group-append">
                    <span class="input-group-text">₪</span>
                </div>
            </div>
            @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    {{-- تاريخ البداية --}}
    <div class="col-md-4">
        <div class="form-group">
            <label for="start_date">تاريخ البداية <span class="text-danger">*</span></label>
            <input type="date"
                name="start_date"
                id="start_date"
                class="form-control @error('start_date') is-invalid @enderror"
                value="{{ old('start_date', isset($class) ? $class->start_date?->format('Y-m-d') : '') }}"
                required>
            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- تاريخ النهاية --}}
    <div class="col-md-4">
        <div class="form-group">
            <label for="end_date">تاريخ النهاية <span class="text-danger">*</span></label>
            <input type="date"
                name="end_date"
                id="end_date"
                class="form-control @error('end_date') is-invalid @enderror"
                value="{{ old('end_date', isset($class) ? $class->end_date?->format('Y-m-d') : '') }}"
                required>
            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- الطاقة الاستيعابية --}}
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="min_capacity">الحد الأدنى للطلاب</label>
            <input type="number"
                name="min_capacity"
                id="min_capacity"
                class="form-control @error('min_capacity') is-invalid @enderror"
                placeholder="اختياري"
                min="1" max="255"
                value="{{ old('min_capacity', $class->min_capacity ?? '') }}">
            @error('min_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="max_capacity">الحد الأقصى للطلاب</label>
            <input type="number"
                name="max_capacity"
                id="max_capacity"
                class="form-control @error('max_capacity') is-invalid @enderror"
                placeholder="اختياري"
                min="1" max="255"
                value="{{ old('max_capacity', $class->max_capacity ?? '') }}">
            @error('max_capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
// تحقق من أن end_date بعد start_date
document.getElementById('start_date').addEventListener('change', function () {
    document.getElementById('end_date').min = this.value;
});
</script>
@endpush
