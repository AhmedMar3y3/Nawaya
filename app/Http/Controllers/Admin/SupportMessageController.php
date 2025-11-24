<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SupprotMessage;
use App\Http\Controllers\Controller;
use App\Filters\SupportMessageFilter;

class SupportMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = SupprotMessage::with('user')->filter(new SupportMessageFilter($request))->paginate(15);
        return view('Admin.support-messages.index', compact('messages'));
    }

    public function show(SupprotMessage $supportMessage)
    {
        $supportMessage->load('user');
        return view('Admin.support-messages.show', compact('supportMessage'));
    }

    public function destroy(SupprotMessage $supportMessage)
    {
        $supportMessage->delete();
        return redirect()->route('admin.support-messages.index')->with('success', 'تم حذف الرسالة بنجاح.');
    }

}
