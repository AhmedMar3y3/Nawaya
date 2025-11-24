@extends('Admin.layout')

@section('styles')
<style>
    .create-container {
        padding: 2rem 0;
    }
    
    .form-card {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        border: 1px solid rgba(255,255,255,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-title {
        color: #fff;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 2rem 0;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #fff;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
    }
    
    .form-control option {
        background: #1E293B;
        color: #fff;
    }
    
    .btn-submit {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-back {
        padding: 0.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.15);
    }
</style>
@endsection

@section('main')
<div class="create-container" dir="rtl">
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-back">
        <i class="fas fa-arrow-right me-2"></i>
        العودة
    </a>

    <div class="form-card">
        <h1 class="form-title">
            <i class="fas fa-plus-circle me-2"></i>
            إضافة اشتراك جديد
        </h1>

        <form action="{{ route('admin.subscriptions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="user_id">المستخدم *</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">اختر المستخدم</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email ?? $user->phone }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">النوع *</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="monthly" {{ old('type') === 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ old('type') === 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
                @error('type')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="starts_at">تاريخ البدء</label>
                <input 
                    type="date" 
                    name="starts_at" 
                    id="starts_at" 
                    class="form-control"
                    value="{{ old('starts_at', now()->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                >
                @error('starts_at')
                    <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save me-2"></i>
                    حفظ
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn-back">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
