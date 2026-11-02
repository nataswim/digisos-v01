<?php

namespace Tests\Feature;

use App\Models\EbookFile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Documents (downloadables) : la page « Nouveau document » et la gestion des fichiers eBook.
 * Lancer : php artisan test --filter=EbookFilesTest
 */
class EbookFilesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin', 'display_name' => 'admin', 'level' => 100]);
        $user = User::create(['name' => 'Test admin', 'email' => 'admin@example.test', 'password' => bcrypt('secret-password'), 'role_id' => $role->id, 'status' => 'active']);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    public function test_create_page_and_file_library(): void
    {
        Storage::fake('local');
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/downloadables/create')->assertOk()->assertSee('/admin/ebook-files', false);
        $this->actingAs($admin)->get('/admin/ebook-files')->assertOk();

        $this->actingAs($admin)->post('/admin/ebook-files', [
            'files' => [UploadedFile::fake()->create('guide-du-nageur.pdf', 300, 'application/pdf')],
            'names' => ['Guide du nageur'],
        ])->assertRedirect('/admin/ebook-files');

        $file = EbookFile::firstOrFail();
        Storage::disk('local')->assertExists($file->path);
        $this->actingAs($admin)->get("/admin/ebook-files/{$file->id}")->assertOk()->assertSee('Guide du nageur');
        $this->actingAs($admin)->getJson('/admin/ebook-files/api/files')->assertOk()->assertSee('Guide du nageur');
    }
}
