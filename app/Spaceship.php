<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


   class Spaceship extends Model {


      public function fire($job, $data)
    {
    // Could be added to database here!
      Log::info('We can put this in the database: ' . print_r($data, TRUE));
      $job->delete();
    }
    }
