<?php
/**************************************************************************************************
 * Top 40 importer                                                                                *
 * Copyright (c) Cas Eliëns 2018                                                                  *
 *                                                                                                *
 * This program is free software: you can redistribute it and/or modify                           *
 * it under the terms of the GNU General Public License as published by                           *
 * the Free Software Foundation, either version 3 of the License, or                              *
 * (at your option) any later version.                                                            *
 *                                                                                                *
 * This program is distributed in the hope that it will be useful,                                *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of                                 *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                                  *
 * GNU General Public License for more details.                                                   *
 *                                                                                                *
 * You should have received a copy of the GNU General Public License                              *
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.                          *
 **************************************************************************************************/

$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
$week = (isset($_GET['week']) && $_GET['week'] <= 53) ? $_GET['week'] : date("W");

$url = "https://www.top40.nl/tipparade/$year/week-$week";

$html = file_get_contents($url);

$dom = new DOMDocument;
$dom->loadHTML($html);
$finder = new DomXPath($dom);
$classname = "new";
$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

$results = "NEW SONGS FOR WEEK $week $year";

foreach ($nodes as $node) {
    $text = $node->textContent;

    $lines = explode("\n", $text);
    $data = [];

    // line 0   date
    // line 1   title
    // line 2   artist

    for ($i = 1; $i <3; $i++) {
        $line = $lines[$i];
        $parsed = preg_replace("/\s{2,}/", " ", $line);
        $data[$i - 1] = $parsed;
    }

    $results = $results . "\n$data[0] BY $data[1]";


}

echo $results;
