<?php

namespace FacturaScripts\Plugins\LoginScreen\Lib\Html;

use Twig\TwigFunction;

final class CssGradientTwig
{
    public static function getFunctions(): array
    {
        return [
            new TwigFunction('css_gradient', [self::class, 'gradient']),
            new TwigFunction('css_linear_gradient', [self::class, 'linearGradient']),
            new TwigFunction('css_rgba', [self::class, 'rgba']),
        ];
    }

    public static function linearGradient(
        string $from,
        string $to,
        $fromOpacity = 1,
        $toOpacity = null,
        string $direction = 'to bottom',
        int $fromStop = 0,
        int $toStop = 100
    ): string {
        $a1 = self::normalizeOpacity($fromOpacity);
        $a2 = ($toOpacity === null)
            ? $a1
            : self::normalizeOpacity($toOpacity);

        $fromRgb = self::hexToRgb($from);
        $toRgb = self::hexToRgb($to);

        if (!$fromRgb || !$toRgb) {
            return 'none';
        }

        $fromStop = max(0, min(100, $fromStop));
        $toStop = max(0, min(100, $toStop));

        $fromRgba = sprintf(
            'rgba(%d,%d,%d,%.3f)',
            $fromRgb[0], $fromRgb[1], $fromRgb[2], $a1
        );

        $toRgba = sprintf(
            'rgba(%d,%d,%d,%.3f)',
            $toRgb[0], $toRgb[1], $toRgb[2], $a2
        );

        return sprintf(
            'linear-gradient(%s, %s %d%%, %s %d%%)',
            $direction,
            $fromRgba,
            $fromStop,
            $toRgba,
            $toStop
        );
    }

    public static function gradient(
        string $from,
        string $to,
        $opacity = 1,
        string $direction = 'to right',
        int $fromStop = 0,
        int $toStop = 100
    ): string {
        $a = self::normalizeOpacity($opacity);

        $fromRgb = self::hexToRgb($from);
        $toRgb = self::hexToRgb($to);

        if (!$fromRgb || !$toRgb) {
            return '';
        }

        $fromStop = max(0, min(100, $fromStop));
        $toStop = max(0, min(100, $toStop));

        $fromRgba = sprintf('rgba(%d,%d,%d,%.3f)', ...array_merge($fromRgb, [$a]));
        $toRgba = sprintf('rgba(%d,%d,%d,%.3f)', ...array_merge($toRgb, [$a]));

        return sprintf(
            'background-color:%s;background-image:linear-gradient(%s,%s %d%%,%s %d%%);',
            $fromRgba,
            $direction,
            $fromRgba,
            $fromStop,
            $toRgba,
            $toStop
        );
    }

    public static function rgba(string $hex, $opacity = 1): string
    {
        $a = self::normalizeOpacity($opacity);
        $rgb = self::hexToRgb($hex);

        if (!$rgb) {
            return '';
        }

        return sprintf('rgba(%d,%d,%d,%.3f)', ...array_merge($rgb, [$a]));
    }

    private static function normalizeOpacity($opacity): float
    {
        $o = (float)$opacity;
        if ($o > 1) {
            $o /= 100;
        }
        return max(0, min(1, $o));
    }

    private static function hexToRgb(string $hex): ?array
    {
        $h = ltrim(trim($hex), '#');

        if (strlen($h) === 3) {
            $h = "$h[0]$h[0]$h[1]$h[1]$h[2]$h[2]";
        }

        if (!preg_match('/^[0-9a-fA-F]{6}$/', $h)) {
            return null;
        }

        return [
            hexdec(substr($h, 0, 2)),
            hexdec(substr($h, 2, 2)),
            hexdec(substr($h, 4, 2)),
        ];
    }
}
