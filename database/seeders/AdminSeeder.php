<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin pertama saat deploy.
     *
     * Kredensial dibaca dari .env agar tidak hardcoded di kode:
     *   ADMIN_SEED_NAME="Nama Admin"
     *   ADMIN_SEED_EMAIL="admin@domain.com"
     *   ADMIN_SEED_PASSWORD="passwordKuat123!"
     *   ADMIN_SEED_NIP="123456789012345678"   (opsional)
     *
     * Jalankan: php artisan db:seed --class=AdminSeeder
     * Atau:     php artisan migrate --seed  (jika dipanggil dari DatabaseSeeder)
     */
    public function run(): void
    {
        $name     = env('ADMIN_SEED_NAME');
        $email    = env('ADMIN_SEED_EMAIL');
        $password = env('ADMIN_SEED_PASSWORD');
        $nip      = env('ADMIN_SEED_NIP');

        // Jika config sedang dicache (php artisan config:cache), env() di luar config/*.php mengembalikan null.
        // Muat ulang .env secara langsung agar seeder tetap dapat berjalan:
        if ((!$email || !$password) && file_exists(base_path('.env'))) {
            try {
                $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
                $dotenv->safeLoad();
                $name     = env('ADMIN_SEED_NAME');
                $email    = env('ADMIN_SEED_EMAIL');
                $password = env('ADMIN_SEED_PASSWORD');
                $nip      = env('ADMIN_SEED_NIP');
            } catch (\Throwable $e) {}
        }

        $name = $name ?: 'Super Admin';

        // Wajib ada email & password di .env
        if (!$email || !$password) {
            $this->command->error('ADMIN_SEED_EMAIL dan ADMIN_SEED_PASSWORD harus diisi di .env sebelum menjalankan AdminSeeder.');
            $this->command->warn('Tip: Jika sudah diisi di .env, jalankan `php artisan config:clear` terlebih dahulu.');
            return;
        }

        // Cek apakah admin sudah ada
        $exists = User::where('email', $email)->exists();
        if ($exists) {
            $this->command->info("Admin '{$email}' sudah ada, seeder dilewati.");
            return;
        }

        $user = User::create([
            'name'          => $name,
            'email'         => $email,
            'password'      => Hash::make($password),
            'role'          => 'admin_kepala_balai',
            'role_selected' => true,   // langsung masuk dashboard, tanpa Role-Selection
            'nip'           => $nip,
            'nip_hash'      => $nip ? User::hashNip($nip) : null,
        ]);

        $this->command->info("✅ Admin berhasil dibuat:");
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Nama',  $user->name],
                ['Email', $user->email],
                ['Role',  $user->role],
                ['NIP',   $nip ?? '(tidak diisi)'],
            ]
        );

        $this->command->warn('⚠  Hapus ADMIN_SEED_PASSWORD dari .env setelah deploy selesai!');
    }
}
