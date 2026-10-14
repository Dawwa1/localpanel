<?php
namespace Localpanel;

class View
{
    private const VIEWS_DIR = __DIR__ . '/Views/';

    /**
     * Renders a single view ***template*** with the provided data.
     * @param string $template The name of the template to render (without .php extension).
     * @param array $data An associative array of data to be extracted into the template.
     * @return string The rendered HTML content of the template.
     */
    public static function render(string $template, array $data = []): string
    {
        $file = self::VIEWS_DIR . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View template not found: {$template}");
        }

        extract($data);
        ob_start();
        require $file;
        return ob_get_clean(); // Return the rendered content
    }

    /**
     * Renders a whole ***page*** with the provided data.
     * @param string $template The name of the template to render (without .php extension).
     * @param array $data An associative array of data to be extracted into the template.
     * @return string The rendered HTML content of the template, within the layout.
     */

    public static function page(string $template, array $data = []): string
    {
        $content = self::render($template, $data);
        return self::render('layout', array_merge($data, ['content' => $content]));
    }
}

?>