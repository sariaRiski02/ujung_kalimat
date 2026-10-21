<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\MembershipPlans;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

/**
 * Data contoh untuk mencoba fitur langganan dan paywall.
 * Aman dijalankan berulang kali: `php artisan db:seed --class=DummyDataSeeder`.
 * Semua akun demo memakai password "password".
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $plans = $this->seedPlans();

        $authors = [
            $this->user('Dewi Lestari', 'dewi.demo@example.test', 'dewilestari', 'Menulis tentang kota dan kenangan.'),
            $this->user('Bayu Pratama', 'bayu.demo@example.test', 'bayupratama', 'Catatan harian seorang pejalan kaki.'),
            $this->user('Sekar Ayu', 'sekar.demo@example.test', 'sekarayu', 'Puisi, esai pendek, dan renungan.'),
        ];

        $this->seedSubscribers($plans);

        foreach ($this->articles() as $index => $data) {
            $this->article($authors[$index % count($authors)], $data);
        }

        $this->seedWithdrawals($authors);
        $this->seedAdmin();
    }

    private function seedAdmin(): void
    {
        $admin = $this->user('Demo Admin', 'admin.demo@example.test', 'demoadmin', 'Akun demo admin untuk memproses penarikan.');

        // Role selalu di-reset ke "user" saat dibuat, jadi harus diset setelahnya.
        $admin->forceFill(['role' => 'admin'])->save();
    }

    /**
     * Penarikan contoh dengan berbagai status, jumlahnya dibatasi saldo penulis.
     *
     * @param  array<int, User>  $authors
     */
    private function seedWithdrawals(array $authors): void
    {
        $minimum = (int) config('monetization.min_withdrawal');
        $phones = ['081234567801', '081234567802', '081234567803'];

        foreach ($authors as $index => $author) {
            if ($author->withdrawals()->exists()) {
                continue;
            }

            $balance = $author->availableBalance();

            // Ditolak tidak mengurangi saldo, jadi selalu bisa dibuat.
            $this->withdrawal($author, $minimum, $phones[$index], Withdrawal::STATUS_REJECTED, now()->subDays(6));

            if ($balance >= $minimum) {
                $this->withdrawal($author, $minimum, $phones[$index], Withdrawal::STATUS_PAID, now()->subDays(3));
                $balance -= $minimum;
            }

            if ($balance >= $minimum) {
                $this->withdrawal($author, $minimum, $phones[$index], Withdrawal::STATUS_PENDING, now()->subHours(5));
            }
        }
    }

    private function withdrawal(User $user, int $amount, string $phone, string $status, $createdAt): void
    {
        $withdrawal = $user->withdrawals()->create(['amount' => $amount, 'phone_number' => $phone, 'status' => $status]);

        $withdrawal->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->saveQuietly();
    }

    /**
     * @return array<string, MembershipPlans>
     */
    private function seedPlans(): array
    {
        return [
            'monthly' => MembershipPlans::firstOrCreate(['name' => 'monthly'], ['price' => 5000, 'duration_months' => 1, 'is_active' => true]),
            'semiannual' => MembershipPlans::firstOrCreate(['name' => 'semiannual'], ['price' => 20000, 'duration_months' => 6, 'is_active' => true]),
        ];
    }

    private function user(string $name, string $email, string $username, string $bio): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'username' => $username,
                'bio' => $bio,
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }

    /**
     * @param  array<string, MembershipPlans>  $plans
     */
    private function seedSubscribers(array $plans): void
    {
        $active = $this->user('Demo Subscriber', 'subscriber.demo@example.test', 'demosubscriber', 'Akun demo dengan langganan aktif.');
        $expired = $this->user('Demo Kedaluwarsa', 'expired.demo@example.test', 'demoexpired', 'Akun demo dengan langganan yang sudah habis.');
        $this->user('Demo Gratis', 'free.demo@example.test', 'demofree', 'Akun demo tanpa langganan.');

        $this->subscription($active, $plans['semiannual'], now()->subDays(10), now()->subDays(10)->addMonths(6), 'DUMMY-ACTIVE');
        $this->subscription($expired, $plans['monthly'], now()->subMonths(2), now()->subMonth(), 'DUMMY-EXPIRED');
    }

    private function subscription(User $user, MembershipPlans $plan, $startedAt, $endedAt, string $orderId): void
    {
        if (Payments::where('order_id', $orderId)->exists()) {
            return;
        }

        $subscription = Subscriptions::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ]);

        Payments::create([
            'subscription_id' => $subscription->id,
            'order_id' => $orderId,
            'transaction_id' => 'dummy-'.strtolower($orderId),
            'amount' => $plan->price,
            'payment_method' => 'qris',
            'status' => Payments::STATUS_PAID,
            'paid_at' => $startedAt,
        ]);
    }

    /**
     * @param  array{title: string, premium: bool, status?: string, body: array<int, string>}  $data
     */
    private function article(User $author, array $data): void
    {
        if (Article::where('title', $data['title'])->exists()) {
            return;
        }

        // Model Article mengisi user_id dari user yang sedang login.
        Auth::login($author);

        $blocks = array_map(fn (string $text) => str_starts_with($text, '> ')
            ? ['type' => 'quote', 'text' => substr($text, 2)]
            : ['type' => 'paragraph', 'text' => $text], $data['body']);

        $article = Article::create([
            'title' => $data['title'],
            'content' => $blocks,
            'is_premium' => $data['premium'],
            'status' => $data['status'] ?? 'published',
        ]);

        // Artikel premium dibuat cukup ramai agar saldo penulis cukup untuk contoh penarikan.
        $views = $data['premium'] ? random_int(300, 900) : random_int(20, 900);

        $article->forceFill(['views' => $views, 'love' => random_int(0, 120)])->saveQuietly();

        Auth::logout();
    }

    /**
     * @return array<int, array{title: string, premium: bool, status?: string, body: array<int, string>}>
     */
    private function articles(): array
    {
        return [
            [
                'title' => 'Hujan Pertama di Bulan Oktober',
                'premium' => false,
                'body' => [
                    'Hujan pertama selalu datang tanpa permisi. Ia mengetuk atap seng rumah lama kami, membangunkan bau tanah yang sudah lama tertidur.',
                    'Ibu menutup jendela sambil menggerutu soal jemuran. Saya justru membuka pintu, membiarkan udara dingin masuk dan menyapa kaki saya.',
                    '> Ada kenangan yang hanya bisa dibuka oleh aroma tertentu.',
                    'Di teras, kami minum teh manis dan tidak berkata apa-apa selama hampir satu jam. Rasanya cukup.',
                ],
            ],
            [
                'title' => 'Mengapa Kita Takut pada Halaman Kosong',
                'premium' => true,
                'body' => [
                    'Setiap penulis pernah menatap halaman kosong dan merasa dihakimi. Kursor berkedip seperti jantung yang tidak sabar.',
                    'Saya pernah percaya bahwa tulisan pertama harus bagus. Keyakinan itu menghabiskan bertahun-tahun waktu saya tanpa menghasilkan satu pun cerita utuh.',
                    'Perubahan datang ketika seorang editor tua berkata, "Tulis yang jelek dulu, perbaiki kemudian." Kalimat sederhana yang membebaskan.',
                    '> Draf pertama hanyalah cara Anda memberi tahu diri sendiri apa yang sebenarnya ingin dikatakan.',
                    'Sejak itu saya punya aturan: lima ratus kata setiap pagi, tanpa menghapus apa pun. Tidak ada yang boleh dinilai sebelum selesai.',
                    'Hasilnya tidak selalu indah. Tetapi di antara tumpukan kalimat canggung, selalu ada satu-dua paragraf yang layak dipertahankan.',
                    'Dan kadang, itu sudah lebih dari cukup untuk memulai hari dengan rasa bangga.',
                ],
            ],
            [
                'title' => 'Surat untuk Diri Saya Sepuluh Tahun Lagi',
                'premium' => true,
                'body' => [
                    'Kepada saya yang membaca ini di masa depan: semoga kamu masih suka kopi hitam dan masih sering lupa membawa payung.',
                    'Hari ini saya sedang belajar menerima bahwa tidak semua rencana harus berjalan lurus. Beberapa jalan memang berliku agar kita sempat melihat pemandangan.',
                    'Saya menulis ini di bangku stasiun, ditemani pengumuman keberangkatan yang berulang-ulang dan seorang anak yang tertidur di bahu ayahnya.',
                    '> Waktu tidak menyembuhkan segalanya, tetapi ia memberi kita cukup jarak untuk melihat lukanya dengan tenang.',
                    'Kalau kamu sudah berhasil melakukan hal-hal yang hari ini terasa mustahil, tolong ingat betapa takutnya saya waktu itu.',
                    'Dan kalau belum, tidak apa-apa. Kamu tetap seseorang yang saya banggakan karena terus berjalan.',
                ],
            ],
            [
                'title' => 'Warung Kopi di Ujung Gang',
                'premium' => false,
                'body' => [
                    'Warung itu hanya punya empat bangku dan satu radio yang selalu menyiarkan lagu lama. Pemiliknya, Pak Hasan, hafal pesanan semua orang.',
                    'Tidak ada wifi, tidak ada colokan. Justru karena itu orang-orang saling bicara.',
                    '> Kota paling hangat sering bersembunyi di tempat paling sederhana.',
                    'Suatu sore saya menyadari bahwa saya lebih banyak belajar tentang hidup dari obrolan di sana daripada dari buku apa pun.',
                ],
            ],
            [
                'title' => 'Seni Menunda yang Tidak Pernah Diajarkan Sekolah',
                'premium' => true,
                'body' => [
                    'Menunda sering dianggap dosa produktivitas. Padahal ada jenis penundaan yang justru membuat keputusan kita lebih matang.',
                    'Bedakan antara menghindar dan mengendapkan. Menghindar lahir dari takut, mengendapkan lahir dari keyakinan bahwa gagasan butuh waktu untuk bertumbuh.',
                    'Saya pernah menunda menjawab tawaran pekerjaan selama tiga hari. Selama itu saya berjalan kaki, tidur cukup, dan menulis daftar yang tidak pernah saya tunjukkan pada siapa pun.',
                    '> Jawaban terbaik sering datang saat kita berhenti mengejarnya.',
                    'Pada hari keempat saya tahu apa yang harus dikatakan, dan saya tidak pernah menyesalinya.',
                    'Sekolah mengajarkan kita mengumpulkan tugas tepat waktu. Hidup mengajarkan kapan sebaiknya tidak buru-buru.',
                ],
            ],
            [
                'title' => 'Peta yang Tidak Pernah Selesai',
                'premium' => true,
                'body' => [
                    'Nenek saya menggambar peta kampungnya dari ingatan. Sungai di sebelah timur, pohon beringin di tengah, dan rumah-rumah yang sebagian sudah tidak ada.',
                    'Setiap kali saya datang, ia menambahkan satu detail baru: nama seorang tetangga, letak sumur tua, jalan pintas menuju sekolah.',
                    'Saya baru paham bahwa peta itu bukan untuk menunjukkan jalan, melainkan untuk menjaga orang-orang agar tidak hilang dari ingatan.',
                    '> Kita semua membawa peta yang tidak pernah selesai tentang tempat yang kita cintai.',
                    'Setelah nenek tiada, kertas itu saya bingkai. Tintanya sudah pudar, tetapi garis-garisnya masih menuntun saya pulang.',
                ],
            ],
            [
                'title' => 'Lima Buku Tipis yang Mengubah Cara Saya Membaca',
                'premium' => false,
                'body' => [
                    'Buku tebal sering membuat kita merasa gagal sebelum mulai. Karena itu saya merekomendasikan lima buku tipis yang bisa selesai dalam satu sore.',
                    'Yang pertama adalah kumpulan esai tentang berjalan kaki. Yang kedua, novel pendek tentang seorang penjaga mercusuar yang mulai lupa wajah anaknya.',
                    'Sisanya saya biarkan menjadi kejutan, karena buku terbaik adalah yang kita temukan pada waktu yang tepat.',
                ],
            ],
            [
                'title' => 'Tentang Rumah yang Tidak Lagi Sama',
                'premium' => true,
                'body' => [
                    'Saya pulang setelah delapan tahun merantau dan menemukan rumah yang terasa lebih kecil. Atau mungkin saya yang sudah terlalu besar untuknya.',
                    'Kamar saya kini menjadi gudang. Poster di dinding diganti kalender, dan meja belajar dipakai Ibu menjahit.',
                    'Awalnya saya sedih, sampai Ibu berkata bahwa rumah memang dirancang untuk berubah mengikuti penghuninya.',
                    '> Pulang bukan berarti kembali ke tempat yang sama, melainkan kembali kepada orang yang sama.',
                    'Malam itu saya tidur di sofa, mendengar suara mesin jahit dari kamar sebelah, dan untuk pertama kalinya setelah lama, saya tidur nyenyak.',
                ],
            ],
            [
                'title' => 'Catatan Kecil dari Kereta Pagi',
                'premium' => false,
                'body' => [
                    'Kereta pagi punya wajahnya sendiri: pekerja yang tertidur berdiri, mahasiswa dengan mata sembab, dan pedagang yang menata dagangan di antara gerbong.',
                    'Saya suka memperhatikan tangan orang-orang. Ada yang menggenggam ponsel erat, ada yang melipat koran dengan rapi.',
                    '> Setiap penumpang membawa cerita yang tidak akan pernah selesai saya baca.',
                ],
            ],
            [
                'title' => 'Draf yang Belum Berani Saya Terbitkan',
                'premium' => true,
                'status' => 'draft',
                'body' => [
                    'Tulisan ini masih berupa draf dan belum siap dibaca siapa pun.',
                    'Ia menunggu keberanian yang belum juga datang.',
                ],
            ],
        ];
    }
}
