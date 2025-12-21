<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\Console\Commands;

use amcsi\LyceeOverture\Models\Article;
use Illuminate\Console\Command;

class PostArticleCommand extends Command
{
    protected $signature = 'lycee:post-article {--markdown= : Markdown source file}';
    protected $description = 'Posts an article';

    public function handle()
    {
        $markdownFilename = $this->option('markdown');
        if (!$markdownFilename || !is_readable($markdownFilename)) {
            $this->warn('Article markdown source must be provided with --markdown and be readable.');

            return 1;
        }

        $markdown = file_get_contents($markdownFilename);
        if (!$markdown) {
            $this->warn('Article markdown source must not be empty.');

            return 1;
        }

        $html = (new \Parsedown())->parse($markdown);

        $title = $this->ask('What should the title be?');

        echo "\n";
        printf("Here is the HTML: %s\n\nHere is the title: %s\n\n", $html, $title);

        if (!$this->confirm('Is this good?')) {
            return 1;
        }

        $article = new Article();
        $article->title = $title;
        $article->markdown = $markdown;
        $article->html = $html;
        $article->save();

        return null;
    }
}
