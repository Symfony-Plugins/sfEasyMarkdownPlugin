<?php

/**
 * sfEasyMarkdownPlugin configuration.
 *
 * @package     sfEasyMarkdownPlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id$
 */
class sfEasyMarkdownPluginConfiguration extends sfPluginConfiguration
{
  static protected $DEPENDENCIES = array(
  );

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $enabledPlugins = $this->configuration->getPlugins();

    foreach (self::$DEPENDENCIES as $pluginName => $whatFor)
    {
      if (!in_array($pluginName, $enabledPlugins))
      {
        throw new sfConfigurationException(sprintf('You must install and enable plugin "%s" which provides %s.', $pluginName, $whatFor));
      }
    }

    /* required for symfony 1.1 compatibility */
    require dirname(__FILE__).'/config.php';
  }
}
