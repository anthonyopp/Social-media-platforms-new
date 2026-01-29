<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index() {

    // 验证用户是否已登录
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', '请先登录');
    }

    $user = Auth::user();

    $events = Event::latest()->get();

    // 判断当前用户是否已报名
    foreach ($events as $event) {
        $event->joined_by_auth_user = $event->users()
            ->where('event_user.user_id', Auth::id())
            ->exists();
    }

    return view('events.index', compact('events'));
}


    public function create() {
        return view('events.create');
    }

    public function store(Request $request)
{
    // 处理文件上传
    $coverImagePath = null;
    $attachmentPath = null;

    if ($request->hasFile('cover_image')) {
        $coverImagePath = $request->file('cover_image')->store('event_covers', 'public');
    }

    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('event_attachments', 'public');
    }

    // 创建活动
    Event::create([
        'user_id' => auth()->id(),
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'start_time' => $request->input('start_time'),
        'end_time' => $request->input('end_time'),
        'location' => $request->input('location'),
        'phone' =>  $request->input('phone'),
        'type' => $request->input('type'),
        'capacity' => $request->input('capacity'),
        'registration_deadline' => $request->input('registration_deadline'),
        'requires_registration' => $request->input('requires_registration', 1),
        'cover_image' => $coverImagePath,
        'attachment' => $attachmentPath,
    ]);

    return redirect('/events')->with('success', '活动创建成功');
}



public function cancel($id)
{
    try {
        // 开启事务，保证数据一致性
        DB::beginTransaction();

        $event = Event::findOrFail($id);
        \Log::info("找到活动", ['event_id' => $event->id, 'status' => $event->status]);

        // 活动已结束或取消就不处理
        if (in_array($event->status, ['closed', 'canceled'])) {
            \Log::info("活动已结束或取消，无需处理", ['event_id' => $event->id]);
            return response()->json([
                'status' => 'failed',
                'badge'  => '<span id="status-badge-' . $event->id . '" class="badge bg-danger">已取消</span>',
                'button' => '<button id="join-btn-' . $event->id . '" class="btn btn-sm btn-secondary" disabled>活动已取消</button>'
            ]);
        }

        // 更新活动状态
        $event->status = 'canceled';
        $event->save();
        \Log::info("活动状态已更新为取消", ['event_id' => $event->id]);

        // 获取所有报名用户
        $registrations = $event->users()->get();
        \Log::info('Pivot 表数据', $registrations->toArray());

        if ($registrations->isEmpty()) {
            \Log::warning("未找到任何报名用户", ['event_id' => $event->id]);
        }

        // 遍历用户创建通知
        foreach ($registrations as $user) {
            \Log::info("准备发送通知给用户", ['user_id' => $user->user_id ?? $user->id]);

            \App\Models\Notification::create([
                'user_id'  => $user->user_id ?? $user->id, // 确保取到正确主键
                'event_id' => $event->id,
                'title'    => '活动取消通知',
                'type'     => 'event_cancel',
                'content'  => "❌ 你报名的活动《{$event->title}》已被取消。",
                'is_read'  => false,
            ]);

            \Log::info("通知已创建", ['user_id' => $user->user_id ?? $user->id]);
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'badge'  => '<span id="status-badge-' . $event->id . '" class="badge bg-danger">已取消</span>',
            'button' => '<button id="join-btn-' . $event->id . '" class="btn btn-sm btn-secondary" disabled>活动已取消</button>',
            'hideCancel' => true
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("取消活动失败", [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => '取消活动失败，请稍后重试'
        ], 500);
    }
}


public function join($id)
{
    $event = Event::findOrFail($id);
    $user = auth()->user();

    // 已报名 → 取消
    if ($event->users()->where('user.user_id', $user->user_id)->exists()) {
        $event->users()->detach($user->user_id);
        return response()->json([
            'status'  => 'canceled',
            'current' => $event->users()->count(),
            'countClass' => $this->getCountClass($event),
            'button'  => '<button id="join-btn-' . $event->id . '" class="btn btn-sm btn-success" onclick="toggleJoin(' . $event->id . ')">报名</button>'
        ]);
    }

    // 已满
    if ($event->capacity && $event->users()->count() >= $event->capacity) {
        return response()->json(['status' => 'full']);
    }

    // 报名
    $event->users()->attach($user->user_id);

    return response()->json([
        'status'  => 'joined',
        'current' => $event->users()->count(),
        'countClass' => $this->getCountClass($event),
        'button'  => '<button id="join-btn-' . $event->id . '" class="btn btn-sm btn-outline-danger" onclick="toggleJoin(' . $event->id . ')">取消报名</button>'
    ]);
}

private function getCountClass($event)
{
    $current = $event->users()->count();
    $capacity = $event->capacity;

    if ($capacity) {
        if ($current >= $capacity) {
            return 'text-danger'; // 满员
        } elseif ($current >= $capacity / 2) {
            return 'text-warning'; // 超过一半
        }
    }

    return 'text-success'; // 默认
}

public function remind(Event $event)
{
    $user = auth()->user();

    // 判断用户是否已设置提醒
    $existing = $event->reminders()->where('user_id', $user->user_id)->first();
    if ($existing) {
        // 取消提醒
        $existing->delete();
        return back()->with('info', '已取消提醒');
    }

    // 设置提醒：活动前一天
    $remindAt = $event->start_time->copy()->subDay();

    $event->reminders()->create([
        'user_id'   => $user->user_id,
        'remind_at' => $remindAt,
    ]);

    // === 派发延时队列任务 ===
    $delay = $remindAt->diffInSeconds(Carbon::now());

    Mail::to($user->email)
        ->later(now()->addSeconds($delay), new EventReminderMail($event));

    return back()->with('success', '提醒已设置，将在活动开始前一天发送邮件');
}

// public function remind(Event $event)
// {
//     $user = auth()->user();

//     // 判断用户是否已设置提醒
//     $existing = $event->reminders()->where('user_id', $user->user_id)->first();
//     if ($existing) {
//         // 取消提醒
//         $existing->delete();
//         return back()->with('info', '已取消提醒');
//     }

//     // 设置提醒，默认提前 1 小时
//     $remindAt = $event->start_time->subHour();

//     $event->reminders()->create([
//         'user_id' => $user->user_id,
//         'remind_at' => $remindAt,
//     ]);

//     return back()->with('success', '提醒已设置');
// }



    // public function join(Event $event) {
    //     $user = Auth::user();
    //     if (!$event->users()->where('user_id', $user->id)->exists()) {
    //         $event->users()->attach($user->id);
    //     } else {
    //         $event->users()->detach($user->id); // 取消报名
    //     }
    //     return back();
    // }

    public function show(Event $event) {
        return view('events.show', compact('event'));
    }
    // public function index()
    // {
    //     $events = Event::latest()->get();
    //     return view('events.index', compact('events'));
    // }

    // public function join($id)
    // {
    //     $event = Event::findOrFail($id);
    //     $event->attendees()->attach(Auth::id());
    //     return back()->with('success', '报名成功！');
    // }
}
