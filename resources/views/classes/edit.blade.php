@extends('layouts.zad')

@section('title', 'تعديل الشعبة')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">تعديل: {{ $class->name }}</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الشعب</a></li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit ml-1"></i> تعديل بيانات الشعبة</h3>
                    </div>
                    <form method="POST" action="{{ route('classes.update', $class) }}">
                        @csrf @method('PUT')
                        <div class="card-body">@include('classes._form')</div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save ml-1"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-right ml-1"></i> رجوع
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
