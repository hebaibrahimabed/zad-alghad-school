<?php

namespace App\Http\Controllers\payment_sys;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Level;
use App\Http\Requests\SchoolClassRequest;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::with('level');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $classes       = $query->latest()->paginate(30)->appends($request->query());

        $levels        = Level::all();
        $academicYears = SchoolClass::withTrashed(false)
                            ->distinct()
                            ->pluck('academic_year');

        return view('classes.index', compact('classes', 'levels', 'academicYears'));
    }

    public function create()
    {
        $levels = Level::all();
        return view('classes.create', compact('levels'));
    }

    public function store(SchoolClassRequest $request)
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')
            ->with('success', 'تم إضافة الشعبة بنجاح');
    }

    public function show(SchoolClass $class)
    {
        $class->load('level', 'registrations.student');
        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class)
    {
        $levels = Level::all();
        return view('classes.edit', compact('class', 'levels'));
    }

    public function update(SchoolClassRequest $request, SchoolClass $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.index')
            ->with('success', 'تم تعديل الشعبة بنجاح');
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->registrations()->count() > 0) {
            return redirect()->route('classes.index')
            
                ->with('error', 'لا يمكن حذف الشعبة لوجود طلاب مسجلين فيها');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'تم حذف الشعبة بنجاح');
    }
}
