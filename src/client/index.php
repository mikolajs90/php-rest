<?php

require_once __DIR__ . '/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'debug' => true
));
$twig->addExtension(new Twig_Extension_Debug());

$config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents('config/resources.yml'));

$context = [];
$context['user'] = $config['user'];

$prefix = isset($config['user']['form_prefix']) ? $config['user']['form_prefix'] : '';
$formHelper = new \FormHelper\FormHelper($prefix);
if ($formHelper->isSubmitted()) {
    $apiClient = new \ApiClient\ApiClient();
    $apiClient->setMethod($formHelper->getMethod());
    $apiClient->setResourceId($formHelper->getResourceId());
    $apiClient->setQuery($formHelper->getQueryString());
    $apiClient->setData($formHelper->getFormData());
    $context['response'] = $apiClient->send();
}

$template = $twig->load('index.html.twig');
echo $template->render($context);