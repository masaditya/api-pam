<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\NoticeView;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function getUserNotices(Request $request)
    {
        // Validasi input user_id
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Ambil data dari tabel notice_view dengan join tabel notice
        $notices = NoticeView::with('notice')
            ->where('user_id', $request->user_id)
            ->get()
            ->map(function ($noticeView) {
                return [
                    'notice_id'  => $noticeView->notice_id,
                    'user_id'    => $noticeView->user_id,
                    'read'       => $noticeView->read,
                    'heading'    => $noticeView->notice->heading ?? null,
                    'date'      => optional($noticeView->notice->created_at)->format('d-m-Y'), // Format tanggal
                    'time'      => optional($noticeView->notice->created_at)->format('H:i:s'), // Format waktu
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $notices,
        ]);
    }

    public function getNoticeDetail(Request $request)
    {

        $request->validate([
            'notice_id' => 'required|exists:notices,id',
        ]);

        // Cari notice berdasarkan notice_id
        $notice = Notice::with(['department'])
            ->leftjoin('notice_files', 'notices.id', '=', 'notice_files.notice_id')
            ->leftjoin('notice_views', 'notices.id', '=', 'notice_views.notice_id')
            ->where('notices.id', $request->notice_id)
            ->select(
                'notices.*', // Semua kolom dari tabel notices
                'notice_files.filename as filename',
                'notice_files.hashname as hashname',
                'notice_files.google_url as google_url',
                'notice_files.dropbox_link as dropbox_link',
                'notice_files.external_link as external_link',
                'notice_files.external_link_name as external_link_name',
                'notice_views.user_id as user_id',
                'notice_views.read as read',
            )
            ->first(); 

        if (!$notice) {
            return response()->json([
                'success' => false,
                'message' => 'Notice not found',
            ], 404);
        }

        // Susun data untuk response
        $data = [
            'notice_id'       => $notice->id,
            'to'              => $notice->to,
            'heading'         => $notice->heading,
            'description'     => $notice->description,
            'added_by'        => $notice->user->name,
            'date'      => optional($notice->created_at)->format('d-m-Y'),
            'time'      => optional($notice->created_at)->format('H:i:s'),
            'department'   => $notice->department->team_name ?? null,
            'filename' => $notice->filename ?? null,
            'hashname' => 'https://app.mahawangsa.com/public/user-uploads/notice-files/'.$notice->id.'/'.$notice->hashname ?? null,
            'google_url' => $notice->google_url ?? null,
            'dropbox_link' => $notice->dropbox_link ?? null,
            'external_link' => $notice->external_link ?? null,
            'external_link_name' => $notice->external_link_name ?? null,
            'user_id' => $notice->user_id ?? null,
            'read' => $notice->read ?? null,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

     public function markAsRead(Request $request)
    {

        $request->validate([
            'notice_id' => 'required|exists:notices,id',
        ]);

        try {
            // Update the 'read' column to 1 for the given notice_id
            $updated = NoticeView::where('notice_id', $request->notice_id)
                ->where('read', 0) // Ensure only unread records are updated
                ->update(['read' => 1]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notice marked as read.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No unread notices found for the given ID.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the notice.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
