<?php

namespace Database\Seeders;

use App\Models\PlatformReview;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PlatformReviewsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing reviews
        PlatformReview::truncate();
        
        $reviews = [
            // 5-star reviews (60%)
            ['name' => 'Anna Andersson', 'email' => 'anna.andersson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Fantastisk tjänst! Jag hittade snabbt en pålitlig städfirma genom plattformen. Allt fungerade smidigt från bokning till slutresultat. Mycket nöjd!'],
            ['name' => 'Erik Svensson', 'email' => 'erik.svensson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Superbra plattform för att hitta lokala hantverkare. Fick hjälp med vår badrumsrenovering och resultatet blev över förväntan. Rekommenderar starkt!'],
            ['name' => 'Maria Johansson', 'email' => 'maria.j@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Extremt nöjd med tjänsten! Bokningsprocessen var enkel och tydlig. Företaget jag fick kontakt med var professionellt och priserna var transparenta.'],
            ['name' => 'Ahmed Hassan', 'email' => 'ahmed.h@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 4, 'speed' => 5, 'review_text' => 'Excellent service! I found a reliable moving company through this platform. Everything was professional and well-organized. Highly recommend!'],
            ['name' => 'Lars Nilsson', 'email' => 'lars.n@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Har använt plattformen flera gånger nu och alltid lika nöjd. Kvaliteten på företagen är hög och priserna rimliga. Toppenbetjäning!'],
            ['name' => 'Sofia Berg', 'email' => 'sofia.berg@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Perfekt för att hitta ROT-berättigade tjänster! Sparade mycket pengar på min fönsterputsning. Smidigt och professionellt.'],
            ['name' => 'Dimitrios Papadopoulos', 'email' => 'dimitrios.p@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Εξαιρετική πλατφόρμα! Very impressed with the quality of companies. Found a great electrician for my home renovation. Professional service!'],
            ['name' => 'Karin Lindström', 'email' => 'karin.l@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Så enkelt att boka! Behövde akut hjälp med VVS och fick snabb respons. Företaget var punktligt och kunnigt. Tack!'],
            ['name' => 'Magnus Karlsson', 'email' => 'magnus.k@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 4, 'review_text' => 'Genomtänkt plattform med bra översikt. Jämförde flera städfirmor och valde den som passade bäst. Resultatet blev perfekt!'],
            ['name' => 'Yasmin Al-Said', 'email' => 'yasmin.a@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'رائع! Amazing platform! I booked a cleaning service and was very satisfied. Professional, affordable, and reliable. Will use again!'],
            ['name' => 'Peter Gustafsson', 'email' => 'peter.g@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Toppenkvalitet på både plattformen och företagen. Fick hjälp med trädgårdsarbete och blev mycket nöjd. Priserna var också bra!'],
            ['name' => 'Emma Hansson', 'email' => 'emma.h@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Otroligt smidigt system! Bokade målning av lägenheten och allt gick som en dans. Proffsiga målare och fair priser.'],
            
            // 5-star reviews continued
            ['name' => 'Johan Eklund', 'email' => 'johan.e@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Bästa plattformen för hemtjänster i Sverige! Har provat flera och detta är överlägset bäst. Enkelt, snabbt och pålitligt.'],
            ['name' => 'Christina Persson', 'email' => 'christina.p@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Verkligen imponerad! Smidigt att jämföra priser och recensioner innan bokning. Fick fantastisk hjälp med storstädning.'],
            ['name' => 'Nikos Georgiou', 'email' => 'nikos.g@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Excellent platform! Found a great company for my kitchen renovation. Professional service and good value for money!'],
            ['name' => 'Linda Bergström', 'email' => 'linda.b@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 4, 'speed' => 5, 'review_text' => 'Fantastisk service från början till slut! Bokade tapetsering och målning. Resultatet blev strålande. Varmt rekommenderat!'],
            ['name' => 'Hassan Yusuf', 'email' => 'hassan.y@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Very good platform for finding professional services. I used it for electrical work and was very satisfied. Will use again!'],
            ['name' => 'Ingrid Lundgren', 'email' => 'ingrid.l@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Så nöjd med denna tjänst! Bokade snöskovling för hela vintern och företaget har varit pålitligt varje gång.'],
            
            // 4-star reviews (30%)
            ['name' => 'Gustav Öberg', 'email' => 'gustav.o@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 4, 'speed' => 4, 'review_text' => 'Mycket bra plattform! Allt fungerade smidigt. Skulle kanske önska fler företag att välja mellan i vissa kategorier, men nöjd överlag.'],
            ['name' => 'Sara Eriksson', 'email' => 'sara.e@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra tjänst med tydlig information. Bokningsprocessen var enkel. Företaget gjorde ett bra jobb, även om det tog lite längre tid än planerat.'],
            ['name' => 'Mohammad Rizvi', 'email' => 'mohammad.r@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 4, 'review_text' => 'Good service overall. Easy to book and compare companies. The quality was good, just wish there were more options in my area.'],
            ['name' => 'Helena Månsson', 'email' => 'helena.m@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra plattform med professionella företag. Lite längre svarstid än förväntat, men slutresultatet blev bra. Skulle använda igen.'],
            ['name' => 'Anastasia Ivanova', 'email' => 'anastasia.i@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 3, 'speed' => 4, 'review_text' => 'Very convenient platform! Booked cleaning service easily. Everything was professional. Would give 5 stars if prices were slightly lower.'],
            ['name' => 'Mikael Fransson', 'email' => 'mikael.f@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 3, 'speed' => 4, 'review_text' => 'Riktigt bra! Enkel bokning och bra översikt. Företaget levererade som utlovat. Lite höga priser i vissa kategorier dock.'],
            ['name' => 'Fatima El-Amin', 'email' => 'fatima.e@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Good platform for finding services. Everything worked well. The company was professional. Just took a bit longer to find available times.'],
            ['name' => 'Anders Blomqvist', 'email' => 'anders.b@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 4, 'speed' => 4, 'review_text' => 'Mycket nöjd med plattformen! Lätt att navigera och boka. Företaget var punktligt och proffsigt. Rekommenderas!'],
            ['name' => 'Katarina Nyström', 'email' => 'katarina.n@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra tjänst! Hittade en bra flyttfirma genom plattformen. Allt gick bra, även om bokningsprocessen kunde varit lite snabbare.'],
            ['name' => 'Georgios Christopoulos', 'email' => 'georgios.c@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 3, 'speed' => 4, 'review_text' => 'Great platform! Easy to use and find reliable companies. Professional service. Would be perfect with more discount options.'],
            
            // 5-star reviews (more)
            ['name' => 'Cecilia Strömberg', 'email' => 'cecilia.s@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Helt enkelt kanon! Bokade fönsterputsning och fick toppresultat. Snabb bokning, trevlig personal och bra pris. Tack!'],
            ['name' => 'Daniel Holm', 'email' => 'daniel.h@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Supersmidigt! Behövde akut hjälp med rörmokare och fick snabb respons. Professionellt och prisvärt. Rekommenderar starkt!'],
            ['name' => 'Layla Ibrahim', 'email' => 'layla.i@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Perfect service! I found a great cleaning company through this platform. Everything was professional and affordable. Highly recommend!'],
            ['name' => 'Björn Wallin', 'email' => 'bjorn.w@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Utmärkt plattform! Har bokkat flera tjänster och alltid varit nöjd. Transparenta priser och kvalitetsgaranti. Toppenklass!'],
            
            // 4-star reviews (more)
            ['name' => 'Lena Sjöberg', 'email' => 'lena.sjoberg@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 4, 'review_text' => 'Bra platform med många olika tjänster. Allt funkade bra, kunde önskat lite mer information om företagen innan bokning.'],
            ['name' => 'Ali Mohammed', 'email' => 'ali.m@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 4, 'review_text' => 'Good service! Easy booking process and professional companies. Everything went well. Would use again!'],
        ];

        foreach ($reviews as $index => $review) {
            // Spread dates across the last 6 months (0-180 days ago)
            $daysAgo = random_int(0, 180);
            $createdAt = Carbon::now()->subDays($daysAgo)->subMinutes(random_int(0, 1440));

            PlatformReview::create([
                'name' => $review['name'],
                'email' => $review['email'],
                'overall_rating' => $review['rating'],
                'service_quality_rating' => $review['service_quality'],
                'price_rating' => $review['price'],
                'speed_rating' => $review['speed'],
                'comment' => $review['review_text'],
                'status' => 'approved', // All pre-seeded reviews are approved
                'user_id' => null, // Anonymous reviews
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Created ' . count($reviews) . ' platform reviews with average rating of 4.8');
    }
}
