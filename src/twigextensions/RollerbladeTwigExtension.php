<?php
/**
 * Rollerblade plugin for Craft CMS 3.x
 *
 * A simple plugint to use a regular old Asset source for your SVG Icons, but still have flexibility in inlining the output.
 *
 * @link      https://digitalsurgeons.com
 * @copyright Copyright (c) 2019 Digital Surgeons
 */

namespace digitalsurgeons\rollerblade\twigextensions;

use digitalsurgeons\rollerblade\Rollerblade;

use Craft;
use craft\elements\Asset;
use craft\helpers\Template as TemplateHelper;
use craft\helpers\FileHelper;
use enshrined\svgSanitize\Sanitizer;

use Twig_Extension;
use Twig_Function;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Digital Surgeons
 * @package   Rollerblade
 * @since     0.1.0
 */
class RollerbladeTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Rollerblade';
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('rollerblade', [$this, 'rollerbladeFunction']),
        ];
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return string
     */
    public function rollerbladeFunction($svg, $class = null, $attr = [])
    {
        // Get SVG contents
        if ($svg instanceof Asset) {
            try {
                $svg = $svg->getContents();
            } catch (\Throwable $e) {
                Craft::error("Could not get the contents of {$svg->getPath()}: {$e->getMessage()}", __METHOD__);
                Craft::$app->getErrorHandler()->logException($e);
                return '';
            }
        } else if (stripos($svg, '<svg') === false) {
            $svg = Craft::getAlias($svg);
            if (!is_file($svg) || !FileHelper::isSvg($svg)) {
                Craft::warning("Could not get the contents of {$svg}: The file doesn't exist", __METHOD__);
                return '';
            }
            $svg = file_get_contents($svg);
        }

        // Sanitize SVG
        $sanitizer = new Sanitizer();
        $svg = $sanitizer->sanitize($svg);

        // Remove comments, title & desc
        $svg = preg_replace('/<!--.*?-->\s*/s', '', $svg);
        $svg = preg_replace('/<title>.*?<\/title>\s*/is', '', $svg);
        $svg = preg_replace('/<desc>.*?<\/desc>\s*/is', '', $svg);

        // Remove the XML declaration
        $svg = preg_replace('/<\?xml.*?\?>/', '', $svg);

        // Add custom attributes 
        $attrs = '';

        if ($class) {
            $attrs .= " class=\"$class\"";
        }

        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $attrs .= " $key=\"$value\"";
            }
        }

        if ($svgTag = preg_match('/(<svg[^>]+)(>)/is', $svg, $m)) {
            if ($class) {
                // Remove current class attribute if it exists
                $m[1] = preg_replace('/class="[^"]+"/is', '', $m[1]);
            }
            $svg = str_replace($m[0], $m[1].$attrs.$m[2], $svg);
        }

        // Render SVG
        return TemplateHelper::raw($svg);
    }
}
