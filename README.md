phileEcParseCSV
===============

A small plugin for [L'Effet Coléoptère website](http://leffetcoleoptere.bz/), using [Phile](CMS) for parsing a CSV file to generate a playlist table (using the [parseCSV PHP class](https://github.com/parsecsv/parsecsv-for-php)).

## Installation

### This plugin

Clone this repository into `plugins/bricebou/ecParseCSV` :

```
$ mkdir -p plugins/bricebou/ecParseCSV
$ git clone https://github.com/bricebou/ecParseCSV.git plugins/bricebou/ecParseCSV
```

### Dependencies

You have to install the [parseCSV PHP class](https://github.com/parsecsv/parsecsv-for-php).

Edit the `composer.json` at the root of your PhileCMS installation and add in the `require` section this line :

```
"parsecsv/php-parsecsv": "0.4.5"
```

Then run

```
$ composer update
```

## Activation

After having installed this plugin and its depedencies, you have to edit your PhileCMS `config.php` and this line:

```
$config['plugins']['bricebou\\ecParseCSV'] = array('active' => true);
```
