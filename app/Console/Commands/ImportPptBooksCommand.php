<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\ContentTranslation;

class ImportPptBooksCommand extends Command
{
    protected $signature = 'ppt:import-books
        {--dry-run : Only show what would be imported, do not download or create}';

    protected $description = 'Import books from preciouspresenttruth.org Kinyarwanda book pages';

    private const PPT_BASE = 'https://preciouspresenttruth.org';

    private array $books = [
        [
            'url' => '/rw/kugana-yesu/',
            'en' => 'Steps to Christ',
            'fr' => 'Le Meilleur Chemin',
            'series' => null,
        ],
        [
            'url' => '/rw/imigani-ya-kristo-12/',
            'en' => 'Christ\'s Object Lessons',
            'fr' => 'Les Paraboles de Jésus',
            'series' => null,
        ],
        [
            'url' => '/rw/inama-zigirwa-itorero-2/',
            'en' => 'Testimonies for the Church – Volume 2',
            'fr' => 'Témoignages pour l\'Église – Volume 2',
            'series' => 'Testimonies for the Church',
        ],
        [
            'url' => '/rw/inyandiko-zibanze/',
            'en' => 'Early Writings',
            'fr' => 'Premiers Écrits',
            'series' => null,
        ],
        [
            'url' => '/rw/uburezi/',
            'en' => 'Education',
            'fr' => 'L\'Éducation',
            'series' => null,
        ],
        [
            'url' => '/rw/ubutumwa-bwatoranyijwe-igitabo-cya-mbere/',
            'en' => 'Selected Messages – Book 1',
            'fr' => 'Messages Choisis – Volume 1',
            'series' => 'Selected Messages',
        ],
        [
            'url' => '/rw/ubutumwa-bwatoranyijwe-igitabo-cya-ii/',
            'en' => 'Selected Messages – Book 2',
            'fr' => 'Messages Choisis – Volume 2',
            'series' => 'Selected Messages',
        ],
        [
            'url' => '/rw/ubutumwa-bwatoranyijwe-vol-3/',
            'en' => 'Selected Messages – Book 3',
            'fr' => 'Messages Choisis – Volume 3',
            'series' => 'Selected Messages',
        ],
        // No PDF available on the page for this book
        // [
        //     'url' => '/rw/inama-ku-busonga/',
        //     'en' => 'Counsels on Stewardship',
        //     'fr' => 'Conseils sur l\'Économie Chrétienne',
        //     'series' => null,
        // ],
        [
            'url' => '/rw/ivugabutumwa/',
            'en' => 'Evangelism',
            'fr' => 'Évangéliser',
            'series' => null,
        ],
        [
            'url' => '/rw/rengera-ubuzima-1/',
            'en' => 'The Ministry of Healing',
            'fr' => 'Le Ministère de la Guérison',
            'series' => null,
        ],
        [
            'url' => '/rw/rengera-ubuzima-2/',
            'en' => 'The Ministry of Healing – Volume 2',
            'fr' => 'Le Ministère de la Guérison – Volume 2',
            'series' => null,
        ],
        [
            'url' => '/rw/umurimo-wa-gikristo/',
            'en' => 'Christian Service',
            'fr' => 'Le Service Chrétien',
            'series' => null,
        ],
        [
            'url' => '/rw/itorero-ryimana-ryasigaye/',
            'en' => 'The Remnant Church',
            'fr' => 'L\'Église du Reste',
            'series' => null,
        ],
        [
            'url' => '/rw/imibereho-yejejwe/',
            'en' => 'The Sanctified Life',
            'fr' => 'La Vie Sanctifiée',
            'series' => null,
        ],
        [
            'url' => '/rw/kwezwa-no-kwirinda/',
            'en' => 'Sanctification and Abstinence',
            'fr' => 'Sanctification et Abstinence',
            'series' => null,
        ],
        [
            'url' => '/rw/urugo-rwa-gikristo/',
            'en' => 'The Christian Home',
            'fr' => 'Le Foyer Chrétien',
            'series' => null,
        ],
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $categoryId = $this->askForCategory();
        $imported = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($this->books as $bookDef) {
            $this->newLine();
            $this->line("Processing: <info>{$bookDef['url']}</info>");

            $rwTitle = $this->scrapeRwTitle($bookDef['url']);
            if (!$rwTitle) {
                $this->warn("  Could not extract title from page, skipping.");
                $failed++;
                continue;
            }

            $existing = Book::where('title', $rwTitle)->first();
            if ($existing) {
                $this->line("  Already exists as ID {$existing->id}: '{$rwTitle}' — skipping.");
                $skipped++;
                continue;
            }

            $this->line("  RW title: <comment>{$rwTitle}</comment>");
            $this->line("  EN title: <comment>{$bookDef['en']}</comment>");

            $pageData = $this->scrapePage($bookDef['url']);
            if (!$pageData) {
                $this->warn("  Failed to fetch page data, skipping.");
                $failed++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [DRY-RUN] Would import: {$rwTitle}");
                $this->line("           PDF: {$pageData['pdf_url']}");
                $this->line("           Cover: {$pageData['cover_url']}");
                $imported++;
                continue;
            }

            $result = $this->createBook($rwTitle, $bookDef, $pageData, $categoryId);
            if ($result) {
                $this->info("  Created book ID {$result->id}: {$rwTitle}");
                $imported++;
            } else {
                $this->warn("  Failed to create book: {$rwTitle}");
                $failed++;
            }
        }

        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                ['Imported', $imported],
                ['Skipped (exists)', $skipped],
                ['Failed', $failed],
            ]
        );

        return 0;
    }

    private function askForCategory(): int
    {
        $this->line("\nAvailable categories:");
        $cats = \App\Models\ContentCategory::whereIn('type', ['document', 'all'])->get();
        foreach ($cats as $c) {
            $this->line("  [{$c->id}] {$c->name}");
        }
        $choice = $this->ask('Which category ID for imported books?', '7');
        return (int) $choice;
    }

    private function scrapeRwTitle(string $path): ?string
    {
        $html = $this->fetchPage($path);
        if (!$html) return null;

        // Try <h1> first
        if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/i', $html, $m)) {
            $title = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($title && !str_contains($title, 'Ibitabo')) {
                return mb_strtoupper($title);
            }
        }

        // Try <title> tag
        if (preg_match('/<title>([^<]+)<\/title>/i', $html, $m)) {
            $title = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $title = str_replace(' - Precious', '', $title);
            if ($title) {
                return mb_strtoupper($title);
            }
        }

        return null;
    }

    private function scrapePage(string $path): ?array
    {
        $html = $this->fetchPage($path);
        if (!$html) return null;

        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

        $xpath = new \DOMXPath($dom);

        // Extract PDF URL
        $pdfUrl = null;
        $links = $xpath->query('//a[contains(@href, ".pdf")]');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (str_ends_with($href, '.pdf')) {
                $pdfUrl = $href;
                break;
            }
        }

        if (!$pdfUrl) {
            // fallback: regex search for PDF URLs
            if (preg_match('/https?:\/\/[^"\']+\.pdf/i', $html, $m)) {
                $pdfUrl = $m[0];
            }
        }

        if (!$pdfUrl) {
            $this->warn("  No PDF URL found on page");
            return null;
        }

        // Extract cover image (first big cover image)
        $coverUrl = null;
        $imgs = $xpath->query('//img[contains(@class, "attachment-medium") or contains(@class, "size-medium") or contains(@class, "wp-image")]');
        if ($imgs->length > 0) {
            $src = $imgs->item(0)->getAttribute('src');
            if ($src) $coverUrl = $src;
        }

        if (!$coverUrl) {
            // Fallback: find the first image with "uploads" in src that's not a logo
            $imgs = $xpath->query('//img[contains(@src, "uploads")]');
            foreach ($imgs as $img) {
                $src = $img->getAttribute('src');
                if ($src && !str_contains($src, 'logo') && !str_contains($src, 'removebg')) {
                    $coverUrl = $src;
                    break;
                }
            }
        }

        // Extract description from the first paragraph below h2
        $description = null;
        $nodes = $xpath->query('//h2/following-sibling::p[1]');
        if ($nodes->length > 0) {
            $description = trim($nodes->item(0)->textContent);
            $description = mb_substr($description, 0, 500);
        }

        return [
            'pdf_url' => $pdfUrl,
            'cover_url' => $coverUrl,
            'description' => $description,
        ];
    }

    private function fetchPage(string $path): ?string
    {
        $url = self::PPT_BASE . $path;
        try {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'follow_location' => true,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $html = file_get_contents($url, false, $ctx);
            if ($html === false) return null;
            return $html;
        } catch (\Exception $e) {
            $this->warn("  Error fetching {$url}: {$e->getMessage()}");
            return null;
        }
    }

    private function createBook(string $rwTitle, array $def, array $pageData, int $categoryId): ?Book
    {
        try {
            $pdfContent = $this->downloadFile($pageData['pdf_url']);
            if (!$pdfContent) {
                $this->warn("  Failed to download PDF from {$pageData['pdf_url']}");
                return null;
            }

            $pdfPath = 'content/documents/' . uniqid() . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdfContent);

            $coverPath = null;
            if ($pageData['cover_url']) {
                $coverContent = $this->downloadFile($pageData['cover_url']);
                if ($coverContent) {
                    $ext = pathinfo(parse_url($pageData['cover_url'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $coverPath = 'content/documents/covers/' . uniqid() . '.' . $ext;
                    Storage::disk('public')->put($coverPath, $coverContent);
                }
            }

            $book = Book::create([
                'title' => $rwTitle,
                'description' => $pageData['description'],
                'file_path' => $pdfPath,
                'cover_image' => $coverPath,
                'author' => 'Ellen G. White',
                'category_id' => $categoryId,
                'series' => $def['series'],
                'published_at' => now(),
                'featured' => false,
                'recommended' => false,
                'is_published' => true,
            ]);

            // Create translations
            $translations = [
                'rw' => $rwTitle,
                'en' => $def['en'],
                'fr' => $def['fr'],
            ];

            foreach ($translations as $locale => $title) {
                ContentTranslation::updateOrCreate(
                    [
                        'content_type' => $book->getMorphClass(),
                        'content_id' => $book->id,
                        'locale' => $locale,
                    ],
                    [
                        'title' => $title,
                        'description' => $locale === 'rw' ? $pageData['description'] : null,
                        'source_locale' => $locale === 'rw' ? 'rw' : 'rw',
                        'translation_status' => 'approved',
                        'translated_by' => 'manual',
                        'quality_score' => 100.0,
                        'is_bible_locked' => false,
                        'reviewed_by' => 1,
                        'reviewed_at' => now(),
                    ]
                );
            }

            return $book;
        } catch (\Exception $e) {
            $this->warn("  Error creating book: {$e->getMessage()}");
            return null;
        }
    }

    private function downloadFile(string $url): ?string
    {
        try {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 60,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'follow_location' => true,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $content = file_get_contents($url, false, $ctx);
            if ($content === false) return null;
            return $content;
        } catch (\Exception $e) {
            $this->warn("  Download error for {$url}: {$e->getMessage()}");
            return null;
        }
    }
}
