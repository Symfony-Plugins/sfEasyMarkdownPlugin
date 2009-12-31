<?php
/**
 * Vendor library: MarkdownExtra
 *
 * @see http://michelf.com/projects/php-markdown/extra/
 */
require_once dirname(__FILE__).'/../vendor/markdown-extra/markdown.php';

/**
 * sfEasyMarkdownHelper
 *
 * @package
 * @version SVN: $Id$
 * @author  Romain Dorgueil <romain.dorgueil@symfony-project.com>
 * @license MIT
 */
class sfEasyMarkdownHelper
{
  /**
   * render - render markdown and apply filters on it
   *
   * @param  string $content
   * @param  array $filters
   * @return void
   */
  static public function render($content, $filters=array())
  {
    $filters = self::addBuiltinFilters($filters);

    // xml special chars
    $content = str_replace(array('<', '>'), array('&lt;', '&gt;'), $content);

    $html = self::render_raw($content);

    foreach ($filters as $filterCallable)
    {
      if (is_callable($filterCallable))
      {
        $html = call_user_func($filterCallable, $html);
      }
      else
      {
        throw new InvalidArgumentException(__CLASS__.'\'s filters must be a callable array.');
      }
    }

    return $html;
  }

  /**
   * todo
   */
  static public function render_with_cache($cache_key, $content, $filters=array())
  {
  }

  /**
   * render_raw - raw convert of markdown to html by the MarkdownExtra library
   *
   * @param mixed $content
   * @return void
   */
  static public function render_raw($content)
  {
    return markdown($content);
  }

  static protected function addBuiltinFilters(array $filters)
  {
    $plugins = sfContext::getInstance()->getConfiguration()->getPlugins();

    if (in_array('sfEasySyntaxHighlighterPlugin', $plugins))
    {
      $filters[] = array(__CLASS__, 'applySyntaxHighlighterFilter');
    }

    return $filters;
  }

  static protected function applySyntaxHighlighterFilter($html)
  {
    return preg_replace_callback('#<pre><code>(.+?)</code></pre>#s', array(__CLASS__, 'applySyntaxHighlighterFilterCallback'), $html);
  }

  static protected function applySyntaxHighlighterFilterCallback($matches)
  {
    if (preg_match('/^\[(.+?)\]\s*(.+)$/s', $matches[1], $match))
    {
      $language = ($match[1] == 'xml') ? 'html4strict' : $match[1];
      return sfEasySyntaxHighlighterHelper::render(html_entity_decode(html_entity_decode($match[2])), $language);
    }
    else
    {
      return '<pre><code>'.$matches[1].'</code></pre>';
    }
  }
}
