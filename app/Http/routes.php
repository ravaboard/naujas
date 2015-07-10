<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/complete', function()
{
    return View::make('complete');
});

	
	Route::get('lord', function()
{

    $user = getenv('DB_USERNAME');
    dd($user);
});
	


// Upload an image to S3 and
// create a job to process it
Route::post('/', function()
{
    $validator = Validator::make(Input::all(), array(
        'title' => 'required',
        'file'  => 'required|mimes:jpeg,jpg,png',
    ));

    if( $validator->fails() )
    {
        return Redirect::to('/');
    }

    // Upload File
    $file = Input::file('file');

    $now = new DateTime;
    $hash = md5( $file->getClientOriginalName().$now->format('Y-m-d H:i:s') );
    $key = $hash.'.'.$file->getClientOriginalExtension();

    $s3 = AWS::createClient('s3');
	
    $s3->putObject(array(
        'Bucket'      => 'bellated',
        'Key'         => $key,
        'SourceFile'  => $file->getRealPath(),
        'ContentType' => $file->getClientMimeType(),
    ));
	

    // Create job
    Queue::push('\Proc\Worker\ImageProcessor', array(
        'bucket'   => 'bellated',
        'hash'     => $hash,
        'key'      => $key,
        'ext'      => $file->getClientOriginalExtension(),
        'mimetype' => $file->getClientMimeType(),
    ));
	
	 Log::info('queue processed');

    return Redirect::to('/complete');
});