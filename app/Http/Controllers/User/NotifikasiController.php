<?php
// =============================================
// app/Http/Controllers/User/NotifikasiController.php
// =============================================

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Tandai satu notif dibaca + redirect ke surat terkait
    public function read(string $id)
    {
        $userId = Auth::id();
        if ($userId === null) {
            abort(403);
        }

        $user = User::query()->findOrFail($userId);
        $notif = $user->notifications()->findOrFail($id);
        $notif->markAsRead();

        $suratId = $notif->data['surat_id'] ?? null;
        if ($suratId) {
            return redirect()->route('user.surat.show', $suratId);
        }

        return redirect()->route('dashboard');
    }

    // Tandai semua dibaca
    public function readAll()
    {
        $userId = Auth::id();
        if ($userId === null) {
            abort(403);
        }

        $user = User::query()->findOrFail($userId);
        $user->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}