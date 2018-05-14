<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 14.05.18
 * Time: 16:28
 */

require __DIR__ . '/../vendor/autoload.php';

$tplDir = dirname(__FILE__). '/../templates';
$tmpDir = dirname(__FILE__) . '/../tmp/cache/';
$loader = new Twig_Loader_Filesystem($tplDir);

// force auto-reload to always have the latest version of the template
$twig = new Twig_Environment($loader, array(
  'cache' => $tmpDir,
  'auto_reload' => true
));
$twig->addExtension(new Twig_Extensions_Extension_I18n());
// configure Twig the way you want

// iterate over all your templates
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tplDir), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
  // force compilation
  if ($file->isFile()) {
    $twig->loadTemplate(str_replace($tplDir.'/', '', $file));
  }
}