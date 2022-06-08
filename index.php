<?php
    /*
        Merge CSV Data
        May 2022
        **Jenridewany

        *** Format Folder ***
        dir name : dataReport/date/

    */

    //list date
    $arrDate = array("12-05-2022");
    
    //looping data tanggal
    for($i=0; $i<count($arrDate); $i++){
        $date = date("Y-m-d",strtotime($arrDate[$i]));

        $dir = "rawReport/$date/";
        
        $files = scan_dir($dir);
        $tmp = [];
        foreach($files as $file){
            $fileName = $dir.$file;
            # read files
            $data = array_map('str_getcsv', file($fileName));

            // delete header csv
            unset($data[0]);
            $tmp = array_merge($tmp, $data);
        }

        //name can be customized
        $name = "Report [project name] $arrDate[$i].csv";
        createCsv($name, $tmp);
    }

    function scan_dir($dir) {
        $ignored = array('.', '..', '.svn', '.htaccess');
    
        $files = array();    
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }
    
        asort($files);
        $files = array_keys($files);
    
        return ($files) ? $files : false;
    }

    function createCsv($filename, $data){

        // open csv file for writing
        $folder = 'mergedReport/'; 
        if(!file_exists($folder)) mkdir($folder, 770);
        if (file_exists($folder) && !is_writable($folder)) chmod($folder, 0770);
        // open csv file for writing
        $f = fopen($folder.'/'.$filename, 'w');

        if ($f === false) {
            return false;
        }

        //Header can be customized
        fputcsv($f, ["No","Broadcast Date","Bot Name","Nama","Phone","Gender","Status Call","Duration","Label","Payment Method","Payment Date","Transkrip URL"]);
        
        // write each row at a time to a file
        foreach ($data as $row) {
            fputcsv($f, $row);
        }

        // close the file
        fclose($f);
        return true;
    }


    
?>
