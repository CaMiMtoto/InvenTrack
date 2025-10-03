<?php

use App\Console\Commands\DeleteOldImages;



Schedule::command(DeleteOldImages::class)->twiceDaily(0, 12)->runInBackground()->withoutOverlapping();
