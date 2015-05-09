<?php

namespace Phile\Plugin\bricebou\ecParseCSV;

/**
 *
 * @author Brice Boucard
 * @link https://github.com/bricebou/ecParseCSV
 * @license http://bricebou.mit-license.org/
 */
class Plugin extends \Phile\Plugin\AbstractPlugin {
	private $tag_ok;
  private $tag_name;

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
    $this->tag_ok = ($dname == "tag");

    // If the URL does start with 'tag/', grab the rest of the URL
    $tag_name_raw = basename($uri);

    if ($this->tag_ok)
        $this->tag_name = htmlentities(urldecode($tag_name_raw), 0, "UTF-8");
  }

	protected function parse_file($file) {

		$csv = new \parseCSV();
		$csv->delimiter = ";";
		$csv->parse($file);

		if ($this->tag_ok) {
			$tableHead = "<div  class='table-respond'><table><thead><tr><th>Titre</th><th>Album</th></tr></thead><tbody>";
		}
		else {
			$tableHead = "<div  class='table-respond'><table><thead><tr><th>Titre</th><th>Artiste</th><th>Album</th></tr></thead><tbody>";
		}
		$tableEnd = "</tbody></table></div>";
		$tableBody = "";

		foreach ($csv->data as $key => $row) {
			$annee = "";
			$groupe = "";

			if ($this->tag_ok) {

				if (isset($row['Groupe']) && strpos(html_entity_decode($row['Groupe']), html_entity_decode($this->tag_name)) !== false) {
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
