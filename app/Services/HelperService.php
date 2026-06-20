<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class HelperService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function extractTagContent(string $html):array
    {
        $dom = new DOMDocument();
        // suppress warning dari HTML yang gak strict + handle UTF-8
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html
        );

        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//body//*'); // ambil semua child element langsung di body

        $result = [];
        foreach($nodes as $node){
            $result[] = [
                'tag' => $node->nodeName,
                'text' => trim($node->textContent),
            ];
        }

        return $result;
    }


    public function getContent(string $content, float $percentage){
        $arrayContent = $this->extractTagContent($content);
        $contentPercentage = round(count($arrayContent) * ($percentage/100));

        $result = array_slice($arrayContent,0 , (int)$contentPercentage);
        $arrayTemp = array_map(function($item){
            if($item['tag'] == 'p'){
                return '<p>' . $item['text'] . '</p>';
            }else{
                return '<blockqoute>' . $item['text'] . '</blockqoute>';
            }
        },$result);

        return implode($arrayTemp);
        
    }
    
}
