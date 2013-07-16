<?php
namespace Cogipix\CogimixDailymotionBundle\Services;


use Cogipix\CogimixCommonBundle\Model\ParsedUrl;

use Cogipix\CogimixDailymotionBundle\Services\ResultBuilder;
use Cogipix\CogimixCommonBundle\MusicSearch\UrlSearcherInterface;

class DailymotionUrlSearch implements UrlSearcherInterface
{
    private $regexHost = '#^(?:www\.)?(?:dailymotion\.com)#';
    private $resultBuilder;

    public function __construct(ResultBuilder $resultBuilder){
        $this->resultBuilder = $resultBuilder;
    }


    public function canParse($host)
    {

        preg_match($this->regexHost, $host,$matches);

       return isset($matches[0]) ? $matches[0] : false;

    }

    public function searchByUrl(ParsedUrl $url)
    {

        if( ($match = $this->canParse($url->host)) !== false){

            $dailymotion = new \Dailymotion();

            $result = null;

                if($url->path[0] == 'swf' || $url->path[0] == 'video' ){

                    $result=$dailymotion->get('/videos',array('fields'=>array('id','title','thumbnail_60_url'),'ids'=>array(end($url->path) ) ));
                }else{
                    $path=end($url->path);
                    $lastPath=explode('_', $path);

                    $result=$dailymotion->get('/videos',array('fields'=>array('id','title','thumbnail_60_url'),'ids'=>array($lastPath[0]) ) );

                }

            if($result != null && isset($result['list'])){
                return  $this->resultBuilder->createFromVideoEntries($result['list']);
            }
        }else{
            return null;
        }


    }

}
