<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationController extends Controller
{
    public function index ()
    {
        $data = [];
        foreach (Notification::where('read', false)->get() as $notifications) {
            $data[] = $notifications;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => null,
        ]);
    }

    public function show($id) {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message'=> 'Notificação não encontrada',
            ], 404);
        }

        $notification->update([
            'read' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $notification,
            'message' => null,
        ]);
    }
}
