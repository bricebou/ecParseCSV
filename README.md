phileEcParseCSV
===============

A small plugin for [L'Effet Coléoptère website](http://leffetcoleoptere.bz/), using [Phile](CMS) for parsing a CSV file to generate a playlist table (using the [parseCSV PHP class](https://github.com/parsecsv/parsecsv-for-php)).

## Installation

### Using composer

From your PhileCMS root folder, just run:

```
composer require bricebou/ecparsecsv:dev-master
```

### Using Github

Clone this repository into `plugins/bricebou/ecParseCSV` :

```
$ mkdir -p plugins/bricebou/ecParseCSV
$ git clone https://github.com/bricebou/ecParseCSV.git plugins/bricebou/ecParseCSV
```

You'll have to install the [parseCSV PHP class](https://github.com/parsecsv/parsecsv-for-php).

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
$config['plugins']['bricebou\\ecparsecsv'] = array('active' => true);
```

### Usage

The CSV files are formatted like this:

```
Titre;Groupe;URL;Album;Année
Sexual Practice;Hot Flowers;http://hotflowers.bandcamp.com/;Camellia;2009
Chains;The Raveonettes;http://www.theraveonettes.com/;Whip It On;2002
```

In the meta of your blog post, just add:
```
CSV: folder/to/your/file.csv
```

In your theme, call the CSVtoTable function like this :

```
{{ CSVtoTable(meta.csv) }}
```


If the called page is a tag page, we display only the lines with the tag as band.