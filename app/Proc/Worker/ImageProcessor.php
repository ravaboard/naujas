<?php namespace Proc\Worker;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageProcessor {

    protected $width;
    protected $height;
    protected $image;

    public function fire($job, $data)
    {
           $s3 = \AWS::createClient('s3');

     
		
		try {
   $response = $s3->getObject(array(
            'Bucket'      => $data['bucket'],
            'Key'         => $data['key'],
        ));
} catch (Exception $e) {
   return; 
}

        $imagine = new Imagine();
        $image = $imagine->load( (string)$response->get('Body') );

        $size = new Box(100, 100);
        $thumb = $image->thumbnail($size);

        $s3->putObject(array(
            'Bucket'      => 'bellated',
            'Key'         => $data['hash'].'_100x100.'.$data['ext'],
            'Body'        => $thumb->get($data['ext']),
            'ContentType' => $data['mimetype'],
        ));

        // Probaby save these to a database here

    }

}