<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EcotheologyContent;

class EcotheologyContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'title' => 'Hemat & Anti-Mubadzir',
                'theme_tag' => 'Hemat & Anti-Mubadzir • Al-Qur\'an',
                'waste_category' => 'all',
                'arabic_text' => 'وَكُلُوا وَاشْرَبُوا وَلَا تُسْرِفُوا ۚ إِنَّهُ لَا يُحِبُّ الْمُسْرِفِينَ',
                'translation' => 'Makan dan minumlah, tetapi jangan berlebih-lebihan. Sungguh, Allah tidak menyukai orang yang berlebih-lebihan.',
                'reference' => 'QS. Al-A\'raf: 31',
                'reflection' => 'Pola konsumsi berlebihan di era modern menghasilkan timbunan sampah sekali pakai yang membebani daya tampung bumi. Kesederhanaan dalam konsumsi adalah wujud syukur sejati atas nikmat rezeki yang Allah karuniakan.',
                'action' => 'Bawalah wadah makan dan botol minum tumbler sendiri saat beraktivitas di kampus, hindari kemasan sekali pakai yang langsung dibuang.',
                'is_active' => true,
            ],
            [
                'title' => 'Kebersihan Separuh dari Keimanan',
                'theme_tag' => 'Thaharah & Kesucian • Hadits',
                'waste_category' => 'organik',
                'arabic_text' => 'الطُّهُورُ شَطْرُ الْإِيمَانِ',
                'translation' => 'Kebersihan dan kesucian itu adalah separuh dari keimanan.',
                'reference' => 'HR. Muslim no. 223',
                'reflection' => 'Menjaga kesucian jasmani dan kebersihan lingkungan sekitar merupakan bukti nyata ketakwaan seorang mukmin. Memilah sampah pada wadah SmartBin ini adalah bentuk ibadah dan implementasi langsung dari keimanan kita kepada Allah SWT.',
                'action' => 'Segera pilah sisa makanan dan sampah organik ke wadah hijau agar dapat dijadikan kompos penyubur tanah dan tidak menimbulkan bau pembusukan di ruang publik.',
                'is_active' => true,
            ],
            [
                'title' => 'Larangan Merusak Tatanan Bumi',
                'theme_tag' => 'Kelestarian Alam • Al-Qur\'an',
                'waste_category' => 'plastik',
                'arabic_text' => 'وَلَا تُفْسِدُوا فِي الْأَرْضِ بَعْدَ إِصْلَاحِهَا وَادْعُوهُ خَوْفًا وَطَمَعًا ۚ إِنَّ رَحْمَتَ اللَّهِ قَرِيبٌ مِّنَ الْمُحْسِنِينَ',
                'translation' => 'Dan janganlah kamu berbuat kerusakan di muka bumi setelah (Allah) memperbaikinya, dan berdoalah kepada-Nya dengan rasa takut dan penuh harap. Sungguh, rahmat Allah sangat dekat kepada orang-orang yang berbuat baik.',
                'reference' => 'QS. Al-A\'raf: 56',
                'reflection' => 'Bumi dan ekosistemnya telah diciptakan Allah dalam keseimbangan yang sempurna. Membuang sampah sembarangan atau mencemari tanah dan air dengan plastik adalah bentuk kezaliman terhadap ciptaan-Nya. Menjaga kelestarian lingkungan adalah bagian dari Ihsan yang mendatangkan rahmat Allah.',
                'action' => 'Kumpulkan botol plastik, gelas kemasan, dan kantong kresek ke wadah Anorganik/Plastik agar dapat didaur ulang dan tidak mencemari lautan.',
                'is_active' => true,
            ],
            [
                'title' => 'Menyingkirkan Sampah dari Jalan Bernilai Sedekah',
                'theme_tag' => 'Sedekah Ekologis • Hadits',
                'waste_category' => 'logam',
                'arabic_text' => 'وَتُمِيطُ الْأَذَى عَنِ الطَّرِيقِ صَدَقَةٌ',
                'translation' => 'Dan engkau menyingkirkan gangguan (sampah, kotoran, duri, atau bahaya) dari jalan adalah sedekah.',
                'reference' => 'HR. Bukhari no. 2989 & Muslim no. 1009',
                'reflection' => 'Setiap tindakan memungut sampah, memilah kemasan daur ulang, atau memastikan area umum tetap higienis dicatat oleh Allah SWT sebagai amalan sedekah. Kebaikan ekologis bernilai pahala berlipat ganda bagi kemaslahatan bersama.',
                'action' => 'Pastikan kaleng bekas, tutup botol seng, dan pecahan beling dimasukkan secara aman ke wadah Logam & Kaca agar tidak mencelakai sesama.',
                'is_active' => true,
            ],
            [
                'title' => 'Pemanfaatan Berkah Kertas Tanpa Tabdzir',
                'theme_tag' => 'Hemat Sumber Daya • Al-Qur\'an',
                'waste_category' => 'kertas',
                'arabic_text' => 'وَآتِ ذَا الْقُرْبَىٰ حَقَّهُ وَالْمِسْكِينَ وَابْنَ السَّبِيلِ وَلَا تُبَذِّرْ تَبْذِيرًا',
                'translation' => 'Dan berikanlah haknya kepada kerabat dekat, juga kepada orang miskin dan orang yang dalam perjalanan; dan janganlah kamu menghambur-hamburkan (hartamu) secara boros.',
                'reference' => 'QS. Al-Isra: 26',
                'reflection' => 'Setiap lembar kertas berasal dari pohon yang tumbuh sebagai bagian dari tasbih alam kepada Allah. Pemborosan kertas tanpa didaur ulang memicu deforestasi hutan yang mempercepat pemanasan global.',
                'action' => 'Pilah kertas HVS, kardus, dan kemasan karton bersih ke wadah Kertas untuk proses daur ulang menjadi pulp ramah lingkungan.',
                'is_active' => true,
            ],
            [
                'title' => 'Kerusakan di Bumi Akibat Ulah Tangan Manusia',
                'theme_tag' => 'Peringatan Ekologis • Al-Qur\'an',
                'waste_category' => 'all',
                'arabic_text' => 'ظَهَرَ الْفَسَادُ فِي الْبَرِّ وَالْبَحْرِ بِمَا كَسَبَتْ أَيْدِي النَّاسِ لِيُذِيقَهُم بَعْضَ الَّذِي عَمِلُوا لَعَلَّهُمْ يَرْجِعُونَ',
                'translation' => 'Telah tampak kerusakan di darat dan di laut disebabkan karena perbuatan tangan manusia, supaya Allah merasakan kepada mereka sebahagian dari (akibat) perbuatan mereka, agar mereka kembali (ke jalan yang benar).',
                'reference' => 'QS. Ar-Rum: 41',
                'reflection' => 'Pencemaran mikroplastik dan timbulan sampah tak terkelola di perkotaan adalah cerminan dari kelalaian kolektif. Islam mengajarkan taubat ekologis: kembali memelihara bumi dengan tindakan nyata pemilahan dari sumbernya.',
                'action' => 'Gunakan teknologi Smart Waste Station ini sebagai langkah disiplin harian memilah sampah demi menjaga keberlangsungan generasi masa depan.',
                'is_active' => true,
            ],
        ];

        foreach ($contents as $data) {
            EcotheologyContent::updateOrCreate(
                ['title' => $data['title']],
                $data
            );
        }
    }
}
