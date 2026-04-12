<?php
/**
 * Sanitizacion de HTML para campos WYSIWYG (TinyMCE).
 * Permite solo tags y atributos seguros. Elimina scripts y event handlers.
 */

class HtmlSanitizer
{
    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // Paso 1: Eliminar tags peligrosos y su contenido
        $html = preg_replace(
            '#<(script|style|object|embed|applet|form|input|button|select|textarea|meta|link|base)\b[^>]*>.*?</\1>#is',
            '', $html
        );
        $html = preg_replace(
            '#<(script|style|object|embed|applet|form|input|button|select|textarea|meta|link|base)\b[^>]*/?\s*>#is',
            '', $html
        );

        // Paso 2: Eliminar event handlers (onclick, onerror, etc.)
        $html = preg_replace('#\s+on[a-z]+\s*=\s*["\'][^"\']*["\']#is', '', $html);
        $html = preg_replace('#\s+on[a-z]+\s*=\s*\S+#is', '', $html);

        // Paso 3: Eliminar javascript: y vbscript: en href/src
        $html = preg_replace('#(href|src)\s*=\s*["\']?\s*javascript\s*:#is', '$1="', $html);
        $html = preg_replace('#(href|src)\s*=\s*["\']?\s*vbscript\s*:#is', '$1="', $html);
        $html = preg_replace('#(href)\s*=\s*["\']?\s*data\s*:#is', '$1="', $html);

        // Paso 4: Eliminar expression() en atributos style
        $html = preg_replace('#style\s*=\s*["\'][^"\']*expression\s*\([^"\']*["\']#is', '', $html);
        $html = preg_replace('#style\s*=\s*["\'][^"\']*javascript\s*:[^"\']*["\']#is', '', $html);

        return $html;
    }
}
