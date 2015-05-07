<?php

namespace Phile\Plugin\bricebou\ecParseCSV;

/**
 *
 * @author Brice Boucard
 * @link https://github.com/bricebou/ecParseCSV
 * @license http://bricebou.mit-license.org/
 */
class Plugin extends \Phile\Plugin\AbstractPlugin {

	protected $events = ['template_engine_registered' => 'output', 'request_uri' => 'request_uri'];

	protected function request_uri($data = null) {
		$uri = $data['uri'];
		/********************************************************
		* from the phileTags plugin by Philipp Schmitt
		* https://github.com/pschmitt/phileTags
		********************************************************/
		$dname = dirname($uri);
    if (substr($dname, 0, 1) == '/') {
        # Remove the leading '/'
        $dname = substr($dname, 1);
    }
    $this->is_tag = ($dname == "tag");

    // If the URL does start with 'tag/', grab the rest of the URL
    $current_tag_raw = basename($uri);

    if ($this->is_tag)
        $this->current_tag = htmlentities(urldecode($current_tag_raw), 0, "UTF-8");
  }

	protected function parse_file($file) {

		$csv = new \parseCSV();
		$csv->delimiter = ";";
		$csv->parse($file);

		if ($this->is_tag) {
			$tableHead = "<table><thead><tr><th>Titre</th><th>Album</th></tr></thead><tbody>";
		}
		else {
			$tableHead = "<table><thead><tr><th>Titre</th><th>Artiste</th><th>Album</th></tr></thead><tbody>";
		}
		$tableEnd = "</tbody></table>";
		$tableBody = "";

		foreach ($csv->data as $key => $row) {
			$annee = "";
			$groupe = "";

			if ($this->is_tag) {

				if (isset($row['Groupe']) && strpos(html_entity_decode($row['Groupe']), html_entity_decode($this->current_tag)) !== false) {
					if ($row['Année']) {
						$annee = "<span class='playlist_year'> (".$row['Année'].")</span>";
					}
					$tableBody .= "<tr><td>".$row['Titre']."</td><td>".$row['Album'].$annee."</td></tr>";
				}
			}
			else {
				if ($row['URL']) {
					if ($row['Groupe']) {
						$groupe = "<a href='".$row['URL']."' title='".$row['URL']."' target='_blank'>".$row['Groupe']."</a>";
					}
					else {
						$row['Titre'] = "<a href='".$row['URL']."' title='".$row['URL']."' target='_blank'>".$row['Titre']."</a>";
					}
					
				}
				else {
					$groupe = $row['Groupe'];
				}
				
				if ($row['Année']) {
					$annee = "<span class='playlist_year'> (".$row['Année'].")</span>";
				}
				$tableBody .= "<tr><td>".$row['Titre']."</td><td>".$groupe."</td><td>".$row['Album'].$annee."</td></tr>";
			}	
		}

		$ret = $tableHead.$tableBody.$tableEnd;

		return $ret;
	}

	protected function output($data = null) {
   	$CSVtoTable = new \Twig_SimpleFunction('CSVtoTable', function ($file) {
   		return $this->parse_file($file);
   	});
   	$data['engine']->addFunction($CSVtoTable);
	}

}
