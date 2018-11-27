<?php
namespace App\Inspections;

class InvalidKeywords
{

    private $invalidKeywords = [
        'yahoo Customer Support'
    ];

    public function detect($body)
    {
        foreach($this->invalidKeywords as $keyword)
        {
            if(stripos($body,$keyword) !== false ) 
            {
                throw new \Exception('Your reply contaions spam.');
            } 
        }
    }

}