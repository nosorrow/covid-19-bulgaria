<?php

include "../../PhpSpreadsheet/vendor/autoload.php";
function readExelData()
{
    $file = "../covid-19.xlsx";

    $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($file);

    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $titles = array_map(function ($e) {
        return lcfirst($e);

    }, reset($sheetData));

    $data = end($sheetData);


    $date = new DateTime(array_shift($data));

    $date = $date->add(new DateInterval('PT10H'));

    $data = array_map(function ($e) {
        return (float)$e;
    }, $data);

    $data['A'] = $date->format("c");
    ksort($data);

    $toJson = ["today" => array_combine($titles, $data)];

    $json = json_encode($toJson, 256);

    $dataToExport = trim(var_export($json, true), "'");

    file_put_contents('../data/data-covid.json', $dataToExport);

    print_r($json);
}
