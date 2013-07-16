<?php
namespace Cogipix\CogimixDailymotionBundle\Services;

use Cogipix\CogimixCommonBundle\Entity\TrackResult;

class ResultBuilder{


    public function createFromVideoEntry($videoEntry){

        $item = null;
        if(!empty($videoEntry) ){
            $item = new TrackResult();
            $item->setEntryId($videoEntry['id']);
            if(strstr($videoEntry['title'],'-' )!==false){
                $artistTitle = explode('-', $videoEntry['title']);
                $item->setArtist(trim($artistTitle[0]));
                $item->setTitle(trim($artistTitle[1]));
            }else{
                $item->setTitle($videoEntry['title']);

            }

            $item->setThumbnails( $videoEntry['thumbnail_60_url']);
            $item->setTag($this->getResultTag());
            $item->setIcon($this->getDefaultIcon());
        }

        return $item;
    }

    public function createFromVideoEntries($videosEntries){

        $result = array();
        foreach($videosEntries as $videoEntry){
            $item = $this->createFromVideoEntry($videoEntry);
            if($item !== null ){
                $result[] = $item;
            }
        }

        return $result;
    }


    public function getDefaultIcon(){
        return 'bundles/cogimixdailymotion/images/dm-icon.png';
    }

    public function getResultTag(){
        return 'dm';
    }
}