<?php

namespace CoolView;

final class CoolEngine {

    private string $viewsPath;

    private string $cachePath;

    public function __construct(string $viewsPath, string $cachePath){
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->cachePath = rtrim($cachePath, '/');
    }

    public function render(string $view, array $data = []): string {

        $templateFile = $this->viewsPath . '/' . $view . '.cool.php';

        if (!file_exists($templateFile)) {
            throw new \RuntimeException("View file '{$view}' not found.");
        }

        $compiledFile = $this->cachePath . '/' . md5($view) . '.php';

        if (!file_exists($compiledFile) || filemtime($templateFile) > filemtime($compiledFile)) {
            $this->compile($templateFile, $compiledFile);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $compiledFile;
        return ob_get_clean();
    }

    private function compile(string $templateFile, string $compiledFile): void {
        $template = file_get_contents($templateFile);

        $compiledTemplate = $this->parseDirectives($template);

        file_put_contents($compiledFile, $compiledTemplate);
    }

    private function parseDirectives(string $template): string {
        // Replace variables {{ $var }}
        $template = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\'); ?>', $template);

        // Replace @if, @elseif, @else, @endif
        $template = preg_replace('/@if\s*\((.+?)\)/', '<?php if ($1): ?>', $template);
        $template = preg_replace('/@elseif\s*\((.+?)\)/', '<?php elseif ($1): ?>', $template);
        $template = preg_replace('/@else/', '<?php else: ?>', $template);
        $template = preg_replace('/@endif/', '<?php endif; ?>', $template);

        // Replace @foreach, @endforeach
        $template = preg_replace('/@foreach\s*\((.+?)\)/', '<?php foreach ($1): ?>', $template);
        $template = preg_replace('/@endforeach/', '<?php endforeach; ?>', $template);

        // Replace @for, @endfor
        $template = preg_replace('/@for\s*\((.+?)\)/', '<?php for ($1): ?>', $template);
        $template = preg_replace('/@endfor/', '<?php endfor; ?>', $template);

        // Replace @while, @endwhile
        $template = preg_replace('/@while\s*\((.+?)\)/', '<?php while ($1): ?>', $template);
        $template = preg_replace('/@endwhile/', '<?php endwhile; ?>', $template);

        return $template;
    }

}