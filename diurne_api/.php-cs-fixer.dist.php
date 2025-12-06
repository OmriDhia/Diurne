<?php

// Ensure the correct namespace is used for Finder and Config
use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()  // Correct the method used to create a Finder instance
    ->in(__DIR__ . '/src')  // Only look in the src directory
    ->exclude('var');       // Exclude the var directory

return (new Config())
    ->setRules([
        '@Symfony' => true,   // Apply the Symfony coding standards
    ])
    ->setFinder($finder);  // Set the finder
