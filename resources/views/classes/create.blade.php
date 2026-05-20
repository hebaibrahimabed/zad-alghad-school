@extends('layouts.zad')


@section('title', 'إضافة شعبة جديدة')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">إضافة شعبة جديدة</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الشعب</a></li>
                    <li class="breadcrumb-item active">إضافة</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chalkboard ml-1"></i> بيانات الشعبة</h3>
                    </div>
                    <form method="POST" action="{{ route('classes.store') }}">
                        @csrf
                        <div class="card-body">@include('classes._form')</div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save ml-1"></i> حفظ
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
