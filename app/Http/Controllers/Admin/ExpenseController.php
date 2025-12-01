<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Filters\ExpenseFilter;
use App\Exports\ExpensesExport;
use Illuminate\Http\JsonResponse;
use App\Traits\DashboardResponses;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ExpenseResource;
use App\Http\Resources\Admin\ExpenseCollection;
use App\Http\Requests\Admin\Expense\StoreExpenseRequest;
use App\Http\Requests\Admin\Expense\UpdateExpenseRequest;


class ExpenseController extends Controller
{
    use DashboardResponses;
    public function index(Request $request): JsonResponse
    {
        try {
            $tab      = $request->get('tab', 'active');
            $query    = $tab === 'trash' ? Expense::onlyTrashed() : Expense::query();
            $filter   = new ExpenseFilter($request);
            $query    = $filter->apply($query->with('workshop'));
            $expenses = $query->get();

            return $this->successWithDataResponse(new ExpenseCollection($expenses));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        try {

            $expense = Expense::create($request->validated());
            $expense->load('workshop');
            return $this->successWithMessageAndDataResponse(new ExpenseResource($expense), 'تم إضافة المصروف بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء إضافة المصروف');
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $expense = Expense::with('workshop')->findOrFail($id);

            return $this->successWithDataResponse(new ExpenseResource($expense));
        } catch (\Exception $e) {
            return $this->failureResponse('المصروف غير موجود');
        }
    }

    public function update(UpdateExpenseRequest $request, $id): JsonResponse
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->update($request->validated());
            $expense->load('workshop');

            return $this->successWithMessageAndDataResponse(new ExpenseResource($expense), 'تم تحديث المصروف بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء تحديث المصروف');
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();

            return $this->successResponse('تم حذف المصروف بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء حذف المصروف');
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $expense = Expense::onlyTrashed()->findOrFail($id);
            $expense->restore();

            return $this->successResponse('تم استعادة المصروف بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء استعادة المصروف');
        }
    }

    public function permanentlyDelete($id): JsonResponse
    {
        try {
            $expense = Expense::onlyTrashed()->findOrFail($id);

            if ($expense->image && file_exists(public_path($expense->image))) {
                unlink(public_path($expense->image));
            }

            $expense->forceDelete();

            return $this->successResponse('تم حذف المصروف نهائياً بنجاح');
        } catch (\Exception $e) {
            return $this->failureResponse('حدث خطأ أثناء حذف المصروف');
        }
    }

    public function exportExcel(Request $request)
    {
        $tab      = $request->get('tab', 'active');
        $category = $request->get('category', 'all');

        return Excel::download(new ExpensesExport($tab, $category), 'expenses.xlsx');
    }
}
