<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SupportMessage;
use App\Http\Controllers\Controller;
use App\Filters\SupportMessageFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SupportMessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportMessage::with('user');
        $filter = new SupportMessageFilter($request);
        $query = $filter->apply($query);
        $messages = $query->paginate(15);
        
        return view('Admin.support-messages.index', compact('messages'));
    }

    public function show($id): JsonResponse
    {
        try {
            $supportMessage = SupportMessage::with(['user.country'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'modalType' => 'show',
                'message' => [
                    'id' => $supportMessage->id,
                    'message' => $supportMessage->message,
                    'created_at' => $supportMessage->created_at->format('Y-m-d H:i:s'),
                    'user' => [
                        'id' => $supportMessage->user->id,
                        'full_name' => $supportMessage->user->full_name,
                        'email' => $supportMessage->user->email,
                        'phone' => $supportMessage->user->phone,
                        'country' => $supportMessage->user->country ? ['name' => $supportMessage->user->country->name] : null,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الرسالة'
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $supportMessage = SupportMessage::findOrFail($id);
            $supportMessage->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرسالة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الرسالة'
            ], 500);
        }
    }
}
