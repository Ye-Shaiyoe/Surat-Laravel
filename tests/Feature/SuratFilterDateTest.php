<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Surat;

class SuratFilterDateTest extends TestCase
{
    public function test_user_can_access_table_with_date_range_filter(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.surat.table', [
            'tanggal_mulai' => '2026-08-02',
            'tanggal_selesai' => '2026-09-02',
        ]));

        $response->assertStatus(200);
        $response->assertSee('tanggal_mulai');
        $response->assertSee('tanggal_selesai');
    }

    public function test_user_can_export_excel_with_date_range(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.surat.exportExcel', [
            'tanggal_mulai' => '2026-08-02',
            'tanggal_selesai' => '2026-09-02',
        ]));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition') ?? '', '.xlsx')
        );
    }

    public function test_admin_can_access_semua_surat_with_date_filter(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if ($admin) {
            $admin->role = 'admin';
            $admin->role_selected = true;
            $admin->save();
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.surat.semua', [
                'tanggal_mulai' => '2026-08-02',
                'tanggal_selesai' => '2026-09-02',
            ]));

        $response->assertStatus(200);
        $response->assertSee('tanggal_mulai');
        $response->assertSee('tanggal_selesai');
        $response->assertSee('btnExportExcel');
    }

    public function test_admin_can_export_excel_with_date_range(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if ($admin) {
            $admin->role = 'admin';
            $admin->role_selected = true;
            $admin->save();
        }

        $response = $this->actingAs($admin)
            ->get(route('admin.surat.exportExcel', [
                'tanggal_mulai' => '2026-08-02',
                'tanggal_selesai' => '2026-09-02',
            ]));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition') ?? '', '.xlsx')
        );
    }
}
