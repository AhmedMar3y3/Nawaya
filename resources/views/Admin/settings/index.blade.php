@extends('Admin.layout')

@section('styles')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

    <style>
        .settings-container {
            padding: 2rem 0;
        }

        .page-header {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .page-title {
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .page-subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-left: 4px solid #ef4444;
        }

        /* Settings Cards */
        .settings-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .settings-card {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .settings-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(56, 189, 248, 0.5), transparent);
        }

        .card-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .card-title i {
            color: #38bdf8;
            font-size: 1.2rem;
        }

        .card-subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0.5rem 0 0 0;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #38bdf8;
            font-size: 0.9rem;
            width: 16px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            width: 100%;
            direction: rtl;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
            color: #fff;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.7);
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-left: 0.5rem;
            cursor: pointer;
        }

        .form-check-label {
            color: #94a3b8;
            cursor: pointer;
            margin-right: 0.5rem;
        }

        .form-text {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-text i {
            color: #38bdf8;
        }

        .info-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            min-height: 45px;
            display: flex;
            align-items: center;
            color: #fff;
        }

        /* CKEditor Styles */
        .ck-editor__editable {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0 0 10px 10px !important;
            min-height: 300px !important;
            direction: rtl;
        }

        .ck-editor__editable:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25) !important;
        }

        .ck.ck-editor {
            border-radius: 10px !important;
            overflow: hidden !important;
        }

        .ck.ck-toolbar {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-bottom: none !important;
            border-radius: 10px 10px 0 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .ck.ck-toolbar__items {
            padding: 0.5rem !important;
        }

        .ck.ck-toolbar__separator {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .ck.ck-button {
            color: #94a3b8 !important;
        }

        .ck.ck-button:hover {
            background: rgba(56, 189, 248, 0.1) !important;
            color: #38bdf8 !important;
        }

        .ck.ck-button.ck-on {
            background: rgba(56, 189, 248, 0.2) !important;
            color: #38bdf8 !important;
        }

        .ck.ck-dropdown__panel {
            background: rgba(30, 41, 59, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 8px !important;
        }

        .ck.ck-list__item {
            color: #94a3b8 !important;
        }

        .ck.ck-list__item:hover {
            background: rgba(56, 189, 248, 0.1) !important;
            color: #38bdf8 !important;
        }

        /* Button Styles */
        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: #fff;
            text-decoration: none;
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading State */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Tabs Styles */
        .settings-tabs {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
        }

        .tab-button {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 10px;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .tab-button:hover {
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.05);
        }

        .tab-button.active {
            color: #fff;
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
        }

        .tab-button i {
            font-size: 1.1rem;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .settings-card {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .settings-tabs {
                flex-direction: column;
                gap: 0.5rem;
            }

            .tab-button {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1rem;
            }
        }

        /* Validation Styles */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25) !important;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .invalid-feedback i {
            color: #ef4444;
        }

        .logo-preview-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-preview {
            object-fit: contain;
            transition: all 0.3s ease;
            max-width: 120px;
            max-height: 120px;
        }

        .logo-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.75rem 1rem;
            background: rgba(56, 189, 248, 0.1);
            border: 1px dashed rgba(56, 189, 248, 0.3);
            border-radius: 10px;
            color: #38bdf8;
            transition: all 0.3s ease;
            text-align: center;
            justify-content: center;
        }

        .file-input-label:hover {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.5);
        }

        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-wrapper-partner {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input-label-partner {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.75rem 1rem;
            background: rgba(56, 189, 248, 0.1);
            border: 1px dashed rgba(56, 189, 248, 0.3);
            border-radius: 10px;
            color: #38bdf8;
            transition: all 0.3s ease;
            text-align: center;
            justify-content: center;
            width: 100%;
        }

        .file-input-label-partner:hover {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.5);
        }

        .partner-file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Items List Styles */
        .items-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .item-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .item-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .item-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .item-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .item-link {
            color: #38bdf8;
            font-size: 0.85rem;
            text-decoration: none;
            word-break: break-all;
        }

        .item-link:hover {
            text-decoration: underline;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .btn-action-small {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-edit-small {
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
        }

        .btn-edit-small:hover {
            background: rgba(56, 189, 248, 0.3);
        }

        .btn-delete-small {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .btn-delete-small:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-view-small {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .btn-view-small:hover {
            background: rgba(16, 185, 129, 0.3);
        }

        .empty-state-small {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
        }

        .empty-state-small i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        /* DR Hope Styles */
        .dr-hope-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dr-hope-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dr-hope-section.full-width {
            grid-column: 1 / -1;
        }

        .section-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            color: #fff;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .section-title i {
            color: #38bdf8;
            font-size: 1.2rem;
        }

        .dr-hope-form {
            margin-bottom: 2rem;
        }

        .btn-add-gradient {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            justify-content: center;
        }

        .btn-add-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
            color: #fff;
        }

        .dr-hope-items-list {
            display: flex;
                flex-direction: column;
            gap: 1rem;
        }

        .dr-hope-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .dr-hope-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .dr-hope-item-content {
            flex: 1;
        }

        .dr-hope-item-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .dr-hope-item-link {
            color: #38bdf8;
            font-size: 0.9rem;
            word-break: break-all;
            text-decoration: none;
        }

        .dr-hope-item-link:hover {
            text-decoration: underline;
        }

        .dr-hope-item-actions {
            display: flex;
                gap: 0.5rem;
            }

        .btn-delete-item {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete-item:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .dr-hope-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .dr-hope-image-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .dr-hope-image-item img {
                width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dr-hope-image-delete {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(239, 68, 68, 0.9);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dr-hope-image-delete:hover {
            background: rgba(239, 68, 68, 1);
        }

        @media (max-width: 768px) {
            .dr-hope-container {
                grid-template-columns: 1fr;
            }
        }

        /* Partners Styles */
        .partners-container {
            display: flex;
                justify-content: center;
        }

        .partners-section {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 1200px;
        }

        .section-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin: 0.5rem 0 0 0;
        }

        .btn-add-partner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-add-partner:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: #fff;
        }

        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .partner-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .partner-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .partner-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .partner-title {
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }

        .partner-description {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }

        .partner-link {
            color: #38bdf8;
            font-size: 0.85rem;
            text-decoration: none;
            word-break: break-all;
            display: block;
            margin-bottom: 1rem;
        }

        .partner-link:hover {
            text-decoration: underline;
        }

        .partner-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .partners-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .partner-empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
            width: 100%;
            grid-column: 1 / -1;
        }

        .partner-empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .partner-empty-state p {
            font-size: 1.1rem;
            color: #94a3b8;
            margin: 0;
        }

        .image-preview-container {
            margin-top: 1rem;
            text-align: center;
        }

        .image-preview-placeholder {
                width: 100%;
            max-width: 300px;
            height: 200px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
                justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            margin: 0 auto;
            color: #94a3b8;
            flex-direction: column;
            gap: 0.5rem;
        }

        .image-preview-placeholder img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .image-preview-placeholder.has-image {
            border-style: solid;
            border-color: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .partners-header-row {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('main')
    <div class="settings-container" dir="rtl">

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        <!-- Settings Tabs -->
        <div class="settings-tabs">
            <button type="button" class="tab-button active" data-tab="general-settings">
                <i class="fas fa-cog"></i>
                الإعدادات العامة
            </button>
            <button type="button" class="tab-button" data-tab="payment-settings">
                <i class="fas fa-credit-card"></i>
                الدفع والحساب البنكي
            </button>
            <button type="button" class="tab-button" data-tab="dr-hope-settings">
                <i class="fas fa-images"></i>
                محتوي DRHOPE
            </button>
            <button type="button" class="tab-button" data-tab="partners-settings">
                <i class="fas fa-handshake"></i>
                الشركاء
            </button>
            <button type="button" class="tab-button" data-tab="reviews-settings">
                <i class="fas fa-star"></i>
                التقييمات
            </button>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Settings Tab -->
            <div class="tab-content active" id="general-settings">
                <div class="settings-grid">

                    <!-- Main Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                                <i class="fas fa-home"></i>
                                الإعدادات الرئيسية
                        </h3>
                            <p class="card-subtitle">إدارة الإعدادات الأساسية للموقع</p>
                    </div>

                    <div class="form-group">
                            <label for="welcome" class="form-label">
                                <i class="fas fa-hand-wave"></i>
                                رسالة الترحيب
                        </label>
                            <input type="text" class="form-control @error('welcome') is-invalid @enderror"
                                id="welcome" name="welcome" value="{{ $data['welcome'] ?? '' }}"
                                placeholder="أدخل رسالة الترحيب">
                            @error('welcome')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo" class="form-label">
                                <i class="fas fa-image"></i>
                                الشعار
                            </label>
                            <div class="file-input-wrapper">
                                <label for="logo" class="file-input-label" id="logoLabel">
                                    <i class="fas fa-upload"></i>
                                    <span id="logoLabelText">اختر صورة الشعار</span>
                                </label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                    id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                            </div>
                            <input type="hidden" id="logo_url" name="logo_url" value="{{ $data['logo'] ?? '' }}">
                            @error('logo')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                                اختر صورة الشعار (JPG, PNG, GIF - الحد الأقصى 5MB)
                        </div>
                            <div class="logo-preview-container" style="margin-top: 1rem;">
                                @if(isset($data['logo']) && $data['logo'])
                                    <img src="{{ $data['logo'] }}" alt="Logo Preview" id="logoPreview" class="logo-preview">
                                @else
                                    <img src="" alt="Logo Preview" id="logoPreview" class="logo-preview" style="display: none;">
                                @endif
                    </div>
                </div>

                        <div class="form-group">
                            <label for="whatsapp" class="form-label">
                                <i class="fab fa-whatsapp"></i>
                                رقم الواتساب
                            </label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror"
                                id="whatsapp" name="whatsapp" value="{{ $data['whatsapp'] ?? '' }}"
                                placeholder="أدخل رقم الواتساب">
                            @error('whatsapp')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Invoice Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                                <i class="fas fa-file-invoice"></i>
                                إعدادات الفواتير
                        </h3>
                            <p class="card-subtitle">إدارة معلومات الفواتير</p>
                    </div>

                    <div class="form-group">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                العنوان
                        </label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" value="{{ $data['address'] ?? '' }}"
                                placeholder="أدخل العنوان">
                            @error('address')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number" class="form-label">
                                <i class="fas fa-phone"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                id="phone_number" name="phone_number" value="{{ $data['phone_number'] ?? '' }}"
                                placeholder="أدخل رقم الهاتف">
                            @error('phone_number')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                    </div>
                            @enderror
                </div>

                        <div class="form-group">
                            <label for="tax_number" class="form-label">
                                <i class="fas fa-id-card"></i>
                                الرقم الضريبي
                            </label>
                            <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                id="tax_number" name="tax_number" value="{{ $data['tax_number'] ?? '' }}"
                                placeholder="أدخل الرقم الضريبي">
                            @error('tax_number')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Workshop Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                إعدادات الورش
                        </h3>
                            <p class="card-subtitle">إدارة سياسات الورش</p>
                    </div>

                    <div class="form-group">
                            <label for="workshop_policy" class="form-label">
                            <i class="fas fa-file-contract"></i>
                                سياسة الورش
                        </label>
                            <textarea id="workshop_policy" name="workshop_policy" class="form-control @error('workshop_policy') is-invalid @enderror" rows="10">{{ $data['workshop_policy'] ?? '' }}</textarea>
                            @error('workshop_policy')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            يمكنك استخدام محرر النصوص لإضافة تنسيق HTML للمحتوى
                    </div>
                    </div>

                    <div class="form-group">
                            <label for="workshop_returning_policy" class="form-label">
                                <i class="fas fa-undo"></i>
                                سياسة الإرجاع للورش
                        </label>
                            <textarea id="workshop_returning_policy" name="workshop_returning_policy" class="form-control @error('workshop_returning_policy') is-invalid @enderror" rows="10">{{ $data['workshop_returning_policy'] ?? '' }}</textarea>
                            @error('workshop_returning_policy')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                            يمكنك استخدام محرر النصوص لإضافة تنسيق HTML للمحتوى
                </div>
                </div>
            </div>

                    <!-- Social Media Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                                <i class="fas fa-share-alt"></i>
                                وسائل التواصل الاجتماعي
                        </h3>
                            <p class="card-subtitle">إدارة روابط وسائل التواصل الاجتماعي</p>
                    </div>

                    <div class="form-group">
                            <label for="facebook" class="form-label">
                                <i class="fab fa-facebook"></i>
                                فيسبوك
                        </label>
                            <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                                id="facebook" name="facebook" value="{{ $data['facebook'] ?? '' }}"
                                placeholder="https://facebook.com/yourpage">
                            @error('facebook')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                            <label for="instgram" class="form-label">
                                <i class="fab fa-instagram"></i>
                                إنستغرام
                        </label>
                            <input type="url" class="form-control @error('instgram') is-invalid @enderror"
                                id="instgram" name="instgram" value="{{ $data['instgram'] ?? '' }}"
                                placeholder="https://instagram.com/yourpage">
                            @error('instgram')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div class="form-group">
                            <label for="tiktok" class="form-label">
                                <i class="fab fa-tiktok"></i>
                                تيك توك
                            </label>
                            <input type="url" class="form-control @error('tiktok') is-invalid @enderror"
                                id="tiktok" name="tiktok" value="{{ $data['tiktok'] ?? '' }}"
                                placeholder="https://tiktok.com/@yourpage">
                            @error('tiktok')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                    </div>
                            @enderror
                </div>

                        <div class="form-group">
                            <label for="twitter" class="form-label">
                                <i class="fab fa-twitter"></i>
                                تويتر
                            </label>
                            <input type="url" class="form-control @error('twitter') is-invalid @enderror"
                                id="twitter" name="twitter" value="{{ $data['twitter'] ?? '' }}"
                                placeholder="https://twitter.com/yourpage">
                            @error('twitter')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <div class="form-group">
                            <label for="snapchat" class="form-label">
                                <i class="fab fa-snapchat"></i>
                                سناب شات
                        </label>
                            <input type="url" class="form-control @error('snapchat') is-invalid @enderror"
                                id="snapchat" name="snapchat" value="{{ $data['snapchat'] ?? '' }}"
                                placeholder="https://snapchat.com/add/yourpage">
                            @error('snapchat')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <button type="submit" class="btn-save" id="saveGeneralBtn">
                            <i class="fas fa-save"></i>
                            حفظ الإعدادات العامة
                        </button>
                    </div>
                    
                </div>
            <!-- Payment and Bank Account Settings Tab -->
            <div class="tab-content" id="payment-settings">
                <div class="settings-grid">

                    <!-- Bank Account Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">
                                <i class="fas fa-university"></i>
                                معلومات الحساب البنكي
                        </h3>
                            <p class="card-subtitle">إدارة معلومات الحساب البنكي</p>
                    </div>

                    <div class="form-group">
                            <label for="account_name" class="form-label">
                                <i class="fas fa-user"></i>
                                اسم الحساب
                        </label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                                id="account_name" name="account_name" value="{{ $data['account_name'] ?? '' }}"
                                placeholder="أدخل اسم الحساب">
                            @error('account_name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div class="form-group">
                            <label for="bank_name" class="form-label">
                                <i class="fas fa-landmark"></i>
                                اسم البنك
                            </label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                id="bank_name" name="bank_name" value="{{ $data['bank_name'] ?? '' }}"
                                placeholder="أدخل اسم البنك">
                            @error('bank_name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <div class="form-group">
                            <label for="IBAN_number" class="form-label">
                                <i class="fas fa-barcode"></i>
                                رقم الآيبان
                        </label>
                            <input type="text" class="form-control @error('IBAN_number') is-invalid @enderror"
                                id="IBAN_number" name="IBAN_number" value="{{ $data['IBAN_number'] ?? '' }}"
                                placeholder="أدخل رقم الآيبان">
                            @error('IBAN_number')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div class="form-group">
                            <label for="account_number" class="form-label">
                                <i class="fas fa-hashtag"></i>
                                رقم الحساب
                            </label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                                id="account_number" name="account_number" value="{{ $data['account_number'] ?? '' }}"
                                placeholder="أدخل رقم الحساب">
                            @error('account_number')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                    </div>

                    <div class="form-group">
                            <label for="swift" class="form-label">
                                <i class="fas fa-exchange-alt"></i>
                                رمز السويفت
                        </label>
                            <input type="text" class="form-control @error('swift') is-invalid @enderror"
                                id="swift" name="swift" value="{{ $data['swift'] ?? '' }}"
                                placeholder="أدخل رمز السويفت">
                            @error('swift')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                    </div>

                    <!-- Payment Settings -->
                    <div class="settings-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-credit-card"></i>
                                إعدادات الدفع
                            </h3>
                            <p class="card-subtitle">إدارة طرق الدفع المتاحة</p>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="online_payment" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="online_payment" name="online_payment" value="1"
                                    {{ isset($data['online_payment']) && $data['online_payment'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="online_payment">
                                    <i class="fas fa-globe"></i>
                                    تفعيل الدفع الإلكتروني
                                </label>
                            </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i>
                                تفعيل خيار الدفع الإلكتروني للمستخدمين
                        </div>
                    </div>

                        <div class="form-group">
                            <input type="hidden" name="bank_transfer" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bank_transfer" name="bank_transfer" value="1"
                                    {{ isset($data['bank_transfer']) && $data['bank_transfer'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="fas fa-university"></i>
                                    تفعيل التحويل البنكي
                                </label>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                تفعيل خيار التحويل البنكي للمستخدمين
                            </div>
                        </div>
                </div>
                </div>

                <div style="text-align: center; margin-top: 2rem;">
                    <button type="submit" class="btn-save" id="savePaymentBtn">
                        <i class="fas fa-save"></i>
                        حفظ إعدادات الدفع
                    </button>
                </div>
            </div>

        </form>

        <!-- DR Hope Settings Tab -->
        <div class="tab-content" id="dr-hope-settings">
            <div class="dr-hope-container">
                <!-- Video Section (Left Column) -->
                <div class="dr-hope-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-video"></i>
                            فيديوهات العرض
                        </h3>
                    </div>
                    <form id="videoForm" class="dr-hope-form">
                        <input type="hidden" name="type" value="video">
                        <div class="form-group">
                            <label class="form-label">عنوان الفيديو</label>
                            <input type="text" name="title" class="form-control" placeholder="أدخل عنوان الفيديو" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">رابط الفيديو (Embed)</label>
                            <input type="url" name="link" class="form-control" placeholder="أدخل رابط الفيديو" required>
                        </div>
                        <button type="submit" class="btn-add-gradient">
                            <i class="fas fa-plus"></i>
                            إضافة فيديو
                </button>
                    </form>
                    <div id="video-list" class="dr-hope-items-list">
                        <!-- Videos will be loaded here -->
            </div>
                </div>

                <!-- Image Section (Middle Column) -->
                <div class="dr-hope-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-images"></i>
                            ألبوم الصور
                        </h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">إضافة صور جديدة</label>
                        <form id="imageForm" class="dr-hope-form" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="image">
                            <div class="file-input-wrapper">
                                <label for="imageFile" class="file-input-label">
                                    <i class="fas fa-upload"></i>
                                    <span id="imageLabelText">اختر الصور</span>
                                </label>
                                <input type="file" id="imageFile" name="image" class="form-control" accept="image/*" onchange="handleImageFile(this)">
                            </div>
                            <button type="submit" class="btn-add-gradient" style="margin-top: 1rem;">
                                <i class="fas fa-plus"></i>
                                إضافة الصور
                            </button>
        </form>
                    </div>
                    <div id="image-list" class="dr-hope-images-grid">
                        <!-- Images will be loaded here -->
                    </div>
                </div>

                <!-- Instagram Section (Bottom) -->
                <div class="dr-hope-section full-width">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fab fa-instagram"></i>
                            روابط بثوث انستجرام
                        </h3>
                    </div>
                    <form id="instagramForm" class="dr-hope-form">
                        <input type="hidden" name="type" value="instagram">
                        <div class="form-group">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="title" class="form-control" placeholder="أدخل عنوان البث" required>
                        </div>
                        <div class="form-group">
                            <input type="url" name="link" class="form-control" placeholder=".../https://instagram.com" required>
                        </div>
                        <button type="submit" class="btn-add-gradient">
                            <i class="fas fa-plus"></i>
                            إضافة رابط
                        </button>
                    </form>
                    <div id="instagram-list" class="dr-hope-items-list">
                        <!-- Instagram links will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Partners Settings Tab -->
        <div class="tab-content" id="partners-settings">
            <div class="partners-container">
                <div class="partners-section">
                    <div class="partners-header-row">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-handshake"></i>
                                إدارة الشركاء
                            </h3>
                            <p class="section-subtitle">إضافة وتعديل وحذف الشركاء</p>
                        </div>
                        <div>
                            <button type="button" class="btn-add-partner" onclick="window.openPartnerCreateModal()">
                                <i class="fas fa-plus"></i>
                             إضافة شريك جديد
                            </button>
                        </div>
                    </div>
                    <div id="partners-list" class="partners-grid">
                        <!-- Partners will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Settings Tab -->
        <div class="tab-content" id="reviews-settings">
            <div style="padding: 2rem; text-align: center;">
                <div style="margin-bottom: 2rem;">
                    <i class="fas fa-star" style="font-size: 4rem; color: #fbbf24; margin-bottom: 1rem;"></i>
                    <h3 style="color: #fff; margin-bottom: 1rem;">إدارة التقييمات</h3>
                    <p style="color: #94a3b8; margin-bottom: 2rem;">عرض وإدارة تقييمات المستخدمين للورش</p>
                    <a href="{{ route('admin.reviews.index') }}" class="btn-create" style="text-decoration: none; display: inline-block;">
                        <i class="fas fa-star"></i>
                        الانتقال إلى صفحة التقييمات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- DR Hope Modals -->
    <div class="modal fade" id="drHopeModal" tabindex="-1" aria-labelledby="drHopeModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="drHopeModalLabel">إضافة محتوى</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="drHopeModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Modals -->
    <div class="modal fade" id="partnerModal" tabindex="-1" aria-labelledby="partnerModalLabel" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partnerModalLabel">إضافة شريك</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="partnerModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Switching
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            const editors = {};

            const editorIds = ['workshop_policy', 'workshop_returning_policy'];

            editorIds.forEach(function(id) {
                ClassicEditor
                    .create(document.querySelector('#' + id), {
                        language: 'ar',
                        direction: 'rtl',
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', '|',
                                'link', '|',
                                'undo', 'redo'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Paragraph',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Heading 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Heading 2',
                                    class: 'ck-heading_heading2'
                                },
                                {
                                    model: 'heading3',
                                    view: 'h3',
                                    title: 'Heading 3',
                                    class: 'ck-heading_heading3'
                                }
                            ]
                        },
                        link: {
                            addTargetToExternalLinks: true
                        },
                        table: {
                            contentToolbar: [
                                'tableColumn',
                                'tableRow',
                                'mergeTableCells'
                            ]
                        }
                    })
                    .then(editor => {
                        editors[id] = editor;

                        editor.setData(document.querySelector('#' + id).value);

                        editor.model.document.on('change:data', () => {
                            document.querySelector('#' + id).value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor for ' + id + ':', error);
                    });
            });

            const form = document.getElementById('settingsForm');
            const saveGeneralBtn = document.getElementById('saveGeneralBtn');
            const savePaymentBtn = document.getElementById('savePaymentBtn');

            form.addEventListener('submit', function(e) {
                Object.keys(editors).forEach(function(id) {
                    if (editors[id]) {
                        document.querySelector('#' + id).value = editors[id].getData();
                    }
                });

                const clickedBtn = e.submitter || (saveGeneralBtn && saveGeneralBtn.contains(document.activeElement) ? saveGeneralBtn : savePaymentBtn);
                
                if (clickedBtn) {
                    clickedBtn.disabled = true;
                    clickedBtn.innerHTML = '<span class="spinner"></span> جاري الحفظ...';
                }
                
                form.classList.add('loading');
            });

            // Logo preview function
            window.previewLogo = function(input) {
                const preview = document.getElementById('logoPreview');
                const logoUrlInput = document.getElementById('logo_url');
                const labelText = document.getElementById('logoLabelText');
                
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const reader = new FileReader();
                    
                    // Update label text with file name
                    labelText.textContent = file.name;
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        logoUrlInput.value = ''; // Clear old URL when new file is selected
                    };
                    
                    reader.readAsDataURL(file);
                } else {
                    // If no file selected, show existing logo if available
                    if (logoUrlInput.value) {
                        preview.src = logoUrlInput.value;
                        preview.style.display = 'block';
                        labelText.textContent = 'اختر صورة الشعار';
                    } else {
                        preview.style.display = 'none';
                        labelText.textContent = 'اختر صورة الشعار';
                    }
                }
            };

            // DR Hope Functions
            const drHopeBaseUrl = '{{ url("dr-hope") }}';
            
            function loadDrHopeItems(type) {
                fetch(`${drHopeBaseUrl}/${type}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (type === 'image') {
                                renderDrHopeImages(data.items);
                            } else {
                                renderDrHopeItems(type, data.items);
                            }
                        }
                    })
                    .catch(error => console.error('Error loading items:', error));
            }

            function renderDrHopeItems(type, items) {
                const container = document.getElementById(`${type}-list`);
                if (items.length === 0) {
                    container.innerHTML = '';
                    return;
                }

                container.innerHTML = items.map(item => {
                    return `
                        <div class="dr-hope-item">
                            <div class="dr-hope-item-content">
                                <div class="dr-hope-item-title">${item.title || 'بث مباشر #' + item.id}</div>
                                <a href="${item.link}" target="_blank" class="dr-hope-item-link">${item.link}</a>
                            </div>
                            <div class="dr-hope-item-actions">
                                <button class="btn-delete-item" onclick="window.deleteDrHopeItem(${item.id}, '${type}')" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function renderDrHopeImages(items) {
                const container = document.getElementById('image-list');
                if (items.length === 0) {
                    container.innerHTML = '';
                    return;
                }

                container.innerHTML = items.map(item => {
                    return `
                        <div class="dr-hope-image-item">
                            <img src="${item.image}" alt="Image">
                            <button class="dr-hope-image-delete" onclick="window.deleteDrHopeItem(${item.id}, 'image')" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }).join('');
            }

            function handleImageFile(input) {
                const labelText = document.getElementById('imageLabelText');
                if (input.files && input.files[0]) {
                    labelText.textContent = input.files[0].name;
                } else {
                    labelText.textContent = 'اختر الصور';
                }
            }

            // Video Form Handler
            const videoForm = document.getElementById('videoForm');
            if (videoForm) {
                videoForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
                    
                    fetch(`${drHopeBaseUrl}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.reset();
                            loadDrHopeItems('video');
                        } else {
                            alert(data.message || 'حدث خطأ');
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء إضافة الفيديو');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            // Image Form Handler
            const imageForm = document.getElementById('imageForm');
            if (imageForm) {
                imageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
                    
                    fetch(`${drHopeBaseUrl}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.reset();
                            document.getElementById('imageLabelText').textContent = 'اختر الصور';
                            loadDrHopeItems('image');
                        } else {
                            alert(data.message || 'حدث خطأ');
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء رفع الصورة');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            // Instagram Form Handler
            const instagramForm = document.getElementById('instagramForm');
            if (instagramForm) {
                instagramForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
                    
                    fetch(`${drHopeBaseUrl}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.reset();
                            loadDrHopeItems('instagram');
                        } else {
                            alert(data.message || 'حدث خطأ');
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء إضافة الرابط');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
            }

            // Make deleteDrHopeItem globally accessible
            window.deleteDrHopeItem = function(id, type) {
                if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                    fetch(`${drHopeBaseUrl}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadDrHopeItems(type);
                        } else {
                            alert(data.message || 'حدث خطأ');
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء حذف العنصر');
                    });
                }
            };

            // Partner Functions
            const partnerBaseUrl = '{{ url("partners") }}';

            function loadPartners() {
                fetch(`${partnerBaseUrl}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderPartners(data.partners);
                        }
                    });
            }

            function renderPartners(partners) {
                const container = document.getElementById('partners-list');
                if (partners.length === 0) {
                    container.innerHTML = `
                        <div class="partner-empty-state" style="grid-column: 1 / -1;">
                            <i class="fas fa-inbox"></i>
                            <p>لا يوجد شركاء</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = partners.map(partner => `
                    <div class="partner-card">
                        <img src="${partner.image}" alt="${partner.title}" class="partner-image">
                        <div class="partner-title">${partner.title}</div>
                        <div class="partner-actions">
                            <button class="btn-action-small btn-view-small" onclick="window.openPartnerShowModal(${partner.id})">
                                <i class="fas fa-eye"></i> عرض
                            </button>
                            <button class="btn-action-small btn-edit-small" onclick="window.openPartnerEditModal(${partner.id})">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button class="btn-action-small btn-delete-small" onclick="window.deletePartner(${partner.id})">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                `).join('');
            }

            // Make partner functions globally accessible
            window.openPartnerCreateModal = function() {
                const modal = new bootstrap.Modal(document.getElementById('partnerModal'));
                document.getElementById('partnerModalLabel').textContent = 'إضافة شريك جديد';
                
                const formHtml = `
                    <form id="partnerForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الرابط</label>
                            <input type="url" name="link" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">الصورة</label>
                            <div class="file-input-wrapper-partner">
                                <label for="partnerImageInput" class="file-input-label-partner">
                                    <i class="fas fa-upload"></i>
                                    <span id="partnerImageLabelText">اختر الصورة</span>
                                </label>
                                <input type="file" id="partnerImageInput" name="image" class="form-control partner-file-input" accept="image/*" required onchange="previewPartnerImage(this)">
                            </div>
                            <div class="image-preview-container">
                                <div class="image-preview-placeholder" id="partnerImagePreview">
                                    <i class="fas fa-image" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <span>معاينة الصورة</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn-save">حفظ</button>
                        </div>
                    </form>
                `;
                
                document.getElementById('partnerModalContent').innerHTML = formHtml;
                
                const form = document.getElementById('partnerForm');
                let formSubmitted = false;
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (formSubmitted) return;
                    
                    formSubmitted = true;
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
                    
                    fetch(`${partnerBaseUrl}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            loadPartners();
                        } else {
                            alert(data.message || 'حدث خطأ');
                            formSubmitted = false;
                        }
                    })
                    .catch(error => {
                        alert('حدث خطأ أثناء إضافة الشريك');
                        formSubmitted = false;
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
                });
                
                // Reset formSubmitted flag when modal is closed
                document.getElementById('partnerModal').addEventListener('hidden.bs.modal', function() {
                    formSubmitted = false;
                });
                
                modal.show();
            }

            window.previewPartnerImage = function(input) {
                const preview = document.getElementById('partnerImagePreview');
                const labelText = document.getElementById('partnerImageLabelText');
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    labelText.textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        preview.classList.add('has-image');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = `
                        <i class="fas fa-image" style="font-size: 3rem; opacity: 0.3;"></i>
                        <span>معاينة الصورة</span>
                    `;
                    preview.classList.remove('has-image');
                    labelText.textContent = 'اختر الصورة';
                }
            };

            window.openPartnerShowModal = function(id) {
                fetch(`${partnerBaseUrl}/${id}/show`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const partner = data.partner;
                            const modal = new bootstrap.Modal(document.getElementById('partnerModal'));
                            document.getElementById('partnerModalLabel').textContent = 'تفاصيل الشريك';
                            
                            document.getElementById('partnerModalContent').innerHTML = `
                                <div>
                                    <div class="form-group">
                                        <label class="form-label">الصورة</label>
                                        <img src="${partner.image}" style="max-width: 100%; border-radius: 8px;">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">الاسم</label>
                                        <div class="info-box">${partner.title}</div>
                                    </div>
                                    ${partner.description ? `
                                        <div class="form-group">
                                            <label class="form-label">الوصف</label>
                                            <div class="info-box">${partner.description}</div>
                                        </div>
                                    ` : ''}
                                    ${partner.link ? `
                                        <div class="form-group">
                                            <label class="form-label">الرابط</label>
                                            <div class="info-box">
                                                <a href="${partner.link}" target="_blank" class="item-link">${partner.link}</a>
                                            </div>
                                        </div>
                                    ` : ''}
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    </div>
                                </div>
                            `;
                            
                            modal.show();
                        }
                    });
            }

            window.openPartnerEditModal = function(id) {
                fetch(`${partnerBaseUrl}/${id}/show`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const partner = data.partner;
                            const modal = new bootstrap.Modal(document.getElementById('partnerModal'));
                            document.getElementById('partnerModalLabel').textContent = 'تعديل الشريك';
                            
                            const formHtml = `
                                <form id="partnerEditForm" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="form-label">الاسم</label>
                                        <input type="text" name="title" class="form-control" value="${partner.title}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">الوصف</label>
                                        <textarea name="description" class="form-control" rows="3">${partner.description || ''}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">الرابط</label>
                                        <input type="url" name="link" class="form-control" value="${partner.link || ''}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">الصورة</label>
                                        <div class="file-input-wrapper-partner">
                                            <label for="partnerEditImageInput" class="file-input-label-partner">
                                                <i class="fas fa-upload"></i>
                                                <span id="partnerEditImageLabelText">اختر صورة جديدة (اختياري)</span>
                                            </label>
                                            <input type="file" id="partnerEditImageInput" name="image" class="form-control partner-file-input" accept="image/*" onchange="window.previewPartnerEditImage(this)">
                                        </div>
                                        <div class="image-preview-container">
                                            <div class="image-preview-placeholder has-image" id="partnerEditImagePreview">
                                                <img src="${partner.image}" alt="Current Image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn-save">تحديث</button>
                                    </div>
                                </form>
                            `;
                            
                            document.getElementById('partnerModalContent').innerHTML = formHtml;
                            
                            const form = document.getElementById('partnerEditForm');
                            let formSubmitted = false;
                            
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                if (formSubmitted) return;
                                
                                formSubmitted = true;
                                const formData = new FormData(this);
                                const submitBtn = this.querySelector('button[type="submit"]');
                                const originalText = submitBtn.innerHTML;
                                
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
                                
                                fetch(`${partnerBaseUrl}/${id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'X-HTTP-Method-Override': 'PUT'
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        modal.hide();
                                        loadPartners();
                                    } else {
                                        alert(data.message || 'حدث خطأ');
                                        formSubmitted = false;
                                    }
                                })
                                .catch(error => {
                                    alert('حدث خطأ أثناء تحديث الشريك');
                                    formSubmitted = false;
                                })
                                .finally(() => {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                });
                            });
                            
                            // Reset formSubmitted flag when modal is closed
                            document.getElementById('partnerModal').addEventListener('hidden.bs.modal', function() {
                                formSubmitted = false;
                            }, { once: true });
                            
                            modal.show();
                        }
                    });
            }

            window.previewPartnerEditImage = function(input) {
                const preview = document.getElementById('partnerEditImagePreview');
                const labelText = document.getElementById('partnerEditImageLabelText');
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    labelText.textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        preview.classList.add('has-image');
                    };
                    reader.readAsDataURL(file);
                } else {
                    labelText.textContent = 'اختر صورة جديدة (اختياري)';
                }
            };

            window.deletePartner = function(id) {
                if (confirm('هل أنت متأكد من حذف هذا الشريك؟')) {
                    fetch(`${partnerBaseUrl}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPartners();
                        } else {
                            alert(data.message || 'حدث خطأ');
                        }
                    });
                }
            }

            // Load items when tabs are clicked
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    if (targetTab === 'dr-hope-settings') {
                        setTimeout(() => {
                            loadDrHopeItems('instagram');
                            loadDrHopeItems('video');
                            loadDrHopeItems('image');
                        }, 100);
                    } else if (targetTab === 'partners-settings') {
                        loadPartners();
                    }
                });
            });
        });
    </script>
@endsection
