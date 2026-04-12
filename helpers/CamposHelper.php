<?php
/**
 * Helper para renderizar campos dinámicos por tipo/subtipo.
 */

class CamposHelper
{
    public static function getCamposParaSubtipo(?string $tipo, ?string $subtipo): array
    {
        if (!$tipo) return [];

        $config = require ROOT_PATH . '/config/campos_subtipos.php';
        $campos = [];

        if (isset($config[$tipo]['_comunes'])) {
            $campos = array_merge($campos, $config[$tipo]['_comunes']);
        }
        if ($subtipo && isset($config[$tipo][$subtipo])) {
            $campos = array_merge($campos, $config[$tipo][$subtipo]);
        }

        return $campos;
    }

    public static function renderCampo(string $nombre, array $config, $valor = null): string
    {
        $required = !empty($config['required']) ? 'required' : '';
        $placeholder = htmlspecialchars($config['placeholder'] ?? '');
        $label = htmlspecialchars($config['label'] ?? $nombre);
        $reqMark = $required ? ' <span style="color:#DC2626">*</span>' : '';

        $html = '<div class="form-group">';

        switch ($config['type']) {
            case 'text':
            case 'number':
            case 'time':
                $html .= "<label>{$label}{$reqMark}</label>";
                $min = isset($config['min']) ? ' min="' . $config['min'] . '"' : '';
                $max = isset($config['max']) ? ' max="' . $config['max'] . '"' : '';
                $step = isset($config['step']) ? ' step="' . $config['step'] . '"' : '';
                $val = htmlspecialchars($valor ?? '');
                $html .= "<input type=\"{$config['type']}\" name=\"campos[{$nombre}]\" value=\"{$val}\" placeholder=\"{$placeholder}\"{$min}{$max}{$step} {$required}>";
                break;

            case 'textarea':
                $html .= "<label>{$label}{$reqMark}</label>";
                $val = htmlspecialchars($valor ?? '');
                $html .= "<textarea name=\"campos[{$nombre}]\" rows=\"3\" placeholder=\"{$placeholder}\" {$required}>{$val}</textarea>";
                break;

            case 'select':
                $html .= "<label>{$label}{$reqMark}</label>";
                $html .= "<select name=\"campos[{$nombre}]\" {$required}>";
                $html .= '<option value="">Selecciona...</option>';
                foreach ($config['options'] as $key => $optLabel) {
                    $sel = ($valor === $key) ? ' selected' : '';
                    $html .= '<option value="' . htmlspecialchars($key) . '"' . $sel . '>' . htmlspecialchars($optLabel) . '</option>';
                }
                $html .= '</select>';
                break;

            case 'boolean':
                $checked = $valor ? ' checked' : '';
                $html .= '<label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">';
                $html .= "<input type=\"checkbox\" name=\"campos[{$nombre}]\" value=\"1\"{$checked} style=\"width:auto;\">";
                $html .= "<span>{$label}</span></label>";
                break;

            case 'checkbox_group':
                $html .= "<label>{$label}</label>";
                $valores = is_array($valor) ? $valor : [];
                $html .= '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
                foreach ($config['options'] as $key => $optLabel) {
                    $checked = in_array($key, $valores) ? ' checked' : '';
                    $html .= '<label style="display:flex;align-items:center;gap:0.35rem;padding:0.4rem 0.75rem;background:#F7FAFC;border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:0.85rem;">';
                    $html .= "<input type=\"checkbox\" name=\"campos[{$nombre}][]\" value=\"" . htmlspecialchars($key) . "\"{$checked} style=\"width:auto;\">";
                    $html .= '<span>' . htmlspecialchars($optLabel) . '</span></label>';
                }
                $html .= '</div>';
                break;
        }

        $html .= '</div>';
        return $html;
    }

    public static function renderTodos(array $campos, array $valores = []): string
    {
        $html = '<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem;">';
        foreach ($campos as $nombre => $config) {
            $html .= self::renderCampo($nombre, $config, $valores[$nombre] ?? null);
        }
        $html .= '</div>';
        return $html;
    }
}
