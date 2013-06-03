# PastaDebug

A plugin for Mondrian

## What

It is a plugin for [Mondrian][1] . It helps to refine the type-hinting configuration
for graph with PhpUnit tests. Since this plugin is higly experimental, I've made
a plugin outside the Mondrian tool so you have to insert it manually (sorry)

## Install

You need 2 things :

 - a PhpUnit libray in the include_path of PHP (PHPUnit will be packaged later)
 - modifying the composer.json of Mondrian :

```
 $ composer.phar require trismegiste/pastadebug
```
Adding the class Trismegiste\\PastaDebug\\Command\\Refine in the extra->plugins array


## How

This tool add a new command typehint:refine. This command will refine a pre-existing .mondrian.yml
file at the root of the package (at the same level of phpunit.xml). It will catch the autoloading
and edit any scanned class to track a method invocation from another method.

With this information, the command will remove the ignored calls in the .mondrian.yml which were
not found by static analysis of the code.

[1]: https://github.com/Trismegiste/Mondrian
