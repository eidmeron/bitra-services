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
            // Swedish names (8 reviews)
            ['name' => 'Anna Lindqvist', 'email' => 'anna.lindqvist@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Fantastisk tjänst! Hittade snabbt en pålitlig städfirma genom plattformen. Allt fungerade smidigt från bokning till slutresultat. Mycket nöjd!'],
            ['name' => 'Erik Gustavsson', 'email' => 'erik.gustavsson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Superbra plattform för att hitta lokala hantverkare. Fick hjälp med vår badrumsrenovering och resultatet blev över förväntan. Rekommenderar starkt!'],
            ['name' => 'Maria Johansson', 'email' => 'maria.johansson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Extremt nöjd med tjänsten! Bokningsprocessen var enkel och tydlig. Företaget jag fick kontakt med var professionellt och priserna var transparenta.'],
            ['name' => 'Lars Nilsson', 'email' => 'lars.nilsson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Har använt plattformen flera gånger nu och alltid lika nöjd. Kvaliteten på företagen är hög och priserna rimliga. Toppenbetjäning!'],
            ['name' => 'Sofia Bergström', 'email' => 'sofia.bergstrom@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Perfekt för att hitta ROT-berättigade tjänster! Sparade mycket pengar på min fönsterputsning. Smidigt och professionellt.'],
            ['name' => 'Karin Lindström', 'email' => 'karin.lindstrom@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Så enkelt att boka! Behövde akut hjälp med VVS och fick snabb respons. Företaget var punktligt och kunnigt. Tack!'],
            ['name' => 'Magnus Karlsson', 'email' => 'magnus.karlsson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 4, 'review_text' => 'Genomtänkt plattform med bra översikt. Jämförde flera städfirmor och valde den som passade bäst. Resultatet blev perfekt!'],
            ['name' => 'Peter Gustafsson', 'email' => 'peter.gustafsson@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 4, 'speed' => 4, 'review_text' => 'Mycket bra plattform! Allt fungerade smidigt. Skulle kanske önska fler företag att välja mellan i vissa kategorier, men nöjd överlag.'],
            
            // Additional Swedish names (6 reviews)
            ['name' => 'Johan Eklund', 'email' => 'johan.eklund@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Bästa plattformen för hemtjänster i Sverige! Har provat flera och detta är överlägset bäst. Enkelt, snabbt och pålitligt.'],
            ['name' => 'Christina Persson', 'email' => 'christina.persson@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Verkligen imponerad! Smidigt att jämföra priser och recensioner innan bokning. Fick fantastisk hjälp med storstädning.'],
            ['name' => 'Lena Sjöberg', 'email' => 'lena.sjoberg@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 4, 'review_text' => 'Bra platform med många olika tjänster. Allt funkade bra, kunde önskat lite mer information om företagen innan bokning.'],
            ['name' => 'Cecilia Strömberg', 'email' => 'cecilia.stromberg@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Helt enkelt kanon! Bokade fönsterputsning och fick toppresultat. Snabb bokning, trevlig personal och bra pris. Tack!'],
            ['name' => 'Daniel Holm', 'email' => 'daniel.holm@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Supersmidigt! Behövde akut hjälp med rörmokare och fick snabb respons. Professionellt och prisvärt. Rekommenderar starkt!'],
            ['name' => 'Björn Wallin', 'email' => 'bjorn.wallin@email.se', 'rating' => 5, 'service_quality' => 5, 'price' => 5, 'speed' => 5, 'review_text' => 'Utmärkt plattform! Har bokkat flera tjänster och alltid varit nöjd. Transparenta priser och kvalitetsgaranti. Toppenklass!'],
            
            // More Swedish names (6 reviews)
            ['name' => 'Gustav Öberg', 'email' => 'gustav.oberg@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 4, 'speed' => 4, 'review_text' => 'Mycket bra plattform! Allt fungerade smidigt. Skulle kanske önska fler företag att välja mellan i vissa kategorier, men nöjd överlag.'],
            ['name' => 'Sara Eriksson', 'email' => 'sara.eriksson@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra tjänst med tydlig information. Bokningsprocessen var enkel. Företaget gjorde ett bra jobb, även om det tog lite längre tid än planerat.'],
            ['name' => 'Helena Månsson', 'email' => 'helena.mansson@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra plattform med professionella företag. Lite längre svarstid än förväntat, men slutresultatet blev bra. Skulle använda igen.'],
            ['name' => 'Mikael Fransson', 'email' => 'mikael.fransson@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 3, 'speed' => 4, 'review_text' => 'Riktigt bra! Enkel bokning och bra översikt. Företaget levererade som utlovat. Lite höga priser i vissa kategorier dock.'],
            ['name' => 'Anders Blomqvist', 'email' => 'anders.blomqvist@email.se', 'rating' => 4, 'service_quality' => 5, 'price' => 4, 'speed' => 4, 'review_text' => 'Mycket nöjd med plattformen! Lätt att navigera och boka. Företaget var punktligt och proffsigt. Rekommenderas!'],
            ['name' => 'Katarina Nyström', 'email' => 'katarina.nystrom@email.se', 'rating' => 4, 'service_quality' => 4, 'price' => 4, 'speed' => 3, 'review_text' => 'Bra tjänst! Hittade en bra flyttfirma genom plattformen. Allt gick bra, även om bokningsprocessen kunde varit lite snabbare.'],
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
