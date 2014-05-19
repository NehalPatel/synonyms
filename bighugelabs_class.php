<?php

/**
 * Class BigHugeLabs
 * Get synonyms of keywords and generate a csv file
 */

class BigHugeLabs{

    private $filename;
    private $base_url = 'http://words.bighugelabs.com/api/2';
    private $api_key = 'ee6d63c27c3bd86cdc9b3cc43d64d2e7';

    function __construct(){

    }

    private function generateFileName(){
        $this->filename = 'synonyms_'. time() .'.csv';
        return $this->filename;
    }

    private function writeToFile($keyword, $words){
        if (file_exists('tmp\\' . $this->filename))
            $fh = fopen('tmp\\' .$this->filename, 'a');
        else
            $fh = fopen('tmp\\' .$this->filename, 'w');

        fputcsv($fh, $words);
        fclose($fh);
    }

    private function getSynonyms($keyword, $format = 'json'){
        $url = $this->base_url.'/'.$this->api_key.'/'.$keyword.'/'.$format;
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        curl_close ($ch);
        return $output;
    }

    public function getKeywordSynonyms($keyword){
        $keyword = trim($keyword);
        if(empty($keyword))
            return false;

        try {
            $output = $this->getSynonyms($keyword);
            $output = json_decode($output,true);

            /*IF any input miss the data from target, again make a call*/
            if(empty($output)){
                $output = $this->getSynonyms($keyword);
                $output = json_decode($output,true);
            }
            return $output;
        } catch (Exception $e) {
            //header('Content-Type: application/json');
            return array('status' => false, 'error_message' => $e->getMessage());
        }
    }

    public function generateCSV($keywords){

        $result_keywords = array();
        $raw_keywords = array('&', '(', ')');
        try {
            $this->generateFileName();  //generate a random csv file name

            foreach($keywords as $key => $keyword_str){

                foreach($raw_keywords as $rk){
                    $keyword_str = str_replace($rk, "", $keyword_str);
                }

                //echo $keyword_str .'<br>';continue;

                $keywords = preg_split('/\s+/', $keyword_str);  //if keyword are combination of multiple words

                foreach($keywords as $keyword){

                    $keyword = strtolower($keyword);
                    if(in_array($keyword, $result_keywords)){
                        continue;
                    }
                    $result_keywords[] = $keyword;

                    $output = $this->getKeywordSynonyms($keyword);

                    $result = array($keyword);

                    if(isset($output['noun']['syn'])){
                        $result = array_merge($result, array('NOUNS'), $output['noun']['syn']);
                    }

                    if(isset($output['verb']['syn'])){
                        $result = array_merge($result, array('VERBS'), $output['verb']['syn']);
                    }
                    //echo '<pre>';print_r($result);echo '</pre>';exit;
                    $this->writeToFile($keyword, $result);
                }
            }
            return array('status' => true, 'file' => $this->filename);
        } catch (Exception $e) {
            return array('status' => false, 'error_message' => $e->getMessage());
        }
    }
}