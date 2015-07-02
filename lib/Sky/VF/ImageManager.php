<?php

namespace Sky\VF;

/**
 * @package SkyPHP
 */
class ImageManager
{

    /**
    * Get event flyer, resized with imgix
    */
    public static function get_flyer($eventide, $flyer_type, $w, $h){ 
        global $vfolder_base_url;
        $imgix_base = $vfolder_base_url;
        $imgix_w = "";
        $imgix_h = "";
        if(!is_null($w)){
            $imgix_w = "&w=".$w;
        }
        if(!is_null($h)){
            $imgix_h = "&h=".$h;
        }
        $imgix_params = "?fit=crop".$imgix_w.$imgix_h;

        $this_event = new \Crave\Model\ct_event($eventide);
        $media_items = $this_event->media_items;
        $media = json_decode($media_items);

        $flyers = [] ;
        if($media){
            foreach ($media as $type => $image) {
                $flyers[$type] = $image; 
            } 
        }
        $flyer = "";
        if(isset($flyers[$flyer_type]) && $flyers[$flyer_type] != ""){
            $flyer = $imgix_base . "events/" . $eventide . "/". $flyer_type ."/" . $flyers[$flyer_type] . $imgix_params;
        }

        return $flyer;
    }

    /**
    * Get a single venue image from the new system, with featured as priority
    * This system uses imgix for image manipulation
    */
    public static function get_venue_image($venueide, $w, $h){
        $venue = new \Crave\Model\venue($venueide);

        $media_items = $venue->media_items;
        $media = json_decode($media_items);

        if(!is_null($media) && !empty($media)){
            if(stripos($media_items, 'featured":"1"')){
                foreach($media as $image){
                    if($image->featured == 1){
                        $img = self::get_venue_image_src($image, $w, $h);
                        return $img;
                    }
                }
            }else{
                $image = $media[0];
                $img = self::get_venue_image_src($image, $w, $h);
                return $img;
            }
        }

        return false;
    }

    /**
    * Get venue image imgix url
    * Must pass a single $image object from a media_items array
    */
    public static function get_venue_image_src($image, $w, $h){
        global $vfolder_base_url;
        $imgix_base = $vfolder_base_url;
        $imgix_w = "";
        $imgix_h = "";
        if(!is_null($w)){
            $imgix_w = "&w=".$w;
        }
        if(!is_null($h)){
            $imgix_h = "&h=".$h;
        }
        $imgix_params = "?fit=crop".$imgix_w.$imgix_h;

        $img = new \stdClass();
        $img->src = $imgix_base . $image->venue_ide . "/" . $image->name . $imgix_params;

        return $img;
    }

    /** LEGACY **
    * Get a single venue image from the new system, with featured as priority
    * This is the old way, by using the vf::getItem function to manipulate the image
    */
    public static function get_venue_image_vf($venueide, $w, $h){
        $venue = new \Crave\Model\venue($venueide);

        $media_items = $venue->media_items;
        $media = json_decode($media_items);

        if(!is_null($media) && !empty($media)){
            if(stripos($media_items, 'featured":"1"')){
                foreach($media as $image){
                    if($image->featured == 1){
                        $config = [
                            'width' => $w,
                            'height' => $h,
                            'crop' =>true,
                            'resize'=>true
                        ];

                        $params = [
                            'ide'=>$venueide,
                            'filename'=>$image->name,
                            'config'=>$config,
                            'type'=>'venue'
                        ];

                        $img = \vf::getItem(181847, 0, 0, 0, "v2", $params);

                        return $img;
                    }
                }
            }else{
                $image = $media[0];

                $config = [
                    'width' => $w,
                    'height' => $h,
                    'crop' =>true,
                    'resize'=>true
                ];

                $params = [
                    'ide'=>$venueide,
                    'filename'=>$image->name,
                    'config'=>$config,
                    'type'=>'venue'
                ];

                $img = \vf::getItem(181847, 0, 0, 0, "v2", $params);

                return $img;
            }
        }

        return false;
    }

}