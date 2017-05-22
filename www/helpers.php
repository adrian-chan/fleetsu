<?php
class helpers {

    static function getFromCsv($file, $delimiter = ',')
    {
        if (($handle = fopen($file, 'r')) === false) {
            die('Error opening file');
        }

        $headers = fgetcsv($handle, 4000, $delimiter);
        $csv = array();

        while ($row = fgetcsv($handle, 4000, $delimiter)) {
            $csv[] = array_combine($headers, $row);
        }

        fclose($handle);
        return $csv;
    }

}
?>