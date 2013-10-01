<?php
namespace Cogipix\CogimixDailymotionBundle\Services;

use Cogipix\CogimixCommonBundle\Entity\TrackResult;
use Cogipix\CogimixCommonBundle\MusicSearch\AbstractMusicSearch;

class DailymotionMusicSearch extends AbstractMusicSearch{

    private $dailymotionService;
    private $videoQuery;
    private $resultBuilder;

    public function __construct(ResultBuilder $resultBuilder){
        $this->resultBuilder=$resultBuilder;
        $this->dailymotionService=new  \Dailymotion();


    }

    protected function parseResponse($feeds){

        $result = array();
        if(isset($feeds['list'])){
            $result = $this->resultBuilder->createFromVideoEntries($feeds['list']);
        }

        $this->logger->info('Dailymotion return '.count($result).' results');
        return $result;
    }

    protected function executeQuery(){
        $this->logger->info('Dailymotion executeQuery');
        $feeds = array();
        try{
            $feeds= $this->dailymotionService->get('/videos',$this->videoQuery);
            //var_dump($feeds);
        }catch(\Exception $ex){
            $this->logger->err($ex);
            return array();
        }

        return $this->parseResponse($feeds);

    }

    protected function executePopularQuery(){


        $this->logger->info('Dailymotion executePopularQuery');
        $this->videoQuery = array('fields'=>array('id','title','thumbnail_60_url'),'filters'=>array('hd'), 'channel'=>'music','sort'=>'visited-week','limit'=>30);
        try{
             $feeds= $this->dailymotionService->get('/videos',$this->videoQuery);

        }catch(\Exception $ex){
            $this->logger->err($ex);
            return array();
        }

        return $this->parseResponse($feeds);

    }

    protected function buildQuery(){

        $this->videoQuery = array('fields'=>array('id','title','thumbnail_60_url'),'channel'=>'music','search'=>$this->searchQuery->getSongQuery(),'limit'=>30);

    }

    public function  getName(){
        return 'Dailymotion';
    }

    public function  getAlias(){
        return 'dmservice';
    }

    public function getDefaultIcon(){
        return '/bundles/cogimixdailymotion/images/dm-icon.png';
    }

    public function getResultTag(){
        return 'dm';
    }


}

?>